@php
  $level++;
@endphp

@foreach ($items as $item)
  @php
    $hasChildren = $item->children->isNotEmpty();
    $isAccordion = $item->type_id == 100 && $level < 2;
    $isDropdown = $item->type_id == 100 && $level >= 2;
    $isItem = $item->type_id == 110;
  @endphp

  @if ($isAccordion)
    @if ($hasChildren)
      <!-- Menu Direktori Item -->
      <div class="sidebar-label">
        <div class="sidebar-label-wrapper">
          <div class="sidebar-label-text">
            <strong>{{ $item->text }}</strong>
          </div>
          @require('sidebar-menu', ['items' => $item->children, 'level' => $level])
        </div>
      </div>
    @endif
  @elseif($isItem)
    <!-- Simple Item -->
    <ul class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link" href="{{ $item->url }}" data-page="{{ $item->text }}"
            data-level="{{ $level }}">
          <i class="{{ $item->icon ?? 'bi bi-file' }}"></i> {{ $item->text }}
        </a>
      </li>
    </ul>
  @elseif($isDropdown)
    <!-- Dropdown for level 3+ -->
    <li class="nav-item dropdown" data-level="{{ $level }}" data-page="{{ $item->text }}"
      @if (!$hasChildren) data-end="true" @endif>
      <!-- Ganti data-bs-toggle dengan event custom -->
      <button class="nav-link dropdown-toggle" id="dropdown{{ $item->id }}">
        <div class="d-flex align-items-center">
          <i class="{{ $item->icon ?? 'bi bi-folder d-flex align-items-center' }}"></i> {{ $item->text }}
        </div>
        <i class="bi bi-chevron-right"></i>
      </button>
      <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown{{ $item->id }}">
        @if ($hasChildren)
          @require('sidebar-menu', ['items' => $item->children, 'level' => $level])
        @else
          <li><a class="dropdown-item" href="{{ $item->url }}" data-page="{{ $item->text }}"></a></li>
        @endif
      </ul>
    </li>
  @endif
@endforeach
