@extends('layouts.auth')

@section('title', 'Verify Email')

@section('subtitle', 'Verify your email address')

@section('content')

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success">

            A new verification link has been sent to your email address.

        </div>
    @endif

    <div class="alert alert-info">

        Please verify your email address by clicking the link we sent to your inbox.
        If you didn't receive the email, you can request another one.

    </div>

    <form method="POST" action="{{ route('verification.send') }}">

        @csrf

        <button type="submit" class="btn btn-primary w-100 py-2 mb-3">

            Resend Verification Email

        </button>

    </form>

    <form method="POST" action="{{ route('logout') }}">

        @csrf

        <button type="submit" class="btn btn-outline-danger w-100">

            Logout

        </button>

    </form>

@endsection
