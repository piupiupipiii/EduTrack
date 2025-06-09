@extends('layouts.app')

@section('content')

<style>
    .gradient-header {
        background: linear-gradient(to right, #4f46e5, #7c3aed);
        color: white;
        border-top-left-radius: 0.5rem;
        border-top-right-radius: 0.5rem;
        padding: 1rem;
        text-align: center;
    }

    .gradient-button {
        background: linear-gradient(to right, #4f46e5, #7c3aed);
        border: none;
        color: white;
        font-weight: bold;
        padding: 0.75rem;
        border-radius: 0.375rem;
        width: 100%;
        transition: 0.3s ease;
    }

    .gradient-button:hover {
        opacity: 0.9;
    }
</style>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="gradient-header">
                    <h4>Register</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- Name Input -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                id="name" name="name" value="{{ old('name') }}" required autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email Input -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password Input -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirm Password Input -->
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="gradient-button">Register</button>
                    </form>

                    <!-- Login Link -->
                    <div class="mt-3 text-center">
                        <p>Already have an account? <a href="{{ route('login') }}">Login</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
