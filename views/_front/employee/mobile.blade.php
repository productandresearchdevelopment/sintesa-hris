@extends('templates.mobile')

@section('head')
  <style>
    html, body {
      background-color: #ffffff !important;
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    .emp-page-wrapper {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      padding-bottom: 40px;
    }

    .emp-header-banner {
      background: linear-gradient(135deg, #0073e6 0%, #00a651 100%);
      padding: 16px 20px 48px 20px;
      color: #ffffff;
      position: relative;
      border-bottom-left-radius: 28px;
      border-bottom-right-radius: 28px;
      box-shadow: 0 10px 30px rgba(0, 115, 230, 0.2);
    }

    .emp-header-banner::after {
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
      margin-bottom: 16px;
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
      width: 90px;
      height: 90px;
      margin-bottom: 10px;
    }

    .avatar-img {
      width: 90px;
      height: 90px;
      border-radius: 50%;
      object-fit: cover;
      border: 3.5px solid #ffffff;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.18);
    }

    .user-name-title {
      font-size: 18px;
      font-weight: 800;
      color: #ffffff;
      margin-bottom: 4px;
      letter-spacing: -0.4px;
    }

    .user-nik-badge {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      background: rgba(255, 255, 255, 0.2);
      backdrop-filter: blur(8px);
      padding: 3px 12px;
      border-radius: 50px;
      font-size: 12px;
      font-weight: 600;
      color: #ffffff;
      border: 1px solid rgba(255, 255, 255, 0.25);
    }

    .content-body {
      padding: 0 16px;
      margin-top: -24px;
      z-index: 10;
      position: relative;
    }

    @media (min-width: 769px) {
      .emp-header-banner {
        display: none !important;
      }
      .content-body {
        margin-top: 0 !important;
        padding: 0 !important;
      }
    }

    /* Scrollable Tab Control Bar */
    .tab-scroll-wrapper {
      position: relative;
      margin-bottom: 16px;
    }

    .tab-segmented-control {
      background: #ffffff;
      border-radius: 16px;
      padding: 5px;
      padding-right: 42px;
      display: flex;
      gap: 5px;
      overflow-x: auto;
      white-space: nowrap;
      scrollbar-width: none;
      -ms-overflow-style: none;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
      border: 1px solid #f1f5f9;
      scroll-behavior: smooth;
    }

    .tab-segmented-control::-webkit-scrollbar {
      display: none;
    }

    .btn-tab-scroll-right {
      position: absolute;
      right: 6px;
      top: 50%;
      transform: translateY(-50%);
      width: 32px;
      height: 32px;
      border-radius: 50%;
      background: #0073e6;
      color: #ffffff;
      border: 2px solid #ffffff;
      box-shadow: 0 4px 12px rgba(0, 115, 230, 0.4);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 14px;
      cursor: pointer;
      z-index: 10;
    }

    .nav-item {
      flex: 0 0 auto;
      border: none;
      background: transparent;
      padding: 8px 14px;
      border-radius: 12px;
      font-size: 13px;
      font-weight: 700;
      color: #64748b;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: all 0.2s ease;
      cursor: pointer;
    }

    .nav-item.active {
      background: #0073e6;
      color: #ffffff;
      box-shadow: 0 4px 12px rgba(0, 115, 230, 0.3);
    }

    /* Card Styling */
    .emp-card {
      background: #ffffff;
      border-radius: 18px;
      padding: 18px;
      border: 1px solid #f1f5f9;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
      margin-bottom: 14px;
    }

    .emp-card-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      font-size: 14px;
      font-weight: 800;
      color: #0f172a;
      border-bottom: 1px solid #f1f5f9;
      padding-bottom: 10px;
      margin-bottom: 12px;
    }

    .emp-field-group {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 12px;
    }

    .emp-field-item {
      display: flex;
      flex-direction: column;
    }

    .emp-field-item.full-width {
      grid-column: span 2;
    }

    .emp-label {
      font-size: 11px;
      font-weight: 700;
      color: #94a3b8;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 2px;
    }

    .emp-value {
      font-size: 13.5px;
      font-weight: 700;
      color: #1e293b;
      word-break: break-word;
    }

    .btn-action-primary {
      width: 100%;
      padding: 12px;
      background: linear-gradient(135deg, #0073e6 0%, #005bb5 100%);
      color: #ffffff;
      font-size: 13.5px;
      font-weight: 700;
      border: none;
      border-radius: 14px;
      box-shadow: 0 6px 18px rgba(0, 115, 230, 0.25);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      margin-top: 14px;
      cursor: pointer;
    }

    .btn-action-primary:active {
      transform: scale(0.98);
    }

    .empty-state-box {
      text-align: center;
      padding: 34px 20px;
      background: #ffffff;
      border-radius: 18px;
      border: 1px solid #f1f5f9;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.03);
    }

    .empty-state-icon {
      font-size: 42px;
      color: #cbd5e1;
      margin-bottom: 8px;
    }

    #org_id.jstree {
      border: 1px solid #e2e8f0;
      border-radius: 10px;
      padding: 8px;
    }
  </style>
@endsection

@section('content')
  <div class="emp-page-wrapper">
    <div class="emp-header-banner">
      <div class="top-action-bar">
        <a href="{{ route('main') }}" class="btn-back-link">
          <i class="bi bi-arrow-left"></i>
        </a>
        <h1 class="header-page-title">Data Pegawai</h1>
        <div style="width: 38px;"></div>
      </div>

      <div class="user-hero-card">
        <div class="avatar-wrapper">
          @if (optional($user->employee)->photo_id)
            @php
              $urlFile = route('file', $user->employee->photo_id);
            @endphp
            <img src="{{ $urlFile }}" alt="Photo" class="avatar-img">
          @else
            <img src="{{ asset('/images/image-no-user.png') }}" alt="No Photo" class="avatar-img">
          @endif
        </div>

        <h2 class="user-name-title">{{ strtoupper(optional($user->employee)->fullname ?? $user->name) }}</h2>
        <div class="user-nik-badge">
          <i class="bi bi-person-badge-fill"></i>
          <span>NIK: {{ optional($user->employee)->nik ?? '-' }}</span>
        </div>
      </div>
    </div>

    <div class="content-body">
      <div class="tab-scroll-wrapper">
        <div class="tab-segmented-control" id="tabSegmentedControl">
          <button class="nav-item active" data-tab="general">
            <i class="bi bi-person-vcard-fill"></i> General
          </button>
          <button class="nav-item" data-tab="contract">
            <i class="bi bi-file-earmark-text-fill"></i> Kontrak
          </button>
          <button class="nav-item" data-tab="career">
            <i class="bi bi-graph-up-arrow"></i> Karir
          </button>
          <button class="nav-item" data-tab="citizen">
            <i class="bi bi-card-heading"></i> Identitas
          </button>
          <button class="nav-item" data-tab="education">
            <i class="bi bi-mortarboard-fill"></i> Pendidikan
          </button>
          <button class="nav-item" data-tab="family">
            <i class="bi bi-people-fill"></i> Keluarga
          </button>
          <button class="nav-item" data-tab="experience">
            <i class="bi bi-briefcase-fill"></i> Pengalaman
          </button>
          <button class="nav-item" data-tab="training">
            <i class="bi bi-award-fill"></i> Pelatihan
          </button>
        </div>
        <button type="button" class="btn-tab-scroll-right" id="btnTabScrollRight" title="Geser Tab Ke Kanan">
          <i class="bi bi-chevron-right"></i>
        </button>
      </div>

      <div id="tab-container">
        <div id="tab-content">
        </div>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      const user = @json($user);
      const organizations = @json($organizations);
      let userEmployee = null;
      let responseDataAllTables = null;
      let organizationSelected = null;

      function generateFileComponent(file) {
        if (!file) return '';
        const fileUrl = `{{ route('file', ':id') }}`.replace(':id', file.id);
        const fileExtension = (file.filename_origin || '').split('.').pop().toLowerCase();

        return `
          <div class="mt-2 mb-3 p-2 bg-light rounded-3 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2 overflow-hidden">
              <i class="bi bi-file-earmark-arrow-down-fill fs-4 text-primary"></i>
              <span class="small fw-bold text-dark text-truncate">${file.filename_origin || 'File Lampiran'}</span>
            </div>
            <a href="${fileUrl}" target="_blank" class="btn btn-sm btn-primary rounded-pill px-3">
              <i class="bi bi-download"></i> Unduh
            </a>
          </div>
        `;
      }

      $('#btnTabScrollRight').on('click', function() {
        const $active = $('.tab-segmented-control .nav-item.active');
        let $next = $active.next('.nav-item');
        if (!$next.length) {
          $next = $('.tab-segmented-control .nav-item').first();
        }
        $next.click();
      });

      $('.nav-item').on('click', function() {
        const tab = $(this).data('tab');
        $('.nav-item').removeClass('active');
        $(this).addClass('active');
        this.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
        loadTabContent(tab);
      });

      async function loadTabContent(tab) {
        if (userEmployee === null && responseDataAllTables === null) {
          showLoading();

          try {
            const response = await $.ajax({
              url: "{{ route('employee.get') }}" + '/' + user.employ_id,
              type: 'GET'
            });

            userEmployee = response;
            responseDataAllTables = await fetchAllTableData();
            populateTabContent(tab, response);
          } catch (error) {
            console.error('Gagal mengambil data pegawai.', error);
            showAlert('danger', 'Gagal memuat data pegawai.');
          } finally {
            hideLoading();
          }
        } else {
          populateTabContent(tab, userEmployee);
        }
      }

      function populateTabContent(tab, data) {
        let content = '';

        const generateEmpField = (label, value, isFull = false) => `
          <div class="emp-field-item ${isFull ? 'full-width' : ''}">
            <span class="emp-label">${label}</span>
            <span class="emp-value">${value || '-'}</span>
          </div>
        `;

        const generateEmpCards = (items, fields, emptyTitle, iconClass = 'bi-inbox') => {
          if (items && items.length > 0) {
            const cards = items.map(item => {
              let fileHtml = item.file ? generateFileComponent(item.file) : '';
              let fieldsHtml = '';

              fields.forEach(f => {
                let val = item[f.key];
                if (f.nestedKey) val = item[f.key]?.[f.nestedKey];
                if (['start_date', 'end_date', 'date', 'graduate', 'birth_date'].includes(f.key) && val) {
                  val = new Date(val).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
                }

                fieldsHtml += `
                  <div class="emp-field-item ${f.full ? 'full-width' : ''}">
                    <span class="emp-label">${f.label}</span>
                    <span class="emp-value">${val || '-'}</span>
                  </div>
                `;
              });

              return `
                <div class="emp-card">
                  ${fileHtml}
                  <div class="emp-field-group">
                    ${fieldsHtml}
                  </div>
                </div>
              `;
            }).join('');

            return cards;
          } else {
            return `
              <div class="empty-state-box">
                <i class="bi ${iconClass} empty-state-icon"></i>
                <h6 class="fw-bold text-dark mb-1">${emptyTitle}</h6>
                <p class="small text-muted mb-0">Tidak ada rincian data ditemukan</p>
              </div>
            `;
          }
        };

        switch (tab) {
          case 'general':
            const requests = data.employee_requests || [];
            const hasPendingApproval = requests.some(r => r.approved_status === null);

            content = `
              <div class="emp-card">
                <div class="emp-card-header">
                  <span><i class="bi bi-person-badge-fill text-primary me-1"></i> Identitas Pegawai</span>
                  <span class="badge bg-primary-subtle text-primary">${data.nik || '-'}</span>
                </div>
                <div class="emp-field-group">
                  ${generateEmpField('NAMA LENGKAP', data.fullname, true)}
                  ${generateEmpField('NAMA PANGGILAN', data.nickname)}
                  ${generateEmpField('TANGGAL MASUK', data.join_date)}
                  ${generateEmpField('PERUSAHAAN', data.company?.name, true)}
                  ${generateEmpField('ORGANISASI', data.organization?.name, true)}
                  ${generateEmpField('DIVISI', data.division?.name)}
                  ${generateEmpField('PENEMPATAN', data.placement?.name)}
                  ${generateEmpField('SISA CUTI', data.leave_saldo !== undefined ? data.leave_saldo + ' Hari' : '-')}
                </div>
              </div>

              <div class="emp-card">
                <div class="emp-card-header">
                  <span><i class="bi bi-envelope-at-fill text-primary me-1"></i> Kontak & Alamat</span>
                </div>
                <div class="emp-field-group">
                  ${generateEmpField('EMAIL', data.email, true)}
                  ${generateEmpField('NO. TELEPON', data.phone, true)}
                  ${generateEmpField('ALAMAT TINGGAL', data.address, true)}
                  ${generateEmpField('KOTA TINGGAL', data.address_city?.city)}
                  ${generateEmpField('PROVINSI TINGGAL', data.address_province?.province)}
                  ${generateEmpField('ALAMAT KTP', data.address_permanent, true)}
                </div>
              </div>

              <div class="emp-card">
                <div class="emp-card-header">
                  <span><i class="bi bi-info-circle-fill text-primary me-1"></i> Data Pribadi & Rekening</span>
                </div>
                <div class="emp-field-group">
                  ${generateEmpField('TEMPAT LAHIR', data.birth_place)}
                  ${generateEmpField('TANGGAL LAHIR', data.birth_date)}
                  ${generateEmpField('JENIS KELAMIN', data.gender?.name)}
                  ${generateEmpField('STATUS NIKAH', data.marital?.name)}
                  ${generateEmpField('AGAMA', data.religion?.name)}
                  ${generateEmpField('BANK', data.bank?.name)}
                  ${generateEmpField('NO. REKENING', data.bank_account, true)}
                </div>
              </div>

              <div class="emp-card">
                <div class="emp-card-header">
                  <span><i class="bi bi-telephone-plus-fill text-danger me-1"></i> Kontak Darurat</span>
                </div>
                <div class="emp-field-group">
                  ${generateEmpField('HUBUNGAN', data.emergency_relation?.name)}
                  ${generateEmpField('NAMA KONTAK', data.emergency_contact_name)}
                  ${generateEmpField('NO. TELEPON', data.emergency_contact_phone, true)}
                </div>
              </div>

              <button class="btn-action-primary edit-general" ${hasPendingApproval ? "disabled" : ""}>
                <i class="bi bi-pencil-square"></i> ${hasPendingApproval ? "Permohonan Dalam Proses" : "Ajukan Perubahan Data"}
              </button>
            `;
            break;

          case 'contract':
            const contractFields = [
              { label: 'STATUS KONTRAK', key: 'status', nestedKey: 'name' },
              { label: 'TGL MULAI', key: 'start_date' },
              { label: 'TGL SELESAI', key: 'end_date' },
              { label: 'KETERANGAN', key: 'description', full: true }
            ];
            content = generateEmpCards(data.contracts, contractFields, 'Belum Ada Data Kontrak', 'bi-file-earmark-text');
            break;

          case 'career':
            const careerFields = [
              { label: 'STATUS KARIR', key: 'career', nestedKey: 'name' },
              { label: 'TANGGAL', key: 'date' },
              { label: 'ORGANISASI', key: 'organization', nestedKey: 'name', full: true },
              { label: 'PENEMPATAN', key: 'placement', nestedKey: 'name', full: true },
              { label: 'KETERANGAN', key: 'description', full: true }
            ];
            content = generateEmpCards(data.careers, careerFields, 'Belum Ada Data Karir', 'bi-graph-up-arrow');
            break;

          case 'citizen':
            const citizenFields = [
              { label: 'DOKUMEN IDENTITAS', key: 'citizen', nestedKey: 'name' },
              { label: 'NOMOR / VALUE', key: 'value' },
              { label: 'KETERANGAN', key: 'description', full: true }
            ];
            content = generateEmpCards(data.citizens, citizenFields, 'Belum Ada Data Identitas', 'bi-card-heading');
            break;

          case 'education':
            const educationFields = [
              { label: 'JENJANG', key: 'education', nestedKey: 'name' },
              { label: 'JURUSAN', key: 'major', nestedKey: 'name' },
              { label: 'INSTITUSI / SEKOLAH', key: 'institution', full: true },
              { label: 'TAHUN LULUS', key: 'graduate' },
              { label: 'IPK / NILAI', key: 'ipk' }
            ];
            content = generateEmpCards(data.educations, educationFields, 'Belum Ada Data Pendidikan', 'bi-mortarboard');
            break;

          case 'family':
            const familyFields = [
              { label: 'NAMA ANGGOTA', key: 'name', full: true },
              { label: 'HUBUNGAN', key: 'relation', nestedKey: 'name' },
              { label: 'NIK KELUARGA', key: 'nik' },
              { label: 'PEKERJAAN', key: 'occupation', nestedKey: 'name' },
              { label: 'NO. TELEPON', key: 'phone' }
            ];
            content = generateEmpCards(data.families, familyFields, 'Belum Ada Data Keluarga', 'bi-people');
            break;

          case 'experience':
            const expFields = [
              { label: 'PERUSAHAAN', key: 'name', full: true },
              { label: 'JABATAN / POSISI', key: 'job_title', full: true },
              { label: 'PERIODE', key: 'start_date' },
              { label: 'SAMPAI', key: 'end_date' },
              { label: 'ALASAN RESIGN', key: 'reason_leaving', full: true }
            ];
            content = generateEmpCards(data.job_experiences, expFields, 'Belum Ada Pengalaman Kerja', 'bi-briefcase');
            break;

          case 'training':
            const trainingFields = [
              { label: 'JUDUL PELATIHAN', key: 'title', full: true },
              { label: 'LOKASI', key: 'location', full: true },
              { label: 'TGL MULAI', key: 'start_date' },
              { label: 'TGL SELESAI', key: 'end_date' }
            ];
            content = generateEmpCards(data.trainings, trainingFields, 'Belum Ada Data Pelatihan', 'bi-award');
            break;
        }

        $('#tab-content').html(content);

        $('.edit-general').on('click', function() {
          openEditModal('general', data);
        });
      }

      function openEditModal(tab, data) {
        let fields = [
          { label: 'Nama Lengkap', key: 'fullname' },
          { label: 'NIK', key: 'nik' },
          { label: 'Nama Panggilan', key: 'nickname' },
          { label: 'Tanggal Masuk', key: 'join_date', type: 'date' },
          { label: 'Alamat Email', key: 'email', type: 'email' },
          { label: 'No. Telepon', key: 'phone' },
          { label: 'Tempat Lahir', key: 'birth_place' },
          { label: 'Tanggal Lahir', key: 'birth_date', type: 'date' },
          { label: 'Alamat Tempat Tinggal', key: 'address' }
        ];

        let modalContent = `
          <div class="modal-header">
            <h5 class="modal-title fw-bold">Ajukan Perubahan Data</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="editFormModal">
        `;

        fields.forEach(field => {
          const val = data ? (data[field.key] || '') : '';
          modalContent += `
            <div class="form-group mb-3">
              <label class="form-label small fw-bold text-muted">${field.label}</label>
              <input type="${field.type || 'text'}" class="form-control" name="${field.key}" value="${val}">
            </div>
          `;
        });

        modalContent += `
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Batal</button>
            <button type="button" class="btn btn-primary fw-bold save-item">Kirim Request</button>
          </div>
        `;

        $('#editModal').remove();
        $('body').append(`<div class="modal fade" id="editModal" tabindex="-1" role="dialog"><div class="modal-dialog modal-dialog-centered" role="document"><div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">${modalContent}</div></div></div>`);

        $('#editModal').modal('show');

        $('.save-item').on('click', function() {
          const formData = new FormData($('#editFormModal')[0]);
          formData.append('_token', '{{ csrf_token() }}');
          formData.append('employ_id', userEmployee.id);

          showLoading();
          $.ajax({
            url: `{{ route('employee.request.create') }}`,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
              hideLoading();
              showAlert('success', response.message || 'Permohonan berhasil dikirim!');
              $('#editModal').modal('hide');
              userEmployee = null;
              loadTabContent('general');
            },
            error: function(xhr) {
              hideLoading();
              showAlert('danger', xhr.responseJSON?.message || 'Gagal mengirim permohonan.');
            }
          });
        });
      }

      loadTabContent('general');
    });
  </script>
@endsection
