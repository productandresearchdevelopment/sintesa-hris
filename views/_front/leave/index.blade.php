@extends('headers.head')

@section('header')
  <style>
    .card-list {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 1rem;
    }

    .leave-card {
      border-radius: 12px;
      overflow: hidden;
      background-color: #fff;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      transition: transform 0.2s ease, box-shadow 0.2s ease;
      position: relative;
      display: flex;
      flex-direction: column;
    }

    .leave-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
    }

    .leave-card .card-img-container {
      display: flex;
      justify-content: center;
      align-items: center;
      padding-top: 1rem;
    }

    .leave-card img {
      width: 80px;
      height: 80px;
      border-radius: 100%;
      object-fit: cover;
    }

    .leave-card .status-badge {
      position: absolute;
      top: 15px;
      right: 15px;
      text-transform: uppercase;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .leave-card .duration-badge {
      max-width: max-content;
      margin: auto;
    }

    .leave-card .card-body {
      padding: 1rem 1.5rem;
      flex: 1;
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
    }

    .leave-card .card-body h3 {
      font-size: 1.2rem;
      font-weight: bold;
      margin: 0;
    }

    .leave-card .card-body h5 {
      font-size: 1rem;
      font-weight: bold;
      margin: 0;
      color: #333;
    }

    .leave-card .card-body .info-row {
      display: flex;
      justify-content: space-between;
      font-size: 0.95rem;
      color: #555;
    }

    .leave-card .card-body .info-row strong {
      color: #444;
    }

    .leave-card .card-footer {
      padding: 1rem 1.5rem;
      display: flex;
      justify-content: flex-end;
      gap: 0.5rem;
      border-top: 1px solid #e0e0e0;
    }

    .leave-card .card-footer button {
      padding: 0.6rem 1.2rem;
      font-size: 0.9rem;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .input-search-container {
      width: max-content;
    }


    @media(max-width: 576px) {
      .button-add-container {
        width: 100%;
        order: 2;
      }

      .button-add-container button {
        width: 100%;
      }

      .select-show-container {
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
      }

      .select-show-container select {
        width: 25%;
      }

      .input-search-container {
        width: 100%;
      }

      .input-search-container input {
        width: 100%;
      }

      .dropdown-custom-container {
        width: 100%;
      }

      .dropdown-custom-container button {
        width: 100%;
      }
    }
  </style>
@endsection

@section('body')
  <div class="bg-white p-4 main-container" style="min-height: 100%; min-width: 100%;">
    <div class="row mb-4">
      <div
        class="col-12 col-md-6 d-flex flex-column flex-sm-row justify-content-center justify-content-md-start align-items-center mb-2 mb-sm-4 mb-md-0 gap-2">
        <div class="button-add-container">
          <button type="button" class="btn btn-primary btn-sm" data-action="add" data-bs-target="#modalLeaveForm">
            Add New
          </button>
        </div>
        @if ($user->hasRoute('leave.export.excel'))
          <div class="button-export-excel-container">
            <button type="button" class="btn btn-success btn-sm" data-action="export-excel"
              data-bs-target="#modalLeaveForm">
              Export Excel
            </button>
          </div>
        @endif
      </div>

      <div
        class="col-12 col-md-6 d-flex flex-column flex-md-row align-items-center justify-content-center justify-content-md-end gap-2 gap-sm-4">
        <div class="input-group input-group-sm mb-0 input-search-container">
          <input type="text" class="form-control form-control-sm" name="search" id="searchInput"
            placeholder="Search...">
          <div class="input-group-append">
            <button class="btn btn-primary btn-sm" type="button" id="searchBtn">Search</button>
          </div>
        </div>
        <div class="d-flex d-flex flex-column flex-sm-row gap-2 dropdown-custom-container">
          <div class="dropdown">
            <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="leaveTypeDropdown"
              data-bs-toggle="dropdown" aria-expanded="false">
              Leave Type
            </button>
            <ul class="dropdown-menu" aria-labelledby="leaveTypeDropdown" id="leaveTypeFilter"
              style="box-shadow:0 2px 4px rgba(0, 0, 0, 0.1);">
              <li><a class="dropdown-item" href="#" data-leave-type="all">All</a></li>
              @foreach ($types as $type)
                <li><a class="dropdown-item" href="#" data-leave-type="{{ $type->id }}">{{ $type->name }}</a>
                </li>
              @endforeach
            </ul>
          </div>
          <div class="dropdown">
            <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="statusDropdown"
              data-bs-toggle="dropdown" aria-expanded="false">
              Status
            </button>
            <ul class="dropdown-menu" aria-labelledby="statusDropdown" id="statusFilter"
              style="box-shadow:0 2px 4px rgba(0, 0, 0, 0.1);">
              <li><a class="dropdown-item" href="#" data-status="all">All</a></li>
              <li><a class="dropdown-item" href="#" data-status="pending">Pending</a></li>
              <li><a class="dropdown-item" href="#" data-status="approved">Approved</a></li>
              <li><a class="dropdown-item" href="#" data-status="process">Process</a></li>
              <li><a class="dropdown-item" href="#" data-status="checked">Checked</a></li>
              <li><a class="dropdown-item" href="#" data-status="rejected">Rejected</a></li>
              <li><a class="dropdown-item" href="#" data-status="cancelled">Cancelled</a></li>
            </ul>
          </div>
          <div class="dropdown">
            <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="viewDropdown"
              data-bs-toggle="dropdown" aria-expanded="false">
              Ongoing
            </button>
            <ul class="dropdown-menu" aria-labelledby="viewDropdown" id="viewFilter"
              style="box-shadow:0 2px 4px rgba(0, 0, 0, 0.1);">
              <li><a class="dropdown-item" href="#" data-view="ongoing">Ongoing</a></li>
              <li><a class="dropdown-item" href="#" data-view="archived">Archived</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="card-list" id="leaveCardList">
      <!-- Data akan diisi secara dinamis oleh JavaScript -->
    </div>
  </div>

  <div class="modal fade" id="modalLeaveForm" tabindex="-1" role="dialog" aria-labelledby="modalLeaveFormTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
      <form id="leaveForm" action="{{ route('leave.push') }}" method="POST" enctype="multipart/form-data"
        class="mb-0">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalLeaveFormTitle">
              Add Leave
            </h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
              <i class="bi bi-x-lg"></i>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label class="form-label text-muted" for="type_id">Leave Type<span class="text-danger">*</span></label>
              <select class="form-control" name="type_id" id="type_id" required>
                <option value="">Select Leave Type</option>
                @foreach ($types as $type)
                  <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="row">
              <div class="col-md-6 form-group">
                <label class="form-label text-muted" for="start_date">Start Date<span
                    class="text-danger">*</span></label>
                <input type="date" name="start_date" id="start_date" class="form-control" required>
              </div>
              <div class="col-md-6 form-group">
                <label class="form-label text-muted" for="end_date">End Date<span class="text-danger">*</span></label>
                <input type="date" name="end_date" id="end_date" class="form-control" required>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 form-group">
                <label class="form-label text-muted" for="duration">Duration (Days)</label>
                <input type="text" name="duration" id="duration" class="form-control bg-secondary-subtle"
                  readonly>
              </div>
              <div class="col-md-6 form-group">
                <label class="form-label text-muted" for="remaining_leave">Remaining Leave</label>
                <input type="text" name="remaining_leave" id="remaining_leave"
                  class="form-control bg-secondary-subtle" readonly>
                <input type="hidden" name="shadow_remaining_leave" id="shadow_remaining_leave"
                  class="form-control bg-secondary-subtle" readonly>
              </div>
            </div>
            <div class="form-group">
              <label class="form-label text-muted" for="description">Description</label>
              <textarea name="description" id="description" class="form-control"></textarea>
            </div>
            <div class="form-group" id="file-input-container">
              <label class="form-label text-muted">File</label>
              <input type="file" name="file_id" id="file_id" class="form-control">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
              <span>Close</span>
            </button>
            <button type="submit" class="btn btn-primary ms-1">
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

      function createButton(type, action, item, title, iconClass) {
        return `<button type="button" class="btn btn-sm btn-${type}" data-action="${action}"
            data-bs-target="#modalLeaveForm" data-item='${JSON.stringify(item)}' title="${title}">
                <i class="${iconClass}"></i>
            </button>`;
      }

      function getStatusDetails(item) {
        let status = 'Pending';
        let statusClass = 'bg-warning';
        let statusTitle = 'Awaiting for approval';
        let iconStatus = 'bi bi-person text-black';

        if (item.cancel_at) {
          status = 'Cancelled';
          statusClass = 'bg-danger';
          statusTitle = `Cancelled at: ${item.cancel_at}`;
          iconStatus = 'bi bi-x-circle';
        } else if (item.approved1_status === 0) {
          status = 'Rejected';
          statusClass = 'bg-danger';
          statusTitle = `Rejected by: ${item.approver1 ? item.approver1.name : 'Unknown'}`;
          iconStatus = 'bi bi-x-circle';
        } else if (item.approved2_status === 0) {
          status = 'Rejected';
          statusClass = 'bg-danger';
          statusTitle = `Rejected by: ${item.approver2 ? item.approver2.name : 'Unknown'}`;
          iconStatus = 'bi bi-x-circle';
        } else if (item.allowed_status === 0) {
          status = 'Rejected';
          statusClass = 'bg-danger';
          statusTitle = `Rejected by: ${item.allowed ? item.allowed.name : 'Unknown'}`;
          iconStatus = 'bi bi-x-circle';
        } else if (item.allowed_status === 1) {
          status = 'Approved';
          statusClass = 'bg-success';
          statusTitle = `Approved by: ${item.allowed ? item.allowed.name : 'Unknown'}`;
          iconStatus = 'bi bi-check-circle text-success';
        } else if (item.approved2_status === 1) {
          status = 'Process';
          statusClass = 'bg-primary';
          statusTitle = `Processed by: ${item.approver2 ? item.approver2.name : 'Unknown'}`;
          iconStatus = 'bi bi-check-circle text-success';
        } else if (item.approved1_status === 1) {
          status = 'Checked';
          statusClass = 'bg-info';
          statusTitle = `Checked by: ${item.approver1 ? item.approver1.name : 'Unknown'}`;
          iconStatus = 'bi bi-check-circle text-success';
        }

        return {
          status,
          statusClass,
          statusTitle,
          iconStatus
        };
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

      function fetchData(sortField = 'type_id', sortDirection = 'asc') {
        const searchQuery = $('#searchInput').val();
        const url =
          `{{ route('leave.data') }}?search=${searchQuery}&leave_type=${selectedLeaveType}&status=${selectedStatus}&view=${selectedView}`;

        showLoading();

        $.ajax({
          url: url,
          method: 'GET',
          success: function(response) {
            const data = response.data;
            const pagination = response.pagination;

            $('#leaveCardList').empty();

            if (data.length > 0) {
              const today = new Date();
              data.forEach(item => {
                const {
                  status,
                  statusClass,
                  statusTitle,
                  iconStatus
                } = getStatusDetails(item);
                const {
                  actionButton,
                  rejectOrCancelButton
                } = getActionButtons(item, today);

                const card = `
                      <div class="leave-card">
                        <div>
                          <div class="card-img-container">
                            <img src="${item.profile_picture}" alt="Profile Picture of ${item.employee.nickname || item.employee.fullname}" class="card-img-top" />
                          </div>
                        </div>
                        <span class="status-badge badge ${statusClass}" title="${statusTitle}">${status}</span>
                        <div class="card-body">
                          <h3 class="text-center text-black">${item.employee.nickname || item.employee.fullname}</h3>
                          <h5 class="text-center">( ${item.type ? item.type.name : 'N/A'} )</h5>
                          <div class="d-flex gap-2 align-items-center justify-content-center text-muted">
                            <span>${item.start_date}</span> - <span>${item.end_date}</span>
                          </div>
                          <div class="duration-badge badge bg-secondary">
                            ${item.duration} Days
                          </div>
                        </div>
                        <div class="card-footer d-flex gap-2 align-items-center justify-content-between">
                            <div>
                                <i class="${iconStatus}"></i>
                                <span class="text-muted small">${statusTitle}</span>
                            </div>
                            <div class="d-flex gap-2 align-items-center justify-content-center">
                                ${actionButton}
                                ${rejectOrCancelButton}
                            </div>
                        </div>
                      </div>
                    `;
                $('#leaveCardList').css('display', 'grid');
                $('#leaveCardList').append(card);
              });
            } else {
              const card = `
                    <div class="d-flex flex-column justify-content-center align-items-center gap-2 border p-4 rounded">
                        <img src="{{ asset('/images/nodata.png') }}" alt="No Bulletin Found" class="img-fluid" style="max-width: 300px; max-height: 300px;" />
                        <h3 class="text-center font-bold text-black mb-0">No Data Found</h3>
                    </div>
                `;
              $('#leaveCardList').css('display', 'block');
              $('#leaveCardList').append(card);
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

      fetchData();
      $('#searchBtn').click(function() {
        fetchData();
      });

      $(document).on('click', '#leaveTypeFilter .dropdown-item', function() {
        selectedLeaveType = $(this).data('leave-type');
        $(this).closest('.dropdown').find('.btn').text($(this).text());
        fetchData();
      });

      $(document).on('click', '#statusFilter .dropdown-item', function() {
        selectedStatus = $(this).data('status');
        $(this).closest('.dropdown').find('.btn').text($(this).text());
        fetchData();
      });

      $(document).on('click', '#viewFilter .dropdown-item', function() {
        selectedView = $(this).data('view');
        $(this).closest('.dropdown').find('.btn').text($(this).text());
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
        } else if (['approve1', 'approve2', 'evaluate', 'allow'].includes(actionType)) {
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
          'evaluate': 'Approve Leave',
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
          'evaluate': `{{ route('leave.approve', ':id') }}`.replace(':id', leaveId),
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
        fetchLeaveData(leaveData.id);
        handleFilePreview(leaveData);
        $('#modalLeaveForm .modal-footer button[type="submit"]').hide();

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
        fetchLeaveData(leaveId);
        handleFilePreview(leaveData);
        $('#modalLeaveForm .modal-footer button[type="submit"]').show();
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
        fetchLeaveData(leaveId);
        handleFilePreview(leaveData);
        $('#modalLeaveForm .modal-footer button[type="submit"]').show();

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
        fetchLeaveData(leaveId);
        handleFilePreview(leaveData);
        $('#modalLeaveForm .modal-footer button[type="submit"]').show();

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
        $('#remaining_leave').val(currentEmployee.leave_saldo || 0);
        $('#modalLeaveForm .modal-footer button[type="submit"]').show();
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
        $('#modalLeaveForm .modal-footer button[type="submit"]').show();
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
        fetchLeaveData(leaveId);
        handleFilePreview(leaveData);
        $('#modalLeaveForm .modal-footer button[type="submit"]').show();
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

          $('#changeFile').on('click', function() {
            $('#file-preview').remove();
            $('#file_id').show().val('');
          });
        } else {
          let textNoFile =
            '<p class="mb-0" style="flex: 1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-size: 14px;">No file uploaded</p>';
          $('#file-input-container').css('margin-bottom', '0px');
          $('.modal-body #file-input-container').append(`
                    <div id="file-preview" class="d-flex justify-content-between align-items-center">
                        ${textNoFile}
                    </div>
            `);
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
