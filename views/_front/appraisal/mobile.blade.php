@extends('templates.mobile')

@section('head')
  <style>
    html, body {
      background-color: #ffffff !important;
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    .apr-page-wrapper {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      padding-bottom: 40px;
    }

    .apr-header-banner {
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

    .content-body {
      padding: 0 16px;
      margin-top: -24px;
      z-index: 10;
      position: relative;
    }

    @media (min-width: 769px) {
      .apr-header-banner {
        display: none !important;
      }
      .apr-page-wrapper {
        padding: 20px 24px;
        max-width: 1200px;
        margin: 0 auto;
      }
      .content-body {
        margin-top: 0 !important;
        padding: 0 !important;
      }
      .emp-appraisal-card {
        padding: 18px 22px !important;
        border-radius: 18px !important;
      }
      .emp-appraisal-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0, 115, 230, 0.08) !important;
        border-color: #dbeafe !important;
      }
      .search-filter-card {
        padding: 16px 20px !important;
        border-radius: 18px !important;
      }
    }

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

    /* Custom Select2 Styling to match Search Input */
    .select2-container--default .select2-selection--single {
      height: 44px !important;
      border-radius: 14px !important;
      border: 1px solid #e2e8f0 !important;
      background-color: #f8fafc !important;
      display: flex !important;
      align-items: center !important;
      padding-left: 14px !important;
      padding-right: 32px !important;
      transition: all 0.2s ease !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
      color: #0f172a !important;
      font-size: 13.5px !important;
      font-weight: 700 !important;
      line-height: 42px !important;
      padding-left: 0 !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
      height: 42px !important;
      right: 12px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow b {
      border-color: #64748b transparent transparent transparent !important;
      border-width: 5px 5px 0 5px !important;
    }

    .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
      border-color: transparent transparent #0073e6 transparent !important;
      border-width: 0 5px 5px 5px !important;
    }

    .select2-container--default.select2-container--open .select2-selection--single,
    .select2-container--default.select2-container--focus .select2-selection--single {
      border-color: #0073e6 !important;
      background-color: #ffffff !important;
      box-shadow: 0 0 0 3px rgba(0, 115, 230, 0.15) !important;
      outline: none !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__clear {
      margin-right: 10px !important;
      color: #94a3b8 !important;
      font-weight: bold !important;
    }

    .select2-dropdown {
      border-radius: 14px !important;
      border: 1px solid #e2e8f0 !important;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08) !important;
      overflow: hidden !important;
      z-index: 99999 !important;
    }

    .select2-results__option {
      font-size: 13px !important;
      font-weight: 600 !important;
      padding: 10px 14px !important;
    }

    .select2-container--default .select2-results__option--highlighted[aria-selected] {
      background-color: #0073e6 !important;
      color: #ffffff !important;
    }

    .emp-appraisal-card {
      background: #ffffff;
      border-radius: 20px;
      padding: 14px 16px;
      border: 1px solid #f1f5f9;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
      display: flex;
      align-items: center;
      gap: 14px;
      margin-bottom: 12px;
      transition: all 0.2s ease;
      cursor: pointer;
    }

    .emp-appraisal-card:active {
      transform: scale(0.98);
      background: #f8fafc;
    }

    .emp-avatar-box {
      width: 56px;
      height: 56px;
      border-radius: 16px;
      overflow: hidden;
      flex-shrink: 0;
      border: 2px solid #f1f5f9;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
      background: #f1f5f9;
    }

    .emp-avatar-box img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .emp-info-content {
      flex: 1;
      min-width: 0;
    }

    .emp-name {
      font-size: 15px;
      font-weight: 800;
      color: #0f172a;
      margin-bottom: 2px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .emp-org {
      font-size: 12px;
      color: #64748b;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 4px;
    }

    .emp-score-box {
      display: flex;
      flex-direction: column;
      align-items: flex-end;
      gap: 5px;
      flex-shrink: 0;
    }

    .score-badge {
      background: #eff6ff;
      color: #0073e6;
      font-weight: 900;
      font-size: 15px;
      padding: 4px 12px;
      border-radius: 50px;
      line-height: 1;
    }

    .btn-detail-action {
      font-size: 11.5px;
      font-weight: 800;
      padding: 6px 14px;
      border-radius: 50px;
      background: #0073e6;
      color: #ffffff;
      border: none;
      box-shadow: 0 2px 6px rgba(0, 115, 230, 0.25);
      display: flex;
      align-items: center;
      gap: 3px;
      transition: all 0.2s ease;
    }

    .btn-detail-action:hover {
      background: #005bb5;
      color: #ffffff;
    }

    /* Summary Card Styling */
    .summary-card {
      background: #ffffff;
      border-radius: 20px;
      padding: 24px;
      border: 1px solid #f1f5f9;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
      margin-bottom: 20px;
    }

    .summary-header {
      background: linear-gradient(135deg, #0073e6 0%, #00a651 100%);
      border-radius: 16px;
      padding: 20px;
      color: #ffffff;
      margin-bottom: 20px;
    }

    .summary-header h5 {
      color: #ffffff !important;
      font-weight: 800;
      font-size: 18px;
      margin-bottom: 12px;
    }

    .summary-info {
      display: flex;
      flex-wrap: wrap;
      gap: 16px;
      justify-content: center;
      font-size: 13px;
      background: rgba(255, 255, 255, 0.15);
      padding: 10px 16px;
      border-radius: 12px;
      backdrop-filter: blur(8px);
      color: #ffffff;
    }

    .summary-item {
      display: flex;
      gap: 16px;
      align-items: flex-start;
      background: #f8fafc;
      padding: 16px;
      border-radius: 16px;
      border: 1px solid #e2e8f0;
      margin-bottom: 14px;
    }

    .summary-item i {
      font-size: 24px;
      color: #0073e6;
      background: #eff6ff;
      padding: 10px;
      border-radius: 12px;
      flex-shrink: 0;
    }

    .summary-item h6 {
      font-weight: 800;
      color: #0f172a;
      margin-bottom: 6px;
      font-size: 15px;
    }

    .summary-item p {
      margin-bottom: 2px;
      font-size: 13px;
      color: #475569;
    }

    /* Question Container Styling */
    .question-container {
      background: #ffffff !important;
      border-radius: 20px !important;
      padding: 24px !important;
      border: 1px solid #f1f5f9 !important;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04) !important;
      margin-bottom: 16px !important;
    }

    .question-category {
      font-size: 13px;
      font-weight: 800;
      color: #0073e6;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .question-progress {
      font-size: 12px;
      font-weight: 700;
      color: #64748b;
      background: #f1f5f9;
      padding: 3px 12px;
      border-radius: 50px;
      float: right;
    }

    .evaluator-input {
      border-radius: 12px !important;
      border: 1.5px solid #e2e8f0 !important;
      padding: 8px 14px !important;
      font-weight: 800 !important;
      font-size: 14px !important;
      width: 100% !important;
      max-width: 220px !important;
      margin-bottom: 12px !important;
      background: #f8fafc;
    }

    .evaluator-input:focus {
      border-color: #0073e6 !important;
      background: #ffffff !important;
      box-shadow: 0 0 0 3px rgba(0, 115, 230, 0.15) !important;
      outline: none;
    }

    .evaluator-note {
      border-radius: 12px !important;
      border: 1.5px solid #e2e8f0 !important;
      font-size: 13px !important;
      padding: 10px 14px !important;
      background: #f8fafc;
    }

    .evaluator-note:focus {
      border-color: #0073e6 !important;
      background: #ffffff !important;
      box-shadow: 0 0 0 3px rgba(0, 115, 230, 0.15) !important;
      outline: none;
    }
  </style>
@endsection

@section('content')
  <div class="apr-page-wrapper">
    <div class="apr-header-banner">
      <div class="top-action-bar">
        <a href="{{ route('main') }}" class="btn-back-link" id="btnAppraisalBack">
          <i class="bi bi-arrow-left"></i>
        </a>
        <h1 class="header-page-title" id="page-header-title">Employee Assessment</h1>
        <div style="width: 38px;"></div>
      </div>
    </div>

    <div class="content-body">
      <!-- Desktop Back Bar (chevron + Back) -->
      <div id="desktop-back-bar" class="d-none d-md-block mb-3" style="display: none !important; text-align: left !important; width: 100% !important;">
        <button type="button" class="btn btn-link text-decoration-none p-0 text-primary fw-bold" id="desktopBackBtn" style="font-size: 14px !important; margin: 0 !important; float: left !important; display: inline-flex !important; align-items: center !important;">
          <i class="bi bi-chevron-left me-1"></i> Back
        </button>
        <div style="clear: both;"></div>
      </div>

      <div class="search-filter-card mb-3" id="filter-container">
        <div class="row g-2 align-items-center">
          <div class="col-12 col-md-7">
            <div class="search-box-wrapper">
              <input type="text" class="form-control search-input" name="query" id="search" placeholder="Search employee name...">
              <i class="bi bi-search search-icon"></i>
            </div>
          </div>
          <div class="col-12 col-md-5">
            <select id="periodDropdown" class="form-select period-dropdown"></select>
          </div>
        </div>
      </div>

      <div id="employee-list"></div>
      <div id="appraisal-summary"></div>
      <div class="row g-3" id="category-list">
        <div class="col-12 col-md-4" id="technical-category">
          <div class="card text-center p-3 shadow-sm category-card border-0 rounded-4" data-id="1">
            <div class="icon-container icon-blue">
              <i class="bi bi-tools icon"></i>
            </div>
            <h6 class="fw-bold mt-2">Technical Ability & Work Result</h6>
          </div>
        </div>
        <div class="col-12 col-md-4" id="behavior-category">
          <div class="card text-center p-3 shadow-sm category-card border-0 rounded-4" data-id="2">
            <div class="icon-container icon-green">
              <i class="bi bi-list-task icon"></i>
            </div>
            <h6 class="fw-bold mt-2">Behavior & Work Processes</h6>
          </div>
        </div>
        <div class="col-12 col-md-4" id="leadership-category">
          <div class="card text-center p-3 shadow-sm category-card border-0 rounded-4" data-id="3">
            <div class="icon-container icon-orange">
              <i class="bi bi-person-badge icon"></i>
            </div>
            <h6 class="fw-bold mt-2">Leadership</h6>
          </div>
        </div>
      </div>
      <div id="question-list"></div>

      <div class="text-center mt-3">
        <button class="btn btn-primary rounded-pill px-4" id="load-more" style="display: none;">Load More</button>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      const user = @json($user);
      const userOrgId = user.employee?.org_id || user.organization_id;

      if (!@json(isMobile())) {
        const target = document.querySelector('#title-back-container p');
        if (target) {
          const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
              const text = $(mutation.target).text().trim();
              if (text === 'Appraisal') {
                $('#back-button-wrapper').addClass('d-none');
              } else {
                $('#back-button-wrapper').removeClass('d-none');
              }
            });
          });
          observer.observe(target, {
            characterData: true,
            childList: true,
            subtree: true
          });
        }
      }

      let query = '';
      let loadLimit = 5;
      let currentIndex = 0;
      let allEmployees = [];
      let allPeriods = [];
      let allPeriodsEmployeeLogin = [];
      let allSummaryAppraisal = [];
      let allQuestions = [];
      let allCategories = [];
      let allDefaultQuestionAnswers = [];
      let allAnswers = [];
      let filteredEmployees = [];
      let questionData = null;
      let selectedEmployee = null;
      let selectedPeriod = null;
      let selectedCategory = null;
      let selectedSummary = null;

      function formatDate(dateString) {
        if (!dateString) return '-';

        const date = new Date(dateString);
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();

        return `${day}/${month}/${year}`;
      }

      function updateLoadMoreButton() {
        $('#load-more').toggle(currentIndex + loadLimit < filteredEmployees.length);
      }

      function updateNavigationButtons() {
        $('#prev-btn').prop('disabled', currentIndex === 0);
        if (questionData?.id === user?.employ_id) {
          if (currentIndex === allQuestions.length - 1) {
            $('#next-btn').hide();
          } else {
            $('#next-btn').show();
          }
        } else {
          $('#next-btn').show();
          $('#next-btn').text(currentIndex === allQuestions.length - 1 ? 'Submit' : 'Next');
        }
      }

      function filterData() {
        filteredEmployees = allEmployees.filter(employee =>
          query === '' || employee.fullname.toLowerCase().includes(query.toLowerCase())
        );
        currentIndex = 0;
        renderEmployees(filteredEmployees.slice(0, loadLimit));

        updateLoadMoreButton();
      }

      function fetchEmployees() {
        showLoading();
        $.ajax({
          url: '{{ route('appraisal.employee.data.employee') }}',
          method: 'GET',
          data: {
            trash: 1
          },
          success: function(response) {
            allEmployees = response.data;
            filterData();
          },
          error: function() {
            console.error('Failed to fetch employees.');
          },
          complete: function() {
            hideLoading();
          }
        });
      }

      function renderEmployees(employees) {
        const employeeList = $('#employee-list');
        employeeList.empty();

        if (employees.length === 0) {
          employeeList.append(`
            <div class="card-body text-center">
                <img src="{{ asset('/images/nodata.png') }}" alt="No Employees Found" class="img-fluid" style="max-width: 250px;"/>
                <h3 class="text-black mt-3">No Employees Found</h3>
                </div>
                `);
          return;
        }

        employees.forEach(employee => {
          const canAssess = isUserEvaluatorFor(employee) || (employee.id == user.employ_id);
          const cursorClass = canAssess ? 'cursor-pointer' : '';
          const cursorStyle = canAssess ? 'cursor: pointer;' : 'cursor: default;';

          const appraisalEmployee = employee?.appraisal_employees?.find(appraisal => appraisal.period
            .period_id === selectedPeriod?.period_id);
          const imageUrl = employee.photo_id ?
            `{{ route('file', ['id' => '__ID__']) }}`.replace('__ID__', employee.photo_id) :
            `{{ asset('/images/image-no-user.png') }}`;

          const scoreValue = appraisalEmployee ? parseFloat(appraisalEmployee.total_point || 0).toFixed(2) : '-';
          const scoreBadge = appraisalEmployee ? `
            <div class="emp-score-box">
              <span class="score-badge">${scoreValue}</span>
              <button id="detail-employee-btn" class="btn-detail-action">
                Detail <i class="bi bi-chevron-right ms-1"></i>
              </button>
            </div>
          ` : `
            <div class="emp-score-box">
              <button class="btn-detail-action" style="background: #e2e8f0; color: #475569; box-shadow: none;">
                Start <i class="bi bi-chevron-right ms-1"></i>
              </button>
            </div>
          `;

          const card = `
            <div class="emp-appraisal-card ${cursorClass}" style="${cursorStyle}"
                data-id="${employee.id}" data-score="${appraisalEmployee ? '1' : ''}">
              <div class="emp-avatar-box">
                <img src="${imageUrl}" alt="${employee.fullname}" onerror="this.onerror=null;this.src='{{ asset('/images/image-no-user.png') }}';">
              </div>
              <div class="emp-info-content">
                <div class="emp-name">${employee.fullname}</div>
                <div class="emp-org">
                  <i class="bi bi-building me-1"></i>${employee.organization?.name || 'Employee'}
                </div>
              </div>
              ${scoreBadge}
            </div>
          `;
          employeeList.append(card);
        });

        updateLoadMoreButton();
      }

      function fetchPeriods() {
        showLoading();
        $.ajax({
          url: '{{ route('appraisal.period.organization.data') }}',
          method: 'GET',
          data: {
            trash: 1
          },
          success: function(response) {
            allPeriods = response.data;
            allPeriodsEmployeeLogin = response.data.filter(p => p.organization_id === userOrgId);
            renderPeriodDropdown(allPeriodsEmployeeLogin);
          },
          error: function() {
            console.error('Failed to fetch periods.');
          },
          complete: function() {
            hideLoading();
          }
        });
      }

      function renderPeriodDropdown(periods) {
        const dropdown = document.getElementById('periodDropdown');
        dropdown.innerHTML = '';

        if (periods.length === 0) {
          dropdown.innerHTML = '<option value="">No available periods</option>';
          return;
        }

        const uniquePeriods = [];
        const seenPeriodIds = new Set();
        periods.forEach(p => {
          if (p.appraisal_period && !seenPeriodIds.has(p.appraisal_period.id)) {
            seenPeriodIds.add(p.appraisal_period.id);
            uniquePeriods.push(p);
          }
        });

        uniquePeriods.sort((a, b) => {
          if (a.appraisal_period.period === b.appraisal_period.period) {
            return a.appraisal_period.smester - b.appraisal_period.smester;
          }
          return a.appraisal_period.period - b.appraisal_period.period;
        });

        const currentYear = new Date().getFullYear();
        let defaultPeriod = uniquePeriods.find(p => p.appraisal_period.period == currentYear) || uniquePeriods[0];
        selectedPeriod = defaultPeriod;

        uniquePeriods.forEach(p => {
          const option = document.createElement('option');
          option.value = p.appraisal_period.id;
          option.textContent = `${p.appraisal_period.period} ( SMT ${p.appraisal_period.smester} )`;
          if (p.appraisal_period.id === defaultPeriod.appraisal_period.id) {
            option.selected = true;
          }
          dropdown.appendChild(option);
        });

        $(dropdown).select2({
          width: '100%',
          placeholder: 'Select a period',
          allowClear: true,
          minimumResultsForSearch: 0
        });
      }

      function fetchSummary() {
        showLoading();
        $.ajax({
          url: `{{ route('appraisal.employee.summary.data') }}`,
          data: {
            employee: selectedEmployee?.id,
          },
          method: 'GET',
          success: function(response) {
            allSummaryAppraisal = response.data;
            const summaryData = allSummaryAppraisal.find(a =>
              a.period === selectedPeriod?.appraisal_period?.period &&
              a.smester === selectedPeriod?.appraisal_period?.smester
            );

            if (summaryData) {
              selectedSummary = summaryData;
              renderSummary(summaryData);
            } else {
              const summaryContainer = $("#appraisal-summary");
              summaryContainer.empty();

              summaryContainer.html(
                `<div class="d-flex flex-column justify-content-center align-items-center gap-2 border p-4 rounded">
                        <img src="{{ asset('/images/nodata.png') }}" alt="No Bulletin Found" class="img-fluid" style="max-width: 300px; max-height: 300px;" />
                        <h3 class="text-center font-bold text-black mb-0">No Data Found</h3>
                    </div>`
              );
            }
          },
          error: function() {
            console.error('Failed to fetch questions.');
          },
          complete: function() {
            hideLoading();
          }
        });
      }

      function renderSummary(data) {
        const summaryContainer = $("#appraisal-summary");
        summaryContainer.empty();

        const filteredPeriod = allPeriods.find(p =>
          p.appraisal_period.period == data.period &&
          p.appraisal_period.smester == data.smester &&
          p.organization_id == selectedEmployee.org_id
        );

        const isSelf = selectedEmployee && selectedEmployee.id == user.employ_id;
        const isEvaluator = selectedEmployee ? isUserEvaluatorFor(selectedEmployee) : false;
        const isClosed = filteredPeriod?.appraisal_period?.is_closed == 1 || filteredPeriod?.appraisal_period
          ?.is_closed === true;
        const isEditable = !isSelf && !isClosed && isEvaluator;

        const componentEdit = isEditable ? `
        <button class="btn btn-warning" onclick="editAppraisal('${data.appraisal_employ_id}')">
        <i class="bi bi-pencil me-2"></i>Edit
        </button>` : '';

        let additionalInfoHTML = `<div class="summary-info">`;

        if (data.smester) {
          additionalInfoHTML += `<div><strong>Semester:</strong> ${data.smester}</div>`;
        }

        if (data.final_score) {
          additionalInfoHTML += `<div><strong>Final Score:</strong> ${data.final_score}</div>`;
        }

        if (data.final_grade) {
          additionalInfoHTML += `<div><strong>Final Grade:</strong> ${data.final_grade}</div>`;
        }

        additionalInfoHTML += `</div>`;

        summaryContainer.html(`
            <div class="summary-card">
            <div class="summary-header">
                <h5 class="mb-0 text-center">Period ${data.period}</h5>
                ${additionalInfoHTML}
            </div>

            ${(data.tech_weight > 0 || data.tech_eval1_point !== null || data.tech_eval2_point !== null) ? `
                            <div class="summary-item">
                                <i class="bi bi-tools"></i>
                                <div>
                                <h6>Technical Ability & Work Result</h6>
                                <p>Evaluator 1: ${data.tech_eval1_point ?? '-'} | Grade: ${data.tech_eval1_grade ?? 'N/A'}</p>
                                <p>Evaluator 2: ${data.tech_eval2_point ?? '-'} | Grade: ${data.tech_eval2_grade ?? 'N/A'}</p>
                                <p>Weight: ${data.tech_weight ? data.tech_weight + '%' : '-'}</p>
                                </div>
                            </div>` : ''}

            ${(data.behavior_weight > 0 || data.behavior_eval1_point !== null || data.behavior_eval2_point !== null) ? `
                            <div class="summary-item">
                                <i class="bi bi-list-task"></i>
                                <div>
                                <h6>Behavior & Work Processes</h6>
                                <p>Evaluator 1: ${data.behavior_eval1_point ?? '-'} | Grade: ${data.behavior_eval1_grade ?? 'N/A'}</p>
                                <p>Evaluator 2: ${data.behavior_eval2_point ?? '-'} | Grade: ${data.behavior_eval2_grade ?? 'N/A'}</p>
                                <p>Weight: ${data.behavior_weight ? data.behavior_weight + '%' : '-'}</p>
                                </div>
                            </div>` : ''}

            ${(data.leadership_weight > 0 || data.leadership_eval1_point !== null || data.leadership_eval2_point !== null) ? `
                            <div class="summary-item">
                                <i class="bi bi-person-badge"></i>
                                <div>
                                <h6>Leadership</h6>
                                <p>Evaluator 1: ${data.leadership_eval1_point ?? '-'} | Grade: ${data.leadership_eval1_grade ?? 'N/A'}</p>
                                <p>Evaluator 2: ${data.leadership_eval2_point ?? '-'} | Grade: ${data.leadership_eval2_grade ?? 'N/A'}</p>
                                <p>Weight: ${data.leadership_weight ? data.leadership_weight + '%' : '-'}</p>
                                </div>
                            </div>` : ''}

            <div class="d-flex gap-2 mt-4">
                <button id="export-pdf" class="btn btn-primary">
                <i class="bi bi-file-earmark-pdf me-1"></i> Export PDF
                </button>
                ${componentEdit}
            </div>
            </div>
        `);
      }


      function fetchQuestions(isDetail) {
        showLoading();
        $.ajax({
          url: `{{ route('appraisal.employee.get') }}/${selectedEmployee.id}`,
          method: 'GET',
          data: {
            period_year: selectedPeriod?.appraisal_period?.period,
            period_smt: selectedPeriod?.appraisal_period?.smester
          },
          success: function(response) {
            questionData = response;
            allQuestions = response.appraisal_template?.appraisal_questions || [];
            allQuestions.sort((a, b) => {
              if (a.category_id !== b.category_id) {
                return a.category_id - b.category_id;
              }
              let groupCompare = a.group_kpi.localeCompare(b.group_kpi);
              if (groupCompare !== 0) {
                return groupCompare;
              }
              return new Date(a.created_at) - new Date(b.created_at);
            });
            allCategories = [
              ...new Map(allQuestions.map(q => [q.category.id, q.category])).values()
            ];
            allDefaultQuestionAnswers = questionData.appraisal_employee?.appraisal_employee_questions || [];
            currentIndex = allQuestions.findIndex(q => q.category_id === selectedCategory);

            if (allAnswers.length === 0 && allDefaultQuestionAnswers.length > 0) {
              allQuestions.forEach((q, idx) => {
                const def = allDefaultQuestionAnswers.find(item => item.question_id === q.id) ||
                  allDefaultQuestionAnswers[idx];
                if (def && (def.evaluator1_point !== null || def.evaluator2_point !== null)) {
                  allAnswers[idx] = {
                    questionId: q.id,
                    categoryId: q.category_id,
                    evaluator1: def.evaluator1_point,
                    evaluator1_point: def.evaluator1_point,
                    evalutor1Weight: q.weight,
                    evaluator1Note: def.evaluator1_note || '',
                    evaluator1_note: def.evaluator1_note || '',
                    evaluator2: def.evaluator2_point,
                    evaluator2_point: def.evaluator2_point,
                    evalutor2Weight: q.weight,
                    evaluator2Note: def.evaluator2_note || '',
                    evaluator2_note: def.evaluator2_note || '',
                  };
                }
              });
            }

            if (isDetail) {
              renderQuestionList();
            } else {
              renderQuestion(currentIndex, allDefaultQuestionAnswers);
            }
          },
          error: function() {
            console.error('Failed to fetch questions.');
          },
          complete: function() {
            hideLoading();
          }
        });
      }

      function renderQuestion(index, defaultAnswers = []) {
        const questionContainer = $('#question-list');
        questionContainer.empty();

        const question = allQuestions[index];
        if (!question) return;

        let currentCategory = allCategories.find(cat => cat.id === question.category_id);
        let isAdmin = false;
        if (user.role) {
          const roleName = user.role.name.toLowerCase();
          isAdmin = (roleName === 'developer' || roleName === 'superadmin' || roleName === 'hrga');
        }

        let evaluator1Enabled = questionData.organization?.authorized1 == userOrgId || isAdmin;
        let evaluator2Enabled = questionData.organization?.authorized2 == userOrgId || isAdmin;
        let singleEvaluatorMode = questionData.organization?.authorized1 == questionData.organization?.authorized2;

        const currentQuestionAnswer = allAnswers[index] || {};
        const defaultQuestionAnswer = defaultAnswers.find(item => item.question_id === question.id) || defaultAnswers[
          index] || {};

        const savedEvaluator1 = currentQuestionAnswer.evaluator1 !== undefined && currentQuestionAnswer.evaluator1 !==
          null ?
          currentQuestionAnswer.evaluator1 :
          (currentQuestionAnswer.evaluator1_point !== undefined && currentQuestionAnswer.evaluator1_point !== null ?
            currentQuestionAnswer.evaluator1_point :
            (defaultQuestionAnswer.evaluator1_point ?? ''));

        const savedEvaluator2 = currentQuestionAnswer.evaluator2 !== undefined && currentQuestionAnswer.evaluator2 !==
          null ?
          currentQuestionAnswer.evaluator2 :
          (currentQuestionAnswer.evaluator2_point !== undefined && currentQuestionAnswer.evaluator2_point !== null ?
            currentQuestionAnswer.evaluator2_point :
            (defaultQuestionAnswer.evaluator2_point ?? ''));

        const savedEvaluator1Note = currentQuestionAnswer.evaluator1Note !== undefined && currentQuestionAnswer
          .evaluator1Note !== null ?
          currentQuestionAnswer.evaluator1Note :
          (currentQuestionAnswer.evaluator1_note !== undefined && currentQuestionAnswer.evaluator1_note !== null ?
            currentQuestionAnswer.evaluator1_note :
            (defaultQuestionAnswer.evaluator1_note ?? ''));

        const savedEvaluator2Note = currentQuestionAnswer.evaluator2Note !== undefined && currentQuestionAnswer
          .evaluator2Note !== null ?
          currentQuestionAnswer.evaluator2Note :
          (currentQuestionAnswer.evaluator2_note ?? '');

        const questionHtml = question.question ?
          `<p class="d-flex flex-column"><strong>Target:</strong>${question.question}</p>` : '';
        const groupKpiHtml = question.group_kpi ?
          `<p class="d-flex flex-column"><strong>Group KPI:</strong> ${question.group_kpi}</p>` : '';
        const formulaDescriptionHtml = question.formula_description ?
          `<p class="d-flex flex-column"><strong>Formula:</strong> ${question.formula_description.replace(/\r\n/g, '<br>')}</p>` :
          '';
        const weightHtml = question.weight ?
          `<p class="d-flex flex-column"><strong>Weight:</strong> ${question.weight}%</p>` : '';

        let evaluatorHtml = '';

        if (singleEvaluatorMode) {
          const evaluatorBothHtml = `
                <label>Evaluator Score:</label>
                <input type="number" class="evaluator-input" data-evaluator="both" data-id="${question.id}"
                    min="1" max="10" value="${savedEvaluator1 || savedEvaluator2}">
                <label>Note:</label>
                <textarea class="evaluator-note form-control" data-evaluator="both" data-id="${question.id}"
                        rows="2" placeholder="Add note...">${savedEvaluator1Note || savedEvaluator2Note}</textarea>
            `;
          evaluatorHtml = evaluatorBothHtml;
        } else {
          const evaluator1Html = evaluator1Enabled ? `
                <label>Evaluator Score:</label>
                <input type="number" class="evaluator-input" data-evaluator="1" data-id="${question.id}"
                    min="1" max="10" value="${savedEvaluator1}">
                <label>Note:</label>
                <textarea class="evaluator-note form-control" data-evaluator="1" data-id="${question.id}"
                        rows="2" placeholder="Add note...">${savedEvaluator1Note}</textarea>
            ` : '';

          const evaluator2Html = evaluator2Enabled ? `
                <label>Evaluator Score:</label>
                <input type="number" class="evaluator-input" data-evaluator="2" data-id="${question.id}"
                    min="1" max="10" value="${savedEvaluator2}">
                <label>Note:</label>
                <textarea class="evaluator-note form-control" data-evaluator="2" data-id="${question.id}"
                        rows="2" placeholder="Add note...">${savedEvaluator2Note}</textarea>
            ` : '';

          evaluatorHtml = evaluator1Html + evaluator2Html;
        }

        const card = `
            <div class="question-container p-4 shadow-sm rounded fade-in border">
                <p class="question-category">${currentCategory?.name}</p>
                <p class="question-progress">${index + 1} / ${allQuestions.length}</p>
                ${questionHtml}
                ${groupKpiHtml}
                ${formulaDescriptionHtml}
                ${weightHtml}
                ${evaluatorHtml}
                <div class="mt-4 d-flex gap-1">
                    <button class="btn btn-secondary" id="prev-btn" ${index === 0 ? 'disabled' : ''}>Previous</button>
                    <button class="btn btn-primary" id="next-btn">${index === allQuestions.length - 1 ? 'Submit' : 'Next'}</button>
                </div>
            </div>
        `;

        questionContainer.append(card);
        updateNavigationButtons();
      }

      function renderQuestionList() {
        const questionContainer = $('#question-list');
        questionContainer.empty();

        if (!questionData?.appraisal_employee?.appraisal_employee_questions?.length) {
          questionContainer.append(`<div class="d-flex flex-column justify-content-center align-items-center gap-2 border p-4 rounded">
                        <img src="{{ asset('/images/nodata.png') }}" alt="No Bulletin Found" class="img-fluid" style="max-width: 300px; max-height: 300px;" />
                        <h3 class="text-center font-bold text-black mb-0">No Data Found</h3>
                    </div>`);
          return;
        }

        const sortedQuestions = questionData.appraisal_employee.appraisal_employee_questions.sort((a, b) => a.question
          .category_id - b.question.category_id);

        sortedQuestions.forEach(questionItem => {
          const question = questionItem.question;
          const category = allCategories.find(cat => cat.id === question.category_id);

          const categoryName = category?.name || 'Unknown Category';
          const groupKPI = question?.group_kpi || 'N/A';
          const evaluator1Score = questionItem?.evaluator1_point || 'N/A';
          const evaluator2Score = questionItem?.evaluator2_point || 'N/A';
          const evaluator1Note = questionItem?.evaluator1_note || 'N/A';
          const evaluator2Note = questionItem?.evaluator2_note || 'N/A';
          const questionHtml = question?.question ? `<p><strong>Question:</strong> ${question.question}</p>` : '';
          const weightHtml = question?.weight ? `<p><strong>Weight:</strong> ${question.weight}</p>` : '';

          const questionCard = `
            <div class="question-container p-4 shadow-sm rounded fade-in border mb-3">
                <p class="question-category mb-3"><strong>${categoryName}</strong></p>
                <p class="question-group-kpi"><strong>Group KPI:</strong> ${groupKPI}</p>
                ${questionHtml}
                ${weightHtml}
                <p><strong>Evaluator 1 Score:</strong> ${evaluator1Score}</p>
                <p><strong>Evaluator 2 Score:</strong> ${evaluator2Score}</p>
                <p><strong>Evaluator 1 Note:</strong> ${evaluator1Note}</p>
                <p><strong>Evaluator 2 Note:</strong> ${evaluator2Note}</p>
            </div>`;

          questionContainer.append(questionCard);
        });
      }

      function saveResponse() {
        if (!allQuestions || !allQuestions[currentIndex]) return;

        const questionId = allQuestions[currentIndex].id;
        const categoryId = allQuestions[currentIndex].category_id;
        const weight = allQuestions[currentIndex].weight;

        let singleEvaluatorMode = questionData.organization?.authorized1 == questionData.organization?.authorized2;
        if (singleEvaluatorMode) {
          const val = $(`input[data-id='${questionId}'][data-evaluator='both']`).val() || null;
          const note = $(`textarea[data-id='${questionId}'][data-evaluator='both']`).val() || '';

          allAnswers[currentIndex] = {
            questionId,
            categoryId,
            evaluator1: val,
            evaluator1_point: val,
            evalutor1Weight: weight,
            evaluator1Note: note,
            evaluator1_note: note,
            evaluator2: val,
            evaluator2_point: val,
            evalutor2Weight: weight,
            evaluator2Note: note,
            evaluator2_note: note,
          };
        } else {
          const evaluator1Input = $(`input[data-id='${questionId}'][data-evaluator='1']`);
          const evaluator2Input = $(`input[data-id='${questionId}'][data-evaluator='2']`);
          const evaluator1NoteInput = $(`textarea[data-id='${questionId}'][data-evaluator='1']`);
          const evaluator2NoteInput = $(`textarea[data-id='${questionId}'][data-evaluator='2']`);

          const existing = allAnswers[currentIndex] || {};

          const evaluator1 = evaluator1Input.length ? (evaluator1Input.val() || null) : (existing.evaluator1 ??
            existing.evaluator1_point ?? null);
          const evaluator2 = evaluator2Input.length ? (evaluator2Input.val() || null) : (existing.evaluator2 ??
            existing.evaluator2_point ?? null);
          const evaluator1Note = evaluator1NoteInput.length ? (evaluator1NoteInput.val() || '') : (existing
            .evaluator1Note ?? existing.evaluator1_note ?? '');
          const evaluator2Note = evaluator2NoteInput.length ? (evaluator2NoteInput.val() || '') : (existing
            .evaluator2Note ?? existing.evaluator2_note ?? '');

          allAnswers[currentIndex] = {
            questionId,
            categoryId,
            evaluator1: evaluator1,
            evaluator1_point: evaluator1,
            evalutor1Weight: weight,
            evaluator1Note: evaluator1Note,
            evaluator1_note: evaluator1Note,
            evaluator2: evaluator2,
            evaluator2_point: evaluator2,
            evalutor2Weight: weight,
            evaluator2Note: evaluator2Note,
            evaluator2_note: evaluator2Note,
          };
        }
      }

      function validateAnswers() {
        for (let i = 0; i < allQuestions.length; i++) {
          if (!allAnswers[i] || (allAnswers[i].evaluator1_point === '' && allAnswers[i].evaluator1 === '') ||
            (allAnswers[i].evaluator2_point === '' && allAnswers[i].evaluator2 === '')) {
            showAlert('danger', `Please answer all questions before submitting!`);
            return false;
          }
        }
        return true;
      }

      function submitAnswers() {
        if (!validateAnswers()) return showAlert('danger', 'Please answer all questions before submitting!');

        let evaluatorRole = null;
        if (questionData.organization?.authorized1 == userOrgId &&
          questionData.organization?.authorized2 == userOrgId) {
          evaluatorRole = "single_evaluator";
        } else if (questionData.organization?.authorized1 == userOrgId) {
          evaluatorRole = "evaluator1";
        } else if (questionData.organization?.authorized2 == userOrgId) {
          evaluatorRole = "evaluator2";
        }

        if (!evaluatorRole) {
          let isAdmin = false;
          if (user.role) {
            const roleName = user.role.name.toLowerCase();
            isAdmin = (roleName === 'developer' || roleName === 'superadmin' || roleName === 'hrga');
          }
          if (isAdmin) {
            let singleEvaluatorMode = questionData.organization?.authorized1 === questionData.organization
              ?.authorized2;
            if (singleEvaluatorMode) {
              evaluatorRole = "single_evaluator";
            } else {
              const hasVal1 = $('.evaluator-input[data-evaluator="1"]').length > 0;
              const hasVal2 = $('.evaluator-input[data-evaluator="2"]').length > 0;
              if (hasVal1) {
                evaluatorRole = "evaluator1";
              } else if (hasVal2) {
                evaluatorRole = "evaluator2";
              } else {
                evaluatorRole = "evaluator1";
              }
            }
          }
        }

        $.ajax({
          url: '{{ route('appraisal.question.template.front.evaluator') }}',
          method: 'POST',
          data: {
            _token: '{{ csrf_token() }}',
            answers: allAnswers,
            period_id: selectedPeriod.id,
            employee_id: selectedEmployee.id,
            organization_id: selectedEmployee.org_id,
            template_id: questionData.appraisal_template.id,
            evaluator_role: evaluatorRole,
            user_id: user.employ_id,
          },
          success: function(response) {
            $('#question-list').hide();
            $('#appraisal-summary').show();
            $('#filter-container').show();
            $('#search-container').hide();
            $('#period-selected').show().text(
              selectedEmployee.fullname ||
              'No period selected');
            $('#title-back-container p').text('Back');
            fetchSummary();
            showAlert('success', 'Data has been processed successfully.');
          },
          error: function(jqXHR, textStatus, errorThrown) {
            try {
              const response = JSON.parse(jqXHR.responseText);
              const message = response.message || 'An error occurred while processing the data.';
              console.error(message);
              showAlert('danger', message);
            } catch (e) {
              console.error('Failed to parse JSON response:', e);
              console.error('Server response:', jqXHR.responseText);
              showAlert('danger', 'An error occurred while processing the data.');
            }
          }
        });
      }

      $(document).on('click', '#export-pdf', function() {
        if (selectedSummary && selectedEmployee) {
          const route = '{{ route('appraisal.question.template.front.export.pdf', ':employeeId') }}'
            .replace(':employeeId', selectedEmployee.id);

          const queryParams = new URLSearchParams({
            period: selectedSummary.period,
            smester: selectedSummary.smester,
            appraisal: selectedSummary.appraisal_employ_id
          });

          window.open(route + '?' + queryParams.toString(), '_blank');
        } else {
          console.error('No summary data available.');
        }
      });

      function isUserEvaluatorFor(employee) {
        let isAdmin = false;
        if (user.role) {
          const roleName = user.role.name.toLowerCase();
          isAdmin = (roleName === 'developer' || roleName === 'superadmin' || roleName === 'hrga');
        }
        if (isAdmin) return true;

        const empOrg = employee?.organization;
        if (!empOrg) return false;
        return empOrg.authorized1 == userOrgId || empOrg.authorized2 == userOrgId;
      }

      function showCategoriesForTemplate(callback) {
        showLoading();
        $('#no-template-alert').remove();
        $.ajax({
          url: `{{ route('appraisal.employee.get') }}/${selectedEmployee.id}`,
          method: 'GET',
          data: {
            period_year: selectedPeriod?.appraisal_period?.period,
            period_smt: selectedPeriod?.appraisal_period?.smester
          },
          success: function(response) {
            const questions = response.appraisal_template?.appraisal_questions || [];
            const categoryIds = [...new Set(questions.map(q => q.category_id))];

            const categoryMap = {
              1: '#technical-category',
              2: '#behavior-category',
              3: '#leadership-category'
            };

            Object.values(categoryMap).forEach(sel => $(sel).closest('.col-md-4, .col-md-6').hide());

            if (questions.length === 0 || !response.appraisal_template) {
              $('#category-list').before(`
                <div id="no-template-alert" class="alert alert-warning text-center shadow-sm rounded-4 p-4 my-3">
                  <i class="bi bi-exclamation-triangle-fill fs-2 text-warning d-block mb-2"></i>
                  <h5 class="fw-bold">No Appraisal Template Assigned</h5>
                  <p class="mb-1 text-dark">There is no appraisal template assigned to <strong>${selectedEmployee.fullname}</strong> (${selectedEmployee.organization?.name || 'Organization'}) for period <strong>${selectedPeriod?.appraisal_period?.period || '-'} SMT ${selectedPeriod?.appraisal_period?.smester || '-'}</strong>.</p>
                  <small class="text-secondary d-block mt-2">Please assign an appraisal template to this organization and period in Appraisal Period Organization settings first.</small>
                </div>
              `);
            } else {
              const visibleCount = categoryIds.length;
              const colClass = visibleCount <= 2 ? 'col-md-6' : 'col-md-4';

              categoryIds.forEach(catId => {
                const sel = categoryMap[catId];
                if (sel) {
                  $(sel).closest('.col-md-4, .col-md-6')
                    .removeClass('col-md-4 col-md-6')
                    .addClass(colClass)
                    .show();
                }
              });
            }

            if (callback) callback();
          },
          error: function() {
            console.error('Failed to fetch template questions for categories.');
          },
          complete: function() {
            hideLoading();
          }
        });
      }

      window.editAppraisal = function(appraisalEmployId) {
        $('#employee-list').hide();
        $('#appraisal-summary').hide();
        $('#question-list').hide();
        $('#search-container').hide();
        $('#filter-container').hide();
        $('#load-more').hide();

        currentIndex = 0;
        selectedSummary = null;
        allSummaryAppraisal = [];
        allAnswers = [];
        selectedCategory = null;
        $('#title-back-container p').text('Back');

        showCategoriesForTemplate(function() {
          $('#category-list').show();
        });
      };

      function setNavState(state) {
        window.currentNavState = state;
        if (state === 'Back') {
          $('#page-header-title').text('Assessment Details');
          $('#desktop-back-bar').attr('style', 'display: block !important; text-align: left !important; width: 100% !important;');
        } else {
          $('#page-header-title').text('Employee Assessment');
          $('#desktop-back-bar').attr('style', 'display: none !important;');
        }
      }

      $(document).on('click', '#desktopBackBtn', function(e) {
        $('#btnAppraisalBack').trigger('click');
      });

      $(document).on('change', '#periodDropdown', function(event) {
        const dropdown = event.target;
        selectedPeriod = allPeriodsEmployeeLogin.find(p => p.appraisal_period.id == dropdown.value);

        if ((window.currentNavState || 'Appraisal') === 'Appraisal') {
          fetchEmployees();
        } else {
          $('#load-more').hide();
          fetchQuestions(true);
          fetchSummary();
        }

      })

      $(document).on('click', '#employee-list .emp-appraisal-card, #employee-list .card', function(event) {
        const employeeId = $(this).data('id');
        const employeeScore = $(this).data('score');
        selectedEmployee = allEmployees.find(employee => employee.id === employeeId);

        if (!selectedEmployee) {
          console.warn("Employee not found!");
          return;
        }

        const filteredPeriodBySelectEmployee = allPeriods.filter(p => p.organization_id === selectedEmployee
          .org_id);

        if (!selectedPeriod || !selectedPeriod.appraisal_period) {
          console.warn("No selected period available.");
          return;
        }

        const activePeriod = selectedPeriod;
        const smester = activePeriod?.appraisal_period?.smester;
        const period = activePeriod?.appraisal_period?.period;
        let targetPeriod = filteredPeriodBySelectEmployee.find(p =>
          p.appraisal_period?.period == period && p.appraisal_period?.smester == smester
        );
        selectedPeriod = targetPeriod || activePeriod;

        if (event.target.closest('#detail-employee-btn') || event.target.closest('.btn-detail-action')) {
          $('#employee-list').hide();
          $('#filter-container').show();
          $('#search-container').hide();
          $('#period-selected').show().text(
            selectedEmployee.fullname || 'No period selected'
          );
          $('#load-more').hide();
          $('#question-list').show();
          setNavState('Back');
          fetchQuestions(true);
          return;
        }

        const isSelf = (selectedEmployee && selectedEmployee.id == user.employ_id);
        const isEvaluator = isUserEvaluatorFor(selectedEmployee);

        if (!isEvaluator && !isSelf) {
          return;
        }

        let isAdmin = false;
        if (user.role) {
          const roleName = user.role.name.toLowerCase();
          isAdmin = (roleName === 'developer' || roleName === 'superadmin' || roleName === 'hrga');
        }

        const appraisalEmp = selectedEmployee?.appraisal_employees?.find(appraisal => {
          if (appraisal.period_id === selectedPeriod?.id || appraisal.period_id === selectedPeriod?.period_id) return true;
          if (appraisal.period && appraisal.period.appraisal_period) {
            return appraisal.period.appraisal_period.period == period && appraisal.period.appraisal_period.smester == smester;
          }
          return false;
        });

        const empOrg = selectedEmployee?.organization;
        const isAuth1 = empOrg ? (empOrg.authorized1 == userOrgId) : false;
        const isAuth2 = empOrg ? (empOrg.authorized2 == userOrgId) : false;
        const isSingleEval = isAuth1 && isAuth2;

        let userHasEvaluated = false;

        if (isSelf && !isEvaluator) {
          userHasEvaluated = true;
        } else if (isSingleEval) {
          userHasEvaluated = !!(appraisalEmp && (appraisalEmp.evaluator1_at || appraisalEmp.evaluator1_by || appraisalEmp.evaluator2_at || appraisalEmp.evaluator2_by));
        } else if (isAuth1 && !isAuth2) {
          userHasEvaluated = !!(appraisalEmp && (appraisalEmp.evaluator1_at || appraisalEmp.evaluator1_by));
        } else if (isAuth2 && !isAuth1) {
          userHasEvaluated = !!(appraisalEmp && (appraisalEmp.evaluator2_at || appraisalEmp.evaluator2_by));
        } else if (isAdmin) {
          if (isAuth1 && !isAuth2) {
            userHasEvaluated = !!(appraisalEmp && (appraisalEmp.evaluator1_at || appraisalEmp.evaluator1_by));
          } else if (isAuth2 && !isAuth1) {
            userHasEvaluated = !!(appraisalEmp && (appraisalEmp.evaluator2_at || appraisalEmp.evaluator2_by));
          } else {
            userHasEvaluated = !!(appraisalEmp && (appraisalEmp.evaluator1_at || appraisalEmp.evaluator1_by || appraisalEmp.evaluator2_at || appraisalEmp.evaluator2_by));
          }
        } else {
          userHasEvaluated = !!(appraisalEmp && (appraisalEmp.evaluator1_at || appraisalEmp.evaluator1_by || appraisalEmp.evaluator2_at || appraisalEmp.evaluator2_by));
        }

        if (isSelf && !isEvaluator) {
          $('#employee-list').hide();
          $('#category-list').hide();
          $('#question-list').hide();
          $('#search-container').hide();
          $('#appraisal-summary').show();
          $('#filter-container').show();
          $('#period-selected').show().text(
            selectedEmployee.fullname || 'No period selected'
          );
          setNavState('Back');
          $('#load-more').hide();
          fetchSummary();
        } else if (userHasEvaluated) {
          $('#employee-list').hide();
          $('#category-list').hide();
          $('#question-list').hide();
          $('#search-container').hide();
          $('#appraisal-summary').show();
          $('#filter-container').show();
          $('#period-selected').show().text(
            selectedEmployee.fullname || 'No period selected'
          );
          setNavState('Back');
          $('#load-more').hide();
          fetchSummary();
        } else {
          $('#employee-list').hide();
          currentIndex = 0;
          $('#filter-container').hide();
          $('#load-more').hide();
          setNavState('Back');

          showCategoriesForTemplate(function() {
            $('#category-list').show();
          });
        }
      });

      $(document).on('click', '#category-list .card', function() {
        selectedCategory = $(this).data('id');
        if (selectedCategory) {
          $('#category-list').hide();
          currentIndex = 0;
          $('#title-back-container p').text('Back');
          $('#filter-container').hide();
          $('#load-more').hide();
          $('#question-list').show();
          fetchQuestions(false);
        }
      });

      $(document).on("input change", ".evaluator-input, .evaluator-note", function() {
        saveResponse();
      });

      $(document).on("click", "#prev-btn", function() {
        if (currentIndex > 0) {
          saveResponse();
          currentIndex--;
          renderQuestion(currentIndex, allDefaultQuestionAnswers);
        }
      });

      $(document).on("click", "#next-btn", function() {
        saveResponse();
        if (currentIndex < allQuestions.length - 1) {
          currentIndex++;
          renderQuestion(currentIndex, allDefaultQuestionAnswers);
        } else {
          submitAnswers();
        }
      });

      $('#load-more').on('click', function() {
        currentIndex += loadLimit;
        renderEmployees(filteredEmployees.slice(0, currentIndex + loadLimit));
        updateLoadMoreButton();
      });

      $('#search').on('input', debounce(function() {
        query = $(this).val();
        filterData();
      }, 500));

      $(document).on('click', '#btnAppraisalBack, #title-back-container', function(e) {
        e.preventDefault();
        $('#no-template-alert').remove();
        const state = window.currentNavState || 'Appraisal';
        if (state === 'Appraisal') {
          window.location.href = "{{ route('main') }}";
        } else if (selectedEmployee && selectedCategory) {
          $('#question-list').hide();
          $('#appraisal-summary').hide();
          currentIndex = 0;
          selectedCategory = null;
          selectedSummary = null;
          allAnswers = [];

          showCategoriesForTemplate(function() {
            $('#category-list').show();
          });
        } else {
          $('#no-template-alert').remove();
          $('#period-selected').hide();
          $('#category-list').hide();
          $('#appraisal-summary').hide();
          $('#question-list').hide();
          $('#search-container').show();
          $('#employee-list').show();
          $('#filter-container').show();
          currentIndex = 0;
          selectedEmployee = null;
          selectedCategory = null;
          selectedSummary = null;
          setNavState('Appraisal');
          renderEmployees(filteredEmployees.slice(0, loadLimit));
          updateLoadMoreButton();
        }
      });

      Promise.all([fetchPeriods(), fetchEmployees()]).then(() => {
        $('#filter-container').show();
        $('#period-selected').hide();
        $('#category-list').hide();
        $('#appraisal-summary').hide();
      });
    });
  </script>
@endsection
