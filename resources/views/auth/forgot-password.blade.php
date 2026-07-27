@extends('layouts.auth')

@section('title', 'Forgot Password')

@section('subtitle', 'Forgot your password?')

@section('content')

    @if (session('status'))
        <div class="alert alert-success">

            {{ session('status') }}

        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">

        @csrf

        <div class="mb-4">

            <label class="form-label">

                Email Address

            </label>

            <input type="email" name="email" value="{{ old('email') }}"
                class="form-control @error('email') is-invalid @enderror" required autofocus>

            @error('email')
                <div class="invalid-feedback">

                    {{ $message }}

                </div>
            @enderror

        </div>

        <button type="submit" class="btn btn-primary w-100 py-2">

            Send Reset Link

        </button>

        <div class="text-center mt-4">

            <a href="{{ route('login') }}">

                Back to Login

            </a>

        </div>

    </form>

@endsection
