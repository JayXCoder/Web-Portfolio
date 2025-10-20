@extends('layouts.app')

@section('title', 'Visitor Analytics')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 mb-0">Visitor Analytics</h1>
                    <div>
                        <a href="{{ route('admin.visitors.export') }}" class="btn btn-outline-primary">
                            <i class="fas fa-download"></i> Export Data
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Unique Visitors</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($stats['total_visitors']) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Total Page Views</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($stats['total_page_views']) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-eye fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Today's Visitors</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($stats['today_visitors']) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    This Month</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($stats['this_month_visitors']) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row mb-4">
            <!-- Daily Visitors Chart -->
            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Daily Visitors (Last 30 Days)</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-area">
                            <canvas id="dailyVisitorsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Device Types Chart -->
            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Device Types</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-pie pt-4 pb-2">
                            <canvas id="deviceTypesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Analytics Tables -->
        <div class="row">
            <!-- Most Visited Pages -->
            <div class="col-xl-6 col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Most Visited Pages</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Page</th>
                                        <th>Views</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($most_visited_pages as $page)
                                        <tr>
                                            <td>{{ $page->page_title ?: $page->page_url }}</td>
                                            <td>{{ number_format($page->total_views) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Browser Statistics -->
            <div class="col-xl-6 col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Browser Statistics</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Browser</th>
                                        <th>Visitors</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($visitors_by_browser as $browser)
                                        <tr>
                                            <td>{{ $browser->browser }}</td>
                                            <td>{{ number_format($browser->count) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Country Statistics -->
        @if ($visitors_by_country->count() > 0)
            <div class="row">
                <div class="col-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Visitor Locations</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Country</th>
                                            <th>City</th>
                                            <th>Visitors</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($visitors_by_country as $country)
                                            <tr>
                                                <td>{{ $country->country }}</td>
                                                <td>{{ $country->city ?: 'N/A' }}</td>
                                                <td>{{ number_format($country->count) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* Dark mode chart styling */
        .chart-area,
        .chart-pie {
            background: #1a1a1a;
            border-radius: 8px;
            padding: 1rem;
        }

        .card {
            background: #1a1a1a !important;
            border: 1px solid #333333 !important;
            color: #ffffff;
        }

        .card-header {
            background: #2a2a2a !important;
            border-bottom: 1px solid #333333 !important;
            color: #ffffff !important;
        }

        .table {
            color: #ffffff !important;
            background: #1a1a1a !important;
        }

        .table th {
            background: #2a2a2a !important;
            border-color: #333333 !important;
            color: #ffffff !important;
        }

        .table td {
            background: #1a1a1a !important;
            border-color: #333333 !important;
            color: #ffffff !important;
        }

        .table tbody tr {
            background: #1a1a1a !important;
        }

        .table tbody tr:hover {
            background: #2a2a2a !important;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #1a1a1a !important;
        }

        .table-striped tbody tr:nth-of-type(even) {
            background-color: #2a2a2a !important;
        }
    </style>

    <script>
        // Daily Visitors Chart
        const dailyData = @json($daily_stats);
        const dailyLabels = dailyData.map(item => item.date);
        const dailyVisitors = dailyData.map(item => item.visitors);
        const dailyPageViews = dailyData.map(item => item.page_views);

        const dailyCtx = document.getElementById('dailyVisitorsChart').getContext('2d');
        new Chart(dailyCtx, {
            type: 'line',
            data: {
                labels: dailyLabels,
                datasets: [{
                    label: 'Visitors',
                    data: dailyVisitors,
                    borderColor: '#8b5cf6',
                    backgroundColor: 'rgba(139, 92, 246, 0.2)',
                    tension: 0.1,
                    fill: true
                }, {
                    label: 'Page Views',
                    data: dailyPageViews,
                    borderColor: '#a855f7',
                    backgroundColor: 'rgba(168, 85, 247, 0.2)',
                    tension: 0.1,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            color: '#ffffff'
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            color: '#a0a0a0'
                        },
                        grid: {
                            color: '#333333'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#a0a0a0'
                        },
                        grid: {
                            color: '#333333'
                        }
                    }
                }
            }
        });

        // Device Types Chart
        const deviceData = @json($visitors_by_device);
        const deviceLabels = deviceData.map(item => item.device_type);
        const deviceCounts = deviceData.map(item => item.count);

        const deviceCtx = document.getElementById('deviceTypesChart').getContext('2d');
        new Chart(deviceCtx, {
            type: 'doughnut',
            data: {
                labels: deviceLabels,
                datasets: [{
                    data: deviceCounts,
                    backgroundColor: [
                        '#8b5cf6',
                        '#a855f7',
                        '#c084fc',
                        '#10b981',
                        '#f59e0b'
                    ],
                    borderColor: '#1a1a1a',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            color: '#ffffff',
                            font: {
                                size: 12
                            }
                        }
                    }
                }
            }
        });
    </script>
@endsection
