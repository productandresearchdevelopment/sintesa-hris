@extends('templates.mobile')
@section('head')
  <style>
    body {
      background-color: #f8fafc;
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    .main-wrapper {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      padding-bottom: 30px;
    }

    .header-topbar {
      padding: 16px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: #ffffff;
      border-bottom: 1px solid #f1f5f9;
      position: sticky;
      top: 0;
      z-index: 999;
    }

    .header-brand {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .header-brand img {
      height: 38px;
      width: auto;
      object-fit: contain;
    }

    .user-avatar-btn {
      width: 42px;
      height: 42px;
      border-radius: 50%;
      overflow: hidden;
      border: 2px solid #0073e6;
      cursor: pointer;
      box-shadow: 0 2px 8px rgba(0, 115, 230, 0.2);
    }

    .user-avatar-btn img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    #dropdownMenu {
      display: none;
      position: absolute;
      right: 20px;
      top: 68px;
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 14px;
      width: 220px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      padding: 8px 0;
      z-index: 1000;
    }

    #dropdownMenu .dropdown-header {
      padding: 12px 16px;
      border-bottom: 1px solid #f1f5f9;
    }

    #dropdownMenu .dropdown-item {
      padding: 10px 16px;
      cursor: pointer;
      font-size: 14px;
      font-weight: 500;
      color: #334155;
      transition: background 0.15s ease;
    }

    #dropdownMenu .dropdown-item:hover {
      background: #f8fafc;
      color: #0073e6;
    }

    .greeting-card {
      background: linear-gradient(135deg, #0073e6 0%, #00a651 100%);
      margin: 20px 20px 10px 20px;
      padding: 22px 20px;
      border-radius: 20px;
      color: #ffffff;
      box-shadow: 0 10px 25px rgba(0, 115, 230, 0.25);
      position: relative;
      overflow: hidden;
    }

    .greeting-card::after {
      content: '';
      position: absolute;
      right: -20px;
      bottom: -30px;
      width: 130px;
      height: 130px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.1);
      pointer-events: none;
    }

    /* Quick Attendance Widget */
    .quick-attendance-widget {
      margin-top: 14px;
      position: relative;
      z-index: 2;
    }

    .att-status-badge {
      background: rgba(255, 255, 255, 0.18);
      backdrop-filter: blur(12px);
      border: 1px solid rgba(255, 255, 255, 0.3);
      border-radius: 16px;
      padding: 10px 14px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      transition: all 0.2s ease;
      cursor: pointer;
    }

    .att-status-badge:active {
      transform: scale(0.98);
      background: rgba(255, 255, 255, 0.28);
    }

    .att-status-info {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .att-status-info i {
      font-size: 22px;
    }

    .icon-pulse {
      color: #fde047;
      animation: pulseIcon 1.5s infinite;
    }

    @keyframes pulseIcon {
      0%, 100% { transform: scale(1); opacity: 1; }
      50% { transform: scale(1.15); opacity: 0.8; }
    }

    .att-status-title {
      font-size: 13px;
      font-weight: 800;
      line-height: 1.2;
      color: #ffffff;
    }

    .att-status-desc {
      font-size: 10.5px;
      color: rgba(255, 255, 255, 0.9);
      margin-top: 1px;
    }

    .btn-att-direct {
      font-size: 11px;
      font-weight: 800;
      background: #ffffff;
      color: #0073e6;
      padding: 6px 12px;
      border-radius: 50px;
      display: flex;
      align-items: center;
      gap: 2px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
      white-space: nowrap;
    }

    .btn-att-direct.btn-out {
      background: #fef2f2;
      color: #ef4444;
    }

    .btn-att-direct.btn-done {
      background: #ecfdf5;
      color: #10b981;
    }

    .menu-section {
      padding: 10px 20px 20px 20px;
    }

    .section-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 14px;
    }

    .section-title {
      font-size: 16px;
      font-weight: 800;
      color: #0f172a;
      letter-spacing: -0.3px;
      margin: 0;
    }

    .menu-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 14px;
    }

    .menu-item-card {
      background: #ffffff;
      border-radius: 16px;
      border: 1px solid #f1f5f9;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
      text-align: center;
      padding: 16px 8px;
      position: relative;
      cursor: pointer;
      transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .menu-item-card:active {
      transform: scale(0.95);
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    }

    .menu-icon-box {
      width: 48px;
      height: 48px;
      border-radius: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 22px;
      margin: 0 auto 10px auto;
      transition: transform 0.2s ease;
    }

    .menu-item-label {
      font-size: 12px;
      font-weight: 700;
      color: #334155;
      display: block;
      line-height: 1.3;
      word-wrap: break-word;
    }

    .notif-badge {
      position: absolute;
      top: -4px;
      right: -4px;
      background: #ef4444;
      color: #ffffff;
      font-size: 10px;
      font-weight: 800;
      padding: 2px 7px;
      border-radius: 50px;
      box-shadow: 0 2px 6px rgba(239, 68, 68, 0.4);
      border: 2px solid #ffffff;
    }

    /* Bulletin Carousel Section */
    .bulletin-section {
      padding: 10px 20px 20px 20px;
    }

    .bulletin-carousel-card {
      background: #ffffff;
      border-radius: 18px;
      border: 1px solid #f1f5f9;
      box-shadow: 0 4px 14px rgba(0, 0, 0, 0.04);
      overflow: hidden;
      cursor: pointer;
      transition: all 0.2s ease;
      display: flex;
      flex-direction: column;
      height: 100%;
    }

    .bulletin-carousel-card:active {
      transform: scale(0.98);
    }

    .bulletin-card-img-box {
      width: 100%;
      height: 120px;
      position: relative;
      overflow: hidden;
      background: #f1f5f9;
    }

    .bulletin-card-img-box img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .bulletin-card-category {
      position: absolute;
      top: 10px;
      left: 10px;
      background: rgba(0, 115, 230, 0.85);
      backdrop-filter: blur(6px);
      color: #ffffff;
      font-size: 10px;
      font-weight: 800;
      padding: 3px 10px;
      border-radius: 50px;
    }

    .bulletin-card-body {
      padding: 12px 14px;
      display: flex;
      flex-direction: column;
      flex-grow: 1;
    }

    .bulletin-card-title {
      font-size: 13px;
      font-weight: 800;
      color: #0f172a;
      line-height: 1.35;
      margin-bottom: 8px;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    .bulletin-card-meta {
      font-size: 11px;
      color: #94a3b8;
      font-weight: 600;
      margin-top: auto;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
  </style>
@endsection

@section('content')
  @php
    $hour = (int) date('H');
    if ($hour >= 5 && $hour < 12) {
      $greeting = 'Selamat Pagi';
    } elseif ($hour >= 12 && $hour < 15) {
      $greeting = 'Selamat Siang';
    } elseif ($hour >= 15 && $hour < 18) {
      $greeting = 'Selamat Sore';
    } else {
      $greeting = 'Selamat Malam';
    }

    $rawModules = [];
    if (isset($treeMenu) && count($treeMenu) > 0) {
      foreach ($treeMenu as $menu) {
        if (isset($menu->children) && count($menu->children) > 0) {
          foreach ($menu->children as $child) {
            $c = clone $child;
            $c->parent_text = $menu->text;
            $rawModules[] = $c;
          }
        } else {
          $c = clone $menu;
          $c->parent_text = null;
          $rawModules[] = $c;
        }
      }
    }

    $titleCounts = [];
    foreach ($rawModules as $m) {
      $t = trim($m->text ?? '');
      $titleCounts[$t] = ($titleCounts[$t] ?? 0) + 1;
    }

    $todayAttendanceStatus = null;
    if ($user->employ_id) {
      $todayAttendanceStatus = \App\Models\Attendance::where('employee_id', $user->employ_id)
        ->where('date', \Carbon\Carbon::today()->format('Y-m-d'))
        ->first();
    }

    $hasBulletinModule = Route::has('bulletin.data');
    $bulletinRoute = Route::has('bulletin.index') ? route('bulletin.index') : '#';

    $mobileMenuConfig = [
      'leave.index' => [
        'text' => 'Leave',
        'display_title' => 'Leave',
        'route' => 'leave.index'
      ],
      'appraisal.question.template.index.mobile' => [
        'text' => 'Assessment',
        'display_title' => 'Assessment',
        'route' => 'appraisal.question.template.index.mobile'
      ],
      'attendance.index' => [
        'text' => 'Attendance',
        'display_title' => 'Attendance',
        'route' => 'attendance.index'
      ],
      'employee.index' => [
        'text' => 'Employee',
        'display_title' => 'Employee',
        'route' => 'employee.index'
      ],
    ];

    $modules = [];
    foreach ($mobileMenuConfig as $routeKey => $cfg) {
      if (Route::has($routeKey)) {
        $modules[] = (object) $cfg;
      }
    }

    $getModuleMeta = function($mod) {
      $name = strtolower($mod->text ?? '');
      $route = strtolower($mod->route ?? '');

      if ($route === 'auth.user') {
        return ['icon' => 'bi bi-person-gear', 'bg' => '#eef2ff', 'color' => '#4f46e5', 'key' => 'users'];
      } elseif ($route === 'auth.role') {
        return ['icon' => 'bi bi-shield-lock-fill', 'bg' => '#f0fdf4', 'color' => '#16a34a', 'key' => 'roles'];
      } elseif ($route === 'auth.module') {
        return ['icon' => 'bi bi-layers-fill', 'bg' => '#fef3c7', 'color' => '#d97706', 'key' => 'modules'];
      } elseif ($route === 'company.index') {
        return ['icon' => 'bi bi-building-fill', 'bg' => '#f0fdfa', 'color' => '#0d9488', 'key' => 'company'];
      } elseif ($route === 'office.index') {
        return ['icon' => 'bi bi-geo-alt-fill', 'bg' => '#eff6ff', 'color' => '#2563eb', 'key' => 'office'];
      } elseif ($route === 'division.index') {
        return ['icon' => 'bi bi-diagram-2-fill', 'bg' => '#fae8ff', 'color' => '#c026d3', 'key' => 'division'];
      } elseif ($route === 'organization.index') {
        return ['icon' => 'bi bi-diagram-3-fill', 'bg' => '#eef2ff', 'color' => '#6366f1', 'key' => 'organization'];
      } elseif ($route === 'employee.index') {
        return ['icon' => 'bi bi-person-badge-fill', 'bg' => '#ecfdf5', 'color' => '#059669', 'key' => 'employee'];
      } elseif ($route === 'leave.index') {
        return ['icon' => 'bi bi-calendar2-check-fill', 'bg' => '#fef2f2', 'color' => '#dc2626', 'key' => 'leave'];
      } elseif ($route === 'leave.type.index') {
        return ['icon' => 'bi bi-card-checklist', 'bg' => '#fff7ed', 'color' => '#d97706', 'key' => 'leavetype'];
      } elseif ($route === 'appraisal.period.index') {
        return ['icon' => 'bi bi-calendar3', 'bg' => '#faf5ff', 'color' => '#9333ea', 'key' => 'period'];
      } elseif ($route === 'appraisal.question.template.index') {
        return ['icon' => 'bi bi-file-earmark-spreadsheet-fill', 'bg' => '#f0f9ff', 'color' => '#0284c7', 'key' => 'template'];
      } elseif ($route === 'appraisal.period.organization.index') {
        return ['icon' => 'bi bi-bar-chart-line-fill', 'bg' => '#fdf4ff', 'color' => '#c026d3', 'key' => 'appraisalorg'];
      } elseif ($route === 'appraisal.employee.index') {
        return ['icon' => 'bi bi-person-lines-fill', 'bg' => '#f0fdf4', 'color' => '#16a34a', 'key' => 'appraisalemployee'];
      } elseif ($route === 'appraisal.question.template.index.mobile') {
        return ['icon' => 'bi bi-journal-check', 'bg' => '#fff1f2', 'color' => '#e11d48', 'key' => 'assessment'];
      } elseif ($route === 'attendance.index') {
        return ['icon' => 'bi bi-clock-history', 'bg' => '#fdf4ff', 'color' => '#d946ef', 'key' => 'attendance'];
      } elseif ($route === 'attendance.report') {
        return ['icon' => 'bi bi-file-earmark-bar-chart-fill', 'bg' => '#ecfeff', 'color' => '#0891b2', 'key' => 'attendancereport'];
      } elseif (str_contains($name, 'file') || str_contains($route, 'file')) {
        return ['icon' => 'bi bi-folder2-open', 'bg' => '#eff6ff', 'color' => '#2563eb', 'key' => 'filemanager'];
      } elseif (str_contains($name, 'helpdesk') || str_contains($route, 'helpdesk')) {
        return ['icon' => 'bi bi-headset', 'bg' => '#fff7ed', 'color' => '#f97316', 'key' => 'helpdesk'];
      } elseif (str_contains($name, 'bulletin') || str_contains($route, 'bulletin')) {
        return ['icon' => 'bi bi-newspaper', 'bg' => '#f0f9ff', 'color' => '#0ea5e9', 'key' => 'bulletin'];
      }

      return ['icon' => 'bi bi-grid-fill', 'bg' => '#f0f9ff', 'color' => '#0073e6', 'key' => $route ?: 'default'];
    };
  @endphp

  <div class="main-wrapper">
    <div class="header-topbar">
      <div class="header-brand">
        <img src="{{ asset('images/logo-sintesa.jpg') }}" alt="Logo Sintesa">
        <span class="fw-bold fs-5" style="letter-spacing: -0.3px;">
          <span style="color: #0073e6;">SINTESA</span> <span style="color: #00a651;">HRIS</span>
        </span>
      </div>

      <div class="user-greeting position-relative">
        <div class="user-avatar-btn" id="avatarDropdown">
          @if ($user->photo_id || ($user->employee && $user->employee->photo_id))
            @php
              $urlFile = route('file', $user->photo_id ?: $user->employee->photo_id);
            @endphp
            <img src="{{ $urlFile }}" alt="User Photo">
          @else
            <img src="{{ asset('/images/image-no-user.png') }}" alt="no-user">
          @endif
        </div>

        <div class="dropdown-menu" id="dropdownMenu">
          <div class="dropdown-header">
            <strong class="d-block text-dark">{{ strtoupper($user->name) }}</strong>
            <small class="text-muted">{{ $user->role->name ?? 'User' }}</small>
          </div>

          <div class="dropdown-item d-flex align-items-center gap-2"
            onclick="window.location='{{ route('profile.mobile') }}'">
            <i class="bi bi-person-circle text-primary"></i> Profil Saya
          </div>

          <div class="dropdown-item d-flex align-items-center gap-2 text-danger"
            onclick="event.preventDefault(); window.location='{{ route('logout') }}'">
            <i class="bi bi-box-arrow-right"></i> Keluar
          </div>
        </div>
      </div>
    </div>

    <div class="greeting-card">
      <div class="small opacity-85 fw-semibold mb-1" style="font-size: 11.5px;">
        <i class="bi bi-calendar-event me-1"></i> {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
      </div>
      <h5 class="fw-bold mb-1">{{ $greeting }}, {{ strtok($user->name, ' ') }}! 👋</h5>
      <p class="mb-0 small opacity-90">Akses cepat ke seluruh modul dan layanan HR kamu</p>

      @if (Route::has('attendance.index'))
        <div class="quick-attendance-widget" onclick="window.location='{{ route('attendance.index') }}'">
          @if (!$todayAttendanceStatus || !$todayAttendanceStatus->clock_in_time)
            <div class="att-status-badge">
              <div class="att-status-info">
                <i class="bi bi-exclamation-circle-fill icon-pulse"></i>
                <div>
                  <div class="att-status-title">Belum Presensi Masuk</div>
                  <div class="att-status-desc">Klik untuk presensi sekarang</div>
                </div>
              </div>
              <span class="btn-att-direct">Presensi <i class="bi bi-chevron-right ms-1"></i></span>
            </div>
          @elseif ($todayAttendanceStatus->clock_in_time && !$todayAttendanceStatus->clock_out_time)
            <div class="att-status-badge">
              <div class="att-status-info">
                <i class="bi bi-clock-history text-warning"></i>
                <div>
                  <div class="att-status-title">Presensi Masuk: {{ \Carbon\Carbon::parse($todayAttendanceStatus->clock_in_time)->format('H:i') }}</div>
                  <div class="att-status-desc">Jangan lupa presensi keluar</div>
                </div>
              </div>
              <span class="btn-att-direct btn-out">Clock Out <i class="bi bi-chevron-right ms-1"></i></span>
            </div>
          @else
            <div class="att-status-badge">
              <div class="att-status-info">
                <i class="bi bi-check-circle-fill text-success"></i>
                <div>
                  <div class="att-status-title">Presensi Hari Ini Selesai</div>
                  <div class="att-status-desc">Masuk: {{ \Carbon\Carbon::parse($todayAttendanceStatus->clock_in_time)->format('H:i') }} | Keluar: {{ \Carbon\Carbon::parse($todayAttendanceStatus->clock_out_time)->format('H:i') }}</div>
                </div>
              </div>
              <span class="btn-att-direct btn-done">Detail <i class="bi bi-chevron-right ms-1"></i></span>
            </div>
          @endif
        </div>
      @endif
    </div>

    <div class="menu-section">
      <div class="section-header">
        <h3 class="section-title">Modul Layanan</h3>
        <small class="text-muted fw-semibold" style="font-size: 12px;">{{ count($modules) }} Modul</small>
      </div>

      <div class="menu-grid">
        @foreach($modules as $mod)
          @php
            $meta = $getModuleMeta($mod);
            $modRoute = $mod->route && Route::has($mod->route) ? route($mod->route) : ($mod->url ?? '#');
          @endphp
          <div class="menu-item-card" data-module="{{ $meta['key'] }}" data-route="{{ $modRoute }}">
            <div class="menu-icon-box" style="background-color: {{ $meta['bg'] }}; color: {{ $meta['color'] }};">
              <i class="{{ $meta['icon'] }}"></i>
            </div>
            <span class="menu-item-label">{{ $mod->display_title ?? $mod->text }}</span>
          </div>
        @endforeach
      </div>
    </div>

    @if (Route::has('bulletin.data'))
      <div class="bulletin-section" id="bulletinSection" style="display: none;">
        <div class="section-header">
          <h3 class="section-title">Informasi & Buletin</h3>
          @if ($bulletinRoute)
            <a href="{{ $bulletinRoute }}" class="text-primary text-decoration-none fw-bold" style="font-size: 12px;">
              Lihat Semua <i class="bi bi-chevron-right"></i>
            </a>
          @endif
        </div>
        <div class="owl-carousel owl-theme" id="bulletinCarouselContainer"></div>
      </div>
    @endif
  </div>

  <script>
    $(document).ready(function() {
      $('#avatarDropdown').on('click', function(e) {
        e.stopPropagation();
        $('#dropdownMenu').toggle();
      });

      $(document).on('click', function() {
        $('#dropdownMenu').hide();
      });

      $('.menu-item-card').click(function(e) {
        e.preventDefault();

        const route = $(this).data('route');
        const module = $(this).data('module');

        if (route && module && route !== '#') {
          $.ajax({
            url: '{{ route('notification.read.all.module', '') }}/' + module,
            method: 'GET',
            data: { _token: '{{ csrf_token() }}' },
            success: function() {
              $('.menu-item-card').each(function() {
                const m = $(this).data('module');
                if (m === module) {
                  $(this).find('.notif-badge').remove();
                }
              });

              window.location.href = route;
            },
            error: function() {
              window.location.href = route;
            }
          });
        } else if (route && route !== '#') {
          window.location.href = route;
        }
      });

      $.ajax({
        url: '{{ route('notification.count') }}',
        method: 'GET',
        data: { trash: 1 },
        success: function(response) {
          const data = response ? response.data : null;
          if (!data) return;

          $('.menu-item-card').each(function() {
            const module = $(this).data('module');
            let count = 0;

            if (module === 'helpdesk') {
              count = (data.helpdesk || 0) + (data.helpdesk_answer || 0);
            } else {
              count = data[module] || 0;
            }

            $(this).find('.notif-badge').remove();
            if (count > 0) {
              const badge = $('<span class="notif-badge"></span>').text(count);
              $(this).append(badge);
            }
          });
        },
        error: function() {
          console.error('Gagal memuat notifikasi.');
        }
      });

      @if (Route::has('bulletin.data'))
        $.ajax({
          url: '{{ route('bulletin.data') }}',
          method: 'GET',
          data: { trash: 1 },
          success: function(response) {
            const data = response ? (response.data || response) : [];
            const container = $('#bulletinCarouselContainer');
            container.empty();

            if (data && data.length > 0) {
              const items = data.slice(0, 6);
              let count = 0;

              items.forEach(function(b) {
                const imgUrl = b.cover_image_id ?
                  '{{ route('file', ':id') }}'.replace(':id', b.cover_image_id) :
                  '{{ asset('/images/image-no-user.png') }}';
                const viewUrl = '{{ route('bulletin.view', ':id') }}'.replace(':id', b.id);
                const catName = b.category ? b.category.name : 'Buletin';
                const dateStr = b.created_at ? new Date(b.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) : '';

                const cardHtml = `
                  <div class="item">
                    <div class="bulletin-carousel-card" onclick="window.location.href='${viewUrl}'">
                      <div class="bulletin-card-img-box">
                        <img src="${imgUrl}" alt="${b.title || 'Buletin'}" onerror="this.onerror=null;this.src='{{ asset('/images/image-no-user.png') }}';">
                        <span class="bulletin-card-category">${catName}</span>
                      </div>
                      <div class="bulletin-card-body">
                        <div class="bulletin-card-title">${b.title || 'Buletin'}</div>
                        <div class="bulletin-card-meta">
                          <span><i class="bi bi-clock me-1"></i>${dateStr}</span>
                          <span class="text-primary fw-bold">Baca <i class="bi bi-arrow-right"></i></span>
                        </div>
                      </div>
                    </div>
                  </div>
                `;
                container.append(cardHtml);
                count++;
              });

              if (count > 0) {
                $('#bulletinSection').show();
                container.owlCarousel({
                  margin: 14,
                  loop: false,
                  autoplay: false,
                  dots: true,
                  nav: false,
                  responsive: {
                    0: { items: 1.25 },
                    480: { items: 1.8 },
                    768: { items: 2.5 }
                  }
                });
              }
            }
          }
        });
      @endif
    });
  </script>
@endsection
