@extends('templates.auth-mobile')
@section('head')
  <style>
    .reset-container {
      max-width: 400px;
      margin: 40px auto;
      padding: 20px;
    }

    .logo {
      margin-bottom: 30px;
    }

    .logo img {
      height: 40px;
      max-width: 300px;
    }

    .reset-title {
      font-size: 36px;
      font-weight: bold;
      margin-bottom: 8px;
    }

    .reset-subtitle {
      color: var(--gray-medium);
      margin-bottom: 24px;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-label {
      font-size: 14px;
      font-weight: 500;
      color: var(--gray-medium);
    }

    .form-disabled {
      background: var(--gray-light);
      color: var(--dark);
      pointer-events: none;
    }

    .reset-btn {
      padding: 12px;
      margin-top: 10px;
    }

    .footer {
      position: fixed;
      bottom: 0;
      width: 100%;
      padding: 20px;
      text-align: center;
      font-size: 14px;
      color: var(--gray-medium);
    }

    .footer .watermark {
      color: var(--primary-color);
    }
  </style>
@endsection


@section('content')
  <div class="reset-container">
    <div class="logo d-flex align-items-center justify-content-center gap-2">
      <img src="{{ asset('images/logo-sintesa.jpg') }}" alt="Logo Sintesa" style="height: 48px; width: auto; object-fit: contain;">
      <span class="h3 mb-0" style="font-weight: 900; letter-spacing: -0.5px;"><span style="color: #0073e6;">SINTESA</span> <span style="color: #00a651;">HRIS</span></span>
    </div>

    <a href="{{ url()->previous() }}" class="text-decoration-none d-flex align-items-center mb-3 text-primary-color">
      <i class="fas fa-chevron-left me-2"></i> Back to Login
    </a>

    <h1 class="reset-title">Reset Password</h1>
    <p class="reset-subtitle">Enter your email to reset your password</p>

    <form id="resetForm" method="POST" action="{{ route('password.update') }}">
      @csrf
      <div class="form-group mb-3">
        <label class="form-label">Email</label>
        <input type="text" name="email" class="form-control form-disabled" placeholder="Email"
          value="{{ $email }}">
      </div>

      <div class="form-group mb-3">
        <label class="form-label" for="password">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Password">
      </div>

      <div class="form-group mb-3">
        <label class="form-label" for="password-confirm">Confirm Password</label>
        <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm Password">
      </div>

      <button type="submit" class="btn btn-primary reset-btn">Reset Password</button>
    </form>
  </div>

  <div class="footer">
    <p>All Rights Reserved &copy; {{ Date::now()->format('Y') }} <span class="watermark">Sintesa Talenta Asia</span></p>
  </div>

  <script>
    $(document).ready(function() {
      $('#resetForm').submit(function(e) {
        e.preventDefault();

        $.ajax({
          url: $(this).attr('action'),
          method: 'POST',
          data: $(this).serialize(),
          success: function(response) {
            showAlert('success', 'Reset password success.');
            setTimeout(() => {
              window.location.href = '/login';
            }, 300);
          },
          error: function(xhr) {
            showAlert('danger', 'Failed to reset password.');
          }
        });
      });
    });
  </script>
@endsection
