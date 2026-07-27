@extends('layouts.auth')

@section('title', 'Reset Password')

@section('subtitle', 'Create a New Password')

@section('content')

<form method="POST" action="{{ route('password.store') }}">

    @csrf

    <input type="hidden" name="token" value="{{ $request->route('token') }}">

    <div class="mb-3">

        <label class="form-label">

            Email Address

        </label>

        <input
            type="email"
            name="email"
            value="{{ old('email', $request->email) }}"
            class="form-control @error('email') is-invalid @enderror"
            required
            autofocus>

        @error('email')

            <div class="invalid-feedback">

                {{ $message }}

            </div>

        @enderror

    </div>

    <div class="mb-3">

        <label class="form-label">

            New Password

        </label>

        <div class="position-relative">

            <input
                id="password"
                type="password"
                name="password"
                class="form-control pe-5 @error('password') is-invalid @enderror"
                required>

            <button
                type="button"
                class="btn position-absolute top-50 end-0 translate-middle-y border-0 bg-transparent toggle-password"
                data-target="password">

                <i class="fa-regular fa-eye"></i>

            </button>

        </div>

        @error('password')

            <div class="invalid-feedback d-block">

                {{ $message }}

            </div>

        @enderror

    </div>

    <div class="mb-4">

        <label class="form-label">

            Confirm Password

        </label>

        <div class="position-relative">

            <input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                class="form-control pe-5 @error('password_confirmation') is-invalid @enderror"
                required>

            <button
                type="button"
                class="btn position-absolute top-50 end-0 translate-middle-y border-0 bg-transparent toggle-password"
                data-target="password_confirmation">

                <i class="fa-regular fa-eye"></i>

            </button>

        </div>

        @error('password_confirmation')

            <div class="invalid-feedback d-block">

                {{ $message }}

            </div>

        @enderror

    </div>

    <button type="submit" class="btn btn-primary w-100 py-2">

        Reset Password

    </button>

    <div class="text-center mt-4">

        <a href="{{ route('login') }}">

            Back to Login

        </a>

    </div>

</form>

@endsection