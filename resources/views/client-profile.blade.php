@extends('layouts.app')

@section('title', 'Client Profile')

@section('content')
<div class="col-lg-9 col-md-9 col-12 p-0">
    <div class="dashboard-layout">
        <div class="dashboard-top">
            <div class="dashboard-heading">
                <h4>Client Profile</h4>
                <p>Unified 360° view of one person</p>
            </div>
        </div>
        <div class="box-info-detail">

            <div class="active-customer">
                <div class="customer-info-prs">
                    <h5>{{ $client->full_name }}</h5>
                    <p><a href="mailto:{{ $client->email }}">{{ $client->email }}</a> ·
                       @if($client->phone)<a href="tel:{{ $client->phone }}">{{ $client->phone }}</a> · @endif
                       {{ $client->city ?? 'Unknown' }}, {{ $client->state ?? 'Unknown' }}
                    </p>
                </div>
                <p class="step-chosse"><span>{{ $client->application_status }}</span></p>
            </div>

            <div class="time-follow">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-12">
                        <nav>
                            <div class="nav nav-tabs mb-3" id="nav-tab" role="tablist">
                                <button class="nav-link active" id="nav-personal-goal-tab" data-bs-toggle="tab" data-bs-target="#nav-personal-goal" type="button" role="tab" aria-controls="nav-personal-goal" aria-selected="true">Overview</button>
                                <button class="nav-link" id="nav-value-lifestyle-tab" data-bs-toggle="tab" data-bs-target="#nav-value-lifestyle" type="button" role="tab" aria-controls="nav-value-lifestyle" aria-selected="false">Application</button>
                                <button class="nav-link" id="nav-emotional-readiness-tab" data-bs-toggle="tab" data-bs-target="#nav-emotional-readiness" type="button" role="tab" aria-controls="nav-emotional-readiness" aria-selected="false">Payments</button>
                                <button class="nav-link" id="nav-partner-criteria-tab" data-bs-toggle="tab" data-bs-target="#nav-partner-criteria" type="button" role="tab" aria-controls="nav-partner-criteria" aria-selected="false">Coaching History</button>
                                <button class="nav-link" id="nav-photos-tab" data-bs-toggle="tab" data-bs-target="#nav-photos" type="button" role="tab" aria-controls="nav-photos" aria-selected="false">Documents</button>
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">

                            <!-- OVERVIEW TAB -->
                            <div class="tab-pane fade active show" id="nav-personal-goal" role="tabpanel" aria-labelledby="nav-personal-goal-tab">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="form-box">
                                            <div class="meeting-time mt-0 pt-0">
                                                <h5>Activity Timeline</h5>
                                                <ul>
                                                    @forelse($activities as $activity)
                                                        <li>
                                                            <h6>{{ ucfirst($activity->action) }}</h6>
                                                            <p>{{ $activity->created_at->format('M j, Y') }} · {{ $activity->user ? $activity->user->name : 'System' }}</p>
                                                        </li>
                                                    @empty
                                                        <li>
                                                            <h6 class="text-muted">No activity logged yet.</h6>
                                                        </li>
                                                    @endforelse
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-12">
                                        <div class="review-user">
                                            <div class="form-box">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <label>Snapshot</label>
                                                    </div>
                                                    <div class="col-12">
                                                        <label>Assigned Coach / Closer</label>
                                                        <input type="text" class="form-control" value="{{ $client->deal && $client->deal->closer ? $client->deal->closer->name : 'Unassigned' }}" readonly>
                                                    </div>
                                                    <div class="col-12">
                                                        <label>Package</label>
                                                        <input type="text" class="form-control" value="{{ $client->payment && $client->payment->package ? $client->payment->package->name . ' — $' . number_format($client->payment->package->price, 2) : 'No Package Assigned' }}" readonly>
                                                    </div>
                                                    <div class="col-12">
                                                        <label>Program Progress</label>
                                                        <input type="text" class="form-control" value="N/A (Module Pending)" readonly>
                                                    </div>
                                                    <div class="col-12">
                                                        <label>Phone / Time Zone</label>
                                                        <input type="text" class="form-control" value="{{ $client->phone ?? 'Unknown' }}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- APPLICATION TAB -->
                            <div class="tab-pane fade" id="nav-value-lifestyle" role="tabpanel" aria-labelledby="nav-value-lifestyle-tab">
                                <form action="">
                                    <div class="form-box">
                                        <div class="row">
                                            <div class="col-6">
                                                <label>Relationship Goal</label>
                                                <input type="text" class="form-control" value="{{ $client->relationship_goal ?? 'Not Provided' }}" readonly>
                                            </div>
                                            <div class="col-6">
                                                <label>Commitment Timeline</label>
                                                <input type="text" class="form-control" value="{{ $client->commitment_timeline ?? 'Not Provided' }}" readonly>
                                            </div>
                                            <div class="col-6">
                                                <label>Core Values</label>
                                                <input type="text" class="form-control" value="{{ $client->core_values ?? 'Not Provided' }}" readonly>
                                            </div>
                                            <div class="col-6">
                                                <label>Emotional Readiness Stage</label>
                                                <input type="text" class="form-control" value="{{ $client->current_stage ?? 'Not Provided' }}" readonly>
                                            </div>
                                            <div class="col-12">
                                                <label>Reviewer Notes</label>
                                                <input type="text" class="form-control" value="{{ $client->review_notes ?? 'No notes recorded.' }}" readonly>
                                            </div>
                                        </div>
                                        <p>Full application detail lives in <a href="{{ route('client-intake-application.edit', $client->id) }}">Client Intake</a>.</p>
                                    </div>
                                </form>
                            </div>

                            <!-- PAYMENTS TAB -->
                            <div class="tab-pane fade" id="nav-emotional-readiness" role="tabpanel" aria-labelledby="nav-emotional-readiness-tab">
                                <form action="">
                                    <div class="form-box">
                                        <div class="row">
                                            <div class="col-6">
                                                <label>Package</label>
                                                <input type="text" class="form-control" value="{{ $client->payment && $client->payment->package ? $client->payment->package->name . ' — $' . number_format($client->payment->package->price, 2) : 'None' }}" readonly>
                                            </div>

                                            @php
                                                $payment = $client->payment;
                                            @endphp

                                            <div class="col-6">
                                                <label>Payment Status</label>
                                                <input type="text" class="form-control" value="{{ $payment ? ucfirst($payment->status) : 'No Payment Found' }}" readonly>
                                            </div>
                                            <div class="col-6">
                                                <label>Payment Method</label>
                                                <input type="text" class="form-control" value="{{ $payment ? ucfirst($payment->payment_method) : 'N/A' }}" readonly>
                                            </div>

                                            <div class="col-6">
                                                <label>Contract Signed</label>
                                                <input type="text" class="form-control" value="N/A (Feature Pending)" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <!-- COACHING HISTORY TAB -->
                            <div class="tab-pane fade" id="nav-partner-criteria" role="tabpanel" aria-labelledby="nav-partner-criteria-tab">
                                <div class="form-box">
                                    <div class="meeting-time mt-0">
                                        <ul>
                                            <li>
                                                <h6>Session 4 of 12 (Static Placeholder)</h6>
                                                <p>Jul 14, 1:30 PM · Phone · Coach: Priya</p>
                                                <input type="text" class="form-control" placeholder="Worked on confidence in dating conversations. Homework: journal 3 wins this week." readonly>
                                            </li>
                                            <li>
                                                <h6>Session 3 of 12 (Static Placeholder)</h6>
                                                <p>Jul 7, 1:30 PM · Zoom · Coach: Priya</p>
                                                <input type="text" class="form-control" placeholder="Discussed limiting beliefs around worthiness." readonly>
                                            </li>
                                            <li>
                                                <h6>Session 2 of 12 (Static Placeholder)</h6>
                                                <p>Jun 30, 1:30 PM · Zoom · Coach: Priya</p>
                                                <input type="text" class="form-control" placeholder="Set program milestones and identified dating deal-breakers." readonly>
                                            </li>
                                            <li>
                                                <h6>Session 1 of 12 — Onboarding (Static Placeholder)</h6>
                                                <p>Jun 23, 1:30 PM · Zoom · Coach: Priya</p>
                                                <input type="text" class="form-control" placeholder="Intake and goal-setting for the Magnetic Woman program." readonly>
                                            </li>
                                        </ul>
                                        <p>Full session log with editing will live in a future Coaching Operations module.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- DOCUMENTS TAB (Using Photos) -->
                            <div class="tab-pane fade" id="nav-photos" role="tabpanel" aria-labelledby="nav-photos-tab">
                                <div class="review-user">
                                    <div class="form-box">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>Document / Photo</th>
                                                    <th>Type</th>
                                                    <th>Date Added</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($client->photos as $photo)
                                                    <tr>
                                                        <td>
                                                            <a href="{{ asset('storage/' . $photo->file_path) }}" target="_blank">
                                                                <img src="{{ asset('storage/' . $photo->file_path) }}" alt="Photo" style="height: 50px; width: 50px; object-fit: cover; border-radius: 4px; margin-right: 10px;">
                                                                {{ basename($photo->file_path) }}
                                                            </a>
                                                        </td>
                                                        <td>Photo</td>
                                                        <td>{{ $photo->created_at->format('M j, Y') }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3" class="text-center text-muted">No documents or photos uploaded yet.</td>
                                                    </tr>
                                                @endforelse
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
    </div>
</div>
@endsection
