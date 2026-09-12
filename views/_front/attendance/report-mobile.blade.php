@extends('templates.mobile')

@section('head')
  <style>
    html,
    body {
      height: 100%;
      margin: 0;
    }

    .report-page-wrapper {
      display: flex;
      flex-direction: column;
      height: 100vh;
      box-sizing: border-box;
      padding-bottom: 15px;
    }

    .header-section {
      background-color: var(--primary-color);
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 15px;
      color: white;
      flex-shrink: 0;
    }

    .table-card {
      flex: 1;
      display: flex;
      flex-direction: column;
      min-height: 0;
      border-radius: 12px;
      overflow: hidden;
      background: white;
      margin-bottom: 0 !important;
    }

    .table-card .table-responsive {
      flex: 1;
      height: 100%;
      max-height: 100% !important;
      overflow-y: auto;
    }

    .date-selectors {
      display: flex;
      gap: 10px;
      margin-bottom: 15px;
    }

    .date-selector {
      background-color: rgba(255, 255, 255, 0.2);
      border-radius: 8px;
      padding: 10px 15px;
      color: white;
      border: none;
      flex: 1;
      appearance: none;
      -webkit-appearance: none;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 16 16'%3E%3Cpath fill='white' d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: calc(100% - 10px) center;
      padding-right: 30px;
    }

    .date-selector option {
      background-color: var(--primary-color);
      color: white;
    }

    .header-actions {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
    }

    .export-btn {
      background-color: rgba(255, 255, 255, 0.25);
      border: none;
      border-radius: 8px;
      padding: 8px 12px;
      color: white;
      font-size: 13px;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 5px;
      text-decoration: none;
    }

    .export-btn:hover {
      background-color: rgba(255, 255, 255, 0.35);
      color: white;
    }

    .log-status {
      padding: 4px 8px;
      border-radius: 12px;
      font-size: 12px;
      font-weight: 600;
      display: inline-block;
      text-align: center;
    }

    .status-on-time {
      background-color: #d1fae5;
      color: #047857;
    }

    .status-late {
      background-color: #ffedd5;
      color: #c2410c;
    }

    .status-absent {
      background-color: #fee2e2;
      color: #b91c1c;
    }
  </style>
@endsection

@section('content')
  <div class="px-3 report-page-wrapper">
    @if (isMobile())
      <div class="py-3" style="flex-shrink: 0;">
        <div class="d-flex align-items-center gap-4" style="cursor: pointer;"
          onclick="window.location.href='{{ route('attendance.index') }}'">
          <i class="bi bi-chevron-left" style="font-size: 1.2rem;"></i>
          <p class="m-0" style="font-size: 1.1rem;">Attendance Report</p>
        </div>
      </div>
    @endif

    <div class="header-section">
      <form action="{{ route('attendance.report') }}" method="GET" id="reportForm">
        <div class="header-actions">
          <div style="font-weight: 600; font-size: 16px;">Attendance Report</div>
          <div class="d-flex align-items-center gap-2">
            @if ($isHR)
              <button type="button" class="export-btn" data-bs-toggle="modal" data-bs-target="#createAttendanceModal"
                style="cursor: pointer; border: none;">
                <i class="bi bi-plus-lg"></i> Create
              </button>
            @endif
            <a href="{{ route('attendance.report') }}" class="export-btn" title="Reset Filters">
              <i class="bi bi-arrow-counterclockwise"></i> Clear
            </a>
            <a href="{{ route('attendance.export.excel', request()->all()) }}" class="export-btn">
              <i class="bi bi-download"></i> Export
            </a>
          </div>
        </div>

        @if ($isHR)
          <div class="row g-2 mb-2">
            <div class="col-6 col-md-2">
              <select name="month" class="date-selector w-100" id="month-selector">
                <option value="">Month</option>
                @for ($i = 1; $i <= 12; $i++)
                  <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                    {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                  </option>
                @endfor
              </select>
            </div>

            <div class="col-6 col-md-2">
              <select name="year" class="date-selector w-100" id="year-selector">
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

            <div class="col-6 col-md-2">
              <input type="date" name="start_date" id="start_date" class="date-selector w-100"
                value="{{ $startDate }}" style="background-image: none; color-scheme: dark;" title="Start Date">
            </div>

            <div class="col-6 col-md-2">
              <input type="date" name="end_date" id="end_date" class="date-selector w-100"
                value="{{ $endDate }}" style="background-image: none; color-scheme: dark;" title="End Date">
            </div>

            <div class="col-12 col-md-2">
              <select name="org_id" class="date-selector w-100" id="org-selector">
                <option value="">All Organizations</option>
                @foreach ($orgTree as $org)
                  <option value="{{ $org['id'] }}"
                    {{ (string) $selectedOrgId === (string) $org['id'] ? 'selected' : '' }}>
                    {{ $org['name'] }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="col-12 col-md-2">
              <select name="employee_id" class="date-selector w-100" id="employee-selector">
                <option value="">All Employees</option>
                @foreach ($employeesList as $emp)
                  <option value="{{ $emp->id }}"
                    {{ (string) $selectedEmployeeId === (string) $emp->id ? 'selected' : '' }}>
                    {{ $emp->fullname }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>
        @else
          <div class="date-selectors">
            <select name="month" class="date-selector" id="month-selector">
              @for ($i = 1; $i <= 12; $i++)
                <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>
                  {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                </option>
              @endfor
            </select>

            <select name="year" class="date-selector" id="year-selector">
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
        @endif
      </form>
    </div>

    <div class="card shadow-sm border-0 table-card">
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="width: 100%;">
          <thead style="position: sticky; top: 0; z-index: 10; background-color: #f8f9fa;">
            <tr>
              <th
                style="padding: 15px 20px; font-weight: 600; color: #495057; border-bottom: 2px solid #dee2e6; background-color: #f8f9fa;">
                Employee Name</th>
              <th
                style="padding: 15px 20px; font-weight: 600; color: #495057; text-align: center; border-bottom: 2px solid #dee2e6; background-color: #f8f9fa;">
                Day</th>
              <th
                style="padding: 15px 20px; font-weight: 600; color: #495057; text-align: center; border-bottom: 2px solid #dee2e6; background-color: #f8f9fa;">
                Date</th>
              <th
                style="padding: 15px 20px; font-weight: 600; color: #495057; text-align: center; border-bottom: 2px solid #dee2e6; background-color: #f8f9fa;">
                Clock In</th>
              <th
                style="padding: 15px 20px; font-weight: 600; color: #495057; text-align: center; border-bottom: 2px solid #dee2e6; background-color: #f8f9fa;">
                Clock Out</th>
              <th
                style="padding: 15px 20px; font-weight: 600; color: #495057; text-align: center; border-bottom: 2px solid #dee2e6; background-color: #f8f9fa;">
                Status</th>
              <th
                style="padding: 15px 20px; font-weight: 600; color: #495057; text-align: center; border-bottom: 2px solid #dee2e6; background-color: #f8f9fa;">
                Media</th>
              @if ($isHR)
                <th
                  style="padding: 15px 20px; font-weight: 600; color: #495057; text-align: center; border-bottom: 2px solid #dee2e6; background-color: #f8f9fa;">
                  Action</th>
              @endif
            </tr>
          </thead>
          <tbody>
            @if (isset($formattedLogs) && count($formattedLogs) > 0)
              @foreach ($formattedLogs as $log)
                <tr style="border-bottom: 1px solid #e9ecef;">
                  <td style="padding: 15px 20px; font-weight: 500; color: #212529;">{{ $log['employee_name'] }}</td>
                  <td style="padding: 15px 20px; text-align: center; color: #6c757d;">{{ $log['day'] }}</td>
                  <td style="padding: 15px 20px; text-align: center; color: #6c757d;">{{ $log['date'] }}</td>
                  <td style="padding: 15px 20px; text-align: center; color: #212529;">{{ $log['clock_in'] }}</td>
                  <td style="padding: 15px 20px; text-align: center; color: #212529;">{{ $log['clock_out'] }}</td>
                  <td style="padding: 15px 20px; text-align: center;">
                    <span class="log-status status-{{ strtolower(str_replace(' ', '-', $log['status'])) }}"
                      style="width: 100px;">
                      {{ $log['status'] }}
                    </span>
                  </td>
                  <td style="padding: 15px 20px; text-align: center;">
                    <div class="d-inline-flex align-items-center gap-2">
                      @if ($log['clock_in_photo'])
                        <a href="{{ $log['clock_in_photo'] }}" target="_blank"
                          class="btn btn-sm btn-outline-primary py-1 px-2 d-inline-flex align-items-center gap-1"
                          style="font-size:12px; border-radius:6px;" title="Clock In Photo">
                          <i class="bi bi-image"></i>
                          In
                        </a>
                      @endif

                      @if ($log['clock_out_photo'])
                        <a href="{{ $log['clock_out_photo'] }}" target="_blank"
                          class="btn btn-sm btn-outline-primary py-1 px-2 d-inline-flex align-items-center gap-1"
                          style="font-size:12px; border-radius:6px;" title="Clock Out Photo">
                          <i class="bi bi-image"></i>
                          Out
                        </a>
                      @endif

                      @if (!$log['clock_in_photo'] && !$log['clock_out_photo'])
                        <span class="text-muted">-</span>
                      @endif
                    </div>
                  </td>
                  @if ($isHR)
                    <td style="padding: 12px; text-align: center;">
                      <button class="edit-attendance-btn" title="Edit Attendance" data-id="{{ $log['id'] }}"
                        data-name="{{ $log['employee_name'] }}" data-date="{{ $log['date'] }}"
                        data-clock-in="{{ $log['raw_clock_in'] }}" data-clock-out="{{ $log['raw_clock_out'] }}"
                        style="width:36px; height:36px; border:none; border-radius:10px; background:var(--primary-color); color:#fff; display:inline-flex; align-items:center; justify-content:center; cursor:pointer; transition:background-color .2s ease, box-shadow .2s ease; box-shadow:0 2px 8px rgba(37,99,235,.18);"
                        onmouseover="this.style.background='#1d4ed8';this.style.boxShadow='0 6px 16px rgba(37,99,235,.35)';"
                        onmouseout="this.style.background='var(--primary-color)';this.style.boxShadow='0 2px 8px rgba(37,99,235,.18)';">
                        <i class="bi bi-pencil-square" style="font-size:14px;"></i>
                      </button>
                    </td>
                  @endif
                </tr>
              @endforeach
            @else
              <tr>
                <td colspan="{{ $isHR ? 8 : 7 }}" style="padding: 40px; text-align: center; color: #6c757d;">
                  <div class="empty-icon" style="font-size: 3rem; margin-bottom: 10px; color: #dee2e6;">
                    <i class="bi bi-calendar-x"></i>
                  </div>
                  <div>No attendance records found for this period</div>
                </td>
              </tr>
            @endif
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="modal fade" id="editAttendanceModal" tabindex="-1" aria-labelledby="editAttendanceModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
        <div class="modal-header"
          style="border-bottom: 1px solid #e9ecef; background-color: #f8f9fa; border-top-left-radius: 12px; border-top-right-radius: 12px;">
          <h5 class="modal-title" id="editAttendanceModalLabel" style="font-weight: 600; color: #212529;">Edit
            Attendance</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="editAttendanceForm">
          @csrf
          <input type="hidden" name="id" id="edit-id">
          <div class="modal-body" style="padding: 20px;">
            <div class="mb-3">
              <label class="form-label" style="font-weight: 500; color: #495057;">Employee</label>
              <input type="text" class="form-control-plaintext" id="edit-name" readonly
                style="font-weight: 600; padding: 0;">
            </div>
            <div class="mb-3">
              <label class="form-label" style="font-weight: 500; color: #495057;">Date</label>
              <input type="text" class="form-control-plaintext" id="edit-date" readonly
                style="color: #6c757d; padding: 0;">
            </div>
            <div class="row">
              <div class="col-6">
                <div class="mb-3">
                  <label for="edit-clock-in" class="form-label" style="font-weight: 500; color: #495057;">Clock In
                    Time</label>
                  <input type="time" class="form-control" name="clock_in" id="edit-clock-in"
                    style="border-radius: 8px;">
                </div>
              </div>
              <div class="col-6">
                <div class="mb-3">
                  <label for="edit-clock-out" class="form-label" style="font-weight: 500; color: #495057;">Clock Out
                    Time</label>
                  <input type="time" class="form-control" name="clock_out" id="edit-clock-out"
                    style="border-radius: 8px;">
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer"
            style="border-top: 1px solid #e9ecef; padding: 15px 20px; display: flex !important; flex-direction: row !important; justify-content: flex-end !important; gap: 10px !important; align-items: center !important;">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"
              style="border-radius: 8px !important; font-weight: 500 !important; border: 1px solid #ced4da !important; color: #495057 !important; background: transparent !important; transition: all 0.2s ease-in-out !important; padding: 8px 20px !important; width: auto !important; margin: 0 !important;"
              onmouseover="this.style.backgroundColor='#f8f9fa'; this.style.color='#212529'; this.style.borderColor='#babbbc';"
              onmouseout="this.style.backgroundColor='transparent'; this.style.color='#495057'; this.style.borderColor='#ced4da';">
              Cancel
            </button>
            <button type="submit" class="btn btn-primary"
              style="border-radius: 8px !important; font-weight: 500 !important; background-color: var(--primary-color) !important; border-color: var(--primary-color) !important; padding: 8px 20px !important; width: auto !important; margin: 0 !important;">Save
              Changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  @if ($isHR)
    <div class="modal fade" id="createAttendanceModal" tabindex="-1" aria-labelledby="createAttendanceModalLabel"
      aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
          <div class="modal-header"
            style="border-bottom: 1px solid #e9ecef; background-color: #f8f9fa; border-top-left-radius: 12px; border-top-right-radius: 12px;">
            <h5 class="modal-title" id="createAttendanceModalLabel" style="font-weight: 600; color: #212529;">Create
              Attendance</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="createAttendanceForm">
            @csrf
            <div class="modal-body" style="padding: 20px;">
              <div class="mb-3">
                <label for="create-employee-id" class="form-label" style="font-weight: 500; color: #495057;">Employee
                  <span class="text-danger">*</span></label>
                <select name="employee_id" id="create-employee-id" class="form-select" style="border-radius: 8px;"
                  required>
                  <option value="">-- Select Employee --</option>
                  @foreach ($employeesList as $emp)
                    <option value="{{ $emp->id }}">{{ $emp->fullname }}</option>
                  @endforeach
                </select>
              </div>
              <div class="mb-3">
                <label for="create-date" class="form-label" style="font-weight: 500; color: #495057;">Date <span
                    class="text-danger">*</span></label>
                <input type="date" class="form-control" name="date" id="create-date"
                  value="{{ date('Y-m-d') }}" style="border-radius: 8px;" required>
              </div>
              <div class="row">
                <div class="col-6">
                  <div class="mb-3">
                    <label for="create-clock-in" class="form-label" style="font-weight: 500; color: #495057;">Clock In
                      Time</label>
                    <input type="time" class="form-control" name="clock_in" id="create-clock-in"
                      style="border-radius: 8px;">
                  </div>
                </div>
                <div class="col-6">
                  <div class="mb-3">
                    <label for="create-clock-out" class="form-label" style="font-weight: 500; color: #495057;">Clock
                      Out Time</label>
                    <input type="time" class="form-control" name="clock_out" id="create-clock-out"
                      style="border-radius: 8px;">
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer"
              style="border-top: 1px solid #e9ecef; padding: 15px 20px; display: flex !important; flex-direction: row !important; justify-content: flex-end !important; gap: 10px !important; align-items: center !important;">
              <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                style="border-radius: 8px !important; font-weight: 500 !important; border: 1px solid #ced4da !important; color: #495057 !important; background: transparent !important; transition: all 0.2s ease-in-out !important; padding: 8px 20px !important; width: auto !important; margin: 0 !important;"
                onmouseover="this.style.backgroundColor='#f8f9fa'; this.style.color='#212529'; this.style.borderColor='#babbbc';"
                onmouseout="this.style.backgroundColor='transparent'; this.style.color='#495057'; this.style.borderColor='#ced4da';">
                Cancel
              </button>
              <button type="submit" class="btn btn-primary"
                style="border-radius: 8px !important; font-weight: 500 !important; background-color: var(--primary-color) !important; border-color: var(--primary-color) !important; padding: 8px 20px !important; width: auto !important; margin: 0 !important;">Save</button>
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
      $('#month-selector, #year-selector').change(function() {
        const month = $('#month-selector').val();
        const year = $('#year-selector').val();
        if (month && year) {
          const startDate = `${year}-${String(month).padStart(2, '0')}-01`;
          const lastDay = new Date(year, month, 0).getDate();
          const endDate = `${year}-${String(month).padStart(2, '0')}-${String(lastDay).padStart(2, '0')}`;
          $('#start_date').val(startDate);
          $('#end_date').val(endDate);
        }
        $('#reportForm').submit();
      });

      $('#org-selector, #employee-selector, #start_date, #end_date').change(function() {
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
              alert(response.message || 'Failed to update attendance');
              submitBtn.prop('disabled', false).text('Save Changes');
            }
          },
          error: function(xhr) {
            const errorMsg = xhr.responseJSON ? xhr.responseJSON.message : 'Error updating attendance';
            alert(errorMsg);
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
            const errorMsg = xhr.responseJSON ? xhr.responseJSON.message : 'Error creating attendance';
            alert(errorMsg);
            submitBtn.prop('disabled', false).text('Save');
          }
        });
      });
    });
  </script>
@endsection
