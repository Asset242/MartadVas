@extends('layouts/adminContentNavbarLayout')

@section('title', 'Create Partner')

@section('page-script')
@vite('resources/assets/js/form-basic-inputs.js')

@section('content')
<div class="row g-6">
    <div class="col-md-12">
        <div class="card">
            <h5 class="card-header">Create Product</h5>
            <div class="card-body">
                <!-- Display Success Message -->
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Display Validation Errors -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            <form action="{{ route('admin.create.product') }}" method="POST">
                @csrf
                <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="partner" class="form-label">Select Partner</label>
                        <select id="partner" name="user_id" class="form-control">
                            <option value="" disabled selected>Select a partner</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        @error('partner_id')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label">Select Plan(s)</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="plans[]" value="day" id="plan-day">
                            <label class="form-check-label" for="plan-day">Day</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="plans[]" value="week" id="plan-week">
                            <label class="form-check-label" for="plan-week">Week</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="plans[]" value="month" id="plan-month">
                            <label class="form-check-label" for="plan-month">Month</label>
                        </div>
                        @error('plans')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label for="password" class="form-label">Coin Bonus</label>
                    <input type="integer" name="coin" class="form-control" placeholder="Coin Bonus" />
                    @error('password')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="my-3">
                    <button type="submit" class="btn btn-warning">Create Product</button>
                </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
