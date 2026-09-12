@extends('templates.mobile')

@section('head')
  <style>
    .btn-primary {
      font-size: 1rem;
      font-weight: 500;
      border: none
    }

    .search-container {
      position: relative;
      width: 60%;
    }

    .search-input {
      border-radius: 10px;
      padding-left: 45px;
      height: 40px;
    }

    .search-icon {
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      font-size: 1.2rem;
    }

    .period-selected {
      width: 60%;
      border-radius: 10px;
      font-size: 1.2rem;
      margin-bottom: 0;
      font-weight: 500;
      border: 1px solid #dee2e6;
      padding: 8px 12px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }


    .select2.select2-container.select2-container--default {
      border-radius: 10px !important;
      height: 40px !important;
      width: 40% !important;
    }

    .select2-selection.select2-selection--single {
      height: 100% !important;
      border: 1px solid #dee2e6 !important;
      border-radius: 10px !important;
    }

    .select2-selection.select2-selection--single .select2-selection__rendered {
      display: flex !important;
      justify-content: space-between !important;
      align-items: center !important;
      height: 100% !important;
    }

    .select2-selection.select2-selection--single .select2-selection__clear {
      order: 1 !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
      top: 8px !important;
    }

    .icon-blue {
      background-color: #007bff;
    }

    .icon-green {
      background-color: #28a745;
    }

    .icon-orange {
      background-color: #fd7e14;
    }

    #appraisal-summary {
      display: flex;
      flex-direction: column;
      gap: 1.2rem;
      max-width: 500px;
      margin: auto;
    }

    #appraisal-summary .summary-card {
      background: linear-gradient(135deg, #ffffff, #f0f2f5);
      border: 1px solid #dcdcdc;
      border-radius: 16px;
      padding: 1.6rem;
      box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
      transition: transform 0.3s ease-in-out;
    }

    #appraisal-summary .summary-card:hover {
      transform: translateY(-6px);
    }

    #appraisal-summary .summary-header {
      display: flex;
      flex-direction: column;
      gap: 0.2rem;
    }

    #appraisal-summary .summary-header h5 {
      font-weight: 600;
      color: #2c3e50;
      font-size: 1.5rem;
    }

    #appraisal-summary .summary-info {
      padding: 10px 0;
      display: flex;
      flex-direction: column;
      gap: 4px;
      font-size: 16px;
      color: #444;
    }

    #appraisal-summary .summary-info div {
      line-height: 1.4;
    }

    #appraisal-summary .summary-item {
      background: linear-gradient(135deg, #ffffff, #f8f9fa);
      padding: 1.2rem;
      border-radius: 12px;
      margin-top: 1.2rem;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      display: flex;
      align-items: center;
      gap: 1.2rem;
      transition: all 0.3s ease-in-out;
      border-left: 5px solid #007bff;
    }

    #appraisal-summary .summary-item:hover {
      background: #eef2f7;
    }

    #appraisal-summary .summary-item i {
      font-size: 1.6rem;
      color: #007bff;
    }

    #appraisal-summary .summary-item h6 {
      font-weight: bold;
      margin: 0;
      color: #2c3e50;
    }

    #appraisal-summary .summary-item p {
      margin: 0;
      font-size: 0.95rem;
      color: #6c757d;
    }


    #category-list .category-card {
      transition: transform 0.3s ease-in-out, box-shadow 0.3s;
      border-radius: 10px;
      border: none;
      background: linear-gradient(135deg, #ffffff, #f8f9fa);
      cursor: pointer;
    }

    #category-list .category-card:hover {
      transform: scale(1.05);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
      cursor: pointer;
    }

    #category-list .icon-container {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 10px;
    }

    #category-list .icon {
      font-size: 2rem;
      color: white;
    }

    #category-list h5 {
      font-weight: bold;
      color: #333;
    }

    #question-list .question-container {
      background: white;
      border-radius: 8px;
      padding: 20px;
    }

    #question-list .question-category {
      font-size: 1rem;
      font-weight: bold;
      color: #555;
      margin-bottom: 0px;
      text-align: center;
    }

    #question-list .question-progress {
      font-size: 0.8rem;
      font-weight: 500;
      color: #555;
      margin-bottom: 1rem;
      text-align: center;
    }

    #question-list .evaluator-input {
      width: 100%;
      padding: 8px;
      border: 1px solid #ddd;
      border-radius: 8px;
      font-size: 1rem;
    }

    #question-list .btn {
      padding: 10px 20px;
      border-radius: 5px;
    }
  </style>
@endsection

@section('content')
  <div class="px-3" style="min-height: 100%; min-width: 100%; padding-bottom: 100px;">
    <div class="py-3 {{ !isMobile() ? 'd-none' : '' }}" id="back-button-wrapper">
      <div class="d-flex align-items-center gap-4" style="cursor: pointer;" id="title-back-container">
        <i class="bi bi-chevron-left" style="font-size: 1.2rem;font-weight: 500;"></i>
        <p class="m-0" style="font-size: 1.4rem; font-weight: 500;">Appraisal</p>
      </div>
    </div>

    <div class="mb-3" id="filter-container">
      <div class="d-flex align-items-center gap-2 ">
        <div class="search-container position-relative flex-grow-1" id="search-container">
          <input type="text" class="form-control ps-5 search-input" name="query" id="search"
            placeholder="Search...">
          <i class="bi bi-search text-muted position-absolute search-icon"></i>
        </div>
        <h5 id="period-selected" class="period-selected"></h5>
        <select id="periodDropdown" class="form-select period-dropdown"></select>
      </div>
    </div>


    <div id="employee-list"></div>
    <div id="appraisal-summary"></div>
    <div class="row" id="category-list">
      <div class="col-md-4 mb-3" id="technical-category">
        <div class="card text-center p-4 shadow category-card border" data-id="1">
          <div class="icon-container icon-blue">
            <i class="bi bi-tools icon"></i>
          </div>
          <h5>Technical Ability & Work Result</h5>
        </div>
      </div>
      <div class="col-md-4 mb-3" id="behavior-category">
        <div class="card text-center p-4 shadow category-card border" data-id="2">
          <div class="icon-container icon-green">
            <i class="bi bi-list-task icon"></i>
          </div>
          <h5>Behavior & Work Processes</h5>
        </div>
      </div>
      <div class="col-md-4 mb-3" id="leadership-category">
        <div class="card text-center p-4 shadow category-card border" data-id="3">
          <div class="icon-container icon-orange">
            <i class="bi bi-person-badge icon"></i>
          </div>
          <h5>Leadership</h5>
        </div>
      </div>
    </div>
    <div id="question-list"></div>

    <div class="text-center">
      <button class="btn btn-primary mt-2" id="load-more" style="display: none;">Load More</button>
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

          const componentScoreAndDetail = appraisalEmployee ? `<div class="text-center">
                        <p class="mb-1 fw-semibold" style="font-size: 20px;">${appraisalEmployee.total_point ?? 0}</p>
                        <button id="detail-employee-btn" class="btn btn-primary btn-sm" style="font-size: 12px;">Detail</button>
                    </div>` : '';

          const card = `
            <div class="card shadow-sm mb-3 fade-in ${cursorClass}"
                style="border-radius: 12px; background: white; max-height: 110px; ${cursorStyle}"
                data-id="${employee.id}" data-score="${appraisalEmployee ? '1' : ''}">
              <div class="d-flex align-items-center">
                <img src="${imageUrl}" alt="${employee.fullname}"
                  style="margin-left: 4px; width: 90px; height: 90px; object-fit: cover; border-radius: 12px 0 0 12px;">
                <div class="d-flex justify-content-between align-items-center gap-2 w-100 p-3">
                    <div style="flex:1;">
                        <p class="mb-0 fw-bold">${employee.fullname}</p>
                        <p class="mb-0 text-muted" style="font-size: 14px;">${employee.organization?.name || ''}</p>
                    </div>
                   ${componentScoreAndDetail}
                </div>
              </div>
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
                <h5 class="mb-0 text-center">Periode ${data.period}</h5>
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
                        rows="2" placeholder="Tambahkan catatan...">${savedEvaluator1Note || savedEvaluator2Note}</textarea>
            `;
          evaluatorHtml = evaluatorBothHtml;
        } else {
          const evaluator1Html = evaluator1Enabled ? `
                <label>Evaluator Score:</label>
                <input type="number" class="evaluator-input" data-evaluator="1" data-id="${question.id}"
                    min="1" max="10" value="${savedEvaluator1}">
                <label>Note:</label>
                <textarea class="evaluator-note form-control" data-evaluator="1" data-id="${question.id}"
                        rows="2" placeholder="Tambahkan catatan...">${savedEvaluator1Note}</textarea>
            ` : '';

          const evaluator2Html = evaluator2Enabled ? `
                <label>Evaluator Score:</label>
                <input type="number" class="evaluator-input" data-evaluator="2" data-id="${question.id}"
                    min="1" max="10" value="${savedEvaluator2}">
                <label>Note:</label>
                <textarea class="evaluator-note form-control" data-evaluator="2" data-id="${question.id}"
                        rows="2" placeholder="Tambahkan catatan...">${savedEvaluator2Note}</textarea>
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
            showAlert('danger', `Mohon isi semua jawaban sebelum submit!`);
            return false;
          }
        }
        return true;
      }

      function submitAnswers() {
        if (!validateAnswers()) return showAlert('danger', 'Mohon isi semua jawaban sebelum submit!');

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

      $(document).on('change', '#periodDropdown', function(event) {
        const dropdown = event.target;
        selectedPeriod = allPeriodsEmployeeLogin.find(p => p.appraisal_period.id == dropdown.value);

        if ($('#title-back-container p').text() === 'Appraisal') {
          fetchEmployees();
        } else {
          $('#load-more').hide();
          fetchQuestions(true);
          fetchSummary();
        }

      })

      $(document).on('click', '#employee-list .card', function(event) {
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

        if (event.target.closest('#detail-employee-btn')) {
          $('#employee-list').hide();
          $('#filter-container').show();
          $('#search-container').hide();
          $('#period-selected').show().text(
            selectedEmployee.fullname || 'No period selected'
          );
          $('#load-more').hide();
          $('#question-list').show();
          $('#title-back-container p').text('Back');
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
          $('#title-back-container p').text('Back');
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
          $('#title-back-container p').text('Back');
          $('#load-more').hide();
          fetchSummary();
        } else {
          $('#employee-list').hide();
          currentIndex = 0;
          $('#filter-container').hide();
          $('#load-more').hide();
          $('#title-back-container p').text('Back');

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

      $('#title-back-container').on('click', function() {
        $('#no-template-alert').remove();
        if ($('#title-back-container p').text() === 'Appraisal') {
          history.back();
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
          $('#title-back-container p').text('Appraisal');
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
