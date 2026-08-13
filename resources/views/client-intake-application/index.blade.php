@extends('layouts.app')

@section('title', 'Client Intake / Application')

@section('content')
    <div class="col-lg-9 col-md-9 col-12 p-0">
        <div class="dashboard-layout">
            <div class="dashboard-top">
                <div class="dashboard-heading">
                    <h4> Client Intake / Application</h4>
                    <p>
                        Manage and view your clients
                    </p>
                </div>
            </div>
            <div class="box-info-detail">
                <div class="search-info mt-4">
                    <div class="customer-info-prs">
                        <h5>Client List</h5>
                    </div>
                </div>

                <div class="time-follow">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-12">
                            <div class="review-user">
                                <div class="form-box">
                                    <table class="team-users w-100" id="clients-table">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Timezone</th>
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
    .action-btns .btn {
        margin-right: 5px;
    }
</style>

@push('scripts')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            }
        });

        var table = $('#clients-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('client-intake-application.data') }}",
            columns: [
                {data: 'full_name', name: 'full_name'},
                {data: 'email', name: 'email'},
                {data: 'phone', name: 'phone'},
                {data: 'timezone', name: 'timezone'},
                {data: 'status', name: 'status', orderable: false, searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false, className: 'action-btns'},
            ]
        });
    });
</script>
@endpush
