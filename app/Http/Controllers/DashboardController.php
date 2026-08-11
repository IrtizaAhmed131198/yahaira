<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Deal;
use App\Models\MatchDate;
use App\Models\Lead;
use App\Models\Client;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        // 1. Today's Calls & Sessions
        $dealsToday = Deal::whereDate('consultation_at', $today)->count();
        $matchDatesToday = MatchDate::whereDate('date_time', $today)->count();
        $callsToday = $dealsToday + $matchDatesToday;

        // 2. This Week's Schedule
        $dealsWeek = Deal::whereBetween('consultation_at', [$startOfWeek, $endOfWeek])->count();
        $matchDatesWeek = MatchDate::whereBetween('date_time', [$startOfWeek, $endOfWeek])->count();
        $thisWeek = $dealsWeek + $matchDatesWeek;

        // 3. Active Leads / Clients based on role
        $activeLeadsClients = 0;
        if ($user->hasRole('admin')) {
            $activeLeadsClients = Lead::where('status', 'active')->count() + Client::where('status', 'active')->count();
        } elseif ($user->hasRole('setter')) {
            $activeLeadsClients = Lead::where('assigned_setter_id', $user->id)->where('status', 'active')->count();
        } elseif ($user->hasRole('closer')) {
            $activeLeadsClients = Deal::where('assigned_closer_id', $user->id)->whereNotIn('status', ['lost'])->count(); // Assuming deals that aren't lost mean active leads
        } else {
            // Default for other roles
            $activeLeadsClients = 0;
        }

        // 4. Today's Agenda (Merge Deals and Match Dates)
        $agenda = collect();

        $todayDeals = Deal::with(['lead', 'closer'])->whereDate('consultation_at', $today)->get();
        foreach ($todayDeals as $deal) {
            $agenda->push([
                'title' => 'Discovery Call — ' . ($deal->lead ? $deal->lead->full_name : 'Unknown'),
                'details' => ($deal->zoom_link ? 'Zoom' : 'Call') . ' · Closer: ' . ($deal->closer ? $deal->closer->name : 'Unassigned'),
                'time' => Carbon::parse($deal->consultation_at)->format('h:i A'),
                'sort_time' => Carbon::parse($deal->consultation_at)
            ]);
        }

        $todayMatchDates = MatchDate::with('matchRecord')->whereDate('date_time', $today)->get();
        foreach ($todayMatchDates as $matchDate) {
            $match = $matchDate->matchRecord;
            $clientName = $match ? $match->client_id : 'Unknown'; // Normally would join client table for full name, but client_id is a placeholder for now
            if ($match && $match->client) {
                $clientName = $match->client->full_name;
            }
            $candidateName = $match ? $match->candidate_name : 'Unknown';
            
            $agenda->push([
                'title' => 'Introduction Date — ' . $clientName . ' & ' . $candidateName,
                'details' => ($matchDate->location ?? 'In person') . ' · Matchmaker',
                'time' => Carbon::parse($matchDate->date_time)->format('h:i A'),
                'sort_time' => Carbon::parse($matchDate->date_time)
            ]);
        }

        $agenda = $agenda->sortBy('sort_time')->values();

        return view('dashboard', compact('callsToday', 'thisWeek', 'activeLeadsClients', 'agenda', 'today'));
    }
}
