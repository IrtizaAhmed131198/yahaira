@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="col-lg-9 col-md-9 col-12 p-0">
        <div class="dashboard-layout">
            <div class="dashboard-top">
                <div class="dashboard-heading">
                    <h4>My Dashboard</h4>
                    <p>
                        Personal to-do list for the day
                    </p>
                </div>
            </div>
            <div class="box-info-detail">
                {{-- <div class="top-notification">
                    <p>Every user's home screen. Shows only what's assigned to them — their calls, their
                        sessions,
                        their follow-ups. Nothing here is sent automatically; it's a manual to-do list built
                        from
                        what's on the calendar.
                    </p>
                </div> --}}
                <div class="activety-details">
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-12">
                            <div class="box-achivement">
                                <h4>{{ $callsToday }}</h4>
                                <p>Today's Calls & Sessions</p>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-12">
                            <div class="box-achivement">
                                <h4>{{ $thisWeek }}</h4>
                                <p>This Week's Schedule</p>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-12">
                            <div class="box-achivement">
                                <h4>{{ $followUpsCount }}</h4>
                                <p>Follow-Ups Due</p>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-12">
                            <div class="box-achivement">
                                <h4>{{ $activeLeadsClients }}</h4>
                                <p>My Active Leads / Clients</p>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-12">
                            <div class="search-info position-relative">
                                <form autocomplete="off" onsubmit="event.preventDefault();">
                                    <input type="search" name="q" class="form-control"
                                        placeholder="Search leads, clients…" id="globalSearchInput">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </form>
                                <div id="globalSearchResults" class="dropdown-menu w-100" style="display: none; position: absolute; top: 100%; left: 0; z-index: 1000;">
                                    <!-- Results will be injected here -->
                                </div>
                                <div class="add-user">
                                    @if(Auth::user()->hasRole('setter'))
                                        <button class="btn web-btn" data-bs-toggle="modal" data-bs-target="#addLeadModal">Add New Lead</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="time-follow">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-12">
                            <div class="day-time">
                                <h4>Today — {{ $today->format('l, F j') }}</h4>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-12">
                            <div class="meeting-time">
                                <ul>
                                    @forelse($agenda as $item)
                                    <li>
                                        <h6>{{ $item['time'] }} · {{ $item['title'] }}</h6>
                                        <p>{{ $item['details'] }}</p>
                                    </li>
                                    @empty
                                    <li>
                                        <h6 class="text-muted">No calls or sessions scheduled for today.</h6>
                                    </li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-12">
                            <div class="meeting-time">
                                <h5>Follow-Ups Due</h5>
                                <ul>
                                    @forelse($followUps as $followUp)
                                        <li>
                                            <h6>Pending Follow-Up — {{ $followUp->lead ? $followUp->lead->full_name : 'Unknown' }}</h6>
                                            <p>Note: {{ Str::limit($followUp->notes, 60) }}</p>
                                        </li>
                                    @empty
                                        <li>
                                            <h6 class="text-muted">No pending follow-ups.</h6>
                                        </li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Lead Modal -->
    <div class="modal fade" id="addLeadModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Add New Lead</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="add-lead-form">
              <div class="modal-body">
                  @csrf
                  <div class="form-group mb-3">
                      <label>Full Name *</label>
                      <input type="text" name="full_name" class="form-control" required>
                  </div>
                  <div class="form-group mb-3">
                      <label>Email *</label>
                      <input type="email" name="email" class="form-control" required>
                  </div>
                  <div class="form-group mb-3">
                      <label>Phone</label>
                      <input type="text" name="phone" class="form-control">
                  </div>
                  <div class="form-group mb-3">
                      <label>Source</label>
                      <select name="source" class="form-control">
                          <option value="Website">Website</option>
                          <option value="Facebook">Facebook</option>
                          <option value="Instagram">Instagram</option>
                          <option value="Manual">Manual</option>
                      </select>
                  </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="create-lead-btn">Create Lead</button>
              </div>
          </form>
        </div>
      </div>
    </div>

@endsection

@stack('styles')
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.min.css" rel="stylesheet">

@push('scripts')
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content'),
                'Accept': 'application/json'
            },
            dataType: 'json'
        });

        // Global Search Logic
        let searchTimeout = null;

        $('#globalSearchInput').on('keyup', function() {
            let query = $(this).val();
            let resultsBox = $('#globalSearchResults');

            clearTimeout(searchTimeout);

            if (query.length < 2) {
                resultsBox.hide();
                return;
            }

            searchTimeout = setTimeout(function() {
                $.get('{{ route("global.search") }}', { q: query }, function(data) {
                    let html = '';

                    if (data.leads.length > 0) {
                        html += '<h6 class="dropdown-header">Leads</h6>';
                        data.leads.forEach(function(lead) {
                            html += `<a class="dropdown-item" href="{{ route('lead-management') }}?open_lead=${lead.id}">${lead.full_name} <small class="text-muted">(${lead.email})</small></a>`;
                        });
                    }

                    if (data.clients.length > 0) {
                        if (html !== '') html += '<div class="dropdown-divider"></div>';
                        html += '<h6 class="dropdown-header">Clients</h6>';
                        data.clients.forEach(function(client) {
                            let editUrl = '{{ route("client-profile", ":id") }}'.replace(':id', client.id);
                            html += `<a class="dropdown-item" href="${editUrl}">${client.full_name} <small class="text-muted">(${client.email})</small></a>`;
                        });
                    }

                    if (html === '') {
                        html = '<span class="dropdown-item text-muted">No results found.</span>';
                    }

                    resultsBox.html(html).show();
                });
            }, 300);
        });

        // Hide dropdown when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.search-info').length) {
                $('#globalSearchResults').hide();
            }
        });

        // Add lead form
        $('#add-lead-form').submit(function(e) {
            e.preventDefault();
            let btn = $('#create-lead-btn');
            btn.prop('disabled', true).text('Creating...');

            $.ajax({
                url: '{{ route("lead-management.store") }}',
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload(); // Reload to update dashboard counts
                    });
                    $('#add-lead-form')[0].reset();
                    $('#addLeadModal').modal('hide');
                },
                error: function(xhr) {
                    let errorMsg = xhr.responseJSON?.message || 'Error creating lead';
                    if (xhr.status === 422 && xhr.responseJSON?.errors) {
                        // Extract first validation error
                        let firstError = Object.values(xhr.responseJSON.errors)[0][0];
                        errorMsg = firstError;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: errorMsg
                    });
                },
                complete: function() {
                    btn.prop('disabled', false).text('Create Lead');
                }
            });
        });
    });
</script>
@endpush
