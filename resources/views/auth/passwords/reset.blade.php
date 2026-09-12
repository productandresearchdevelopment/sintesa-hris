@extends('headers.head')

@section('header')
  <style>
    .auth-container {
      display: flex;
      height: 100vh;
    }

    .auth-left {
      width: 40%;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 40px;
    }

    .auth-right {
      width: 60%;
      background: url("{{ asset('templates/auth/images/auth.jpg') }}") no-repeat center center;
      background-size: cover;
    }
  </style>
@endsection

@section('body')
  <div class="auth-container">
    <div class="auth-left">
      <div style="width: 100%; max-width: 400px;">
        <div class="text-center mb-4">
          <div class="auth-brand-badge d-inline-flex align-items-center gap-3">
            <img src="{{ asset('images/logo-sintesa.jpg') }}" alt="Logo Sintesa" style="height: 52px; width: auto; object-fit: contain;">
            <span class="h3 fw-bold mb-0" style="letter-spacing: -0.5px;"><span style="color: #0073e6;">SINTESA</span> <span style="color: #00a651;">HRIS</span></span>
          </div>
        </div>

        <h3 class="text-center fw-bold">Change Password</h3>
        <p class="text-center text-muted">Enter your new password!</p>

        @if ($errors->any())
          <div class="alert alert-danger">
            <ul>
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        @if (session('status'))
          <div class="alert alert-success">
            {{ session('status') }}
          </div>
        @endif

        <form action="{{ route('password.update') }}" method="POST">
          @csrf
          <div class="form-group mb-3">
            <label class="fw-semibold" for="email">Email</label>
            <input type="text" name="email" class="form-control rounded-pill" placeholder="Email"
              value="{{ $email }}" readonly>
          </div>

          <div class="form-group mb-3">
            <label class="fw-semibold" for="password">Password</label>
            <input type="password" name="password" class="form-control rounded-pill" placeholder="Password">
          </div>

          <div class="form-group mb-3">
            <label class="fw-semibold" for="password-confirm">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control rounded-pill"
              placeholder="Confirm Password">
          </div>

          <input type="hidden" name="token" value="{{ $token }}">

          <div class="form-group">
            <button type="submit" class="btn w-100 text-white fw-semibold rounded-pill"
              style="background-color: #1565c0;">Reset Password</button>
          </div>

          <div class="form-group">
            <a href="{{ route('login') }}" class="btn w-100 text-muted fw-semibold rounded-pill"
              style="background-color: #f1f1f1;">BACK TO LOGIN PAGE</a>
          </div>
        </form>


        <div class="text-center mt-4 d-flex justify-content-center align-items-center gap-3">
          <small class="text-muted me-2" style="font-size: 0.75rem;">&copy; {{ Date::now()->format('Y') }} PT SINTESA TALENTA ASIA</small>
        </div>
      </div>
    </div>

    <div class="auth-right"></div>
  </div>
@endsection
