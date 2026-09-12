@include('headers.head')

<div id="appCapsule" class="bg-white w-100 h-100" style="padding: 20px 0;">
  <div class="card">
    <div class="card-body">
      <div class="text-center gap-3">
        <h3 class="title" style="font-size: 24px;font-weight: bold">{{ $data->title }}</h3>
        <div class="">
          <div class="color-box mb-1 d-flex align-items-center justify-content-center">
            <p class="text-center p-2 rounded text-white mb-0"
              style="background: #{{ $data->category->color }}; max-width: 100px;">
              {{ $data->category->name }}</p>
          </div>
        </div>
        <div>
          <p>
            {{ $data->createdBy->name ?? '-' }} -
            {{ date('l, d F Y | h:i A', strtotime($data->created_at)) }}
          </p>
        </div>
        {{-- <div class="my-2">
          @if ($data->cover_image_id)
            <img src="{{ route('file', $data->cover_image_id) }}" width="100%" class="border border-2"
              alt="{{ $data->title }}">
          @endif
        </div> --}}
        <div class="text-left">
          <p class="mb-0">{!! $data->content ?? '-' !!}</p>
        </div>
      </div>
    </div>
  </div>
</div>
