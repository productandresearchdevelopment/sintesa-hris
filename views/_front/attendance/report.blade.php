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
      border-radius: 8px;
      border: 1px solid #e2e8f0;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
    }
  </style>
@endsection

@section('body')
  <div class="bg-white main-container">
    <!-- Clean Page Header Bar -->
    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
      <div>
        <h5 class="fw-bold text-dark mb-0">Employee Attendance Report</h5>
        <p class="text-muted small mb-0" style="font-size: 12px;">Summary and historical records of employee attendance</p>
      </div>

      <div class="d-flex align-items-center gap-2">
        @if ($isHR)
          <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createAttendanceModal">
            <i class="bi bi-plus-lg me-1"></i> Add Attendance
          </button>
        @endif
        <a href="{{ route('attendance.report') }}" class="btn btn-outline-secondary btn-sm">
          <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
        </a>
        <a href="{{ route('attendance.export.excel', request()->all()) }}" class="btn btn-success btn-sm">
          <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
        </a>
      </div>
    </div>

    <!-- Filter Card Toolbar (English) -->
    <div class="card shadow-sm border mb-3">
      <div class="card-body p-3">
        <form action="{{ route('attendance.report') }}" method="GET" id="desktopReportForm">
          <div class="row g-2 align-items-center">
            <div class="col-md-3">
              <label for="desktop-month-selector" class="form-label small text-muted mb-1" style="font-size: 11.5px;">Month:</label>
              <select name="month" class="form-select form-select-sm" id="desktop-month-selector" onchange="this.form.submit()">
                <option value="">All Months</option>
                @for ($i = 1; $i <= 12; $i++)
                  <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                    {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                  </option>
                @endfor
              </select>
            </div>

            <div class="col-md-3">
              <label for="desktop-year-selector" class="form-label small text-muted mb-1" style="font-size: 11.5px;">Year:</label>
              <select name="year" class="form-select form-select-sm" id="desktop-year-selector" onchange="this.form.submit()">
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

            @if (isset($orgTree) && count($orgTree) > 0)
              <div class="col-md-3">
                <label for="org-selector" class="form-label small text-muted mb-1" style="font-size: 11.5px;">Organization:</label>
                <select name="org_id" class="form-select form-select-sm" id="org-selector" onchange="this.form.submit()">
                  <option value="">All Organizations</option>
                  @foreach ($orgTree as $org)
                    <option value="{{ $org['id'] }}" {{ $selectedOrgId == $org['id'] ? 'selected' : '' }}>
                      {{ $org['name'] }}
                    </option>
                  @endforeach
                </select>
              </div>
            @endif

            <div class="col-md-3">
              <label for="emp-name-input" class="form-label small text-muted mb-1" style="font-size: 11.5px;">Search Employee:</label>
              <div class="input-group input-group-sm">
                <input type="text" name="employee_name" id="emp-name-input" class="form-control form-control-sm" placeholder="Employee name..." value="{{ $employeeNameSearch ?? '' }}">
                <button type="submit" class="btn btn-outline-secondary btn-sm"><i class="bi bi-search"></i></button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Data Table Card (English) -->
    <div class="card shadow-sm border mb-3">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th class="py-2 px-3 text-muted small" style="font-size: 12px;">Employee Name</th>
              <th class="py-2 px-3 text-muted small" style="font-size: 12px;">Day</th>
              <th class="py-2 px-3 text-muted small" style="font-size: 12px;">Date</th>
              <th class="py-2 px-3 text-muted small" style="font-size: 12px;">Clock In</th>
              <th class="py-2 px-3 text-muted small" style="font-size: 12px;">Clock Out</th>
              <th class="py-2 px-3 text-muted small" style="font-size: 12px;">Status</th>
              <th class="py-2 px-3 text-muted small text-center" style="font-size: 12px;">Photos & Actions</th>
            </tr>
          </thead>
          <tbody>
            @if (isset($formattedLogs) && count($formattedLogs) > 0)
              @foreach ($formattedLogs as $log)
                <tr>
                  <td class="py-2 px-3 fw-bold text-dark" style="font-size: 12.5px;">{{ $log['employee_name'] }}</td>
                  <td class="py-2 px-3 text-muted small" style="font-size: 12px;">{{ $log['day'] }}</td>
                  <td class="py-2 px-3 text-dark small" style="font-size: 12px;">{{ $log['date'] }}</td>
                  <td class="py-2 px-3 text-dark fw-bold small" style="font-size: 12px;">{{ $log['clock_in'] }}</td>
                  <td class="py-2 px-3 text-dark fw-bold small" style="font-size: 12px;">{{ $log['clock_out'] }}</td>
                  <td class="py-2 px-3">
                    @php
                      $st = strtolower(str_replace(' ', '-', $log['status']));
                      $badgeBg = 'bg-secondary';
                      if ($st == 'on-time') $badgeBg = 'bg-success';
                      elseif ($st == 'late') $badgeBg = 'bg-warning text-dark';
                      elseif ($st == 'absent') $badgeBg = 'bg-danger';
                    @endphp
                    <span class="badge {{ $badgeBg }}" style="font-size: 11px;">
                      {{ $log['status'] }}
                    </span>
                  </td>
                  <td class="py-2 px-3 text-center">
                    <div class="d-inline-flex align-items-center gap-1">
                      @if ($log['clock_in_photo'])
                        <a href="{{ $log['clock_in_photo'] }}" target="_blank" class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size: 11px;">
                          <i class="bi bi-image me-1"></i> In Photo
                        </a>
                      @endif
                      @if ($log['clock_out_photo'])
                        <a href="{{ $log['clock_out_photo'] }}" target="_blank" class="btn btn-sm btn-outline-danger py-0 px-2" style="font-size: 11px;">
                          <i class="bi bi-image me-1"></i> Out Photo
                        </a>
                      @endif
                      @if ($isHR)
                        <button type="button" class="btn btn-sm btn-outline-secondary py-0 px-2 edit-attendance-btn" style="font-size: 11px;"
                          data-id="{{ $log['id'] }}"
                          data-name="{{ $log['employee_name'] }}"
                          data-date="{{ $log['date'] }}"
                          data-clock-in="{{ $log['raw_clock_in'] }}"
                          data-clock-out="{{ $log['raw_clock_out'] }}">
                          <i class="bi bi-pencil"></i> Edit
                        </button>
                      @endif
                    </div>
                  </td>
                </tr>
              @endforeach
            @else
              <tr>
                <td colspan="7" class="text-center py-4 text-muted small">
                  <i class="bi bi-inbox fs-3 d-block mb-1"></i>
                  <span>No attendance records found</span>
                </td>
              </tr>
            @endif
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Modal Edit Attendance (HR - English) -->
  <div class="modal fade" id="editAttendanceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow">
        <div class="modal-header border-bottom py-2">
          <h6 class="modal-title fw-bold">Edit Attendance Record</h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="editAttendanceForm">
          @csrf
          <input type="hidden" name="id" id="edit-id">
          <div class="modal-body p-3">
            <div class="mb-2">
              <label class="form-label small fw-bold text-muted mb-0">Employee Name</label>
              <input type="text" class="form-control-plaintext fw-bold py-0" id="edit-name" readonly>
            </div>
            <div class="mb-3">
              <label class="form-label small fw-bold text-muted mb-0">Date</label>
              <input type="text" class="form-control-plaintext text-muted py-0" id="edit-date" readonly>
            </div>
            <div class="row g-2">
              <div class="col-6">
                <label class="form-label small fw-bold text-muted">Clock In Time</label>
                <input type="time" class="form-control form-control-sm" name="clock_in" id="edit-clock-in">
              </div>
              <div class="col-6">
                <label class="form-label small fw-bold text-muted">Clock Out Time</label>
                <input type="time" class="form-control form-control-sm" name="clock_out" id="edit-clock-out">
              </div>
            </div>
          </div>
          <div class="modal-footer border-top py-2">
            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary btn-sm">Save Changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  @if ($isHR)
    <!-- Modal Create Attendance (HR - English) -->
    <div class="modal fade" id="createAttendanceModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
          <div class="modal-header border-bottom py-2">
            <h6 class="modal-title fw-bold">Add Attendance Record</h6>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="createAttendanceForm">
            @csrf
            <div class="modal-body p-3">
              <div class="mb-3">
                <label class="form-label small fw-bold text-muted">Select Employee <span class="text-danger">*</span></label>
                <select name="employee_id" id="create-employee-id" class="form-select form-select-sm" required>
                  <option value="">-- Select Employee --</option>
                  @foreach ($employeesList as $emp)
                    <option value="{{ $emp->id }}">{{ $emp->fullname }}</option>
                  @endforeach
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label small fw-bold text-muted">Date <span class="text-danger">*</span></label>
                <input type="date" class="form-control form-control-sm" name="date" id="create-date" value="{{ date('Y-m-d') }}" required>
              </div>
              <div class="row g-2">
                <div class="col-6">
                  <label class="form-label small fw-bold text-muted">Clock In Time</label>
                  <input type="time" class="form-control form-control-sm" name="clock_in" id="create-clock-in">
                </div>
                <div class="col-6">
                  <label class="form-label small fw-bold text-muted">Clock Out Time</label>
                  <input type="time" class="form-control form-control-sm" name="clock_out" id="create-clock-out">
                </div>
              </div>
            </div>
            <div class="modal-footer border-top py-2">
              <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary btn-sm">Save</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  @endif

  <script>
    document.addEventListener('DOMContentLoaded', function() {
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
              alert(response.message || 'Failed to update attendance');
              submitBtn.prop('disabled', false).text('Save Changes');
            }
          },
          error: function(xhr) {
            alert(xhr.responseJSON?.message || 'Error updating attendance');
            submitBtn.prop('disabled', false).text('Save Changes');
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
              alert(response.message || 'Failed to create attendance');
              submitBtn.prop('disabled', false).text('Save');
            }
          },
          error: function(xhr) {
            alert(xhr.responseJSON?.message || 'Error creating attendance');
            submitBtn.prop('disabled', false).text('Save');
          }
        });
      });
    });
  </script>
@endsection
