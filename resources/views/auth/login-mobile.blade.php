@extends('templates.auth-mobile')
@section('head')
  <style>
    body {
      background-color: #ffffff;
    }

    .login-mobile-wrap {
      max-width: 400px;
      margin: 0 auto;
      padding: 40px 24px 80px 24px;
    }

    .logo-brand-mobile {
      margin-bottom: 24px;
    }

    .login-title {
      font-size: 26px;
      font-weight: 800;
      color: #0f172a;
      margin-bottom: 6px;
    }

    .login-subtitle {
      color: #64748b;
      font-size: 14px;
      margin-bottom: 28px;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-label {
      font-size: 13px;
      font-weight: 600;
      color: #334155;
      margin-bottom: 6px;
    }

    .form-control {
      border-radius: 12px !important;
      padding: 12px 16px !important;
      border: 1.5px solid #cbd5e1 !important;
      font-size: 15px !important;
      background-color: #ffffff !important;
    }

    .form-control:focus {
      border-color: #0073e6 !important;
      box-shadow: 0 0 0 0.25rem rgba(0, 115, 230, 0.15) !important;
    }

    .login-btn {
      padding: 13px !important;
      margin-top: 10px !important;
      background: linear-gradient(135deg, #0073e6 0%, #00a651 100%) !important;
      border: none !important;
      box-shadow: 0 4px 14px rgba(0, 115, 230, 0.35) !important;
      font-weight: 800 !important;
      font-size: 16px !important;
      border-radius: 12px !important;
      width: 100% !important;
    }

    .forgot-password {
      color: #0073e6;
      font-weight: 600;
      font-size: 13px;
    }

    .footer {
      position: fixed;
      bottom: 0;
      left: 0;
      width: 100%;
      padding: 18px;
      text-align: center;
      font-size: 12px;
      color: #64748b;
      background: #ffffff;
    }

    .footer .watermark {
      color: #0073e6;
      font-weight: 700;
    }
  </style>
@endsection

@section('content')
  <div class="login-mobile-wrap">
    <div class="logo-brand-mobile text-center">
      <div class="d-inline-flex align-items-center gap-2">
        <img src="{{ asset('images/logo-sintesa.jpg') }}" alt="Logo Sintesa" style="height: 52px; width: auto; object-fit: contain;">
        <span class="h3 mb-0" style="font-weight: 900; letter-spacing: -0.5px;"><span style="color: #0073e6;">SINTESA</span> <span style="color: #00a651;">HRIS</span></span>
      </div>
    </div>

    <h1 class="login-title text-center">Sign in to your Account</h1>
    <p class="login-subtitle text-center">Enter your username and password to log in</p>

    <form id="loginForm" method="POST" action="{{ route('login') }}">
      @csrf
      <div class="form-group">
        <label class="form-label">Username</label>
        <input type="text" name="username" class="form-control" placeholder="Enter your username..." required>
      </div>

      <div class="form-group position-relative">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control pe-5" id="password" placeholder="••••••••" required>
        <button type="button" id="togglePassword"
          class="btn position-absolute end-0 translate-middle-y me-3 p-0 border-0 bg-transparent"
          style="top: 68%; max-width: max-content;">
          <i class="bi bi-eye-slash text-muted fs-5" id="toggleIcon"></i>
        </button>
      </div>

      <div class="text-end mb-3">
        <a href="{{ route('password.request') }}" class="forgot-password text-decoration-none">Forgot Password?</a>
      </div>

      @if ($errors->any())
        <div class="alert alert-danger rounded-3 text-center py-2 mb-3 small fw-semibold">
          {{ $errors->first() }}
        </div>
      @endif

      <button type="submit" class="btn btn-primary login-btn">Log In</button>
    </form>
  </div>

  <div class="footer">
    <p class="mb-0">All Rights Reserved &copy; {{ Date::now()->format('Y') }} <span class="watermark">PT Sintesa Talenta Asia</span></p>
  </div>

  <script>
    $(document).ready(function() {
      $('#togglePassword').on('click', function() {
        const passwordInput = $('#password');
        const icon = $('#toggleIcon');
        const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';

        passwordInput.attr('type', type);
        icon.toggleClass('bi-eye').toggleClass('bi-eye-slash');
      });
    });
  </script>
@endsection
