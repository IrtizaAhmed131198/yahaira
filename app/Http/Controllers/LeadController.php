<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\LeadNote;
use App\Models\Activity;
use App\Models\User;
use App\Models\Deal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class LeadController extends Controller
{
    public function index()
    {
        // Get setters and closers for assignment dropdowns if needed
        $setters = User::role('setter')->get();
        return view('lead-management', compact('setters'));
    }

    public function getLeads(Request $request)
    {
        $query = Lead::with('setter')->orderBy('created_at', 'desc');

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // If setter, only show their leads
        if (Auth::user()->hasRole('setter') && !Auth::user()->hasRole('admin')) {
            $query->where('assigned_setter_id', Auth::id());
        }

        // Status filter
        if ($request->has('status') && !empty($request->status)) {
            $status = strtolower($request->status);
            if ($status === 'handed_off') {
                $query->where(function($q) {
                    $q->where('status', 'handed_off')
                      ->orWhere('status', 'like', '%hand%')
                      ->orWhere('status', 'like', '%closer%');
                });
            } else {
                $query->where('status', $status);
            }
        }

        $leads = $query->paginate(15);

        return response()->json([
            'success' => true,
            'status' => $request->status,
            'leads' => $leads->items(),
            'pagination' => [
                'current_page' => $leads->currentPage(),
                'last_page' => $leads->lastPage(),
                'total' => $leads->total()
            ]
        ]);
    }

    public function show($id)
    {
        $lead = Lead::with('setter')->findOrFail($id);

        // Authorization check for setters
        if (Auth::user()->hasRole('setter') && !Auth::user()->hasRole('admin') && $lead->assigned_setter_id != Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access to this lead.'], 403);
        }
        
        $notes = $lead->notes()->with('user')->orderBy('created_at', 'desc')->paginate(10);

        return response()->json([
            'success' => true,
            'lead' => $lead,
            'notes' => $notes->items(),
            'notes_pagination' => [
                'current_page' => $notes->currentPage(),
                'last_page' => $notes->lastPage(),
                'total' => $notes->total()
            ]
        ]);
    }

    public function getNotesData($id)
    {
        $lead = Lead::findOrFail($id);

        // Authorization check for setters
        if (Auth::user()->hasRole('setter') && !Auth::user()->hasRole('admin') && $lead->assigned_setter_id != Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access to this lead.'], 403);
        }

        $notes = $lead->notes()->with('user')->orderBy('created_at', 'desc')->paginate(10);

        return response()->json([
            'success' => true,
            'notes' => $notes->items(),
            'notes_pagination' => [
                'current_page' => $notes->currentPage(),
                'last_page' => $notes->lastPage(),
                'total' => $notes->total()
            ]
        ]);
    }

    public function store(Request $request)
    {
        if (Auth::user()->hasRole('admin')) {
            return response()->json(['success' => false, 'message' => 'Admins have view-only access.'], 403);
        }

        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $lead = Lead::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'source' => $request->source ?? 'Manual',
            'status' => 'new',
            'assigned_setter_id' => Auth::user()->hasRole('setter') ? Auth::id() : null, // Auto-assign to self if setter
        ]);

        $this->logActivity('created lead', Lead::class, $lead->id, 'Lead created manually');

        return response()->json([
            'success' => true,
            'message' => 'Lead created successfully',
            'lead' => $lead
        ]);
    }

    public function update(Request $request, $id)
    {
        if (Auth::user()->hasRole('admin')) {
            return response()->json(['success' => false, 'message' => 'Admins have view-only access.'], 403);
        }

        $lead = Lead::findOrFail($id);

        if (Auth::user()->hasRole('setter') && $lead->assigned_setter_id != Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $oldStatus = $lead->status;

        // Update all fillable fields provided in the request
        $fillable = ['full_name', 'email', 'phone', 'timezone', 'interested_service', 'budget_range', 'next_followup_at', 'status'];
        foreach ($fillable as $field) {
            if ($request->has($field)) {
                $lead->$field = $request->$field;
            }
        }

        $lead->save();

        if ($oldStatus != $lead->status) {
            $this->logActivity('status changed', Lead::class, $lead->id, "Status changed from {$oldStatus} to {$lead->status}");

            if ($lead->status === 'handed_off') {
                Deal::firstOrCreate(
                    ['lead_id' => $lead->id],
                    ['status' => 'assigned']
                );
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Lead updated successfully'
        ]);
    }

    public function addNote(Request $request, $id)
    {
        if (Auth::user()->hasRole('admin')) {
            return response()->json(['success' => false, 'message' => 'Admins have view-only access.'], 403);
        }

        $request->validate([
            'note' => 'required|string',
        ]);

        $lead = Lead::findOrFail($id);

        if (Auth::user()->hasRole('setter') && $lead->assigned_setter_id != Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $note = LeadNote::create([
            'lead_id' => $lead->id,
            'user_id' => Auth::id(),
            'note' => $request->note
        ]);

        $this->logActivity('added note', LeadNote::class, $note->id, 'Added note to lead: ' . $lead->full_name);

        return response()->json([
            'success' => true,
            'message' => 'Note added successfully',
            'note' => $note->load('user') // load relationship for immediate rendering
        ]);
    }

    private function logActivity($action, $subjectType, $subjectId, $description)
    {
        Activity::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
            'description' => $description
        ]);
    }
}
