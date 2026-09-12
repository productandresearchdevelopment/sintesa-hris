@extends('templates.mobile')

@section('head')
  <style>
    .bulletin-container {
      padding: 16px 16px 82px 16px;
      max-width: 768px;
      margin: 0 auto;
    }

    .bulletin-category {
      color: #666;
      font-size: 14px;
      text-transform: uppercase;
      margin-bottom: 8px;
    }

    .bulletin-title {
      font-size: 24px;
      font-weight: bold;
      line-height: 1.3;
      margin-bottom: 12px;
    }

    .bulletin-meta {
      display: flex;
      align-items: center;
      gap: 8px;
      color: #666;
      font-size: 14px;
      margin-bottom: 16px;
    }

    .bulletin-image {
      width: 100%;
      border-radius: 8px;
      margin-bottom: 16px;
    }

    .bulletin-content {
      font-size: 20px;
      line-height: 1.6;
      color: #333;
    }
  </style>
@endsection

@section('content')
  <div class="bulletin-container">
    <div class="d-flex align-items-center gap-4 mb-4" style="cursor: pointer;" onclick="history.back()">
      <i class="bi bi-chevron-left" style="font-size: 1.2rem;"></i>
      <p class="m-0" style="font-size: 1.1rem;">Detail Bulletin</p>
    </div>

    @if (isset($data))
      <div class="bulletin-category">
        {{ $data->category->name ?? 'NEWS' }}
      </div>

      <h1 class="bulletin-title">
        {{ $data->title }}
      </h1>

      <div class="bulletin-meta">
        <span class="author">By {{ $data->author->name ?? 'Anonymous' }}</span>
        <span>•</span>
        <span class="date">{{ \Carbon\Carbon::parse($data->created_at)->format('F d, Y') }}</span>
      </div>

      {{-- @if ($data->cover_image_id)
        <img src="{{ route('file', $data->cover_image_id) }}" alt="{{ $data->title }}" class="bulletin-image img-fluid">
      @endif --}}

      <div class="bulletin-content">
        {!! $data->content !!}
      </div>
    @else
      <div class="alert alert-warning">
        Bulletin not found.
      </div>
    @endif
  </div>
@endsection
