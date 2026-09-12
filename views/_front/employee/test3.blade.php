@extends('templates.mobile')

@section('head')
  <style>
    /* Profile */
    .profile-container {
      background-color: var(--background-color);
      padding-bottom: 112px;
      min-height: 100vh;
      height: 100%;
    }

    .profile-top {
      position: relative;
      display: flex;
      align-items: center;
      gap: 16px;
      padding: 12px 16px;
      background-color: var(--primary-color);
      color: white;
      min-height: 170px;
    }

    .profile-back {
      position: absolute;
      top: 36%;
      left: 0;
      padding-left: 20px;
    }

    .back-button {
      background: none;
      border: none;
      color: white;
      font-size: 20px;
      cursor: pointer;
    }

    .profile-title {
      color: var(--white);
      font-size: 24px;
      font-weight: 500;
    }

    .profile-avatar-section {
      position: absolute;
      top: 36%;
      left: 50%;
      transform: translateX(-50%);
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 8px;
    }

    .profile-avatar {
      width: 120px;
      height: 120px;
      border: 2px solid white;
      border-radius: 12px;
      object-fit: cover;
    }

    .profile-avatar-wrapper {
      position: relative;
      width: 120px;
      height: 120px;
    }

    .edit-avatar-icon {
      position: absolute;
      bottom: -5px;
      right: -5px;
      background-color: var(--primary-color);
      color: white;
      border-radius: 50%;
      padding: 8px;
      font-size: 16px;
      cursor: pointer;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .check-avatar-icon {
      position: absolute;
      bottom: -5px;
      right: -5px;
      background-color: var(--green-color);
      color: white;
      border-radius: 50%;
      padding: 8px;
      font-size: 16px;
      cursor: pointer;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }


    .profile-info {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 4px;
    }

    .profile-wrapper {
      display: flex;
      gap: 8px;
      align-items: center;
    }

    .profile-name {
      color: var(--dark);
      font-size: 20px;
      font-weight: 700;
      margin-bottom: 0px;
    }

    .profile-badge {
      font-size: 20px;
      color: var(--secondary-color);
    }

    .profile-role {
      font-size: 14px;
      font-weight: 500;
      color: var(--secondary-color);
    }

    .profile-nav {
      margin: 140px 20px;
      display: flex;
      justify-content: space-evenly;
      border: 1px solid var(--gray-medium);
      border-radius: var(--radius-xl);
      margin-bottom: 16px;
      overflow-x: auto;
      background-color: var(--white);
      padding: 8px;
    }

    .nav-item {
      padding: 12px 24px;
      color: var(--dark);
      cursor: pointer;
      background: none;
      border: none;
      text-align: center;
      font-size: 16px;
    }

    .nav-item.active {
      background-color: var(--primary-color);
      color: var(--white);
      border-radius: var(--radius-xl);
    }

    .tab-container {
      margin: 20px 20px 0 20px;
    }

    .profile-field {
      margin-bottom: 16px;
    }

    .field-label {
      font-size: 14px;
      color: var(--gray-medium);
      margin-bottom: 4px;
    }

    .field-input {
      width: 100%;
      font-size: 16px;
      padding: 8px 16px;
      background-color: var(--white);
      border: 1px solid var(--gray-medium);
      color: var(--dark);
      border-radius: 8px;
      outline: 1px solid var(--primary-color);
    }

    .field-input-disabled {
      background: var(--gray-light);
      color: var(--dark);
      pointer-events: none;
    }

    .field-input:focus {
      border: var(--primary-color);
    }

    .field-input:disabled {
      background: var(--gray-light);
      color: var(--dark);
    }

    input[type="date"] {
      display: block;
      -webkit-appearance: textfield;
      -moz-appearance: textfield;
      min-height: 1.2em;
      padding: 6px 12px;
      min-width: 95%;
      width: 100%;
    }

    /* History */
    .history-card {
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      margin-bottom: 1rem;
      background: white;
      position: relative;
    }

    .history-card-title {
      border-left: 4px solid var(--primary-color);
      padding-left: 20px;
    }

    .status-badge {
      font-size: 0.8rem;
      padding: 0.25rem 0.75rem;
      border-radius: 999px;
      display: inline-flex;
      align-items: center;
      gap: 0.25rem;
      font-weight: 500;
    }

    .status-badge.approved {
      background-color: var(--green-light);
      color: var(--green-color);
    }

    .status-badge.rejected {
      background-color: var(--red-light);
      color: var(--red-color);
    }

    .status-badge.pending {
      background-color: var(--yellow-light);
      color: var(--yellow-color);
    }

    .meta-info {
      font-size: 0.875rem;
      color: var(--gray-medium);
    }

    .meta-info i {
      margin-right: 0.25rem;
    }

    .action-dots {
      cursor: pointer;
      padding: 0.5rem;
      border-radius: 50%;
      transition: background-color 0.2s;
    }

    .action-dots:hover {
      background-color: var(--gray-light);
    }

    .dot {
      width: 4px;
      height: 4px;
      background-color: var(--gray-medium);
      border-radius: 50%;
      margin: 2px 0;
    }

    .dropdown-menu {
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
  </style>
@endsection

@section('content')
  <div class="profile-container">
    <div class="profile-top">
      <div class="profile-back">
        <form action="{{ url()->previous() }}" method="GET">
          <button type="submit" class="back-button">
            <i class="fas fa-chevron-left"></i>
          </button>
        </form>
      </div>

      <div class="profile-avatar-section">
        <span class="profile-title">Profile</span>
        @if ($user->employee->photo_id)
          @php
            $urlFile = route('file', $user->employee->photo_id);
          @endphp
          <div class="profile-avatar-wrapper">
            <img src="{{ $urlFile }}" alt="Profile" class="profile-avatar">
            <i class="edit-avatar-icon fas fa-pencil-alt"></i>
            <i class="check-avatar-icon fas fa-check" style="display: none;"></i>
            <input type="file" class="avatar-input" accept="image/*" style="display: none;">
          </div>
        @else
          <div class="profile-avatar-wrapper">
            <img src="{{ asset('/images/image-no-user.png') }}" alt="no-user" class="profile-avatar">
            <i class="edit-avatar-icon fas fa-pencil-alt"></i>
            <i class="check-avatar-icon fas fa-check" style="display: none;"></i>
            <input type="file" class="avatar-input" accept="image/*" style="display: none;">
          </div>
        @endif
        <div class="profile-info">
          <div class="profile-wrapper">
            <h3 class="profile-name"> {{ strtoupper($user->name) ?? 'User Name' }} </h3>
            <i class="profile-badge fas fa-square-check"></i>
          </div>
          <span class="profile-role">{{ $user->role->name ?? '' }}</span>
        </div>
      </div>

    </div>

    <div class="profile-nav">
      <button class="nav-item active" data-tab="profile">Profile</button>
      <button class="nav-item" data-tab="history">History</button>
      <button class="nav-item" data-tab="settings">Settings</button>
    </div>

    <div id="tab-container" class="tab-container">
      <div id="tab-content">
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      let selectedFile = null;
      let previousImage = '';
      const user = @json($user);

      $('.nav-item').on('click', function() {
        const tab = $(this).data('tab');
        $('.nav-item').removeClass('active');
        $(this).addClass('active');

        if (tab === 'profile') {
          loadProfileForm();
        } else if (tab === 'history') {
          loadHistoryRequestForm();
        } else if (tab === 'settings') {
          loadSettingsForm();
        }
      });

      // Handle file input change
      $('.edit-avatar-icon').on('click', function() {
        const $input = $(this).siblings('.avatar-input');
        $input.click();
      });

      $('.avatar-input').on('change', function(event) {
        const file = event.target.files[0];
        if (file) {
          selectedFile = file;
          previousImage = $(this).siblings('.profile-avatar').attr('src');

          const reader = new FileReader();
          reader.onload = (e) => {
            $(this).siblings('.profile-avatar').attr('src', e.target.result);
            $(this).siblings('.check-avatar-icon').show();
            $(this).siblings('.edit-avatar-icon').css('right', '30px');
          };
          reader.readAsDataURL(file);
        }
      });

      $('.check-avatar-icon').on('click', function() {
        const $avatar = $(this).siblings('.profile-avatar');

        if (selectedFile) {
          $avatar.attr('src', previousImage);
          $(this).siblings('.edit-avatar-icon').css('right', '-5px');
          $(this).hide();
          showAlert('success', 'Click the request change button to confirm the new avatar.');
        } else {
          $avatar.attr('src', previousImage);
        }
      });

      $('.edit-avatar-icon').on('click', function() {
        const $checkIcon = $(this).siblings('.check-avatar-icon');
        if ($checkIcon.is(':visible')) {
          $checkIcon.hide();
        }
      });


      // Profile
      function loadProfileForm() {
        $('#tab-content').html(`
            <form id="profileForm">
                @csrf
                <div class="profile-field">
                    <div class="field-label">Full Name</div>
                    <input type="text" class="field-input" name="fullname" required>
                </div>
                <div class="profile-field">
                    <div class="field-label">NIK</div>
                    <input type="text" class="field-input field-input-disabled" name="nik">
                </div>
                <div class="profile-field">
                    <div class="field-label">Nickname</div>
                    <input type="text" class="field-input" name="nickname">
                </div>
                <div class="profile-field">
                    <div class="field-label">Join Date</div>
                    <input type="date" class="field-input field-input-disabled" name="join_date">
                </div>
                <div class="profile-field">
                    <div class="field-label">Company</div>
                    <select class="field-input field-input-disabled" name="company_id"></select>
                </div>
                <div class="profile-field">
                    <div class="field-label">Organization</div>
                    <select class="field-input field-input-disabled" name="org_id"></select>
                </div>
                <div class="profile-field">
                    <div class="field-label">Placement</div>
                    <select class="field-input field-input-disabled" name="placement_id"></select>
                </div>
                <div class="profile-field">
                    <div class="field-label">Division</div>
                    <select class="field-input field-input-disabled" name="division_id"></select>
                </div>
                <div class="profile-field">
                    <div class="field-label">Leave Balance</div>
                    <input type="number" class="field-input field-input-disabled" name="leave_saldo">
                </div>
                <div class="profile-field">
                    <div class="field-label">Birth Place</div>
                    <input type="text" class="field-input" name="birth_place">
                </div>
                <div class="profile-field">
                    <div class="field-label">Birth Date</div>
                    <input type="date" class="field-input" name="birth_date">
                </div>
                <div class="profile-field">
                    <div class="field-label">Phone</div>
                    <input type="text" class="field-input" name="phone">
                </div>
                <div class="profile-field">
                    <div class="field-label">Email</div>
                    <input type="email" class="field-input" name="email">
                </div>
                <div class="profile-field">
                    <div class="field-label">Gender</div>
                    <select class="field-input" name="gender_id"></select>
                </div>
                <div class="profile-field">
                    <div class="field-label">Marital Status</div>
                    <select class="field-input" name="marital_id"></select>
                </div>
                <div class="profile-field">
                    <div class="field-label">Religion</div>
                    <select class="field-input" name="religion_id"></select>
                </div>
                <div class="profile-field">
                    <div class="field-label">Current Address</div>
                    <input type="text" class="field-input" name="address">
                </div>
                <div class="profile-field">
                    <div class="field-label">Current City</div>
                    <select class="field-input" name="address_city_id"></select>
                </div>
                <div class="profile-field">
                    <div class="field-label">Current Province</div>
                    <select class="field-input" name="address_province_id"></select>
                </div>
                <div class="profile-field">
                    <div class="field-label">Permanent Address</div>
                    <input type="text" class="field-input" name="address_permanent">
                </div>
                <div class="profile-field">
                    <div class="field-label">Permanent City</div>
                    <select class="field-input" name="address_permanent_city_id"></select>
                </div>
                <div class="profile-field">
                    <div class="field-label">Permanent Province</div>
                    <select class="field-input" name="address_permanent_province_id"></select>
                </div>
                <div class="profile-field">
                    <div class="field-label">Bank</div>
                    <select class="field-input" name="bank_id"></select>
                </div>
                <div class="profile-field">
                    <div class="field-label">Bank Account</div>
                    <input type="text" class="field-input" name="bank_account">
                </div>
                <div class="profile-field">
                    <div class="field-label">Emergency Relation</div>
                    <select class="field-input" name="emergency_relation_id"></select>
                </div>
                <div class="profile-field">
                    <div class="field-label">Emergency Relation Name</div>
                    <input type="text" class="field-input" name="emergency_contact_name">
                </div>
                <div class="profile-field">
                    <div class="field-label">Emergency Relation Phone</div>
                    <input type="text" class="field-input" name="emergency_contact_phone">
                </div>
                 <div class="profile-field">
                    <div class="field-label">Emergency Relation Address</div>
                    <input type="text" class="field-input" name="emergency_contact_address">
                </div>
                <button type="submit" class="mt-2 btn btn-primary">Request Change</button>
            </form>
        `);

        fetchProfileData();

        $('#profileForm').on('submit', function(e) {
          e.preventDefault();
          saveProfileData();
        });
      }

      function fetchProfileData() {
        showLoading();

        $.ajax({
          url: "{{ route('employee.get') }}" + '/' + user.employ_id,
          type: 'GET',
          success: async function(response) {
            const responseData = await fetchAllTableData();
            populateProfileForm(response, responseData);
          },
          error: function() {
            console.error('Failed to fetch profile data.');
          },
          complete: function() {
            hideLoading();
          }
        });
      }

      async function populateProfileForm(data, responseData) {
        $('input[name="fullname"]').val(data.fullname || '');
        $('input[name="nik"]').val(data.nik || '');
        $('input[name="nickname"]').val(data.nickname || '');
        $('input[name="join_date"]').val(data.join_date || '');
        $('select[name="company_id"]').html(generateOptions(responseData.companies, data.company_id));
        $('select[name="org_id"]').html(generateOptions(responseData.organizations, data.org_id));
        $('select[name="placement_id"]').html(generateOptions(responseData.placements, data.placement_id));
        $('select[name="division_id"]').html(generateOptions(responseData.divisions, data.division_id));
        $('input[name="leave_saldo"]').val(data.leave_saldo || '');
        $('input[name="birth_place"]').val(data.birth_place || '');
        $('input[name="birth_date"]').val(data.birth_date || '');
        $('input[name="phone"]').val(data.phone || '');
        $('input[name="email"]').val(data.email || '');
        $('select[name="gender_id"]').html(generateOptions(responseData.genders, data.gender_id));
        $('select[name="marital_id"]').html(generateOptions(responseData.maritals, data.marital_id));
        $('select[name="religion_id"]').html(generateOptions(responseData.religions, data.religion_id));
        $('input[name="address"]').val(data.address || '');
        $('select[name="address_city_id"]').html(generateOptions(responseData.cities, data.address_city_id));
        $('select[name="address_province_id"]').html(generateOptions(responseData.cities, data
          .address_province_id));
        $('input[name="address_permanent"]').val(data.address_permanent || '');
        $('select[name="address_permanent_city_id"]').html(generateOptions(responseData.cities, data
          .address_permanent_city_id));
        $('select[name="address_permanent_province_id"]').html(generateOptions(responseData.cities, data
          .address_permanent_province_id));
        $('select[name="bank_id"]').html(generateOptions(responseData.banks, data
          .bank_id));
        $('input[name="bank_account"]').val(data.bank_account || '');
        $('select[name="emergency_relation_id"]').html(generateOptions(responseData.emergencyRelations, data
          .emergency_relation_id));
        $('input[name="emergency_contact_name"]').val(data.emergency_contact_name || '');
        $('input[name="emergency_contact_phone"]').val(data.emergency_contact_phone || '');
        $('input[name="emergency_contact_address"]').val(data.emergency_contact_address || '');
      }

      function generateOptions(options, selectedValue) {
        return options.map(option => {
          const selected = option.id === selectedValue ? 'selected' : '';
          return `<option value="${option.id}" ${selected}>${option.name || option.city || option.province}</option>`;
        }).join('');
      }

      function saveProfileData() {
        const formData = new FormData($('#profileForm')[0]);
        formData.append('employ_id', user.employ_id);

        if (selectedFile) {
          formData.append('fileInputGeneral', selectedFile);
        }

        $.ajax({
          url: "{{ route('employee.request.create') }}",
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function(response) {
            showAlert('success', response.message || 'Data saved successfully.');
          },
          error: function() {
            showAlert('danger', 'Failed to request change.');
          },
        });
      }

      //   History Request
      function populateHistoryRequest(response) {
        const historyContainer = $('#tab-content .history-card-container');
        let allHistoryData = response.data;
        let displayedCount = 0;
        const batchSize = 3;

        historyContainer.empty();

        function formatDate(dateString) {
          if (!dateString) return 'Unknown Date';
          const options = {
            day: '2-digit',
            month: 'long',
            year: 'numeric',
          };
          return new Date(dateString).toLocaleDateString('id-ID', options);
        }

        function generateDropdownOptions(item, user) {
          const isCreator = item.created_by === user.id;
          const isHRGA = user.role.name.toLowerCase() === 'hrga';

          const detailOption = `
            <li>
                <a class="dropdown-item text-secondary-color detail-button" data-id="${item.id}" href="#">
                    <i class="fas fa-info-circle"></i> Detail
                </a>
            </li>`;
          const deleteOption = isCreator ? `
            <li>
                <a class="dropdown-item text-danger delete-button" data-id="${item.id}" href="#">
                    <i class="fas fa-trash"></i> Delete
                </a>
            </li>` : '';
          const approveOption = isHRGA ? `
            <li>
                <a class="dropdown-item text-success approve-button" data-id="${item.id}" href="#">
                    <i class="fas fa-check-circle"></i> Approve
                </a>
            </li>` : '';
          const rejectOption = isHRGA ? `
            <li>
                <a class="dropdown-item text-danger reject-button" data-id="${item.id}" href="#">
                    <i class="fas fa-times-circle"></i> Reject
                </a>
            </li>` : '';

          return `${detailOption}${approveOption}${rejectOption}${deleteOption}`;
        }

        function createCard(item, user) {
          let statusText = 'Pending';
          let statusClass = 'pending';
          if (item.approved_status === 1 || item.approved_status === '1') {
            statusText = 'Approved';
            statusClass = 'approved';
          } else if (item.approved_status === 0 || item.approved_status === '0') {
            statusText = 'Rejected';
            statusClass = 'rejected';
          }

          const approvedBy = item.approved_status === 1 || item.approved_status === '1' ?
            (item.approved_by?.name ?
              `<span><i class="fas fa-user me-1"></i> Approved by ${item.approved_by.name}</span>` :
              `<span><i class="fas fa-user me-1"></i> Approved by -</span>`) :
            item.approved_status === 0 || item.approved_status === '0' ?
            (item.approved_by?.name ?
              `<span><i class="fas fa-user me-1"></i> Rejected by ${item.approved_by.name}</span>` :
              `<span><i class="fas fa-user me-1"></i> Rejected by -</span>`) :
            '';

          const dropdownOptions = generateDropdownOptions(item, user);

          return $(`
                <div class="history-card fade-in">
                    <div class="card-body p-4">
                        <div class="history-card-title d-flex flex-column align-items-start gap-1 mb-4">
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <h5 class="card-title mb-0">${item.fullname || 'Unknown Title'}</h5>
                            <div class="dropdown">
                            <div class="action-dots" data-bs-toggle="dropdown">
                                <div class="dot"></div>
                                <div class="dot"></div>
                                <div class="dot"></div>
                            </div>
                            <ul class="dropdown-menu">
                                ${dropdownOptions}
                            </ul>
                            </div>
                        </div>
                        <div>
                            <span class="status-badge ${statusClass}">
                            <i class="fas fa-${statusText === 'Approved' ? 'check' : statusText === 'Rejected' ? 'times' : 'exclamation-triangle'}"></i>
                            ${statusText}
                            </span>
                        </div>
                        </div>
                        <div class="meta-info d-flex ${approvedBy ? 'justify-content-between' : 'justify-content-end'}">
                        ${approvedBy}
                        <span><i class="fas fa-clock me-1"></i> ${formatDate(item.created_at)}</span>
                        </div>
                    </div>
                </div>
            `);
        }

        function displayBatch() {
          const remainingData = allHistoryData.slice(displayedCount, displayedCount + batchSize);
          remainingData.forEach(item => {
            const newCard = createCard(item, user);
            historyContainer.append(newCard);
          });

          displayedCount += batchSize;

          if (displayedCount >= allHistoryData.length) {
            $('#loadMoreButton').hide();
          }
        }

        displayBatch();

        $('#loadMoreButton').off('click').on('click', function() {
          displayBatch();
        });

        // Detail button functionality
        $(document).on('click', '.detail-button', function(e) {
          const id = $(this).data('id');
          const item = allHistoryData.find(data => data.id === id);
          let statusText = "";
          let noteText = "";

          if (item.approved_status === 1 || item.approved_status === '1') {
            statusText = "Approved";
          } else if (item.approved_status === 0 || item.approved_status === '0') {
            statusText = "Rejected";
          } else {
            statusText = "Pending";
          }

          noteText = item.approved_note || "-";

          if (item) {
            const detailModal = `
                <div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Detail Request</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <tr><th>NIK</th><td>${item.nik}</td></tr>
                                <tr><th>Fullname</th><td>${item.fullname}</td></tr>
                                <tr><th>Nickname</th><td>${item.nickname}</td></tr>
                                <tr><th>Birth Place</th><td>${item.birth_place}</td></tr>
                                <tr><th>Birth Date</th><td>${item.birth_date}</td></tr>
                                <tr><th>Phone</th><td>${item.phone}</td></tr>
                                <tr><th>Email</th><td>${item.email}</td></tr>
                                <tr><th>Gender</th><td>${item.gender.name}</td></tr>
                                <tr><th>Marital Status</th><td>${item.marital.name}</td></tr>
                                <tr><th>Religion</th><td>${item.religion.name}</td></tr>
                                <tr><th>Join Date</th><td>${item.join_date}</td></tr>
                                <tr><th>Leave Balance</th><td>${item.leave_saldo}</td></tr>
                                <tr><th>Address</th><td>${item.address}</td></tr>
                                <tr><th>City</th><td>${item.address_city.city}</td></tr>
                                <tr><th>Province</th><td>${item.address_province.province}</td></tr>
                                <tr><th>Permanent Address</th><td>${item.address_permanent}</td></tr>
                                <tr><th>Permanent City</th><td>${item.address_permanent_city.city || "-"}</td></tr>
                                <tr><th>Permanent Province</th><td>${item.address_permanent_province.province || "-"}</td></tr>
                                <tr><th>Bank</th><td>${item.bank.name}</td></tr>
                                <tr><th>Bank Account</th><td>${item.bank_account}</td></tr>
                                <tr><th>Emergency Relation</th><td>${item.emergency_relation.name}</td></tr>
                                <tr><th>Emergency Contact Name</th><td>${item.emergency_contact_name}</td></tr>
                                <tr><th>Emergency Contact Phone</th><td>${item.emergency_contact_phone}</td></tr>
                                <tr><th>Emergency Contact Address</th><td>${item.emergency_contact_address}</td></tr>
                                <tr><th>Status</th><td>${statusText}</td></tr>
                                <tr><th>Note</th><td>${noteText}</td></tr>
                                <tr><th>Created At</th><td>${formatDate(item.created_at)}</td></tr>
                            </table>
                        </div>
                        </div>
                        </div>
                    </div>
                </div>
            `;
            $('body').append(detailModal);
            $('#detailModal').modal('show');
            $('#detailModal').on('hidden.bs.modal', function() {
              $(this).remove();
            });
          }
        });

        // Approve button functionality
        $(document).on('click', '.approve-button', function(e) {
          e.preventDefault();
          const id = $(this).data('id');
          const approveModal = `
            <div class="modal fade" id="approveModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title">Confirm Approval</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    <textarea id="note" class="form-control" placeholder="Note" rows="3"></textarea>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn-small btn-danger" data-bs-dismiss="modal">No</button>
                    <button type="button" class="btn-small btn-primary confirm-approve-btn" data-id="${id}">Yes</button>
                    </div>
                </div>
                </div>
            </div>
          `;
          $('body').append(approveModal);
          $('#approveModal').modal('show');
          $('#approveModal').on('hidden.bs.modal', function() {
            $(this).remove();
          });
        });

        $(document).on('click', '.confirm-approve-btn', function() {
          const id = $(this).data('id');
          $.ajax({
            url: "{{ route('employee.request.approve') }}",
            type: 'POST',
            data: {
              id,
              note: $('#note').val(),
              _token: '{{ csrf_token() }}',
              _method: 'PUT'
            },
            success: function(response) {
              showAlert('success', 'Request approved!');
              loadHistoryRequestForm();
              $('#approveModal').modal('hide');
            },
            error: function() {
              showAlert('danger', "Failed to approve request.");
            }
          });
        })

        // Reject button functionality
        $(document).on('click', '.reject-button', function(e) {
          e.preventDefault();
          const id = $(this).data('id');
          const rejectModal = `
            <div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title">Confirm Rejection</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    <textarea id="note" class="form-control" placeholder="Note" rows="3"></textarea>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn-small btn-danger" data-bs-dismiss="modal">No</button>
                    <button type="button" class="btn-small btn-primary confirm-reject-btn" data-id="${id}">Yes</button>
                    </div>
                </div>
                </div>
            </div>
          `;
          $('body').append(rejectModal);
          $('#rejectModal').modal('show');
          $('#rejectModal').on('hidden.bs.modal', function() {
            $(this).remove();
          });
        });

        $(document).on('click', '.confirm-reject-btn', function() {
          const id = $(this).data('id');
          $.ajax({
            url: "{{ route('employee.request.reject') }}",
            type: 'POST',
            data: {
              id,
              note: $('#note').val(),
              _token: '{{ csrf_token() }}',
              _method: 'PUT'
            },
            success: function(response) {
              showAlert('success', 'Request rejected!');
              loadHistoryRequestForm();
              $('#rejectModal').modal('hide');
            },
            error: function() {
              showAlert('danger', "Failed to reject request.");
            }
          });
        })

        // Delete button functionality
        $(document).on('click', '.delete-button', function(e) {
          e.preventDefault();
          const id = $(this).data('id');
          const deleteModal = `
            <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    <p class="mb-0">Are you sure you want to delete this record?</p>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn-small btn-secondary" data-bs-dismiss="modal">No</button>
                    <button type="button" class="btn-small btn-danger confirm-delete-btn" data-id="${id}">Yes</button>
                    </div>
                </div>
                </div>
            </div>
            `;
          $('body').append(deleteModal);
          $('#deleteModal').modal('show');
          $('#deleteModal').on('hidden.bs.modal', function() {
            $(this).remove();
          });
        });

        $(document).on('click', '.confirm-delete-btn', function() {
          const id = $(this).data('id');
          $.ajax({
            url: "{{ route('employee.request.delete') }}",
            type: 'POST',
            data: {
              id,
              _token: '{{ csrf_token() }}',
              _method: 'DELETE'
            },
            success: function() {
              showAlert('success', 'Request deleted!');
              $('#deleteModal').modal('hide');
              loadHistoryRequestForm();
            },
            error: function() {
              showAlert('danger', 'Failed to delete record.');
            },
          });
        });
      }

      function loadHistoryRequestForm() {
        $('#tab-content').html(`
            <div class="w-100">
                <div class="row">
                    <div class="col-12">
                        <div class="history-card-container"></div>

                        <button id="loadMoreButton" class="mt-2 btn btn-primary">Load More</button>
                    </div>
                </div>
            </div>
        `);

        fetchHistoryRequest();
      }

      function fetchHistoryRequest() {
        showLoading();

        $.ajax({
          url: "{{ route('employee.request.data', ':id') }}".replace(':id', user.employ_id),
          type: 'GET',
          data: {
            trash: 1
          },
          success: function(response) {
            if (response.data.length > 0) {
              populateHistoryRequest(response);
            } else {
              $('#loadMoreButton').hide();
              $('#tab-content').html(`
                    <div class="w-100">
                        <div class="row">
                            <div class="col-12">
                                <div class="card-body">
                                    <div class="d-flex flex-column justify-content-center align-items-center gap-2">
                                        <img src="{{ asset('/images/nodata.png') }}" alt="No History Found" class="img-fluid" style="max-width: 300px; max-height: 300px;" />
                                        <h3 class="text-center font-bold text-black mb-0">No History Found</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `);
            }
          },
          error: function() {
            console.error('Failed to fetch profile data.');
          },
          complete: function() {
            hideLoading();
          }
        });
      }

      // Settings
      function loadChangePasswordForm() {
        $('#tab-content').html(`
            <form id="changePasswordForm">
                <div class="card p-4">
                    <h5 class="card-title">Change Password</h5>
                    <div class="form-group mb-3">
                        <label for="old" class="form-label">Old Password</label>
                        <input type="password" name="old" id="old" class="form-control"
                            placeholder="Enter your old password">
                    </div>
                    <div class="form-group mb-3">
                        <label for="new" class="form-label">New Password</label>
                        <input type="password" name="new" id="new" class="form-control"
                            placeholder="Enter new password">
                    </div>
                    <div class="form-group mb-4">
                        <label for="confirm" class="form-label">Confirm Password</label>
                        <input type="password" name="confirm" id="confirm" class="form-control"
                            placeholder="Enter confirm password">
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        <button type="button" id="backButton" class="btn btn-danger">Cancel</button>
                        <button type="submit" id="submitPasswordButton" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </f>
        `);

        $('#backButton').on('click', function() {
          loadSettingsForm();
        });

        $('#changePasswordForm').off('submit').on('submit', function(e) {
          event.preventDefault();

          const formData = new FormData(this);
          formData.append('_token', '{{ csrf_token() }}');

          const newPassword = $('#new').val();
          const confirmPassword = $('#confirm').val();

          if (newPassword !== confirmPassword) {
            showAlert('warning', 'New Password and Confirm Password do not match.');
            return;
          }

          $.ajax({
            url: "{{ route('profile.password') }}",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
              showAlert('success', response.message || 'Password changed successfully.');
              loadSettingsForm();
              $('#changePasswordForm')[0].reset();
            },
            error: function(xhr) {
              const errorMessage = xhr.responseJSON.message || 'Failed to change password.';
              showAlert('danger', errorMessage);
            }
          });
        })
      }

      function loadSettingsForm() {
        $('#tab-content').html(`
            <div id="settingsCard">
                <div class="card">
                    <div class="card-body">
                        <ul class="d-flex flex-column gap-1 p-0 m-0">
                            <li class="list-group-item d-flex align-items-center p-2">
                                <a href="#" id="changePasswordLink" class="text-decoration-none text-primary-color d-flex align-items-center justify-content-between w-100">
                                    <div>
                                        <i class="fas fa-lock me-2"></i> Change Password
                                    </div>
                                    <p class="mb-0">></p>
                                </a>
                            </li>
                            <li class="list-group-item d-flex align-items-center p-2">
                                <a href="#" id="exportPdfLink" class="text-decoration-none d-flex text-secondary-color align-items-center justify-content-between w-100">
                                    <div>
                                        <i class="fas fa-file-pdf me-2"></i> Export PDF
                                    </div>
                                    <p class="mb-0">></p>
                                </a>
                            </li>
                            <li class="list-group-item d-flex align-items-center p-2">
                                <a href="{{ route('logout') }}" class="text-decoration-none text-danger-color d-flex align-items-center justify-content-between w-100">
                                    <div>
                                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                                    </div>
                                    <p class="mb-0">></p>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        `);

        $('#changePasswordLink').on('click', function(e) {
          e.preventDefault();
          loadChangePasswordForm();
        });

        $('#exportPdfLink').on('click', function(e) {
          e.preventDefault();
          const userId = user.employ_id;

          if (!userId) {
            showAlert('danger', 'User ID not found.');
            return;
          }
          const downloadUrl = '{{ route('employee.request.export.pdf', ':id') }}'.replace(':id', userId);
          window.location.href = downloadUrl;
        });
      }

      loadProfileForm();
    });
  </script>
@endsection
