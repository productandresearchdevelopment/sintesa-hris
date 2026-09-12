@extends('templates.mobile')

@section('head')
  <style>
    .maintenance-page {
      background-color: var(--background-color);
      color: var(--dark);
      padding: var(--spacing-lg);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: flex-start;
      gap: var(--spacing-lg);
    }

    .back-button {
      display: flex;
      align-items: center;
      gap: var(--spacing-sm);
      cursor: pointer;
      font-size: var(--text-md);
      font-weight: 500;
      color: var(--primary-color);
      transition: color var(--transition-fast);
    }

    .back-button:hover {
      color: var(--primary-hover);
    }

    .maintenance-icon {
      font-size: 3rem;
      color: var(--primary-color);
      margin: 0 auto;
    }

    .title {
      font-size: var(--text-2xl);
      font-weight: bold;
      text-align: center;
      color: var(--primary-color);
    }

    .description {
      font-size: var(--text-md);
      text-align: center;
      color: var(--gray-medium);
      max-width: 400px;
      margin: 0 auto;
    }

    .maintenance-box {
      background-color: var(--white);
      padding: var(--spacing-lg);
      border-radius: var(--radius-lg);
      box-shadow: var(--shadow-md);
    }
  </style>
@endsection

@section('content')
  <div class="maintenance-page">
    <div class="back-button" onclick="history.back()">
      <i class="bi bi-chevron-left"></i>
      <span>Kembali ke Beranda</span>
    </div>

    <div class="maintenance-box">
      <div class="text-center mb-3">
        <i class="bi bi-tools maintenance-icon"></i>
      </div>

      <h1 class="title">Halaman Dalam Pengembangan</h1>
      <p class="description">
        Mohon maaf atas ketidaknyamanannya. Kami sedang melakukan pembaruan untuk meningkatkan pengalaman Anda. Silakan
        cek secara berkala.
      </p>
    </div>
  </div>
@endsection
