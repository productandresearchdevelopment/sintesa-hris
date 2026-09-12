<script>
  var Forms = function() {
    let me = Ext.utils.windowForms(this);
    me.data = null;
    me.selected = null;

    // me.createDetailPanel = function(title, kpiOrDimension, showTarget) {
    //   return Ext.create('Ext.grid.Panel', {
    //     title: title,
    //     hidden: false,
    //     height: 'auto',
    //     maxHeight: 400,
    //     width: '100%',
    //     autoScroll: true,
    //     columnLines: true,
    //     store: Ext.create('Ext.data.Store', {
    //       fields: [{
    //           name: 'question',
    //           type: 'auto'
    //         },
    //         {
    //           name: 'evaluator1_point',
    //           type: 'number'
    //         },
    //         {
    //           name: 'evaluator1_total',
    //           type: 'number'
    //         },
    //         {
    //           name: 'evaluator2_point',
    //           type: 'number'
    //         },
    //         {
    //           name: 'evaluator2_total',
    //           type: 'number'
    //         }
    //       ]
    //     }),
    //     tools: [{
    //       type: 'close',
    //       tooltip: 'Close Panel',
    //       handler: function(event, toolEl, panelHeader) {
    //         panelHeader.up('panel').hide();
    //       }
    //     }],
    //     columns: [{
    //         text: kpiOrDimension,
    //         dataIndex: "question",
    //         flex: 1,
    //         align: 'center',
    //         renderer: function(value, metaData, record, rowIndex, colIndex, store) {
    //           let isFirstOccurrence = rowIndex === 0 || store.getAt(rowIndex - 1).get('question')
    //             ?.group_kpi !== value?.group_kpi;
    //           metaData.style = "white-space: normal; padding: 8px;";
    //           return isFirstOccurrence ? value.group_kpi : '';
    //         }
    //       },
    //       showTarget ? {
    //         text: "TARGET",
    //         dataIndex: "question",
    //         flex: 1,
    //         align: 'center',
    //         renderer: function(val, metaData) {
    //           metaData.style = "white-space: normal; padding: 8px; text-align: left;";
    //           return val ? val.question : '-';
    //         }
    //       } : null,
    //       {
    //         text: "FORMULA",
    //         dataIndex: "question",
    //         flex: 1,
    //         align: 'center',
    //         renderer: function(val, metaData) {
    //           metaData.style = "white-space: normal; padding: 8px; text-align: left;";
    //           if (!val || !val.formula_description) return '-';
    //           return val.formula_description.replace(/\r\n/g, '<br>');
    //         }
    //       },
    //       {
    //         text: "WEIGHT",
    //         dataIndex: "question",
    //         width: 80,
    //         align: 'center',
    //         renderer: function(val) {
    //           return val.weight + '%';
    //         }
    //       },
    //       {
    //         text: "EVALUATOR 1",
    //         columns: [{
    //             text: "POINT",
    //             dataIndex: "evaluator1_point",
    //             width: 80,
    //             align: 'center'
    //           },
    //           {
    //             text: "TOTAL",
    //             width: 80,
    //             align: 'center',
    //             renderer: function(value, metaData, record) {
    //               let weight = record.get('question')?.weight;
    //               let point = record.get('evaluator1_point');
    //               return weight ? ((point * weight) / 100).toFixed(2) : '0';
    //             }
    //           }
    //         ]
    //       },
    //       {
    //         text: "EVALUATOR 2",
    //         columns: [{
    //             text: "POINT",
    //             dataIndex: "evaluator2_point",
    //             width: 80,
    //             align: 'center'
    //           },
    //           {
    //             text: "TOTAL",
    //             width: 80,
    //             align: 'center',
    //             renderer: function(value, metaData, record) {
    //               let weight = record.get('question')?.weight;
    //               let point = record.get('evaluator2_point');
    //               return weight ? ((point * weight) / 100).toFixed(2) : '0';
    //             }
    //           }
    //         ]
    //       }
    //     ].filter(Boolean),
    //   });
    // };

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
        title: 'Summary Performance Appraisal',
        height: 'auto',
        maxHeight: 400,
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
            }
          },
          itemdblclick: function(obj, rec) {
            if (!rec.get('deleted_at')) {
              me.selected = rec;
              //   Ext.Ajax.request({
              //     url: '{{ route('appraisal.employee.question.data') }}',
              //     success: function(response) {
              //       let jsonResponse = Ext.decode(response.responseText);
              //       let data = jsonResponse.data.filter(function(item) {
              //         return item.appraisal_employ_id == rec.data.appraisal_employ_id;
              //       });

              //       let tech = data.filter(item => item.question.category_id == 1);
              //       let behavior = data.filter(item => item.question.category_id == 2);
              //       let leadership = data.filter(item => item.question.category_id == 3);

              //       me.techDetailPanel.show();
              //       me.behaviorDetailPanel.show();
              //       me.leadershipDetailPanel.show();

              //       me.techDetailPanel.getStore().loadData(tech);
              //       me.behaviorDetailPanel.getStore().loadData(behavior);
              //       me.leadershipDetailPanel.getStore().loadData(leadership);
              //     },
              //     failure: function(response) {
              //       let jsonResponse = Ext.decode(response.responseText);

              //       if (jsonResponse.errors) {
              //         let errorMessage = '';
              //         Ext.Object.each(jsonResponse.errors, function(field, errors) {
              //           errorMessage += errors.join(', ') + "\n";
              //         });

              //         Ext.Msg.alert('Error', errorMessage);
              //       } else {
              //         Ext.Msg.alert('Error', 'Failed to load data.');
              //       }
              //     }
              //   });
            }
          }
        }
      });

      //   me.techDetailPanel = me.createDetailPanel("Technical Ability & Work Result", "KPI", true);
      //   me.behaviorDetailPanel = me.createDetailPanel("Behavioral & Work Processes", "DIMENSION", true);
      //   me.leadershipDetailPanel = me.createDetailPanel("Leadership", "DIMENSION", false);

      // Create container for all panels
      //   me.mainContainer = Ext.create('Ext.container.Container', {
      //     layout: {
      //       type: 'vbox',
      //       align: 'stretch'
      //     },
      //     width: '100%',
      //     height: '100%',
      //     autoScroll: true,
      //     items: [
      //       me.grids,
      //       {
      //         xtype: 'container',
      //         style: {
      //           marginTop: '20px'
      //         },
      //         items: [me.techDetailPanel]
      //       },
      //       {
      //         xtype: 'container',
      //         style: {
      //           marginTop: '20px'
      //         },
      //         items: [me.behaviorDetailPanel]
      //       },
      //       {
      //         xtype: 'container',
      //         style: {
      //           marginTop: '20px'
      //         },
      //         items: [me.leadershipDetailPanel]
      //       }
      //     ]
      //   });

      me.window = Ext.create('Ext.window.Window', {
        layout: 'fit',
        modal: true,
        maximized: true,
        closeAction: 'hide',
        autoScroll: true,
        items: [me.grids]
      });

    };

    me.detail = function() {
      var rec = grids.getRec(true);
      me.data = rec;
      me.window.setTitle('');
      me.window.show();
      me.store.getProxy().setExtraParam('employee', rec.id);
      me.store.load();

      //   me.techDetailPanel.hide();
      //   me.behaviorDetailPanel.hide();
      //   me.leadershipDetailPanel.hide();
    };
  };
</script>
