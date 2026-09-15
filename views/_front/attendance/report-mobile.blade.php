@extends('templates.mobile')

@section('head')
  <style>
    html, body {
      background-color: #ffffff !important;
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    .report-page-wrapper {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      padding-bottom: 40px;
    }

    .report-header-banner {
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

    .btn-dots-more {
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
      transition: all 0.2s ease;
      cursor: pointer;
    }

    .btn-dots-more:active {
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
      .report-header-banner {
        display: none !important;
      }
      .content-body {
        margin-top: 0 !important;
        padding: 0 !important;
      }
    }

    /* Summary Card Styling */
    .summary-card-att {
      background: #ffffff;
      border-radius: 20px;
      padding: 18px 20px;
      border: 1px solid #f1f5f9;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
      margin-bottom: 12px;
    }

    .summary-label {
      font-size: 11.5px;
      font-weight: 800;
      color: #64748b;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      display: block;
      margin-bottom: 2px;
    }

    .summary-value {
      font-size: 22px;
      font-weight: 800;
      color: #0f172a;
    }

    .btn-summary-action {
      border-radius: 14px;
      padding: 10px 18px;
      font-size: 13px;
      font-weight: 800;
      border: none;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      transition: all 0.2s ease;
      cursor: pointer;
    }

    .btn-primary-action {
      background: #0073e6;
      color: #ffffff;
      box-shadow: 0 4px 12px rgba(0, 115, 230, 0.25);
    }

    .btn-primary-action:active {
      transform: scale(0.96);
      background: #005bb5;
    }

    /* Filter Card */
    .filter-card {
      background: #ffffff;
      border-radius: 20px;
      padding: 16px;
      border: 1px solid #f1f5f9;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
      margin-bottom: 12px;
    }

    .filter-card-title {
      font-size: 13px;
      font-weight: 800;
      color: #0f172a;
      margin-bottom: 12px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .filter-select-custom {
      width: 100%;
      height: 44px;
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 14px;
      padding: 0 14px;
      font-size: 13.5px;
      font-weight: 700;
      color: #0f172a;
      outline: none;
      transition: all 0.2s ease;
    }

    .filter-select-custom:focus {
      border-color: #0073e6;
      background: #ffffff;
      box-shadow: 0 0 0 3px rgba(0, 115, 230, 0.15);
    }

    /* Attendance Record Cards for Mobile */
    .record-card {
      background: #ffffff;
      border-radius: 20px;
      padding: 18px 20px;
      border: 1px solid #f1f5f9;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
      margin-bottom: 12px;
    }

    .record-card-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      border-bottom: 1px solid #f1f5f9;
      padding-bottom: 10px;
      margin-bottom: 12px;
    }

    .record-emp-name {
      font-size: 14.5px;
      font-weight: 800;
      color: #0f172a;
      margin: 0;
    }

    .record-date-badge {
      font-size: 11px;
      font-weight: 700;
      color: #64748b;
      background: #f1f5f9;
      padding: 3px 10px;
      border-radius: 50px;
    }

    .record-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 10px;
      margin-bottom: 10px;
    }

    .record-item {
      display: flex;
      flex-direction: column;
    }

    .record-item-label {
      font-size: 11px;
      font-weight: 700;
      color: #94a3b8;
      text-transform: uppercase;
    }

    .record-item-value {
      font-size: 14px;
      font-weight: 800;
      color: #1e293b;
    }

    .status-badge-pill {
      padding: 4px 10px;
      border-radius: 50px;
      font-size: 11px;
      font-weight: 800;
      display: inline-flex;
      align-items: center;
      gap: 4px;
    }

    .status-on-time { background: #ecfdf5; color: #059669; }
    .status-late { background: #fffbe0; color: #d97706; }
    .status-absent { background: #fef2f2; color: #dc2626; }

    .media-btn-link {
      font-size: 11px;
      font-weight: 700;
      padding: 4px 10px;
      border-radius: 8px;
      background: #eff6ff;
      color: #0073e6;
      border: 1px solid #dbeafe;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 4px;
    }

    .btn-white-card-action {
      background-color: #ffffff !important;
      color: #1d72b8 !important;
      border: 1px solid #ffffff !important;
      border-radius: 8px !important;
      padding: 6px 16px !important;
      font-size: 13px !important;
      font-weight: 700 !important;
      display: inline-flex !important;
      align-items: center !important;
      gap: 6px !important;
      text-decoration: none !important;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08) !important;
      transition: all 0.2s ease !important;
    }

    .btn-white-card-action:hover {
      background-color: #f1f5f9 !important;
      color: #1565c0 !important;
    }

    .select-translucent-blue {
      background-color: rgba(255, 255, 255, 0.25) !important;
      color: #ffffff !important;
      border: none !important;
      border-radius: 8px !important;
      padding: 10px 16px !important;
      font-size: 14px !important;
      font-weight: 600 !important;
      width: 100% !important;
      outline: none !important;
      appearance: none !important;
      -webkit-appearance: none !important;
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23ffffff' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
      background-repeat: no-repeat !important;
      background-position: right 0.85rem center !important;
      background-size: 16px 12px !important;
      cursor: pointer !important;
    }

    .select-translucent-blue option {
      background-color: #ffffff !important;
      color: #0f172a !important;
    }
  </style>
@endsection

@section('content')
  {{-- DESKTOP WEBSITE VIEW --}}
  <div class="d-none d-md-block container-fluid p-3">
    <!-- Header Filter Card -->
    <div class="card border-0 mb-4 text-white shadow-sm" style="background: #1d72b8; border-radius: 12px; padding: 20px 24px;">
      <form action="{{ route('attendance.report') }}" method="GET" id="desktopReportForm">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div class="fw-bold fs-5 text-white">Attendance Report</div>
          <div class="d-flex align-items-center gap-2">
            @if ($isHR)
              <button type="button" class="btn-white-card-action" data-bs-toggle="modal" data-bs-target="#createAttendanceModal">
                <i class="bi bi-plus-lg"></i> Create
              </button>
            @endif
            <a href="{{ route('attendance.report') }}" class="btn-white-card-action">
              <i class="bi bi-arrow-counterclockwise"></i> Clear
            </a>
            <a href="{{ route('attendance.export.excel', request()->all()) }}" class="btn-white-card-action">
              <i class="bi bi-file-earmark-excel"></i> Export
            </a>
          </div>
        </div>

        <div class="row g-2">
          <div class="col-md-6">
            <select name="month" class="select-translucent-blue" id="desktop-month-selector" onchange="this.form.submit()">
              <option value="">All Months</option>
              @for ($i = 1; $i <= 12; $i++)
                <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                  {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                </option>
              @endfor
            </select>
          </div>

          <div class="col-md-6">
            <select name="year" class="select-translucent-blue" id="desktop-year-selector" onchange="this.form.submit()">
              @php
                $currentYear = date('Y');
                $startYear = $currentYear - 3;
              @endphp
              @for ($i = $currentYear; $i >= $startYear; $i--)
                <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>
                  {{ $i }}
                </option>
              @endfor
            </select>
          </div>
        </div>
      </form>
    </div>

    <!-- Table Card -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th class="py-3 px-4 text-muted fw-bold small">Employee Name</th>
              <th class="py-3 px-4 text-muted fw-bold small">Day</th>
              <th class="py-3 px-4 text-muted fw-bold small">Date</th>
              <th class="py-3 px-4 text-muted fw-bold small">Clock In</th>
              <th class="py-3 px-4 text-muted fw-bold small">Clock Out</th>
              <th class="py-3 px-4 text-muted fw-bold small">Status</th>
              <th class="py-3 px-4 text-muted fw-bold small text-center">Media</th>
            </tr>
          </thead>
          <tbody>
            @if (isset($formattedLogs) && count($formattedLogs) > 0)
              @foreach ($formattedLogs as $log)
                <tr>
                  <td class="py-3 px-4 fw-bold text-dark">{{ $log['employee_name'] }}</td>
                  <td class="py-3 px-4 text-muted small">{{ $log['day'] }}</td>
                  <td class="py-3 px-4 text-dark small">{{ $log['date'] }}</td>
                  <td class="py-3 px-4 text-dark fw-bold small">{{ $log['clock_in'] }}</td>
                  <td class="py-3 px-4 text-dark fw-bold small">{{ $log['clock_out'] }}</td>
                  <td class="py-3 px-4">
                    @php
                      $st = strtolower(str_replace(' ', '-', $log['status']));
                    @endphp
                    <span class="badge rounded-pill px-3 py-2 fw-semibold status-{{ $st }}" style="font-size: 11px;">
                      {{ $log['status'] }}
                    </span>
                  </td>
                  <td class="py-3 px-4 text-center">
                    @if ($log['clock_in_photo'])
                      <a href="{{ $log['clock_in_photo'] }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 me-1" style="font-size: 11px;">
                        <i class="bi bi-image me-1"></i> In
                      </a>
                    @endif
                    @if ($log['clock_out_photo'])
                      <a href="{{ $log['clock_out_photo'] }}" target="_blank" class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1 me-1" style="font-size: 11px;">
                        <i class="bi bi-image me-1"></i> Out
                      </a>
                    @endif
                  </td>
                </tr>
              @endforeach
            @else
              <tr>
                <td colspan="7" class="text-center py-5 text-muted">
                  <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                  <span>No Attendance Data Found</span>
                </td>
              </tr>
            @endif
          </tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- MOBILE VIEW --}}
  <div class="report-page-wrapper d-block d-md-none">
    <div class="report-header-banner">
      <div class="top-action-bar">
        <a href="{{ route('attendance.index') }}" class="btn-back-link">
          <i class="bi bi-arrow-left"></i>
        </a>
        <h1 class="header-page-title">Attendance Report</h1>
        <div class="dropdown">
          <button type="button" class="btn-dots-more" data-bs-toggle="dropdown" aria-expanded="false" title="More Options">
            <i class="bi bi-three-dots-vertical"></i>
          </button>
          <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-4 p-2" style="z-index: 99999;">
            <li>
              <a class="dropdown-item fw-semibold d-flex align-items-center gap-2 text-success py-2 rounded-3" href="{{ route('attendance.export.excel', request()->all()) }}">
                <i class="bi bi-file-earmark-excel fs-6"></i> Export Excel
              </a>
            </li>
            @if ($isHR)
              <li>
                <button type="button" class="dropdown-item fw-semibold d-flex align-items-center gap-2 text-primary py-2 rounded-3" data-bs-toggle="modal" data-bs-target="#createAttendanceModal">
                  <i class="bi bi-plus-circle fs-6"></i> Add Attendance
                </button>
              </li>
            @endif
          </ul>
        </div>
      </div>
    </div>

    <div class="content-body">
      <!-- Attendance Summary Header Card -->
      <div class="summary-card-att mb-3">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <span class="summary-label">Attendance Records</span>
            <h4 class="summary-value mb-0">{{ isset($formattedLogs) ? count($formattedLogs) : 0 }} <small style="font-size: 13px; font-weight: 600; color: #64748b;">Logs</small></h4>
          </div>
          @if ($isHR)
            <div class="d-flex align-items-center gap-2">
              <button type="button" class="btn-summary-action btn-primary-action" data-bs-toggle="modal" data-bs-target="#createAttendanceModal">
                <i class="bi bi-plus-lg me-1"></i> Add Record
              </button>
            </div>
          @endif
        </div>
      </div>

      <!-- Filter Card -->
      <div class="filter-card">
        <div class="filter-card-title">
          <span><i class="bi bi-funnel-fill text-primary me-1"></i> Attendance Filter</span>
          <a href="{{ route('attendance.report') }}" class="text-decoration-none text-muted fw-bold" style="font-size: 11.5px;">
            <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
          </a>
        </div>

        <form action="{{ route('attendance.report') }}" method="GET" id="reportForm">
          <div class="row g-2">
            <div class="col-6">
              <select name="month" class="filter-select-custom" id="month-selector" onchange="this.form.submit()">
                <option value="">All Months</option>
                @for ($i = 1; $i <= 12; $i++)
                  <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                    {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                  </option>
                @endfor
              </select>
            </div>

            <div class="col-6">
              <select name="year" class="filter-select-custom" id="year-selector" onchange="this.form.submit()">
                @php
                  $currentYear = date('Y');
                  $startYear = $currentYear - 3;
                @endphp
                @for ($i = $currentYear; $i >= $startYear; $i--)
                  <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>
                    {{ $i }}
                  </option>
                @endfor
              </select>
            </div>

            @if ($isHR)
              <div class="col-12">
                <select name="org_id" class="filter-select-custom" id="org-selector" onchange="this.form.submit()">
                  <option value="">All Organizations</option>
                  @foreach ($orgTree as $org)
                    <option value="{{ $org['id'] }}" {{ (string) $selectedOrgId === (string) $org['id'] ? 'selected' : '' }}>
                      {{ $org['name'] }}
                    </option>
                  @endforeach
                </select>
              </div>

              <div class="col-12">
                <select name="employee_id" class="filter-select-custom" id="employee-selector" onchange="this.form.submit()">
                  <option value="">All Employees</option>
                  @foreach ($employeesList as $emp)
                    <option value="{{ $emp->id }}" {{ (string) $selectedEmployeeId === (string) $emp->id ? 'selected' : '' }}>
                      {{ $emp->fullname }}
                    </option>
                  @endforeach
                </select>
              </div>
            @endif
          </div>
        </form>
      </div>

      <!-- Record Cards List -->
      <div id="recordsContainer">
        @if (isset($formattedLogs) && count($formattedLogs) > 0)
          @foreach ($formattedLogs as $log)
            <div class="record-card">
              <div class="record-card-header">
                <div>
                  <h3 class="record-emp-name">{{ $log['employee_name'] }}</h3>
                  <span class="record-date-badge"><i class="bi bi-calendar3 me-1"></i> {{ $log['day'] }}, {{ $log['date'] }}</span>
                </div>

                @php
                  $st = strtolower(str_replace(' ', '-', $log['status']));
                @endphp
                <span class="status-badge-pill status-{{ $st }}">
                  <i class="bi bi-clock-history"></i> {{ $log['status'] }}
                </span>
              </div>

              <div class="record-grid">
                <div class="record-item">
                  <span class="record-item-label">CLOCK IN</span>
                  <span class="record-item-value text-success">{{ $log['clock_in'] }}</span>
                </div>
                <div class="record-item">
                  <span class="record-item-label">CLOCK OUT</span>
                  <span class="record-item-value text-danger">{{ $log['clock_out'] }}</span>
                </div>
              </div>

              <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                <div class="d-flex gap-2">
                  @if ($log['clock_in_photo'])
                    <a href="{{ $log['clock_in_photo'] }}" target="_blank" class="media-btn-link">
                      <i class="bi bi-image"></i> In Photo
                    </a>
                  @endif
                  @if ($log['clock_out_photo'])
                    <a href="{{ $log['clock_out_photo'] }}" target="_blank" class="media-btn-link">
                      <i class="bi bi-image"></i> Out Photo
                    </a>
                  @endif
                </div>

                @if ($isHR)
                  <button class="edit-btn-icon edit-attendance-btn" title="Edit Attendance" data-id="{{ $log['id'] }}"
                    data-name="{{ $log['employee_name'] }}" data-date="{{ $log['date'] }}"
                    data-clock-in="{{ $log['raw_clock_in'] }}" data-clock-out="{{ $log['raw_clock_out'] }}">
                    <i class="bi bi-pencil-square"></i>
                  </button>
                @endif
              </div>
            </div>
          @endforeach
        @else
          <div class="record-card text-center py-4">
            <i class="bi bi-calendar-x text-muted" style="font-size: 36px;"></i>
            <h5 class="fw-bold text-dark mt-2 mb-1">No Attendance Data</h5>
            <p class="small text-muted mb-0">No attendance records found for the selected period</p>
          </div>
        @endif
      </div>
    </div>
  </div>

  <!-- Modal Edit Attendance (HR) -->
  <div class="modal fade" id="editAttendanceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
        <div class="modal-header border-0 pb-0">
          <h5 class="modal-title fw-bold">Edit Attendance</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="editAttendanceForm">
          @csrf
          <input type="hidden" name="id" id="edit-id">
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label small fw-bold text-muted">Employee</label>
              <input type="text" class="form-control-plaintext fw-bold" id="edit-name" readonly>
            </div>
            <div class="mb-3">
              <label class="form-label small fw-bold text-muted">Date</label>
              <input type="text" class="form-control-plaintext text-muted" id="edit-date" readonly>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="mb-3">
                  <label class="form-label small fw-bold text-muted">Clock In Time</label>
                  <input type="time" class="form-control" name="clock_in" id="edit-clock-in" style="border-radius: 10px;">
                </div>
              </div>
              <div class="col-6">
                <div class="mb-3">
                  <label class="form-label small fw-bold text-muted">Clock Out Time</label>
                  <input type="time" class="form-control" name="clock_out" id="edit-clock-out" style="border-radius: 10px;">
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer border-0">
            <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary fw-bold">Save</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  @if ($isHR)
    <!-- Modal Create Attendance (HR) -->
    <div class="modal fade" id="createAttendanceModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
          <div class="modal-header border-0 pb-0">
            <h5 class="modal-title fw-bold">Add Attendance Record</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="createAttendanceForm">
            @csrf
            <div class="modal-body">
              <div class="mb-3">
                <label class="form-label small fw-bold text-muted">Select Employee <span class="text-danger">*</span></label>
                <select name="employee_id" id="create-employee-id" class="form-select" style="border-radius: 10px;" required>
                  <option value="">-- Select Employee --</option>
                  @foreach ($employeesList as $emp)
                    <option value="{{ $emp->id }}">{{ $emp->fullname }}</option>
                  @endforeach
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label small fw-bold text-muted">Date <span class="text-danger">*</span></label>
                <input type="date" class="form-control" name="date" id="create-date" value="{{ date('Y-m-d') }}" style="border-radius: 10px;" required>
              </div>
              <div class="row">
                <div class="col-6">
                  <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Clock In Time</label>
                    <input type="time" class="form-control" name="clock_in" id="create-clock-in" style="border-radius: 10px;">
                  </div>
                </div>
                <div class="col-6">
                  <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Clock Out Time</label>
                    <input type="time" class="form-control" name="clock_out" id="create-clock-out" style="border-radius: 10px;">
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer border-0">
              <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary fw-bold">Save</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endif
@endsection

@section('scripts')
  <script>
    $(document).ready(function() {
      $('#month-selector, #year-selector, #org-selector, #employee-selector').change(function() {
        $('#reportForm').submit();
      });

      $('.edit-attendance-btn').click(function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const date = $(this).data('date');
        const clockIn = $(this).data('clock-in');
        const clockOut = $(this).data('clock-out');

        $('#edit-id').val(id);
        $('#edit-name').val(name);
        $('#edit-date').val(date);
        $('#edit-clock-in').val(clockIn);
        $('#edit-clock-out').val(clockOut);

        $('#editAttendanceModal').modal('show');
      });

      $('#editAttendanceForm').submit(function(e) {
        e.preventDefault();
        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true).text('Saving...');

        $.ajax({
          url: '{{ route('attendance.update') }}',
          type: 'POST',
          data: $(this).serialize(),
          success: function(response) {
            if (response.success) {
              window.location.reload();
            } else {
              showAlert('danger', response.message || 'Failed to update attendance');
              submitBtn.prop('disabled', false).text('Save');
            }
          },
          error: function(xhr) {
            showAlert('danger', xhr.responseJSON?.message || 'Error updating attendance');
            submitBtn.prop('disabled', false).text('Save');
          }
        });
      });

      $('#createAttendanceForm').submit(function(e) {
        e.preventDefault();
        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true).text('Saving...');

        $.ajax({
          url: '{{ route('attendance.store') }}',
          type: 'POST',
          data: $(this).serialize(),
          success: function(response) {
            if (response.success) {
              window.location.reload();
            } else {
              showAlert('danger', response.message || 'Failed to create attendance');
              submitBtn.prop('disabled', false).text('Save');
            }
          },
          error: function(xhr) {
            showAlert('danger', xhr.responseJSON?.message || 'Error creating attendance');
            submitBtn.prop('disabled', false).text('Save');
          }
        });
      });
    });
  </script>
@endsection
