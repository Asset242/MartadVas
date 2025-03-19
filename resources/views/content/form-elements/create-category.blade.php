@extends('layouts/contentNavbarLayout')

@section('title', 'Basic Inputs - Forms')

@section('page-script')
    @vite('resources/assets/js/form-basic-inputs.js')
@endsection

@section('content')
    <div class="row g-6">
        <div class="col-md-12">
            <div class="card">
                <h5 class="card-header">Create Category</h5>
                <form form action="{{ route('categories.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="mb-4">
                            <label for="exampleFormControlInput1" class="form-label">Category name</label>
                            <input type="text" name="name" class="form-control" placeholder="category" />
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="my-3">
                          <button type="submit" class="btn btn-primary">Create Category</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
