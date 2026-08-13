@extends('layouts.app')

@section('title', 'Founder-Only Financial Dashboard')

@section('content')
    <div class="col-lg-9 col-md-9 col-12 p-0">
        <div class="dashboard-layout">
            <div class="dashboard-top">
                <div class="dashboard-heading">
                    <h4>Founder-Only Financial Dashboard</h4>
                    <p>
                        Revenue, profit, sales performance
                    </p>
                </div>
            </div>
            <div class="box-info-detail">

                <div class="tab-icon-btn">
                    <ul>
                        <li>
                            <p> Admin / Founder · <span>Full — only role that can open this page</span></p>
                        </li>
                        <li>
                            <p> Every other role (incl. Closer) · <span>None</span></p>
                        </li>
                    </ul>

                </div>
                <div class="activety-details">
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-12">
                            <div class="box-achivement">
                                <h4>${{ number_format($revenueThisMonth) }}</h4>
                                <p>Revenue — This Month</p>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-12">
                            <div class="box-achivement">
                                <h4>${{ number_format($revenueYTD) }}</h4>
                                <p>Revenue — YTD</p>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-12">
                            <div class="box-achivement">
                                <h4>${{ number_format($profitThisMonth) }}</h4>
                                <p>Profit — This Month (Est. 30%)</p>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-12">
                            <div class="box-achivement">
                                <h4>{{ $renewalRate }}%</h4>
                                <p>Renewal Rate</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="time-follow">
                    <div class="row">

                        <div class="col-lg-6 col-md-6 col-12">
                            <div class="review-user">
                                <div class="form-box pt-3">
                                    <label for="">Sales Performance by Closer
                                    </label>
                                    <table class="team-users">
                                        <thead>
                                            <tr>
                                                <th>Closer</th>
                                                <th>Won</th>
                                                <th>Revenue</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($closerPerformance as $closer)
                                            <tr>
                                                <td>{{ $closer['name'] }}</td>
                                                <td>{{ $closer['won_percentage'] }}%</td>
                                                <td>${{ number_format($closer['revenue']) }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="3" class="text-center text-muted">No sales performance data available.</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>

                                </div>
                                {{-- <div class="form-btn mt-3">
                                    <button class="btn web-btn" onclick="alert('Export functionality coming soon.')">Export Financial Report (Admin only)</button>
                                </div> --}}

                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-12">
                            <div class="review-user">
                                <form action="" onsubmit="event.preventDefault();">
                                    <div class="form-box">
                                        <div class="row">
                                            <div class="col-12">
                                                <label for="">Revenue by Package
                                                </label>
                                            </div>

                                            @forelse($revenueByPackage as $package)
                                            <div class="col-12">
                                                <label for="">{{ $package['package_name'] }}</label>
                                                <input type="text" class="form-control" value="${{ number_format($package['revenue']) }}" readonly>
                                            </div>
                                            @empty
                                            <div class="col-12">
                                                <p class="text-muted">No package revenue data available.</p>
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
