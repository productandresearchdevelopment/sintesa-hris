@extends('templates.mobile')

@section('head')
  <style>
    html, body {
      background-color: #ffffff !important;
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    .leave-page-wrapper {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      padding-bottom: 40px;
    }

    .leave-header-banner {
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

    .header-page-title {
      font-size: 17px;
      font-weight: 700;
      letter-spacing: -0.3px;
      margin: 0;
      color: #ffffff;
    }

    .btn-header-add {
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

    .btn-header-add:active {
      transform: scale(0.92);
      background: rgba(255, 255, 255, 0.3);
    }

    .content-body {
      padding: 0 16px;
      margin-top: -24px;
      z-index: 10;
      position: relative;
    }

    @media (min-width: 769px) {
      .leave-header-banner {
        display: none !important;
      }
      .leave-page-wrapper {
        padding: 20px 24px;
        max-width: 1200px;
        margin: 0 auto;
      }
      .content-body {
        margin-top: 0 !important;
        padding: 0 !important;
      }
    }

    /* Summary Card Styling */
    .leave-summary-card {
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
      font-size: 24px;
      font-weight: 900;
      color: #0f172a;
      line-height: 1.1;
    }

    .btn-summary-action {
      font-size: 13.5px;
      font-weight: 800;
      padding: 10px 20px;
      border-radius: 50px;
      border: none;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      transition: all 0.2s ease;
      cursor: pointer;
    }

    .btn-primary-action {
      background: linear-gradient(135deg, #0073e6 0%, #005bb5 100%);
      color: #ffffff;
      box-shadow: 0 4px 14px rgba(0, 115, 230, 0.35);
    }

    .btn-primary-action:active {
      transform: scale(0.95);
    }

    .btn-dots-more {
      width: 42px;
      height: 42px;
      border-radius: 50%;
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      color: #64748b;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      transition: all 0.2s ease;
      cursor: pointer;
    }

    .btn-dots-more:active {
      transform: scale(0.92);
      background: #e2e8f0;
    }

    /* Search & Filter Card Styling */
    .search-filter-card {
      background: #ffffff;
      border-radius: 20px;
      padding: 12px;
      border: 1px solid #f1f5f9;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
    }

    .search-box-wrapper {
      position: relative;
    }

    .search-input {
      border-radius: 14px !important;
      padding-left: 38px !important;
      height: 44px !important;
      border: 1px solid #e2e8f0 !important;
      font-size: 13.5px !important;
      background: #f8fafc !important;
    }

    .search-input:focus {
      background: #ffffff !important;
      border-color: #0073e6 !important;
      box-shadow: 0 0 0 3px rgba(0, 115, 230, 0.15) !important;
    }

    .search-icon {
      left: 12px;
      top: 50%;
      transform: translateY(-50%);
      font-size: 15px;
      color: #94a3b8;
      position: absolute;
      pointer-events: none;
    }

    .btn-filter {
      border-radius: 14px !important;
      background-color: #f8fafc !important;
      color: #334155 !important;
      border: 1px solid #e2e8f0 !important;
      font-weight: 700 !important;
      font-size: 12.5px !important;
      height: 44px !important;
      display: flex !important;
      align-items: center !important;
      justify-content: space-between !important;
      padding: 0 14px !important;
      white-space: nowrap !important;
      overflow: hidden !important;
      text-overflow: ellipsis !important;
    }

    .btn-filter:focus, .btn-filter:active {
      border-color: #0073e6 !important;
      background-color: #ffffff !important;
      box-shadow: 0 0 0 3px rgba(0, 115, 230, 0.15) !important;
    }

    /* Filter Dropdown Menu Customization */
    #filter-container .dropdown-menu {
      max-height: 260px !important;
      overflow-y: auto !important;
      border-radius: 18px !important;
      border: 1px solid #e2e8f0 !important;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12) !important;
      padding: 6px !important;
      min-width: 200px !important;
      max-width: 280px !important;
      z-index: 99999 !important;
      background: #ffffff !important;
    }

    #filter-container .dropdown-menu::-webkit-scrollbar {
      width: 5px;
    }
    #filter-container .dropdown-menu::-webkit-scrollbar-track {
      background: #f8fafc;
      border-radius: 10px;
    }
    #filter-container .dropdown-menu::-webkit-scrollbar-thumb {
      background: #cbd5e1;
      border-radius: 10px;
    }

    #filter-container .dropdown-item {
      font-size: 13px !important;
      font-weight: 600 !important;
      padding: 10px 14px !important;
      border-radius: 12px !important;
      color: #334155 !important;
      white-space: normal !important;
      word-break: break-word !important;
      line-height: 1.35 !important;
      transition: all 0.15s ease !important;
    }

    #filter-container .dropdown-item:hover,
    #filter-container .dropdown-item:focus,
    #filter-container .dropdown-item.active {
      background-color: #eff6ff !important;
      color: #0073e6 !important;
    }

    /* Card List Styling */
    .card-list-leave {
      background: #ffffff;
      border-radius: 20px;
      padding: 16px;
      border: 1px solid #f1f5f9;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
      margin-bottom: 12px;
      transition: all 0.2s ease;
      position: relative;
    }

    .card-list-leave:hover {
      box-shadow: 0 6px 20px rgba(0, 115, 230, 0.08);
      border-color: #e2e8f0;
    }

    .leave-type-icon {
      width: 44px;
      height: 44px;
      border-radius: 14px;
      background: #eff6ff;
      color: #0073e6;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      flex-shrink: 0;
    }

    .emp-name-title {
      font-size: 15px;
      font-weight: 800;
      color: #0f172a;
      margin-bottom: 2px;
    }

    .leave-type-name {
      font-size: 12.5px;
      font-weight: 700;
      color: #0073e6;
    }

    .leave-date-text {
      font-size: 12px;
      color: #64748b;
      font-weight: 600;
    }

    .duration-badge {
      background-color: #eff6ff !important;
      color: #0073e6 !important;
      font-weight: 800 !important;
      font-size: 11.5px !important;
      padding: 4px 10px !important;
      border-radius: 50px !important;
      line-height: 1 !important;
    }

    /* Status Badges */
    .status-badge-custom {
      font-size: 11.5px;
      font-weight: 800;
      padding: 5px 12px;
      border-radius: 50px;
      display: inline-flex;
      align-items: center;
      gap: 4px;
      line-height: 1.2;
    }

    .status-badge-custom.approved {
      background-color: #ecfdf5;
      color: #059669;
    }

    .status-badge-custom.rejected,
    .status-badge-custom.cancelled {
      background-color: #fef2f2;
      color: #dc2626;
    }

    .status-badge-custom.pending,
    .status-badge-custom.process,
    .status-badge-custom.checked {
      background-color: #fffbeb;
      color: #d97706;
    }

    .action-dots {
      width: 32px;
      height: 32px;
      border-radius: 10px;
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      color: #64748b;
      transition: all 0.2s ease;
    }

    .action-dots:active {
      transform: scale(0.92);
      background: #e2e8f0;
    }

    .dropdown-menu-list {
      border-radius: 16px !important;
      border: 1px solid #e2e8f0 !important;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08) !important;
      padding: 6px !important;
    }

    .dropdown-menu-list .action-item {
      font-size: 13px !important;
      font-weight: 700 !important;
      padding: 8px 12px !important;
      border-radius: 10px !important;
      color: #334155 !important;
      display: flex !important;
      align-items: center !important;
      gap: 8px !important;
      cursor: pointer !important;
      transition: background 0.15s ease !important;
    }

    .dropdown-menu-list .action-item:hover {
      background: #f1f5f9 !important;
      color: #0073e6 !important;
    }

    /* Modal Form Customization */
    .modal-content {
      border-radius: 24px !important;
      border: none !important;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
      overflow: hidden !important;
    }

    .modal-header {
      background: linear-gradient(135deg, #0073e6 0%, #00a651 100%) !important;
      color: #ffffff !important;
      padding: 16px 20px !important;
      border: none !important;
    }

    .modal-header .modal-title {
      color: #ffffff !important;
      font-weight: 800 !important;
      font-size: 17px !important;
    }

    .modal-header .btn-close {
      filter: brightness(0) invert(1) !important;
    }

    .modal-body {
      padding: 20px !important;
    }

    .modal-body .form-control,
    .modal-body .form-select {
      border-radius: 14px !important;
      border: 1.5px solid #e2e8f0 !important;
      padding: 10px 14px !important;
      font-size: 13.5px !important;
      font-weight: 600 !important;
      background-color: #f8fafc !important;
    }

    .modal-body .form-control:focus,
    .modal-body .form-select:focus {
      border-color: #0073e6 !important;
      background-color: #ffffff !important;
      box-shadow: 0 0 0 3px rgba(0, 115, 230, 0.15) !important;
    }

    .modal-footer {
      border-top: 1px solid #f1f5f9 !important;
      padding: 14px 20px !important;
      background: #ffffff !important;
    }
  </style>
@endsection

@section('content')
  <div class="leave-page-wrapper">
    <div class="leave-header-banner">
      <div class="top-action-bar">
        <a href="{{ route('main') }}" class="btn-back-link" id="btnLeaveBack">
          <i class="bi bi-arrow-left"></i>
        </a>
        <h1 class="header-page-title">Leave Management</h1>
        <div style="width: 38px;"></div>
      </div>
    </div>

    <div class="content-body">
      <!-- Leave Quota Summary Card -->
      <div class="leave-summary-card mb-3">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <span class="summary-label">Remaining Leave</span>
            <h4 class="summary-value mb-0">{{ $currentEmployee->leave_saldo ?? 0 }} <small style="font-size: 13px; font-weight: 600; color: #64748b;">Days</small></h4>
          </div>
          <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn-summary-action btn-primary-action" data-action="add" data-bs-target="#modalLeaveForm">
              <i class="bi bi-plus-lg me-1"></i> Add Leave
            </button>
            @if ($user->hasRoute('leave.export.excel'))
              <div class="dropdown">
                <button type="button" class="btn-dots-more" data-bs-toggle="dropdown" aria-expanded="false" title="More Options">
                  <i class="bi bi-three-dots-vertical"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-4 p-2">
                  <li>
                    <a class="dropdown-item fw-semibold d-flex align-items-center gap-2 text-success py-2 rounded-3" href="#" data-action="export-excel" data-bs-target="#modalLeaveForm">
                      <i class="bi bi-file-earmark-spreadsheet fs-6"></i> Export Excel
                    </a>
                  </li>
                </ul>
              </div>
            @endif
          </div>
        </div>
      </div>

      <!-- Search & Filter Card -->
      <div class="search-filter-card mb-3" id="filter-container">
        <div class="row g-2 align-items-center">
          <div class="col-12 col-md-5">
            <div class="search-box-wrapper">
              <input type="text" class="form-control search-input" name="search" id="searchInput" placeholder="Search leave records...">
              <i class="bi bi-search search-icon" id="searchBtn"></i>
            </div>
          </div>
          <div class="col-12 col-md-7">
            <div class="d-flex gap-2" id="filter-dropdown">
              <div class="dropdown flex-grow-1">
                <button class="btn btn-filter dropdown-toggle w-100" type="button" id="leaveTypeDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="bi bi-funnel me-1"></i> Type
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-4" aria-labelledby="leaveTypeDropdown" id="leaveTypeFilter">
                  <li><a class="dropdown-item fw-semibold" href="#" data-leave-type="all">All Types</a></li>
                  @foreach ($types as $type)
                    <li><a class="dropdown-item fw-semibold" href="#" data-leave-type="{{ $type->id }}">{{ $type->name }}</a></li>
                  @endforeach
                </ul>
              </div>
              <div class="dropdown flex-grow-1">
                <button class="btn btn-filter dropdown-toggle w-100" type="button" id="statusDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="bi bi-tag me-1"></i> Status
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-4" aria-labelledby="statusDropdown" id="statusFilter">
                  <li><a class="dropdown-item fw-semibold" href="#" data-status="all">All Status</a></li>
                  <li><a class="dropdown-item fw-semibold" href="#" data-status="pending">Pending</a></li>
                  <li><a class="dropdown-item fw-semibold" href="#" data-status="approved">Approved</a></li>
                  <li><a class="dropdown-item fw-semibold" href="#" data-status="rejected">Rejected</a></li>
                </ul>
              </div>
              <div class="dropdown flex-grow-1">
                <button class="btn btn-filter dropdown-toggle w-100" type="button" id="viewDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="bi bi-archive me-1"></i> Ongoing
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-4" aria-labelledby="viewDropdown" id="viewFilter">
                  <li><a class="dropdown-item fw-semibold" href="#" data-view="ongoing">Ongoing</a></li>
                  <li><a class="dropdown-item fw-semibold" href="#" data-view="archived">Archived</a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Leave Card List -->
      <div class="card-list" id="leaveCardList">
        <!-- Data akan diisi secara dinamis oleh JavaScript -->
      </div>
    </div>
  </div>

  {{-- MODAL  --}}

  <div class="modal fade" id="modalLeaveForm" tabindex="-1" role="dialog" aria-labelledby="modalLeaveFormTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-fullscreen modal-dialog-scrollable" role="document">
      <form id="leaveForm" action="{{ route('leave.push') }}" method="POST" enctype="multipart/form-data"
        class="mb-0" style="height: 100%">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalLeaveFormTitle">
              Add Leave
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="form-group mb-3">
              <label class="form-label text-muted" for="type_id">Leave Type<span class="text-danger">*</span></label>
              <select class="form-control" name="type_id" id="type_id" required>
                <option value="">Select Leave Type</option>
                @foreach ($types as $type)
                  <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="row mb-3">
              <div class="col-6 form-group">
                <label class="form-label text-muted" for="start_date">Start Date<span
                    class="text-danger">*</span></label>
                <input type="date" name="start_date" id="start_date" class="form-control" required>
              </div>
              <div class="col-6 form-group">
                <label class="form-label text-muted" for="end_date">End Date<span class="text-danger">*</span></label>
                <input type="date" name="end_date" id="end_date" class="form-control" required>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-6 form-group">
                <label class="form-label text-muted" for="duration">Duration (Days)</label>
                <input type="text" name="duration" id="duration" class="form-control bg-secondary-subtle"
                  readonly>
              </div>
              <div class="col-6 form-group">
                <label class="form-label text-muted" for="remaining_leave">Remaining Leave</label>
                <input type="text" name="remaining_leave" id="remaining_leave"
                  class="form-control bg-secondary-subtle" readonly>
                <input type="hidden" name="shadow_remaining_leave" id="shadow_remaining_leave" readonly>
              </div>
            </div>
            <div class="form-group mb-3">
              <label class="form-label text-muted" for="description">Description</label>
              <textarea name="description" id="description" class="form-control" rows="5" style="width: 100%;"></textarea>
            </div>
            <div class="form-group mb-3" id="file-input-container">
              <label class="form-label text-muted">File</label>
              <input type="file" name="file_id" id="file_id" class="form-control">
            </div>
          </div>

          <div class="modal-footer" id="modalFooter">
            <button type="button" class="btn btn-secondary fw-semibold" data-bs-dismiss="modal">
              <span>Close</span>
            </button>
            <button type="submit" class="btn btn-primary ms-1 fw-semibold">
              <span>Save</span>
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      let selectedLeaveType = '';
      let selectedStatus = 'all';
      let selectedView = 'ongoing';
      let formSelectedLeaveType = '';
      let allLeaveTypes = @json($types);
      let currentEmployee = @json($currentEmployee);

      function formatDate(dateString) {
        const date = new Date(dateString);
        const day = String(date.getDate()).padStart(2, '0');
        const month = date.toLocaleString('en-US', {
          month: 'short'
        });
        const year = date.getFullYear();
        return {
          day,
          month,
          year
        };
      }

      function fetchData(sortField = 'type_id', sortDirection = 'desc') {
        const searchQuery = $('#searchInput').val();
        const url =
          `{{ route('leave.data') }}?search=${searchQuery}&leave_type=${selectedLeaveType}&status=${selectedStatus}&view=${selectedView}`;

        showLoading();

        $.ajax({
          url: url,
          method: 'GET',
          success: function(response) {
            const data = response.data;
            const isMobile = window.matchMedia("(max-width: 768px)").matches;

            $('#leaveCardList').empty();

            if (data.length > 0) {
              data.forEach(item => {
                const {
                  status,
                  statusClass,
                  statusTitle,
                  iconStatus
                } = getStatusDetails(item);
                const dateListLeave = getDateListLeave(item.start_date, item.end_date);
                const today = new Date();
                const endDate = new Date(item.end_date);

                const {
                  actionButton,
                  rejectOrCancelButton
                } = getActionButtons(item, today, endDate);

                if (isMobile) {
                  const card = createMobileCard(item, dateListLeave, status, statusClass, statusTitle,
                    iconStatus, actionButton, rejectOrCancelButton);
                  $('#leaveCardList').append(card);
                }
              });
            } else {
              if (isMobile) {
                const card = createNoDataCard();
                $('#leaveCardList').append(card);
              }
            }
          },
          error: function() {
            alert('Failed to load data.');
          },
          complete: function() {
            hideLoading();
          }
        });
      }

      function getStatusDetails(item) {
        let status = 'Pending';
        let statusClass = 'pending';
        let statusTitle = 'Awaiting approval';
        let iconStatus = 'bi bi-clock-history';

        if (item.cancel_at) {
          status = 'Cancelled';
          statusClass = 'cancelled';
          statusTitle = `Cancelled at: ${item.cancel_at}`;
          iconStatus = 'bi bi-x-circle';
        } else if (item.approved1_status === 0) {
          status = 'Rejected';
          statusClass = 'rejected';
          statusTitle = `Rejected by: ${item.approver1 ? item.approver1.name : 'Unknown'}`;
          iconStatus = 'bi bi-x-circle';
        } else if (item.approved2_status === 0) {
          status = 'Rejected';
          statusClass = 'rejected';
          statusTitle = `Rejected by: ${item.approver2 ? item.approver2.name : 'Unknown'}`;
          iconStatus = 'bi bi-x-circle';
        } else if (item.allowed_status === 0) {
          status = 'Rejected';
          statusClass = 'rejected';
          statusTitle = `Rejected by: ${item.allowed ? item.allowed.name : 'Unknown'}`;
          iconStatus = 'bi bi-x-circle';
        } else if (item.allowed_status === 1) {
          status = 'Approved';
          statusClass = 'approved';
          statusTitle = `Approved by: ${item.allowed ? item.allowed.name : 'Unknown'}`;
          iconStatus = 'bi bi-check-circle-fill';
        } else if (item.approved2_status === 1) {
          status = 'Process';
          statusClass = 'process';
          statusTitle = `Processed by: ${item.approver2 ? item.approver2.name : 'Unknown'}`;
          iconStatus = 'bi bi-check-circle';
        } else if (item.approved1_status === 1) {
          status = 'Checked';
          statusClass = 'checked';
          statusTitle = `Checked by: ${item.approver1 ? item.approver1.name : 'Unknown'}`;
          iconStatus = 'bi bi-check-circle';
        }

        return {
          status,
          statusClass,
          statusTitle,
          iconStatus
        };
      }

      function getDateListLeave(startDate, endDate) {
        const start = formatDate(startDate);
        const end = formatDate(endDate);

        if (start.day === end.day && start.month === end.month && start.year === end.year) {
          return `${start.day} ${start.month} ${start.year}`;
        } else if (start.month === end.month && start.year === end.year) {
          return `${start.day} - ${end.day} ${start.month} ${start.year}`;
        } else {
          return `${start.day} ${start.month} ${start.year} - ${end.day} ${end.month} ${end.year}`;
        }
      }

      function getActionButtons(item, today) {
        let actionButton = '';
        let rejectOrCancelButton = '';

        const isCancelled = !!item.cancel_at;
        const isApproved1Done = item.approved1_status === 1;
        const isApproved2Done = item.approved2_status === 1;
        const isAllowedDone = item.allowed_status === 1;
        const isRejected = item.approved1_status === 0 || item.approved2_status === 0 || item.allowed_status === 0;

        const areEvaluatorsDone = (function() {
          if (item.single_evaluator) {
            return isApproved1Done && isApproved2Done;
          }
          let done = true;
          if (item.employee?.organization?.authorized1) {
            if (!isApproved1Done) done = false;
          }
          if (item.employee?.organization?.authorized2) {
            if (!isApproved2Done) done = false;
          }
          return done;
        })();

        if (!isCancelled && !isRejected && !isAllowedDone) {
          if (item.single_evaluator && item.can_evaluate && (!isApproved1Done || !isApproved2Done)) {
            actionButton = createButton('success', 'evaluate', item, 'Approve Leave', 'bi bi-check-circle');
            rejectOrCancelButton += createButton('danger', 'reject', item, 'Reject Leave', 'bi bi-x-circle');
          } else if (item.can_approve_1 && !isApproved1Done) {
            actionButton = createButton('success', 'approve1', item, 'Approve Leave - Step 1', 'bi bi-check-circle');
            rejectOrCancelButton += createButton('danger', 'reject', item, 'Reject Leave', 'bi bi-x-circle');
          } else if (item.can_approve_2 && (isApproved1Done || item.is_super) && !isApproved2Done) {
            actionButton = createButton('success', 'approve2', item, 'Approve Leave - Step 2', 'bi bi-check-circle');
            rejectOrCancelButton += createButton('danger', 'reject', item, 'Reject Leave', 'bi bi-x-circle');
          } else if (item.can_allow && (areEvaluatorsDone || item.is_super)) {
            actionButton = createButton('success', 'allow', item, 'Allow Leave', 'bi bi-check-circle');
            rejectOrCancelButton += createButton('danger', 'reject', item, 'Reject Leave', 'bi bi-x-circle');
          } else if (item.is_user_leave && !isApproved1Done && !isApproved2Done && !isAllowedDone) {
            actionButton = createButton('primary', 'edit', item, 'Edit Leave', 'bi bi-pencil-square');
          }
        }

        if ((item.is_user_leave || item.is_super) && !isCancelled && !isAllowedDone) {
          if (!rejectOrCancelButton.includes('data-action="cancel"')) {
            rejectOrCancelButton += createButton('warning', 'cancel', item, 'Cancel Leave', 'bi bi-slash-circle');
          }
        }

        if (!actionButton) {
          actionButton = createButton('primary', 'view', item, 'View Leave', 'bi bi-eye');
        }

        return {
          actionButton,
          rejectOrCancelButton
        };
      }

      function createButton(type, action, item, title, iconClass, level) {
        return `
        <div class="action-item my-1" data-action="${action}" data-bs-target="#modalLeaveForm"
        data-item='${JSON.stringify(item)}' title="${title}" ${level ? `data-level="${level}"` : ''}>
            <i class="${iconClass}"></i> ${title}
        </div>`;
      }

      function createMobileCard(item, dateListLeave, status, statusClass, statusTitle, iconStatus, actionButton,
        rejectOrCancelButton) {
        const empName = item.employee?.nickname || item.employee?.fullname || 'Employee';
        const typeName = item.type ? item.type.name : 'Leave Request';

        return `
        <div class="card-list-leave fade-in">
          <div class="d-flex align-items-start justify-content-between mb-3">
            <div class="d-flex align-items-center gap-3">
              <div class="leave-type-icon">
                <i class="bi bi-calendar2-event"></i>
              </div>
              <div>
                <div class="emp-name-title">${empName}</div>
                <div class="leave-type-name">${typeName}</div>
              </div>
            </div>
            <div class="dropdown">
              <div class="action-dots" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-three-dots-vertical"></i>
              </div>
              <ul class="dropdown-menu dropdown-menu-end dropdown-menu-list">
                ${actionButton ? `<li>${actionButton}</li>` : ''}
                ${rejectOrCancelButton ? `<li>${rejectOrCancelButton}</li>` : ''}
              </ul>
            </div>
          </div>

          <div class="d-flex align-items-center justify-content-between pt-2 border-top border-light">
            <div class="d-flex align-items-center gap-2">
              <span class="leave-date-text"><i class="bi bi-calendar3 me-1"></i>${dateListLeave}</span>
              <span class="duration-badge">${item.duration} Days</span>
            </div>
            <div class="status-badge-custom ${statusClass}" title="${statusTitle}">
              <i class="${iconStatus}"></i> ${status}
            </div>
          </div>
        </div>`;
      }

      function createNoDataCard() {
        return `
                    <div class="d-flex flex-column justify-content-center align-items-center gap-2 border p-4 rounded">
                        <img src="{{ asset('/images/nodata.png') }}" alt="No Bulletin Found" class="img-fluid" style="max-width: 300px; max-height: 300px;" />
                        <h3 class="text-center font-bold text-black mb-0">No Data Found</h3>
                    </div>`;
      }

      // Initial data load
      fetchData();

      // Search button click event
      $('#searchBtn').click(function() {
        fetchData();
      });

      $(document).on('click', '#leaveTypeFilter .dropdown-item', function(e) {
        e.preventDefault();
        selectedLeaveType = $(this).data('leave-type');
        const text = $(this).text().trim();
        $(this).closest('.dropdown').find('.btn').html('<i class="bi bi-funnel me-1"></i> ' + text);
        fetchData();
      });

      $(document).on('click', '#statusFilter .dropdown-item', function(e) {
        e.preventDefault();
        selectedStatus = $(this).data('status');
        const text = $(this).text().trim();
        $(this).closest('.dropdown').find('.btn').html('<i class="bi bi-tag me-1"></i> ' + text);
        fetchData();
      });

      $(document).on('click', '#viewFilter .dropdown-item', function(e) {
        e.preventDefault();
        selectedView = $(this).data('view');
        const text = $(this).text().trim();
        $(this).closest('.dropdown').find('.btn').html('<i class="bi bi-archive me-1"></i> ' + text);
        fetchData();
      });

      const modal = $('#modalLeaveForm');
      const form = $('#leaveForm');
      let actionType = 'add';

      $(document).on('click', '[data-action]', function(event) {
        event.preventDefault();

        actionType = $(this).data('action');
        let leaveData = {};
        let leaveId = '';

        if (actionType !== 'add' && actionType !== 'export-excel') {
          try {
            leaveData = JSON.parse($(this).attr('data-item'));
            leaveId = leaveData.id || '';
          } catch (error) {
            console.error("Error parsing JSON:", error);
          }
        }

        const $modalLeaveForm = $('#modalLeaveForm');
        if ($modalLeaveForm.length === 0) {
          console.error("Modal tidak ditemukan di DOM");
          return;
        }

        setModalTitle(actionType);

        const form = $('#modalLeaveForm form');
        form.attr('action', getFormActionUrl(actionType, leaveId));

        resetForm();

        if (actionType === 'edit') {
          handleEdit(leaveId, leaveData);
        } else if (['approve1', 'approve2', 'allow'].includes(actionType)) {
          handleApproval(leaveId, leaveData, actionType);
        } else if (['cancel', 'reject'].includes(actionType)) {
          handleCancellationOrRejection(leaveId, leaveData, actionType);
        } else if (actionType === 'add') {
          handleAddNew();
        } else if (actionType === 'view') {
          handleView(leaveData);
        } else if (actionType === 'export-excel') {
          handleExportExcel();
        } else {
          handleDefault(leaveId, leaveData);
        }

        $modalLeaveForm.modal('show');
      });

      function setModalTitle(actionType) {
        const modalTitle = $('#modalLeaveForm .modal-title');
        const titles = {
          'add': 'Add New Leave',
          'edit': 'Edit Leave',
          'approve1': 'Approve Leave - Step 1',
          'approve2': 'Approve Leave - Step 2',
          'cancel': 'Cancel Leave',
          'reject': 'Reject Leave',
          'view': 'View Leave Request',
          'allow': 'Allow Leave',
          'export-excel': 'Export Excel',
        };
        modalTitle.text(titles[actionType] || 'Unknown Action');
      }

      function getFormActionUrl(actionType, leaveId) {
        const routes = {
          'edit': `{{ route('leave.push', ':id') }}`.replace(':id', leaveId),
          'approve1': `{{ route('leave.approve', ':id') }}`.replace(':id', leaveId),
          'approve2': `{{ route('leave.approve', ':id') }}`.replace(':id', leaveId),
          'allow': `{{ route('leave.approve', ':id') }}`.replace(':id', leaveId),
          'cancel': `{{ route('leave.cancel', ':id') }}`.replace(':id', leaveId),
          'reject': `{{ route('leave.reject', ':id') }}`.replace(':id', leaveId),
          'add': `{{ route('leave.push') }}`,
          'export-excel': `{{ route('leave.export.excel') }}`,
        };
        return routes[actionType] || `{{ route('leave.push') }}`;
      }

      function resetForm() {
        $('#start_date, #end_date, #duration, #remaining_leave').closest('.form-group').hide();
        $('#notes')
          .removeAttr('required')
          .val('')
          .closest('.form-group')
          .hide();
        $('#file-preview').remove();
        $('#leave-history-table').remove();
        $('#leave-history-label').remove();
      }

      function handleView(leaveData) {
        $('#type_id, #start_date, #end_date, #duration, #remaining_leave, #description')
          .closest('.form-group')
          .show()
          .find('input, select, textarea')
          .prop('readonly', true)
          .addClass('bg-secondary-subtle');
        $('#file-input-container').show();
        $('#file_id').hide();
        $('#modalFooter').hide();
        fetchLeaveData(leaveData.id);
        handleFilePreview(leaveData);

        const leaveHistoryTable = `
            <label class="form-label text-muted mt-3 mb-1 font-bold" id="leave-history-label">Leave History</label>
            <table id="leave-history-table" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>Action At</th>
                        <th>Action By</th>
                        <th>Note</th>
                    </tr>
                </thead>
                <tbody>
                    ${generateLeaveHistoryRows(leaveData)}
                </tbody>
            </table>
        `;

        if (hasValidHistory(leaveData)) {
          $('.modal-body').append(leaveHistoryTable);
        }
      }

      function generateLeaveHistoryRows(leaveData) {
        const historyFields = [{
            status: getApprovalStatus(leaveData.approved1_status, 'Approved 1', 'Rejected 1'),
            at: leaveData.approved1_at,
            by: leaveData.approved1_at && leaveData.approver1 ? leaveData.approver1.name : leaveData.approved1_by,
            note: leaveData.approved1_note
          },
          {
            status: getApprovalStatus(leaveData.approved2_status, 'Approved 2', 'Rejected 2'),
            at: leaveData.approved2_at,
            by: leaveData.approved2_at && leaveData.approver2 ? leaveData.approver2.name : leaveData.approved2_by,
            note: leaveData.approved2_note
          },
          {
            status: getApprovalStatus(leaveData.allowed_status, 'Allowed', 'Rejected'),
            at: leaveData.allowed_at,
            by: leaveData.allowed_at && leaveData.allowed ? leaveData.allowed.name : leaveData.allowed_by,
            note: leaveData.allowed_note
          },
          {
            status: 'Cancelled',
            at: leaveData.cancel_at,
            by: leaveData.cancel_at && leaveData.created_by ? leaveData.created_by.name : leaveData.cancel_by,
            note: leaveData.cancel_note
          }
        ];

        return historyFields
          .filter(field => field.at || field.by || field.note)
          .map(
            (field) => `
                <tr>
                    <td>${field.status}</td>
                    <td>${field.at || '-'}</td>
                    <td>${field.by || '-'}</td>
                    <td>${field.note || '-'}</td>
                </tr>
            `
          )
          .join('');
      }


      function getApprovalStatus(status, approvedText, rejectedText) {
        if (status === 1) return approvedText;
        if (status === 0) return rejectedText;
        return 'Pending';
      }

      function hasValidHistory(leaveData) {
        return (
          leaveData.approved1_at ||
          leaveData.approved2_at ||
          leaveData.allowed_at ||
          leaveData.cancel_at ||
          leaveData.cancel_note ||
          leaveData.cancel_by
        );
      }

      function handleEdit(leaveId, leaveData) {
        $('#start_date, #end_date, #duration, #remaining_leave').closest('.form-group').show();
        $('#file-input-container').show();
        $('#file_id').hide();
        $('#modalFooter').show();
        fetchLeaveData(leaveId);
        handleFilePreview(leaveData);
      }

      function handleApproval(leaveId, leaveData, actionType) {
        $('#start_date, #end_date, #duration, #remaining_leave').closest('.form-group').show();
        $('#type_id, #start_date, #end_date, #duration, #remaining_leave, #description')
          .closest('.form-group')
          .show()
          .find('input, select, textarea')
          .prop('readonly', true)
          .addClass('bg-secondary-subtle');
        $('#file-input-container').show();
        $('#file_id').hide();
        $('#modalFooter').show();
        fetchLeaveData(leaveId);
        handleFilePreview(leaveData);

        if ($('#notes').length === 0) {
          $('.modal-body').append(`
            <div class="form-group mt-2">
                <label for="notes" class="form-label text-muted">Notes<span class="text-danger">*</span></label>
                <textarea id="notes" name="notes" class="form-control" rows="4" placeholder="Provide your reason here..." required></textarea>
            </div>
        `);
        } else {
          $('#notes').closest('.form-group').show();
        }
      }

      function handleCancellationOrRejection(leaveId, leaveData, actionType) {
        $('#type_id, #start_date, #end_date, #duration, #remaining_leave, #description')
          .closest('.form-group')
          .show()
          .find('input, select, textarea')
          .prop('readonly', true)
          .addClass('bg-secondary-subtle');
        $('#file-input-container').show();
        $('#file_id').hide();
        $('#modalFooter').show();
        fetchLeaveData(leaveId);
        handleFilePreview(leaveData);

        if ($('#notes').length === 0) {
          $('.modal-body').append(`
            <div class="form-group mt-2">
                <label for="notes" class="form-label text-muted">Notes<span class="text-danger">*</span></label>
                <textarea id="notes" name="notes" class="form-control" rows="4" placeholder="Provide your reason here..." required></textarea>
            </div>
        `);
        } else {
          $('#notes').closest('.form-group').show();
        }
      }

      function handleAddNew() {
        $('#start_date, #end_date, #duration, #remaining_leave').closest('.form-group').show();
        $('#file-input-container').show();
        $('#file_id').show();
        $('#modalFooter').show();
        $('#remaining_leave').val(currentEmployee.leave_saldo || 0);
        formSelectedLeaveType = $('#type_id').val();
        if (formSelectedLeaveType) {
          calculateLeave(formSelectedLeaveType);
        }
      }

      function handleExportExcel() {
        $('#start_date, #end_date').closest('.form-group').show();
        $('#type_id, #duration, #remaining_leave, #description')
          .closest('.form-group').hide();
        $('#file-input-container').hide();
        $('#type_id').removeAttr('required');
        $('#modalFooter').show();
      }

      function handleDefault(leaveId, leaveData) {
        $('#type_id, #start_date, #end_date, #duration, #remaining_leave, #description')
          .closest('.form-group')
          .show()
          .find('input, select, textarea')
          .prop('readonly', true)
          .addClass('bg-secondary-subtle');
        $('#file-input-container').show();
        $('#file_id').hide();
        $('#modalFooter').show();
        fetchLeaveData(leaveId);
        handleFilePreview(leaveData);
      }

      function handleFilePreview(leaveData) {
        if (leaveData.file) {
          let urlFile = "{{ route('file', ':id') }}".replace(':id', leaveData.file.id);
          $('#file-input-container').css('margin-bottom', '0px');

          let filePreviewHtml = `
                <div id="file-preview" class="d-flex justify-content-between align-items-center">
                     <p class="mb-0" style="flex: 1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-size: 14px;">${leaveData.file.filename_origin}</p>
                <div class="file-actions d-flex justify-content-center gap-2">
          `;

          if (leaveData.is_user_leave &&
            leaveData.approved1_status === null &&
            leaveData.approved2_status === null &&
            leaveData.allowed_status === null) {
            filePreviewHtml += `
                    <button type="button" id="changeFile" class="btn btn-sm btn-outline-secondary" title="Change File">
                        <i class="bi bi-pencil"></i>
                    </button>
            `;
          }

          filePreviewHtml += `
                    <a href="${urlFile}" class="btn btn-sm btn-outline-primary" title="Download File" download>
                        <i class="bi bi-download"></i>
                    </a>
                </div>
            </div>
          `;

          $('.modal-body #file-input-container').append(filePreviewHtml);

          if ($('#file_preview').length === 0) {
            $('#file-input-container').append(
              '<input type="hidden" id="file_preview" name="file_preview" value="true" />');
          }

          $('#changeFile').on('click', function() {
            $('#file-preview').remove();
            $('#file_id').show().val('');
            $('#file_id').trigger('click');
          });

        } else {
          let textNoFile =
            '<p class="mb-0" style="flex: 1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-size: 14px;">No file uploaded</p>';
          $('#file-input-container').css('margin-bottom', '0px');

          let addFileButtonHtml = '';
          if (actionType === 'edit') {
            addFileButtonHtml = `
                <div class="file-actions d-flex justify-content-center gap-2">
                    <button type="button" id="addFile" class="btn btn-sm btn-outline-primary" title="Add File">
                        <i class="bi bi-plus"></i>
                    </button>
                </div>
            `;
          }

          $('.modal-body #file-input-container').append(`
                <div id="file-preview" class="d-flex justify-content-between align-items-center">
                    ${textNoFile}
                    ${addFileButtonHtml}
                </div>
        `);

          if (actionType === 'edit') {
            $('#addFile').on('click', function() {
              $('#file-preview').remove();
              $('#file_id').show().val('');
              $('#file_id').trigger('click');
            });
          }
        }
      }

      $('#modalLeaveForm').on('hidden.bs.modal', function() {
        form[0].reset();
        form.attr('action', '');
        form.attr('method', 'POST');
        $('#type_id, #start_date, #end_date, #description')
          .closest('.form-group')
          .show()
          .find('input, select, textarea')
          .prop('readonly', false)
          .removeClass('bg-secondary-subtle');
        $('#type_id').attr('required', true);
        $('#modalLeaveForm .modal-title').text('');
      });

      function fetchLeaveData(leaveId) {
        $.ajax({
          url: `{{ route('leave.get', ':id') }}`.replace(':id', leaveId),
          method: 'GET',
          success: function(data) {
            $('#type_id').val(data.data.type_id);
            $('#start_date').val(data.data.start_date);
            $('#end_date').val(data.data.end_date);
            $('#description').val(data.data.description);

            const typeId = data.data.type_id;
            const leaveType = allLeaveTypes.filter(item => item.id === parseInt(typeId))[0];
            const duration = data.data.duration;
            $('#duration').val(duration);

            let leaveSaldo = data.data.leave_saldo;
            if (actionType !== 'add' && actionType !== 'edit') {
              if (actionType === 'view') {
                $('#remaining_leave').val(leaveSaldo);
              } else {
                if (leaveType?.property?.flag_reduce_balance) {
                  $('#remaining_leave').val(leaveSaldo - duration);
                } else {
                  $('#remaining_leave').val(leaveSaldo);
                }
              }
            }

            if (actionType === 'add' || actionType === 'edit') {
              formSelectedLeaveType = data.data.type_id;
              calculateLeave(formSelectedLeaveType);
            }
          },
          error: function(error) {
            console.error('Failed to fetch leave data:', error);
          }
        });
      }

      function calculateLeave(leaveId) {
        const leaveIdNum = parseInt(leaveId);
        const leaveType = allLeaveTypes.filter(item => parseInt(item.id) === leaveIdNum)[0];

        const startDate = new Date($('#start_date').val());
        const endDate = new Date($('#end_date').val());

        let leaveSaldo = parseFloat(currentEmployee.leave_saldo || 0);
        let shadowLeaveSaldo = leaveSaldo;

        if (!isNaN(startDate) && !isNaN(endDate) && startDate <= endDate) {
          const duration = Math.floor((endDate - startDate) / (1000 * 60 * 60 * 24)) + 1;
          $('#duration').val(duration);

          let isReduceBalance = false;
          if (leaveType) {
            if (typeof leaveType.property === 'string') {
              try {
                const parsed = JSON.parse(leaveType.property);
                isReduceBalance = parsed?.flag_reduce_balance === true || parsed?.flag_reduce_balance === '1' || parsed?.flag_reduce_balance === 'true';
              } catch (e) {}
            } else if (typeof leaveType.property === 'object' && leaveType.property !== null) {
              isReduceBalance = leaveType.property.flag_reduce_balance === true || leaveType.property.flag_reduce_balance === '1' || leaveType.property.flag_reduce_balance === 'true';
            }
            if (leaveType.flag_reduce_balance === true || leaveType.flag_reduce_balance === '1' || leaveType.flag_reduce_balance === 'true') {
              isReduceBalance = true;
            }
          }

          if (isReduceBalance) {
            leaveSaldo = shadowLeaveSaldo - duration;
            $('#remaining_leave').val(leaveSaldo);
          } else {
            $('#remaining_leave').val(shadowLeaveSaldo);
          }

          $('#shadow_remaining_leave').val(shadowLeaveSaldo);
        } else {
          $('#duration').val('');
          $('#remaining_leave').val(shadowLeaveSaldo);
          $('#shadow_remaining_leave').val(shadowLeaveSaldo);
        }
      }

      $('#type_id').on('change', function() {
        formSelectedLeaveType = $(this).val();
        if (actionType === 'add' || actionType === 'edit') {
          calculateLeave(formSelectedLeaveType);
        }
      });

      $('#start_date, #end_date').on('change', function() {
        if (actionType === 'add' || actionType === 'edit') {
          calculateLeave(formSelectedLeaveType);
        }
      });

      form.on('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(this);

        if (actionType === 'export-excel') {
          const startDate = form.find('[name="start_date"]').val();
          const endDate = form.find('[name="end_date"]').val();
          const query = $.param({
            start_date: startDate || '',
            end_date: endDate || ''
          });

          const exportUrl = form.attr('action') + '?' + query;
          window.location.href = exportUrl;
          modal.modal('hide');
          return;
        }

        const typeId = formData.get('type_id');
        const fileId = formData.get('file_id');
        const filePreview = formData.get('file_preview');

        if (actionType === 'add' || actionType === 'edit') {
          const leaveIdNum = parseInt(typeId);
          const leaveType = allLeaveTypes.filter(item => parseInt(item.id) === leaveIdNum)[0];
          let isReduceBalance = false;
          if (leaveType) {
            if (typeof leaveType.property === 'string') {
              try {
                const parsed = JSON.parse(leaveType.property);
                isReduceBalance = parsed?.flag_reduce_balance === true || parsed?.flag_reduce_balance === '1' || parsed?.flag_reduce_balance === 'true';
              } catch (e) {}
            } else if (typeof leaveType.property === 'object' && leaveType.property !== null) {
              isReduceBalance = leaveType.property.flag_reduce_balance === true || leaveType.property.flag_reduce_balance === '1' || leaveType.property.flag_reduce_balance === 'true';
            }
            if (leaveType.flag_reduce_balance === true || leaveType.flag_reduce_balance === '1' || leaveType.flag_reduce_balance === 'true') {
              isReduceBalance = true;
            }
          }

          if (isReduceBalance) {
            const remLeave = parseFloat($('#remaining_leave').val());
            if (!isNaN(remLeave) && remLeave < -5) {
              showAlert('danger', 'Insufficient leave balance. Remaining leave cannot be less than -5.');
              return;
            }
          }
        }

        if (typeId === '2101') {
          if (actionType === 'add') {
            if (!fileId || fileId.size === 0) {
              showAlert('warning', 'Please upload a file for this type before proceeding.');
              return;
            }
          } else if (actionType === 'edit') {
            if (filePreview !== 'true') {
              if (!fileId || fileId.size === 0) {
                showAlert('warning', 'Please upload a file for this type before proceeding.');
                return;
              }
            }
          }
        }

        $.ajax({
          url: form.attr('action'),
          method: 'POST',
          data: formData,
          contentType: false,
          processData: false,
          success: function(response) {
            modal.modal('hide');
            form[0].reset();
            fetchData();
            showAlert('success', 'Data has been processed successfully.');
            formSelectedLeaveType = '';
          },
          error: function(xhr) {
            try {
              const response = JSON.parse(xhr.responseText);
              const message = response.message || 'An error occurred while processing the data.';
              console.error(message);
              showAlert('danger', message);
            } catch (e) {
              console.error('Failed to parse JSON response:', e);
              showAlert('danger', 'An error occurred while processing the data.');
            }
          }
        });
      });

    });
  </script>
@endsection
