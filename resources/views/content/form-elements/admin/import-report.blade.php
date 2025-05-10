@extends('layouts/adminContentNavbarLayout')

@section('title', 'Import VAS Report')

@section('page-script')
@vite('resources/assets/js/form-basic-inputs.js')
@endsection

@section('content')
<div class="row g-6">
    <div class="col-md-12">
        <div class="card">
            <h5 class="card-header">Import VAS Excel Report</h5>
            <div class="card-body">

                <!-- Success Message -->
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <!-- Error Message -->
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <!-- Validation Errors -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.import.vas') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="file" class="form-label">Select Excel File</label>
                        <input type="file" name="file" id="file" class="form-control" accept=".xlsx,.xls">
                        @error('file')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Import</button>
                </form>
                @if ($logs->count())
    <div class="mt-4 card">
        <h5 class="card-header">Previous Import Logs</h5>
        <div class="card-body table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Imported At</th>
                        <th>Records Inserted</th>
                        <th>Total Revenue (₦)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($logs as $log)
                        <tr>
                            <td>{{ $log->imported_at->format('Y-m-d H:i') }}</td>
                            <td>{{ $log->records_inserted }}</td>
                            <td>₦{{ number_format($log->total_revenue, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif


            </div>
        </div>
    </div>
</div>
@endsection
