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
                    type: 'line',
                    height: 350,
                    toolbar: { show: false }
                },
                series: [{
                    name: 'Revenue',
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
                colors: ['#696CFF'],
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                markers: {
                    size: 5,
                    colors: ['#696CFF'],
                    strokeColors: '#fff',
                    strokeWidth: 2,
                    hover: { size: 7 }
                },
                dataLabels: {
                    enabled: true,
                    formatter: function (val) {
                        return "₦" + val.toLocaleString();
                    }
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return "₦" + val.toLocaleString();
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

    @php
        $cards = [
            ['title' => 'Total Revenue All Time', 'amount' => $totalRevenueAllTime, 'icon' => 'wallet.png'],
            ['title' => 'Total Revenue This Month', 'amount' => $totalRevenueThisMonth, 'icon' => 'wallet.png'],
            ['title' => 'Revenue Yesterday', 'amount' => $totalRevenueYesterday, 'icon' => 'chart-success.png'],
            ['title' => 'Revenue Today', 'amount' => $totalSalesToday, 'icon' => 'wallet-info.png'],
        ];
    @endphp

    @foreach ($cards as $card)
    <div class="col-lg-3 col-md-6 col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between">
                    <div class="avatar flex-shrink-0">
                        <img src="{{ asset('assets/img/icons/unicons/' . $card['icon']) }}" alt="icon" class="rounded">
                    </div>
                </div>
                <span>{{ $card['title'] }}</span>
                <h3 class="card-title text-nowrap mb-1">₦{{ number_format($card['amount']) }}</h3>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Chart Card -->
    <div class="col-12 mt-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Last 6 Months Revenue Trend</h5>
                <div id="revenueChart"></div>
            </div>
        </div>
    </div>
</div>
@endsection
