@extends('headers.head')

@section('header')
  <style>
    body, html {
      background-color: #ffffff !important;
    }

    .main-container {
      background-color: #ffffff;
      min-height: 100vh;
      padding: 20px 24px !important;
    }

    .emp-appraisal-card {
      background-color: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      transition: all 0.2s ease-in-out;
      cursor: pointer;
    }

    .emp-appraisal-card:hover {
      border-color: #0d59b7;
      box-shadow: 0 4px 12px rgba(13, 89, 183, 0.08) !important;
    }

    .emp-avatar-thumb {
      width: 56px;
      height: 56px;
      border-radius: 10px;
      object-fit: cover;
      border: 1px solid #e2e8f0;
    }

    .btn-detail-blue {
      background-color: #0d59b7 !important;
      border-color: #0d59b7 !important;
      color: #ffffff !important;
      border-radius: 6px !important;
      padding: 4px 18px !important;
      font-size: 12px !important;
      font-weight: 500 !important;
    }

    .btn-detail-blue:hover {
      background-color: #094796 !important;
      border-color: #094796 !important;
    }

    .category-card {
      background-color: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 10px;
      padding: 24px;
      text-align: center;
      transition: all 0.2s ease;
      cursor: pointer;
    }

    .category-card:hover {
      border-color: #0d59b7;
      box-shadow: 0 4px 12px rgba(13, 89, 183, 0.1);
    }

    .question-box {
      background-color: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 8px;
      padding: 16px;
      margin-bottom: 16px;
    }

    .evaluator-input {
      max-width: 180px;
    }
  </style>
@endsection

@section('body')
  <div class="main-container">
    <!-- Top Toolbar: Search Input & Period Filter -->
    <div class="row mb-4 align-items-center" id="top-toolbar">
      <div class="col-12 col-md-6 d-flex align-items-center mb-2 mb-md-0" id="toolbar-left">
        <div id="desktop-back-bar" style="display: none;">
          <button type="button" class="btn btn-outline-secondary btn-sm px-3" id="desktopBackBtn" style="border-radius: 6px;">
            <i class="bi bi-arrow-left me-1"></i> Back
          </button>
        </div>
      </div>

      <div class="col-12 col-md-6 d-flex flex-column flex-sm-row align-items-center justify-content-end gap-2" id="toolbar-right">
        <div class="input-group input-group-sm input-search-container" id="search-container" style="max-width: 320px;">
          <input type="text" class="form-control form-control-sm" name="search" id="searchInput" placeholder="Search...">
          <div class="input-group-append">
            <button class="btn btn-primary btn-sm" type="button" id="searchBtn">Search</button>
          </div>
        </div>

        <div class="d-flex flex-column flex-sm-row gap-2 dropdown-custom-container" id="filter-container">
          <div class="dropdown">
            <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="periodDropdownBtn" data-bs-toggle="dropdown" aria-expanded="false">
              Select Period
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="periodDropdownBtn" id="periodDropdownMenu" style="box-shadow:0 2px 4px rgba(0, 0, 0, 0.1);">
              <li><a class="dropdown-item disabled" href="#">Loading...</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <!-- Alert Box Container -->
    <div id="alert-container"></div>

    <!-- Loading Indicator -->
    <div id="loading" class="text-center py-5" style="display: none;">
      <div class="spinner-border text-primary" role="status" style="width: 2rem; height: 2rem;">
        <span class="visually-hidden">Loading...</span>
      </div>
      <p class="mt-2 text-muted small mb-0">Loading Assessment Data...</p>
    </div>

    <!-- Employee Cards List (Full-Width Rows) -->
    <div id="employee-list"></div>

    <!-- Load More Button -->
    <div class="text-center mt-3 mb-4">
      <button id="load-more" class="btn btn-outline-primary btn-sm px-4" style="display: none; border-radius: 6px;">
        Load More Employees
      </button>
    </div>

    <!-- Category List Selection -->
    <div id="category-list" style="display: none;">
      <div class="card shadow-sm border mb-4" style="border-radius: 12px;">
        <div class="card-body p-3 text-center">
          <img id="cat-emp-avatar" class="rounded mb-2" style="width: 64px; height: 64px; object-fit: cover; border-radius: 10px;" src="{{ asset('/images/image-no-user.png') }}" alt="Avatar">
          <h6 id="cat-employee-name" class="fw-bold text-dark mb-0">Select Category</h6>
          <p id="cat-employee-org" class="text-muted small mb-0" style="font-size: 12px;"></p>
        </div>
      </div>

      <div class="row g-3">
        <div class="col-md-4" id="technical-category">
          <div class="category-card" data-id="1">
            <i class="bi bi-gear-fill fs-3 text-primary d-block mb-2"></i>
            <h6 class="fw-bold text-dark mb-1">Technical Skills</h6>
            <p class="text-muted small mb-0" style="font-size: 12px;">Evaluate technical capabilities & job competence</p>
          </div>
        </div>

        <div class="col-md-4" id="behavior-category">
          <div class="category-card" data-id="2">
            <i class="bi bi-person-badge-fill fs-3 text-info d-block mb-2"></i>
            <h6 class="fw-bold text-dark mb-1">Behavior & Soft Skills</h6>
            <p class="text-muted small mb-0" style="font-size: 12px;">Assess attitude, teamwork, & communication</p>
          </div>
        </div>

        <div class="col-md-4" id="leadership-category">
          <div class="category-card" data-id="3">
            <i class="bi bi-award-fill fs-3 text-success d-block mb-2"></i>
            <h6 class="fw-bold text-dark mb-1">Leadership</h6>
            <p class="text-muted small mb-0" style="font-size: 12px;">Evaluate leadership & decision making</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Question Container -->
    <div id="question-list" style="display: none;"></div>

    <!-- Summary Container -->
    <div id="appraisal-summary" style="display: none;"></div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      let allPeriods = [];
      let allPeriodsEmployeeLogin = [];
      let selectedPeriod = null;
      let allEmployees = [];
      let filteredEmployees = [];
      let selectedEmployee = null;

      let allQuestions = [];
      let allCategories = [];
      let selectedCategory = null;

      let currentIndex = 0;
      let loadLimit = 12;
      let query = '';
      let user = @json($user);

      let questionData = null;
      let allAnswers = [];
      let allDefaultQuestionAnswers = [];
      let userOrgId = user.employee ? user.employee.org_id : null;

      let selectedSummary = null;
      let isQuestionReadOnly = false;

      function updateSearchHeader(employee) {
        const searchInput = $('#searchInput');
        const searchBtn = $('#searchBtn');

        if (employee && employee.fullname) {
          searchInput.val(employee.fullname);
          searchInput.prop('disabled', true);
          searchInput.css({'background-color': '#e9ecef', 'cursor': 'not-allowed'});
          searchBtn.prop('disabled', true);
        } else {
          searchInput.val(query || '');
          searchInput.prop('disabled', false);
          searchInput.css({'background-color': '#ffffff', 'cursor': 'text'});
          searchInput.attr('placeholder', 'Search employee name...');
          searchBtn.prop('disabled', false);
        }
      }

      function showLoading() { $('#loading').show(); }
      function hideLoading() { $('#loading').hide(); }

      function showAlert(type, message) {
        const alertHtml = `
          <div class="alert alert-${type} alert-dismissible fade show my-2" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        `;
        $('#alert-container').html(alertHtml);
      }

      function debounce(func, wait) {
        let timeout;
        return function(...args) {
          clearTimeout(timeout);
          timeout = setTimeout(() => func.apply(this, args), wait);
        };
      }

      function fetchPeriods() {
        showLoading();
        return $.ajax({
          url: '{{ route('appraisal.period.organization.data') }}',
          method: 'GET',
          data: { trash: 1 },
          success: function(response) {
            const data = response.data || response || [];
            allPeriods = data;
            const uniquePeriodsMap = new Map();
            data.forEach(p => {
              if (p.appraisal_period) {
                const key = `${p.appraisal_period.period}-${p.appraisal_period.smester}`;
                if (!uniquePeriodsMap.has(key)) {
                  uniquePeriodsMap.set(key, p);
                }
              }
            });

            allPeriodsEmployeeLogin = Array.from(uniquePeriodsMap.values());
            allPeriodsEmployeeLogin.sort((a, b) => b.appraisal_period.period - a.appraisal_period.period || b.appraisal_period.smester - a.appraisal_period.smester);

            const periodDropdownMenu = $('#periodDropdownMenu');
            const periodDropdownBtn = $('#periodDropdownBtn');
            periodDropdownMenu.empty();

            if (allPeriodsEmployeeLogin.length > 0) {
              const activePeriod = allPeriodsEmployeeLogin.find(p => p.appraisal_period.status === 'active') || allPeriodsEmployeeLogin[0];
              selectedPeriod = activePeriod;
              periodDropdownBtn.html(`${activePeriod.appraisal_period.period} ( SMT ${activePeriod.appraisal_period.smester} )`);

              allPeriodsEmployeeLogin.forEach(period => {
                const isSelected = period.appraisal_period.id === activePeriod.appraisal_period.id ? 'active' : '';
                const text = `${period.appraisal_period.period} ( SMT ${period.appraisal_period.smester} )`;
                periodDropdownMenu.append(
                  `<li><a class="dropdown-item period-select-item ${isSelected}" href="#" data-id="${period.appraisal_period.id}">${text}</a></li>`
                );
              });
            } else {
              periodDropdownBtn.html('No Periods Available');
              periodDropdownMenu.append('<li><a class="dropdown-item disabled" href="#">No Periods Available</a></li>');
            }
          },
          error: function() { showAlert('danger', 'Failed to load appraisal periods.'); },
          complete: function() { hideLoading(); }
        });
      }

      function fetchEmployees() {
        showLoading();
        return $.ajax({
          url: '{{ route('appraisal.employee.data.employee') }}',
          method: 'GET',
          data: { trash: 1 },
          success: function(response) {
            allEmployees = response.data || response || [];
            filterData();
          },
          error: function() { showAlert('danger', 'Failed to load employees.'); },
          complete: function() { hideLoading(); }
        });
      }

      function filterData() {
        filteredEmployees = allEmployees.filter(employee => {
          const fullName = employee.fullname ? employee.fullname.toLowerCase() : '';
          const orgName = employee.organization && employee.organization.name ? employee.organization.name.toLowerCase() : '';
          const searchQuery = query.toLowerCase();
          return fullName.includes(searchQuery) || orgName.includes(searchQuery);
        });

        currentIndex = 0;
        renderEmployees(filteredEmployees.slice(0, loadLimit));
        updateLoadMoreButton();
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
            <div class="text-center py-5">
              <i class="bi bi-inbox fs-2 text-muted d-block mb-2"></i>
              <p class="text-muted small">No employees found matching the search criteria</p>
            </div>
          `);
          return;
        }

        employees.forEach(employee => {
          const imageUrl = employee.photo_id ?
            `{{ route('file', ['id' => '__ID__']) }}`.replace('__ID__', employee.photo_id) :
            `{{ asset('/images/image-no-user.png') }}`;

          const appraisalEmp = getEmployeeAppraisal(employee, selectedPeriod);
          let scoreDisplay = '-';
          if (appraisalEmp && appraisalEmp.total_point !== null && appraisalEmp.total_point !== undefined && appraisalEmp.total_point !== '') {
            const num = parseFloat(appraisalEmp.total_point);
            if (!isNaN(num)) {
              scoreDisplay = num.toFixed(2).replace(/\.00$/, '');
            }
          }

          const card = `
            <div class="card shadow-sm border mb-3 emp-appraisal-card" data-id="${employee.id}">
              <div class="card-body p-3 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3 overflow-hidden me-3">
                  <img src="${imageUrl}" class="emp-avatar-thumb flex-shrink-0" alt="${employee.fullname}" onerror="this.src='{{ asset('/images/image-no-user.png') }}'">
                  <div class="overflow-hidden">
                    <h6 class="fw-bold text-dark mb-1 text-truncate" style="font-size: 15px;" title="${employee.fullname}">${employee.fullname}</h6>
                    <p class="text-muted small mb-0 text-truncate" style="font-size: 12.5px;">${employee.organization?.name || employee.division?.name || 'Department'}</p>
                  </div>
                </div>
                <div class="text-end flex-shrink-0 d-flex flex-column align-items-end gap-1">
                  <div class="fw-bold text-dark fs-5 mb-0">${scoreDisplay}</div>
                  <button type="button" class="btn btn-detail-blue btn-sm btn-detail-action">
                    Detail
                  </button>
                </div>
              </div>
            </div>
          `;
          employeeList.append(card);
        });
      }

      function updateLoadMoreButton() {
        if (currentIndex + loadLimit < filteredEmployees.length) {
          $('#load-more').show();
        } else {
          $('#load-more').hide();
        }
      }

      function fetchSummary() {
        if (!selectedEmployee) return;
        showLoading();
        const periodParam = selectedPeriod?.appraisal_period?.period || selectedPeriod?.period;
        const smesterParam = selectedPeriod?.appraisal_period?.smester || selectedPeriod?.smester;

        $.ajax({
          url: '{{ route('appraisal.employee.summary.data') }}',
          method: 'GET',
          data: { employee: selectedEmployee.id },
          success: function(response) {
            const list = response.data || response || [];
            let summaryData = null;
            if (Array.isArray(list) && list.length > 0) {
              summaryData = list.find(a => a.period == periodParam && a.smester == smesterParam) || list[0];
            } else if (response && response.appraisal_employ_id) {
              summaryData = response;
            }

            if (summaryData) {
              selectedSummary = summaryData;
              $('#question-list').hide();
              $('#category-list').hide();
              $('#appraisal-summary').show();
              renderSummary(summaryData);
            } else {
              $('#appraisal-summary').hide();
              const isSelf = selectedEmployee && String(selectedEmployee.id) === String(user.employ_id);
              const isEvaluator = isUserEvaluatorFor(selectedEmployee);
              if (!isEvaluator && !isSelf) {
                $('#question-list').show();
                fetchQuestions(true);
              } else {
                showCategoriesForTemplate(function() {
                  $('#category-list').show();
                });
              }
            }
          },
          error: function() {
            $('#appraisal-summary').hide();
            showCategoriesForTemplate(function() {
              $('#category-list').show();
            });
          },
          complete: function() {
            hideLoading();
          }
        });
      }

      function renderSummary(data) {
        const summaryContainer = $("#appraisal-summary");
        summaryContainer.empty();

        const isSelf = selectedEmployee && selectedEmployee.id == user.employ_id;
        const isEvaluator = selectedEmployee ? isUserEvaluatorFor(selectedEmployee) : false;
        const isEditable = !isSelf && isEvaluator;

        const componentEdit = isEditable ? `
          <button class="btn btn-warning btn-sm" onclick="editAppraisal('${data.appraisal_employ_id}')">
            <i class="bi bi-pencil me-1"></i> Edit Assessment
          </button>` : '';

        summaryContainer.html(`
          <div class="card shadow-sm border p-4 bg-white" style="border-radius: 12px;">
            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">Evaluation Assessment Summary - Period ${data.period || '-'} (Semester ${data.smester || '-'})</h6>
            <div class="row g-3 mb-3">
              <div class="col-md-4">
                <div class="p-3 bg-light rounded border">
                  <span class="text-muted small d-block">Employee Name</span>
                  <strong class="text-dark fs-6">${selectedEmployee.fullname}</strong>
                </div>
              </div>
              <div class="col-md-4">
                <div class="p-3 bg-light rounded border">
                  <span class="text-muted small d-block">Final Score</span>
                  <strong class="text-primary fs-5">${data.final_score ?? '-'}</strong>
                </div>
              </div>
              <div class="col-md-4">
                <div class="p-3 bg-light rounded border">
                  <span class="text-muted small d-block">Final Grade</span>
                  <strong class="text-success fs-5">${data.final_grade ?? '-'}</strong>
                </div>
              </div>
            </div>

            ${(data.tech_weight > 0 || data.tech_eval1_point !== null || data.tech_eval2_point !== null) ? `
              <div class="p-3 border rounded mb-2 bg-light">
                <h6 class="fw-bold text-dark mb-1"><i class="bi bi-gear-fill text-primary me-2"></i> Technical Ability & Work Result</h6>
                <p class="mb-0 small text-muted">Evaluator 1: ${data.tech_eval1_point ?? '-'} | Evaluator 2: ${data.tech_eval2_point ?? '-'} | Weight: ${data.tech_weight ? data.tech_weight + '%' : '-'}</p>
              </div>` : ''}

            ${(data.behavior_weight > 0 || data.behavior_eval1_point !== null || data.behavior_eval2_point !== null) ? `
              <div class="p-3 border rounded mb-2 bg-light">
                <h6 class="fw-bold text-dark mb-1"><i class="bi bi-person-badge-fill text-info me-2"></i> Behavior & Work Processes</h6>
                <p class="mb-0 small text-muted">Evaluator 1: ${data.tech_eval1_point ?? '-'} | Evaluator 2: ${data.tech_eval2_point ?? '-'} | Weight: ${data.behavior_weight ? data.behavior_weight + '%' : '-'}</p>
              </div>` : ''}

            ${(data.leadership_weight > 0 || data.leadership_eval1_point !== null || data.leadership_eval2_point !== null) ? `
              <div class="p-3 border rounded mb-3 bg-light">
                <h6 class="fw-bold text-dark mb-1"><i class="bi bi-award-fill text-success me-2"></i> Leadership</h6>
                <p class="mb-0 small text-muted">Evaluator 1: ${data.tech_eval1_point ?? '-'} | Evaluator 2: ${data.tech_eval2_point ?? '-'} | Weight: ${data.leadership_weight ? data.leadership_weight + '%' : '-'}</p>
              </div>` : ''}

            <div class="d-flex gap-2">
              <button id="export-pdf" class="btn btn-primary btn-sm btn-detail-blue">
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
              ...new Map(allQuestions.map(q => [q.category ? q.category.id : q.category_id, q.category || { id: q.category_id, name: 'Category' }])).values()
            ];
            allDefaultQuestionAnswers = questionData.appraisal_employee?.appraisal_employee_questions || [];
            currentIndex = allQuestions.findIndex(q => q.category_id === selectedCategory);
            if (currentIndex < 0) currentIndex = 0;

            allAnswers = [];
            if (allDefaultQuestionAnswers.length > 0) {
              allQuestions.forEach((q, idx) => {
                const def = allDefaultQuestionAnswers.find(item => String(item.question_id) === String(q.id)) ||
                  allDefaultQuestionAnswers.find(item => item.question?.id && String(item.question.id) === String(q.id)) ||
                  allDefaultQuestionAnswers.find(item => item.question_id && allQuestions[idx] && String(item.question_id) === String(allQuestions[idx].id));
                if (def) {
                  const val1 = (def.evaluator1_point !== null && def.evaluator1_point !== undefined && def.evaluator1_point !== '') ? def.evaluator1_point : (def.evaluator1_value ?? '');
                  const val2 = (def.evaluator2_point !== null && def.evaluator2_point !== undefined && def.evaluator2_point !== '') ? def.evaluator2_point : (def.evaluator2_value ?? '');
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
            showAlert('danger', 'Failed to fetch question data.');
            $('#question-list').html(`
              <div class="card shadow-sm border p-5 text-center bg-white my-3" style="border-radius: 12px;">
                <i class="bi bi-exclamation-triangle fs-2 text-warning d-block mb-2"></i>
                <h6 class="fw-bold text-dark mb-1">Failed to Load Questions</h6>
                <p class="text-muted small mb-0">An error occurred while fetching appraisal question data.</p>
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
            <div class="card shadow-sm border p-5 text-center bg-white my-3" style="border-radius: 12px;">
              <i class="bi bi-inbox fs-2 text-muted d-block mb-2"></i>
              <h6 class="fw-bold text-dark mb-1">No Assessment Questions Found</h6>
              <p class="text-muted small mb-0">No appraisal template or questions are assigned for this period.</p>
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
          isAdmin = (roleName === 'developer' || roleName === 'superadmin' || roleName === 'hrga');
        }

        const auth1Id = questionData.organization?.authorized1?.id || (typeof questionData.organization?.authorized1 === 'object' ? null : questionData.organization?.authorized1);
        const auth2Id = questionData.organization?.authorized2?.id || (typeof questionData.organization?.authorized2 === 'object' ? null : questionData.organization?.authorized2);

        let singleEvaluatorMode = auth1Id && auth2Id && String(auth1Id) === String(auth2Id);
        let evaluator1Enabled = isQuestionReadOnly || isAdmin || (auth1Id && String(auth1Id) === String(userOrgId));
        let evaluator2Enabled = isQuestionReadOnly || isAdmin || (auth2Id && String(auth2Id) === String(userOrgId));

        const currentQuestionAnswer = allAnswers[index] || {};
        const defaultQuestionAnswer = defaultAnswers.find(item => String(item.question_id) === String(question.id) || (item.question && String(item.question.id) === String(question.id))) || {};

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
          defaultQuestionAnswer.evaluator1_point,
          defaultQuestionAnswer.evaluator1_value
        );

        const savedEvaluator2 = getScoreVal(
          currentQuestionAnswer.evaluator2,
          currentQuestionAnswer.evaluator2_point,
          defaultQuestionAnswer.evaluator2_point,
          defaultQuestionAnswer.evaluator2_value
        );

        const savedEvaluator1Note = getNoteVal(
          currentQuestionAnswer.evaluator1Note,
          currentQuestionAnswer.evaluator1_note,
          defaultQuestionAnswer.evaluator1_note
        );

        const savedEvaluator2Note = getNoteVal(
          currentQuestionAnswer.evaluator2Note,
          currentQuestionAnswer.evaluator2_note,
          defaultQuestionAnswer.evaluator2_note
        );

        const questionHtml = question.question ? `<p class="mb-2"><strong>Target:</strong> ${question.question}</p>` : '';
        const groupKpiHtml = question.group_kpi ? `<p class="mb-2"><strong>Group KPI:</strong> ${question.group_kpi}</p>` : '';
        const formulaHtml = question.formula_description ? `<p class="mb-2"><strong>Formula:</strong> ${question.formula_description.replace(/\r\n/g, '<br>')}</p>` : '';
        const weightHtml = question.weight ? `<p class="mb-3"><strong>Weight:</strong> ${question.weight}%</p>` : '';

        let evaluatorHtml = '';
        const disabledAttr = isQuestionReadOnly ? 'disabled' : '';
        const scorePlaceholder = isQuestionReadOnly ? '' : 'Enter score 1-10';
        const notePlaceholder = isQuestionReadOnly ? '' : 'Add note...';

        const inputType = isQuestionReadOnly ? 'text' : 'number';

        if (singleEvaluatorMode) {
          const val = getScoreVal(savedEvaluator1, savedEvaluator2);
          const note = getNoteVal(savedEvaluator1Note, savedEvaluator2Note);
          evaluatorHtml = `
            <div class="evaluator-section mt-3">
              <div class="mb-3">
                <label class="form-label fw-bold text-dark small d-block mb-1">Evaluator Score (1-10):</label>
                <input type="${inputType}" class="form-control form-control-sm evaluator-input mb-2" ${disabledAttr} data-evaluator="both" data-id="${question.id}" min="1" max="10" value="${val}" placeholder="${scorePlaceholder}" style="max-width: 200px;">
              </div>
              <div class="mb-3">
                <label class="form-label fw-bold text-dark small d-block mb-1">Note:</label>
                <textarea class="form-control form-control-sm evaluator-note" ${disabledAttr} data-evaluator="both" data-id="${question.id}" rows="3" placeholder="${notePlaceholder}">${note}</textarea>
              </div>
            </div>
          `;
        } else {
          const eval1Html = evaluator1Enabled ? `
            <div class="col-md-6 mb-3">
              <div class="p-3 border rounded bg-light">
                <h6 class="fw-bold text-primary mb-2" style="font-size: 13px;">Evaluator 1</h6>
                <div class="mb-3">
                  <label class="form-label fw-bold text-dark small d-block mb-1">Evaluator 1 Score (1-10):</label>
                  <input type="${inputType}" class="form-control form-control-sm evaluator-input mb-2" ${disabledAttr} data-evaluator="1" data-id="${question.id}" min="1" max="10" value="${savedEvaluator1}" placeholder="${scorePlaceholder}" style="max-width: 200px;">
                </div>
                <div class="mb-2">
                  <label class="form-label fw-bold text-dark small d-block mb-1">Evaluator 1 Note:</label>
                  <textarea class="form-control form-control-sm evaluator-note" ${disabledAttr} data-evaluator="1" data-id="${question.id}" rows="2" placeholder="${notePlaceholder}">${savedEvaluator1Note}</textarea>
                </div>
              </div>
            </div>
          ` : '';

          const eval2Html = evaluator2Enabled ? `
            <div class="col-md-6 mb-3">
              <div class="p-3 border rounded bg-light">
                <h6 class="fw-bold text-info mb-2" style="font-size: 13px;">Evaluator 2</h6>
                <div class="mb-3">
                  <label class="form-label fw-bold text-dark small d-block mb-1">Evaluator 2 Score (1-10):</label>
                  <input type="${inputType}" class="form-control form-control-sm evaluator-input mb-2" ${disabledAttr} data-evaluator="2" data-id="${question.id}" min="1" max="10" value="${savedEvaluator2}" placeholder="${scorePlaceholder}" style="max-width: 200px;">
                </div>
                <div class="mb-2">
                  <label class="form-label fw-bold text-dark small d-block mb-1">Evaluator 2 Note:</label>
                  <textarea class="form-control form-control-sm evaluator-note" ${disabledAttr} data-evaluator="2" data-id="${question.id}" rows="2" placeholder="${notePlaceholder}">${savedEvaluator2Note}</textarea>
                </div>
              </div>
            </div>
          ` : '';

          evaluatorHtml = `<div class="row mt-3">${eval1Html}${eval2Html}</div>`;
        }

        let nextBtnText = index === allQuestions.length - 1 ? 'Submit' : 'Next';
        if (isQuestionReadOnly && index === allQuestions.length - 1) {
          nextBtnText = 'Close';
        }

        const card = `
          <div class="card shadow-sm border p-4 bg-white" style="border-radius: 12px;">
            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
              <span class="badge bg-primary fs-6">${currentCategory?.name || 'Category'}</span>
              <span class="text-muted small fw-bold">Question ${index + 1} of ${allQuestions.length}</span>
            </div>

            <div class="question-box mb-3">
              ${questionHtml}
              ${groupKpiHtml}
              ${formulaHtml}
              ${weightHtml}
            </div>

            ${evaluatorHtml}

            <div class="d-flex gap-2 mt-3">
              <button class="btn btn-secondary btn-sm" id="prev-btn" ${index === 0 ? 'disabled' : ''}>Previous</button>
              <button class="btn btn-primary btn-sm btn-detail-blue" id="next-btn">${nextBtnText}</button>
            </div>
          </div>
        `;
        questionContainer.append(card);
      }

      function renderQuestionList() {
        const questionContainer = $('#question-list');
        questionContainer.empty();

        if (!questionData?.appraisal_employee?.appraisal_employee_questions?.length) {
          questionContainer.append(`
            <div class="text-center py-5">
              <i class="bi bi-inbox fs-2 text-muted d-block mb-2"></i>
              <p class="text-muted small">No questions found</p>
            </div>
          `);
          return;
        }

        const sortedQuestions = questionData.appraisal_employee.appraisal_employee_questions;
        sortedQuestions.forEach(questionItem => {
          const question = questionItem.question;
          const category = allCategories.find(cat => cat.id === question?.category_id);

          const questionCard = `
            <div class="card shadow-sm border p-3 mb-3 bg-white" style="border-radius: 12px;">
              <h6 class="fw-bold text-primary mb-2">${category?.name || 'Category'}</h6>
              <p class="mb-1 small"><strong>Target:</strong> ${question?.question || '-'}</p>
              <p class="mb-1 small"><strong>Group KPI:</strong> ${question?.group_kpi || '-'}</p>
              <p class="mb-1 small"><strong>Weight:</strong> ${question?.weight || 0}%</p>
              <hr class="my-2">
              <div class="row small">
                <div class="col-md-6">
                  <p class="mb-1"><strong>Evaluator 1 Score:</strong> ${questionItem.evaluator1_point ?? '-'}</p>
                  <p class="mb-0"><strong>Note 1:</strong> ${questionItem.evaluator1_note || '-'}</p>
                </div>
                <div class="col-md-6">
                  <p class="mb-1"><strong>Evaluator 2 Score:</strong> ${questionItem.evaluator2_point ?? '-'}</p>
                  <p class="mb-0"><strong>Note 2:</strong> ${questionItem.evaluator2_note || '-'}</p>
                </div>
              </div>
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
            questionId, categoryId,
            evaluator1: val, evaluator1_point: val, evalutor1Weight: weight, evaluator1Note: note, evaluator1_note: note,
            evaluator2: val, evaluator2_point: val, evalutor2Weight: weight, evaluator2Note: note, evaluator2_note: note,
          };
        } else {
          const evaluator1 = input1.length ? (input1.val() || null) : (existing.evaluator1 ?? existing.evaluator1_point ?? null);
          const evaluator2 = input2.length ? (input2.val() || null) : (existing.evaluator2 ?? existing.evaluator2_point ?? null);
          const evaluator1Note = note1.length ? (note1.val() || '') : (existing.evaluator1Note ?? existing.evaluator1_note ?? '');
          const evaluator2Note = note2.length ? (note2.val() || '') : (existing.evaluator2Note ?? existing.evaluator2_note ?? '');

          allAnswers[currentIndex] = {
            questionId, categoryId,
            evaluator1: evaluator1, evaluator1_point: evaluator1, evalutor1Weight: weight, evaluator1Note: evaluator1Note, evaluator1_note: evaluator1Note,
            evaluator2: evaluator2, evaluator2_point: evaluator2, evalutor2Weight: weight, evaluator2Note: evaluator2Note, evaluator2_note: evaluator2Note,
          };
        }
      }

      function validateAnswers() {
        for (let i = 0; i < allQuestions.length; i++) {
          if (!allAnswers[i] || (allAnswers[i].evaluator1_point === '' && allAnswers[i].evaluator1 === '') ||
            (allAnswers[i].evaluator2_point === '' && allAnswers[i].evaluator2 === '')) {
            showAlert('danger', 'Please answer all questions before submitting!');
            return false;
          }
        }
        return true;
      }

      function submitAnswers() {
        if (!validateAnswers()) return;

        const auth1Id = questionData.organization?.authorized1?.id || (typeof questionData.organization?.authorized1 === 'object' ? null : questionData.organization?.authorized1);
        const auth2Id = questionData.organization?.authorized2?.id || (typeof questionData.organization?.authorized2 === 'object' ? null : questionData.organization?.authorized2);

        let evaluatorRole = null;
        if (auth1Id && auth2Id && String(auth1Id) === String(userOrgId) && String(auth2Id) === String(userOrgId)) {
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
            isAdmin = (roleName === 'developer' || roleName === 'superadmin' || roleName === 'hrga');
          }
          if (isAdmin) {
            let singleEvaluatorMode = auth1Id && auth2Id && String(auth1Id) === String(auth2Id);
            evaluatorRole = singleEvaluatorMode ? "single_evaluator" : "evaluator1";
          }
        }

        showLoading();
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
            fetchSummary();
            showAlert('success', 'Assessment completed successfully.');
          },
          error: function(jqXHR) {
            try {
              const res = JSON.parse(jqXHR.responseText);
              showAlert('danger', res.message || 'An error occurred while processing the data.');
            } catch (e) {
              showAlert('danger', 'An error occurred while processing the data.');
            }
          },
          complete: function() {
            hideLoading();
          }
        });
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
            const categoryMap = { 1: '#technical-category', 2: '#behavior-category', 3: '#leadership-category' };

            $('#technical-category, #behavior-category, #leadership-category').hide();

            if (questions.length === 0 || !response.appraisal_template) {
              $('#category-list').before(`
                <div id="no-template-alert" class="alert alert-warning text-center shadow-sm rounded p-3 my-3">
                  <i class="bi bi-exclamation-triangle-fill fs-3 text-warning d-block mb-1"></i>
                  <h6 class="fw-bold text-dark mb-1">No Appraisal Template Assigned</h6>
                  <p class="mb-0 small text-muted">There is no appraisal template assigned to <strong>${selectedEmployee.fullname}</strong> for this period.</p>
                </div>
              `);
            } else {
              categoryIds.forEach(catId => {
                const sel = categoryMap[catId];
                if (sel) $(sel).show();
              });
            }
            if (callback) callback();
          },
          error: function() {
            showAlert('danger', 'Failed to fetch template categories.');
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
        $('#search-container').show();
        $('#desktop-back-bar').show();

        currentIndex = 0;
        selectedSummary = null;
        allAnswers = [];
        selectedCategory = null;
        updateSearchHeader(selectedEmployee);

        showCategoriesForTemplate(function() {
          $('#category-list').show();
        });
      };

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

      $(document).on('click', '#desktopBackBtn', function() {
        $('#no-template-alert').remove();
        $('#category-list').hide();
        $('#appraisal-summary').hide();
        $('#question-list').hide();
        $('#desktop-back-bar').hide();
        $('#search-container').show();
        $('#employee-list').show();
        currentIndex = 0;
        selectedEmployee = null;
        selectedCategory = null;
        selectedSummary = null;
        updateSearchHeader(null);
        renderEmployees(filteredEmployees.slice(0, loadLimit));
        updateLoadMoreButton();
      });

      $(document).on('click', '.period-select-item', function(e) {
        e.preventDefault();
        const periodId = $(this).data('id');
        $('.period-select-item').removeClass('active');
        $(this).addClass('active');

        selectedPeriod = allPeriodsEmployeeLogin.find(p => p.appraisal_period.id == periodId);
        if (selectedPeriod) {
          const text = `${selectedPeriod.appraisal_period.period} ( SMT ${selectedPeriod.appraisal_period.smester} )`;
          $('#periodDropdownBtn').html(text);
          fetchEmployees();
        }
      });

      $(document).on('click', '#employee-list .emp-appraisal-card', function(event) {
        const employeeId = String($(this).data('id'));
        selectedEmployee = allEmployees.find(e => String(e.id) === employeeId);
        if (!selectedEmployee) return;

        updateSearchHeader(selectedEmployee);

        if (event.target.closest('.btn-detail-action')) {
          $('#employee-list').hide();
          $('#category-list').hide();
          $('#appraisal-summary').hide();
          $('#search-container').show();
          $('#load-more').hide();
          $('#desktop-back-bar').show();
          $('#question-list').show();
          currentIndex = 0;
          fetchQuestions(false, true);
          return;
        }

        $('#employee-list').hide();
        $('#category-list').hide();
        $('#question-list').hide();
        $('#search-container').show();
        $('#load-more').hide();
        $('#desktop-back-bar').show();
        $('#appraisal-summary').show();
        fetchSummary();
      });

      $(document).on('click', '#category-list .category-card', function() {
        selectedCategory = $(this).data('id');
        if (selectedCategory) {
          $('#category-list').hide();
          currentIndex = 0;
          $('#search-container').show();
          $('#question-list').show();
          updateSearchHeader(selectedEmployee);
          fetchQuestions(false, false);
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
            $('#question-list').hide();
            $('#appraisal-summary').show();
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

      $(document).on('input keyup', '#searchInput', debounce(function() {
        query = $(this).val();
        filterData();
      }, 300));

      $(document).on('click', '#searchBtn', function() {
        query = $('#searchInput').val();
        filterData();
      });

      $(document).on('click', '#load-more', function() {
        currentIndex += loadLimit;
        renderEmployees(filteredEmployees.slice(0, currentIndex + loadLimit));
        updateLoadMoreButton();
      });

      $(document).on('click', '#export-pdf', function() {
        if (selectedSummary && selectedEmployee) {
          const route = '{{ route('appraisal.question.template.front.export.pdf', ':employeeId') }}'.replace(':employeeId', selectedEmployee.id);
          const queryParams = new URLSearchParams({
            period: selectedSummary.period,
            smester: selectedSummary.smester,
            appraisal: selectedSummary.appraisal_employ_id
          });
          window.open(route + '?' + queryParams.toString(), '_blank');
        }
      });

      Promise.all([fetchPeriods(), fetchEmployees()]).then(() => {
        $('#category-list').hide();
        $('#appraisal-summary').hide();
      });
    });
  </script>
@endsection
