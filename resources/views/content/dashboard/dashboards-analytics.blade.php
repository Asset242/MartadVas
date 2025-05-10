@extends('layouts/contentNavbarLayout')

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
            const totalRevenueChart = new ApexCharts(document.querySelector("#totalRevenueChar"), {
                chart: { type: 'line', height: 350, toolbar: { show: false } },
                series: [{
                    name: 'Total Revenue',
                    data: [
                        @foreach($lastSixMonths as $data)
                            {{ $data['total'] }},
                        @endforeach
                    ]
                }],
                xaxis: {
                    categories: [
                        @foreach($lastSixMonths as $data)
                            '{{ $data['month'] }}',
                        @endforeach
                    ]
                },
                stroke: { curve: 'smooth', width: 3 },
                colors: ['#696CFF'],
                tooltip: { y: { formatter: val => "₦" + val.toLocaleString() } },
                dataLabels: { enabled: true, formatter: val => "₦" + val.toLocaleString() }
            });

            totalRevenueChart.render();

            const partnerRevenueChart = new ApexCharts(document.querySelector("#partnerRevenueChart"), {
                chart: { type: 'line', height: 350, toolbar: { show: false } },
                series: [{
                    name: 'Partner Revenue',
                    data: [
                        @foreach($partnerRevenueTrend as $data)
                            {{ $data['total'] }},
                        @endforeach
                    ]
                }],
                xaxis: {
                    categories: [
                        @foreach($partnerRevenueTrend as $data)
                            '{{ $data['month'] }}',
                        @endforeach
                    ]
                },
                stroke: { curve: 'smooth', width: 3 },
                colors: ['#00B894'],
                tooltip: { y: { formatter: val => "₦" + val.toLocaleString() } },
                dataLabels: { enabled: true, formatter: val => "₦" + val.toLocaleString() }
            });

            partnerRevenueChart.render();
        });
    </script>
@endsection

@section('content')
<div class="row">
    <div class="col-xxl-8 mb-6 order-0">
        <div class="card">
            <div class="d-flex align-items-start row">
                <div class="col-sm-7">
                    <div class="card-body">
                        <h5 class="card-title text-primary mb-3">Welcome {{$user->service_name}}, Admin! 🎉</h5>
                        <p class="mb-6">Get live reports <br>Analysis and Updates on your services</p>
                    </div>
                </div>
                <div class="col-sm-5 text-center text-sm-left">
                    <div class="card-body pb-0 px-0 px-md-6">
                        <img src="{{ asset('assets/img/illustrations/handshake.png') }}" height="175" class="scaleX-n1-rtl" alt="View Badge User">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Cards -->
    <div class="col-lg-4 col-md-4 order-1">
        <div class="row">
            <div class="col-lg-6 col-md-12 col-6 mb-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="avatar flex-shrink-0 mb-4">
                            <img src="{{ asset('assets/img/icons/unicons/cc-warning.png') }}" alt="chart success" class="rounded">
                        </div>
                        <p class="mb-1">Revenue All Time</p>
                        <h4 class="card-title mb-3" style="font-size: 15px">₦{{ number_format($totalRevenueAllTime, 2) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 col-6 mb-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="avatar flex-shrink-0 mb-4">
                            <img src="{{ asset('assets/img/icons/unicons/cc-primary.png') }}" alt="Credit Card" class="rounded">
                        </div>
                        <p class="mb-1">Total Count</p>
                        <h4 class="card-title mb-3" style="font-size: 15px">{{ $totalCountAllTime }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Revenue Chart -->
    <div class="col-12 col-xxl-8 order-2 order-md-3 order-xxl-2 mb-6">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="m-0 me-2">Revenue Trend (Last 6 Months)</h5>
            </div>
            <div id="totalRevenueChar" class="px-3"></div>
        </div>
    </div>

    <!-- Partner Revenue Chart
    <div class="col-12 col-xxl-8 order-4 mb-6">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="m-0 me-2">Partner Revenue Trend</h5>
            </div>
            <div id="partnerRevenueChart" class="px-3"></div>
        </div>
    </div> -->

    <!-- This Month Stats -->
    <div class="col-12 col-md-8 col-lg-12 col-xxl-4 order-3 order-md-2">
        <div class="row">
            <div class="col-6 mb-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="avatar flex-shrink-0 mb-4">
                            <img src="{{ asset('assets/img/icons/unicons/cc-warning.png') }}" alt="paypal" class="rounded">
                        </div>
                        <p class="mb-1">Revenue This Month</p>
                        <h4 class="card-title mb-3" style="font-size: 15px">₦{{ number_format($totalRevenueThisMonth, 2) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-6 mb-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="avatar flex-shrink-0 mb-4">
                            <img src="{{ asset('assets/img/icons/unicons/cc-primary.png') }}" alt="Credit Card" class="rounded">
                        </div>
                        <p class="mb-1">Count This Month</p>
                        <h4 class="card-title mb-3" style="font-size: 15px">{{ $totalCountThisMonth }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
