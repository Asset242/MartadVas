@extends('layouts/adminContentNavbarLayout')

@section('title', 'Admin Dashboard')

@section('vendor-style')
    @vite('resources/assets/vendor/libs/apex-charts/apex-charts.scss')
@endsection

@section('vendor-script')
    @vite('resources/assets/vendor/libs/apex-charts/apexcharts.js')
@endsection

@section('page-script')
    @vite('resources/assets/js/dashboards-analytics.js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const options = {
                chart: {
                    type: 'bar',
                    height: 350
                },
                series: [{
                    name: 'Revenue',
                    data: [
                        {{ $totalRevenueAllTime }},
                        {{ $totalRevenueThisMonth }},
                        {{ $totalRevenueYesterday }},
                        {{ $totalSalesToday }}
                    ]
                }],
                xaxis: {
                    categories: [
                        'All Time',
                        'This Month',
                        'Yesterday',
                        'Today'
                    ]
                },
                colors: ['#696CFF'],
                plotOptions: {
                    bar: {
                        borderRadius: 5,
                        horizontal: false
                    }
                },
                dataLabels: {
                    enabled: true,
                    formatter: function (val) {
                        return "₦" + val;
                    }
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return "₦" + val;
                        }
                    }
                }
            };

            const chart = new ApexCharts(document.querySelector("#revenueChart"), options);
            chart.render();
        });
    </script>
@endsection

@section('content') 
<div class="row">
    <!-- Total Revenue All Time Card -->
    <div class="col-lg-3 col-md-6 col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0">
                        <img src="{{ asset('assets/img/icons/unicons/wallet.png') }}" alt="Credit Card" class="rounded">
                    </div>
                </div>
                <span>Total Revenue All Time</span>
                <h3 class="card-title text-nowrap mb-1">₦{{ number_format($totalRevenueAllTime) }}</h3>
            </div>
        </div>
    </div>

    <!-- Revenue This Month Card -->
    <div class="col-lg-3 col-md-6 col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0">
                        <img src="{{ asset('assets/img/icons/unicons/wallet.png') }}" alt="Credit Card" class="rounded">
                    </div>
                </div>
                <span>Total Revenue This Month</span>
                <h3 class="card-title text-nowrap mb-1">₦{{ number_format($totalRevenueThisMonth) }}</h3>
            </div>
        </div>
    </div>

    <!-- Revenue Yesterday Card -->
    <div class="col-lg-3 col-md-6 col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0">
                        <img src="{{ asset('assets/img/icons/unicons/chart-success.png') }}" alt="chart success" class="rounded">
                    </div>
                </div>
                <span class="fw-semibold d-block mb-1">Revenue Yesterday</span>
                <h3 class="card-title mb-2">₦{{ number_format($totalRevenueYesterday) }}</h3>
            </div>
        </div>
    </div>

    <!-- Revenue Today Card -->
    <div class="col-lg-3 col-md-6 col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0">
                        <img src="{{ asset('assets/img/icons/unicons/wallet-info.png') }}" alt="Credit Card" class="rounded">
                    </div>
                </div>
                <span>Revenue Today</span>
                <h3 class="card-title text-nowrap mb-1">₦{{ number_format($totalSalesToday) }}</h3>
            </div>
        </div>
    </div>

    <!-- Chart Card Below Cards -->
    <div class="col-12 mt-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Revenue Overview</h5>
                <div id="revenueChart"></div>
            </div>
        </div>
    </div>
</div>
@endsection
