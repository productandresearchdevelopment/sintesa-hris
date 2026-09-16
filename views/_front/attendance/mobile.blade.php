@extends('templates.mobile')

@section('head')
  <style>
    html, body {
      background-color: #ffffff !important;
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    .att-page-wrapper {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      padding-bottom: 40px;
    }

    .att-header-banner {
      background: linear-gradient(135deg, #0073e6 0%, #00a651 100%);
      padding: 16px 20px 44px 20px;
      color: #ffffff;
      position: relative;
      border-bottom-left-radius: 28px;
      border-bottom-right-radius: 28px;
      box-shadow: 0 10px 30px rgba(0, 115, 230, 0.2);
    }

    .att-header-banner::after {
      content: '';
      position: absolute;
      right: -20px;
      bottom: -30px;
      width: 140px;
      height: 140px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.1);
      pointer-events: none;
    }

    .top-action-bar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 16px;
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

    /* Live Digital Clock Card */
    .digital-clock-box {
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(12px);
      border: 1px solid rgba(255, 255, 255, 0.25);
      border-radius: 20px;
      padding: 16px 20px;
      text-align: center;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    }

    .clock-live-time {
      font-size: 32px;
      font-weight: 900;
      color: #ffffff;
      letter-spacing: 0.5px;
      line-height: 1;
      margin-bottom: 4px;
      font-variant-numeric: tabular-nums;
    }

    .clock-live-date {
      font-size: 13px;
      font-weight: 600;
      color: rgba(255, 255, 255, 0.9);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
    }

    .live-dot {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: #22c55e;
      box-shadow: 0 0 8px #22c55e;
      animation: pulseDot 1.5s infinite;
    }

    @keyframes pulseDot {
      0% { opacity: 1; transform: scale(1); }
      50% { opacity: 0.4; transform: scale(0.8); }
      100% { opacity: 1; transform: scale(1); }
    }

    .content-body {
      padding: 0 16px;
      margin-top: -24px;
      z-index: 10;
      position: relative;
    }

    @media (min-width: 769px) {
      .att-header-banner {
        display: none !important;
      }
      .content-body {
        margin-top: 0 !important;
        padding: 0 !important;
      }
    }

    /* Clock In / Out Action Grid */
    .action-clock-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 12px;
      margin-bottom: 18px;
    }

    .clock-action-card {
      background: #ffffff;
      border-radius: 20px;
      padding: 18px 14px;
      text-align: center;
      border: 1px solid #f1f5f9;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
      cursor: pointer;
      transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      overflow: hidden;
    }

    .clock-action-card:active {
      transform: scale(0.95);
    }

    .clock-action-card.disabled {
      cursor: not-allowed;
      background: #ffffff;
      border-color: #e2e8f0;
      box-shadow: none;
    }

    .clock-action-card.disabled:active {
      transform: none;
    }

    .clock-action-card.disabled .clock-icon-circle {
      opacity: 0.45;
      filter: grayscale(50%);
    }

    .clock-action-card.disabled .clock-action-label,
    .clock-action-card.disabled .clock-action-time {
      opacity: 0.55;
    }

    .clock-icon-circle {
      width: 48px;
      height: 48px;
      border-radius: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 22px;
      margin: 0 auto 10px auto;
    }

    .clock-in-bg {
      background: #ecfdf5;
      color: #10b981;
    }

    .clock-out-bg {
      background: #fef2f2;
      color: #ef4444;
    }

    .clock-action-label {
      font-size: 12px;
      font-weight: 700;
      color: #64748b;
      margin-bottom: 2px;
    }

    .clock-action-time {
      font-size: 17px;
      font-weight: 800;
      color: #0f172a;
    }

    /* Stat Cards Grid (2x2) */
    .stats-grid-container {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 12px;
      margin-bottom: 18px;
    }

    .stat-mini-card {
      background: #ffffff;
      border-radius: 18px;
      padding: 14px 16px;
      border: 1px solid #f1f5f9;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .stat-mini-val {
      font-size: 22px;
      font-weight: 900;
      color: #0f172a;
      line-height: 1;
      margin-bottom: 3px;
    }

    .stat-mini-label {
      font-size: 11px;
      font-weight: 700;
      color: #64748b;
    }

    .stat-icon-box {
      width: 40px;
      height: 40px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
    }

    /* Log Section */
    .log-card {
      background: #ffffff;
      border-radius: 20px;
      padding: 18px;
      border: 1px solid #f1f5f9;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
    }

    .log-card-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 14px;
      padding-bottom: 10px;
      border-bottom: 1px solid #f1f5f9;
    }

    .log-card-title {
      font-size: 15px;
      font-weight: 800;
      color: #0f172a;
      margin: 0;
    }

    .log-view-all-link {
      font-size: 12px;
      font-weight: 700;
      color: #0073e6;
      text-decoration: none;
    }

    .log-row-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 12px 0;
      border-bottom: 1px solid #f8fafc;
    }

    .log-row-item:last-child {
      border-bottom: none;
    }

    .log-item-left {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .log-type-title {
      font-size: 13.5px;
      font-weight: 700;
      color: #1e293b;
      margin: 0;
    }

    .log-date-sub {
      font-size: 11.5px;
      color: #94a3b8;
      margin: 1px 0 0 0;
    }

    .log-time-badge {
      font-size: 13px;
      font-weight: 800;
      color: #0f172a;
      background: #f1f5f9;
      padding: 5px 12px;
      border-radius: 50px;
    }
  </style>
@endsection

@section('content')
  {{-- DESKTOP WEBSITE VIEW --}}
  <div class="d-none d-md-block container-fluid p-3">
    <!-- Desktop Blue Banner Header -->
    <div class="card border-0 mb-4 text-white shadow-sm" style="background: #1d72b8; border-radius: 14px; padding: 24px;">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="fw-bold fs-5">{{ \Carbon\Carbon::now()->format('F d, Y') }}</div>
        <div class="badge bg-white text-dark font-monospace fs-6 px-3 py-2 rounded-pill shadow-sm" id="desktopLiveTime">--:--:--</div>
      </div>
      <div class="row g-3">
        <div class="col-md-6">
          <div class="card border-0 p-4 text-center cursor-pointer {{ $todayAttendance && $todayAttendance->clock_in_time ? 'opacity-75' : '' }}" 
               style="border-radius: 12px; background: #ffffff; color: #1e293b;"
               onclick="handleClockAction('in')">
            <div class="small fw-semibold text-muted mb-2">Clock in</div>
            <div class="fw-bold fs-3 text-dark" style="font-variant-numeric: tabular-nums;">
              {{ $todayAttendance && $todayAttendance->clock_in_time ? \Carbon\Carbon::parse($todayAttendance->clock_in_time)->format('H:i') : '--:--' }}
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="card border-0 p-4 text-center cursor-pointer {{ !$todayAttendance || !$todayAttendance->clock_in_time || ($todayAttendance && $todayAttendance->clock_out_time) ? 'opacity-75' : '' }}" 
               style="border-radius: 12px; background: rgba(255, 255, 255, 0.7); color: #1e293b;"
               onclick="handleClockAction('out')">
            <div class="small fw-semibold text-muted mb-2">Clock out</div>
            <div class="fw-bold fs-3 text-dark" style="font-variant-numeric: tabular-nums;">
              {{ $todayAttendance && $todayAttendance->clock_out_time ? \Carbon\Carbon::parse($todayAttendance->clock_out_time)->format('H:i') : '--:--' }}
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Stats Row (4 Cards) -->
    <div class="row g-3 mb-4">
      <div class="col-md-6 col-lg-3">
        <div class="card border-0 p-3 shadow-sm rounded-3 d-flex flex-row align-items-center justify-content-between" style="background: #f8fafc;">
          <div>
            <h2 class="fw-bold mb-0 text-dark">{{ $monthStats['on_time'] }}</h2>
            <div class="small text-muted fw-semibold" style="font-size: 12px;">Total On Time</div>
          </div>
          <div class="p-3 rounded-3" style="background: #ecfdf5; color: #10b981;">
            <i class="bi bi-person-check fs-4"></i>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-lg-3">
        <div class="card border-0 p-3 shadow-sm rounded-3 d-flex flex-row align-items-center justify-content-between" style="background: #f8fafc;">
          <div>
            <h2 class="fw-bold mb-0 text-dark">{{ $monthStats['late'] }}</h2>
            <div class="small text-muted fw-semibold" style="font-size: 12px;">Total Late</div>
          </div>
          <div class="p-3 rounded-3" style="background: #fffbe0; color: #d97706;">
            <i class="bi bi-clock fs-4"></i>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-lg-3">
        <div class="card border-0 p-3 shadow-sm rounded-3 d-flex flex-row align-items-center justify-content-between" style="background: #f8fafc;">
          <div>
            <h2 class="fw-bold mb-0 text-dark">{{ $monthStats['absent'] }}</h2>
            <div class="small text-muted fw-semibold" style="font-size: 12px;">Total Not Present</div>
          </div>
          <div class="p-3 rounded-3" style="background: #fef2f2; color: #ef4444;">
            <i class="bi bi-person-x fs-4"></i>
          </div>
        </div>
      </div>

      <div class="col-md-6 col-lg-3">
        <div class="card border-0 p-3 shadow-sm rounded-3 d-flex flex-row align-items-center justify-content-between" style="background: #f8fafc;">
          <div>
            <h2 class="fw-bold mb-0 text-dark">{{ $monthStats['working'] }}</h2>
            <div class="small text-muted fw-semibold" style="font-size: 12px;">Total Working Hours</div>
          </div>
          <div class="p-3 rounded-3" style="background: #eff6ff; color: #0073e6;">
            <i class="bi bi-briefcase fs-4"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Attendance Log Card -->
    <div class="card border-0 p-4 shadow-sm rounded-3 mb-4">
      <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-3">
        <h5 class="fw-bold text-dark mb-0">Attendance Log</h5>
        <a href="{{ route('attendance.report') }}" class="text-primary text-decoration-none fw-bold small">View Log</a>
      </div>

      @forelse($formattedLogs as $log)
        <div class="d-flex align-items-center justify-content-between py-3 border-bottom">
          <div class="d-flex align-items-center gap-3">
            <i class="bi {{ $log['icon'] }} fs-5 text-secondary"></i>
            <div>
              <div class="fw-bold text-dark small">{{ $log['type'] }}</div>
              <div class="text-muted small" style="font-size: 12px;">{{ $log['date'] }}</div>
            </div>
          </div>
          <div class="fw-bold text-dark small">{{ $log['time'] }}</div>
        </div>
      @empty
        <div class="text-center py-4 text-muted">
          <i class="bi bi-calendar-x fs-3 d-block mb-2"></i>
          <span>No attendance records found</span>
        </div>
      @endforelse
    </div>
  </div>

  {{-- MOBILE VIEW --}}
  <div class="att-page-wrapper d-block d-md-none">
    <div class="att-header-banner">
      <div class="top-action-bar">
        <a href="{{ route('main') }}" class="btn-back-link">
          <i class="bi bi-arrow-left"></i>
        </a>
        <h1 class="header-page-title">Attendance</h1>
        <div style="width: 38px;"></div>
      </div>

      <div class="digital-clock-box">
        <div class="clock-live-time" id="clockLiveTime">--:--:--</div>
        <div class="clock-live-date">
          <span class="live-dot"></span>
          <span id="clockLiveDate">{{ \Carbon\Carbon::now()->format('l, d F Y') }}</span>
        </div>
      </div>
    </div>

    <div class="content-body">
      <!-- Clock In / Out Action Grid -->
      <div class="action-clock-grid">
        <div id="clockin-button"
          class="clock-action-card {{ $todayAttendance && $todayAttendance->clock_in_time ? 'disabled' : '' }}"
          onclick="handleClockAction('in')">
          <div class="clock-icon-circle clock-in-bg">
            <i class="bi bi-box-arrow-in-right"></i>
          </div>
          <div class="clock-action-label">CLOCK IN</div>
          <div class="clock-action-time">
            {{ $todayAttendance && $todayAttendance->clock_in_time ? \Carbon\Carbon::parse($todayAttendance->clock_in_time)->format('H:i') : '--:--' }}
          </div>
        </div>

        <div id="clockout-button"
          class="clock-action-card {{ !$todayAttendance || !$todayAttendance->clock_in_time || ($todayAttendance && $todayAttendance->clock_out_time) ? 'disabled' : '' }}"
          onclick="handleClockAction('out')">
          <div class="clock-icon-circle clock-out-bg">
            <i class="bi bi-box-arrow-right"></i>
          </div>
          <div class="clock-action-label">CLOCK OUT</div>
          <div class="clock-action-time">
            {{ $todayAttendance && $todayAttendance->clock_out_time ? \Carbon\Carbon::parse($todayAttendance->clock_out_time)->format('H:i') : '--:--' }}
          </div>
        </div>
      </div>

      <!-- Stat Cards Grid (2x2) -->
      <div class="stats-grid-container">
        <div class="stat-mini-card">
          <div>
            <div class="stat-mini-val text-success">{{ $monthStats['on_time'] }}</div>
            <div class="stat-mini-label">On Time</div>
          </div>
          <div class="stat-icon-box" style="background: #ecfdf5; color: #10b981;">
            <i class="bi bi-check-circle-fill"></i>
          </div>
        </div>

        <div class="stat-mini-card">
          <div>
            <div class="stat-mini-val text-warning">{{ $monthStats['late'] }}</div>
            <div class="stat-mini-label">Late</div>
          </div>
          <div class="stat-icon-box" style="background: #fffbe0; color: #d97706;">
            <i class="bi bi-clock-history"></i>
          </div>
        </div>

        <div class="stat-mini-card">
          <div>
            <div class="stat-mini-val text-danger">{{ $monthStats['absent'] }}</div>
            <div class="stat-mini-label">Not Present</div>
          </div>
          <div class="stat-icon-box" style="background: #fef2f2; color: #ef4444;">
            <i class="bi bi-x-circle-fill"></i>
          </div>
        </div>

        <div class="stat-mini-card">
          <div>
            <div class="stat-mini-val text-primary">{{ $monthStats['working'] }}</div>
            <div class="stat-mini-label">Work Hours</div>
          </div>
          <div class="stat-icon-box" style="background: #eff6ff; color: #0073e6;">
            <i class="bi bi-briefcase-fill"></i>
          </div>
        </div>
      </div>

      <!-- Attendance Log Card -->
      <div class="log-card">
        <div class="log-card-header">
          <h3 class="log-card-title">Recent Attendance Logs</h3>
          <a href="{{ route('attendance.report') }}" class="log-view-all-link">
            View Report <i class="bi bi-arrow-right"></i>
          </a>
        </div>

        @forelse($formattedLogs as $log)
          <div class="log-row-item">
            <div class="log-item-left">
              <div class="stat-icon-box" style="background: #f8fafc; color: #0073e6;">
                <i class="bi {{ $log['icon'] }}"></i>
              </div>
              <div>
                <h4 class="log-type-title">{{ $log['type'] }}</h4>
                <p class="log-date-sub">{{ $log['date'] }}</p>
              </div>
            </div>
            <span class="log-time-badge">{{ $log['time'] }}</span>
          </div>
        @empty
          <div class="text-center py-4">
            <i class="bi bi-calendar-x text-muted" style="font-size: 32px;"></i>
            <p class="mt-2 text-muted small mb-0">No attendance records found this month</p>
          </div>
        @endforelse
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script>
    $(document).ready(function() {
      function updateLiveClock() {
        const now = new Date();
        const timeStr = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false });
        const timeStr12 = now.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', second: '2-digit', hour12: true });
        $('#clockLiveTime').text(timeStr);
        $('#desktopLiveTime').text(timeStr12);
      }

      updateLiveClock();
      setInterval(updateLiveClock, 1000);

      const urlParams = new URLSearchParams(window.location.search);
      const success = urlParams.get('success');
      const error = urlParams.get('error');

      if (success === 'clockin') {
        showAlert('success', 'Clock in recorded successfully!');
      } else if (success === 'clockout') {
        showAlert('success', 'Clock out recorded successfully!');
      } else if (error) {
        showAlert('danger', error);
      }
    });

    function handleClockAction(type) {
      if (type === 'in') {
        const btn = document.getElementById('clockin-button');
        if (btn.classList.contains('disabled')) {
          showAlert('warning', 'You have already clocked in today');
          return;
        }
        window.location.href = "{{ route('attendance.check', ['type' => 'in']) }}";
      } else if (type === 'out') {
        const btn = document.getElementById('clockout-button');
        if (btn.classList.contains('disabled')) {
          const clockInTime = document.getElementById('clockin-button').querySelector('.clock-action-time').innerText;
          if (clockInTime === '--:--') {
            showAlert('info', 'You must clock in first');
          } else {
            showAlert('warning', 'You have already clocked out today');
          }
          return;
        }
        window.location.href = "{{ route('attendance.check', ['type' => 'out']) }}";
      }
    }
  </script>
@endsection
