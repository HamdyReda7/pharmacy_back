@extends('layouts.auth')

@section('title', 'Confirm Password')

@section('subtitle', 'Please confirm your password')

@section('content')

    <form method="POST" action="{{ route('password.confirm') }}">

        @csrf

        <div class="alert alert-info">

            This is a secure area. Please confirm your password to continue.

        </div>

        <div class="mb-4">

            <label class="form-label">

                Password

            </label>

            <div class="position-relative">

                <input id="password" type="password" name="password"
                    class="form-control pe-5 @error('password') is-invalid @enderror" required
                    autocomplete="current-password">

                <button type="button"
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

        <button type="submit" class="btn btn-primary w-100 py-2">

            Confirm Password

        </button>

        <div class="text-center mt-4">

            <a href="{{ route('login') }}">

                Back to Login

            </a>

        </div>

    </form>

@endsection
