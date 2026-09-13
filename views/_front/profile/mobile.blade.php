@extends('templates.mobile')

@section('head')
  <style>
    body {
      background-color: #f8fafc;
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    .profile-page-wrapper {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      padding-bottom: 40px;
    }

    .profile-header-banner {
      background: linear-gradient(135deg, #0073e6 0%, #00a651 100%);
      padding: 16px 20px 48px 20px;
      color: #ffffff;
      position: relative;
      border-bottom-left-radius: 28px;
      border-bottom-right-radius: 28px;
      box-shadow: 0 10px 30px rgba(0, 115, 230, 0.2);
    }

    .profile-header-banner::after {
      content: '';
      position: absolute;
      right: -20px;
      bottom: -30px;
      width: 140px;
      height: 140px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.1);
      pointer-events: none;
    }

    .top-action-bar {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 20px;
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

    .user-hero-card {
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
    }

    .avatar-wrapper {
      position: relative;
      width: 104px;
      height: 104px;
      margin-bottom: 12px;
    }

    .avatar-img {
      width: 104px;
      height: 104px;
      border-radius: 50%;
      object-fit: cover;
      border: 3.5px solid #ffffff;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.18);
    }

    .avatar-edit-badge {
      position: absolute;
      bottom: 2px;
      right: 2px;
      width: 34px;
      height: 34px;
      border-radius: 50%;
      background: #0073e6;
      border: 2.5px solid #ffffff;
      color: #ffffff;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 14px;
      cursor: pointer;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
      transition: transform 0.2s ease;
    }

    .avatar-edit-badge:active {
      transform: scale(0.9);
    }

    .user-name-title {
      font-size: 20px;
      font-weight: 800;
      color: #ffffff;
      margin-bottom: 4px;
      letter-spacing: -0.4px;
    }

    .user-role-badge {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      background: rgba(255, 255, 255, 0.2);
      backdrop-filter: blur(8px);
      padding: 4px 14px;
      border-radius: 50px;
      font-size: 12px;
      font-weight: 600;
      color: #ffffff;
      border: 1px solid rgba(255, 255, 255, 0.25);
    }

    .content-body {
      padding: 0 20px;
      margin-top: -24px;
      z-index: 10;
      position: relative;
    }

    .tab-segmented-control {
      background: #ffffff;
      border-radius: 16px;
      padding: 5px;
      display: flex;
      gap: 5px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
      border: 1px solid #f1f5f9;
      margin-bottom: 18px;
    }

    .tab-btn {
      flex: 1;
      border: none;
      background: transparent;
      padding: 10px 14px;
      border-radius: 12px;
      font-size: 13px;
      font-weight: 700;
      color: #64748b;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
      transition: all 0.2s ease;
      cursor: pointer;
    }

    .tab-btn.active {
      background: #0073e6;
      color: #ffffff;
      box-shadow: 0 4px 12px rgba(0, 115, 230, 0.3);
    }

    .form-card {
      background: #ffffff;
      border-radius: 20px;
      padding: 22px 20px;
      border: 1px solid #f1f5f9;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
    }

    .form-label-custom {
      font-size: 12px;
      font-weight: 700;
      color: #475569;
      margin-bottom: 6px;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .input-group-custom {
      position: relative;
      margin-bottom: 16px;
    }

    .input-icon {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      color: #94a3b8;
      font-size: 16px;
      pointer-events: none;
      z-index: 5;
    }

    .textarea-icon {
      top: 18px !important;
      transform: none !important;
    }

    .input-field-custom {
      width: 100%;
      padding: 12px 14px 12px 42px;
      font-size: 14px;
      font-weight: 600;
      color: #0f172a;
      background: #f8fafc;
      border: 1.5px solid #e2e8f0;
      border-radius: 12px;
      transition: all 0.2s ease;
      outline: none;
    }

    .input-field-custom:focus {
      background: #ffffff;
      border-color: #0073e6;
      box-shadow: 0 0 0 4px rgba(0, 115, 230, 0.1);
    }

    textarea.input-field-custom {
      padding-top: 12px;
      min-height: 90px;
      resize: vertical;
    }

    .btn-submit-gradient {
      width: 100%;
      padding: 13px;
      background: linear-gradient(135deg, #0073e6 0%, #005bb5 100%);
      color: #ffffff;
      font-size: 14px;
      font-weight: 700;
      border: none;
      border-radius: 14px;
      box-shadow: 0 6px 18px rgba(0, 115, 230, 0.25);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      transition: all 0.2s ease;
      cursor: pointer;
    }

    .btn-submit-gradient:active {
      transform: scale(0.98);
      opacity: 0.9;
    }

    .settings-list-card {
      background: #ffffff;
      border-radius: 20px;
      border: 1px solid #f1f5f9;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
      overflow: hidden;
    }

    .settings-item {
      padding: 16px 18px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      border-bottom: 1px solid #f1f5f9;
      text-decoration: none;
      transition: background 0.15s ease;
      cursor: pointer;
    }

    .settings-item:last-child {
      border-bottom: none;
    }

    .settings-item:active {
      background: #f8fafc;
    }

    .settings-item-left {
      display: flex;
      align-items: center;
      gap: 14px;
    }

    .settings-icon-box {
      width: 42px;
      height: 42px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
    }

    .settings-title {
      font-size: 14px;
      font-weight: 700;
      color: #1e293b;
      margin: 0;
      line-height: 1.2;
    }

    .settings-desc {
      font-size: 12px;
      color: #64748b;
      margin: 2px 0 0 0;
    }

    .pwd-toggle-btn {
      position: absolute;
      right: 14px;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      color: #94a3b8;
      font-size: 16px;
      cursor: pointer;
      z-index: 5;
    }
  </style>
@endsection

@section('content')
  <div class="profile-page-wrapper">
    <div class="profile-header-banner">
      <div class="top-action-bar">
        <a href="{{ route('main') }}" class="btn-back-link">
          <i class="bi bi-arrow-left"></i>
        </a>
        <h1 class="header-page-title">Profil Saya</h1>
        <div style="width: 38px;"></div>
      </div>

      <div class="user-hero-card">
        <div class="avatar-wrapper">
          @if ($user->photo_id || ($user->employee && $user->employee->photo_id))
            @php
              $urlFile = route('file', $user->photo_id ?: $user->employee->photo_id);
            @endphp
            <img src="{{ $urlFile }}" alt="User Photo" class="avatar-img" id="userAvatarImg">
          @else
            <img src="{{ asset('/images/image-no-user.png') }}" alt="User Photo" class="avatar-img" id="userAvatarImg">
          @endif
          <div class="avatar-edit-badge" id="btnEditAvatar" title="Ubah Foto Profil">
            <i class="bi bi-camera-fill"></i>
          </div>
          <input type="file" id="avatarFileInput" accept="image/*" style="display: none;">
        </div>

        <h2 class="user-name-title" id="userNameTitle">{{ strtoupper($user->name) }}</h2>
        <div class="user-role-badge">
          <i class="bi bi-patch-check-fill"></i>
          <span>{{ $user->role->name ?? 'User Role' }}</span>
        </div>
      </div>
    </div>

    <div class="content-body">
      <div class="tab-segmented-control">
        <button class="tab-btn active" data-tab="profile">
          <i class="bi bi-person-badge-fill"></i> Data Profil
        </button>
        <button class="tab-btn" data-tab="settings">
          <i class="bi bi-sliders"></i> Pengaturan
        </button>
      </div>

      <div id="tabContentArea">
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      const user = @json($user);

      $('.tab-btn').on('click', function() {
        const tab = $(this).data('tab');
        $('.tab-btn').removeClass('active');
        $(this).addClass('active');

        if (tab === 'profile') {
          loadProfileForm();
        } else if (tab === 'settings') {
          loadSettingsForm();
        }
      });

      $('#btnEditAvatar').on('click', function() {
        $('#avatarFileInput').click();
      });

      $('#avatarFileInput').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
          uploadAvatar(file);
        }
      });

      function uploadAvatar(file) {
        let formData = new FormData();
        formData.append('photo', file);

        showLoading();

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
            hideLoading();
            if (response.success) {
              $('#userAvatarImg').attr('src', `{{ route('file', '') }}/${response.data.photo_id}`);
              showAlert('success', response.message || 'Foto profil berhasil diperbarui!');
            } else {
              showAlert('danger', response.message || 'Gagal mengunggah foto.');
            }
          },
          error: function(jqXHR) {
            hideLoading();
            let msg = 'Gagal mengunggah foto. Silakan coba lagi.';
            try {
              const res = JSON.parse(jqXHR.responseText);
              if (res.message) msg = res.message;
            } catch (e) {}
            showAlert('danger', msg);
          }
        });
      }

      function loadProfileForm() {
        $('#tabContentArea').html(`
          <div class="form-card">
            <form id="profileForm">
              @csrf
              <div class="form-group mb-3">
                <label class="form-label-custom">
                  <i class="bi bi-person-fill text-primary"></i> Nama Lengkap
                </label>
                <div class="input-group-custom">
                  <i class="bi bi-person input-icon"></i>
                  <input type="text" class="input-field-custom" name="name" placeholder="Masukkan nama lengkap" required>
                </div>
              </div>

              <div class="form-group mb-3">
                <label class="form-label-custom">
                  <i class="bi bi-at text-primary"></i> Username
                </label>
                <div class="input-group-custom">
                  <i class="bi bi-at input-icon"></i>
                  <input type="text" class="input-field-custom" name="username" placeholder="Masukkan username" required>
                </div>
              </div>

              <div class="form-group mb-3">
                <label class="form-label-custom">
                  <i class="bi bi-envelope-fill text-primary"></i> Alamat Email
                </label>
                <div class="input-group-custom">
                  <i class="bi bi-envelope input-icon"></i>
                  <input type="email" class="input-field-custom" name="email" placeholder="Masukkan alamat email" required>
                </div>
              </div>

              <div class="form-group mb-4">
                <label class="form-label-custom">
                  <i class="bi bi-geo-alt-fill text-primary"></i> Alamat Tempat Tinggal
                </label>
                <div class="input-group-custom">
                  <i class="bi bi-geo-alt input-icon textarea-icon"></i>
                  <textarea class="input-field-custom" name="address" placeholder="Masukkan alamat tempat tinggal"></textarea>
                </div>
              </div>

              <button type="submit" class="btn-submit-gradient">
                <i class="bi bi-check-circle-fill"></i> Simpan Perubahan
              </button>
            </form>
          </div>
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
          success: function(response) {
            hideLoading();
            if (response) {
              $('input[name="name"]').val(response.name || '');
              $('input[name="username"]').val(response.username || '');
              $('input[name="email"]').val(response.email || '');
              $('textarea[name="address"]').val(response.address || '');
            }
          },
          error: function() {
            hideLoading();
            console.error('Gagal mengambil data profil.');
          }
        });
      }

      function saveProfileData() {
        const formData = new FormData($('#profileForm')[0]);
        showLoading();

        $.ajax({
          url: "{{ route('profile.edit') }}",
          type: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function(response) {
            hideLoading();
            if (response.success && response.data) {
              $('#userNameTitle').text(response.data.name.toUpperCase());
              showAlert('success', response.message || 'Profil berhasil diperbarui.');
            } else {
              showAlert('danger', response.message || 'Gagal menyimpan perubahan.');
            }
          },
          error: function(xhr) {
            hideLoading();
            const msg = xhr.responseJSON?.message || 'Gagal meminta perubahan.';
            showAlert('danger', msg);
          }
        });
      }

      function loadSettingsForm() {
        $('#tabContentArea').html(`
          <div class="settings-list-card" id="settingsMenuCard">
            <div class="settings-item" id="btnChangePasswordLink">
              <div class="settings-item-left">
                <div class="settings-icon-box" style="background: #eff6ff; color: #0073e6;">
                  <i class="bi bi-key-fill"></i>
                </div>
                <div>
                  <h4 class="settings-title">Ubah Kata Sandi</h4>
                  <p class="settings-desc">Perbarui kata sandi keamanan akun kamu</p>
                </div>
              </div>
              <i class="bi bi-chevron-right text-muted fs-6"></i>
            </div>

            <div class="settings-item" id="btnExportPdfLink">
              <div class="settings-item-left">
                <div class="settings-icon-box" style="background: #fef2f2; color: #ef4444;">
                  <i class="bi bi-file-earmark-pdf-fill"></i>
                </div>
                <div>
                  <h4 class="settings-title">Ekspor PDF / CV</h4>
                  <p class="settings-desc">Unduh dokumen profil lengkap dalam PDF</p>
                </div>
              </div>
              <i class="bi bi-chevron-right text-muted fs-6"></i>
            </div>

            <a href="{{ route('logout') }}" class="settings-item" onclick="event.preventDefault(); window.location='{{ route('logout') }}';">
              <div class="settings-item-left">
                <div class="settings-icon-box" style="background: #fff1f2; color: #e11d48;">
                  <i class="bi bi-box-arrow-right"></i>
                </div>
                <div>
                  <h4 class="settings-title text-danger">Keluar Akun</h4>
                  <p class="settings-desc">Keluar dari sesi Sintesa HRIS</p>
                </div>
              </div>
              <i class="bi bi-chevron-right text-danger fs-6"></i>
            </a>
          </div>
        `);

        $('#btnChangePasswordLink').on('click', function(e) {
          e.preventDefault();
          loadChangePasswordForm();
        });

        $('#btnExportPdfLink').on('click', function(e) {
          e.preventDefault();
          const userId = user.employ_id;

          if (!userId) {
            showAlert('danger', 'ID Pegawai tidak ditemukan.');
            return;
          }
          const downloadUrl = '{{ route('employee.request.export.pdf', ':id') }}'.replace(':id', userId);
          window.location.href = downloadUrl;
        });
      }

      function loadChangePasswordForm() {
        $('#tabContentArea').html(`
          <div class="form-card">
            <div class="d-flex align-items-center gap-2 mb-3">
              <button type="button" id="btnCancelPassword" class="btn btn-sm btn-light rounded-circle p-2" style="width:34px; height:34px; display:flex; align-items:center; justify-content:center;">
                <i class="bi bi-arrow-left"></i>
              </button>
              <h5 class="fw-bold mb-0 text-dark" style="font-size: 16px;">Ubah Kata Sandi</h5>
            </div>

            <form id="changePasswordForm">
              @csrf
              <div class="form-group mb-3">
                <label class="form-label-custom">Password Saat Ini</label>
                <div class="input-group-custom">
                  <i class="bi bi-lock-fill input-icon"></i>
                  <input type="password" name="old" id="oldPasswordInput" class="input-field-custom" placeholder="Masukkan password lama" required>
                  <button type="button" class="pwd-toggle-btn" data-target="#oldPasswordInput">
                    <i class="bi bi-eye"></i>
                  </button>
                </div>
              </div>

              <div class="form-group mb-3">
                <label class="form-label-custom">Password Baru</label>
                <div class="input-group-custom">
                  <i class="bi bi-shield-lock-fill input-icon"></i>
                  <input type="password" name="new" id="newPasswordInput" class="input-field-custom" placeholder="Minimal 8 karakter (Huruf Besar, Kecil, Angka)" required>
                  <button type="button" class="pwd-toggle-btn" data-target="#newPasswordInput">
                    <i class="bi bi-eye"></i>
                  </button>
                </div>
              </div>

              <div class="form-group mb-4">
                <label class="form-label-custom">Konfirmasi Password Baru</label>
                <div class="input-group-custom">
                  <i class="bi bi-shield-check input-icon"></i>
                  <input type="password" name="confirm" id="confirmPasswordInput" class="input-field-custom" placeholder="Ulangi password baru" required>
                  <button type="button" class="pwd-toggle-btn" data-target="#confirmPasswordInput">
                    <i class="bi bi-eye"></i>
                  </button>
                </div>
              </div>

              <div class="d-flex gap-2">
                <button type="button" id="btnCancelPasswordSubmit" class="btn btn-light w-50 py-2 fw-bold" style="border-radius: 12px;">Batal</button>
                <button type="submit" class="btn-submit-gradient w-50">Simpan</button>
              </div>
            </form>
          </div>
        `);

        $('#btnCancelPassword, #btnCancelPasswordSubmit').on('click', function() {
          loadSettingsForm();
        });

        $('.pwd-toggle-btn').on('click', function() {
          const targetSelector = $(this).data('target');
          const input = $(targetSelector);
          const icon = $(this).find('i');

          if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('bi-eye').addClass('bi-eye-slash');
          } else {
            input.attr('type', 'password');
            icon.removeClass('bi-eye-slash').addClass('bi-eye');
          }
        });

        $('#changePasswordForm').on('submit', function(e) {
          e.preventDefault();

          const newPassword = $('#newPasswordInput').val();
          const confirmPassword = $('#confirmPasswordInput').val();

          if (newPassword !== confirmPassword) {
            showAlert('warning', 'Password baru dan Konfirmasi Password tidak cocok.');
            return;
          }

          const formData = new FormData(this);
          showLoading();

          $.ajax({
            url: "{{ route('profile.password') }}",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
              hideLoading();
              if (response.success) {
                showAlert('success', response.message || 'Password berhasil diubah.');
                loadSettingsForm();
              } else {
                showAlert('danger', response.message || 'Gagal mengubah password.');
              }
            },
            error: function(xhr) {
              hideLoading();
              const errorMessage = xhr.responseJSON?.message || 'Gagal mengubah password.';
              showAlert('danger', errorMessage);
            }
          });
        });
      }

      loadProfileForm();
    });
  </script>
@endsection
