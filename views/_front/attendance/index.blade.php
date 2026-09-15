@extends('headers.head')

@section('header')
  <style>
    .main-container {
      background-color: #fff;
      min-height: 100vh;
      padding: 16px 24px !important;
    }

    .card {
      background-color: #fff;
      border-radius: 10px;
      border: 1px solid #e2e8f0;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
    }

    .digital-clock-desktop {
      font-size: 22px;
      font-weight: 700;
      font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
    }

    .card-statistic-3 {
      position: relative;
      overflow: hidden;
      border-radius: 10px;
      padding: 14px 18px !important;
    }

    .card-statistic-3 .card-icon {
      position: absolute;
      right: 12px;
      top: 8px;
      opacity: 0.18;
    }

    .card-statistic-3 .card-icon i {
      font-size: 56px;
      color: #ffffff;
    }

    .l-bg-green-dark {
      background: linear-gradient(135deg, #0a504a 0%, #38ef7d 100%) !important;
      color: #ffffff !important;
      border: none !important;
    }

    .l-bg-orange-dark {
      background: linear-gradient(135deg, #a86008 0%, #ffba56 100%) !important;
      color: #ffffff !important;
      border: none !important;
    }

    .l-bg-cherry {
      background: linear-gradient(135deg, #493240 0%, #ff4b4b 100%) !important;
      color: #ffffff !important;
      border: none !important;
    }

    .l-bg-blue-dark {
      background: linear-gradient(135deg, #373b44 0%, #4286f4 100%) !important;
      color: #ffffff !important;
      border: none !important;
    }
  </style>
@endsection

@section('body')
  <div class="bg-white main-container">
    <!-- Header Greeting Bar (No back button) -->
    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
      <div>
        <h6 class="fw-bold text-dark mb-0">{{ date('l, d F Y') }}</h6>
        <p class="text-muted small mb-0" style="font-size: 12px;"><i class="bi bi-person-fill text-primary me-1"></i> Welcome back, {{ $employee->fullname }}</p>
      </div>

      <div class="text-end">
        <div class="digital-clock-desktop text-dark" id="live-desktop-clock">00:00:00 AM</div>
        <span class="badge bg-secondary" style="font-size: 10px;">WIB (UTC+7)</span>
      </div>
    </div>

    <!-- 4 Metric Cards (English) -->
    <div class="row g-3 mb-3">
      <div class="col-md-3">
        <div class="card card-statistic-3 l-bg-green-dark shadow-sm">
          <div class="card-icon"><i class="bi bi-clock-check-fill"></i></div>
          <span class="text-white-50 small d-block mb-1 text-uppercase fw-bold" style="font-size: 10.5px; letter-spacing: 0.5px;">On-Time Total</span>
          <h4 class="fw-bold text-white mb-0">{{ $monthStats['on_time'] }} <small class="text-white-50 fs-6 fw-normal">Days</small></h4>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card card-statistic-3 l-bg-orange-dark shadow-sm">
          <div class="card-icon"><i class="bi bi-exclamation-triangle-fill"></i></div>
          <span class="text-white-50 small d-block mb-1 text-uppercase fw-bold" style="font-size: 10.5px; letter-spacing: 0.5px;">Late Total</span>
          <h4 class="fw-bold text-white mb-0">{{ $monthStats['late'] }} <small class="text-white-50 fs-6 fw-normal">Days</small></h4>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card card-statistic-3 l-bg-cherry shadow-sm">
          <div class="card-icon"><i class="bi bi-dash-circle-fill"></i></div>
          <span class="text-white-50 small d-block mb-1 text-uppercase fw-bold" style="font-size: 10.5px; letter-spacing: 0.5px;">Absent Total</span>
          <h4 class="fw-bold text-white mb-0">{{ $monthStats['absent'] }} <small class="text-white-50 fs-6 fw-normal">Days</small></h4>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card card-statistic-3 l-bg-blue-dark shadow-sm">
          <div class="card-icon"><i class="bi bi-briefcase-fill"></i></div>
          <span class="text-white-50 small d-block mb-1 text-uppercase fw-bold" style="font-size: 10.5px; letter-spacing: 0.5px;">Working Hours</span>
          <h4 class="fw-bold text-white mb-0">{{ number_format($monthStats['working'], 1) }} <small class="text-white-50 fs-6 fw-normal">Hours</small></h4>
        </div>
      </div>
    </div>

    <!-- Clock Action Cards (English) -->
    <div class="row g-3 mb-3">
      <div class="col-md-6">
        <div class="card p-3 shadow-sm h-100">
          <div class="d-flex align-items-center justify-content-between mb-2">
            <div>
              <h6 class="fw-bold text-dark mb-0"><i class="bi bi-box-arrow-in-right text-primary me-1"></i> Clock In</h6>
              <p class="text-muted small mb-0" style="font-size: 11.5px;">Record your morning check-in time</p>
            </div>
            @if ($todayAttendance && $todayAttendance->clock_in_time)
              <span class="badge bg-success" style="font-size: 11px;">Recorded: {{ \Carbon\Carbon::parse($todayAttendance->clock_in_time)->format('h:i A') }}</span>
            @endif
          </div>

          @if ($todayAttendance && $todayAttendance->clock_in_time)
            <button type="button" class="btn btn-secondary btn-sm w-100 mt-2 py-2" disabled>Already Clocked In Today</button>
          @else
            <a href="{{ route('attendance.check', 'in') }}" class="btn btn-primary btn-sm w-100 mt-2 py-2 fw-bold"><i class="bi bi-camera me-1"></i> Clock In Now</a>
          @endif
        </div>
      </div>

      <div class="col-md-6">
        <div class="card p-3 shadow-sm h-100">
          <div class="d-flex align-items-center justify-content-between mb-2">
            <div>
              <h6 class="fw-bold text-dark mb-0"><i class="bi bi-box-arrow-right text-danger me-1"></i> Clock Out</h6>
              <p class="text-muted small mb-0" style="font-size: 11.5px;">Record your evening check-out time</p>
            </div>
            @if ($todayAttendance && $todayAttendance->clock_out_time)
              <span class="badge bg-danger" style="font-size: 11px;">Recorded: {{ \Carbon\Carbon::parse($todayAttendance->clock_out_time)->format('h:i A') }}</span>
            @endif
          </div>

          @if (!$todayAttendance || !$todayAttendance->clock_in_time)
            <button type="button" class="btn btn-secondary btn-sm w-100 mt-2 py-2" disabled>Clock In First Required</button>
          @elseif ($todayAttendance->clock_out_time)
            <button type="button" class="btn btn-secondary btn-sm w-100 mt-2 py-2" disabled>Already Clocked Out Today</button>
          @else
            <a href="{{ route('attendance.check', 'out') }}" class="btn btn-danger btn-sm w-100 mt-2 py-2 fw-bold"><i class="bi bi-camera me-1"></i> Clock Out Now</a>
          @endif
        </div>
      </div>
    </div>

    <!-- Recent Activity Table (English) -->
    <div class="card shadow-sm">
      <div class="card-header bg-light py-2 px-3 d-flex align-items-center justify-content-between">
        <h6 class="fw-bold text-dark mb-0 style-title" style="font-size: 13.5px;"><i class="bi bi-journal-text me-1 text-primary"></i> Recent Attendance Activity</h6>
        <a href="{{ route('attendance.report') }}" class="btn btn-outline-primary btn-sm py-1 px-3" style="font-size: 12px;">View Full Report <i class="bi bi-arrow-right ms-1"></i></a>
      </div>

      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th class="py-2 px-3 text-muted small" style="font-size: 12px;">Activity Type</th>
              <th class="py-2 px-3 text-muted small" style="font-size: 12px;">Date</th>
              <th class="py-2 px-3 text-muted small" style="font-size: 12px;">Recorded Time</th>
            </tr>
          </thead>
          <tbody>
            @if (count($formattedLogs) > 0)
              @foreach ($formattedLogs as $log)
                <tr>
                  <td class="py-2 px-3">
                    <span class="badge {{ $log['type'] == 'Clock In' ? 'bg-primary' : 'bg-danger' }}" style="font-size: 11px;">
                      <i class="bi {{ $log['icon'] }} me-1"></i> {{ $log['type'] }}
                    </span>
                  </td>
                  <td class="py-2 px-3 text-dark small" style="font-size: 12.5px;">{{ $log['date'] }}</td>
                  <td class="py-2 px-3 text-dark fw-bold small" style="font-size: 12.5px;">{{ $log['time'] }}</td>
                </tr>
              @endforeach
            @else
              <tr>
                <td colspan="3" class="text-center py-3 text-muted small">No recent attendance logs found.</td>
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
