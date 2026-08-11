@extends('layouts.app')

@section('title', 'Team & User Management')

@section('content')
    <div class="col-lg-9 col-md-9 col-12 p-0">
        <div class="dashboard-layout">
            <div class="dashboard-top">
                <div class="dashboard-heading">
                    <h4> Team & User Management</h4>
                    <p>
                        Add staff, assign roles, control routing
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
                            <p> All other roles · <span>None</span></p>
                        </li>
                    </ul>
                </div>

                <div class="search-info">
                    <div class="customer-info-prs">
                        <h5>Team</h5>
                    </div>
                </div>

                <div class="time-follow">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-12">
                            <div class="review-user">
                                <div class="form-box">
                                    <table class="team-users w-100" id="users-table">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Role</th>
                                                <th>Email</th>
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
                        <div class="col-lg-6 col-md-6 col-12 mt-4">
                            <div class="review-user">
                                <form id="add-user-form">
                                    @csrf
                                    <div class="form-box">
                                        <div class="row">
                                            <div class="col-12">
                                                <label for="">Add User</label>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group mb-3">
                                                    <label for="">Name </label>
                                                    <input type="text" name="name" class="form-control"
                                                        placeholder="e.g. Dana Ruiz" required>
                                                    <div class="invalid-feedback name-error"></div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group mb-3">
                                                    <label for="">Email</label>
                                                    <input type="email" name="email" class="form-control"
                                                        placeholder="e.g. dana@imready.com" required>
                                                    <div class="invalid-feedback email-error"></div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group mb-3">
                                                    <label for="">Role</label>
                                                    <select name="role" class="form-control" required>
                                                        <option value="">Select Role...</option>
                                                        @foreach($roles as $role)
                                                            <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="invalid-feedback role-error"></div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-btn">
                                                    <button type="submit" class="btn web-btn" id="create-user-btn">Create User</button>
                                                </div>
                                            </div>
                                            
                                            <div class="col-12 mt-2">
                                            </div>
                                        </div>
                                    </div>
                                    <p>New user gets that role's permissions instantly — no developer required.</p>
                                </form>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-12 mt-4">
                            <div class="review-user">
                                <form action="">
                                    <div class="form-box">
                                        <div class="row">
                                            <div class="col-12">
                                                <label for="">Routing Rules
                                                </label>
                                            </div>
                                            <div class="col-12">
                                                <label for="">Setter Round Robin</label>
                                                <input type="text" class="form-control"
                                                    placeholder="On — 3 Setters" readonly>
                                            </div>
                                            <div class="col-12">
                                                <label for="">Closer Round Robin</label>
                                                <input type="text" class="form-control"
                                                    placeholder="On — 2 Closers" readonly>
                                            </div>
                                            <div class="col-12">
                                                <label for="">Manual Reassign</label>
                                                <input type="text" class="form-control"
                                                    placeholder="Drag any lead/client to a different teammate"
                                                    readonly>
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

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="edit-user-form">
              <div class="modal-body">
                  @csrf
                  @method('PUT')
                  <input type="hidden" name="id" id="edit_user_id">
                  <div class="form-group mb-3">
                      <label for="">Name </label>
                      <input type="text" name="name" id="edit_name" class="form-control" required>
                      <div class="invalid-feedback edit-name-error"></div>
                  </div>
                  <div class="form-group mb-3">
                      <label for="">Email</label>
                      <input type="email" name="email" id="edit_email" class="form-control" required>
                      <div class="invalid-feedback edit-email-error"></div>
                  </div>
                  <div class="form-group mb-3">
                      <label for="">Role</label>
                      <select name="role" id="edit_role" class="form-control" required>
                          <option value="">Select Role...</option>
                          @foreach($roles as $role)
                              <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                          @endforeach
                      </select>
                      <div class="invalid-feedback edit-role-error"></div>
                  </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" id="update-user-btn">Update User</button>
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
        
        // Setup CSRF Token for all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
            }
        });

        // Initialize DataTable
        var table = $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('team-users.data') }}",
            columns: [
                {data: 'name', name: 'name'},
                {data: 'role', name: 'role', orderable: false, searchable: false},
                {data: 'email', name: 'email'},
                {data: 'status', name: 'status', orderable: false, searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false, className: 'action-btns'},
            ]
        });

        // Add user form submission
        $('#add-user-form').submit(function(e) {
            e.preventDefault();
            
            let btn = $('#create-user-btn');
            btn.prop('disabled', true).text('Creating...');
            
            $('.invalid-feedback').text('');
            $('input, select').removeClass('is-invalid');
            
            $.ajax({
                url: '{{ route("team-and-user-management.store") }}',
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
                    $('#add-user-form')[0].reset();
                    table.draw(); // Reload datatable
                },
                error: function(xhr) {
                    if(xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        if(errors.name) {
                            $('[name="name"]').addClass('is-invalid');
                            $('.name-error').text(errors.name[0]);
                        }
                        if(errors.email) {
                            $('[name="email"]').addClass('is-invalid');
                            $('.email-error').text(errors.email[0]);
                        }
                        if(errors.role) {
                            $('[name="role"]').addClass('is-invalid');
                            $('.role-error').text(errors.role[0]);
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
                    btn.prop('disabled', false).text('Create User');
                }
            });
        });

        // Edit User Click
        $('body').on('click', '.editUser', function () {
            var userId = $(this).data('id');
            
            // Clear previous errors
            $('.invalid-feedback').text('');
            $('input, select').removeClass('is-invalid');
            $('#edit-success-alert, #edit-error-alert').addClass('d-none');
            let url = '{{ route("team-and-user-management.edit", ":id") }}'.replace(':id', userId);
            
            $.get(url, function (data) {
                $('#editUserModal').modal('show');
                $('#edit_user_id').val(data.id);
                $('#edit_name').val(data.name);
                $('#edit_email').val(data.email);
                
                if(data.roles && data.roles.length > 0) {
                    $('#edit_role').val(data.roles[0].name);
                } else {
                    $('#edit_role').val('');
                }
            }).fail(function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Error fetching user data'
                });
            });
        });

        // Update User Submission
        $('#edit-user-form').submit(function(e) {
            e.preventDefault();
            
            var userId = $('#edit_user_id').val();
            let btn = $('#update-user-btn');
            btn.prop('disabled', true).text('Updating...');
            
            $('.invalid-feedback').text('');
            $('input, select').removeClass('is-invalid');
            
            let url = '{{ route("team-and-user-management.update", ":id") }}'.replace(':id', userId);
            
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
                    $('#editUserModal').modal('hide');
                },
                error: function(xhr) {
                    if(xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        if(errors.name) {
                            $('#edit_name').addClass('is-invalid');
                            $('.edit-name-error').text(errors.name[0]);
                        }
                        if(errors.email) {
                            $('#edit_email').addClass('is-invalid');
                            $('.edit-email-error').text(errors.email[0]);
                        }
                        if(errors.role) {
                            $('#edit_role').addClass('is-invalid');
                            $('.edit-role-error').text(errors.role[0]);
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
                    btn.prop('disabled', false).text('Update User');
                }
            });
        });

        // Delete User Click
        $('body').on('click', '.deleteUser', function () {
            var userId = $(this).data("id");
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
                    let url = '{{ route("team-and-user-management.destroy", ":id") }}'.replace(':id', userId);
                    $.ajax({
                        type: "DELETE",
                        url: url,
                        success: function (data) {
                            table.draw();
                            Swal.fire(
                                'Deleted!',
                                'User has been deleted.',
                                'success'
                            )
                        },
                        error: function (data) {
                            console.log('Error:', data);
                            Swal.fire(
                                'Error!',
                                'Error deleting user.',
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
