@extends('layouts.app')

@section('title', 'Dashboard & Reporting')

@section('content')
    <div class="col-lg-9 col-md-9 col-12 p-0">
        <div class="dashboard-layout">
            <div class="dashboard-top">
                <div class="dashboard-heading">
                    <h4>Dashboard & Reporting</h4>
                    <p>
                        Role-based performance views
                    </p>
                </div>
            </div>
            <div class="box-info-detail">

                {{-- <div class="tab-icon-btn">
                    <ul>
                        <li>
                            <p> Admin · <span>Full, all data</span></p>
                        </li>
                        <li>
                            <p> Each role · <span>Own performance only</span></p>
                        </li>
                        <li>
                            <p> Billing · <span>Payment status only, no revenue totals</span></p>
                        </li>
                    </ul>

                </div> --}}
                <div class="activety-details">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-12">
                            <div class="box-achivement">
                                <h4>{{ $newLeads7Days }}</h4>
                                <p>New Leads (7 Days)</p>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-12">
                            <div class="box-achivement">
                                <h4>{{ $leadQualifiedRate }}%</h4>
                                <p>Lead → Qualified Rate</p>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-12">
                            <div class="box-achivement">
                                <h4>{{ $activeClientsCount }}</h4>
                                <p>Active Clients in Matching</p>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="time-follow">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-12">
                            <div class="review-user">
                                <form action="" onsubmit="event.preventDefault();">
                                    <div class="form-box">
                                        <div class="row">
                                            <div class="col-12">
                                                <label for="">Pipeline Summary
                                                </label>
                                            </div>
                                            <div class="col-12">
                                                <label for="">New </label>
                                                <input type="text" class="form-control" value="{{ $pipelineNew }}" readonly>
                                            </div>
                                            <div class="col-12">
                                                <label for="">Qualified</label>
                                                <input type="text" class="form-control" value="{{ $pipelineQualified }}" readonly>
                                            </div>
                                            <div class="col-12">
                                                <label for="">Closer Queue</label>
                                                <input type="text" class="form-control" value="{{ $pipelineCloserQueue }}" readonly>
                                            </div>
                                            <div class="col-12">
                                                <label for="">Active Clients</label>
                                                <input type="text" class="form-control" value="{{ $activeClientsCount }}" readonly>
                                            </div>

                                        </div>

                                    </div>

                                    {{-- <div class="form-btn mt-3">
                                        <button type="button" class="btn web-btn" onclick="alert('Export CSV functionality coming soon.')">Export CSV</button>
                                        <button type="button" class="btn web-btn" onclick="alert('Export PDF functionality coming soon.')">Export PDF</button>
                                    </div> --}}

                                </form>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-12">
                            <div class="review-user">
                                <form action="" onsubmit="event.preventDefault();">
                                    <div class="form-box">
                                        <div class="row">
                                            <div class="col-12">
                                                <label for="">Lead Source Performance (Admin)
                                                </label>
                                            </div>

                                            @forelse($sourcePerformance as $source => $percentage)
                                            <div class="col-12">
                                                <label for="">{{ $source == 'Website' ? 'Website — Apply Form' : $source }}</label>
                                                <input type="text" class="form-control" value="{{ $percentage }}%" readonly>
                                            </div>
                                            @empty
                                            <div class="col-12">
                                                <p class="text-muted">No lead source data available.</p>
                                            </div>
                                            @endforelse

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
