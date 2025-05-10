@extends('layouts/adminContentNavbarLayout')

@section('title', 'Partner Service Report')

@section('content')
<div class="row g-6">
    <div class="col-md-12">
        <div class="card">
            <h5 class="card-header">Partner Service Report</h5>
            <div class="card-body">

                <!-- Filter Form -->
                <form method="GET" action="{{ route('admin.partner_service.report') }}" class="row g-3 mb-4">
                    <!-- <div class="col-md-4">
                        <label for="added_date" class="form-label">Added Date</label>
                        <input type="date" id="added_date" name="added_date" class="form-control" 
                               value="{{ request('added_date') }}">
                    </div>

                    <div class="col-md-4">
                        <label for="added_date" class="form-label">Logged Date</label>
                        <input type="date" id="added_date" name="logged_date" class="form-control" 
                               value="{{ request('logged_date') }}">
                    </div> -->

                    <div class="col-md-4">
                        <label for="added_date" class="form-label">From</label>
                        <input type="date" id="added_date" name="from" class="form-control" 
                               value="{{ request('from') }}">
                    </div>

                    <div class="col-md-4">
                        <label for="added_date" class="form-label">To</label>
                        <input type="date" id="added_date" name="to" class="form-control" 
                               value="{{ request('to') }}">
                    </div>

                    <div class="col-md-4">
    <label for="service_type" class="form-label">Service Type</label>
    <select id="service_type" name="service_type" class="form-select">
        <option value="">-- All --</option>
        @foreach($serviceTypes as $id => $name)
    <option value="{{ $id }}" {{ request('service_type') == $id ? 'selected' : '' }}>
        {{ $name }}
    </option>
@endforeach
    </select>
</div>

<div class="mb-3">
    <label for="product_types" class="form-label">Product Type</label>
    <select id="product_types" name="product_types" class="form-select">
        <option value="">-- All --</option>
        @foreach($productTypes as $type)
            <option value="{{ $type }}" {{ request('product_types') == $type ? 'selected' : '' }}>
                {{ $type }}
            </option>
        @endforeach
    </select>
</div>


                    <div class="mb-3">
    <label for="price_point" class="form-label">Price Point</label>
    <select id="price_point" name="price_point" class="form-select">
        <option value="">-- All --</option>
        @foreach($amounts as $amount)
        <option value="{{ $amount }}" {{ request('price_point') == $amount ? 'selected' : '' }}>
    ₦{{ number_format((float) $amount) }}
</option>

        @endforeach
    </select>
</div>


                    <div class="col-12 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </form>
                <div class="row mb-3">
    <div class="col">
        <div class="alert alert-primary  d-flex justify-content-center" style="gap:10px;">
        <div>
                <strong>Total Records:</strong> {{ $totalRecords }}
            </div>
            <div>
                <strong>Total Count:</strong> {{ number_format($totalCount) }} <br>
            </div>
            <div>
                <strong>Total Revenue:</strong> ₦{{ number_format($totalAmount, 2) }}
            </div>
        </div>
    </div>
</div>

                <!-- Table Display -->
                <div class="table-responsive text-wrap">
            <table class="table">
                <thead table-light>
                            <tr>
                                <th>#</th>
                                <th>Service Id</th>
                                <th>Product Name</th>
                                
                                <th>Price Point</th>
                                <th>Date</th>
                                <th>Count</th>
                                <th>Transaction</th>

                                <th>Total</th>

                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($partnerServices as $index => $service)
                                <tr>
                                    <td>{{ $partnerServices->firstItem() + $index }}</td>
                                    <td>
    {{ $serviceTypes[$service->service_id] ?? $service->service_id }}
</td>

                                    <td>{{ $service->product_name }}</td>
                                    <td>₦{{ $service->price_point }}</td>
                                    <td>{{ \Carbon\Carbon::parse($service->date)->format('l, jS F Y') }}</td>
                                    <td>{{ $service->count }}</td>
                                    <td>{{ $service->transaction }}</td>
                                    <td>₦{{ $service->revenue }}</td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No partner services found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-2 ">
                    {{ $partnerServices->withQueryString()->links('pagination::bootstrap-5') }}
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
