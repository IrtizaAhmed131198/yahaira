@extends('layouts.app')

@section('title', 'Matchmaking & Scheduling')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
@endpush

@section('content')
<div class="col-lg-9 col-md-9 col-12 p-0">
    <div class="dashboard-layout">
        <div class="dashboard-top">
            <div class="dashboard-heading">
                <h4> Matchmaking & Scheduling</h4>
                <p>Candidates, compatibility, dates, feedback</p>
            </div>
        </div>
        
        <div class="box-info-detail">
            <div class="d-flex justify-content-between mb-3">
                <button type="button" class="btn btn-primary web-btn" data-bs-toggle="modal" data-bs-target="#addMatchModal">
                    + Add New Match
                </button>
            </div>
            
            <div class="time-follow">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="matchesTable">
                        <thead>
                            <tr>
                                <th>Client Name</th>
                                <th>Candidate Name</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Match Modal -->
<div class="modal fade" id="addMatchModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('matchmaking.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Identify New Candidate</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Select Client</label>
                        <select name="client_id" class="form-control" required>
                            <option value="">-- Select Client --</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Candidate Name</label>
                        <input type="text" name="candidate_name" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary web-btn">Start Match Process</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    $('#matchesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("matchmaking.data") }}',
        columns: [
            { data: 'client_name', name: 'client_name' },
            { data: 'candidate_name', name: 'candidate_name' },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
});
</script>
@endpush
