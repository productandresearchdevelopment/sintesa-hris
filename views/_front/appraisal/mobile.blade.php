@extends('templates.mobile')

@section('head')
  <style>
    html,
    body {
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
      flex: 1;
      text-align: center;
    }

    .content-body {
      padding: 0 16px;
      margin-top: -24px;
      z-index: 10;
      position: relative;
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
      border-radius: 18px !important;
      border: 1px solid #e2e8f0 !important;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12) !important;
      overflow: hidden !important;
      z-index: 99999 !important;
      background: #ffffff !important;
      padding: 6px !important;
    }

    .select2-results__options {
      max-height: 250px !important;
      overflow-y: auto !important;
    }

    .select2-results__options::-webkit-scrollbar {
      width: 5px;
    }

    .select2-results__options::-webkit-scrollbar-track {
      background: #f8fafc;
      border-radius: 10px;
    }

    .select2-results__options::-webkit-scrollbar-thumb {
      background: #cbd5e1;
      border-radius: 10px;
    }

    .select2-results__option {
      font-size: 13px !important;
      font-weight: 600 !important;
      padding: 10px 14px !important;
      border-radius: 12px !important;
      white-space: normal !important;
      word-break: break-word !important;
      line-height: 1.35 !important;
    }

    .select2-container--default .select2-results__option--highlighted[aria-selected] {
      background-color: #eff6ff !important;
      color: #0073e6 !important;
    }

    /* Assessment Category Cards Redesign */
    .category-header-card {
      background: #ffffff !important;
      border-radius: 20px !important;
      padding: 12px 16px !important;
      border: 1px solid #f1f5f9 !important;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04) !important;
      margin-bottom: 12px !important;
    }

    .emp-avatar-box-sm {
      width: 42px;
      height: 42px;
      border-radius: 12px;
      overflow: hidden;
      flex-shrink: 0;
      border: 2px solid #f1f5f9;
      background: #f1f5f9;
    }

    .emp-avatar-box-sm img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .cat-header-name {
      font-size: 14.5px !important;
      font-weight: 800 !important;
      color: #0f172a !important;
      margin: 0 !important;
      line-height: 1.25 !important;
    }

    .cat-header-org {
      font-size: 11.5px !important;
      font-weight: 600 !important;
      color: #64748b !important;
      display: block !important;
      margin-top: 1px !important;
    }

    .category-card {
      background: #ffffff !important;
      border-radius: 20px !important;
      padding: 16px 18px !important;
      border: 1px solid #f1f5f9 !important;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04) !important;
      display: flex !important;
      flex-direction: row !important;
      align-items: center !important;
      gap: 14px !important;
      cursor: pointer !important;
      transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
      text-align: left !important;
      min-height: 88px !important;
      box-sizing: border-box !important;
    }

    .category-card:active {
      transform: scale(0.97) !important;
      background: #f8fafc !important;
    }

    .cat-icon-box {
      width: 48px;
      height: 48px;
      min-width: 48px;
      border-radius: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 22px;
      flex-shrink: 0;
      transition: all 0.2s ease;
    }

    .cat-icon-box.icon-blue {
      background: #eff6ff;
      color: #0073e6;
    }

    .cat-icon-box.icon-green {
      background: #ecfdf5;
      color: #059669;
    }

    .cat-icon-box.icon-orange {
      background: #fff7ed;
      color: #d97706;
    }

    .cat-info-content {
      flex: 1;
      min-width: 0;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .cat-title {
      font-size: 14px;
      font-weight: 800;
      color: #0f172a;
      margin-bottom: 2px;
      letter-spacing: -0.2px;
      line-height: 1.25;
    }

    .cat-desc {
      font-size: 11.5px;
      font-weight: 600;
      color: #64748b;
      display: block;
      line-height: 1.3;
    }

    .cat-arrow {
      width: 34px;
      height: 34px;
      min-width: 34px;
      border-radius: 10px;
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #94a3b8;
      font-size: 14px;
      transition: all 0.2s ease;
      flex-shrink: 0;
    }

    .category-card:hover .cat-arrow {
      background: #0073e6;
      color: #ffffff;
      border-color: #0073e6;
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

    /* Summary Card Stylig */
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
      max-width: 100% !important;
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

    @media (min-width: 768px) {
      .apr-header-banner .btn-back-link.desktop-hidden {
        visibility: hidden !important;
        pointer-events: none !important;
      }
    }
  </style>
@endsection

@section('content')
  <div class="apr-page-wrapper">
    <div class="apr-header-banner">
      <div class="top-action-bar">
        <a href="{{ route('main') }}" class="btn-back-link desktop-hidden" id="btnAppraisalBack">
          <i class="bi bi-arrow-left"></i>
        </a>
        <h1 class="header-page-title" id="page-header-title">Employee Assessment</h1>
        <div style="width: 38px;"></div>
      </div>
    </div>

    <div class="content-body">
      <!-- Desktop Back Bar (chevron + Back) -->
      <div id="desktop-back-bar" class="d-none d-md-block mb-3"
        style="display: none !important; text-align: left !important; width: 100% !important;">
        <button type="button" class="btn btn-link text-decoration-none p-0 text-primary fw-bold" id="desktopBackBtn"
          style="font-size: 14px !important; margin: 0 !important; float: left !important; display: inline-flex !important; align-items: center !important;">
          <i class="bi bi-chevron-left me-1"></i> Back
        </button>
        <div style="clear: both;"></div>
      </div>

      <div class="search-filter-card mb-3" id="filter-container">
        <div class="row g-2 align-items-center">
          <div class="col-12 col-md-7">
            <div class="search-box-wrapper">
              <input type="text" class="form-control search-input" name="query" id="search"
                placeholder="Search employee name...">
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
      <div id="category-list" style="display: none;">
        <div class="category-header-card mb-3" id="cat-employee-card">
          <div class="d-flex align-items-center gap-3">
            <div class="emp-avatar-box-sm">
              <img id="cat-emp-avatar" src="{{ asset('/images/image-no-user.png') }}" alt="Employee Avatar"
                onerror="this.onerror=null;this.src='{{ asset('/images/image-no-user.png') }}';">
            </div>
            <div class="flex-grow-1 min-w-0">
              <h5 class="cat-header-name text-truncate" id="cat-employee-name">Select Category</h5>
              <span class="cat-header-org text-truncate" id="cat-employee-org"><i
                  class="bi bi-building me-1"></i>Employee</span>
            </div>
          </div>
        </div>

        <div class="row g-0">
          <div class="col-12 mb-3" id="technical-category">
            <div class="card category-card" data-id="1">
              <div class="cat-icon-box icon-blue">
                <i class="bi bi-tools"></i>
              </div>
              <div class="cat-info-content">
                <div class="cat-title">Technical Ability & Work Result</div>
                <span class="cat-desc">Assess technical skills, quality & output</span>
              </div>
              <div class="cat-arrow">
                <i class="bi bi-chevron-right"></i>
              </div>
            </div>
          </div>
          <div class="col-12 mb-3" id="behavior-category">
            <div class="card category-card" data-id="2">
              <div class="cat-icon-box icon-green">
                <i class="bi bi-list-task"></i>
              </div>
              <div class="cat-info-content">
                <div class="cat-title">Behavior & Work Processes</div>
                <span class="cat-desc">Assess work attitude, discipline & processes</span>
              </div>
              <div class="cat-arrow">
                <i class="bi bi-chevron-right"></i>
              </div>
            </div>
          </div>
          <div class="col-12 mb-3" id="leadership-category">
            <div class="card category-card" data-id="3">
              <div class="cat-icon-box icon-orange">
                <i class="bi bi-person-badge"></i>
              </div>
              <div class="cat-info-content">
                <div class="cat-title">Leadership</div>
                <span class="cat-desc">Assess leadership & team management skills</span>
              </div>
              <div class="cat-arrow">
                <i class="bi bi-chevron-right"></i>
              </div>
            </div>
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
      let isQuestionReadOnly = false;

      function updateSearchHeader(employee) {
        const searchInput = $('#search');
        const searchIcon = $('.search-icon');

        if (employee && employee.fullname) {
          searchInput.val(employee.fullname);
          searchInput.prop('disabled', true);
          searchInput.css({
            'background-color': '#e9ecef',
            'cursor': 'not-allowed'
          });
          if (searchIcon.length) {
            searchIcon.removeClass('bi-search').addClass('bi-person-fill');
          }
        } else {
          searchInput.val(query || '');
          searchInput.prop('disabled', false);
          searchInput.css({
            'background-color': '#f8fafc',
            'cursor': 'text'
          });
          searchInput.attr('placeholder', 'Search employee name...');
          if (searchIcon.length) {
            searchIcon.removeClass('bi-person-fill').addClass('bi-search');
          }
        }
      }

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
          if (isQuestionReadOnly && currentIndex === allQuestions.length - 1) {
            $('#next-btn').text('Close');
          } else {
            $('#next-btn').text(currentIndex === allQuestions.length - 1 ? 'Submit' : 'Next');
          }
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

      function getEmployeeAppraisal(employee, selPeriod) {
        if (!employee || !employee.appraisal_employees || !employee.appraisal_employees.length || !selPeriod) {
          return null;
        }
        const selPeriodOrgId = selPeriod.id;
        const selPeriodId = selPeriod.period_id || selPeriod.appraisal_period?.id;
        const selYear = selPeriod.appraisal_period?.period || selPeriod.period;
        const selSmester = selPeriod.appraisal_period?.smester || selPeriod.smester;

        return employee.appraisal_employees.find(app => {
          if (selPeriodOrgId && String(app.period_id) === String(selPeriodOrgId)) return true;
          if (app.period && selPeriodId && String(app.period.period_id) === String(selPeriodId)) return true;
          if (app.period && app.period.appraisal_period && selYear && selSmester) {
            if (String(app.period.appraisal_period.period) === String(selYear) &&
              String(app.period.appraisal_period.smester) === String(selSmester)) {
              return true;
            }
          }
          return false;
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
          const canAssess = isUserEvaluatorFor(employee) || (String(employee.id) === String(user.employ_id));
          const cursorClass = canAssess ? 'cursor-pointer' : '';
          const cursorStyle = canAssess ? 'cursor: pointer;' : 'cursor: default;';

          const appraisalEmployee = getEmployeeAppraisal(employee, selectedPeriod);
          const hasScore = appraisalEmployee && (
            (appraisalEmployee.total_point !== null && appraisalEmployee.total_point !== undefined &&
              appraisalEmployee.total_point !== '') ||
            appraisalEmployee.evaluator1_by || appraisalEmployee.evaluator2_by
          );

          const imageUrl = employee.photo_id ?
            `{{ route('file', ['id' => '__ID__']) }}`.replace('__ID__', employee.photo_id) :
            `{{ asset('/images/image-no-user.png') }}`;

          let scoreValue = '-';
          if (appraisalEmployee && appraisalEmployee.total_point !== null && appraisalEmployee.total_point !==
            undefined && appraisalEmployee.total_point !== '') {
            const num = parseFloat(appraisalEmployee.total_point);
            if (!isNaN(num)) {
              scoreValue = num.toFixed(2).replace(/\.00$/, '');
            }
          }

          const scoreBadge = hasScore ? `
            <div class="emp-score-box">
              <span class="score-badge">${scoreValue}</span>
              <button class="btn-detail-action" title="View Assessment Details">
                Detail <i class="bi bi-chevron-right ms-1"></i>
              </button>
            </div>
          ` : `
            <div class="emp-score-box">
              <span class="badge bg-light text-secondary border px-2 py-1" style="font-size: 11px; font-weight: 500;">Belum Dinilai</span>
            </div>
          `;

          const card = `
            <div class="emp-appraisal-card ${cursorClass}" style="${cursorStyle}"
                data-id="${employee.id}" data-score="${hasScore ? '1' : ''}">
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
            allPeriods = response.data || [];
            allPeriodsEmployeeLogin = allPeriods.filter(p => p.appraisal_period);
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
        const periodParam = selectedPeriod?.appraisal_period?.period || selectedPeriod?.period;
        const smesterParam = selectedPeriod?.appraisal_period?.smester || selectedPeriod?.smester;

        $.ajax({
          url: `{{ route('appraisal.employee.summary.data') }}`,
          data: {
            employee: selectedEmployee?.id,
          },
          method: 'GET',
          success: function(response) {
            allSummaryAppraisal = response.data || [];
            let summaryData = null;
            if (Array.isArray(allSummaryAppraisal) && allSummaryAppraisal.length > 0) {
              summaryData = allSummaryAppraisal.find(a =>
                  String(a.period) == String(periodParam) &&
                  String(a.smester) == String(smesterParam)
                ) || allSummaryAppraisal.find(a => String(a.employ_id) == String(selectedEmployee?.id)) ||
                allSummaryAppraisal[0];
            }

            if (!summaryData) {
              const appEmp = getEmployeeAppraisal(selectedEmployee, selectedPeriod);
              if (appEmp && (appEmp.total_point !== null || appEmp.evaluator1_by || appEmp.evaluator2_by)) {
                const score = (appEmp.total_point !== null && appEmp.total_point !== undefined && appEmp
                    .total_point !== '') ?
                  Number(appEmp.total_point).toFixed(2).replace(/\.00$/, '') : '-';
                summaryData = {
                  employ_id: selectedEmployee?.id,
                  appraisal_employ_id: appEmp.id,
                  period: periodParam || '-',
                  smester: smesterParam || '-',
                  final_score: score,
                  final_grade: appEmp.grade || (score !== '-' && Number(score) >= 9 ? 'A' : (score !== '-' &&
                    Number(score) >= 8 ? 'B' : 'A')),
                  tech_weight: 40,
                  tech_eval1_point: score,
                  tech_eval1_grade: appEmp.grade || 'A',
                  behavior_weight: 40,
                  behavior_eval1_point: score,
                  behavior_eval1_grade: appEmp.grade || 'A',
                };
              }
            }

            if (summaryData) {
              selectedSummary = summaryData;
              renderSummary(summaryData);
            } else {
              const summaryContainer = $("#appraisal-summary");
              summaryContainer.empty();

              summaryContainer.html(
                `<div class="d-flex flex-column justify-content-center align-items-center gap-2 border p-4 rounded bg-white shadow-sm my-3">
                    <img src="{{ asset('/images/nodata.png') }}" alt="No Data Found" class="img-fluid" style="max-width: 220px;" />
                    <h5 class="fw-bold text-dark mt-2 mb-1">Belum Ada Detail Penilaian</h5>
                    <p class="text-muted small mb-0 text-center">Karyawan ini belum memiliki ringkasan nilai penilaian untuk periode ini.</p>
                </div>`
              );
            }
          },
          error: function() {
            console.error('Failed to fetch summary.');
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
          p.appraisal_period?.period == data.period &&
          p.appraisal_period?.smester == data.smester &&
          p.organization_id == selectedEmployee?.org_id
        ) || selectedPeriod;

        const isClosed = filteredPeriod?.appraisal_period?.is_closed == 1 ||
          filteredPeriod?.appraisal_period?.is_closed === true ||
          filteredPeriod?.is_closed == 1 ||
          filteredPeriod?.is_closed === true ||
          selectedPeriod?.appraisal_period?.is_closed == 1 ||
          selectedPeriod?.appraisal_period?.is_closed === true;
        const isEvaluator = selectedEmployee ? isUserEvaluatorFor(selectedEmployee) : false;
        const isEditable = isEvaluator && !isClosed;

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

        const auth1Id = selectedEmployee?.organization?.authorized1?.id || (typeof selectedEmployee?.organization
          ?.authorized1 === 'object' ? null : selectedEmployee?.organization?.authorized1);
        const auth2Id = selectedEmployee?.organization?.authorized2?.id || (typeof selectedEmployee?.organization
          ?.authorized2 === 'object' ? null : selectedEmployee?.organization?.authorized2);
        const isTwoEvaluators = auth1Id && auth2Id && String(auth1Id) !== String(auth2Id);

        const renderCategoryScore = (eval1Point, eval1Grade, eval2Point, eval2Grade) => {
          if (!isTwoEvaluators) {
            const point = eval1Point ?? eval2Point ?? '-';
            const grade = eval1Grade ?? eval2Grade ?? 'N/A';
            return `<p>Evaluator: ${point} | Grade: ${grade}</p>`;
          }
          return `
            <p>Evaluator 1: ${eval1Point ?? '-'} | Grade: ${eval1Grade ?? 'N/A'}</p>
            <p>Evaluator 2: ${eval2Point ?? '-'} | Grade: ${eval2Grade ?? 'N/A'}</p>
          `;
        };

        const renderCategoryItem = (icon, title, eval1Point, eval1Grade, eval2Point, eval2Grade, weight) => {
          if (!weight && eval1Point === null && eval2Point === null) return '';
          return `
            <div class="summary-item">
              <i class="bi ${icon}"></i>
              <div>
                <h6>${title}</h6>
                ${renderCategoryScore(eval1Point, eval1Grade, eval2Point, eval2Grade)}
                <p>Weight: ${weight ? weight + '%' : '-'}</p>
              </div>
            </div>
          `;
        };

        const techHtml = renderCategoryItem('bi-tools', 'Technical Ability & Work Result', data.tech_eval1_point, data
          .tech_eval1_grade, data.tech_eval2_point, data.tech_eval2_grade, data.tech_weight);
        const behaviorHtml = renderCategoryItem('bi-list-task', 'Behavior & Work Processes', data
          .behavior_eval1_point, data.behavior_eval1_grade, data.behavior_eval2_point, data.behavior_eval2_grade,
          data.behavior_weight);
        const leadershipHtml = renderCategoryItem('bi-person-badge', 'Leadership', data.leadership_eval1_point, data
          .leadership_eval1_grade, data.leadership_eval2_point, data.leadership_eval2_grade, data.leadership_weight);

        summaryContainer.html(`
          <div class="summary-card">
            <div class="summary-header">
              <h5 class="mb-0 text-center">Period ${data.period}</h5>
              ${additionalInfoHTML}
            </div>
            ${techHtml}
            ${behaviorHtml}
            ${leadershipHtml}
            <div class="d-flex gap-2 mt-4">
              <button id="export-pdf" class="btn btn-primary">
                <i class="bi bi-file-earmark-pdf me-1"></i> Export PDF
              </button>
              ${componentEdit}
            </div>
          </div>
        `);
      }


      function fetchQuestions(isDetail, isReadOnly = false) {
        isQuestionReadOnly = isReadOnly;
        showLoading();
        $.ajax({
          url: `{{ route('appraisal.employee.get') }}/${selectedEmployee.id}`,
          method: 'GET',
          data: {
            period_year: selectedPeriod?.appraisal_period?.period || selectedPeriod?.period,
            period_smt: selectedPeriod?.appraisal_period?.smester || selectedPeriod?.smester
          },
          success: function(response) {
            questionData = response;
            allQuestions = response.appraisal_template?.appraisal_questions || [];
            allQuestions.sort((a, b) => {
              if (a.category_id !== b.category_id) {
                return a.category_id - b.category_id;
              }
              let groupCompare = (a.group_kpi || '').localeCompare(b.group_kpi || '');
              if (groupCompare !== 0) {
                return groupCompare;
              }
              return new Date(a.created_at) - new Date(b.created_at);
            });
            allCategories = [
              ...new Map(allQuestions.map(q => [q.category ? q.category.id : q.category_id, q.category || {
                id: q.category_id,
                name: 'Category'
              }])).values()
            ];
            allDefaultQuestionAnswers = questionData.appraisal_employee?.appraisal_employee_questions || [];
            currentIndex = allQuestions.findIndex(q => q.category_id === selectedCategory);
            if (currentIndex < 0) currentIndex = 0;

            allAnswers = [];
            if ((window.isEditingMode || isQuestionReadOnly) && allDefaultQuestionAnswers.length > 0) {
              allQuestions.forEach((q, idx) => {
                const def = allDefaultQuestionAnswers.find(item => String(item.question_id) === String(q
                    .id)) ||
                  allDefaultQuestionAnswers.find(item => item.question?.id && String(item.question.id) ===
                    String(q.id)) ||
                  allDefaultQuestionAnswers.find(item => item.question_id && allQuestions[idx] && String(
                    item.question_id) === String(allQuestions[idx].id));
                if (def) {
                  const val1 = (def.evaluator1_point !== null && def.evaluator1_point !== undefined && def
                    .evaluator1_point !== '') ? def.evaluator1_point : (def.evaluator1_value ?? '');
                  const val2 = (def.evaluator2_point !== null && def.evaluator2_point !== undefined && def
                    .evaluator2_point !== '') ? def.evaluator2_point : (def.evaluator2_value ?? '');
                  allAnswers[idx] = {
                    questionId: q.id,
                    categoryId: q.category_id,
                    evaluator1: val1,
                    evaluator1_point: val1,
                    evalutor1Weight: q.weight,
                    evaluator1Note: def.evaluator1_note || '',
                    evaluator1_note: def.evaluator1_note || '',
                    evaluator2: val2,
                    evaluator2_point: val2,
                    evalutor2Weight: q.weight,
                    evaluator2Note: def.evaluator2_note || '',
                    evaluator2_note: def.evaluator2_note || '',
                  };
                }
              });
            }

            if (isDetail && questionData.appraisal_employee?.appraisal_employee_questions?.length) {
              renderQuestionList();
            } else {
              renderQuestion(currentIndex, allDefaultQuestionAnswers);
            }
          },
          error: function() {
            console.error('Failed to fetch questions.');
            $('#question-list').html(`
              <div class="question-container p-4 text-center shadow-sm rounded border bg-white my-3" style="border-radius: 20px;">
                <img src="{{ asset('/images/nodata.png') }}" alt="No Data" class="img-fluid mb-2" style="max-width: 200px;" />
                <h5 class="fw-bold text-dark mb-1">Failed to Load Questions</h5>
                <p class="text-muted small mb-0">An error occurred while loading appraisal questions.</p>
              </div>
            `);
          },
          complete: function() {
            hideLoading();
          }
        });
      }

      function renderQuestion(index, defaultAnswers = []) {
        const questionContainer = $('#question-list');
        questionContainer.empty();

        if (!allQuestions || allQuestions.length === 0) {
          questionContainer.html(`
            <div class="question-container p-4 text-center shadow-sm rounded border bg-white my-3" style="border-radius: 20px;">
              <img src="{{ asset('/images/nodata.png') }}" alt="No Data Found" class="img-fluid mb-2" style="max-width: 200px;" />
              <h5 class="fw-bold text-dark mb-1">No Questions Found</h5>
              <p class="text-muted small mb-0">No appraisal template or questions assigned for this period.</p>
            </div>
          `);
          return;
        }

        if (index < 0 || index >= allQuestions.length) index = 0;
        const question = allQuestions[index];
        if (!question) return;

        let currentCategory = allCategories.find(cat => cat.id === question.category_id);
        let isAdmin = false;
        if (user.role) {
          const roleName = user.role.name.toLowerCase();
          isAdmin = (roleName === 'developer' || roleName === 'superadmin' || roleName === 'administrator');
        }

        const auth1Id = questionData.organization?.authorized1?.id || (typeof questionData.organization
          ?.authorized1 === 'object' ? null : questionData.organization?.authorized1) || (selectedEmployee
          ?.organization?.authorized1?.id || (typeof selectedEmployee?.organization?.authorized1 === 'object' ?
            null : selectedEmployee?.organization?.authorized1));
        const auth2Id = questionData.organization?.authorized2?.id || (typeof questionData.organization
          ?.authorized2 === 'object' ? null : questionData.organization?.authorized2) || (selectedEmployee
          ?.organization?.authorized2?.id || (typeof selectedEmployee?.organization?.authorized2 === 'object' ?
            null : selectedEmployee?.organization?.authorized2));

        const isTwoEvaluators = auth1Id && auth2Id && String(auth1Id) !== String(auth2Id);
        const singleEvaluatorMode = !isTwoEvaluators;
        let isAuth1 = auth1Id && String(auth1Id) === String(userOrgId);
        let isAuth2 = auth2Id && String(auth2Id) === String(userOrgId);
        let isEvaluator = isAuth1 || isAuth2 || isAdmin;

        let evaluator1Enabled = !isQuestionReadOnly && (isAdmin || isAuth1);
        let evaluator2Enabled = !isQuestionReadOnly && (isAdmin || isAuth2);
        let singleEnabled = !isQuestionReadOnly && isEvaluator;

        const currentQuestionAnswer = allAnswers[index] || {};
        const defaultQuestionAnswer = (window.isEditingMode || isQuestionReadOnly) ?
          (defaultAnswers.find(item => String(item.question_id) === String(question.id) ||
            (item.question && String(item.question.id) === String(question.id))) || {}) : {};

        function getScoreVal(...scores) {
          for (let s of scores) {
            if (s !== undefined && s !== null && s !== '' && s !== 'null' && s !== 'N/A') {
              let str = String(s).replace(',', '.').trim();
              let num = parseFloat(str);
              if (!isNaN(num)) {
                return num.toString();
              }
              return String(s).trim();
            }
          }
          return '';
        }

        function getNoteVal(...notes) {
          for (let n of notes) {
            if (n !== undefined && n !== null && n !== '' && n !== 'null' && n !== 'N/A') {
              return String(n).trim();
            }
          }
          return '';
        }

        const savedEvaluator1 = getScoreVal(
          currentQuestionAnswer.evaluator1,
          currentQuestionAnswer.evaluator1_point,
          (window.isEditingMode || isQuestionReadOnly) ? defaultQuestionAnswer.evaluator1_point : null,
          (window.isEditingMode || isQuestionReadOnly) ? defaultQuestionAnswer.evaluator1_value : null
        );

        const savedEvaluator2 = getScoreVal(
          currentQuestionAnswer.evaluator2,
          currentQuestionAnswer.evaluator2_point,
          (window.isEditingMode || isQuestionReadOnly) ? defaultQuestionAnswer.evaluator2_point : null,
          (window.isEditingMode || isQuestionReadOnly) ? defaultQuestionAnswer.evaluator2_value : null
        );

        const savedEvaluator1Note = getNoteVal(
          currentQuestionAnswer.evaluator1Note,
          currentQuestionAnswer.evaluator1_note,
          (window.isEditingMode || isQuestionReadOnly) ? defaultQuestionAnswer.evaluator1_note : null
        );

        const savedEvaluator2Note = getNoteVal(
          currentQuestionAnswer.evaluator2Note,
          currentQuestionAnswer.evaluator2_note,
          (window.isEditingMode || isQuestionReadOnly) ? defaultQuestionAnswer.evaluator2_note : null
        );

        const questionHtml = question.question ?
          `<div class="mb-2"><span class="text-muted small d-block" style="font-size: 11px; text-transform: uppercase; font-weight: 600;">Target / Question</span><span class="text-dark fw-semibold" style="font-size: 14px; line-height: 1.4;">${question.question}</span></div>` :
          '';
        const groupKpiHtml = question.group_kpi ?
          `<div class="mb-2"><span class="text-muted small d-block" style="font-size: 11px; text-transform: uppercase; font-weight: 600;">Group KPI</span><span class="text-secondary small">${question.group_kpi}</span></div>` :
          '';
        const formulaDescriptionHtml = question.formula_description ?
          `<div class="mb-2"><span class="text-muted small d-block" style="font-size: 11px; text-transform: uppercase; font-weight: 600;">Formula</span><span class="text-secondary small">${question.formula_description.replace(/\r\n/g, '<br>')}</span></div>` :
          '';
        const weightHtml = question.weight ?
          `<span class="badge bg-light text-secondary border px-2.5 py-1 rounded-3 small">Weight: ${question.weight}%</span>` :
          '';

        let evaluatorHtml = '';

        const singleDisabledAttr = (!singleEnabled || isQuestionReadOnly) ? 'disabled' : '';
        const scorePlaceholder = isQuestionReadOnly ? '' : 'Enter score 1-10';
        const notePlaceholder = isQuestionReadOnly ? '' : 'Add note...';

        const inputType = (isQuestionReadOnly || !isEvaluator) ? 'text' : 'number';

        if (singleEvaluatorMode) {
          const val = getScoreVal(savedEvaluator1, savedEvaluator2);
          const note = getNoteVal(savedEvaluator1Note, savedEvaluator2Note);
          evaluatorHtml = `
            <div class="evaluator-section mt-3 p-3 bg-light rounded-3 border">
              <div class="mb-3">
                <label class="form-label fw-bold text-dark small d-block mb-1">Evaluator Score (1-10):</label>
                <input type="${inputType}" class="evaluator-input form-control form-control-sm w-100" ${singleDisabledAttr} data-evaluator="both" data-id="${question.id}"
                    min="1" max="10" value="${val}" placeholder="${scorePlaceholder}">
              </div>
              <div class="mb-0">
                <label class="form-label fw-bold text-dark small d-block mb-1">Note:</label>
                <textarea class="evaluator-note form-control form-control-sm w-100" ${singleDisabledAttr} data-evaluator="both" data-id="${question.id}"
                        rows="3" placeholder="${notePlaceholder}">${note}</textarea>
              </div>
            </div>
          `;
        } else {
          const eval1DisabledAttr = (!evaluator1Enabled || isQuestionReadOnly) ? 'disabled' : '';
          const eval2DisabledAttr = (!evaluator2Enabled || isQuestionReadOnly) ? 'disabled' : '';

          const eval1Html = `
            <div class="evaluator-card p-3 border rounded mb-3 bg-light">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="fw-bold text-primary mb-0" style="font-size: 13px;">Evaluator 1</h6>
                ${!evaluator1Enabled && !isQuestionReadOnly ? '<span class="badge bg-secondary-subtle text-secondary" style="font-size: 10.5px;">Read Only</span>' : ''}
              </div>
              <div class="mb-3">
                <label class="form-label fw-bold text-dark small d-block mb-1">Evaluator 1 Score (1-10):</label>
                <input type="${evaluator1Enabled ? 'number' : 'text'}" class="evaluator-input form-control form-control-sm w-100" ${eval1DisabledAttr} data-evaluator="1" data-id="${question.id}"
                    min="1" max="10" value="${savedEvaluator1}" placeholder="${scorePlaceholder}">
              </div>
              <div class="mb-0">
                <label class="form-label fw-bold text-dark small d-block mb-1">Evaluator 1 Note:</label>
                <textarea class="evaluator-note form-control form-control-sm w-100" ${eval1DisabledAttr} data-evaluator="1" data-id="${question.id}"
                        rows="2" placeholder="${notePlaceholder}">${savedEvaluator1Note}</textarea>
              </div>
            </div>
          `;

          const eval2Html = `
            <div class="evaluator-card p-3 border rounded mb-3 bg-light">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="fw-bold text-primary mb-0" style="font-size: 13px;">Evaluator 2</h6>
                ${!evaluator2Enabled && !isQuestionReadOnly ? '<span class="badge bg-secondary-subtle text-secondary" style="font-size: 10.5px;">Read Only</span>' : ''}
              </div>
              <div class="mb-3">
                <label class="form-label fw-bold text-dark small d-block mb-1">Evaluator 2 Score (1-10):</label>
                <input type="${evaluator2Enabled ? 'number' : 'text'}" class="evaluator-input form-control form-control-sm w-100" ${eval2DisabledAttr} data-evaluator="2" data-id="${question.id}"
                    min="1" max="10" value="${savedEvaluator2}" placeholder="${scorePlaceholder}">
              </div>
              <div class="mb-0">
                <label class="form-label fw-bold text-dark small d-block mb-1">Evaluator 2 Note:</label>
                <textarea class="evaluator-note form-control form-control-sm w-100" ${eval2DisabledAttr} data-evaluator="2" data-id="${question.id}"
                        rows="2" placeholder="${notePlaceholder}">${savedEvaluator2Note}</textarea>
              </div>
            </div>
          `;

          evaluatorHtml = `<div class="mt-3">${eval1Html}${eval2Html}</div>`;
        }

        const card = `
            <div class="question-container p-4 shadow-sm rounded-4 fade-in border bg-white mb-3" style="box-shadow: 0 4px 20px rgba(0,0,0,0.04);">
                <div class="d-flex justify-content-between align-items-center mb-3">
                  <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-1.5 rounded-3" style="font-size: 12px;">${currentCategory?.name || 'Category'}</span>
                  <span class="badge bg-light text-dark border px-2.5 py-1 rounded-3">${index + 1} / ${allQuestions.length}</span>
                </div>
                ${questionHtml}
                ${groupKpiHtml}
                ${formulaDescriptionHtml}
                <div class="mb-2">${weightHtml}</div>
                ${evaluatorHtml}
                <div class="mt-4 d-flex gap-2">
                    <button class="btn btn-secondary rounded-3 px-4" id="prev-btn" ${index === 0 ? 'disabled' : ''}>Previous</button>
                    <button class="btn btn-primary rounded-3 px-4" id="next-btn">${index === allQuestions.length - 1 ? (isQuestionReadOnly ? 'Close' : 'Submit') : 'Next'}</button>
                </div>
            </div>
        `;

        questionContainer.append(card);
        updateNavigationButtons();
      }

      function renderQuestionList() {
        const questionContainer = $('#question-list');
        questionContainer.empty();

        const answersList = questionData?.appraisal_employee?.appraisal_employee_questions ||
          allDefaultQuestionAnswers || [];

        function formatScore(val) {
          if (val === undefined || val === null || val === '' || val === 'null' || val === 'N/A') return 'N/A';
          let num = parseFloat(String(val).replace(',', '.'));
          if (!isNaN(num)) return num.toString();
          return String(val).trim();
        }

        function formatNote(val) {
          if (val === undefined || val === null || val === '' || val === 'null' || val === 'N/A') return '-';
          return String(val).trim();
        }

        let displayList = [];
        if (allQuestions && allQuestions.length > 0) {
          displayList = allQuestions.map(q => {
            const ans = answersList.find(a => String(a.question_id) === String(q.id) || (a.question && String(a
              .question.id) === String(q.id))) || {};
            return {
              question: q,
              answer: ans,
              category_id: q.category_id,
              group_kpi: q.group_kpi,
              weight: q.weight,
              evaluator1_point: formatScore(ans.evaluator1_point !== null && ans.evaluator1_point !== undefined && ans
                .evaluator1_point !== '' ? ans.evaluator1_point : ans.evaluator1_value),
              evaluator2_point: formatScore(ans.evaluator2_point !== null && ans.evaluator2_point !== undefined && ans
                .evaluator2_point !== '' ? ans.evaluator2_point : ans.evaluator2_value),
              evaluator1_note: formatNote(ans.evaluator1_note),
              evaluator2_note: formatNote(ans.evaluator2_note),
            };
          });
        } else if (answersList.length > 0) {
          displayList = answersList.map(item => {
            const q = item.question || {};
            return {
              question: q,
              answer: item,
              category_id: q.category_id || item.category_id,
              group_kpi: q.group_kpi || item.group_kpi,
              weight: q.weight || item.weight,
              evaluator1_point: formatScore(item.evaluator1_point !== null && item.evaluator1_point !== undefined && item
                .evaluator1_point !== '' ? item.evaluator1_point : item.evaluator1_value),
              evaluator2_point: formatScore(item.evaluator2_point !== null && item.evaluator2_point !== undefined && item
                .evaluator2_point !== '' ? item.evaluator2_point : item.evaluator2_value),
              evaluator1_note: formatNote(item.evaluator1_note),
              evaluator2_note: formatNote(item.evaluator2_note),
            };
          });
        }

        if (displayList.length === 0) {
          questionContainer.append(`
            <div class="d-flex flex-column justify-content-center align-items-center gap-2 border-0 p-4 rounded-4 bg-white shadow-sm my-3">
              <i class="bi bi-inbox fs-2 text-muted d-block mb-2"></i>
              <h6 class="fw-bold text-dark mb-1">Belum Ada Detail Penilaian</h6>
              <p class="text-muted small mb-0 text-center">Karyawan ini belum memiliki rincian nilai penilaian untuk periode ini.</p>
            </div>
          `);
          return;
        }

        const auth1Id = questionData.organization?.authorized1?.id || (typeof questionData.organization
          ?.authorized1 === 'object' ? null : questionData.organization?.authorized1) || (selectedEmployee
          ?.organization?.authorized1?.id || (typeof selectedEmployee?.organization?.authorized1 === 'object' ?
            null : selectedEmployee?.organization?.authorized1));
        const auth2Id = questionData.organization?.authorized2?.id || (typeof questionData.organization
          ?.authorized2 === 'object' ? null : questionData.organization?.authorized2) || (selectedEmployee
          ?.organization?.authorized2?.id || (typeof selectedEmployee?.organization?.authorized2 === 'object' ?
            null : selectedEmployee?.organization?.authorized2));
        const isTwoEvaluators = auth1Id && auth2Id && String(auth1Id) !== String(auth2Id);
        const singleEvaluatorMode = !isTwoEvaluators;

        displayList.forEach(item => {
          const q = item.question || {};
          const cat = allCategories.find(c => c.id === item.category_id);
          const categoryName = cat?.name || (item.category_id === 1 ? 'Technical Ability & Work Result' : (item
            .category_id === 2 ? 'Behavior & Work Processes' : (item.category_id === 3 ? 'Leadership' :
              'Category')));
          const groupKPI = item.group_kpi || q.group_kpi || '';
          const qText = q.question || '-';
          const weight = item.weight || q.weight || '';

          const groupKpiHtml = groupKPI ? `
            <div class="mb-3">
              <span class="text-muted small d-block" style="font-size: 11px; text-transform: uppercase; font-weight: 600;">Group KPI</span>
              <span class="text-secondary small fw-medium">${groupKPI}</span>
            </div>
          ` : '';

          let evalScoresHtml = '';
          if (singleEvaluatorMode) {
            const singleScore = item.evaluator1_point !== 'N/A' ? item.evaluator1_point : item.evaluator2_point;
            const singleNote = item.evaluator1_note !== '-' ? item.evaluator1_note : item.evaluator2_note;
            evalScoresHtml = `
              <div class="p-3 bg-light rounded-3 border">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <span class="fw-bold text-dark small">Evaluator Score:</span>
                  <span class="badge bg-primary fs-6 px-3 py-1 rounded-pill">${singleScore}</span>
                </div>
                <div>
                  <span class="text-muted small d-block mb-1" style="font-size: 11px;">Catatan / Note:</span>
                  <p class="text-dark mb-0 small">${singleNote}</p>
                </div>
              </div>
            `;
          } else {
            evalScoresHtml = `
              <div class="row g-2">
                <div class="col-12 col-md-6">
                  <div class="p-3 bg-light rounded-3 border h-100">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                      <span class="fw-bold text-primary small">Evaluator 1 Score</span>
                      <span class="badge bg-primary rounded-pill px-2.5 py-1">${item.evaluator1_point}</span>
                    </div>
                    <span class="text-muted small d-block mb-1" style="font-size: 11px;">Note:</span>
                    <p class="text-dark mb-0 small" style="font-size: 12.5px;">${item.evaluator1_note}</p>
                  </div>
                </div>
                <div class="col-12 col-md-6">
                  <div class="p-3 bg-light rounded-3 border h-100">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                      <span class="fw-bold text-primary small">Evaluator 2 Score</span>
                      <span class="badge bg-primary text-white rounded-pill px-2.5 py-1">${item.evaluator2_point}</span>
                    </div>
                    <span class="text-muted small d-block mb-1" style="font-size: 11px;">Note:</span>
                    <p class="text-dark mb-0 small" style="font-size: 12.5px;">${item.evaluator2_note}</p>
                  </div>
                </div>
              </div>
            `;
          }

          const questionCard = `
            <div class="card shadow-sm border-0 mb-3 bg-white p-3 p-md-4 rounded-4" style="box-shadow: 0 4px 20px rgba(0,0,0,0.04);">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-1.5 rounded-3" style="font-size: 12px; letter-spacing: 0.2px;">${categoryName}</span>
                ${weight ? `<span class="badge bg-light text-secondary border px-2.5 py-1 rounded-3 small">Weight: ${weight}%</span>` : ''}
              </div>
              <div class="mb-2">
                <span class="text-muted small d-block" style="font-size: 11px; text-transform: uppercase; font-weight: 600;">Target / Question</span>
                <span class="text-dark fw-semibold" style="font-size: 14px; line-height: 1.4;">${qText}</span>
              </div>
              ${groupKpiHtml}
              ${evalScoresHtml}
            </div>
          `;

          questionContainer.append(questionCard);
        });
      }

      function saveResponse() {
        if (isQuestionReadOnly || !allQuestions || !allQuestions[currentIndex]) return;

        const questionId = allQuestions[currentIndex].id;
        const categoryId = allQuestions[currentIndex].category_id;
        const weight = allQuestions[currentIndex].weight;

        const inputBoth = $(`input[data-id='${questionId}'][data-evaluator='both']`);
        const input1 = $(`input[data-id='${questionId}'][data-evaluator='1']`);
        const input2 = $(`input[data-id='${questionId}'][data-evaluator='2']`);

        const noteBoth = $(`textarea[data-id='${questionId}'][data-evaluator='both']`);
        const note1 = $(`textarea[data-id='${questionId}'][data-evaluator='1']`);
        const note2 = $(`textarea[data-id='${questionId}'][data-evaluator='2']`);

        const existing = allAnswers[currentIndex] || {};

        if (inputBoth.length) {
          const val = inputBoth.val() || null;
          const note = noteBoth.length ? (noteBoth.val() || '') : '';

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
          const evaluator1 = input1.length ? (input1.val() || null) : (existing.evaluator1 ?? existing
            .evaluator1_point ?? null);
          const evaluator2 = input2.length ? (input2.val() || null) : (existing.evaluator2 ?? existing
            .evaluator2_point ?? null);
          const evaluator1Note = note1.length ? (note1.val() || '') : (existing.evaluator1Note ?? existing
            .evaluator1_note ?? '');
          const evaluator2Note = note2.length ? (note2.val() || '') : (existing.evaluator2Note ?? existing
            .evaluator2_note ?? '');

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

        const auth1Id = questionData.organization?.authorized1?.id || (typeof questionData.organization
          ?.authorized1 === 'object' ? null : questionData.organization?.authorized1) || (selectedEmployee
          ?.organization?.authorized1?.id || (typeof selectedEmployee?.organization?.authorized1 === 'object' ?
            null : selectedEmployee?.organization?.authorized1));
        const auth2Id = questionData.organization?.authorized2?.id || (typeof questionData.organization
          ?.authorized2 === 'object' ? null : questionData.organization?.authorized2) || (selectedEmployee
          ?.organization?.authorized2?.id || (typeof selectedEmployee?.organization?.authorized2 === 'object' ?
            null : selectedEmployee?.organization?.authorized2));

        const isTwoEvaluators = auth1Id && auth2Id && String(auth1Id) !== String(auth2Id);
        const singleEvaluatorMode = !isTwoEvaluators;

        let evaluatorRole = null;
        if (singleEvaluatorMode) {
          evaluatorRole = "single_evaluator";
        } else if (auth1Id && String(auth1Id) === String(userOrgId)) {
          evaluatorRole = "evaluator1";
        } else if (auth2Id && String(auth2Id) === String(userOrgId)) {
          evaluatorRole = "evaluator2";
        }

        if (!evaluatorRole) {
          let isAdmin = false;
          if (user.role) {
            const roleName = user.role.name.toLowerCase();
            isAdmin = (roleName === 'developer' || roleName === 'superadmin' || roleName === 'administrator');
          }
          if (isAdmin) {
            evaluatorRole = singleEvaluatorMode ? "single_evaluator" : "evaluator1";
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
          isAdmin = (roleName === 'developer' || roleName === 'superadmin' || roleName === 'administrator');
        }
        if (isAdmin) return true;

        const empOrg = employee?.organization;
        if (!empOrg) return false;

        const auth1Id = empOrg.authorized1?.id || (typeof empOrg.authorized1 === 'object' ? null : empOrg
          .authorized1);
        const auth2Id = empOrg.authorized2?.id || (typeof empOrg.authorized2 === 'object' ? null : empOrg
          .authorized2);

        return (auth1Id && String(auth1Id) === String(userOrgId)) || (auth2Id && String(auth2Id) === String(
          userOrgId));
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

            $('#technical-category, #behavior-category, #leadership-category').hide();

            if (questions.length === 0 || !response.appraisal_template) {
              $('#category-list').hide();
              $('#category-list').before(`
                <div id="no-template-alert" class="alert alert-warning text-center shadow-sm rounded-4 p-4 my-3">
                  <i class="bi bi-exclamation-triangle-fill fs-2 text-warning d-block mb-2"></i>
                  <h5 class="fw-bold">No Appraisal Template Assigned</h5>
                  <p class="mb-1 text-dark">There is no appraisal template assigned to <strong>${selectedEmployee.fullname}</strong> (${selectedEmployee.organization?.name || 'Organization'}) for period <strong>${selectedPeriod?.appraisal_period?.period || '-'} SMT ${selectedPeriod?.appraisal_period?.smester || '-'}</strong>.</p>
                  <small class="text-secondary d-block mt-2">Please assign an appraisal template to this organization and period in Appraisal Period Organization settings first.</small>
                </div>
              `);
            } else {
              categoryIds.forEach(catId => {
                const sel = categoryMap[catId];
                if (sel) {
                  $(sel).show();
                }
              });
              $('#category-list').show();
            }

            if (callback) callback(questions.length > 0 && !!response.appraisal_template);
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
        window.isEditingMode = true;
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
        updateSearchHeader(selectedEmployee);

        showCategoriesForTemplate();
      };

      function setNavState(state) {
        window.currentNavState = state;
        if (state === 'Back') {
          $('#page-header-title').text('Assessment Details');
          $('#btnAppraisalBack').removeClass('desktop-hidden');
          $('#desktop-back-bar').attr('style', 'display: none !important;');
        } else {
          $('#page-header-title').text('Employee Assessment');
          $('#btnAppraisalBack').addClass('desktop-hidden');
          $('#desktop-back-bar').attr('style', 'display: none !important;');
          updateSearchHeader(null);
        }
      }

      function switchSection(sectionId) {
        $('#employee-list, #category-list, #question-list, #appraisal-summary').hide();
        $('#load-more').hide();
        if (sectionId === '#employee-list') {
          setNavState('Appraisal');
          $('#period-selected').hide();
          $('#search-container, #filter-container, #employee-list').show();
          updateSearchHeader(null);
          updateLoadMoreButton();
        } else {
          setNavState('Back');
          $('#filter-container').show();
          $('#period-selected').show().text(selectedEmployee?.fullname || '');
          if (sectionId === '#question-list') {
            $('#search-container').show();
          } else {
            $('#search-container').hide();
          }
          $(sectionId).show();
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
          fetchQuestions(false, isQuestionReadOnly);
          fetchSummary();
        }
      });

      $(document).on('click', '.btn-detail-action, #detail-employee-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const card = $(this).closest('.emp-appraisal-card, .card');
        const employeeId = String(card.data('id'));
        selectedEmployee = allEmployees.find(emp => String(emp.id) === employeeId);
        if (!selectedEmployee) return;

        const filteredPeriodBySelectEmployee = allPeriods.filter(p => p.organization_id === selectedEmployee
          .org_id);
        const activePeriod = selectedPeriod;
        const smester = activePeriod?.appraisal_period?.smester;
        const period = activePeriod?.appraisal_period?.period;
        let targetPeriod = filteredPeriodBySelectEmployee.find(p =>
          p.appraisal_period?.period == period && p.appraisal_period?.smester == smester
        );
        selectedPeriod = targetPeriod || activePeriod;

        updateSearchHeader(selectedEmployee);
        currentIndex = 0;
        switchSection('#question-list');
        fetchQuestions(true, true);
      });

      $(document).on('click', '#employee-list .emp-appraisal-card, #employee-list .card', function(event) {
        event.preventDefault();
        event.stopPropagation();
        const employeeId = String($(this).data('id'));
        selectedEmployee = allEmployees.find(employee => String(employee.id) === employeeId);

        if (!selectedEmployee) return;

        const filteredPeriodBySelectEmployee = allPeriods.filter(p => p.organization_id === selectedEmployee
          .org_id);

        if (!selectedPeriod || !selectedPeriod.appraisal_period) return;

        const activePeriod = selectedPeriod;
        const smester = activePeriod?.appraisal_period?.smester;
        const period = activePeriod?.appraisal_period?.period;
        let targetPeriod = filteredPeriodBySelectEmployee.find(p =>
          p.appraisal_period?.period == period && p.appraisal_period?.smester == smester
        );
        selectedPeriod = targetPeriod || activePeriod;

        updateSearchHeader(selectedEmployee);

        const appraisalEmp = getEmployeeAppraisal(selectedEmployee, selectedPeriod);
        const hasScore = appraisalEmp && (
          (appraisalEmp.total_point !== null && appraisalEmp.total_point !== undefined && appraisalEmp
            .total_point !== '') ||
          appraisalEmp.evaluator1_by || appraisalEmp.evaluator2_by
        );
        const isEvaluator = isUserEvaluatorFor(selectedEmployee);

        if (!hasScore && isEvaluator) {
          switchSection('#category-list');
          showCategoriesForTemplate();
        } else {
          switchSection('#appraisal-summary');
          fetchSummary();
        }
      });

      $(document).on('click', '#category-list .card', function() {
        selectedCategory = $(this).data('id');
        if (selectedCategory) {
          currentIndex = 0;
          $('#title-back-container p').text('Back');
          updateSearchHeader(selectedEmployee);
          switchSection('#question-list');
          const isEvaluator = isUserEvaluatorFor(selectedEmployee);
          fetchQuestions(false, !isEvaluator);
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
        if (isQuestionReadOnly) {
          if (currentIndex < allQuestions.length - 1) {
            currentIndex++;
            renderQuestion(currentIndex, allDefaultQuestionAnswers);
          } else {
            switchSection('#appraisal-summary');
            fetchSummary();
          }
          return;
        }

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
          currentIndex = 0;
          selectedCategory = null;
          selectedSummary = null;
          allAnswers = [];
          switchSection('#category-list');
          showCategoriesForTemplate();
        } else {
          currentIndex = 0;
          selectedEmployee = null;
          selectedCategory = null;
          selectedSummary = null;
          switchSection('#employee-list');
          renderEmployees(filteredEmployees.slice(0, loadLimit));
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
