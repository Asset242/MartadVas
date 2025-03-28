@extends('layouts/adminContentNavbarLayout')

@section('title', 'Create Partner')


@section('page-script')
@vite('resources/assets/js/form-basic-inputs.js')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const passwordField = document.getElementById('partnerPassword');
        passwordField.value = generateSimplePassword();

        function generateSimplePassword() {
            const words = [
            'apple', 'orange', 'banana', 'grape', 'cherry', 'peach', 'mango', 
            'berry', 'melon', 'lemon', 'pineapple', 'kiwi', 'plum', 'pear', 
            'papaya', 'apricot', 'fig', 'guava', 'lychee', 'nectarine', 'date', 
            'pomegranate', 'coconut', 'blueberry', 'strawberry', 'raspberry', 
            'blackberry', 'watermelon', 'cantaloupe', 'tangerine', 'passionfruit', 
            'dragonfruit', 'cranberry', 'currant', 'persimmon', 'jackfruit', 
            'kumquat', 'mulberry', 'olive', 'avocado', 'quince', 'gooseberry'
            ];
            const password = [];
            for (let i = 0; i < 3; i++) { // Select three random words
                password.push(words[Math.floor(Math.random() * words.length)]);
            }
            return password.join('-'); // Join with a separator like '-'
        }
    });
</script>

@endsection

@section('content')
<div class="row g-6">
    <div class="col-md-12">
        <div class="card">
            <h5 class="card-header">Create Partner</h5>
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
            <form action="{{ route('admin.create.partner') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="username" class="form-label">Partner Username</label>
                            <input type="text" id="username" name="name" class="form-control" placeholder="Username" />
                            @error('username')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Partner Email</label>
                            <input type="email" id="email" name="email" class="form-control" placeholder="Email" />
                            @error('email')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                            
                        </div>
                    </div>
                    <div class="form-group mb-4">
                    <label for="password" class="form-label">Password</label>
                            <input type="text" id="partnerPassword" name="password" class="form-control" placeholder="Password" />
                            @error('password')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                    </div>
                    <p><h6 class="text-primary">Please take-note of the partner's auto-generated password, as it will no longer be displayed after creating the partner</h6></p>

                    <div class="my-3">
                        <button type="submit" class="btn btn-warning">Create Partner</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
