@extends('layouts/contentNavbarLayout')

@section('title', 'Cards basic - UI elements')

@section('vendor-script')
    @vite('resources/assets/vendor/libs/masonry/masonry.js')
@endsection

@section('content')
    <!-- Examples -->
    <div class="row mb-12 g-6">
      @if (session('success'))
          <div class="alert alert-success">
              {{ session('success') }}
          </div>
      @endif

      @foreach($categories as $category)
          <div class="col-md-6 col-lg-4">
              <div class="card h-100">
                  <img class="card-img-top" src="{{ asset('assets/img/elements/2.jpg') }}" alt="Card image cap" />
                  <div class="card-body">
                      <h5 class="card-title">{{ $category->name }}</h5>
                      {{-- <a class="btn btn-outline-primary" href="{{ route('posts.index', ['category' => $category->id]) }}">View posts</a> --}}
                  </div>
              </div>
          </div>
      @endforeach
  </div>


@endsection
