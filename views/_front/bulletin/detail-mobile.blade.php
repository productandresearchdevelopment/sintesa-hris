@extends('templates.mobile')

@section('head')
  <style>
    html, body {
      background-color: #ffffff !important;
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    .bulletin-page-wrapper {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      padding-bottom: 40px;
    }

    .bulletin-header-banner {
      background: linear-gradient(135deg, #0073e6 0%, #00a651 100%);
      padding: 16px 20px 44px 20px;
      color: #ffffff;
      position: relative;
      border-bottom-left-radius: 28px;
      border-bottom-right-radius: 28px;
      box-shadow: 0 10px 30px rgba(0, 115, 230, 0.2);
    }

    .top-action-bar {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .btn-back-link {
      width: 38px;
      height: 38px;
      border-radius: 12px;
      background: rgba(255, 255, 255, 0.2);
      backdrop-filter: blur(8px);
      border: none;
      color: #ffffff;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      text-decoration: none;
      transition: all 0.2s ease;
      cursor: pointer;
    }

    .btn-back-link:active {
      transform: scale(0.92);
      background: rgba(255, 255, 255, 0.3);
    }

    .header-page-title {
      font-size: 17px;
      font-weight: 700;
      letter-spacing: -0.3px;
      margin: 0;
      color: #ffffff;
    }

    .content-body {
      padding: 0 16px;
      margin-top: -24px;
      z-index: 10;
      position: relative;
    }

    @media (min-width: 769px) {
      .bulletin-header-banner {
        display: none !important;
      }
      .content-body {
        margin-top: 0 !important;
        padding: 0 !important;
        max-width: 800px;
        margin-left: auto !important;
        margin-right: auto !important;
      }
    }

    .bulletin-detail-card {
      background: #ffffff;
      border-radius: 24px;
      padding: 24px 20px;
      border: 1px solid #f1f5f9;
      box-shadow: 0 6px 24px rgba(0, 0, 0, 0.04);
      margin-bottom: 20px;
      text-align: left !important;
    }

    .cat-pill-badge {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      background: #eff6ff;
      color: #0073e6;
      font-size: 11px;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      padding: 5px 14px;
      border-radius: 50px;
      margin-bottom: 12px;
      border: 1px solid #dbeafe;
    }

    .bulletin-main-title {
      font-size: 20px;
      font-weight: 800;
      line-height: 1.35;
      color: #0f172a;
      letter-spacing: -0.3px;
      margin-bottom: 14px;
    }

    .meta-author-box {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 12px 14px;
      background: #f8fafc;
      border-radius: 16px;
      border: 1px solid #f1f5f9;
      margin-bottom: 20px;
    }

    .author-icon-avatar {
      width: 40px;
      height: 40px;
      border-radius: 12px;
      background: linear-gradient(135deg, #0073e6 0%, #00a651 100%);
      color: #ffffff;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      flex-shrink: 0;
      box-shadow: 0 4px 10px rgba(0, 115, 230, 0.2);
    }

    .author-info-text {
      flex: 1;
      min-width: 0;
    }

    .author-name {
      font-size: 13.5px;
      font-weight: 800;
      color: #0f172a;
      line-height: 1.2;
    }

    .author-date {
      font-size: 11.5px;
      font-weight: 600;
      color: #64748b;
      margin-top: 2px;
    }

    /* Content Typography & Elements Styling */
    .bulletin-content {
      font-size: 14.5px;
      line-height: 1.75;
      color: #334155;
    }

    .bulletin-content p {
      margin-bottom: 16px;
    }

    .bulletin-content h1,
    .bulletin-content h2,
    .bulletin-content h3,
    .bulletin-content h4,
    .bulletin-content h5 {
      color: #0f172a;
      font-weight: 800;
      margin-top: 24px;
      margin-bottom: 12px;
      line-height: 1.3;
    }

    .bulletin-content h3,
    .bulletin-content h4 {
      font-size: 16.5px;
    }

    .bulletin-content img {
      max-width: 100%;
      height: auto;
      border-radius: 16px;
      margin: 16px 0;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
    }

    .bulletin-content blockquote {
      background: #eff6ff;
      border-left: 4px solid #0073e6;
      border-radius: 0 16px 16px 0;
      padding: 14px 18px;
      font-style: italic;
      color: #1e40af;
      margin: 20px 0;
    }

    .bulletin-content ul,
    .bulletin-content ol {
      padding-left: 20px;
      margin-bottom: 18px;
    }

    .bulletin-content li {
      margin-bottom: 8px;
    }
  </style>
@endsection

@section('content')
  <div class="bulletin-page-wrapper">
    <div class="bulletin-header-banner">
      <div class="top-action-bar">
        <a href="javascript:history.back()" class="btn-back-link">
          <i class="bi bi-arrow-left"></i>
        </a>
        <h1 class="header-page-title">Bulletin Details</h1>
        <div style="width: 38px;"></div>
      </div>
    </div>

    <div class="content-body">
      @if (isset($data))
        <div class="bulletin-detail-card">
          <span class="cat-pill-badge">
            <i class="bi bi-bookmark-fill me-1"></i> {{ $data->category->name ?? 'INFORMASI HR' }}
          </span>

          <h1 class="bulletin-main-title">
            {{ $data->title }}
          </h1>

          <div class="meta-author-box">
            <div class="author-icon-avatar">
              <i class="bi bi-megaphone-fill"></i>
            </div>
            <div class="author-info-text">
              <div class="author-name">{{ $data->author->name ?? 'HR Learning & Development Team' }}</div>
              <div class="author-date">
                <i class="bi bi-calendar3 me-1"></i> {{ \Carbon\Carbon::parse($data->created_at)->format('d M Y') }}
              </div>
            </div>
          </div>

          @if ($data->cover_image_id)
            <div class="mb-4" style="border-radius: 16px; overflow: hidden; box-shadow: 0 4px 16px rgba(0,0,0,0.08);">
              <img src="{{ route('file', $data->cover_image_id) }}" alt="{{ $data->title }}" class="img-fluid w-100">
            </div>
          @endif

          <div class="bulletin-content">
            {!! $data->content !!}
          </div>
        </div>
      @else
        <div class="bulletin-detail-card text-center py-5">
          <i class="bi bi-journal-x text-muted" style="font-size: 40px;"></i>
          <h5 class="fw-bold text-dark mt-3 mb-1">Bulletin Not Found</h5>
          <p class="text-secondary small mb-0">The requested bulletin article could not be loaded or has been archived.</p>
        </div>
      @endif
    </div>
  </div>
@endsection
