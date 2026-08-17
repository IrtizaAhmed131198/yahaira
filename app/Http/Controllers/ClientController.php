<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    public function index()
    {
        return view('client-intake-application.index');
    }

    public function getClients(Request $request)
    {
        if ($request->ajax()) {
            $data = Client::with('payment')->select('clients.*');
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    if ($row->status === 'active') {
                        return '<span class="badge bg-success">Active</span>';
                    }
                    return '<span class="badge bg-secondary">'.ucfirst($row->status).'</span>';
                })
                ->addColumn('action', function ($row) {
                    $user = Auth::user();
                    $isPaid = $row->payment && $row->payment->status === 'paid';

                    $buttons = '<a href="'.route('client-profile', $row->id).'" class="btn web-btn me-2" style="background-color: #6c757d; border-color: #6c757d;"><i class="fa-solid fa-user"></i> View Profile</a>';

                    // if ($user->hasRole('closer') && !$isPaid) {
                    //     $buttons .= '<button class="btn btn-secondary btn-sm" disabled title="Payment not paid"><i class="fa-solid fa-lock"></i> Locked</button>';
                    // } else {
                        $buttons .= '<a href="'.route('client-intake-application.edit', $row->id).'" class="btn web-btn"><i class="fa-solid fa-edit"></i> Edit</a>';
                    // }

                    return $buttons;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
    }

    public function edit($id)
    {
        $client = Client::with(['payment', 'photos'])->findOrFail($id);

        $user = Auth::user();
        $isPaid = $client->payment && $client->payment->status === 'paid';

        // if ($user->hasRole('closer') && !$isPaid) {
        //     abort(403, 'You can only edit this client after their payment is fully paid.');
        // }

        return view('client-intake-application.edit', compact('client'));
    }

    public function update(Request $request, $id)
    {
        $client = Client::findOrFail($id);

        $user = Auth::user();
        $isPaid = $client->payment && $client->payment->status === 'paid';

        // if ($user->hasRole('closer') && !$isPaid) {
        //     abort(403, 'You can only edit this client after their payment is fully paid.');
        // }

        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120'
        ]);

        $client->update($request->except(['_token', '_method', 'photos']));

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                $path = $file->store('client_photos', 'public');
                $client->photos()->create(['file_path' => $path]);
            }
        }

        Activity::create([
            'user_id' => Auth::id(),
            'action' => 'updated client intake',
            'subject_type' => Client::class,
            'subject_id' => $client->id,
            'description' => 'Client intake details updated for ' . $client->full_name
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Client details updated successfully!'
            ]);
        }

        return redirect()->back()->with('success', 'Client details updated successfully!');
    }

    public function deletePhoto($id)
    {
        $photo = \App\Models\ClientPhoto::findOrFail($id);

        $client = $photo->client;
        $user = Auth::user();
        $isPaid = $client->payment && $client->payment->status === 'paid';
        // if ($user->hasRole('closer') && !$isPaid) {
        //     return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        // }

        if (Storage::disk('public')->exists($photo->file_path)) {
            Storage::disk('public')->delete($photo->file_path);
        }
        $photo->delete();

        Activity::create([
            'user_id' => Auth::id(),
            'action' => 'deleted client photo',
            'subject_type' => Client::class,
            'subject_id' => $client->id,
            'description' => 'Photo deleted for client ' . $client->full_name
        ]);

        return response()->json(['success' => true]);
    }

    public function show($id)
    {
        $client = Client::with(['photos', 'deal.closer', 'payment.package'])->findOrFail($id);
        // Also fetch activities if needed, though they are currently polymorphic
        $activities = Activity::where('subject_type', Client::class)
            ->where('subject_id', $client->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('client-profile', compact('client', 'activities'));
    }
}
