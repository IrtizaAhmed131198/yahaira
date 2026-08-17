@extends('layouts.app')

@section('title', 'Sales / Closing')

@section('content')
    <div class="col-lg-9 col-md-9 col-12 p-0">
        <div class="dashboard-layout">
            <div class="dashboard-top">
                <div class="dashboard-heading">
                    <h4> Sales / Closing</h4>
                    <p>
                        Closer workflow — qualified lead to signed deal
                    </p>
                </div>
            </div>
            <div class="box-info-detail">
                <div class="tab-icon-btn">
                    {{-- <ul>
                        <li><p> Admin · <span>View only</span></p></li>
                        <li><p> Setter · <span>None</span></p></li>
                        <li><p> Closer · <span>Full</span></p></li>
                        <li><p> Matchmaker / Coach / Billing · <span>None</span></p></li>
                    </ul> --}}
                    <div class="search-info">
                        <form onsubmit="event.preventDefault();">
                            <input type="search" name="" class="form-control"
                                placeholder="Search leads, clients, candidates…" id="search-deals">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </form>
                    </div>
                </div>
                <div class="box-assiged">
                    <ul>
                        <li data-status="assigned">
                            <h5>Assigned <span id="count-assigned">00</span></h5>
                            <div id="list-assigned"></div>
                        </li>
                        <li data-status="booked">
                            <h5>Consultation Booked <span id="count-booked">00</span></h5>
                            <div id="list-booked"></div>
                        </li>
                        <li data-status="proposal">
                            <h5>Proposal Sent <span id="count-proposal">00</span></h5>
                            <div id="list-proposal"></div>
                        </li>
                        <li data-status="won">
                            <h5>Won (Paid) <span id="count-won">00</span></h5>
                            <div id="list-won"></div>
                        </li>
                        <li data-status="lost">
                            <h5>Lost <span id="count-lost">00</span></h5>
                            <div id="list-lost"></div>
                        </li>
                    </ul>
                </div>

                <div class="time-follow" id="deal-card-section" style="display: none;">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-12">
                            <div class="review-user">
                                <div class="day-time">
                                    <h4 id="dealCardTitle">Deal Card</h4>
                                </div>
                                <form id="update-deal-form">
                                    <input type="hidden" id="card_deal_id">
                                    <div class="form-box">
                                        <div class="row">
                                            <div class="col-lg-3 col-12 mb-3">
                                                <label for="">Phone</label>
                                                <input type="text" class="form-control" id="card_phone" readonly>
                                            </div>
                                            <div class="col-lg-3 col-12 mb-3">
                                                <label for="">Time Zone (from lead)</label>
                                                <input type="text" class="form-control" id="card_timezone" readonly>
                                            </div>
                                            <div class="col-lg-3 col-12 mb-3">
                                                <label for="">Discovery Call Date & Time</label>
                                                <input type="datetime-local" class="form-control" id="card_consultation_at" name="consultation_at">
                                            </div>
                                            <div class="col-lg-3 col-12 mb-3">
                                                <label for="">Method</label>
                                                <input type="text" class="form-control" value="Zoom" readonly>
                                            </div>
                                            <div class="col-lg-6 col-12 mb-3">
                                                <label for="">Zoom Link</label>
                                                <input type="text" class="form-control" id="card_zoom_link" name="zoom_link" placeholder="zoom.us/j/123456789">
                                            </div>
                                            <div class="col-lg-6 col-12 mb-3">
                                                <label for="">Closer Notes & Objections (internal only)</label>
                                                <input type="text" class="form-control" id="card_notes" name="notes" placeholder="Hesitant on price...">
                                            </div>

                                            <div class="col-12 mt-3">
                                                @if(!Auth::user()->hasRole('admin'))
                                                    <div class="form-btn">
                                                        <button type="button" class="btn web-btn ph-btn" id="update-details-btn">Save Details</button>
                                                        <button type="button" class="btn web-btn update-status" data-status="booked">Mark Booked</button>
                                                        <button type="button" class="btn web-btn update-status" data-status="proposal">Mark Proposal Sent</button>
                                                        <button type="button" class="btn web-btn ph-btn update-status" data-status="won">Mark Won (Paid)</button>
                                                        <button type="button" class="btn web-btn dec-btn update-status" data-status="lost">Mark Lost / Not a Fit</button>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <p class="mt-3">Phone and Time Zone are pulled in automatically from the lead record — the Closer never has to ask again. Closer creates the Zoom link in their own Zoom account and pastes it here, then emails the client the details manually.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="time-follow" id="client-intake-section" style="display: none;">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-12">
                            <div class="review-user">
                                <div class="day-time">
                                    <h4>Client Intake Form</h4>
                                </div>
                                <form id="intake-form" enctype="multipart/form-data">
                                    <input type="hidden" id="intake_client_id" name="client_id">
                                    <input type="hidden" id="intake_deal_id" name="deal_id">
                                    <nav>
                                        <div class="nav nav-tabs mb-3" id="nav-tab" role="tablist">
                                            <button class="nav-link active" id="nav-basic-info-tab" data-bs-toggle="tab" data-bs-target="#nav-basic-info" type="button" role="tab" aria-controls="nav-basic-info" aria-selected="true">Basic Info</button>
                                            <button class="nav-link" id="nav-personal-goal-tab" data-bs-toggle="tab" data-bs-target="#nav-personal-goal" type="button" role="tab" aria-controls="nav-personal-goal" aria-selected="false">Personal & Goal</button>
                                            <button class="nav-link" id="nav-value-lifestyle-tab" data-bs-toggle="tab" data-bs-target="#nav-value-lifestyle" type="button" role="tab" aria-controls="nav-value-lifestyle" aria-selected="false">Values & Lifestyle</button>
                                            <button class="nav-link" id="nav-emotional-readiness-tab" data-bs-toggle="tab" data-bs-target="#nav-emotional-readiness" type="button" role="tab" aria-controls="nav-emotional-readiness" aria-selected="false">Emotional Readiness</button>
                                            <button class="nav-link" id="nav-partner-criteria-tab" data-bs-toggle="tab" data-bs-target="#nav-partner-criteria" type="button" role="tab" aria-controls="nav-partner-criteria" aria-selected="false">Partner Criteria</button>
                                        </div>
                                    </nav>

                                    <div class="tab-content" id="nav-tabContent">
                                        <!-- BASIC INFO -->
                                        <div class="tab-pane fade show active" id="nav-basic-info" role="tabpanel" aria-labelledby="nav-basic-info-tab">
                                            <div class="form-box">
                                                <div class="row">
                                                    <div class="col-6"><div class="form-group mb-3"><label>Full Name</label><input type="text" name="full_name" id="intake_full_name" class="form-control" required></div></div>
                                                    <div class="col-6"><div class="form-group mb-3"><label>Email</label><input type="email" name="email" id="intake_email" class="form-control" required></div></div>
                                                    <div class="col-6"><div class="form-group mb-3"><label>Phone</label><input type="text" name="phone" id="intake_phone" class="form-control"></div></div>
                                                    <div class="col-6"><div class="form-group mb-3"><label>Timezone</label><input type="text" name="timezone" id="intake_timezone" class="form-control"></div></div>
                                                    <div class="col-6" style="display:none;"><input type="hidden" name="status" id="intake_status" value="active"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- PERSONAL & GOAL -->
                                        <div class="tab-pane fade" id="nav-personal-goal" role="tabpanel" aria-labelledby="nav-personal-goal-tab">
                                            <div class="form-box">
                                                <div class="row">
                                                    <div class="col-6"><div class="form-group mb-3"><label>Date of Birth</label><input type="date" name="date_of_birth" id="intake_date_of_birth" class="form-control"></div></div>
                                                    <div class="col-6"><div class="form-group mb-3"><label>Occupation</label><input type="text" name="occupation" id="intake_occupation" class="form-control"></div></div>
                                                    <div class="col-6"><div class="form-group mb-3"><label>Relationship Goal</label><input type="text" name="relationship_goal" id="intake_relationship_goal" class="form-control"></div></div>
                                                    <div class="col-6"><div class="form-group mb-3"><label>Commitment Timeline</label><input type="text" name="commitment_timeline" id="intake_commitment_timeline" class="form-control"></div></div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- VALUES & LIFESTYLE -->
                                        <div class="tab-pane fade" id="nav-value-lifestyle" role="tabpanel" aria-labelledby="nav-value-lifestyle-tab">
                                            <div class="form-box">
                                                <div class="row">
                                                    <div class="col-6"><div class="form-group mb-3"><label>Core Values (top 3)</label><input type="text" name="core_values" id="intake_core_values" class="form-control"></div></div>
                                                    <div class="col-6"><div class="form-group mb-3"><label>Lifestyle</label><input type="text" name="lifestyle" id="intake_lifestyle" class="form-control"></div></div>
                                                    <div class="col-6"><div class="form-group mb-3"><label>Faith / Spiritual Practice</label><input type="text" name="faith" id="intake_faith" class="form-control"></div></div>
                                                    <div class="col-6"><div class="form-group mb-3"><label>Children</label><input type="text" name="children" id="intake_children" class="form-control"></div></div>
                                                    <div class="col-12"><div class="form-group mb-3"><label>Deal-Breakers</label><input type="text" name="deal_breakers" id="intake_deal_breakers" class="form-control"></div></div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- EMOTIONAL READINESS -->
                                        <div class="tab-pane fade" id="nav-emotional-readiness" role="tabpanel" aria-labelledby="nav-emotional-readiness-tab">
                                            <div class="form-box">
                                                <div class="row">
                                                    <div class="col-12"><div class="form-group mb-3"><label>Current Stage</label><input type="text" name="current_stage" id="intake_current_stage" class="form-control"></div></div>
                                                    <div class="col-6"><div class="form-group mb-3"><label>What did you learn from your last relationship?</label><input type="text" name="learned_from_last_relationship" id="intake_learned_from_last_relationship" class="form-control"></div></div>
                                                    <div class="col-6"><div class="form-group mb-3"><label>What are you ready for now?</label><input type="text" name="ready_for_now" id="intake_ready_for_now" class="form-control"></div></div>
                                                    <div class="col-12"><div class="form-group mb-3"><label>Support System in Place?</label><input type="text" name="support_system" id="intake_support_system" class="form-control"></div></div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- PARTNER CRITERIA -->
                                        <div class="tab-pane fade" id="nav-partner-criteria" role="tabpanel" aria-labelledby="nav-partner-criteria-tab">
                                            <div class="form-box">
                                                <div class="row">
                                                    <div class="col-6"><div class="form-group mb-3"><label>Age Range</label><input type="text" name="partner_age_range" id="intake_partner_age_range" class="form-control"></div></div>
                                                    <div class="col-6"><div class="form-group mb-3"><label>Location Radius</label><input type="text" name="partner_location_radius" id="intake_partner_location_radius" class="form-control"></div></div>
                                                    <div class="col-6"><div class="form-group mb-3"><label>Education Level</label><input type="text" name="partner_education_level" id="intake_partner_education_level" class="form-control"></div></div>
                                                    <div class="col-6"><div class="form-group mb-3"><label>Career Stage</label><input type="text" name="partner_career_stage" id="intake_partner_career_stage" class="form-control"></div></div>
                                                    <div class="col-12"><div class="form-group mb-3"><label>Must-Haves</label><input type="text" name="partner_must_haves" id="intake_partner_must_haves" class="form-control"></div></div>
                                                    <div class="col-12"><div class="form-group mb-3"><label>Nice-to-Haves</label><input type="text" name="partner_nice_to_haves" id="intake_partner_nice_to_haves" class="form-control"></div></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-box mt-3">
                                        <div class="row">
                                            <div class="col-12 text-end">
                                                <button type="submit" class="btn web-btn ph-btn" id="submit-intake-btn">Save Client Intake & Mark Proposal Sent</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {

        @if(Auth::user()->hasRole('admin'))
            // Disable all form inputs for admins
            $('#update-deal-form input').prop('disabled', true);
        @endif

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content'),
                'Accept': 'application/json'
            }
        });

        let search = '';
        let statuses = ['assigned', 'booked', 'proposal', 'won', 'lost'];
        let pageState = {
            'assigned': { page: 1, lastPage: 1, loading: false },
            'booked': { page: 1, lastPage: 1, loading: false },
            'proposal': { page: 1, lastPage: 1, loading: false },
            'won': { page: 1, lastPage: 1, loading: false },
            'lost': { page: 1, lastPage: 1, loading: false }
        };

        function loadAllDeals() {
            statuses.forEach(status => {
                $('#list-' + status).html('');
                pageState[status] = { page: 1, lastPage: 1, loading: false };
                loadDeals(status, 1);
            });
        }

        loadAllDeals();

        function loadDeals(status, page = 1) {
            if (pageState[status].loading) return;
            pageState[status].loading = true;

            $.get(`{{ route("sales-closing.data") }}?status=${status}&page=${page}&search=${search}`, function(res) {
                if(res.success) {
                    pageState[status].lastPage = res.pagination.last_page;

                    let exactTotal = res.pagination.total;
                    $('#count-' + status).text(exactTotal < 10 ? '0' + exactTotal : exactTotal);

                    renderDealBoard(status, res.deals, page > 1);

                    // Auto-load next page if column is not full enough to scroll
                    setTimeout(function() {
                        let columnEl = $('.box-assiged ul li[data-status="'+status+'"]');
                        if (columnEl.length && columnEl[0].scrollHeight <= columnEl.innerHeight() + 10) {
                            if (pageState[status].page < pageState[status].lastPage) {
                                pageState[status].page++;
                                loadDeals(status, pageState[status].page);
                            }
                        }
                    }, 500);
                }
            }).always(function() {
                pageState[status].loading = false;
            });
        }

        function renderDealBoard(status, deals, append = false) {
            let listId = '#list-' + status;
            let html = '';

            deals.forEach(function(deal) {
                let leadName = deal.lead ? deal.lead.full_name : 'Unknown';
                let sourceText = deal.lead ? deal.lead.source : 'Website';
                let phone = deal.lead ? deal.lead.phone : 'N/A';

                let phoneLink = phone !== 'N/A' ? `<a href="tel:${phone}">${phone}</a>` : 'N/A';
                let service = deal.lead ? deal.lead.interested_service : '';

                html += `
                    <div class="customer-info deal-item" data-id="${deal.id}" style="cursor:pointer;">
                        <h6>${leadName}</h6>
                        <p>${sourceText} · ${service}
                            <br> ${phoneLink}
                        </p>
                        <span>${status.charAt(0).toUpperCase() + status.slice(1)}</span>
                    </div>
                `;
            });

            if (append) {
                $(listId).append(html);
            } else {
                $(listId).html(html);
            }
        }

        $('.box-assiged ul li').scroll(function() {
            let status = $(this).data('status');
            if (!status) return;

            if ($(this).scrollTop() + $(this).innerHeight() >= this.scrollHeight - 10) {
                if (!pageState[status].loading && pageState[status].page < pageState[status].lastPage) {
                    pageState[status].page++;
                    loadDeals(status, pageState[status].page);
                }
            }
        });

        $('#search-deals').on('keyup', function() {
            search = $(this).val();
            loadAllDeals();
        });

        // Open Deal Card
        $('body').on('click', '.deal-item', function () {
            $('.deal-item').removeClass('active');
            $(this).addClass('active');

            var dealId = $(this).data('id');

            let url = '{{ route("sales-closing.show", ":id") }}'.replace(':id', dealId);

            $.get(url, function (res) {
                let deal = res.deal;
                let lead = deal.lead || {};
                let client = deal.client || {};

                if (deal.status === 'booked') {
                    $('#deal-card-section').hide();
                    $('#client-intake-section').slideDown(400, function() {
                        $('html, body').animate({
                            scrollTop: $("#client-intake-section").offset().top - 20
                        }, 500);
                    });

                    // Populate Intake Form
                    $('#intake_client_id').val(client.id || '');
                    $('#intake_deal_id').val(deal.id);

                    $('#intake_full_name').val(client.full_name || lead.full_name || '');
                    $('#intake_email').val(client.email || lead.email || '');
                    $('#intake_phone').val(client.phone || lead.phone || '');
                    $('#intake_timezone').val(client.timezone || lead.timezone || '');
                    $('#intake_status').val(client.status || 'active');

                    let fields = [
                        'date_of_birth', 'occupation', 'relationship_goal', 'commitment_timeline',
                        'core_values', 'lifestyle', 'faith', 'children', 'deal_breakers',
                        'current_stage', 'learned_from_last_relationship', 'ready_for_now', 'support_system',
                        'partner_age_range', 'partner_location_radius', 'partner_education_level',
                        'partner_career_stage', 'partner_must_haves', 'partner_nice_to_haves'
                    ];

                    fields.forEach(function(f) {
                        $('#intake_' + f).val(client[f] || '');
                    });

                } else {
                    $('#client-intake-section').hide();
                    $('#deal-card-section').slideDown(400, function() {
                        $('html, body').animate({
                            scrollTop: $("#deal-card-section").offset().top - 20
                        }, 500);
                    });

                    // Manage button visibility based on status
                    $('.update-status').show();
                    
                    if (deal.status === 'proposal') {
                        $('.update-status[data-status="booked"]').hide();
                        $('.update-status[data-status="proposal"]').hide();
                    }

                    // Payment check for won and lost
                    let payment = client.payment;
                    if (!payment || payment.status !== 'paid') {
                        $('.update-status[data-status="won"]').hide();
                        $('.update-status[data-status="lost"]').hide();
                    }

                    // Populate Deal Card Form
                    $('#card_deal_id').val(deal.id);
                    $('#card_phone').val(lead.phone || '');
                    $('#card_timezone').val(lead.timezone || '');

                    if (deal.consultation_at) {
                        $('#card_consultation_at').val(moment(deal.consultation_at).format('YYYY-MM-DDTHH:mm'));
                    } else {
                        $('#card_consultation_at').val('');
                    }

                    $('#card_zoom_link').val(deal.zoom_link || '');
                    $('#card_notes').val(deal.notes || '');

                    $('#dealCardTitle').text('Deal Card — ' + (lead.full_name || 'Unknown'));
                }
            }).fail(function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Error fetching deal data'
                });
            });
        });

        // Update Status
        $('.update-status').click(function(e) {
            e.preventDefault();
            let status = $(this).data('status');
            let dealId = $('#card_deal_id').val();

            if(!dealId) return;

            let url = '{{ route("sales-closing.update", ":id") }}'.replace(':id', dealId);

            $.ajax({
                url: url,
                method: 'PUT',
                data: { status: status },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Status Updated!',
                        text: 'Deal marked as ' + status,
                        timer: 1500,
                        showConfirmButton: false
                    });
                    loadAllDeals();
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

        // Update Deal Details
        $('#update-details-btn').click(function(e) {
            e.preventDefault();
            let dealId = $('#card_deal_id').val();
            if (!dealId) return;

            let btn = $(this);
            btn.prop('disabled', true).text('Saving...');

            let url = '{{ route("sales-closing.update", ":id") }}'.replace(':id', dealId);

            $.ajax({
                url: url,
                method: 'PUT',
                data: $('#update-deal-form').serialize(),
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Saved!',
                        text: 'Deal details updated successfully',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    loadAllDeals();
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: xhr.responseJSON?.message || 'Error updating details'
                    });
                },
                complete: function() {
                    btn.prop('disabled', false).text('Save Details');
                }
            });
        });

        // Submit Intake Form inside Sales Closing
        $('#intake-form').on('submit', function(e) {
            e.preventDefault();

            let clientId = $('#intake_client_id').val();
            let dealId = $('#intake_deal_id').val();

            if (!clientId) {
                Swal.fire('Error', 'Client ID missing. Did the deal transition to booked correctly?', 'error');
                return;
            }

            let formData = new FormData(this);
            formData.append('_method', 'PUT');

            let btn = $('#submit-intake-btn');
            btn.prop('disabled', true).text('Saving...');

            $.ajax({
                url: '{{ url("dashboard/client-intake-application") }}/' + clientId,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    if (res.success) {
                        // Client saved, now mark deal as proposal sent
                        $.ajax({
                            url: '{{ url("dashboard/sales-closing") }}/' + dealId,
                            type: 'PUT',
                            data: {
                                status: 'proposal'
                            },
                            success: function(res2) {
                                btn.prop('disabled', false).text('Save Client Intake & Mark Proposal Sent');
                                if (res2.success) {
                                    Swal.fire('Success', 'Client details saved and marked as Proposal Sent!', 'success');
                                    $('#client-intake-section').slideUp();
                                    loadAllDeals();
                                }
                            }
                        });
                    } else {
                        btn.prop('disabled', false).text('Save Client Intake & Mark Proposal Sent');
                        Swal.fire('Error', res.message || 'Error saving client', 'error');
                    }
                },
                error: function(err) {
                    btn.prop('disabled', false).text('Save Client Intake & Mark Proposal Sent');
                    let errorMsg = 'An error occurred';
                    if (err.responseJSON && err.responseJSON.errors) {
                        errorMsg = Object.values(err.responseJSON.errors).flat().join('\n');
                    }
                    Swal.fire('Error', errorMsg, 'error');
                }
            });
        });

    });
</script>
<style>
    .deal-item.active {
        border: 2px solid #0d6efd;
        background-color: #f8f9fa;
    }
    .box-assiged ul li {
        height: calc(100vh - 250px);
        overflow-y: auto;
    }
</style>
@endpush
