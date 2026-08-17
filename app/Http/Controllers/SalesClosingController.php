<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Deal;
use App\Models\Client;
use App\Models\Payment;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;

class SalesClosingController extends Controller
{
    public function index()
    {
        return view('sales-closing');
    }

    public function getDeals(Request $request)
    {
        $query = Deal::with('lead');

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->whereHas('lead', function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $deals = $query->paginate(15);

        return response()->json([
            'success' => true,
            'status' => $request->status,
            'deals' => $deals->items(),
            'pagination' => [
                'current_page' => $deals->currentPage(),
                'last_page' => $deals->lastPage(),
                'total' => $deals->total()
            ]
        ]);
    }

    public function show($id)
    {
        $deal = Deal::with(['lead', 'client.photos', 'client.payment'])->findOrFail($id);

        if (in_array($deal->status, ['booked', 'won']) && !$deal->client) {
            $lead = $deal->lead;
            if ($lead) {
                \App\Models\Client::firstOrCreate(
                    ['deal_id' => $deal->id],
                    [
                        'lead_id' => $lead->id,
                        'full_name' => $lead->full_name,
                        'email' => $lead->email,
                        'phone' => $lead->phone,
                        'timezone' => $lead->timezone,
                        'status' => 'active',
                    ]
                );
                // Reload client relationship
                $deal->load(['client.photos', 'client.payment']);
            }
        }

        return response()->json([
            'success' => true,
            'deal' => $deal
        ]);
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->hasRole('admin')) {
            return response()->json(['success' => false, 'message' => 'Admins have view-only access.'], 403);
        }

        $deal = Deal::findOrFail($id);
        $oldStatus = $deal->status;

        $fillable = ['consultation_at', 'zoom_link', 'notes', 'status'];
        foreach ($fillable as $field) {
            if ($request->has($field)) {
                $deal->$field = $request->$field;
            }
        }

        // Set the assigned closer if not already assigned
        if (!$deal->assigned_closer_id && Auth::user()->hasRole('closer')) {
            $deal->assigned_closer_id = Auth::id();
        }

        $deal->save();

        if ($request->has('status') && in_array($request->status, ['booked', 'won', 'proposal'])) {
            $lead = $deal->lead;
            if ($lead) {
                // Creates a client if it doesn't already exist (e.g. on booked)
                $client = Client::firstOrCreate(
                    ['deal_id' => $deal->id],
                    [
                        'lead_id' => $lead->id,
                        'full_name' => $lead->full_name,
                        'email' => $lead->email,
                        'phone' => $lead->phone,
                        'timezone' => $lead->timezone,
                        'status' => 'active',
                    ]
                );

                // Only create payment record if it's actually won
                if ($request->status === 'proposal') {
                    Payment::firstOrCreate(
                        ['client_id' => $client->id],
                        [
                            'amount' => 0.00,
                            'status' => 'invoice_sent',
                        ]
                    );
                }
            }
        }

        Activity::create([
            'user_id' => Auth::id(),
            'action' => 'updated deal',
            'subject_type' => Deal::class,
            'subject_id' => $deal->id,
            'description' => 'Deal updated for lead ' . ($deal->lead->full_name ?? 'Unknown') . ' to status ' . $deal->status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Deal updated successfully',
            'deal' => $deal
        ]);
    }
}
