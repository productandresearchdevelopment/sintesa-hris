@extends('templates.mobile')

@section('head')
  <style>
    html,
    body {
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

    .btn-action-primary:disabled,
    .btn-action-primary[disabled] {
      background: #e2e8f0 !important;
      color: #94a3b8 !important;
      border: 1px solid #cbd5e1 !important;
      box-shadow: none !important;
      cursor: not-allowed !important;
      pointer-events: none !important;
      opacity: 0.9 !important;
      transform: none !important;
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
        <h1 class="header-page-title">Employee Data</h1>
        <button type="button" class="btn-back-link edit-general" id="btnHeaderEdit" title="Request Data Edit">
          <i class="bi bi-pencil-square"></i>
        </button>
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
            <i class="bi bi-file-earmark-text-fill"></i> Contract
          </button>
          <button class="nav-item" data-tab="career">
            <i class="bi bi-graph-up-arrow"></i> Career
          </button>
          <button class="nav-item" data-tab="citizen">
            <i class="bi bi-card-heading"></i> Identity
          </button>
          <button class="nav-item" data-tab="education">
            <i class="bi bi-mortarboard-fill"></i> Education
          </button>
          <button class="nav-item" data-tab="family">
            <i class="bi bi-people-fill"></i> Family
          </button>
          <button class="nav-item" data-tab="experience">
            <i class="bi bi-briefcase-fill"></i> Experience
          </button>
          <button class="nav-item" data-tab="training">
            <i class="bi bi-award-fill"></i> Training
          </button>
        </div>
        <button type="button" class="btn-tab-scroll-right" id="btnTabScrollRight" title="Scroll Tab Right">
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
      const organizations = @json($organizations ?? []);
      const divisions = @json($divisions ?? []);
      const companies = @json($companies ?? []);
      const placements = @json($placements ?? []);
      const genders = @json($genders ?? []);
      const maritals = @json($maritals ?? []);
      const religions = @json($religions ?? []);
      const banks = @json($banks ?? []);
      const emergencyRelations = @json($emergency_relations ?? []);
      const cities = @json($cities ?? []);

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
              <span class="small fw-bold text-dark text-truncate">${file.filename_origin || 'Attachment File'}</span>
            </div>
            <a href="${fileUrl}" target="_blank" class="btn btn-sm btn-primary rounded-pill px-3">
              <i class="bi bi-download"></i> Download
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
        this.scrollIntoView({
          behavior: 'smooth',
          inline: 'center',
          block: 'nearest'
        });
        loadTabContent(tab);
      });

      async function loadTabContent(tab, forceReload = false) {
        if (userEmployee === null || responseDataAllTables === null || forceReload) {
          showLoading();

          try {
            const response = await $.ajax({
              url: "{{ route('employee.get') }}" + '/' + user.employ_id,
              type: 'GET'
            });

            userEmployee = response;
            if (responseDataAllTables === null || forceReload) {
              responseDataAllTables = await fetchAllTableData();
            }
            populateTabContent(tab, response);
          } catch (error) {
            console.error('Failed to fetch employee data.', error);
            showAlert('danger', 'Failed to load employee data.');
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
                  val = new Date(val).toLocaleDateString('en-US', {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric'
                  });
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
                <p class="small text-muted mb-0">No data details found</p>
              </div>
            `;
          }
        };

        switch (tab) {
          case 'general':
            const requests = (data && data.employee_requests) ? data.employee_requests : [];
            const hasPendingApproval = requests.some(r => r.approved_status === null || r.approved_status === 'null' || r.approved_status === undefined || r.approved_by === null);

            content = `
              <div class="emp-card">
                <div class="emp-card-header">
                  <span><i class="bi bi-person-badge-fill text-primary me-1"></i> Employee Identity</span>
                  <span class="badge bg-primary-subtle text-primary">${data.nik || '-'}</span>
                </div>
                <div class="emp-field-group">
                  ${generateEmpField('FULL NAME', data.fullname, true)}
                  ${generateEmpField('NICKNAME', data.nickname)}
                  ${generateEmpField('JOIN DATE', data.join_date)}
                  ${generateEmpField('COMPANY', data.company?.name, true)}
                  ${generateEmpField('ORGANIZATION', data.organization?.name, true)}
                  ${generateEmpField('DIVISION', data.division?.name)}
                  ${generateEmpField('PLACEMENT', data.placement?.name)}
                  ${generateEmpField('LEAVE BALANCE', data.leave_saldo !== undefined ? data.leave_saldo + ' Days' : '-')}
                </div>
              </div>

              <div class="emp-card">
                <div class="emp-card-header">
                  <span><i class="bi bi-envelope-at-fill text-primary me-1"></i> Contact & Address</span>
                </div>
                <div class="emp-field-group">
                  ${generateEmpField('EMAIL', data.email, true)}
                  ${generateEmpField('PHONE NUMBER', data.phone, true)}
                  ${generateEmpField('RESIDENTIAL ADDRESS', data.address, true)}
                  ${generateEmpField('RESIDENTIAL CITY', data.address_city?.city)}
                  ${generateEmpField('RESIDENTIAL PROVINCE', data.address_province?.province)}
                  ${generateEmpField('ID CARD ADDRESS', data.address_permanent, true)}
                </div>
              </div>

              <div class="emp-card">
                <div class="emp-card-header">
                  <span><i class="bi bi-info-circle-fill text-primary me-1"></i> Personal Data & Bank Account</span>
                </div>
                <div class="emp-field-group">
                  ${generateEmpField('PLACE OF BIRTH', data.birth_place)}
                  ${generateEmpField('DATE OF BIRTH', data.birth_date)}
                  ${generateEmpField('GENDER', data.gender?.name)}
                  ${generateEmpField('MARITAL STATUS', data.marital?.name)}
                  ${generateEmpField('RELIGION', data.religion?.name)}
                  ${generateEmpField('BANK', data.bank?.name)}
                  ${generateEmpField('ACCOUNT NUMBER', data.bank_account, true)}
                </div>
              </div>

              <div class="emp-card">
                <div class="emp-card-header">
                  <span><i class="bi bi-telephone-plus-fill text-danger me-1"></i> Emergency Contact</span>
                </div>
                <div class="emp-field-group">
                  ${generateEmpField('RELATIONSHIP', data.emergency_relation?.name)}
                  ${generateEmpField('CONTACT NAME', data.emergency_contact_name)}
                  ${generateEmpField('PHONE NUMBER', data.emergency_contact_phone, true)}
                </div>
              </div>

              <button class="btn-action-primary edit-general" ${hasPendingApproval ? "disabled" : ""}>
                <i class="bi ${hasPendingApproval ? 'bi-hourglass-split' : 'bi-pencil-square'}"></i>
                ${hasPendingApproval ? "Menunggu Persetujuan (On Request)" : "Request Data Change"}
              </button>
            `;
            break;

          case 'contract':
            const contractFields = [{
                label: 'CONTRACT STATUS',
                key: 'status',
                nestedKey: 'name'
              },
              {
                label: 'START DATE',
                key: 'start_date'
              },
              {
                label: 'END DATE',
                key: 'end_date'
              },
              {
                label: 'DESCRIPTION',
                key: 'description',
                full: true
              }
            ];
            content = generateEmpCards(data.contracts, contractFields, 'No Contract Data Available',
              'bi-file-earmark-text');
            break;

          case 'career':
            const careerFields = [{
                label: 'CAREER STATUS',
                key: 'career',
                nestedKey: 'name'
              },
              {
                label: 'DATE',
                key: 'date'
              },
              {
                label: 'ORGANIZATION',
                key: 'organization',
                nestedKey: 'name',
                full: true
              },
              {
                label: 'PLACEMENT',
                key: 'placement',
                nestedKey: 'name',
                full: true
              },
              {
                label: 'DESCRIPTION',
                key: 'description',
                full: true
              }
            ];
            content = generateEmpCards(data.careers, careerFields, 'No Career Data Available', 'bi-graph-up-arrow');
            break;

          case 'citizen':
            const citizenFields = [{
                label: 'IDENTITY DOCUMENT',
                key: 'citizen',
                nestedKey: 'name'
              },
              {
                label: 'NUMBER / VALUE',
                key: 'value'
              },
              {
                label: 'DESCRIPTION',
                key: 'description',
                full: true
              }
            ];
            content = generateEmpCards(data.citizens, citizenFields, 'No Identity Data Available', 'bi-card-heading');
            break;

          case 'education':
            const educationFields = [{
                label: 'DEGREE LEVEL',
                key: 'education',
                nestedKey: 'name'
              },
              {
                label: 'MAJOR',
                key: 'major',
                nestedKey: 'name'
              },
              {
                label: 'INSTITUTION / SCHOOL',
                key: 'institution',
                full: true
              },
              {
                label: 'GRADUATION YEAR',
                key: 'graduate'
              },
              {
                label: 'GPA / SCORE',
                key: 'ipk'
              }
            ];
            content = generateEmpCards(data.educations, educationFields, 'No Education Data Available',
              'bi-mortarboard');
            break;

          case 'family':
            const familyFields = [{
                label: 'MEMBER NAME',
                key: 'name',
                full: true
              },
              {
                label: 'RELATIONSHIP',
                key: 'relation',
                nestedKey: 'name'
              },
              {
                label: 'FAMILY ID (NIK)',
                key: 'nik'
              },
              {
                label: 'OCCUPATION',
                key: 'occupation',
                nestedKey: 'name'
              },
              {
                label: 'PHONE NUMBER',
                key: 'phone'
              }
            ];
            content = generateEmpCards(data.families, familyFields, 'No Family Data Available', 'bi-people');
            break;

          case 'experience':
            const expFields = [{
                label: 'COMPANY',
                key: 'name',
                full: true
              },
              {
                label: 'JOB TITLE / POSITION',
                key: 'job_title',
                full: true
              },
              {
                label: 'START DATE',
                key: 'start_date'
              },
              {
                label: 'END DATE',
                key: 'end_date'
              },
              {
                label: 'REASON FOR LEAVING',
                key: 'reason_leaving',
                full: true
              }
            ];
            content = generateEmpCards(data.job_experiences, expFields, 'No Work Experience Data', 'bi-briefcase');
            break;

          case 'training':
            const trainingFields = [{
                label: 'TRAINING TITLE',
                key: 'title',
                full: true
              },
              {
                label: 'LOCATION',
                key: 'location',
                full: true
              },
              {
                label: 'START DATE',
                key: 'start_date'
              },
              {
                label: 'END DATE',
                key: 'end_date'
              }
            ];
            content = generateEmpCards(data.trainings, trainingFields, 'No Training Data Available', 'bi-award');
            break;
        }

        $('#tab-content').html(content);

        $(document).off('click', '.edit-general').on('click', '.edit-general', function(e) {
          e.preventDefault();
          if ($(this).is(':disabled') || $(this).attr('disabled')) {
            return false;
          }
          openEditModal('general', userEmployee || data);
        });
      }

      function openEditModal(tab, data) {
        const formatDateVal = (val) => val ? String(val).substring(0, 10) : '';

        const buildInput = (name, label, val, type = 'text') => `
          <div class="form-group mb-3">
            <label class="form-label small fw-bold text-muted">${label}</label>
            <input type="${type}" class="form-control rounded-3" name="${name}" value="${val ?? ''}">
          </div>
        `;

        const buildTextarea = (name, label, val, rows = 2) => `
          <div class="form-group mb-3">
            <label class="form-label small fw-bold text-muted">${label}</label>
            <textarea class="form-control rounded-3" name="${name}" rows="${rows}">${val ?? ''}</textarea>
          </div>
        `;

        const buildSelect = (name, label, items, selectedVal, textKey = 'name') => {
          let selectedId = selectedVal;
          if (typeof selectedVal === 'object' && selectedVal !== null) {
            selectedId = selectedVal.id;
          }
          let options = `<option value="">Select ${label}</option>`;
          if (items && items.length) {
            items.forEach(item => {
              const isSelected = selectedId && (item.id == selectedId || String(item.id) === String(
                selectedId)) ? 'selected' : '';
              const text = item[textKey] || item.name || item.city || item.province || '';
              options += `<option value="${item.id}" ${isSelected}>${text}</option>`;
            });
          }
          return `
            <div class="form-group mb-3">
              <label class="form-label small fw-bold text-muted">${label}</label>
              <select class="form-select rounded-3" name="${name}">
                ${options}
              </select>
            </div>
          `;
        };

        const buildSectionHeader = (icon, title, colorClass = 'text-primary') => `
          <div class="d-flex align-items-center gap-2 mb-3 mt-3 pb-2 border-bottom">
            <i class="bi ${icon} ${colorClass} fs-6"></i>
            <span class="fw-bold text-dark small text-uppercase" style="letter-spacing: 0.5px;">${title}</span>
          </div>
        `;

        let modalContent = `
          <div class="modal-header border-bottom">
            <div class="d-flex align-items-center gap-2">
              <i class="bi bi-pencil-square text-primary fs-5"></i>
              <h6 class="modal-title fw-bold mb-0">Request Data Change</h6>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" style="max-height: 70vh; overflow-y: auto; padding: 20px;">
            <form id="editFormModal">
              <!-- Section 1: Employee Identity -->
              ${buildSectionHeader('bi-person-badge-fill', 'Employee Identity', 'text-primary')}
              ${buildInput('fullname', 'Full Name', data?.fullname)}
              ${buildInput('nickname', 'Nickname', data?.nickname)}
              ${buildInput('nik', 'NIK', data?.nik)}
              ${buildInput('join_date', 'Join Date', formatDateVal(data?.join_date), 'date')}
              ${buildSelect('company_id', 'Company', companies, data?.company_id ?? data?.company?.id)}
              ${buildSelect('org_id', 'Organization', organizations, data?.org_id ?? data?.organization?.id)}
              ${buildSelect('division_id', 'Division', divisions, data?.division_id ?? data?.division?.id)}
              ${buildSelect('placement_id', 'Placement', placements, data?.placement_id ?? data?.placement?.id)}
              ${buildInput('leave_saldo', 'Leave Saldo', data?.leave_saldo, 'number')}

              <!-- Section 2: Contact & Address -->
              ${buildSectionHeader('bi-envelope-at-fill', 'Contact & Address', 'text-primary')}
              ${buildInput('email', 'Email Address', data?.email, 'email')}
              ${buildInput('phone', 'Phone Number', data?.phone, 'tel')}
              ${buildTextarea('address', 'Residential Address', data?.address)}
              ${buildSelect('address_city_id', 'Residential City', cities, data?.address_city_id ?? data?.address_city?.id, 'city')}
              ${buildSelect('address_province_id', 'Residential Province', cities, data?.address_province_id ?? data?.address_province?.id, 'province')}
              ${buildTextarea('address_permanent', 'ID Card / Permanent Address', data?.address_permanent)}
              ${buildSelect('address_permanent_city_id', 'Permanent City', cities, data?.address_permanent_city_id ?? data?.address_permanent_city?.id, 'city')}
              ${buildSelect('address_permanent_province_id', 'Permanent Province', cities, data?.address_permanent_province_id ?? data?.address_permanent_province?.id, 'province')}

              <!-- Section 3: Personal Data & Bank Account -->
              ${buildSectionHeader('bi-info-circle-fill', 'Personal Data & Bank Account', 'text-primary')}
              ${buildInput('birth_place', 'Place of Birth', data?.birth_place)}
              ${buildInput('birth_date', 'Date of Birth', formatDateVal(data?.birth_date), 'date')}
              ${buildSelect('gender_id', 'Gender', genders, data?.gender_id ?? data?.gender?.id)}
              ${buildSelect('marital_id', 'Marital Status', maritals, data?.marital_id ?? data?.marital?.id)}
              ${buildSelect('religion_id', 'Religion', religions, data?.religion_id ?? data?.religion?.id)}
              ${buildSelect('bank_id', 'Bank', banks, data?.bank_id ?? data?.bank?.id)}
              ${buildInput('bank_account', 'Bank Account Number', data?.bank_account)}

              <!-- Section 4: Emergency Contact -->
              ${buildSectionHeader('bi-telephone-plus-fill', 'Emergency Contact', 'text-danger')}
              ${buildSelect('emergency_relation_id', 'Emergency Relation', emergencyRelations, data?.emergency_relation_id ?? data?.emergency_relation?.id)}
              ${buildInput('emergency_contact_name', 'Emergency Contact Name', data?.emergency_contact_name)}
              ${buildInput('emergency_contact_phone', 'Emergency Contact Phone', data?.emergency_contact_phone, 'tel')}
              ${buildTextarea('emergency_contact_address', 'Emergency Contact Address', data?.emergency_contact_address)}
            </form>
          </div>
          <div class="modal-footer border-top bg-light">
            <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary rounded-pill px-4 fw-bold save-item">Submit Request</button>
          </div>
        `;

        $('#editModal').remove();
        $('body').append(
          `<div class="modal fade" id="editModal" tabindex="-1" role="dialog"><div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document"><div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">${modalContent}</div></div></div>`
          );

        $('#editModal').modal('show');

        $('.save-item').off('click').on('click', function() {
          const formData = new FormData($('#editFormModal')[0]);
          formData.append('_token', '{{ csrf_token() }}');
          formData.append('employ_id', (userEmployee || data).id);

          showLoading();
          $.ajax({
            url: `{{ route('employee.request.create') }}`,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: async function(response) {
              hideLoading();
              showAlert('success', response.message || 'Request submitted successfully!');
              $('#editModal').modal('hide');
              userEmployee = null;
              responseDataAllTables = null;
              await loadTabContent('general', true);
            },
            error: function(xhr) {
              hideLoading();
              showAlert('danger', xhr.responseJSON?.message || 'Failed to submit request.');
            }
          });
        });
      }

      loadTabContent('general');
    });
  </script>
@endsection
