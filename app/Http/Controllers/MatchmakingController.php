<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MatchRecord;
use App\Models\MatchCompatibility;
use App\Models\MatchDate;
use App\Models\MatchFeedback;
use App\Models\Client;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class MatchmakingController extends Controller
{
    public function index()
    {
        $clients = Client::whereHas('payment', function($query) {
            $query->where('status', 'paid');
        })->get();
        return view('matchmaking.index', compact('clients'));
    }

    public function getMatches()
    {
        $matches = MatchRecord::with('client')->select('matches.*');
        return DataTables::of($matches)
            ->addColumn('client_name', function ($match) {
                return $match->client ? $match->client->full_name : 'N/A';
            })
            ->addColumn('action', function ($match) {
                return '<a href="' . route('matchmaking.edit', $match->id) . '" class="btn btn-sm btn-primary">Edit Match</a>';
            })
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'candidate_name' => 'required|string|max:255',
        ]);

        $match = MatchRecord::create([
            'client_id' => $request->client_id,
            'candidate_name' => $request->candidate_name,
            'status' => 'identified',
        ]);

        // Create associated empty records
        MatchCompatibility::create(['match_id' => $match->id]);
        MatchDate::create(['match_id' => $match->id]);
        MatchFeedback::create(['match_id' => $match->id]);

        Activity::create([
            'user_id' => Auth::id(),
            'action' => 'created match',
            'subject_type' => MatchRecord::class,
            'subject_id' => $match->id,
            'description' => 'Match created for client ' . $match->client_id . ' with candidate ' . $match->candidate_name
        ]);

        return redirect()->route('matchmaking.edit', $match->id)->with('success', 'Match created successfully.');
    }

    public function edit($id)
    {
        $match = MatchRecord::with(['client', 'compatibility', 'date', 'feedback'])->findOrFail($id);
        $status = ['identified', 'reviewed', 'proposed', 'approved', 'scheduled', 'completed', 'logged'];
        return view('matchmaking.edit', compact('match', 'status'));
    }

    public function update(Request $request, $id)
    {
        $match = MatchRecord::findOrFail($id);

        $match->update([
            'status' => $request->status,
        ]);

        $match->compatibility()->update([
            'values_score' => $request->values_score,
            'lifestyle_score' => $request->lifestyle_score,
            'goal_alignment' => $request->goal_alignment,
            'deal_breaker_check' => $request->deal_breaker_check,
            'notes' => $request->compatibility_notes,
        ]);

        $match->date()->update([
            'date_time' => $request->date_time,
            'location' => $request->location,
            'status' => $request->date_status,
            'notes' => $request->date_notes,
        ]);

        $match->feedback()->update([
            'client_feedback' => $request->client_feedback,
            'candidate_feedback' => $request->candidate_feedback,
            'rating' => $request->rating,
            'notes' => $request->feedback_notes,
        ]);

        Activity::create([
            'user_id' => Auth::id(),
            'action' => 'updated match',
            'subject_type' => MatchRecord::class,
            'subject_id' => $match->id,
            'description' => 'Match details updated'
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Match details updated successfully.']);
        }

        return redirect()->route('matchmaking.edit', $match->id)->with('success', 'Match details updated successfully.');
    }
}
