@extends('headers.head')

@section('header')
  <style>
    .main-container {
      background-color: #ffffff;
      min-height: 100vh;
      padding: 16px 20px !important;
    }

    @media (max-width: 576px) {
      .main-container {
        padding: 12px 10px !important;
      }
    }

    .card-att-base {
      background-color: #ffffff;
      border-radius: 12px;
      border: 1px solid #e2e8f0;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
      transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .digital-clock-desktop {
      font-size: 20px;
      font-weight: 700;
      font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
      letter-spacing: -0.5px;
    }

    .card-statistic-3 {
      position: relative;
      overflow: hidden;
      border-radius: 12px;
      padding: 12px 14px !important;
      border: none !important;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .card-statistic-3 .card-icon {
      position: absolute;
      right: 10px;
      top: 8px;
      opacity: 0.18;
      pointer-events: none;
    }

    .card-statistic-3 .card-icon i {
      font-size: 42px;
      color: #ffffff;
    }

    .l-bg-green-dark {
      background: linear-gradient(135deg, #059669 0%, #10b981 100%) !important;
      color: #ffffff !important;
    }

    .l-bg-orange-dark {
      background: linear-gradient(135deg, #d97706 0%, #f59e0b 100%) !important;
      color: #ffffff !important;
    }

    .l-bg-cherry {
      background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%) !important;
      color: #ffffff !important;
    }

    .l-bg-blue-dark {
      background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%) !important;
      color: #ffffff !important;
    }

    .action-clock-box {
      border-radius: 12px;
      border: 1px solid #e2e8f0;
      background: #ffffff;
      padding: 14px 16px;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    }
  </style>
@endsection

@section('body')
  <div class="bg-white main-container">
    <!-- Header Greeting Bar -->
    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
      <div>
        <h6 class="fw-bold text-dark mb-0" style="font-size: 15px;">{{ date('l, d F Y') }}</h6>
        <p class="text-muted small mb-0" style="font-size: 11.5px;">
          <i class="bi bi-person-fill text-primary me-1"></i> Welcome back, <span
            class="fw-semibold text-dark">{{ $employee->fullname }}</span>
        </p>
      </div>

      <div class="text-end">
        <div class="digital-clock-desktop text-dark lh-1 mb-1" id="live-desktop-clock">00:00:00 AM</div>
        <span class="badge bg-light text-secondary border py-1 px-2" style="font-size: 9.5px;">WIB (UTC+7)</span>
      </div>
    </div>

    <!-- 4 Metric Cards (2x2 on Mobile, 4 Cols on Desktop) -->
    <div class="row g-2 g-md-3 mb-3">
      <div class="col-6 col-md-3">
        <div class="card card-statistic-3 l-bg-green-dark h-100">
          <div class="card-icon"><i class="bi bi-clock-check-fill"></i></div>
          <span class="text-white-50 small d-block mb-1 text-uppercase fw-bold"
            style="font-size: 10px; letter-spacing: 0.5px;">On-Time Total</span>
          <h4 class="fw-bold text-white mb-0" style="font-size: 20px;">
            {{ $monthStats['on_time'] }} <small class="text-white-50 fw-normal" style="font-size: 12px;">Days</small>
          </h4>
        </div>
      </div>

      <div class="col-6 col-md-3">
        <div class="card card-statistic-3 l-bg-orange-dark h-100">
          <div class="card-icon"><i class="bi bi-exclamation-triangle-fill"></i></div>
          <span class="text-white-50 small d-block mb-1 text-uppercase fw-bold"
            style="font-size: 10px; letter-spacing: 0.5px;">Late Total</span>
          <h4 class="fw-bold text-white mb-0" style="font-size: 20px;">
            {{ $monthStats['late'] }} <small class="text-white-50 fw-normal" style="font-size: 12px;">Days</small>
          </h4>
        </div>
      </div>

      <div class="col-6 col-md-3">
        <div class="card card-statistic-3 l-bg-cherry h-100">
          <div class="card-icon"><i class="bi bi-dash-circle-fill"></i></div>
          <span class="text-white-50 small d-block mb-1 text-uppercase fw-bold"
            style="font-size: 10px; letter-spacing: 0.5px;">Not Present</span>
          <h4 class="fw-bold text-white mb-0" style="font-size: 20px;">
            {{ $monthStats['absent'] }} <small class="text-white-50 fw-normal" style="font-size: 12px;">Days</small>
          </h4>
        </div>
      </div>

      <div class="col-6 col-md-3">
        <div class="card card-statistic-3 l-bg-blue-dark h-100">
          <div class="card-icon"><i class="bi bi-briefcase-fill"></i></div>
          <span class="text-white-50 small d-block mb-1 text-uppercase fw-bold"
            style="font-size: 10px; letter-spacing: 0.5px;">Working Hours</span>
          <h4 class="fw-bold text-white mb-0" style="font-size: 20px;">
            {{ number_format($monthStats['working'], 1) }} <small class="text-white-50 fw-normal"
              style="font-size: 12px;">Hours</small>
          </h4>
        </div>
      </div>
    </div>

    <!-- Clock In / Clock Out Action Cards -->
    <div class="row g-2 g-md-3 mb-3">
      <div class="col-12 col-md-6">
        <div class="action-clock-box h-100 d-flex flex-column justify-content-between">
          <div class="d-flex align-items-center justify-content-between mb-2">
            <div>
              <h6 class="fw-bold text-dark mb-0" style="font-size: 13.5px;">
                <i class="bi bi-box-arrow-in-right text-success me-1"></i> Clock In
              </h6>
              <p class="text-muted small mb-0" style="font-size: 11px;">Record your morning check-in time</p>
            </div>
            @if ($todayAttendance && $todayAttendance->clock_in_time)
              <span class="badge bg-success-subtle text-success border border-success-subtle py-1 px-2"
                style="font-size: 11px;">
                <i class="bi bi-check2-circle me-1"></i>
                {{ \Carbon\Carbon::parse($todayAttendance->clock_in_time)->format('h:i A') }}
              </span>
            @endif
          </div>

          <div class="mt-2">
            @if ($todayAttendance && $todayAttendance->clock_in_time)
              <button type="button" class="btn btn-secondary btn-sm w-100 py-2 fw-semibold opacity-75"
                style="font-size: 12px;" disabled>
                <i class="bi bi-check-circle-fill me-1"></i> Already Clocked In Today
              </button>
            @else
              <a href="{{ route('attendance.check', 'in') }}" class="btn btn-primary btn-sm w-100 py-2 fw-bold"
                style="font-size: 12px;">
                <i class="bi bi-camera me-1"></i> Clock In Now
              </a>
            @endif
          </div>
        </div>
      </div>

      <div class="col-12 col-md-6">
        <div class="action-clock-box h-100 d-flex flex-column justify-content-between">
          <div class="d-flex align-items-center justify-content-between mb-2">
            <div>
              <h6 class="fw-bold text-dark mb-0" style="font-size: 13.5px;">
                <i class="bi bi-box-arrow-right text-danger me-1"></i> Clock Out
              </h6>
              <p class="text-muted small mb-0" style="font-size: 11px;">Record your evening check-out time</p>
            </div>
            @if ($todayAttendance && $todayAttendance->clock_out_time)
              <span class="badge bg-danger-subtle text-danger border border-danger-subtle py-1 px-2"
                style="font-size: 11px;">
                <i class="bi bi-check2-circle me-1"></i>
                {{ \Carbon\Carbon::parse($todayAttendance->clock_out_time)->format('h:i A') }}
              </span>
            @endif
          </div>

          <div class="mt-2">
            @if (!$todayAttendance || !$todayAttendance->clock_in_time)
              <button type="button" class="btn btn-light btn-sm w-100 py-2 text-muted border" style="font-size: 12px;"
                disabled>
                Clock In First Required
              </button>
            @elseif ($todayAttendance->clock_out_time)
              <button type="button" class="btn btn-secondary btn-sm w-100 py-2 fw-semibold opacity-75"
                style="font-size: 12px;" disabled>
                <i class="bi bi-check-circle-fill me-1"></i> Already Clocked Out Today
              </button>
            @else
              <a href="{{ route('attendance.check', 'out') }}" class="btn btn-danger btn-sm w-100 py-2 fw-bold"
                style="font-size: 12px;">
                <i class="bi bi-camera me-1"></i> Clock Out Now
              </a>
            @endif
          </div>
        </div>
      </div>
    </div>

    <!-- Recent Activity Table -->
    <div class="card card-att-base">
      <div class="card-header bg-light py-2 px-3 d-flex align-items-center justify-content-between border-bottom">
        <h6 class="fw-bold text-dark mb-0" style="font-size: 13px;">
          <i class="bi bi-journal-text me-1 text-primary"></i> Recent Attendance Activity
        </h6>
        <a href="{{ route('attendance.report') }}" class="btn btn-outline-primary btn-sm py-0 px-2"
          style="font-size: 11.5px;">
          View Full Report <i class="bi bi-arrow-right ms-1"></i>
        </a>
      </div>

      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th class="py-2 px-3 text-muted small" style="font-size: 11.5px;">Activity Type</th>
              <th class="py-2 px-3 text-muted small" style="font-size: 11.5px;">Date</th>
              <th class="py-2 px-3 text-muted small" style="font-size: 11.5px;">Recorded Time</th>
            </tr>
          </thead>
          <tbody>
            @if (count($formattedLogs) > 0)
              @foreach ($formattedLogs as $log)
                <tr>
                  <td class="py-2 px-3">
                    <span class="badge {{ $log['type'] == 'Clock In' ? 'bg-primary' : 'bg-danger' }}"
                      style="font-size: 10.5px;">
                      <i class="bi {{ $log['icon'] }} me-1"></i> {{ $log['type'] }}
                    </span>
                  </td>
                  <td class="py-2 px-3 text-dark small" style="font-size: 12px;">{{ $log['date'] }}</td>
                  <td class="py-2 px-3 text-dark fw-bold small" style="font-size: 12px;">{{ $log['time'] }}</td>
                </tr>
              @endforeach
            @else
              <tr>
                <td colspan="3" class="text-center py-3 text-muted small" style="font-size: 12px;">No recent
                  attendance logs found.</td>
              </tr>
            @endif
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script>
    function updateDesktopClock() {
      const now = new Date();
      let hours = now.getHours();
      const minutes = String(now.getMinutes()).padStart(2, '0');
      const seconds = String(now.getSeconds()).padStart(2, '0');
      const ampm = hours >= 12 ? 'PM' : 'AM';
      hours = hours % 12;
      hours = hours ? hours : 12;
      const hoursStr = String(hours).padStart(2, '0');
      const clockElem = document.getElementById('live-desktop-clock');
      if (clockElem) {
        clockElem.textContent = `${hoursStr}:${minutes}:${seconds} ${ampm}`;
      }
    }
    setInterval(updateDesktopClock, 1000);
    updateDesktopClock();
  </script>
@endsection
