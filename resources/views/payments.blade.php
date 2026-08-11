@extends('layouts.app')

@section('title', 'Payments')

@section('content')
    <div class="col-lg-9 col-md-9 col-12 p-0">
        <div class="dashboard-layout">
            <div class="dashboard-top">
                <div class="dashboard-heading">
                    <h4> Payments</h4>
                    <p>
                        Invoicing and payment status
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
                    <ul>
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
                    </ul>
                </div>

                <div class="time-follow mt-4">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-12">
                            <div class="review-user">
                                <form id="payment-form">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="id" id="payment_id">
                                    
                                    <div class="form-box">
                                        <h5 id="selected-client-name" class="mb-3">Select a payment from the queue</h5>
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group mb-3">
                                                    <label for="">Package </label>
                                                    <select class="form-control" name="package_id" id="package_id" disabled>
                                                        <option value="">Select Package</option>
                                                        @foreach($packages as $package)
                                                            <option value="{{ $package->id }}" data-price="{{ $package->price }}">{{ $package->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group mb-3">
                                                    <label for="">Agreed Price</label>
                                                    <input type="number" step="0.01" class="form-control" name="amount" id="amount" placeholder="0.00" disabled>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group mb-3">
                                                    <label for="">Payment Status</label>
                                                    <select class="form-control" name="status" id="status" disabled>
                                                        <option value="invoice_sent">Invoice Sent</option>
                                                        <option value="paid">Paid</option>
                                                        <option value="overdue">Overdue</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group mb-3">
                                                    <label for="">Payment Method</label>
                                                    <input type="text" class="form-control" name="payment_method" id="payment_method" placeholder="e.g. Credit Card" disabled>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group mb-3">
                                                    <label for="">Contract Signed</label>
                                                    <input type="date" class="form-control" name="contract_signed_at" id="contract_signed_at" disabled>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group mb-3">
                                                    <label for="">Date Paid</label>
                                                    <input type="date" class="form-control" name="paid_at" id="paid_at" disabled>
                                                </div>
                                            </div>
                                            <div class="col-12 mt-3">
                                                <div class="form-btn">
                                                    <button type="button" id="mark-paid-btn" class="btn web-btn" disabled>Mark Paid in Full</button>
                                                    <button type="submit" id="save-payment-btn" class="btn web-btn ph-btn" disabled>Save Details</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <p class="mt-3 text-muted">Billing emails the generated invoice manually from their own inbox — it is not auto-sent.</p>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-12">
                            <div class="review-user">
                                <div class="form-box">
                                    <h5 class="mb-3">Payment Queue</h5>
                                    <table class="w-100" id="payments-table">
                                        <thead>
                                            <tr>
                                                <th>Client</th>
                                                <th>Package</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@stack('styles')
<link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.min.css" rel="stylesheet">
<style>
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0 !important;
        margin: 0 !important;
        border: none !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: transparent !important;
        border: none !important;
    }
</style>

@push('scripts')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js"></script>
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            }
        });

        var table = $('#payments-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('payments.data') }}",
            columns: [
                {data: 'client_name', name: 'client_name'},
                {data: 'package_name', name: 'package_name'},
                {data: 'status_label', name: 'status_label', orderable: false, searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
        
        // Auto-fill price when package changes
        $('#package_id').change(function() {
            let price = $(this).find('option:selected').data('price');
            if(price) {
                $('#amount').val(price);
            }
        });

        // View payment details
        $('body').on('click', '.view-payment', function () {
            var paymentId = $(this).data('id');
            let url = '{{ url("/dashboard/payments") }}/' + paymentId;
            
            $.get(url, function (data) {
                let payment = data.payment;
                $('#payment_id').val(payment.id);
                $('#selected-client-name').text('Payment for: ' + (payment.client ? payment.client.full_name : 'Unknown'));
                
                $('#package_id').val(payment.package_id);
                $('#amount').val(payment.amount);
                $('#status').val(payment.status);
                $('#payment_method').val(payment.payment_method);
                
                if(payment.contract_signed_at) {
                    $('#contract_signed_at').val(payment.contract_signed_at.split('T')[0]);
                } else {
                    $('#contract_signed_at').val('');
                }
                
                if(payment.paid_at) {
                    $('#paid_at').val(payment.paid_at.split('T')[0]);
                } else {
                    $('#paid_at').val('');
                }
                
                // Enable form fields
                $('#payment-form').find('input, select, button').prop('disabled', false);
            }).fail(function() {
                Swal.fire('Error', 'Error fetching payment data', 'error');
            });
        });

        // Mark Paid in Full button
        $('#mark-paid-btn').click(function(e) {
            e.preventDefault();
            $('#status').val('paid');
            let today = new Date().toISOString().split('T')[0];
            $('#paid_at').val(today);
            $('#payment-form').submit();
        });

        // Save Details Form Submission
        $('#payment-form').submit(function(e) {
            e.preventDefault();
            
            var paymentId = $('#payment_id').val();
            let btn = $('#save-payment-btn');
            btn.prop('disabled', true).text('Saving...');
            
            let url = '{{ url("/dashboard/payments") }}/' + paymentId;
            
            $.ajax({
                url: url,
                method: 'PUT',
                data: $(this).serialize(),
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                    table.draw();
                },
                error: function(xhr) {
                    Swal.fire('Error', 'Something went wrong while saving.', 'error');
                },
                complete: function() {
                    btn.prop('disabled', false).text('Save Details');
                }
            });
        });
        
    });
</script>
@endpush
