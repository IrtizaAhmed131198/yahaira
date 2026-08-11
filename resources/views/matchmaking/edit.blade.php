@extends('layouts.app')

@section('title', 'Matchmaking & Scheduling')

@section('content')
<div class="col-lg-9 col-md-9 col-12 p-0">
    <div class="dashboard-layout">
        <div class="dashboard-top">
            <div class="dashboard-heading">
                <h4> Matchmaking & Scheduling</h4>
                <p>
                    Candidates, compatibility, dates, feedback
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
                <ul class="mt-3">
                    <p class="step-chosse m-0"> <span>Candidate Identified</span></p>
                    @if(in_array($match->status, $status) && $match->status !== 'identified')
                        <p class="step-chosse m-0"> <span>Compatibility Reviewed</span></p>
                    @else
                        <li><p class=""> <span>Compatibility Reviewed</span></p></li>
                    @endif
                    @if(in_array($match->status, $status) && $match->status !== 'identified' && $match->status !== 'reviewed')
                        <p class="step-chosse m-0"> <span>Proposed to Client</span></p>
                    @else
                        <li><p class=""> <span>Proposed to Client</span></p></li>
                    @endif
                    @if(in_array($match->status, $status) && $match->status !== 'identified' && $match->status !== 'reviewed' && $match->status !== 'proposed')
                        <p class="step-chosse m-0"> <span>Client Approved</span></p>
                    @else
                        <li><p class=""> <span>Client Approved</span></p></li>
                    @endif
                    @if(in_array($match->status, $status) && $match->status !== 'identified' && $match->status !== 'reviewed' && $match->status !== 'proposed' && $match->status !== 'approved')
                        <p class="step-chosse m-0"> <span>Introduction Scheduled</span></p>
                    @else
                        <li><p class=""> <span>Introduction Scheduled</span></p></li>
                    @endif
                    @if(in_array($match->status, $status) && $match->status !== 'identified' && $match->status !== 'reviewed' && $match->status !== 'proposed' && $match->status !== 'approved' && $match->status !== 'scheduled')
                        <p class="step-chosse m-0"> <span>Date Completed</span></p>
                    @else
                        <li><p class=""> <span>Date Completed</span></p></li>
                    @endif
                    @if(in_array($match->status, $status) && $match->status !== 'identified' && $match->status !== 'reviewed' && $match->status !== 'proposed' && $match->status !== 'approved' && $match->status !== 'scheduled' && $match->status !== 'completed')
                        <p class="step-chosse m-0"> <span>Outcome Logged</span></p>
                    @else
                        <li><p class=""> <span>Outcome Logged</span></p></li>
                    @endif
                </ul>
            </div>

            <form id="matchmaking-edit-form" action="{{ route('matchmaking.update', $match->id) }}" method="POST">
                @csrf
                @method('PUT')

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="time-follow">
                    <div class="row">
                        <!-- Compatibility Review -->
                        <div class="col-lg-7 col-md-7 col-12">
                            <div class="review-user">
                                <div class="form-box">
                                    <div class="row">
                                        <div class="col-12">
                                            <label>Compatibility Review — {{ $match->client->full_name }} × {{ $match->candidate_name }}</label>
                                        </div>
                                        <div class="col-6 mb-3">
                                            <label>Values Alignment </label>
                                            <input type="text" name="values_score" class="form-control" value="{{ old('values_score', $match->compatibility->values_score ?? '') }}" placeholder="e.g. High">
                                        </div>
                                        <div class="col-6 mb-3">
                                            <label>Lifestyle Fit</label>
                                            <input type="text" name="lifestyle_score" class="form-control" value="{{ old('lifestyle_score', $match->compatibility->lifestyle_score ?? '') }}" placeholder="e.g. High">
                                        </div>
                                        <div class="col-6 mb-3">
                                            <label>Relationship-Goal Alignment</label>
                                            <input type="text" name="goal_alignment" class="form-control" value="{{ old('goal_alignment', $match->compatibility->goal_alignment ?? '') }}" placeholder="e.g. High">
                                        </div>
                                        <div class="col-6 mb-3">
                                            <label>Deal-Breaker Check</label>
                                            <input type="text" name="deal_breaker_check" class="form-control" value="{{ old('deal_breaker_check', $match->compatibility->deal_breaker_check ?? '') }}" placeholder="e.g. Clear">
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label>Matchmaker Rationale</label>
                                            <input type="text" name="compatibility_notes" class="form-control" value="{{ old('compatibility_notes', $match->compatibility->notes ?? '') }}" placeholder="e.g. Both value-driven, similar timelines, complementary lifestyles.">
                                        </div>

                                        <div class="col-12 mb-3">
                                            <label>Overall Match Status</label>
                                            <select name="status" class="form-control">
                                                <option value="identified" {{ $match->status == 'identified' ? 'selected' : '' }}>Candidate Identified</option>
                                                <option value="reviewed" {{ $match->status == 'reviewed' ? 'selected' : '' }}>Compatibility Reviewed</option>
                                                <option value="proposed" {{ $match->status == 'proposed' ? 'selected' : '' }}>Proposed to Client</option>
                                                <option value="approved" {{ $match->status == 'approved' ? 'selected' : '' }}>Client Approved</option>
                                                <option value="scheduled" {{ $match->status == 'scheduled' ? 'selected' : '' }}>Introduction Scheduled</option>
                                                <option value="completed" {{ $match->status == 'completed' ? 'selected' : '' }}>Date Completed</option>
                                                <option value="outcome" {{ $match->status == 'outcome' ? 'selected' : '' }}>Outcome Logged</option>
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-btn">
                                                <button type="submit" class="btn web-btn ph-btn">Save All Details</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <p class="mt-3">Matchmaker collects feedback manually by phone/email after the date and enters it here — nothing is auto-collected.</p>
                            </div>
                        </div>

                        <!-- Date Planning & Feedback -->
                        <div class="col-lg-5 col-md-5 col-12">
                            <div class="review-user">
                                <div class="form-box">
                                    <div class="row">
                                        <div class="col-12">
                                            <label>Date Planning</label>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label>Date & Time</label>
                                            <input type="text" name="date_time" class="form-control" value="{{ old('date_time', $match->date->date_time ?? '') }}" placeholder="e.g. Sat, Jul 19 · 4:00 PM">
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label>Location</label>
                                            <input type="text" name="location" class="form-control" value="{{ old('location', $match->date->location ?? '') }}" placeholder="e.g. Cafe Luna, Downtown">
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label>Date Status</label>
                                            <input type="text" name="date_status" class="form-control" value="{{ old('date_status', $match->date->status ?? '') }}" placeholder="e.g. Scheduled">
                                        </div>

                                        <div class="col-12 mt-4">
                                            <label>Post-Date Feedback</label>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label>{{ explode(' ', $match->client->full_name)[0] }}'s Rating / Notes</label>
                                            <input type="text" name="client_feedback" class="form-control" value="{{ old('client_feedback', $match->feedback->client_feedback ?? '') }}" placeholder="e.g. ★★★★☆ — Great conversation, would meet again">
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label>{{ explode(' ', $match->candidate_name)[0] }}'s Rating / Notes</label>
                                            <input type="text" name="candidate_feedback" class="form-control" value="{{ old('candidate_feedback', $match->feedback->candidate_feedback ?? '') }}" placeholder="e.g. ★★★★★ — Really enjoyed it">
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label>Overall Rating</label>
                                            <input type="text" name="rating" class="form-control" value="{{ old('rating', $match->feedback->rating ?? '') }}" placeholder="e.g. Mutual Interest">
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label>Feedback Notes / Outcome</label>
                                            <input type="text" name="feedback_notes" class="form-control" value="{{ old('feedback_notes', $match->feedback->notes ?? '') }}" placeholder="e.g. Moving to Date 2">
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
@endsection

@push('scripts')
<script>
    document.getElementById('matchmaking-edit-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        var form = this;
        var submitBtn = form.querySelector('button[type="submit"]');
        var originalText = submitBtn.innerHTML;
        
        submitBtn.innerHTML = 'Saving...';
        submitBtn.disabled = true;

        fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            
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
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            Swal.fire({
                title: 'Error',
                text: 'Something went wrong while communicating with the server.',
                icon: 'error',
                confirmButtonColor: '#3085d6'
            });
        });
    });
</script>
@endpush
