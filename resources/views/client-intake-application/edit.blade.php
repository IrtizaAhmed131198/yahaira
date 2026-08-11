@extends('layouts.app')

@section('title', 'Client Intake / Application')

@section('content')
    <div class="col-lg-9 col-md-9 col-12 p-0">
        <div class="dashboard-layout">
            <div class="dashboard-top">
                <div class="dashboard-heading">
                    <h4> Client Intake / Application</h4>
                    <p>
                        Full matchmaking application (reviewed by Matchmaker) for {{ $client->full_name }}
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
                    <div class="link-status">
                        <button type="button" class="btn">Send Application Link</button>
                        <p @if($client->application_status == 'Approved') style="color: #198754; font-weight: 600; background: #d2f7df;" @endif>
                            Status: {{ $client->application_status }}
                        </p>
                    </div>
                </div>

                <form id="intake-form" action="{{ route('client-intake-application.update', $client->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="time-follow">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-12">
                                @if(session('success'))
                                    <div class="alert alert-success">{{ session('success') }}</div>
                                @endif
                                @if($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <nav>
                                    <div class="nav nav-tabs mb-3" id="nav-tab" role="tablist">
                                        <button class="nav-link active" id="nav-basic-info-tab" data-bs-toggle="tab" data-bs-target="#nav-basic-info" type="button" role="tab" aria-controls="nav-basic-info" aria-selected="true">Basic Info</button>
                                        <button class="nav-link" id="nav-personal-goal-tab" data-bs-toggle="tab" data-bs-target="#nav-personal-goal" type="button" role="tab" aria-controls="nav-personal-goal" aria-selected="false">Personal & Goal</button>
                                        <button class="nav-link" id="nav-value-lifestyle-tab" data-bs-toggle="tab" data-bs-target="#nav-value-lifestyle" type="button" role="tab" aria-controls="nav-value-lifestyle" aria-selected="false">Values &amp; Lifestyle</button>
                                        <button class="nav-link" id="nav-emotional-readiness-tab" data-bs-toggle="tab" data-bs-target="#nav-emotional-readiness" type="button" role="tab" aria-controls="nav-emotional-readiness" aria-selected="false">Emotional Readiness</button>
                                        <button class="nav-link" id="nav-partner-criteria-tab" data-bs-toggle="tab" data-bs-target="#nav-partner-criteria" type="button" role="tab" aria-controls="nav-partner-criteria" aria-selected="false">Partner Criteria</button>
                                        <button class="nav-link" id="nav-photos-tab" data-bs-toggle="tab" data-bs-target="#nav-photos" type="button" role="tab" aria-controls="nav-photos" aria-selected="false">Photos</button>
                                    </div>
                                </nav>

                                <div class="tab-content" id="nav-tabContent">
                                    <!-- BASIC INFO -->
                                    <div class="tab-pane fade show active" id="nav-basic-info" role="tabpanel" aria-labelledby="nav-basic-info-tab">
                                        <div class="form-box">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-group mb-3">
                                                        <label>Full Name</label>
                                                        <input type="text" name="full_name" class="form-control" value="{{ old('full_name', $client->full_name) }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group mb-3">
                                                        <label>Email</label>
                                                        <input type="email" name="email" class="form-control" value="{{ old('email', $client->email) }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group mb-3">
                                                        <label>Phone</label>
                                                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $client->phone) }}">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group mb-3">
                                                        <label>Timezone</label>
                                                        <input type="text" name="timezone" class="form-control" value="{{ old('timezone', $client->timezone) }}">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group mb-3">
                                                        <label>Status</label>
                                                        <select name="status" class="form-control">
                                                            <option value="active" {{ old('status', $client->status) == 'active' ? 'selected' : '' }}>Active</option>
                                                            <option value="inactive" {{ old('status', $client->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- PERSONAL & GOAL -->
                                    <div class="tab-pane fade" id="nav-personal-goal" role="tabpanel" aria-labelledby="nav-personal-goal-tab">
                                        <div class="form-box">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-group mb-3">
                                                        <label>Date of Birth</label>
                                                        <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', $client->date_of_birth) }}">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group mb-3">
                                                        <label>Occupation</label>
                                                        <input type="text" name="occupation" class="form-control" value="{{ old('occupation', $client->occupation) }}" placeholder="e.g. Physical Therapist">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group mb-3">
                                                        <label>Relationship Goal</label>
                                                        <input type="text" name="relationship_goal" class="form-control" value="{{ old('relationship_goal', $client->relationship_goal) }}" placeholder="e.g. Marriage">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group mb-3">
                                                        <label>Commitment Timeline</label>
                                                        <input type="text" name="commitment_timeline" class="form-control" value="{{ old('commitment_timeline', $client->commitment_timeline) }}" placeholder="e.g. Within 1 year">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- VALUES & LIFESTYLE -->
                                    <div class="tab-pane fade" id="nav-value-lifestyle" role="tabpanel" aria-labelledby="nav-value-lifestyle-tab">
                                        <div class="form-box">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-group mb-3">
                                                        <label>Core Values (top 3)</label>
                                                        <input type="text" name="core_values" class="form-control" value="{{ old('core_values', $client->core_values) }}" placeholder="e.g. Honesty, Family, Growth">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group mb-3">
                                                        <label>Lifestyle</label>
                                                        <input type="text" name="lifestyle" class="form-control" value="{{ old('lifestyle', $client->lifestyle) }}" placeholder="e.g. Active, health-conscious">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group mb-3">
                                                        <label>Faith / Spiritual Practice</label>
                                                        <input type="text" name="faith" class="form-control" value="{{ old('faith', $client->faith) }}" placeholder="e.g. Christian — practicing">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group mb-3">
                                                        <label>Children</label>
                                                        <input type="text" name="children" class="form-control" value="{{ old('children', $client->children) }}" placeholder="e.g. Wants children">
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group mb-3">
                                                        <label>Deal-Breakers</label>
                                                        <input type="text" name="deal_breakers" class="form-control" value="{{ old('deal_breakers', $client->deal_breakers) }}" placeholder="e.g. Smoking, no interest in children">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- EMOTIONAL READINESS -->
                                    <div class="tab-pane fade" id="nav-emotional-readiness" role="tabpanel" aria-labelledby="nav-emotional-readiness-tab">
                                        <div class="form-box">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group mb-3">
                                                        <label>Current Stage</label>
                                                        <input type="text" name="current_stage" class="form-control" value="{{ old('current_stage', $client->current_stage) }}" placeholder="e.g. Heal → Rebuild → Choose">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group mb-3">
                                                        <label>What did you learn from your last relationship?</label>
                                                        <input type="text" name="learned_from_last_relationship" class="form-control" value="{{ old('learned_from_last_relationship', $client->learned_from_last_relationship) }}">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group mb-3">
                                                        <label>What are you ready for now?</label>
                                                        <input type="text" name="ready_for_now" class="form-control" value="{{ old('ready_for_now', $client->ready_for_now) }}">
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group mb-3">
                                                        <label>Support System in Place?</label>
                                                        <input type="text" name="support_system" class="form-control" value="{{ old('support_system', $client->support_system) }}" placeholder="e.g. Yes — therapist + close friend group">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- PARTNER CRITERIA -->
                                    <div class="tab-pane fade" id="nav-partner-criteria" role="tabpanel" aria-labelledby="nav-partner-criteria-tab">
                                        <div class="form-box">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-group mb-3">
                                                        <label>Age Range</label>
                                                        <input type="text" name="partner_age_range" class="form-control" value="{{ old('partner_age_range', $client->partner_age_range) }}" placeholder="e.g. 34–44">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group mb-3">
                                                        <label>Location Radius</label>
                                                        <input type="text" name="partner_location_radius" class="form-control" value="{{ old('partner_location_radius', $client->partner_location_radius) }}" placeholder="e.g. Within 30 miles">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group mb-3">
                                                        <label>Education Level</label>
                                                        <input type="text" name="partner_education_level" class="form-control" value="{{ old('partner_education_level', $client->partner_education_level) }}" placeholder="e.g. Bachelor's or higher">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group mb-3">
                                                        <label>Career Stage</label>
                                                        <input type="text" name="partner_career_stage" class="form-control" value="{{ old('partner_career_stage', $client->partner_career_stage) }}" placeholder="e.g. Established professional">
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group mb-3">
                                                        <label>Must-Haves</label>
                                                        <input type="text" name="partner_must_haves" class="form-control" value="{{ old('partner_must_haves', $client->partner_must_haves) }}" placeholder="e.g. Emotionally available">
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group mb-3">
                                                        <label>Nice-to-Haves</label>
                                                        <input type="text" name="partner_nice_to_haves" class="form-control" value="{{ old('partner_nice_to_haves', $client->partner_nice_to_haves) }}" placeholder="e.g. Active lifestyle">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- PHOTOS -->
                                    <div class="tab-pane fade" id="nav-photos" role="tabpanel" aria-labelledby="nav-photos-tab">
                                        <div class="form-box">
                                            <div class="profile-img-box d-flex flex-wrap gap-3">
                                                @foreach($client->photos as $photo)
                                                    <div class="box-achivement position-relative" id="photo-box-{{ $photo->id }}" style="width:150px; height:150px; border:1px solid #ddd; padding:5px; text-align:center;">
                                                        <img src="{{ asset('storage/' . $photo->file_path) }}" alt="Photo" style="max-width:100%; max-height:100px; object-fit:contain;">
                                                        <button type="button" class="btn btn-sm btn-danger position-absolute" style="top:5px; right:5px;" onclick="deletePhoto({{ $photo->id }})"><i class="fa fa-trash"></i></button>
                                                    </div>
                                                @endforeach
                                                <div class="box-achivement" style="width:150px; height:150px; border:1px dashed #ddd; display:flex; align-items:center; justify-content:center;">
                                                    <input type="file" name="photos[]" multiple accept="image/*" id="photo-upload" style="display:none;" onchange="updateFileName()">
                                                    <button type="button" class="btn add-btn" onclick="document.getElementById('photo-upload').click()">+ Add Photos</button>
                                                </div>
                                                <div id="file-name-display" class="mt-2 w-100 text-center"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- REVIEW SECTION -->
                                <div class="review-user mt-4">
                                    <div class="form-box">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group mb-3">
                                                    <label>Reviewed By</label>
                                                    <input type="text" name="reviewed_by" class="form-control" value="{{ old('reviewed_by', $client->reviewed_by) }}" placeholder="e.g. Renee (Matchmaker)">
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group mb-3">
                                                    <label>Review Notes (internal only)</label>
                                                    <input type="text" name="review_notes" class="form-control" value="{{ old('review_notes', $client->review_notes) }}" placeholder="e.g. Strong candidate for Premier pool">
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-btn d-flex align-items-center gap-2">
                                                    <input type="hidden" name="application_status" id="application_status" value="{{ old('application_status', $client->application_status) }}">

                                                    <button type="button" class="btn web-btn" onclick="setStatus('Approved')">Approve</button>
                                                    <button type="button" class="btn web-btn ph-btn" onclick="setStatus('On Hold')">Put On Hold</button>
                                                    <button type="button" class="btn web-btn dec-btn" onclick="setStatus('Declined')">Decline</button>

                                                    <button type="submit" class="btn web-btn ms-auto">Save All Details</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function setStatus(status) {
            document.getElementById('application_status').value = status;
            document.getElementById('intake-form').dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));
        }

        document.getElementById('intake-form').addEventListener('submit', function(e) {
            e.preventDefault();

            var form = this;
            var submitBtns = form.querySelectorAll('button[type="submit"], button[onclick^="setStatus"]');

            // Client-side file size validation (8MB limit total)
            var photoInput = document.getElementById('photo-upload');
            if (photoInput.files.length > 0) {
                var totalSize = 0;
                for (var i = 0; i < photoInput.files.length; i++) {
                    totalSize += photoInput.files[i].size;
                }
                // Check if total size > 8MB (8388608 bytes)
                if (totalSize > 8388608) {
                    Swal.fire({
                        title: 'Files Too Large',
                        text: 'Total file size exceeds 8MB. Please select smaller photos or upload fewer at a time.',
                        icon: 'error',
                        confirmButtonColor: '#3085d6'
                    });
                    return;
                }
            }

            submitBtns.forEach(btn => btn.disabled = true);

            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    if (response.status === 422) {
                        return response.json().then(err => { throw err; });
                    } else if (response.status === 413) {
                        throw new Error("Files are too large for the server to process. Please reduce file sizes.");
                    }
                    throw new Error("Server Error: " + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                submitBtns.forEach(btn => btn.disabled = false);

                if (data.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: '#3085d6'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: data.message || 'Something went wrong',
                        icon: 'error',
                        confirmButtonColor: '#3085d6'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                submitBtns.forEach(btn => btn.disabled = false);

                let errorMsg = error.message || 'Something went wrong while communicating with the server.';
                if (error.errors) {
                    errorMsg = Object.values(error.errors).flat().join('\n');
                }

                Swal.fire({
                    title: 'Error',
                    text: errorMsg,
                    icon: 'error',
                    confirmButtonColor: '#3085d6'
                });
            });
        });

        function updateFileName() {
            var input = document.getElementById('photo-upload');
            var display = document.getElementById('file-name-display');
            if (input.files.length > 0) {
                display.innerHTML = '<strong>' + input.files.length + ' photo(s) selected. Remember to save.</strong>';
            } else {
                display.innerHTML = '';
            }
        }

        function deletePhoto(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('{{ url("dashboard/client-intake-application/photo") }}/' + id, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Remove the DOM element
                            document.getElementById('photo-box-' + id).remove();

                            // Show success alert
                            Swal.fire(
                                'Deleted!',
                                'Your photo has been deleted.',
                                'success'
                            );
                        } else {
                            Swal.fire(
                                'Error!',
                                data.message || 'Error deleting photo',
                                'error'
                            );
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire(
                            'Error!',
                            'Something went wrong while deleting the photo.',
                            'error'
                        );
                    });
                }
            });
        }
    </script>
    @endpush
@endsection
