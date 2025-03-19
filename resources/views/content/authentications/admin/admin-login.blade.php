@extends('layouts/blankLayout')

@section('title', 'Martad-Admin - Login')

@section('page-style')
@vite([
  'resources/assets/vendor/scss/pages/page-auth.scss'
])
@endsection

@section('content')
<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner">
      <!-- Register -->
      <div class="card px-sm-6 px-0">
        <div class="card-body" style="color:azure">
          <!-- Logo -->
          <div class="app-brand justify-content-center">
            <a href="{{url('/')}}" class="app-brand-link gap-2">
              <!-- <span class="app-brand-logo demo">@include('_partials.macros',["width"=>25,"withbg"=>'var(--bs-primary)'])</span> -->
              <span class="app-brand-text demo text-heading fw-bold">Martad Admin</span>
            </a>
          </div>
          <!-- /Logo -->
          <center><h4 class="mb-1">Welcome to Martad Admin 👨‍💻</h4> </center>
          @error('message')
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                @enderror

          <form id="formAuthentication" class="mb-6" action="{{ route('martad.admin.login') }}" method="POST">
            @csrf
            <div class="mb-6">
              <label for="email" class="form-label">✉️Email</label>
              <input type="text" class="form-control" id="email" name="email" placeholder="Enter your email or username" autofocus>
              
            </div>
            <div class="mb-6 form-password-toggle">
              <label class="form-label" for="password">🔒Password</label>
              <div class="input-group input-group-merge">
                <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
              </div>
            </div>
           
            <div class="mb-6">
              <button class="btn btn-warning d-grid w-100" type="submit">Login</button>
            </div>
          </form>

           
        </div>
      </div>
    </div>
    <!-- /Register -->
  </div>
</div>
@endsection
