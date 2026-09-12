@extends('headers.head')

@section('body')
  <div class="min-vh-100 d-flex align-items-center justify-content-center bg-light py-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
          <div class="card shadow-lg border-0 rounded-4">
            <div class="card-body p-5">
              <div class="text-center mb-4">
                <div
                  class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center"
                  style="width: 80px; height: 80px;">
                  <div>
                    <i class="bi bi-envelope-check text-primary" style="font-size: 30px;"></i>
                  </div>
                </div>
              </div>

              <h2 class="text-center fw-bold mb-2">Email Verification</h2>

              <p class="text-center text-muted mb-4">
                We sent a verification code to<br>
                <strong class="text-dark">{{ $user->email }}</strong>
              </p>

              @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                  <i class="bi bi-check-circle-fill me-2"></i>{{ session('status') }}
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              @endif

              @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              @endif

              @if (isset($status))
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                  <i class="bi bi-info-circle-fill me-2"></i>{{ $status }}
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              @endif

              <form method="POST" action="{{ route('verification.check') }}">
                @csrf
                <div class="mb-4">
                  <label for="code" class="form-label fw-semibold">Verification Code</label>
                  <input type="text"
                    class="form-control form-control-lg text-center fs-4 letter-spacing-wide @error('code') is-invalid @enderror"
                    id="code" name="code" placeholder="000000" maxlength="6" required autofocus
                    style="letter-spacing: 0.5em;">
                  @error('code')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                  <small class="form-text text-muted">Enter the 6-digit code from your email</small>
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100 mb-3 fw-semibold">
                  <i class="bi bi-shield-check me-2"></i>Verify Email
                </button>
              </form>

              <div class="text-center">
                <p class="text-muted mb-2">Didn't receive the code?</p>
                <form method="POST" action="{{ route('verification.resend') }}" class="d-inline">
                  @csrf
                  {{-- <div class="w-100 d-flex align-items-center jutify-content-center"> --}}
                  <button type="submit" class="w-100 btn btn-link text-decoration-none p-0 text-center">
                    <i class="bi bi-arrow-clockwise me-1"></i>Resend verification code
                  </button>
                  {{-- </div> --}}
                </form>
              </div>
            </div>
          </div>

          <div class="text-center mt-4">
            <p class="text-muted small mb-0">
              <i class="bi bi-info-circle me-1"></i>
              Check your spam folder if you don't see the email
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <style>
    .card {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 1rem 3rem rgba(0, 0, 0, .175) !important;
    }

    .btn-primary {
      transition: all 0.3s ease;
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 0.5rem 1rem rgba(13, 110, 253, 0.3);
    }

    input[type="text"]:focus {
      border-color: #0d6efd;
      box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    .letter-spacing-wide {
      letter-spacing: 0.5em;
    }
  </style>
@endsection
