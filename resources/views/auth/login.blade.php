@extends('headers.head')

@section('header')
  <style>
    .auth-container {
      display: flex;
      height: 100vh;
      background-color: #f8fafc;
    }

    .auth-left {
      width: 40%;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 40px;
      background: #ffffff;
    }

    .auth-right {
      width: 60%;
      background: linear-gradient(135deg, rgba(0, 115, 230, 0.92) 0%, rgba(0, 166, 81, 0.88) 100%), url("{{ asset('templates/auth/images/auth.jpg') }}") no-repeat center center;
      background-size: cover;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      color: #ffffff;
      padding: 60px;
      position: relative;
    }

    .auth-brand-badge {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 14px;
      margin-bottom: 24px;
    }

    .auth-brand-icon {
      width: 52px;
      height: 52px;
      border-radius: 14px;
      background: #ffffff;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 6px 16px rgba(0, 115, 230, 0.15);
      padding: 6px;
    }

    .btn-primary-custom {
      background: linear-gradient(135deg, #0073e6 0%, #00a651 100%);
      border: none;
      box-shadow: 0 4px 14px rgba(0, 115, 230, 0.35);
      transition: all 0.25s ease-in-out;
    }

    .btn-primary-custom:hover {
      transform: translateY(-1px);
      box-shadow: 0 6px 20px rgba(0, 166, 81, 0.45);
      filter: brightness(1.05);
    }

    .form-control:focus {
      border-color: #0073e6;
      box-shadow: 0 0 0 0.25rem rgba(0, 115, 230, 0.15);
    }

    @media (max-width: 991.98px) {
      .auth-left { width: 100%; }
      .auth-right { display: none; }
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

        <h4 class="text-center fw-bold mb-1" style="color: #0f172a;">Sign In to your account</h4>
        <p class="text-center text-muted mb-4" style="font-size: 0.9rem;">Enter your credentials to access your account</p>

        <form action="{{ route('login') }}" method="POST">
          @csrf
          <div class="form-group mb-3">
            <label class="fw-semibold mb-1" style="font-size: 0.85rem; color: #334155;" for="username">Username</label>
            <input type="text" name="username" id="username" class="form-control rounded-3 py-2 px-3"
              placeholder="Enter your username...">
          </div>

          <div class="form-group mb-3 position-relative">
            <label class="fw-semibold mb-1" style="font-size: 0.85rem; color: #334155;" for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control rounded-3 py-2 px-3 pe-5"
              placeholder="********">
            <button type="button" id="togglePassword"
              class="btn position-absolute end-0 translate-middle-y me-3 p-0 border-0 bg-transparent" style="top: 68%;">
              <i class="bi bi-eye-slash text-muted" id="toggleIcon"></i>
            </button>
          </div>

          <div class="form-group d-flex justify-content-between mb-4">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="" id="rememberMe" disabled>
              <label class="form-check-label text-muted" style="font-size: 0.85rem;" for="rememberMe">
                Remember Me
              </label>
            </div>

            <div class="text-end">
              <a href="{{ route('password.request') }}" class="text-decoration-none fw-semibold"
                style="color: #0073e6; font-size: 0.85rem;">Forgot Password?</a>
            </div>
          </div>

          @if ($errors->any())
            <div class="alert alert-danger rounded-3 text-center py-2 mb-3 small">
              {{ $errors->first() }}
            </div>
          @endif

          <div class="form-group mb-3">
            <button type="submit" class="btn btn-primary-custom w-100 text-white fw-semibold rounded-3 py-2">
              Sign In
            </button>
          </div>
        </form>

        <div class="text-center mt-4 d-flex justify-content-center align-items-center gap-3">
          <small class="text-muted me-2" style="font-size: 0.75rem;">&copy; {{ Date::now()->format('Y') }} PT SINTESA TALENTA ASIA</small>
        </div>
      </div>
    </div>

    <div class="auth-right">
      <div class="text-center max-w-lg" style="max-width: 500px;">
        <h2 class="fw-bold mb-3"><span style="color: #ffffff;">SINTESA</span> <span style="color: #bbf7d0;">HRIS</span></h2>
        <p class="lead opacity-90 mb-0">Integrated Human Resource Management System by PT Sintesa Talenta Asia.</p>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      const $usernameInput = $('#username');
      const $rememberMeCheckbox = $('#rememberMe');

      const storedUsername = localStorage.getItem('_name_');
      if (storedUsername) {
        $usernameInput.val(storedUsername);
        $rememberMeCheckbox.prop('checked', true).prop('disabled', false);
      }

      $usernameInput.on('input', function() {
        $rememberMeCheckbox.prop('disabled', !$usernameInput.val().trim());
      });

      $rememberMeCheckbox.on('change', function() {
        if (this.checked) {
          localStorage.setItem('_name_', $usernameInput.val().trim());
        } else {
          localStorage.removeItem('_name_');
        }
      });

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
