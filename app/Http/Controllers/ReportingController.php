<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportingController extends Controller
{
    public function index()
    {
        $sevenDaysAgo = Carbon::now()->subDays(7);

        // 1. New Leads (7 Days)
        $newLeads7Days = Lead::where('created_at', '>=', $sevenDaysAgo)->count();

        // 2. Lead -> Qualified Rate
        $totalLeads = Lead::count();
        $qualifiedOrHigher = Lead::whereIn('status', ['qualified', 'handed_off'])->count();
        $leadQualifiedRate = $totalLeads > 0 ? round(($qualifiedOrHigher / $totalLeads) * 100) : 0;

        // 3. Active Clients in Matching
        $activeClientsCount = Client::where('status', 'active')->count();

        // 4. Pipeline Summary
        $pipelineNew = Lead::where('status', 'new')->count();
        $pipelineQualified = Lead::where('status', 'qualified')->count();
        $pipelineCloserQueue = Lead::where('status', 'handed_off')->count();
        // $activeClientsCount already calculated

        // 5. Lead Source Performance
        $sourcesData = Lead::select('source', DB::raw('count(*) as total'))
            ->groupBy('source')
            ->get();
            
        $sourcePerformance = [];
        foreach ($sourcesData as $data) {
            $source = $data->source ?: 'Unknown';
            $percentage = $totalLeads > 0 ? round(($data->total / $totalLeads) * 100) : 0;
            $sourcePerformance[$source] = $percentage;
        }

        return view('reporting.index', compact(
            'newLeads7Days',
            'leadQualifiedRate',
            'activeClientsCount',
            'pipelineNew',
            'pipelineQualified',
            'pipelineCloserQueue',
            'sourcePerformance'
        ));
    }
}
