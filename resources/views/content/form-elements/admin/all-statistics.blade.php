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
                    <div class="col-md-4">
                        <label for="added_date" class="form-label">Added Date</label>
                        <input type="date" id="added_date" name="added_date" class="form-control" 
                               value="{{ request('added_date') }}">
                    </div>

                    <div class="col-md-4">
                        <label for="added_date" class="form-label">Logged Date</label>
                        <input type="date" id="added_date" name="logged_date" class="form-control" 
                               value="{{ request('logged_date') }}">
                    </div>

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
        <option value="">-- Select Service Type --</option>
        @foreach($serviceTypes as $type)
            <option value="{{ $type }}" {{ request('service_type') == $type ? 'selected' : '' }}>
                {{ $type }}
            </option>
        @endforeach
    </select>
</div>

                    <div class="col-md-4">
                        <label for="charge_amount" class="form-label">Charge Amount</label>
                        <input type="text" id="charge_amount" name="charge_amount" class="form-control"
                               value="{{ request('charge_amount') }}" placeholder="Charge Amount">
                    </div>

                    <div class="col-12 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </form>

                <!-- Table Display -->
                <div class="table-responsive text-wrap">
            <table class="table">
                <thead table-light>
                            <tr>
                                <th>#</th>
                                <th>Service Type</th>
                                <th>Charge Amount</th>
                                <th>Added Date</th>
                                <th>Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($partnerServices as $index => $service)
                                <tr>
                                    <td>{{ $partnerServices->firstItem() + $index }}</td>
                                    <td>{{ $service->service_type }}</td>
                                    <td>{{ $service->charge_amount }}</td>
                                    <td>{{ $service->added_date }}</td>
                                    <td>{{ $service->count }}</td>
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
