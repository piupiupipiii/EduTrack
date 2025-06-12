@extends('layouts.app')

@section('title', 'Register')

@section('content')

<style>
    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
        font-family: 'Poppins', sans-serif;
    }

    .gradient-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-top-left-radius: 0.5rem;
        border-top-right-radius: 0.5rem;
        padding: 1.5rem;
        text-align: center;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .card {
        border: none;
        border-radius: 0.5rem;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    }

    .gradient-button {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        color: white;
        font-weight: 600;
        padding: 0.75rem;
        border-radius: 0.375rem;
        width: 100%;
        transition: all 0.3s ease;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 6px rgba(102, 126, 234, 0.2);
    }

    .gradient-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 7px 14px rgba(102, 126, 234, 0.3);
    }

    .gradient-button:active {
        transform: translateY(0);
    }

    .form-control {
        border: 1px solid #e2e8f0;
        padding: 0.75rem;
        border-radius: 0.375rem;
        transition: border-color 0.2s ease;
    }

    .form-control:focus {
        border-color: #a0aec0;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .form-check-input:checked {
        background-color: #667eea;
        border-color: #667eea;
    }

    a {
        color: #667eea;
        text-decoration: none;
        font-weight: 500;
    }

    a:hover {
        color: #764ba2;
        text-decoration: underline;
    }

    .invalid-feedback {
        color: #e53e3e;
        font-size: 0.875rem;
    }
</style>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card">
                <div class="gradient-header">
                    <h4 class="mb-0">Register</h4>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- Name -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-medium">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                id="name" name="name" value="{{ old('name') }}" required autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="form-label fw-medium">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <label for="password" class="form-label fw-medium">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-medium">Confirm Password</label>
                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation" required>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="gradient-button mb-3">Register</button>
                    </form>

                    <!-- Login Redirect -->
                    <div class="mt-3 text-center">
                        <p class="mb-0">Already have an account? <a href="{{ route('login') }}">Login here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection