@extends('templates.mobile')

@section('head')
  <style>
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
      color: var(--dark);
      cursor: pointer;
      background: none;
      border: none;
      text-align: center;
      font-size: 16px;
      padding: 12px 24px;
    }

    .nav-item.active {
      background-color: var(--primary-color);
      color: var(--white);
      border-radius: var(--radius-xl);
    }

    .tab-container {
      margin: 20px 20px 0 20px;
    }

    #org_id.jstree {
      border: 1px solid #dee2e6;
      border-radius: 0.375rem;
      padding: 0.375rem 0.75rem;
    }

    #org_id .jstree-anchor {
      font-size: 12px;
    }

    .profile-form {
      background-color: var(--white);
      border: 1px solid var(--gray-medium);
      border-radius: var(--radius-xl);
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      padding: 20px;
      max-width: 600px;
      margin: auto;
    }

    .profile-field {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px 0;
      width: 100%;
      border-bottom: 1px solid #e0e0e0;
    }

    .field-label {
      font-weight: bold;
      color: #555;
    }

    .field-value {
      color: #777;
      text-align: right;
      flex-grow: 1;
      padding-left: 20px;
    }

    .profile-list {
      margin: 20px 0;
    }

    .profile-card {
      background: #f9f9f9;
      border: 1px solid #e0e0e0;
      border-radius: 8px;
      padding: 15px;
      margin-bottom: 15px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .profile-card p {
      margin: 5px 0;
    }

    .profile-card strong {
      color: #333;
    }

    .profile-card:last-child {
      margin-bottom: 0;
    }

    .profile-card-file {
      width: 100%;
      height: 100px;
      object-fit: cover;
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
        <span class="profile-title">Employee</span>
        @if ($user->employee->photo_id)
          @php
            $urlFile = route('file', $user->employee->photo_id);
          @endphp
          <div class="profile-avatar-wrapper">
            <img src="{{ $urlFile }}" alt="Info" class="profile-avatar">
            <input type="file" class="avatar-input" accept="image/*" style="display: none;">
          </div>
        @else
          <div class="profile-avatar-wrapper">
            <img src="{{ asset('/images/image-no-user.png') }}" alt="no-user" class="profile-avatar">
            <input type="file" class="avatar-input" accept="image/*" style="display: none;">
          </div>
        @endif
        <div class="profile-info">
          <div class="profile-wrapper">
            <h3 class="profile-name"> {{ strtoupper($user->employee->fullname) ?? 'User Name' }} </h3>
            <i class="profile-badge fas fa-square-check"></i>
          </div>
        </div>
      </div>

    </div>

    <div class="profile-nav">
      <button class="nav-item active" data-tab="general">General</button>
      <button class="nav-item" data-tab="contract">Contract</button>
      <button class="nav-item" data-tab="career">Career</button>
      <button class="nav-item" data-tab="citizen">Citizen</button>
      <button class="nav-item" data-tab="education">Education</button>
      <button class="nav-item" data-tab="family">Family</button>
      <button class="nav-item" data-tab="experience">Experience</button>
      <button class="nav-item" data-tab="training">Training</button>
    </div>

    <div id="tab-container" class="tab-container">
      <div id="tab-content">
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      const user = @json($user);
      const organizations = @json($organizations);
      let userEmployee = null;
      let responseDataAllTables = null;

      function generateFileComponent(file) {
        if (!file) return '';

        const fileUrl = `{{ route('file', ':id') }}`.replace(':id', file.id);
        const fileExtension = file.filename_origin.split('.').pop().toLowerCase();

        const iconMap = {
          'xls': 'bi-file-earmark-excel-fill',
          'xlsx': 'bi-file-earmark-excel-fill',
          'doc': 'bi-file-earmark-word-fill',
          'docx': 'bi-file-earmark-word-fill',
          'ppt': 'bi-file-earmark-ppt-fill',
          'pptx': 'bi-file-earmark-ppt-fill',
          'txt': 'bi-file-earmark-text-fill',
          'pdf': 'bi-file-earmark-pdf-fill',
          'zip': 'bi-file-earmark-zip-fill',
          'rar': 'bi-file-earmark-zip-fill',
          'mp3': 'bi-file-earmark-music-fill',
          'mp4': 'bi-file-earmark-play-fill'
        };

        const colorMap = {
          'xls': '#068800',
          'xlsx': '#068800',
          'doc': '#145adc',
          'docx': '#145adc',
          'ppt': '#ff9000',
          'pptx': '#ff9000',
          'txt': '#0654af',
          'pdf': '#e10a0a',
          'zip': '#8100ce',
          'rar': '#8100ce',
          'mp3': '#ffa200',
          'mp4': '#A02334'
        };

        const iconClass = iconMap[fileExtension] || 'bi-file-earmark-fill';
        const iconColor = colorMap[fileExtension] || '#666666';

        const fileContent = fileExtension === 'pdf' ?
          `<i class="${iconClass}" style="font-size: 40px; color: ${iconColor};"></i>
                 <p class="profile-card-file-name m-0 text-center">${file.filename_origin}</p>` :
          fileExtension.match(/jpg|jpeg|png|gif/) ?
          `<img src="${fileUrl}" alt="${file.filename_origin}" class="profile-card-file" />` :
          `<i class="${iconClass}" style="font-size: 40px; color: ${iconColor};"></i>
                 <p class="profile-card-file-name m-0 text-center">${file.filename_origin}</p>`;

        return `
            <div class="d-flex justify-content-center w-full mb-4">
                <a href="${fileUrl}" ${fileExtension === 'pdf' || fileExtension.match(/jpg|jpeg|png|gif/) ? 'target="_blank"' : 'download'}
                    class="profile-card-file-link d-flex flex-column justify-content-center align-items-center text-decoration-none text-black">
                    ${fileContent}
                </a>
            </div>`;
      }

      $('.nav-item').on('click', function() {
        const tab = $(this).data('tab');
        $('.nav-item').removeClass('active');
        $(this).addClass('active');
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
            console.error('Failed to fetch profile data.', error);
          } finally {
            hideLoading();
          }
        } else {
          populateTabContent(tab, userEmployee);
        }
      }

      async function populateTabContent(tab, data) {
        let content = '';

        const generateProfileField = (label, value) => `
                <div class="profile-field">
                    <div class="field-label">${label}</div>
                    <div class="field-value">${value || ''}</div>
                </div>
            `;

        const generateProfileCard = (items, fields, noItemsMessage, tabName) => {
          if (items.length > 0) {
            const itemElements = items.map(item => {
              let itemContent = '';

              if (item.file) {
                itemContent += generateFileComponent(item.file);
              }

              fields.forEach(field => {
                const {
                  label,
                  key,
                  nestedKey
                } = field;
                let value = item[key];

                if (nestedKey) {
                  value = item[key]?.[nestedKey];
                }

                if (key === 'start_date' || key === 'end_date' || key === 'date' || key === 'graduate') {
                  value = value ?
                    new Date(value).toLocaleDateString('id-ID', {
                      day: '2-digit',
                      month: 'short',
                      year: 'numeric'
                    }) :
                    'N/A';
                }

                itemContent += `<p><strong>${label}:</strong> ${value || 'N/A'}</p>`;
              });

              //   itemContent += `
            //                 <div class="profile-card-actions">
            //                     <button class="btn btn-primary edit-item" data-tab="${tabName}" data-id="${item.id}">Edit</button>
            //                     <button class="btn btn-danger delete-item" data-tab="${tabName}" data-id="${item.id}">Delete</button>
            //                 </div>
            //             `;

              return `<div class="profile-card">${itemContent}</div>`;
            }).join('');
            return `<div class="profile-list">${itemElements}</div>`;
          } else {
            return `<div class="profile-list">
                <img src="{{ asset('images/nodata.png') }}" alt="No Items" style="width: 100%; max-height: 300px;" />
                <p class="text-center">${noItemsMessage}</p>
                </div>`;
          }
        };

        switch (tab) {
          case 'general':

            const hasPendingApproval = userEmployee.employee_requests.some(request => request.approved_status ===
              null);

            content = `
                        ${generateProfileField('Full Name', data.fullname)}
                        ${generateProfileField('NIK', data.nik)}
                        ${generateProfileField('Nickname', data.nickname)}
                        ${generateProfileField('Join Date', data.join_date)}
                        ${generateProfileField('Company', data.company?.name)}
                        ${generateProfileField('Organization', data.organization?.name)}
                        ${generateProfileField('Placement', data.placement?.name)}
                        ${generateProfileField('Division', data.division?.name)}
                        ${generateProfileField('Leave Balance', data.leave_saldo)}
                        ${generateProfileField('Birth Place', data.birth_place)}
                        ${generateProfileField('Birth Date', data.birth_date)}
                        ${generateProfileField('Phone', data.phone)}
                        ${generateProfileField('Email', data.email)}
                        ${generateProfileField('Gender', data.gender?.name)}
                        ${generateProfileField('Marital Status', data.marital?.name)}
                        ${generateProfileField('Religion', data.religion?.name)}
                        ${generateProfileField('Current Address', data.address)}
                        ${generateProfileField('Current City', data.address_city?.city)}
                        ${generateProfileField('Current Province', data.address_province?.province)}
                        ${generateProfileField('Permanent Address', data.address_permanent)}
                        ${generateProfileField('Permanent City', data.address_permanent_city?.city)}
                        ${generateProfileField('Permanent Province', data.address_permanent_province?.province)}
                        ${generateProfileField('Bank', data.bank?.name)}
                        ${generateProfileField('Bank Account', data.bank_account)}
                        ${generateProfileField('Emergency Relation', data.emergency_relation?.name)}
                        ${generateProfileField('Emergency Relation Name', data.emergency_contact_name)}
                        ${generateProfileField('Emergency Relation Phone', data.emergency_contact_phone)}
                        ${generateProfileField('Emergency Relation Address', data.emergency_contact_address)}
                        <button class="btn btn-primary edit-general mt-3" ${hasPendingApproval ? "disabled" : ""}>${hasPendingApproval ? "Pending Approval" : "Request Change"}</button>
                    `;
            break;

          case 'contract':
            const contractFields = [{
                label: 'Contract Status',
                key: 'status',
                nestedKey: 'name'
              },
              {
                label: 'Start Date',
                key: 'start_date'
              },
              {
                label: 'End Date',
                key: 'end_date'
              },
              {
                label: 'Description',
                key: 'description'
              }
            ];
            // content = `
          //             <button class="btn btn-success add-item" data-tab="contract">Add</button>
          //             ${generateProfileCard(data.contracts, contractFields, 'No contracts available', 'contract')}
          //         `;
            content = `
                        ${generateProfileCard(data.contracts, contractFields, 'No contracts available', 'contract')}
                    `;
            break;

          case 'career':
            const careerFields = [{
                label: 'Career Name',
                key: 'career',
                nestedKey: 'name'
              },
              {
                label: 'Date',
                key: 'date'
              },
              {
                label: 'Organization',
                key: 'organization',
                nestedKey: 'name'
              },
              {
                label: 'Placement',
                key: 'placement',
                nestedKey: 'name'
              },
              {
                label: 'Description',
                key: 'description'
              }
            ];
            content = `
                        ${generateProfileCard(data.careers, careerFields, 'No careers available', 'career')}
                    `;
            break;

          case 'citizen':
            const citizenFields = [{
                label: 'Identity',
                key: 'citizen',
                nestedKey: 'name'
              },
              {
                label: 'Value',
                key: 'value'
              },
              {
                label: 'Description',
                key: 'description'
              }
            ];
            content = `
                        ${generateProfileCard(data.citizens, citizenFields, 'No citizens available', 'citizen')}
                    `;
            break;

          case 'education':
            const educationFields = [{
                label: 'Education',
                key: 'education',
                nestedKey: 'name'
              },
              {
                label: 'Major',
                key: 'major',
                nestedKey: 'name'
              },
              {
                label: 'Graduate',
                key: 'graduate'
              },
              {
                label: 'IPK',
                key: 'ipk'
              },
              {
                label: 'Description',
                key: 'description'
              }
            ];
            content = `
                        ${generateProfileCard(data.educations, educationFields, 'No educations available', 'education')}
                    `;
            break;

          case 'family':
            const familyFields = [{
                label: 'NIK',
                key: 'nik'
              },
              {
                label: 'Name',
                key: 'name'
              },
              {
                label: 'Relation',
                key: 'relation',
                nestedKey: 'name'
              },
              {
                label: 'Occupation',
                key: 'occupation',
                nestedKey: 'name'
              },
              {
                label: 'Address',
                key: 'address'
              },
              {
                label: 'Phone',
                key: 'phone'
              }
            ];
            content = `
                        ${generateProfileCard(data.families, familyFields, 'No families available', 'family')}
                    `;
            break;

          case 'experience':
            const experienceFields = [{
                label: 'Name',
                key: 'name'
              },
              {
                label: 'Job Title',
                key: 'job_title'
              },
              {
                label: 'Job Description',
                key: 'job_description'
              },
              {
                label: 'Start Date',
                key: 'start_date'
              },
              {
                label: 'End Date',
                key: 'end_date'
              },
              {
                label: 'Reason Leaving',
                key: 'reason_leaving'
              }
            ];
            content = `
                        ${generateProfileCard(data.job_experiences, experienceFields, 'No job experiences available', 'experience')}
                    `;
            break;

          case 'training':
            const trainingFields = [{
                label: 'Title',
                key: 'title'
              },
              {
                label: 'Location',
                key: 'location'
              },
              {
                label: 'Start Date',
                key: 'start_date'
              },
              {
                label: 'End Date',
                key: 'end_date'
              },
              {
                label: 'Internal',
                key: 'is_internal'
              },
              {
                label: 'Certification',
                key: 'is_certification'
              },
              {
                label: 'Description',
                key: 'description'
              }
            ];
            content = `
                        ${generateProfileCard(data.trainings, trainingFields, 'No trainings available', 'training')}
                    `;
            break;

          default:
            content = '<div>No data available for this tab.</div>';
        }

        $('#tab-content').html(
          `
                    <div id="profileForm" class="profile-form">
                        ${content}
                    </div>
                `
        );

        // Initialize modal for editing
        $('#profileForm .edit-general').on('click', function() {
          openEditModal('general', data);
        });

        $('#profileForm .edit-item').on('click', function() {
          const tab = $(this).data('tab');
          const id = $(this).data('id');
          const item = userEmployee[tab].find(i => i.id === id);
          openEditModal(tab, item);
        });

        $('#profileForm .delete-item').on('click', function() {
          const tab = $(this).data('tab');
          const id = $(this).data('id');
          deleteItem(tab, id);
        });

        $('#profileForm .add-item').on('click', function() {
          const tab = $(this).data('tab');
          openEditModal(tab, null);
        });
      }

      function openEditModal(tab, data) {
        let modalContent = '';
        let fields = [];

        switch (tab) {
          case 'general':
            fields = [{
                label: 'Full Name',
                key: 'fullname'
              },
              {
                label: 'NIK',
                key: 'nik'
              },
              {
                label: 'Nickname',
                key: 'nickname'
              },
              {
                label: 'Join Date',
                key: 'join_date',
                type: 'date'
              },
              {
                label: 'Company',
                key: 'company_id',
                type: 'select',
                options: responseDataAllTables.companies.map(c => ({
                  value: c.id,
                  label: c.name
                }))
              },
              {
                label: 'Organization',
                key: 'org_id',
                type: 'jstree'
              },
              {
                label: 'Placement',
                key: 'placement_id',
                type: 'select',
                options: responseDataAllTables.placements.map(p => ({
                  value: p.id,
                  label: p.name
                }))
              },
              {
                label: 'Division',
                key: 'division_id',
                type: 'select',
                options: []
              },
              {
                label: 'Leave Balance',
                key: 'leave_saldo'
              },
              {
                label: 'Birth Place',
                key: 'birth_place'
              },
              {
                label: 'Birth Date',
                key: 'birth_date',
                type: 'date'
              },
              {
                label: 'Phone',
                key: 'phone'
              },
              {
                label: 'Email',
                key: 'email'
              },
              {
                label: 'Gender',
                key: 'gender_id',
                type: 'select',
                options: responseDataAllTables.genders.map(g => ({
                  value: g.id,
                  label: g.name
                }))
              },
              {
                label: 'Marital Status',
                key: 'marital_id',
                type: 'select',
                options: responseDataAllTables.maritals.map(m => ({
                  value: m.id,
                  label: m.name
                }))
              },
              {
                label: 'Religion',
                key: 'religion_id',
                type: 'select',
                options: responseDataAllTables.religions.map(r => ({
                  value: r.id,
                  label: r.name
                }))
              },
              {
                label: 'Current Address',
                key: 'address'
              },
              {
                label: 'Current City',
                key: 'address_city_id',
                type: 'select',
                options: responseDataAllTables.cities.map(c => ({
                  value: c.id,
                  label: c.city
                }))
              },
              {
                label: 'Current Province',
                key: 'address_province_id',
                type: 'select',
                options: responseDataAllTables.provinces.map(p => ({
                  value: p.id,
                  label: p.province
                }))
              },
              {
                label: 'Permanent Address',
                key: 'address_permanent'
              },
              {
                label: 'Permanent City',
                key: 'address_permanent_city_id',
                type: 'select',
                options: responseDataAllTables.cities.map(c => ({
                  value: c.id,
                  label: c.city
                }))
              },
              {
                label: 'Permanent Province',
                key: 'address_permanent_province_id',
                type: 'select',
                options: responseDataAllTables.provinces.map(p => ({
                  value: p.id,
                  label: p.province
                }))
              },
              {
                label: 'Bank',
                key: 'bank_id',
                type: 'select',
                options: responseDataAllTables.banks.map(b => ({
                  value: b.id,
                  label: b.name
                }))
              },
              {
                label: 'Bank Account',
                key: 'bank_account'
              },
              {
                label: 'Emergency Relation',
                key: 'emergency_relation_id',
                type: 'select',
                options: responseDataAllTables.emergencyRelations.map(e => ({
                  value: e.id,
                  label: e.name
                }))
              },
              {
                label: 'Emergency Relation Name',
                key: 'emergency_contact_name'
              },
              {
                label: 'Emergency Relation Phone',
                key: 'emergency_contact_phone'
              },
              {
                label: 'Emergency Relation Address',
                key: 'emergency_contact_address'
              },
              {
                label: 'Avatar',
                key: 'fileInputGeneral',
                type: 'file'
              }
            ];
            break;
          case 'contract':
            fields = [{
                label: 'Contract Status',
                key: 'status_id',
                type: 'select',
                options: userEmployee.contract_statuses.map(s => ({
                  value: s.id,
                  label: s.name
                }))
              },
              {
                label: 'Start Date',
                key: 'start_date',
                type: 'date'
              },
              {
                label: 'End Date',
                key: 'end_date',
                type: 'date'
              },
              {
                label: 'File',
                key: 'file_id',
                type: 'file'
              }
            ];
            break;
          default:
            fields = [];
        }

        modalContent += `<div class="modal-header">
                        <h5 class="modal-title">${data ? 'Edit' : 'Add'} ${tab.charAt(0).toUpperCase() + tab.slice(1)}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">`;

        fields.forEach(field => {
          const value = data ? data[field.key] : '';
          modalContent += `<div class="form-group mb-2">
                    <label class="form-label">${field.label}</label>`;

          if (field.type === 'select') {
            modalContent += `<select class="form-control" name="${field.key}">`;
            field.options.forEach(option => {
              modalContent +=
                `<option value="${option.value}" ${value == option.value ? 'selected' : ''}>${option.label}</option>`;
            });
            modalContent += `</select>`;
          } else if (field.type === 'jstree') {
            modalContent += `<div id="org_id"></div>`;
          } else if (field.type === 'file') {
            modalContent +=
              `<input type="file" class="form-control" name="${field.key}">`;
          } else {
            modalContent +=
              `<input type="${field.type || 'text'}" class="form-control" name="${field.key}" value="${value}">`;
          }

          modalContent += `</div>`;
        });

        modalContent += `</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary save-item" data-tab="${tab}" data-id="${data ? data.id : ''}">${data ? 'Save Changes' : 'Add Item'}</button>
                    </div>`;

        $('#editModal').remove();
        $('body').append(`<div class="modal fade" id="editModal" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">${modalContent}</div>
                        </div>
                    </div>`);

        $('#editModal').modal('show');

        $('select[name="company_id"]').on('change', function() {
          const companyId = $(this).val();
          updateOrganizationsTree(companyId);
          updateDivisions(companyId);
        });

        updateOrganizationsTree(data ? data.company_id : $('select[name="company_id"]').val());
        updateDivisions(data ? data.company_id : $('select[name="company_id"]').val());

        $('.save-item').on('click', function() {
          const tab = $(this).data('tab');
          const id = $(this).data('id');
          updateItem(tab, fields.reduce((acc, field) => {
            if (field.type === 'jstree') {
              acc[field.key] = organizationSelected;
            } else {
              const inputElement = $(`input[name="${field.key}"], select[name="${field.key}"]`);
              if (inputElement.length > 0) {
                acc[field.key] = inputElement.val();
              } else {
                console.error(`Element with name "${field.key}" not found.`);
              }
            }
            return acc;
          }, {}));
        });
      }

      function updateOrganizationsTree(companyId) {
        const filteredOrganizations = organizations.filter(item => item.company_id ==
          companyId);

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
          if (userEmployee.org_id) {
            jstreeInstance.select_node(userEmployee.org_id);
            jstreeInstance.open_node(userEmployee.org_id);
            organizationSelected = userEmployee.org_id;
          }
        }).on('select_node.jstree', function(e, data) {
          const selectedNode = data.node.text;
          organizationSelected = data.node.id;
        }).on('error.jstree', function(e, error) {
          console.error('jstree error:', error);
        });
      }

      function updateDivisions(companyId) {
        const filteredDivisions = responseDataAllTables.divisions.filter(d => d.company_id == companyId);
        const $divisionSelect = $('select[name="division_id"]');
        $divisionSelect.empty();
        filteredDivisions.forEach(d => {
          $divisionSelect.append(`<option value="${d.id}">${d.name}</option>`);
        });
      }

      function updateItem(tab, data) {
        const formData = new FormData();
        Object.keys(data).forEach(key => {
          if (key === 'fileInputGeneral' || key === 'file_id') {
            const fileInput = $(`input[name="${key}"]`)[0];
            if (fileInput && fileInput.files.length > 0) {
              formData.append(key, fileInput.files[0]);
            }
          } else {
            formData.append(key, data[key]);
          }
        });

        formData.append('_token', '{{ csrf_token() }}');
        formData.append('employ_id', userEmployee.id);

        $.ajax({
          url: `{{ route('employee.request.create', '') }}`,
          type: 'POST',
          data: formData,
          contentType: false,
          processData: false,
          success: function(response) {
            showAlert('success', response.message || 'Item updated successfully!');
            $('#editModal').modal('hide');
            $('#editModal').remove();
            userEmployee = null;
            responseDataAllTables = null;
            loadTabContent(tab);
          },
          error: function(jqXHR) {
            let msg = 'Failed to update item.';
            try {
              const res = JSON.parse(jqXHR.responseText);
              if (res.message) {
                msg = res.message;
              } else if (res.errors) {
                const firstErr = Object.values(res.errors)[0];
                if (firstErr) msg = Array.isArray(firstErr) ? firstErr[0] : firstErr;
              }
            } catch (e) {}
            showAlert('danger', msg);
          }
        });
      }

      //   function addItem(tab, data) {
      //     $.ajax({
      //       url: `{{ route('employee.create') }}`,
      //       type: 'POST',
      //       data: data,
      //       success: function(response) {
      //         showAlert('success', 'Item added successfully!');
      //         loadTabContent(tab);
      //       },
      //       error: function() {
      //         showAlert('danger', 'Failed to add item.');
      //       }
      //     });
      //   }

      //   function deleteItem(tab, id) {
      //     if (confirm('Are you sure you want to delete this item?')) {
      //       $.ajax({
      //         url: `{{ route('employee.delete', '') }}/${id}`,
      //         type: 'DELETE',
      //         success: function(response) {
      //           alert('Item deleted successfully!');
      //           loadTabContent(tab);
      //         },
      //         error: function() {
      //           alert('Failed to delete item.');
      //         }
      //       });
      //     }
      //   }

      loadTabContent('general');
    });
  </script>
@endsection
