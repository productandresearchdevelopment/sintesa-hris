<script>
  var Forms = function() {
    let me = Ext.utils.windowForms(this);
    me.data = null;
    me.selected = null;
    me.selectedEmployee = null;

    me.init = function() {
      me.store = Ext.create('Ext.data.Store', {
        pageSize: 50,
        autoload: true,
        fields: [{
            name: 'appraisal_employ_id',
            type: 'string'
          },
          {
            name: 'formatted_period',
            type: 'string'
          },
          {
            name: 'period',
            type: 'string'
          },
          {
            name: 'smester',
            type: 'auto'
          },
          {
            name: 'tech_weight',
            type: 'auto'
          },
          {
            name: 'tech_eval1_point',
            type: 'auto'
          },
          {
            name: 'tech_eval1_grade',
            type: 'auto'
          },
          {
            name: 'tech_eval2_point',
            type: 'auto'
          },
          {
            name: 'tech_eval2_grade',
            type: 'auto'
          },
          {
            name: 'behavior_weight',
            type: 'auto'
          },
          {
            name: 'behavior_eval1_point',
            type: 'auto'
          },
          {
            name: 'behavior_eval1_grade',
            type: 'auto'
          },
          {
            name: 'behavior_eval2_point',
            type: 'auto'
          },
          {
            name: 'behavior_eval2_grade',
            type: 'auto'
          },
          {
            name: 'leadership_weight',
            type: 'auto'
          },
          {
            name: 'leadership_eval1_point',
            type: 'auto'
          },
          {
            name: 'leadership_eval1_grade',
            type: 'auto'
          },
          {
            name: 'leadership_eval2_point',
            type: 'auto'
          },
          {
            name: 'leadership_eval2_grade',
            type: 'auto'
          },
          {
            name: 'final_score',
            type: 'auto'
          },
          {
            name: 'final_grade',
            type: 'auto'
          },
        ],
        remoteSort: true,
        proxy: {
          type: 'ajax',
          url: '{{ route('appraisal.employee.summary.data') }}',
          reader: {
            root: 'data',
            totalProperty: 'count',
            simpleSortMode: true,
          }
        },
      });

      me.grids = Ext.create('Ext.grid.Panel', {
        region: 'center',
        height: 'auto',
        maxHeight: 500,
        width: '100%',
        autoScroll: true,
        columnLines: true,
        store: me.store,
        columns: [{
            text: "PERIODE",
            dataIndex: "formatted_period",
            width: 150,
            align: "center",
          },
          {
            text: "Technical Ability & Work Result",
            columns: [{
                text: "WEIGHT",
                dataIndex: "tech_weight",
                width: 80,
                align: "center",
                renderer: function(val, meta, rec) {
                  return val ? val + '%' : '-';
                }
              },
              {
                text: "Evaluator 1",
                columns: [{
                    text: "POINT",
                    dataIndex: "tech_eval1_point",
                    width: 80,
                    align: "center",
                  },
                  {
                    text: "GRADE",
                    dataIndex: "tech_eval1_grade",
                    width: 80,
                    align: "center",
                  }
                ]
              },
              {
                text: "Evaluator 2",
                columns: [{
                    text: "POINT",
                    dataIndex: "tech_eval2_point",
                    width: 80,
                    align: "center",
                  },
                  {
                    text: "GRADE",
                    dataIndex: "tech_eval2_grade",
                    width: 80,
                    align: "center",
                  }
                ]
              }
            ]
          },
          {
            text: "Behavior & Work Processes",
            columns: [{
                text: "WEIGHT",
                dataIndex: "behavior_weight",
                width: 80,
                align: "center",
                renderer: function(val, meta, rec) {
                  return val ? val + '%' : '-';
                }
              },
              {
                text: "Evaluator 1",
                columns: [{
                    text: "POINT",
                    dataIndex: "behavior_eval1_point",
                    width: 80,
                    align: "center",
                  },
                  {
                    text: "GRADE",
                    dataIndex: "behavior_eval1_grade",
                    width: 80,
                    align: "center",
                  }
                ]
              },
              {
                text: "Evaluator 2",
                columns: [{
                    text: "POINT",
                    dataIndex: "behavior_eval2_point",
                    width: 80,
                    align: "center",
                  },
                  {
                    text: "GRADE",
                    dataIndex: "behavior_eval2_grade",
                    width: 80,
                    align: "center",
                  }
                ]
              }
            ]
          },
          {
            text: "Leadership",
            columns: [{
                text: "WEIGHT",
                dataIndex: "leadership_weight",
                width: 80,
                align: "center",
                renderer: function(val, meta, rec) {
                  return val ? val + '%' : '-';
                }
              },
              {
                text: "Evaluator 1",
                columns: [{
                    text: "POINT",
                    dataIndex: "leadership_eval1_point",
                    width: 80,
                    align: "center",
                  },
                  {
                    text: "GRADE",
                    dataIndex: "leadership_eval1_grade",
                    width: 80,
                    align: "center",
                  }
                ]
              },
              {
                text: "Evaluator 2",
                columns: [{
                    text: "POINT",
                    dataIndex: "leadership_eval2_point",
                    width: 80,
                    align: "center",
                  },
                  {
                    text: "GRADE",
                    dataIndex: "leadership_eval2_grade",
                    width: 80,
                    align: "center",
                  }
                ]
              }
            ]
          },
          {
            text: "FINAL SCORE & GRADE",
            columns: [{
                text: "SCORE",
                dataIndex: "final_score",
                width: 100,
                align: "center"
              },
              {
                text: "GRADE",
                dataIndex: "final_grade",
                width: 100,
                align: "center"
              }
            ]
          },
          {
            text: "ACTION",
            xtype: 'actioncolumn',
            width: 50,
            align: 'center',
            sortable: false,
            menuDisabled: true,
            items: [{
              iconCls: 'icon-pdf',
              tooltip: 'Export PDF',
              handler: function(grid, rowIndex, colIndex) {
                let record = grid.getStore().getAt(rowIndex);
                const route =
                  '{{ route('appraisal.question.template.front.export.pdf', ':employeeId') }}'
                  .replace(':employeeId', me.data.id);

                const queryParams = new URLSearchParams({
                  period: record.data.period,
                  smester: record.data.smester,
                  appraisal: record.data.appraisal_employ_id
                });

                window.open(route + '?' + queryParams.toString(), '_blank');
              }
            }]
          },
        ],
        listeners: {
          itemclick: function(obj, rec) {
            if (!rec.get('deleted_at')) {
              me.selected = rec;

              const title = `PERIODE ${rec.get('period')} - SEMESTER ${rec.get('smester')}`;
              me.previewPanel.setTitle(title);

              const html = me.buildSummaryHTML(rec.data);
              me.previewPanel.update(html);

              Ext.defer(() => {
                const exportBtn = document.getElementById('export-pdf');
                if (exportBtn) {
                  exportBtn.addEventListener('click', function() {
                    const employeeId = me.selectedEmployee.id;
                    const route =
                      '{{ route('appraisal.question.template.front.export.pdf', ':employeeId') }}'
                      .replace(':employeeId', employeeId);

                    const queryParams = new URLSearchParams({
                      period: rec.get('period'),
                      smester: rec.get('smester'),
                      appraisal: rec.get('appraisal_employ_id')
                    });

                    window.open(route + '?' + queryParams.toString(), '_blank');
                  });
                }
              }, 100);

            }
          },
          itemdblclick: function(obj, rec) {
            if (!rec.get('deleted_at')) {
              me.selected = rec;
              const regionEast = me.window.query('panel[region=east]')[0];
              if (regionEast.collapsed) {
                const title = `PERIODE ${rec.get('period')} - SEMESTER ${rec.get('smester')}`;
                me.previewPanel.setTitle(title);
                me.previewPanel.show();
                regionEast.expand();
              }
            }
          }
        }
      });

      me.previewPanel = Ext.create('Ext.panel.Panel', {
        id: 'detail-panel',
        region: 'east',
        title: 'VIEW DETAIL',
        split: true,
        width: 400,
        bodyPadding: 12,
        minWidth: 300,
        cls: 'tabx',
        border: false,
        collapsible: true,
        collapsed: true,
        autoScroll: true
      });

      me.window = Ext.create('Ext.window.Window', {
        layout: 'border',
        modal: true,
        maximized: true,
        closeAction: 'hide',
        autoScroll: true,
        items: [
          me.grids,
          me.previewPanel
        ]
      });
    };

    me.buildSummaryHTML = function(data) {
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

      return `
        <div id="appraisal-summary">
            <div class="summary-card">
            <div class="summary-header">
                <h5 class="mb-0 text-center">Periode ${data.period}</h5>
                ${additionalInfoHTML}
            </div>

            <div class="summary-item">
                <i class="bi bi-tools"></i>
                <div>
                <h6>Technical Ability & Work Result</h6>
                <p>Evaluator 1: ${data.tech_eval1_point ?? 0} | Grade: ${data.tech_eval1_grade ?? 'N/A'}</p>
                <p>Evaluator 2: ${data.tech_eval2_point ?? 0} | Grade: ${data.tech_eval2_grade ?? 'N/A'}</p>
                <p>Weight: ${data.tech_weight}%</p>
                </div>
            </div>

            <div class="summary-item">
                <i class="bi bi-list-task"></i>
                <div>
                <h6>Behavior & Work Processes</h6>
                <p>Evaluator 1: ${data.behavior_eval1_point ?? 0} | Grade: ${data.behavior_eval1_grade ?? 'N/A'}</p>
                <p>Evaluator 2: ${data.behavior_eval2_point ?? 0} | Grade: ${data.behavior_eval2_grade ?? 'N/A'}</p>
                <p>Weight: ${data.behavior_weight}%</p>
                </div>
            </div>

            <div class="summary-item">
                <i class="bi bi-person-badge"></i>
                <div>
                <h6>Leadership</h6>
                <p>Evaluator 1: ${data.leadership_eval1_point ?? 0} | Grade: ${data.leadership_eval1_grade ?? 'N/A'}</p>
                <p>Evaluator 2: ${data.leadership_eval2_point ?? 0} | Grade: ${data.leadership_eval2_grade ?? 'N/A'}</p>
                <p>Weight: ${data.leadership_weight}%</p>
                </div>
            </div>

            <div class="mt-4">
                <button id="export-pdf" class="btn btn-primary btn-sm w-100">
                <i class="bi bi-file-earmark-pdf me-1"></i> Export PDF
                </button>
            </div>
            </div>
        </div>
     `;
    }

    me.detail = function(selectedEmployee) {
      me.selectedEmployee = selectedEmployee;
      var rec = grids.getRec(true);
      me.data = rec;
      me.window.setTitle('Summary Performance Appraisal');
      me.window.show();
      me.store.getProxy().setExtraParam('employee', rec.id);
      me.store.load();
    };
  };
</script>

<style>
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

  #appraisal-summary .summary-header {
    display: flex;
    flex-direction: column;
    gap: 0.2rem;
  }

  #appraisal-summary .summary-header h5 {
    font-weight: 600;
    color: #2c3e50;
    font-size: 1rem;
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
    font-size: 0.8rem;
    line-height: 1.4;
  }

  #appraisal-summary .summary-item {
    background: linear-gradient(135deg, #ffffff, #f8f9fa);
    padding: 1.2rem;
    border-radius: 12px;
    margin-top: 1rem;
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
    font-size: 0.8rem;
    font-weight: bold;
    margin: 0;
    color: #2c3e50;
  }

  #appraisal-summary .summary-item p {
    margin: 0;
    font-size: 0.7rem;
    color: #6c757d;
  }
</style>
