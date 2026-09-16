@extends('headers.head')

@section('header')
  <style>
    .section-left {
      border-bottom: 1px solid #e0e0e0;
    }

    #org_id .jstree-anchor {
      font-size: 12px;
    }

    .card {
      background-color: #fff;
      border-radius: 10px;
      border: none;
      position: relative;
      margin-bottom: 30px;
      box-shadow: 0 0.46875rem 2.1875rem rgba(90, 97, 105, 0.1), 0 0.9375rem 1.40625rem rgba(90, 97, 105, 0.1), 0 0.25rem 0.53125rem rgba(90, 97, 105, 0.12), 0 0.125rem 0.1875rem rgba(90, 97, 105, 0.1);
    }

    .l-bg-cherry {
      background: linear-gradient(to right, #493240, #f09) !important;
      color: #fff;
    }

    .l-bg-blue-dark {
      background: linear-gradient(to right, #373b44, #4286f4) !important;
      color: #fff;
    }

    .l-bg-green-dark {
      background: linear-gradient(to right, #0a504a, #38ef7d) !important;
      color: #fff;
    }

    .l-bg-orange-dark {
      background: linear-gradient(to right, #a86008, #ffba56) !important;
      color: #fff;
    }

    .card .card-statistic-3 .card-icon-large .bi {
      font-size: 100px;
    }

    .card .card-statistic-3 .card-icon {
      text-align: center;
      line-height: 50px;
      margin-left: 15px;
      color: #000;
      position: absolute;
      right: 30px;
      top: 30px;
      opacity: 0.1;
    }

    @media (min-width: 768px) {
      .section-left {
        border-bottom: none;
        border-right: 1px solid #e0e0e0;
      }
    }
  </style>
@endsection

@section('body')
  <div class="bg-white p-4 main-container" style="min-height: 100%; min-width: 100%;">
    <div class="row">
      <!-- Sidebar -->
      <div class="col-md-4 section-left pb-4 pb-md-0">
        <div class="card shadow-sm mb-0">
          <div class="card-body">
            <div class="d-flex flex-column align-items-center text-center position-relative">
              <div class="position-relative d-inline-block" id="photo-container">
                <img id="profile-photo" class="rounded-circle mt-2" style="width: 120px; height: 120px;"
                  alt="Profile Photo">
                <button type="button" id="change-photo-btn" class="btn btn-light position-absolute"
                  style="bottom: 0; right: 0; width: 30px; height: 30px; border-radius: 50%; display: flex; justify-content: center; align-items: center;">
                  <i class="bi bi-pencil"></i>
                </button>
                <input type="file" id="photo-profile" class="d-none" accept="image/*">
              </div>

              <div id="photo-preview-container" class="mt-2 d-none">
                <img id="photo-preview" class="rounded-circle" style="width: 120px; height: 120px;"
                  alt="New Photo Preview">
                <div class="mt-2">
                  <button type="button" class="btn btn-success btn-sm" id="confirm-photo-change">Save</button>
                  <button type="button" class="btn btn-danger btn-sm" id="cancel-photo-change">Cancel</button>
                </div>
              </div>

              <div class="mt-3">
                <h5 class="font-bold text-dark mb-0" id="fullname-side">-</h5>
                <p class="text-muted mb-3" id="email-side">-</p>
                <p class="text-muted mb-2" id="status">-</p>
              </div>
            </div>

            <div class="d-flex gap-2 justify-content-center">
              <span id="last-contract" class="badge bg-secondary text-white px-3 py-2">
                Contract
              </span>
              <span id="last-career" class="badge bg-secondary text-white px-3 py-2">
                Career
              </span>
            </div>

            <!-- Export Section -->
            <form id="export-form" method="GET" class="mt-2">
              <button id="export-pdf"
                class="btn btn-primary btn-sm d-flex justify-content-center align-items-center gap-2 m-auto">
                <i class="bi bi-download"></i> Export PDF
              </button>
            </form>
          </div>
        </div>
      </div>

      <!-- Main Content -->
      <div class="col-md-8 ps-0 ps-md-4 mt-4 mt-md-0 section-right">
        <ul class="nav nav-tabs" id="profileTabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button"
              role="tab" aria-controls="general" aria-selected="true">
              Form
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button"
              role="tab" aria-controls="history" aria-selected="false">
              {{ $user->role->name === 'HRGA' ? 'All Request' : 'Request History' }}
            </button>
          </li>
        </ul>

        <div class="tab-content" id="profileTabsContent">
          <!-- Form -->
          @require('form-tab')

          <!-- History Request -->
          <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">
            <div class="row" id="history-cards-container">
              <!-- Dynamic Cards Will Be Inserted Here -->
            </div>
            <div class="text-center mt-3">
              <button id="load-more-btn" class="btn btn-primary">Load More</button>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- Modal History -->
  <div class="modal fade" id="actionModal" tabindex="-1" aria-labelledby="actionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="actionModalLabel">Action</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div id="modal-content"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="modal-close-btn">Close</button>
          <button type="button" id="modal-save-btn" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>


  <script>
    $(document).ready(function() {
      const user = @json($user);
      const organizations = @json($organizations);
      let isEditModeRequest = false;
      let idRequestHistory = null;
      let employeeData = null;
      let photoProfile = null;
      let organizationSelected = null;
      let allHistoryData = [];
      let displayedCount = 0;
      const loadLimit = 6;

      $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
        $('.tab-pane').removeClass('show active');
        $('.nav-link').removeClass('active');

        const target = $(e.target).data('bs-target');
        $(target).addClass('show active');
        $(e.target).addClass('active');

        if (target === '#history') {
          if (isEditModeRequest) {
            updateProfileDisplay(employeeData);
            updateProfileForm(employeeData);
          }
          isEditModeRequest = false;
        }
      });

      function formatDate(dateString) {
        const date = new Date(dateString);
        const month = String(date.getMonth() + 1).padStart(2, "0");
        const day = String(date.getDate()).padStart(2, "0");
        const year = date.getFullYear();
        return `${month}/${day}/${year}`;
      }

      function updateProfileDisplay(response) {
        const urlPhoto = response.photo_id ?
          "{{ route('file', ':id') }}".replace(':id', response.photo_id) :
          "{{ asset('/images/nouser.png') }}";

        $('#fullname-side').text(response.fullname);
        $('#email-side').text(response.email);
        $('#profile-photo').attr('src', urlPhoto);

        if (response.last_contract_id) {
          $('#last-contract').text(response.last_contract.status.name);
          $('#last-contract').show();
        } else {
          $('#last-contract').hide();
        }

        if (response.last_career_id) {
          $('#last-career').text(response.last_career.career.name);
          $('#last-career').show();
        } else {
          $('#last-career').hide();
        }

        let statusHtml = getContractStatusHtml(response.last_contract);
        $('#status').html(statusHtml);
      }

      function getContractStatusHtml(contract) {
        let statusHtml = '';
        if (!contract || !contract.end_date) {
          statusHtml = `
            <div style="background-color: red; padding: 5px; border-radius: 3px; color: white; font-weight: bold; display: flex; justify-content: center; align-items: center;" title="No contract">
                INACTIVE
            </div>`;
        } else if (contract.status.name === 'PERMANENT') {
          statusHtml = `
            <div style="background-color: green; padding: 5px; border-radius: 3px; color: white; font-weight: bold; display: flex; justify-content: center; align-items: center;" title="Permanent contract">
                ACTIVE
            </div>`;
        } else if (['RETIREMENT', 'RESIGN', 'TERMINATE'].includes(contract.status.name)) {
          statusHtml = `
            <div style="background-color: red; padding: 5px; border-radius: 3px; color: white; font-weight: bold; display: flex; justify-content: center; align-items: center;" title="${contract.status.name} status">
                INACTIVE
            </div>`;
        } else {
          statusHtml = calculateContractExpirationStatus(contract);
        }
        return statusHtml;
      }

      function calculateContractExpirationStatus(contract) {
        const today = new Date();
        const endDate = new Date(contract.end_date);
        const timeDifference = endDate - today;
        const daysRemaining = Math.floor(timeDifference / (1000 * 60 * 60 * 24));

        let backgroundColor = 'red';
        let statusText = 'INACTIVE';
        let tooltip = 'Contract expired on ' + contract.end_date;

        if (endDate >= today) {
          statusText = 'ACTIVE';
          if (daysRemaining <= 30) {
            backgroundColor = 'yellow';
            tooltip = 'Expires in ' + daysRemaining + ' days';
          } else {
            backgroundColor = 'green';
            tooltip = 'Contract active for ' + daysRemaining + ' days';
          }
        }

        return `
            <div style="background-color: ${backgroundColor}; padding: 5px; border-radius: 3px; color: white; font-weight: bold; display: flex; justify-content: center; align-items: center;"
                title="${tooltip}">
                ${statusText}
            </div>
        `;
      }

      function updateProfileForm(response) {
        $('#nik').val(response.nik);
        $('#fullname').val(response.fullname);
        $('#nickname').val(response.nickname);
        $('#join_date').val(response.join_date);
        $('#company_id').val(response.company_id).trigger('change');
        updateOrganizationsTree(response.company_id);
        $('#placement_id').val(response.placement_id).trigger('change');
        $('#division_id').val(response.division_id).trigger('change');
        $('#phone').val(response.phone);
        $('#email').val(response.email);
        $('#leave_saldo').val(response.leave_saldo);
        $('#birth_place').val(response.birth_place);
        $('#birth_date').val(response.birth_date);
        $('#gender_id').val(response.gender_id);
        $('#marital_id').val(response.marital_id);
        $('#religion_id').val(response.religion_id);
        $('#address').val(response.address);
        $('#address_city_id').val(response.address_city_id);
        $('#address_province_id').val(response.address_province_id);
        $('#address_permanent').val(response.address_permanent);
        $('#address_permanent_city_id').val(response.address_permanent_city_id);
        $('#address_permanent_province_id').val(response.address_permanent_province_id);
        $('#bank_id').val(response.bank_id);
        $('#bank_account').val(response.bank_account);
        $('#emergency_relation_id').val(response.emergency_relation_id);
        $('#emergency_contact_name').val(response.emergency_contact_name);
        $('#emergency_contact_phone').val(response.emergency_contact_phone);
        $('#emergency_contact_address').val(response.emergency_contact_address);
      }

      function fetchProfileData() {
        showLoading();
        $.ajax({
          url: "{{ route('employee.get') }}" + '/' + user.employ_id,
          type: 'GET',
          success: function(response) {
            employeeData = response;
            updateProfileDisplay(response);
            updateProfileForm(response);
          },
          error: function() {
            console.error('Failed to fetch profile data.');
          },
          complete: function() {
            hideLoading();
          }
        });
      }

      fetchProfileData();

      function fetchHistoryCards() {
        showLoading();

        $.ajax({
          url: "{{ route('employee.request.data', ':id') }}".replace(':id', user.employ_id),
          type: "GET",
          data: {
            trash: 1
          },
          success: function(response) {
            allHistoryData = response.data;
            displayedCount = 0;
            $('#history-cards-container').empty();
            const lastDataRequestEmployee = response.data.filter(request =>
              request.employ_id === user.employ_id && request.approved_by === null
            )[0];

            if (lastDataRequestEmployee) {
              $('#btn-request-change').attr('disabled', true).text('On Request');
            } else {
              $('#btn-request-change').attr('disabled', false).text('Request Change');
            }

            renderHistoryCards();
          },
          error: function() {
            console.error("Failed to fetch history cards.");
          },
          complete: function() {
            hideLoading();
          },
        });
      }

      function renderHistoryCards() {
        const container = $("#history-cards-container");
        const loadMoreButton = $("#load-more-btn");

        if (allHistoryData.length === 0) {
          container.html(`
            <div class="card-body">
                <div class="d-flex flex-column justify-content-center align-items-center gap-2">
                  <img src="{{ asset('/images/nodata.png') }}" alt="No Request Found" class="img-fluid" style="max-width: 300px; max-height: 300px;" />
                  <h3 class="text-center font-bold text-black mb-0">No Request Found</h3>
                </div>
              </div>
          `);
          loadMoreButton.hide();
          return;
        }

        const dataToDisplay = allHistoryData.slice(displayedCount, displayedCount + loadLimit);
        displayedCount += dataToDisplay.length;

        dataToDisplay.forEach((item) => {
          let cardClass = "";
          let iconHTML = "";
          let statusText = "";

          if (item.approved_status === 0 || item.approved_status === '0') {
            cardClass = "l-bg-cherry";
            iconHTML = '<i class="bi bi-x-circle-fill"></i>';
            statusText = "Rejected";
          } else if (item.approved_status === 1 || item.approved_status === '1') {
            cardClass = "l-bg-green-dark";
            iconHTML = '<i class="bi bi-check-circle-fill"></i>';
            statusText = "Approved";
          } else if (item.approved_status === null || item.approved_status === 'null') {
            cardClass = "l-bg-orange-dark";
            iconHTML = '<i class="bi bi-exclamation-circle-fill"></i>';
            statusText = "Pending";
          }

          const actionButtons = `
              <div class="d-flex justify-content-end mt-3">
                ${user.role.name === "HRGA" || user.id === item.created_by ?
                  `<button class="btn btn-primary btn-sm me-2" onclick="openModal('detail', '${item.id}')">Detail</button>`
                  : ""}
                ${user.role.name === "HRGA" && item.approved_status === null ?
                  `<button class="btn btn-success btn-sm me-2" onclick="openModal('approve', '${item.id}')">Approve</button>`
                  : ""}
                ${user.role.name === "HRGA" && item.approved_status === null && user.id !== item.created_by ?
                `<button class="btn btn-danger btn-sm me-2" onclick="openModal('reject', '${item.id}')">Reject</button>`
                : ""}
                ${user.id === item.created_by && item.approved_status === null ?
                  `<button class="btn btn-warning btn-sm me-2" onclick="openModal('edit', '${item.id}')">Edit</button>`
                  : ""}
                ${user.id === item.created_by ?
                  `<button class="btn btn-danger btn-sm" onclick="openModal('delete', '${item.id}')">Delete</button>`
                  : ""}
              </div>
          `;

          const cardHTML = `
          <div class="col-md-6 pt-4">
            <div class="card ${cardClass} mb-0">
              <div class="card-statistic-3 p-4">
                <div class="card-icon card-icon-large">
                  ${iconHTML}
                </div>
                <div class="mb-4">
                  <h5 class="card-title mb-0">${item.employ.fullname}</h5>
                  <span class="badge bg-white text-black mt-2">${statusText}</span>
                </div>
                <div class="row align-items-center mb-2 d-flex">
                  <div class="col-8">
                    <h6 class="mb-0 text-white text-sm">Created At: ${formatDate(item.created_at)}</h6>
                    <h6 class="mb-0 text-white text-sm ${item.approved_by ? "" : "opacity-0"}">
                        ${item.approved_status === 1 || item.approved_status === '1' ? `Approved By: ${item.approved_by?.name}` : "Rejected By: " + item.approved_by?.name}
                    </h6>
                  </div>
                </div>
                ${actionButtons}
              </div>
            </div>
          </div>`;

          container.append(cardHTML);

          const cardElement = container.find(".card").last().addClass("fade-in");
          cardElement.on("animationend", function() {
            $(this).removeClass("fade-in");
          });

        });

        if (displayedCount >= allHistoryData.length) {
          loadMoreButton.hide();
        } else {
          loadMoreButton.show();
        }
      }

      window.openModal = function(action, id) {
        const selectedData = allHistoryData.filter((item) => item.id === id)[0];
        const modalContent = $("#modal-content");
        const modalTitle = $("#actionModalLabel");
        const modalSaveBtn = $("#modal-save-btn");
        const modalCloseBtn = $("#modal-close-btn");

        let contentHTML = "";
        idRequestHistory = id;

        switch (action) {
          case "detail":
            modalTitle.text("Detail Request");
            modalSaveBtn.hide();

            let statusText = "";
            let noteText = "";

            if (selectedData.approved_status === 1 || selectedData.approved_status === '1') {
              statusText = "Approved";
            } else if (selectedData.approved_status === 0 || selectedData.approved_status === '0') {
              statusText = "Rejected";
            } else {
              statusText = "Pending";
            }

            noteText = selectedData.approved_note || "-";

            const emp = selectedData.employ || {};
            const normStr = (v) => (v === null || v === undefined ? '' : String(v).trim());
            const normDate = (v) => (v ? String(v).substring(0, 10) : '');

            const createRow = (label, reqVal, empVal, isDate = false, isRel = false, relOldText = '') => {
              let changed = false;
              let oldTxt = '';
              if (isDate) {
                changed = normDate(reqVal) !== normDate(empVal);
                oldTxt = normDate(empVal);
              } else if (isRel) {
                changed = normStr(reqVal) !== normStr(empVal);
                oldTxt = relOldText || normStr(empVal);
              } else {
                changed = normStr(reqVal) !== normStr(empVal);
                oldTxt = normStr(empVal);
              }

              if (changed) {
                const diffBadge = `<span class="badge bg-warning text-dark ms-2">DIUBAH</span> <span class="text-muted small ms-1">(Sebelumnya: <b>${oldTxt || 'kosong'}</b>)</span>`;
                return `<tr class="table-warning"><th>${label}</th><td><strong class="text-dark">${reqVal ?? '-'}</strong> ${diffBadge}</td></tr>`;
              }
              return `<tr><th>${label}</th><td>${reqVal ?? '-'}</td></tr>`;
            };

            contentHTML = `
              <div class="table-responsive">
                <table class="table table-bordered mb-0">
                  ${createRow('NIK', selectedData.nik, emp.nik)}
                  ${createRow('Fullname', selectedData.fullname, emp.fullname)}
                  ${createRow('Nickname', selectedData.nickname, emp.nickname)}
                  ${createRow('Birth Place', selectedData.birth_place, emp.birth_place)}
                  ${createRow('Birth Date', selectedData.birth_date, emp.birth_date, true)}
                  ${createRow('Phone', selectedData.phone, emp.phone)}
                  ${createRow('Email', selectedData.email, emp.email)}
                  ${createRow('Gender', selectedData.gender?.name, emp.gender_id, false, true, emp.gender?.name)}
                  ${createRow('Marital Status', selectedData.marital?.name, emp.marital_id, false, true, emp.marital?.name)}
                  ${createRow('Religion', selectedData.religion?.name, emp.religion_id, false, true, emp.religion?.name)}
                  ${createRow('Join Date', selectedData.join_date, emp.join_date, true)}
                  ${createRow('Leave Balance', selectedData.leave_saldo, emp.leave_saldo)}
                  ${createRow('Address', selectedData.address, emp.address)}
                  ${createRow('City', selectedData.address_city?.city, emp.address_city_id, false, true, emp.address_city?.city)}
                  ${createRow('Province', selectedData.address_province?.province, emp.address_province_id, false, true, emp.address_province?.province)}
                  ${createRow('Permanent Address', selectedData.address_permanent, emp.address_permanent)}
                  ${createRow('Permanent City', selectedData.address_permanent_city?.city || "-", emp.address_permanent_city_id, false, true, emp.address_permanent_city?.city)}
                  ${createRow('Permanent Province', selectedData.address_permanent_province?.province || "-", emp.address_permanent_province_id, false, true, emp.address_permanent_province?.province)}
                  ${createRow('Bank', selectedData.bank?.name, emp.bank_id, false, true, emp.bank?.name)}
                  ${createRow('Bank Account', selectedData.bank_account, emp.bank_account)}
                  ${createRow('Emergency Relation', selectedData.emergency_relation?.name, emp.emergency_relation_id, false, true, emp.emergency_relation?.name)}
                  ${createRow('Emergency Contact Name', selectedData.emergency_contact_name, emp.emergency_contact_name)}
                  ${createRow('Emergency Contact Phone', selectedData.emergency_contact_phone, emp.emergency_contact_phone)}
                  ${createRow('Emergency Contact Address', selectedData.emergency_contact_address, emp.emergency_contact_address)}
                  <tr><th>Status</th><td><span class="badge ${selectedData.approved_status === 1 || selectedData.approved_status === '1' ? 'bg-success' : selectedData.approved_status === 0 || selectedData.approved_status === '0' ? 'bg-danger' : 'bg-warning text-dark'}">${statusText}</span></td></tr>
                  <tr><th>Note</th><td>${noteText}</td></tr>
                  <tr><th>Created At</th><td>${formatDate(selectedData.created_at)}</td></tr>
                </table>
              </div>
            `;
            break;
          case "approve":
            modalTitle.text("Approve Request");
            modalSaveBtn.show();
            modalSaveBtn.text('Approve Request');
            contentHTML = `
                <textarea id="note" class="form-control" placeholder="Note" rows="3"></textarea>
            `;
            break;
          case "reject":
            modalTitle.text("Reject Request");
            modalSaveBtn.show();
            modalSaveBtn.text('Reject Request');
            contentHTML = `
                <textarea id="note" class="form-control" placeholder="Note" rows="3"></textarea>
            `;
            break;
          case "edit":
            isEditModeRequest = true;
            $('#btn-request-change').attr('disabled', false).text('Save Changes');

            if (selectedData.photo_id) {
              const urlPhoto = "{{ route('file', ':id') }}".replace(':id', selectedData.photo_id);
              $('#profile-photo').attr('src', urlPhoto);
            }

            $('#nik').val(selectedData.nik || '');
            $('#fullname').val(selectedData.fullname || '');
            $('#nickname').val(selectedData.nickname || '');
            $('#join_date').val(selectedData.join_date || '');
            $('#company_id').val(selectedData.company_id).trigger('change');
            $('#placement_id').val(selectedData.placement_id).trigger('change');
            $('#division_id').val(selectedData.division_id).trigger('change');
            $('#phone').val(selectedData.phone || '');
            $('#email').val(selectedData.email || '');
            $('#leave_saldo').val(selectedData.leave_saldo || '');
            $('#birth_place').val(selectedData.birth_place || '');
            $('#birth_date').val(selectedData.birth_date || '');
            $('#gender_id').val(selectedData.gender_id).trigger('change');
            $('#marital_id').val(selectedData.marital_id).trigger('change');
            $('#religion_id').val(selectedData.religion_id).trigger('change');
            $('#address').val(selectedData.address || '');
            $('#address_city_id').val(selectedData.address_city_id).trigger('change');
            $('#address_province_id').val(selectedData.address_province_id).trigger('change');
            $('#address_permanent').val(selectedData.address_permanent || '');
            $('#address_permanent_city_id').val(selectedData.address_permanent_city_id).trigger('change');
            $('#address_permanent_province_id').val(selectedData.address_permanent_province_id).trigger('change');
            $('#bank_id').val(selectedData.bank_id).trigger('change');
            $('#bank_account').val(selectedData.bank_account || '');
            $('#emergency_relation_id').val(selectedData.emergency_relation_id).trigger('change');
            $('#emergency_contact_name').val(selectedData.emergency_contact_name || '');
            $('#emergency_contact_phone').val(selectedData.emergency_contact_phone || '');
            $('#emergency_contact_address').val(selectedData.emergency_contact_address || '');
            break;
          case "delete":
            modalTitle.text("Delete Request");
            modalSaveBtn.show();
            modalSaveBtn.text('Delete Request');
            contentHTML = `
                <p class="mb-0">Are you sure you want to delete this request?</p>
            `;
            break;
        }

        modalContent.html(contentHTML);
        if (isEditModeRequest) {
          $('button[data-bs-toggle="tab"][data-bs-target="#general"]').tab('show');
        } else {
          $("#actionModal").modal("show");
        }
      }

      $('#modal-save-btn').on('click', function() {
        const action = $("#actionModalLabel").text().toLowerCase();
        const selectedData = allHistoryData.filter(item => item.id === idRequestHistory)[0];

        if (action === "approve request") {
          $.ajax({
            url: "{{ route('employee.request.approve') }}",
            type: 'POST',
            data: {
              id: selectedData.id,
              note: $('#note').val(),
              _token: '{{ csrf_token() }}',
              _method: 'PUT'
            },
            success: function(response) {
              showAlert('success', 'Request approved!');
              fetchProfileData();
              fetchHistoryCards();
              $('#actionModal').modal('hide');
            },
            error: function() {
              console.error("Failed to approve request.");
            }
          });
        } else if (action === "reject request") {
          $.ajax({
            url: "{{ route('employee.request.reject') }}",
            type: 'POST',
            data: {
              id: selectedData.id,
              note: $('#note').val(),
              _token: '{{ csrf_token() }}',
              _method: 'PUT'
            },
            success: function(response) {
              showAlert('success', 'Request rejected!');
              fetchHistoryCards();
              $('#actionModal').modal('hide');
            },
            error: function() {
              console.error("Failed to reject request.");
            }
          });
        } else if (action === "delete request") {
          $.ajax({
            url: "{{ route('employee.request.delete') }}",
            type: 'POST',
            data: {
              id: selectedData.id,
              _token: '{{ csrf_token() }}',
              _method: 'DELETE'
            },
            success: function(response) {
              showAlert('success', 'Request deleted!');
              fetchHistoryCards();
              $('#actionModal').modal('hide');
            },
            error: function() {
              console.error("Failed to delete request.");
            }
          });
        }
      });

      $("#load-more-btn").on("click", renderHistoryCards);

      fetchHistoryCards();

      $('#general-form').on('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        if (photoProfile) {
          formData.append('fileInputGeneral', photoProfile);
        }

        if (organizationSelected) {
          formData.append('org_id', organizationSelected);
        }

        formData.append('employ_id', user.employ_id);

        let url, messageSuccess, messageError;

        // if (user.role.name === "HRGA") {
        //   url = '{{ route('employee.update') }}';
        //   formData.append('_method', 'PUT');
        //   formData.append('id', user.employ_id);
        //   messageSuccess = 'Profile updated successfully.';
        //   messageError = 'Failed to update profile.';
        // } else {
        const isUpdateRequest = isEditModeRequest;
        url = isUpdateRequest ?
          '{{ route('employee.request.update') }}' :
          '{{ route('employee.request.create') }}';

        formData.append('_method', isUpdateRequest ? 'PUT' : 'POST');
        if (isUpdateRequest) {
          formData.append('id', idRequestHistory);
        }

        messageSuccess = isUpdateRequest ? 'Request updated successfully.' : 'Request created successfully.';
        messageError = isUpdateRequest ? 'Failed to update request.' : 'Failed to create request.';
        // }

        $.ajax({
          url: url,
          method: 'POST',
          data: formData,
          contentType: false,
          processData: false,
          success: function() {
            showAlert('success', messageSuccess);
            fetchProfileData();
            fetchHistoryCards();

            photoProfile = null;
            organizationSelected = null;
            isEditModeRequest = false;
            idRequestHistory = null;

            $('#btn-request-change').attr('disabled', true).text('On Request');
          },
          error: function(xhr) {
            const errorMessage = xhr.responseJSON?.message || messageError;
            showAlert('danger', errorMessage);
          },
        });
      });


      $('#company_id').on('change', function() {
        const selectedCompanyId = $(this).val();
        updateOrganizationsTree(selectedCompanyId);
      });

      function updateOrganizationsTree(companyId) {
        const filteredOrganizations = organizations.filter(item => item.company_id == companyId);

        const jstreeData = filteredOrganizations.length > 0 ?
          filteredOrganizations.map(item => ({
            id: item.id || "#",
            parent: item.parent_id || "#",
            text: item.name || "Unknown"
          })) : [];

        $('#org_id').jstree("destroy").empty();

        $('#org_id').jstree({
          core: {
            data: jstreeData,
            check_callback: true
          },
          plugins: ["wholerow"]
        }).on('ready.jstree', function(e, data) {
          const jstreeInstance = $(this).jstree(true);
          jstreeInstance.close_all();
          if (employeeData.org_id) {
            jstreeInstance.select_node(employeeData.org_id);
            jstreeInstance.open_node(employeeData.org_id);
            organizationSelected = employeeData.org_id;
          }
        }).on('select_node.jstree', function(e, data) {
          const selectedNode = data.node.text;
          organizationSelected = data.node.id;
        }).on('error.jstree', function(e, error) {
          console.error('jstree error:', error);
        });
      }

      $('#change-photo-btn').on('click', function() {
        $('#photo-profile').trigger('click');
      });

      $('#photo-profile').on('change', function() {
        const file = this.files[0];

        if (file) {
          const reader = new FileReader();

          reader.onload = function(e) {
            $('#photo-preview').attr('src', e.target.result);
            $('#photo-preview-container').removeClass('d-none');
            $('#photo-container').addClass('d-none');
          };

          reader.readAsDataURL(file);
        }
      });

      $('#confirm-photo-change').on('click', function() {
        const formData = new FormData();
        const file = $('#photo-profile')[0].files[0];

        if (!file) {
          showAlert('danger', 'Please select a file.');
          return;
        }

        photoProfile = file;

        $('#photo-preview-container').addClass('d-none');
        $('#photo-container').removeClass('d-none');
        showAlert('info', 'Please click button request change, to process your uploaded photo.');
      });

      $('#cancel-photo-change').on('click', function() {
        $('#photo-preview-container').addClass('d-none');
        $('#photo-profile').val('');
        $('#photo-container').removeClass('d-none');
      });

      $('#export-form').on('submit', function(e) {
        e.preventDefault();

        const userId = user.employ_id;

        if (!userId) {
          showAlert('danger', 'User ID not found.');
          return;
        }

        const downloadUrl = '{{ route('employee.request.export.pdf', ':id') }}'.replace(':id', userId);
        window.location.href = downloadUrl;
      });
    });
  </script>
@endsection
