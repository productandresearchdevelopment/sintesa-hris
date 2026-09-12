@extends('templates.mobile')

@section('head')
  <style>
    .clock-card {
      background-color: var(--primary-color);
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 25px;
    }

    .date-chip {
      color: var(--white);
      font-size: 16px;
      font-weight: 500;
    }

    .time-chip {
      background-color: var(--white);
      color: var(--primary-color);
      border-radius: 20px;
      padding: 6px 16px;
      font-size: 13px;
      font-weight: 600;
      display: inline-block;
    }

    .clock-buttons {
      display: flex;
      justify-content: space-between;
      margin-top: 20px;
    }

    .clock-button {
      background-color: white;
      border: 1px solid #dee2e6;
      border-radius: 12px;
      padding: 20px;
      flex: 1;
      text-align: center;
      margin: 0 5px;
      cursor: pointer;
      transition: all 0.2s ease;
    }

    .clock-button:hover {
      background-color: #f8f9fa;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .clock-button.disabled {
      opacity: 0.6;
      cursor: not-allowed;
    }

    .time-label {
      font-size: 14px;
      color: #6c757d;
      margin-bottom: 5px;
    }

    .time-value {
      font-weight: bold;
      font-size: 20px;
      color: #212529;
    }

    /* === Stat Cards === */
    .stats-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 10px;
      margin-bottom: 20px;
    }

    .stat-card {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: var(--background-color);
      border-radius: 10px;
      padding: 15px;
    }

    .stat-icon {
      width: 40px;
      height: 40px;
      font-size: 24px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 8px;
    }

    .stat-info {
      display: flex;
      flex-direction: column;
      gap: 5px;
    }

    .stat-number {
      font-weight: bold;
      font-size: 32px;
      margin-bottom: 0px;
    }

    .stat-label {
      font-size: 12px;
      color: #6c757d;
      margin-bottom: 0px;
    }

    .absent-icon {
      background-color: #fee2e2;
      color: var(--red-color);
    }

    .on-time-icon {
      background-color: #d1fae5;
      color: var(--green-color);
    }

    .late-icon {
      background-color: #ffedd5;
      color: var(--yellow-color);
    }

    .working-icon {
      background-color: #e0e7ff;
      color: var(--blue-color);
    }

    /* === Attendance Log === */

    .attendance-log {
      background-color: white;
      border: 1px solid #dee2e6;
      border-radius: 10px;
      padding: 15px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      margin-bottom: 20px;
    }

    .log-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 10px;
    }

    .log-title {
      font-weight: bold;
      font-size: 16px;
    }

    .log-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px 0;
      border-bottom: 1px solid #f1f1f1;
      font-size: 14px;
    }

    .log-item .log-left {
      display: flex;
      flex-direction: column;
    }

    .log-date {
      font-weight: 500;
      color: #6c757d;
      font-size: 13px;
    }

    .log-type {
      font-weight: 600;
      color: #333;
    }

    .toast-container {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 9999;
    }

    .toast {
      background-color: rgba(255, 255, 255, 0.95);
      border-radius: 10px;
      padding: 15px;
      margin-bottom: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      display: flex;
      align-items: center;
      max-width: 300px;
    }

    .toast-success {
      border-left: 4px solid var(--green-color);
    }

    .toast-error {
      border-left: 4px solid var(--red-color);
    }

    .toast-icon {
      margin-right: 10px;
      font-size: 24px;
    }

    .toast-success .toast-icon {
      color: var(--green-color);
    }

    .toast-error .toast-icon {
      color: var(--red-color);
    }

    .toast-content {
      flex: 1;
    }

    .toast-title {
      font-weight: 600;
      margin-bottom: 5px;
    }

    .toast-message {
      font-size: 14px;
      color: #4b5563;
    }
  </style>
@endsection

@section('content')
  <div class="px-3" style="min-height: 100%; min-width: 100%; padding-bottom: 100px;">
    @if (isMobile())
      <div class="py-3">
        <div class="d-flex align-items-center gap-4" style="cursor: pointer;"
          onclick="window.location.href='{{ route('main') }}'">
          <i class="bi bi-chevron-left" style="font-size: 1.2rem;"></i>
          <p class="m-0" style="font-size: 1.1rem;">Attendance</p>
        </div>
      </div>
    @endif

    <div class="clock-card">
      <div class="d-flex justify-content-between align-items-center">
        <div class="date-chip" id="current-date">{{ \Carbon\Carbon::now()->format('j F, Y') }}</div>
        <div class="time-chip" id="current-time">{{ \Carbon\Carbon::now()->format('g:i A') }}</div>
      </div>

      <div class="clock-buttons">
        <div id="clockin-button"
          class="clock-button {{ $todayAttendance && $todayAttendance->clock_in_time ? 'disabled' : '' }}"
          onclick="handleClockAction('in')">
          <div class="time-label">Clock in</div>
          <div class="time-value">
            {{ $todayAttendance && $todayAttendance->clock_in_time ? \Carbon\Carbon::parse($todayAttendance->clock_in_time)->format('h:i A') : '--:-- --' }}
          </div>
        </div>
        <div id="clockout-button"
          class="clock-button {{ !$todayAttendance || !$todayAttendance->clock_in_time || ($todayAttendance && $todayAttendance->clock_out_time) ? 'disabled' : '' }}"
          onclick="handleClockAction('out')">
          <div class="time-label">Clock out</div>
          <div class="time-value">
            {{ $todayAttendance && $todayAttendance->clock_out_time ? \Carbon\Carbon::parse($todayAttendance->clock_out_time)->format('h:i A') : '--:-- --' }}
          </div>
        </div>
      </div>
    </div>

    <div class="stats-row">
      <div class="stat-card">
        <div class="stat-info">
          <p class="stat-number">{{ $monthStats['on_time'] }}</p>
          <p class="stat-label">Total On Time</p>
        </div>
        <div class="stat-icon on-time-icon">
          <i class="bi bi-person-check"></i>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-info">
          <p class="stat-number">{{ $monthStats['late'] }}</p>
          <p class="stat-label">Total Late</p>
        </div>
        <div class="stat-icon late-icon">
          <i class="bi bi-clock"></i>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-info">
          <p class="stat-number">{{ $monthStats['absent'] }}</p>
          <p class="stat-label">
            Total Absent
          </p>
        </div>
        <div class="stat-icon absent-icon">
          <i class="bi bi-person-x"></i>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-info">
          <p class="stat-number">{{ $monthStats['working'] }}</p>
          <p class="stat-label">Total Working Hours</p>
        </div>
        <div class="stat-icon working-icon">
          <i class="bi bi-briefcase"></i>
        </div>
      </div>
    </div>

    <div class="attendance-log">
      <div class="log-header">
        <div class="log-title">Attendance Log</div>
        <a href="{{ route('attendance.report') }}" style="font-size: 14px; color: var(--primary-color);">View Log</a>
      </div>

      @forelse($formattedLogs as $log)
        <div class="log-item">
          <div class="log-left">
            <div class="log-type"><i class="bi {{ $log['icon'] }}"></i> {{ $log['type'] }}</div>
            <div class="log-date">{{ $log['date'] }}</div>
          </div>
          <div class="log-time">{{ $log['time'] }}</div>
        </div>
      @empty
        <div class="text-center py-4">
          <i class="bi bi-calendar-x text-muted" style="font-size: 24px;"></i>
          <p class="mt-2 text-muted">No attendance records found</p>
        </div>
      @endforelse
    </div>

  </div>
@endsection

@section('scripts')
  <script>
    $(document).ready(function() {
      function updateDateTime() {
        const now = new Date();
        const dateOptions = {
          day: 'numeric',
          month: 'long',
          year: 'numeric'
        };
        const timeOptions = {
          hour: 'numeric',
          minute: 'numeric',
          second: 'numeric',
          hour12: true
        };

        $('#current-date').text(now.toLocaleDateString('en-US', dateOptions));
        $('#current-time').text(now.toLocaleTimeString('en-US', timeOptions));
      }

      updateDateTime();
      setInterval(updateDateTime, 1000);

      const urlParams = new URLSearchParams(window.location.search);
      const success = urlParams.get('success');
      const error = urlParams.get('error');

      if (success === 'clockin') {
        showAlert('success', 'You have clocked in successfully');
      } else if (success === 'clockout') {
        showAlert('success', 'You have clocked out successfully');
      } else if (error) {
        showAlert('danger', error);
      }
    });

    function handleClockAction(type) {
      if (type === 'in') {
        const clockInButton = document.getElementById('clockin-button');
        if (clockInButton.classList.contains('disabled')) {
          showAlert('warning', 'You have already clocked in today');
          return;
        }
        window.location.href = "{{ route('attendance.check', ['type' => 'in']) }}";
      } else if (type === 'out') {
        const clockOutButton = document.getElementById('clockout-button');
        if (clockOutButton.classList.contains('disabled')) {
          const clockInValue = document.getElementById('clockin-button').querySelector('.time-value').innerText;
          if (clockInValue === '--:-- --') {
            showAlert('info', 'You need to clock in before you can clock out');
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
