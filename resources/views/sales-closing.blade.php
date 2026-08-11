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
                <div class="top-side-icon">
                    <ul>
                        <li><a href="#"><i class="fa-solid fa-magnifying-glass"></i></a></li>
                        <li><a href="#"><i class="fa-regular fa-envelope"></i></a></li>
                        <li><a href="#"><i class="fa-regular fa-bell"></i></a></li>
                        <li><a href="#"><img src="{{ asset('images/profile.png') }}" class="img-fluid" alt=""></a></li>
                    </ul>
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
            $('#deal-card-section').slideDown(400, function() {
                $('html, body').animate({
                    scrollTop: $("#deal-card-section").offset().top - 20
                }, 500);
            });

            let url = '{{ route("sales-closing.show", ":id") }}'.replace(':id', dealId);

            $.get(url, function (res) {
                let deal = res.deal;
                let lead = deal.lead || {};

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
            if(!dealId) return;

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
