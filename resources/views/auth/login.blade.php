@extends('layouts.app') <!-- Menambahkan layout -->

@section('title', 'Login') <!-- Judul Halaman Login -->

@section('content') <!-- Konten halaman login -->

<style>
    /* Gradient untuk header dan tombol */
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
                    <h4>Login</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email Input -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email"
                                class="form-control @error('email') is-invalid @enderror"
                                id="email" name="email"
                                value="{{ old('email') }}"
                                required autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password Input -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password"
                                class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password"
                                required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input"
                                id="remember" name="remember">
                            <label class="form-check-label" for="remember">Remember Me</label>
                        </div>

                        <!-- Login Button -->
                        <button type="submit" class="gradient-button">Login</button>
                    </form>

                    <!-- Register Link -->
                    <div class="mt-3 text-center">
                        <p>Don't have an account? <a href="{{ route('register') }}">Register</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
