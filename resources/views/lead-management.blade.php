@extends('layouts.app')

@section('title', 'Lead Management')

@section('content')
    <div class="col-lg-9 col-md-9 col-12 p-0">
        <div class="dashboard-layout">
            <div class="dashboard-top">
                <div class="dashboard-heading">
                    <h4> Lead Management</h4>
                    <p>
                        Setter workflow — new leads to qualified
                    </p>
                </div>
                <div class="top-side-icon">
                    <ul>
                        <li>
                            <a href="#"><i class="fa-solid fa-magnifying-glass"></i></a>
                        </li>
                        <li>
                            <a href="#"><i class="fa-regular fa-envelope"></i></a>
                        </li>
                        <li>
                            <a href="#"><i class="fa-regular fa-bell"></i></a>
                        </li>
                        <li>
                            <a href="#"><img src="{{ asset('images/profile.png') }}" class="img-fluid" alt=""></a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="box-info-detail">
                <div class="tab-icon-btn">
                    {{-- <ul>
                        <li>
                            <p> Admin · <span>Full</span></p>
                        </li>
                        <li>
                            <p> Setter · <span>Full (own leads)</span></p>
                        </li>
                        <li>
                            <p> Closer · <span>View only (handed off)</span></p>
                        </li>
                        <li>
                            <p> Matchmaker / Coach / Billing · <span>None</span></p>
                        </li>
                    </ul> --}}
                    <div class="search-info">
                        <form onsubmit="event.preventDefault();">
                            <input type="search" name="" class="form-control"
                                placeholder="Search leads, clients, candidates…" id="search-leads">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </form>
                        <div class="add-user">
                            @if(!Auth::user()->hasRole('admin'))
                                <button class="btn web-btn" data-bs-toggle="modal" data-bs-target="#addLeadModal">Add New Lead</button>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="box-assiged">
                    <ul>
                        <li data-status="new">
                            <h5>New <span id="count-new">0</span></h5>
                            <div id="list-new"></div>
                        </li>
                        <li data-status="contacted">
                            <h5>Contacted <span id="count-contacted">0</span></h5>
                            <div id="list-contacted"></div>
                        </li>
                        <li data-status="qualified">
                            <h5>Qualified <span id="count-qualified">0</span></h5>
                            <div id="list-qualified"></div>
                        </li>
                        <li data-status="handed_off">
                            <h5>Handed to Closer <span id="count-handed_off">0</span></h5>
                            <div id="list-handed_off"></div>
                        </li>
                        <li data-status="lost">
                            <h5>Not a Fit / Lost <span id="count-lost">0</span></h5>
                            <div id="list-lost"></div>
                        </li>
                    </ul>
                </div>
                <div class="time-follow" id="lead-card-section" style="display: none;">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-12">
                            <div class="review-user">
                                <div class="day-time">
                                    <h4 id="leadCardTitle">Lead Card</h4>
                                </div>
                                <form id="update-lead-form">
                                    <input type="hidden" id="card_lead_id">
                                    <div class="form-box">
                                        <div class="row">
                                            <div class="col-lg-7 col-md-7 col-12">
                                                <div class="row">
                                                    <div class="col-6 mb-3">
                                                        <label for="">Full Name </label>
                                                        <input type="text" class="form-control"
                                                            id="card_full_name" name="full_name">
                                                    </div>
                                                    <div class="col-6 mb-3">
                                                        <label for="">Lead Source</label>
                                                        <select class="form-control" id="card_source" name="source">
                                                            <option value="Website">Website</option>
                                                            <option value="Facebook">Facebook</option>
                                                            <option value="Instagram">Instagram</option>
                                                            <option value="Manual">Manual</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-6 mb-3">
                                                        <label for="">Email</label>
                                                        <input type="email" class="form-control"
                                                            id="card_email" name="email">
                                                    </div>
                                                    <div class="col-6 mb-3">
                                                        <label for="">Phone</label>
                                                        <input type="text" class="form-control"
                                                            id="card_phone" name="phone">
                                                    </div>
                                                    <div class="col-6 mb-3">
                                                        <label for="">Time Zone</label>
                                                        <input type="text" class="form-control"
                                                            id="card_timezone" name="timezone" placeholder="e.g. Eastern (ET)">
                                                    </div>
                                                    <div class="col-6 mb-3">
                                                        <label for="">Service Interest</label>
                                                        <input type="text" class="form-control"
                                                            id="card_interested_service" name="interested_service" placeholder="e.g. Premier">
                                                    </div>
                                                    <div class="col-6 mb-3">
                                                        <label for="">Budget Range</label>
                                                        <input type="text" class="form-control"
                                                            id="card_budget_range" name="budget_range" placeholder="e.g. $15k–$20k">
                                                    </div>
                                                    <div class="col-6 mb-3">
                                                        <label for="">Status</label>
                                                        <select class="form-control fw-bold" id="card_status" name="status">
                                                            <option value="new">New</option>
                                                            <option value="contacted">Contacted</option>
                                                            <option value="qualified">Qualified</option>
                                                            <option value="handed_off">Handed to Closer</option>
                                                            <option value="lost">Not a Fit / Lost</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-6 mb-3">
                                                        <label for="">Next Follow-Up</label>
                                                        <input type="datetime-local" class="form-control"
                                                            id="card_next_followup" name="next_followup_at">
                                                    </div>
                                                    <div class="col-12 mt-2 text-end">
                                                        @if(!Auth::user()->hasRole('admin'))
                                                            <button type="button" class="btn web-btn ph-btn" id="update-details-btn">Save Lead Details</button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-5 col-md-5 col-12">
                                                <div class="meeting-time mt-0">
                                                    <label>Notes / Activity Log</label>
                                                    <ul id="lead-notes-container" style="max-height: 300px; overflow-y: auto;">
                                                        <!-- Notes loaded via AJAX -->
                                                    </ul>
                                                    @if(!Auth::user()->hasRole('admin'))
                                                        <div class="mt-3">
                                                            <textarea class="form-control mb-2" id="new_note_text" rows="2" placeholder="Type a note... (e.g. Called — very interested)"></textarea>
                                                            <button type="button" class="btn web-btn ph-btn" id="save-note-btn">Add Note</button>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            @if(!Auth::user()->hasRole('admin'))
                                                <div class="col-12 mt-4">
                                                    <div class="form-btn">
                                                        <button type="button" class="btn web-btn ph-btn update-status" data-status="qualified">Mark Qualified & Hand to Closer</button>
                                                        <button type="button" class="btn web-btn dec-btn update-status" data-status="lost">Mark Not a Fit / Lost</button>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                                <p class="mt-3">Setter places this call manually from their own phone and types the note
                                    afterward — the CRM does not dial or send anything. Phone and Time Zone are
                                    captured once, right here, and carry forward automatically into every later
                                    scheduling screen.</p>
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
<style>
    .lead-item {
        cursor: pointer;
        transition: all 0.2s;
    }
    .lead-item:hover {
        background-color: #f8f9fa;
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .lead-item.active {
        border: 2px solid #0d6efd;
        background-color: #f8f9fa;
    }
    .box-assiged ul li {
        height: calc(100vh - 250px);
        overflow-y: scroll;
    }

    /* Optional: customize scrollbar for columns */
    .box-assiged ul li::-webkit-scrollbar {
        width: 5px;
    }
    .box-assiged ul li::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 5px;
    }
</style>

@push('scripts')
<script>
    $(document).ready(function() {

        @if(Auth::user()->hasRole('admin'))
            // Disable all form inputs for admins
            $('#update-lead-form input, #update-lead-form select').prop('disabled', true);
        @endif

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content'),
                'Accept': 'application/json'
            },
            dataType: 'json'
        });

        let search = '';
        let statuses = ['new', 'contacted', 'qualified', 'handed_off', 'lost'];
        let pageState = {
            'new': { page: 1, lastPage: 1, loading: false },
            'contacted': { page: 1, lastPage: 1, loading: false },
            'qualified': { page: 1, lastPage: 1, loading: false },
            'handed_off': { page: 1, lastPage: 1, loading: false },
            'lost': { page: 1, lastPage: 1, loading: false }
        };

        let notesPage = 1;
        let notesLastPage = 1;
        let notesLoading = false;

        // Initial load for all statuses
        function loadAllLeads() {
            statuses.forEach(status => {
                pageState[status].page = 1;
                loadLeads(status, 1);
            });
        }
        loadAllLeads();

        function loadLeads(status, page = 1) {
            let state = pageState[status];
            if (state.loading || page > state.lastPage) return;
            state.loading = true;

            $.get(`{{ route("lead-management.data") }}?status=${status}&page=${page}&search=${search}`, function(res) {
                if(res.success) {
                    state.lastPage = res.pagination.last_page;

                    renderLeadBoard(status, res.leads, page > 1);

                    // Auto-load next page if column is not full enough to scroll
                    setTimeout(function() {
                        if (state.page < state.lastPage) {
                            let col = $('.box-assiged ul li[data-status="' + status + '"]');
                            if (col.prop('scrollHeight') <= col.innerHeight() + 10) {
                                state.page++;
                                loadLeads(status, state.page);
                            }
                        }
                    }, 100);
                }
            }).always(function() {
                state.loading = false;
            });
        }

        function renderLeadBoard(status, leads, append = false) {
            let listId = '#list-' + status;
            let countId = '#count-' + status;

            if (!append) {
                $(listId).html('');
            }

            let html = '';
            leads.forEach(function(lead) {
                let sourceText = lead.source || 'Website';
                let phoneLink = lead.phone ? `<br> <a href="tel:${lead.phone}">${lead.phone}</a>` : '';
                let displayStatus = lead.status ? lead.status : status;

                html += `
                    <div class="customer-info lead-item" data-id="${lead.id}">
                        <h6>${lead.full_name}</h6>
                        <p>${sourceText}
                            ${phoneLink}
                        </p>
                        <span>${displayStatus.charAt(0).toUpperCase() + displayStatus.slice(1)}</span>
                    </div>
                `;
            });

            $(listId).append(html);

            // Update counts based on DOM elements
            let currentCount = $(listId + ' .lead-item').length;
            $(countId).text(currentCount < 10 ? '0' + currentCount : currentCount);
        }

        $('.box-assiged ul li').scroll(function() {
            let status = $(this).data('status');
            if (!status) return;

            if ($(this).scrollTop() + $(this).innerHeight() >= this.scrollHeight - 20) {
                let state = pageState[status];
                if (!state.loading && state.page < state.lastPage) {
                    state.page++;
                    loadLeads(status, state.page);
                }
            }
        });

        //search
        $('#search-leads').on('keyup', function() {
            search = $(this).val();
            loadAllLeads();
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
                    });
                    $('#add-lead-form')[0].reset();
                    $('#addLeadModal').modal('hide');
                    loadAllLeads();
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

        // Open Lead Card
        $('body').on('click', '.lead-item', function () {
            $('.lead-item').removeClass('active');
            $(this).addClass('active');

            var leadId = $(this).data('id');
            $('#lead-card-section').slideDown(400, function() {
                $('html, body').animate({
                    scrollTop: $("#lead-card-section").offset().top - 20
                }, 500);
            });
            $('#lead-notes-container').html('<li><p>Loading notes...</p></li>');

            let url = '{{ route("lead-management.show", ":id") }}'.replace(':id', leadId);

            $.get(url, function (res) {
                let lead = res.lead;
                $('#card_lead_id').val(lead.id);
                $('#card_full_name').val(lead.full_name);
                $('#card_source').val(lead.source);
                $('#card_email').val(lead.email);
                $('#card_phone').val(lead.phone);
                $('#card_timezone').val(lead.timezone);
                $('#card_interested_service').val(lead.interested_service);
                $('#card_budget_range').val(lead.budget_range);

                // Format for datetime-local input
                if (lead.next_followup_at) {
                    $('#card_next_followup').val(moment(lead.next_followup_at).format('YYYY-MM-DDTHH:mm'));
                } else {
                    $('#card_next_followup').val('');
                }

                let statusVal = lead.status ? lead.status.toLowerCase() : 'new';
                // normalize status value
                if(statusVal !== 'new' && statusVal !== 'contacted' && statusVal !== 'qualified' && statusVal !== 'lost' && statusVal !== 'handed_off') {
                    if (statusVal.includes('hand') || statusVal.includes('closer')) {
                        statusVal = 'handed_off';
                    } else {
                        statusVal = 'new';
                    }
                }
                $('#card_status').val(statusVal);

                $('#leadCardTitle').text('Lead Card — ' + lead.full_name + ' (opened)');

                notesPage = 1;
                if(res.notes_pagination) {
                    notesLastPage = res.notes_pagination.last_page;
                    renderNotes(res.notes, false);
                } else {
                    renderNotes([], false);
                }

            }).fail(function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Error fetching lead data'
                });
            });
        });

        function loadMoreNotes(leadId, page) {
            if (notesLoading || page > notesLastPage) return;
            notesLoading = true;
            let url = '{{ route("lead-management.notes-data", ":id") }}'.replace(':id', leadId) + '?page=' + page;
            $.get(url, function(res) {
                if(res.success) {
                    notesLastPage = res.notes_pagination.last_page;
                    renderNotes(res.notes, page > 1);
                }
            }).always(function() {
                notesLoading = false;
            });
        }

        $('#lead-notes-container').scroll(function() {
            if ($(this).scrollTop() + $(this).innerHeight() >= this.scrollHeight - 10) {
                let leadId = $('#card_lead_id').val();
                if (!notesLoading && notesPage < notesLastPage) {
                    notesPage++;
                    loadMoreNotes(leadId, notesPage);
                }
            }
        });

        function renderNotes(notes, append = false) {
            let html = '';
            if(!notes || notes.length === 0) {
                if (!append) html = '<li><p class="text-muted">No notes yet.</p></li>';
            } else {
                notes.forEach(function(note) {
                    let authorRole = 'System';
                    let authorName = 'System';
                    if(note.user) {
                        authorName = note.user.name;
                        // Assuming roles might be loaded if needed, else just show name
                        authorRole = authorName;
                    }
                    let date = moment(note.created_at).format('MMM D, h:mm A');
                    html += `
                        <li>
                            <h6>Note added by ${authorRole}</h6>
                            <p>${date}</p>
                            <input type="text" class="form-control" value="${note.note}" readonly>
                        </li>
                    `;
                });
            }
            if (append) {
                $('#lead-notes-container').append(html);
            } else {
                $('#lead-notes-container').html(html);
            }
        }

        // Add Note
        $('#save-note-btn').click(function(e) {
            e.preventDefault();
            let leadId = $('#card_lead_id').val();
            let noteText = $('#new_note_text').val();

            if(!noteText || !leadId) return;

            let btn = $(this);
            btn.prop('disabled', true).text('Saving...');

            let url = '{{ route("lead-management.notes", ":id") }}'.replace(':id', leadId);

            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    note: noteText
                },
                success: function(response) {
                    $('#new_note_text').val('');
                    notesPage = 1;
                    loadMoreNotes(leadId, 1);
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: xhr.responseJSON?.message || 'Error adding note'
                    });
                },
                complete: function() {
                    btn.prop('disabled', false).text('Add Note');
                }
            });
        });

        // Update Status
        $('.update-status').click(function(e) {
            e.preventDefault();
            let status = $(this).data('status');
            let leadId = $('#card_lead_id').val();

            if(!leadId) return;

            let url = '{{ route("lead-management.update", ":id") }}'.replace(':id', leadId);

            $.ajax({
                url: url,
                method: 'PUT',
                data: { status: status },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Status Updated!',
                        text: 'Lead marked as ' + status,
                        timer: 1500,
                        showConfirmButton: false
                    });

                    let statusVal = status.toLowerCase();
                    if(statusVal !== 'new' && statusVal !== 'contacted' && statusVal !== 'qualified' && statusVal !== 'lost' && statusVal !== 'handed_off') {
                        if (statusVal.includes('hand') || statusVal.includes('closer')) {
                            statusVal = 'handed_off';
                        } else {
                            statusVal = 'new';
                        }
                    }
                    $('#card_status').val(statusVal);

                    loadAllLeads(); // Refresh board

                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: xhr.responseJSON?.message || 'Error updating status'
                    });
                }
            });
        });

        // Update Lead Details
        $('#update-details-btn').click(function(e) {
            e.preventDefault();
            let leadId = $('#card_lead_id').val();
            if(!leadId) return;

            let btn = $(this);
            btn.prop('disabled', true).text('Saving...');

            let url = '{{ route("lead-management.update", ":id") }}'.replace(':id', leadId);

            $.ajax({
                url: url,
                method: 'PUT',
                data: $('#update-lead-form').serialize(),
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Saved!',
                        text: 'Lead details updated successfully',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    loadAllLeads(); // Refresh board to reflect name/phone changes
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: xhr.responseJSON?.message || 'Error updating details'
                    });
                },
                complete: function() {
                    btn.prop('disabled', false).text('Save Lead Details');
                }
            });
        });

    });
</script>
@endpush
