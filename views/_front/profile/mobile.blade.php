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
        @if ($user->photo_id)
          @php
            $urlFile = route('file', $user->photo_id);
          @endphp
          <div class="profile-avatar-wrapper">
            <img src="{{ $urlFile }}" alt="Profile" class="profile-avatar">
            <i class="edit-avatar-icon fas fa-pencil-alt"></i>
            <input type="file" class="avatar-input" accept="image/*" style="display: none;">
          </div>
        @else
          <div class="profile-avatar-wrapper">
            <img src="{{ asset('/images/image-no-user.png') }}" alt="no-user" class="profile-avatar">
            <i class="edit-avatar-icon fas fa-pencil-alt"></i>
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
      <button class="nav-item" data-tab="settings">Settings</button>
    </div>

    <div id="tab-container" class="tab-container">
      <div id="tab-content">
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      const user = @json($user);

      $('.nav-item').on('click', function() {
        const tab = $(this).data('tab');
        $('.nav-item').removeClass('active');
        $(this).addClass('active');

        if (tab === 'profile') {
          loadProfileForm();
        } else if (tab === 'settings') {
          loadSettingsForm();
        }
      });

      function uploadAvatar(file, $input) {
        let formData = new FormData();
        formData.append('photo', file);

        $.ajax({
          url: "{{ route('profile.upload') }}",
          type: "POST",
          data: formData,
          processData: false,
          contentType: false,
          headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
          },
          success: function(response) {
            if (response.success) {
              $input.siblings('.profile-avatar').attr('src',
                `{{ route('file', '') }}/${response.data.photo_id}`);
              showAlert('success', response.message || 'Profile picture updated successfully!');
            } else {
              showAlert('danger', response.message || 'Failed to upload photo.');
            }
          },
          error: function(jqXHR) {
            let msg = 'Failed to upload photo. Please try again.';
            try {
              const response = JSON.parse(jqXHR.responseText);
              if (response.message) msg = response.message;
            } catch (e) {}
            showAlert('danger', msg);
          }
        });
      }

      $('.edit-avatar-icon').on('click', function() {
        const $input = $(this).siblings('.avatar-input');
        $input.click();
      });

      $('.avatar-input').on('change', function(event) {
        const file = event.target.files[0];
        if (file) {
          uploadAvatar(file, $(this));
        }
      });

      // Profile
      function loadProfileForm() {
        $('#tab-content').html(`
            <form id="profileForm">
                @csrf
                <div class="profile-field">
                    <div class="field-label">Username</div>
                    <input type="text" class="field-input" name="username">
                </div>
                <div class="profile-field">
                    <div class="field-label">Email</div>
                    <input type="text" class="field-input" name="email">
                </div>
                <div class="profile-field">
                    <div class="field-label">Name</div>
                   <input type="text" class="field-input" name="name">
                </div>
                <div class="profile-field">
                    <div class="field-label">address</div>
                    <textarea class="field-input" name="address"></textarea>
                </div>
                <button type="submit" class="mt-2 btn btn-primary">Update</button>
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
          url: "{{ route('profile.data') }}",
          type: 'GET',
          success: async function(response) {
            populateProfileForm(response);
          },
          error: function() {
            console.error('Failed to fetch profile data.');
          },
          complete: function() {
            hideLoading();
          }
        });
      }

      async function populateProfileForm(data) {
        $('input[name="organization_id"]').val(data.organization?.name || '');
        $('input[name="employee_id"]').val(data.employee?.nik || '');
        $('input[name="username"]').val(data.username || '');
        $('input[name="email"]').val(data.email || '');
        $('input[name="name"]').val(data.name || '');
        $('textarea[name="address"]').val(data.address || '');
      }

      function saveProfileData() {
        const formData = new FormData($('#profileForm')[0]);

        $.ajax({
          url: "{{ route('profile.edit') }}",
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function(response) {
            $('.profile-name').text(response.data.name.toUpperCase());
            showAlert('success', response.message || 'Data saved successfully.');
          },
          error: function() {
            showAlert('danger', 'Failed to request change.');
          },
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
