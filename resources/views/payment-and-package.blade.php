@extends('layouts.app')

@section('title', 'Payment & Package')

@section('content')
    <div class="col-lg-9 col-md-9 col-12 p-0">
        <div class="dashboard-layout">
            <div class="dashboard-top">
                <div class="dashboard-heading">
                    <h4> Payment & Package</h4>
                    <p>
                        Manage your available packages and pricing
                    </p>
                </div>
            </div>
            <div class="box-info-detail">

                <div class="search-info mt-4">
                    <div class="customer-info-prs">
                        <h5>Packages</h5>
                    </div>
                </div>

                <div class="time-follow">
                    <div class="row">
                        <div class="col-lg-8 col-md-8 col-12">
                            <div class="review-user">
                                <div class="form-box">
                                    <table class="team-users w-100" id="packages-table">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Price</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-12">
                            <div class="review-user">
                                <form id="add-package-form">
                                    @csrf
                                    <div class="form-box">
                                        <div class="row">
                                            <div class="col-12">
                                                <label for="">Add Package</label>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group mb-3">
                                                    <label for="">Name </label>
                                                    <input type="text" name="name" class="form-control"
                                                        placeholder="e.g. Basic Plan" required>
                                                    <div class="invalid-feedback name-error"></div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group mb-3">
                                                    <label for="">Price</label>
                                                    <input type="number" step="0.01" name="price" class="form-control"
                                                        placeholder="e.g. 99.99" required>
                                                    <div class="invalid-feedback price-error"></div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-btn">
                                                    <button type="submit" class="btn web-btn" id="create-package-btn">Create Package</button>
                                                </div>
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

    <!-- Edit Package Modal -->
    <div class="modal fade" id="editPackageModal" tabindex="-1" aria-labelledby="editPackageModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editPackageModalLabel">Edit Package</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="edit-package-form">
              <div class="modal-body">
                  @csrf
                  @method('PUT')
                  <input type="hidden" name="id" id="edit_package_id">
                  <div class="form-group mb-3">
                      <label for="">Name </label>
                      <input type="text" name="name" id="edit_name" class="form-control" required>
                      <div class="invalid-feedback edit-name-error"></div>
                  </div>
                  <div class="form-group mb-3">
                      <label for="">Price</label>
                      <input type="number" step="0.01" name="price" id="edit_price" class="form-control" required>
                      <div class="invalid-feedback edit-price-error"></div>
                  </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="update-package-btn">Update Package</button>
              </div>
          </form>
        </div>
      </div>
    </div>

@endsection

@stack('styles')
<link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.min.css" rel="stylesheet">
<style>
    .invalid-feedback {
        display: block;
    }
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.5/dist/sweetalert2.all.min.js"></script>
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            }
        });

        // Initialize DataTable
        var table = $('#packages-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('packages.data') }}",
            columns: [
                {data: 'name', name: 'name'},
                {data: 'price', name: 'price'},
                {data: 'action', name: 'action', orderable: false, searchable: false, className: 'action-btns'},
            ]
        });

        // Add package form submission
        $('#add-package-form').submit(function(e) {
            e.preventDefault();
            
            let btn = $('#create-package-btn');
            btn.prop('disabled', true).text('Creating...');
            
            $('.invalid-feedback').text('');
            $('input').removeClass('is-invalid');
            
            $.ajax({
                url: '{{ route("payment-and-package.store") }}',
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        timer: 3000,
                        showConfirmButton: false
                    });
                    $('#add-package-form')[0].reset();
                    table.draw(); // Reload datatable
                },
                error: function(xhr) {
                    if(xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        if(errors.name) {
                            $('[name="name"]').addClass('is-invalid');
                            $('.name-error').text(errors.name[0]);
                        }
                        if(errors.price) {
                            $('[name="price"]').addClass('is-invalid');
                            $('.price-error').text(errors.price[0]);
                        }
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong. Please try again.'
                        });
                    }
                },
                complete: function() {
                    btn.prop('disabled', false).text('Create Package');
                }
            });
        });

        // Edit Package Click
        $('body').on('click', '.editPackage', function () {
            var packageId = $(this).data('id');
            
            // Clear previous errors
            $('.invalid-feedback').text('');
            $('input').removeClass('is-invalid');
            let url = '{{ route("payment-and-package.edit", ":id") }}'.replace(':id', packageId);
            
            $.get(url, function (data) {
                $('#editPackageModal').modal('show');
                $('#edit_package_id').val(data.id);
                $('#edit_name').val(data.name);
                $('#edit_price').val(data.price);
            }).fail(function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Error fetching package data'
                });
            });
        });

        // Update Package Submission
        $('#edit-package-form').submit(function(e) {
            e.preventDefault();
            
            var packageId = $('#edit_package_id').val();
            let btn = $('#update-package-btn');
            btn.prop('disabled', true).text('Updating...');
            
            $('.invalid-feedback').text('');
            $('input').removeClass('is-invalid');
            
            let url = '{{ route("payment-and-package.update", ":id") }}'.replace(':id', packageId);
            
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
                    $('#editPackageModal').modal('hide');
                },
                error: function(xhr) {
                    if(xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        if(errors.name) {
                            $('#edit_name').addClass('is-invalid');
                            $('.edit-name-error').text(errors.name[0]);
                        }
                        if(errors.price) {
                            $('#edit_price').addClass('is-invalid');
                            $('.edit-price-error').text(errors.price[0]);
                        }
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong. Please try again.'
                        });
                    }
                },
                complete: function() {
                    btn.prop('disabled', false).text('Update Package');
                }
            });
        });

        // Delete Package Click
        $('body').on('click', '.deletePackage', function () {
            var packageId = $(this).data("id");
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    let url = '{{ route("payment-and-package.destroy", ":id") }}'.replace(':id', packageId);
                    $.ajax({
                        type: "DELETE",
                        url: url,
                        success: function (data) {
                            table.draw();
                            Swal.fire(
                                'Deleted!',
                                'Package has been deleted.',
                                'success'
                            )
                        },
                        error: function (data) {
                            console.log('Error:', data);
                            Swal.fire(
                                'Error!',
                                'Error deleting package.',
                                'error'
                            )
                        }
                    });
                }
            });
        });
        
    });
</script>
@endpush
