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
                            @if(!Auth::user()->hasRole('closer'))
                                <button class="btn web-btn" data-bs-toggle="modal" data-bs-target="#addLeadModal">Add New Lead</button>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="table-responsive mt-4">
                    <table class="table table-bordered table-hover leads-datatable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Source</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody id="leads-table-body">
                            <!-- Leads will be loaded here via AJAX -->
                        </tbody>
                    </table>
                    <div id="loading-spinner" style="display: none; text-align: center; padding: 10px;">Loading...</div>
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
                                                        </select>
                                                    </div>
                                                    <div class="col-6 mb-3">
                                                        <label for="">Next Follow-Up</label>
                                                        <input type="datetime-local" class="form-control"
                                                            id="card_next_followup" name="next_followup_at">
                                                    </div>
                                                    <div class="col-12 mt-2 text-end">
                                                        @if(!Auth::user()->hasRole('closer'))
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
                                                    @if(!Auth::user()->hasRole('admin') && !Auth::user()->hasRole('closer'))
                                                        <div class="mt-3">
                                                            <textarea class="form-control mb-2" id="new_note_text" rows="2" placeholder="Type a note... (e.g. Called — very interested)"></textarea>
                                                            <button type="button" class="btn web-btn ph-btn" id="save-note-btn">Add Note</button>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

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
<link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
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
        background-color: #e9ecef;
    }
    .leads-datatable tbody tr {
        cursor: pointer;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0 !important;
        margin: 0 !important;
        border: none !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: none !important;
        border: none !important;
    }
</style>

@push('scripts')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {

        @if(Auth::user()->hasRole('closer'))
            // Disable all form inputs for closers
            $('#update-lead-form input, #update-lead-form select').prop('disabled', true);
        @endif

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content'),
                'Accept': 'application/json'
            },
            dataType: 'json'
        });

        // Auto-open lead if passed in URL
        const urlParams = new URLSearchParams(window.location.search);
        const openLeadId = urlParams.get('open_lead');
        let initialLoadComplete = false;

        let notesPage = 1;
        let notesLastPage = 1;
        let notesLoading = false;

        var leadsTable = $('.leads-datatable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route("lead-management.data") }}',
                data: function (d) {
                    d.status = 'new';
                }
            },
            columns: [
                { data: 'full_name', name: 'full_name' },
                { data: 'source', name: 'source' },
                { 
                    data: 'phone', 
                    name: 'phone',
                    render: function(data) {
                        return data ? data : '-';
                    }
                },
                { 
                    data: 'email', 
                    name: 'email',
                    render: function(data) {
                        return data ? data : '-';
                    }
                },
                { 
                    data: 'status_formatted', 
                    name: 'status',
                    render: function(data, type, row) {
                        return `<span class="badge bg-primary">${data}</span>`;
                    }
                },
                { data: 'created_at_formatted', name: 'created_at' }
            ],
            createdRow: function(row, data, dataIndex) {
                $(row).addClass('lead-item');
                $(row).attr('data-id', data.id);
            },
            drawCallback: function(settings) {
                // Auto-open lead if passed in URL
                if (openLeadId && !initialLoadComplete) {
                    let targetLead = $(`.lead-item[data-id="${openLeadId}"]`);
                    if (targetLead.length > 0) {
                        targetLead.trigger('click');
                        initialLoadComplete = true;
                    }
                }
            }
        });

        // Hide custom search box if using Datatable's own, or wire it up:
        // Let's wire the existing custom search input to the Datatable search
        $('#search-leads').on('keyup', function() {
            leadsTable.search($(this).val()).draw();
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
                    leadsTable.draw(false);
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

                $('#card_status').val('new');

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
                    leadsTable.draw(false); // Refresh board to reflect name/phone changes
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
