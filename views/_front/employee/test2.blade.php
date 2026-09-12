<!-- INI YANG ADA TAB NYA YA GUYS YA  -->

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

    .profile-nav-request {
      display: flex;
      justify-content: space-evenly;
      margin-bottom: 16px;
      overflow-x: auto;
      background-color: var(--white);
      padding: 8px;
      border-bottom: 2px solid var(--gray-medium);
    }

    .nav-item,
    .nav-item-request {
      color: var(--dark);
      cursor: pointer;
      background: none;
      border: none;
      text-align: center;
      font-size: 16px;
    }

    .nav-item {
      padding: 12px 24px;
    }

    .nav-item-request {
      padding: 8px 16px;
    }


    .nav-item.active,
    .nav-item-request.active {
      background-color: var(--primary-color);
      color: var(--white);
      border-radius: var(--radius-xl);
    }

    .tab-container {
      margin: 20px 20px 0 20px;
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

    .profile-field:not(:last-child) {
      border-bottom: 1px solid #e0e0e0;
    }

    .profile-field {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px 0;
      width: 100%;
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
      <button class="nav-item active" data-tab="profile">Info</button>
      <button class="nav-item" data-tab="request">Request</button>
    </div>

    <div id="tab-container" class="tab-container">
      <div id="tab-content">
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      const user = @json($user);
      let userEmployee = null;

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

        if (tab === 'profile') {
          loadInfoForm();
        } else if (tab === 'request') {
          loadRequestForm();
        }
      });

      // Info
      function loadInfoForm() {
        $('#tab-content').html(`
            <div id="profileForm" class="profile-form">
                <div class="profile-nav-request">
                    <button class="nav-item-request active" data-tab="general">General</button>
                    <button class="nav-item-request" data-tab="contract">Contract</button>
                    <button class="nav-item-request" data-tab="career">Career</button>
                    <button class="nav-item-request" data-tab="citizen">Citizen</button>
                    <button class="nav-item-request" data-tab="education">Education</button>
                    <button class="nav-item-request" data-tab="family">Family</button>
                    <button class="nav-item-request" data-tab="experience">Experience</button>
                    <button class="nav-item-request" data-tab="training">Training</button>
                </div>
                <div id="tab-content-area">
                </div>
            </div>
        `);

        loadTabContent('general');

        $('.nav-item-request').on('click', function() {
          const tab = $(this).data('tab');
          $('.nav-item-request').removeClass('active');
          $(this).addClass('active');
          loadTabContent(tab);
        });
      }

      function loadTabContent(tab) {
        if (userEmployee === null) {
          showLoading();

          $.ajax({
            url: "{{ route('employee.get') }}" + '/' + user.employ_id,
            type: 'GET',
            success: async function(response) {
              userEmployee = response;
              populateTabContent(tab, response);
            },
            error: function() {
              console.error('Failed to fetch profile data.');
            },
            complete: function() {
              hideLoading();
            }
          });
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

        const generateProfileCard = (items, fields, noItemsMessage) => {
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
              return `<div class="profile-card">${itemContent}</div>`;
            }).join('');
            return `<div class="profile-list">${itemElements}</div>`;
          } else {
            return `<div class="profile-list"><p>${noItemsMessage}</p></div>`;
          }
        };

        switch (tab) {
          case 'general':
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
            content = generateProfileCard(data.contracts, contractFields, 'No contracts available');
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
            content = generateProfileCard(data.careers, careerFields, 'No careers available');
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
            content = generateProfileCard(data.citizens, citizenFields, 'No citizens available');
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
            content = generateProfileCard(data.educations, educationFields, 'No educations available');
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
            content = generateProfileCard(data.families, familyFields, 'No families available');
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
            content = generateProfileCard(data.job_experiences, experienceFields, 'No job experiences available');
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
            content = generateProfileCard(data.trainings, trainingFields, 'No trainings available');
            break;

          default:
            content = '<div>No data available for this tab.</div>';
        }

        $('#tab-content-area').html(content);
      }

      // Request
      function generateInputFormRequest(formType, submitUrl, fields) {
        let formHtml = `
            <form id="${formType}FormRequest" action="${submitUrl}" method="POST" enctype="multipart/form-data">
                <div class="card p-4">
                    <h5 class="card-title">${formType.charAt(0).toUpperCase() + formType.slice(1)} Form</h5>
        `;

        fields.forEach(field => {
          let inputElement = '';
          let disabledAttr = field.disabled ? 'disabled' : '';
          let defaultValue = field.defaultValue ? field.defaultValue : '';

          if (field.type === 'file') {
            inputElement =
              `<input type="file" class="form-control" name="${field.name}" ${field.required ? 'required' : ''} ${disabledAttr}>`;
          } else if (field.type === 'select') {
            const selectOptions = field.options.map(option => `
                <option value="${option.value}" ${defaultValue === option.value ? 'selected' : ''}>
                    ${option.label}
                </option>
            `).join('');

            inputElement = `
                <select class="form-control" name="${field.name}" ${field.required ? 'required' : ''} ${disabledAttr}>
                    ${selectOptions}
                </select>
            `;
          } else if (field.type === 'textarea') {
            inputElement =
              `<textarea class="form-control" name="${field.name}" ${field.required ? 'required' : ''} ${disabledAttr}>${defaultValue}</textarea>`;
          } else {
            inputElement =
              `<input type="${field.type}" class="form-control" name="${field.name}" value="${defaultValue}" ${field.required ? 'required' : ''} ${disabledAttr}>`;
          }

          formHtml += `
                <div class="form-group mb-3">
                    <label class="form-label" for="${field.name}">${field.label}</label>
                    ${inputElement}
                </div>
            `;
        });

        formHtml += `
                    <div class="d-flex gap-2 align-items-center">
                        <button id="backButton" type="button" class="btn btn-danger">Back</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        `;

        $('#tab-content').html(formHtml);

        $('#backButton').on('click', function() {
          loadRequestForm();
        });

        $(`#${formType}FormRequest`).on('submit', function(event) {
          event.preventDefault();
          const formData = new FormData(this);

          $.ajax({
            url: submitUrl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
              console.log('Form submitted successfully:', response);
            },
            error: function(xhr, status, error) {
              console.error('Form submission failed:', error);
            }
          });
        });
      }

      function loadGeneralFormRequest(dataOptions) {
        console.log('userEmployee', userEmployee);
        const fields = [{
            name: 'fullname',
            label: 'Full Name',
            type: 'text',
            required: true,
            defaultValue: userEmployee.fullname,
            disabled: false
          },
          {
            name: 'nik',
            label: 'NIK',
            type: 'text',
            required: true,
            defaultValue: userEmployee.nik,
            disabled: true
          },
          {
            name: 'nickname',
            label: 'Nickname',
            type: 'text',
            required: false,
            defaultValue: userEmployee.nickname,
            disabled: false
          },
          {
            name: 'join_date',
            label: 'Join Date',
            type: 'date',
            required: true,
            defaultValue: userEmployee.join_date,
            disabled: true
          },
          {
            name: 'company_id',
            label: 'Company',
            type: 'select',
            options: mappingOptions(dataOptions.companies),
            defaultValue: userEmployee.company_id,
            required: true,
            disabled: true
          },
          {
            name: 'org_id',
            label: 'Organization',
            type: 'select',
            options: mappingOptions(dataOptions.organizations),
            defaultValue: userEmployee.org_id,
            required: true,
            disabled: true
          },
          {
            name: 'placement_id',
            label: 'Placement',
            type: 'select',
            options: mappingOptions(dataOptions.placements),
            defaultValue: userEmployee.placement_id,
            required: true,
            disabled: true
          },
          {
            name: 'division_id',
            label: 'Division',
            type: 'select',
            options: mappingOptions(dataOptions.divisions),
            defaultValue: userEmployee.division_id,
            required: true,
            disabled: true
          },
          {
            name: 'file',
            label: 'Upload File',
            type: 'file',
            required: false,
            disabled: false
          }
        ];

        generateInputFormRequest('general', '/submit/general', fields);
      }

      function mappingOptions(dataOptions) {
        const options = [];
        dataOptions.forEach(option => {
          options.push({
            value: option.id,
            label: option.name || option.city || option.province
          });
        });
        return options;
      }

      function loadContractFormRequest() {
        const fields = [{
            name: 'contractName',
            label: 'Contract Name',
            type: 'text',
            required: true
          },
          {
            name: 'startDate',
            label: 'Start Date',
            type: 'date',
            required: true
          },
          {
            name: 'endDate',
            label: 'End Date',
            type: 'date',
            required: true
          },
          {
            name: 'file',
            label: 'Upload Contract File',
            type: 'file',
            required: true
          }
        ];
        generateInputFormRequest('contract', '/submit/contract', fields);
      }

      async function loadRequestForm() {
        const responseData = await fetchAllTableData();

        $('#tab-content').html(`
            <div id="settingsCard">
                <div class="card">
                    <div class="card-body">
                        <ul class="d-flex flex-column gap-1 p-0 m-0">
                            <li class="list-group-item d-flex align-items-center p-2">
                                <a href="#" id="general-form-request" class="text-decoration-none text-primary-color d-flex align-items-center justify-content-between w-100">
                                    <div>
                                        <i class="fas fa-home me-2"></i> General
                                    </div>
                                    <p class="mb-0">></p>
                                </a>
                            </li>
                            <li class="list-group-item d-flex align-items-center p-2">
                                <a href="#" id="contract-form-request" class="text-decoration-none text-primary-color d-flex align-items-center justify-content-between w-100">
                                    <div>
                                        <i class="fas fa-file-contract me-2"></i> Contract
                                    </div>
                                    <p class="mb-0">></p>
                                </a>
                            </li>
                            <li class="list-group-item d-flex align-items-center p-2">
                                <a href="#" id="citizen-form-request" class="text-decoration-none text-primary-color d-flex align-items-center justify-content-between w-100">
                                    <div>
                                        <i class="fas fa-user me-2"></i> Citizen
                                    </div>
                                    <p class="mb-0">></p>
                                </a>
                            </li>
                            <li class="list-group-item d-flex align-items-center p-2">
                                <a href="#" id="education-form-request" class="text-decoration-none text-primary-color d-flex align-items-center justify-content-between w-100">
                                    <div>
                                        <i class="fas fa-graduation-cap me-2"></i> Education
                                    </div>
                                    <p class="mb-0">></p>
                                </a>
                            </li>
                            <li class="list-group-item d-flex align-items-center p-2">
                                <a href="#" id="family-form-request" class="text-decoration-none text-primary-color d-flex align-items-center justify-content-between w-100">
                                    <div>
                                        <i class="fas fa-users me-2"></i> Family
                                    </div>
                                    <p class="mb-0">></p>
                                </a>
                            </li>
                            <li class="list-group-item d-flex align-items-center p-2">
                                <a href="#" id="experience-form-request" class="text-decoration-none text-primary-color d-flex align-items-center justify-content-between w-100">
                                    <div>
                                        <i class="fas fa-briefcase me-2"></i> Job Experience
                                    </div>
                                    <p class="mb-0">></p>
                                </a>
                            </li>
                            <li class="list-group-item d-flex align-items-center p-2">
                                <a href="#" id="training-form-request" class="text-decoration-none text-primary-color d-flex align-items-center justify-content-between w-100">
                                    <div>
                                        <i class="fas fa-chalkboard-teacher me-2"></i> Training
                                    </div>
                                    <p class="mb-0">></p>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        `);

        $('#general-form-request').on('click', function(e) {
          e.preventDefault();
          loadGeneralFormRequest(responseData);
        });
      }

      loadInfoForm();
    });
  </script>
@endsection
