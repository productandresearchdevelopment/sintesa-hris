@require('form.template')
@require('form.tech')
@require('form.behavior')
@require('form.leadership')

<script>
  var Forms = function() {
    let me = Ext.utils.windowForms(this);
    me.data = null;

    me.init = function() {
      me.template = new FormTemplate();
      me.tech = new FormTech();
      me.behavior = new FormBehavior();
      me.leadership = new FormLeadership();

      me.tabs = Ext.create('Ext.tab.Panel', {
        flex: 1,
        scrollable: true,
        layout: 'fit',
        id: 'tabs-appraisal',
        activeTab: 0,
        items: [{
            title: 'Technical Ability & Work Result',
            layout: 'fit',
            items: me.tech.getGrid()
          },
          {
            title: 'Behavior & Work Processes',
            layout: 'fit',
            items: me.behavior.getGrid()
          },
          {
            title: 'Leadership',
            layout: 'fit',
            items: me.leadership.getGrid()
          }
        ]
      });

      me.form = Ext.widget('form', {
        bodyPadding: 15,
        width: '100%',
        height: '100%',
        border: false,
        layout: {
          type: 'hbox',
          align: 'stretch'
        },
        scrollable: true,
        fieldDefaults: {
          labelAlign: 'top',
        },
        items: [{
            xtype: 'container',
            layout: {
              type: 'vbox',
              align: 'stretch'
            },
            flex: 4,
            margin: '0 20 0 0',
            items: me.template.getFields()
          },
          {
            xtype: 'container',
            layout: {
              type: 'vbox',
              align: 'stretch'
            },
            flex: 6,
            items: [me.tabs]
          }
        ],
        buttons: [
          @if (in_array(strtolower(optional($user->role)->name), ['hrga', 'developer', 'superadmin']))
            {
              text: 'Save',
              iconCls: 'icon-save-bright',
              handler: me.save
            }, {
              text: 'Cancel',
              cls: 'btn-red',
              iconCls: 'icon-close',
              handler: function() {
                if (me.window) {
                  me.window.hide();
                }
              }
            }
          @endif
        ]
      });

      me.window = Ext.create('Ext.window.Window', {
        layout: 'fit',
        modal: true,
        maximized: true,
        closeAction: 'hide',
        items: [me.form],
      });
    };

    me.collectGridData = function() {
      function getCleanGridItems(formModule) {
        var grid = formModule.getGrid();
        if (!grid || !grid[0] || !grid[0].store) return [];
        var records = grid[0].store.getRange();
        var uniqueItems = [];
        var seen = new Set();

        records.forEach(function(rec) {
          var data = rec.data || {};
          if (data.remove_flag) return;

          var key = (data.group_kpi || '') + '|' + (data.question || '') + '|' + (data.formula_description ||
            '') + '|' + (data.weight || '');
          if (!seen.has(key)) {
            seen.add(key);
            var itemCopy = Ext.apply({}, data);
            delete itemCopy.remove_flag;
            uniqueItems.push(itemCopy);
          }
        });

        return uniqueItems;
      }

      return {
        tech: getCleanGridItems(me.tech),
        behavior: getCleanGridItems(me.behavior),
        leadership: getCleanGridItems(me.leadership)
      };
    };

    me.clearStores = function() {
      if (me.tech && me.tech.getGrid && me.tech.getGrid()[0] && me.tech.getGrid()[0].store) {
        me.tech.getGrid()[0].store.removeAll();
      }
      if (me.behavior && me.behavior.getGrid && me.behavior.getGrid()[0] && me.behavior.getGrid()[0].store) {
        me.behavior.getGrid()[0].store.removeAll();
      }
      if (me.leadership && me.leadership.getGrid && me.leadership.getGrid()[0] && me.leadership.getGrid()[0].store) {
        me.leadership.getGrid()[0].store.removeAll();
      }
    };

    me.create = function() {
      me.window.show();
      me.reset();
      me.clearStores();
      me.data = null;
      me.window.setTitle('Create Appraisal Template');

      var tabsAppraisal = Ext.getCmp('tabs-appraisal');
      tabsAppraisal.setActiveTab(0);

      me.tech.groupKpiStore.load();
      me.behavior.groupKpiStore.load();
      me.leadership.groupKpiStore.load();

      me.form.getForm().findField('_method').setValue('POST');
      me.form.url = '{{ route('appraisal.question.template.create') }}';
    };

    me.edit = function() {
      me.data = null;
      @if (in_array(strtolower(optional($user->role)->name), ['hrga', 'developer', 'superadmin']))
        me.window.setTitle('Edit Appraisal Template');
      @else
        me.window.setTitle('View Appraisal Template');
      @endif
      var rec = grids.getRec(true);
      if (rec) {
        me.window.show();
        me.reset();
        me.clearStores();
        me.form.getEl().mask('Loading');
        Ext.Ajax.request({
          method: 'GET',
          url: '{{ route('appraisal.question.template.data') }}/' + rec.id,
          success: function(response) {
            me.data = Ext.decode(response.responseText);
            me.render(me.data, 'edit');
            me.form.getEl().unmask();
          },
          failure: function() {
            Ext.Msg.alert('Error', 'Internal Server Error!');
            me.form.getEl().unmask();
            me.window.hide();
          }
        });
      } else {
        Ext.Msg.alert('Warning', 'Please select a record to edit.');
      }
    };

    me.duplicate = function() {
      me.data = null;
      me.window.setTitle('Duplicate Appraisal');
      var rec = grids.getRec(true);
      if (rec) {
        me.window.show();
        me.reset();
        me.clearStores();
        me.form.getEl().mask('Loading');
        Ext.Ajax.request({
          method: 'GET',
          url: '{{ route('appraisal.question.template.data') }}/' + rec.id,
          success: function(response) {
            me.data = Ext.decode(response.responseText);
            me.render(me.data, 'duplicate');
            me.form.getEl().unmask();
          },
          failure: function() {
            Ext.Msg.alert('Error', 'Internal Server Error!');
            me.form.getEl().unmask();
            me.window.hide();
          }
        });
      } else {
        Ext.Msg.alert('Warning', 'Please select a record to duplicate.');
      }
    };

    me.render = function(rec, action) {
      var tabsAppraisal = Ext.getCmp('tabs-appraisal');
      tabsAppraisal.setActiveTab(0);

      me.form.url = action === 'duplicate' ?
        '{{ route('appraisal.question.template.create') }}' :
        '{{ route('appraisal.question.template.update') }}';

      var method = action === 'duplicate' ? 'POST' : 'PUT';
      me.form.getForm().setValues({
        _method: method,
        id: action === 'duplicate' ? null : rec.id,
        duplicate_id: action === 'duplicate' ? rec.id : null,
        title: rec.title,
        period_year: rec.period_year,
        division_id: rec.division_id,
        period_smt: rec.period_smt,
        is_locked: rec.is_locked,
        is_archived: rec.is_archived,
        description: rec.description
      });

      function getUniqueQuestions(questions, categoryId) {
        if (!questions || !Array.isArray(questions)) return [];
        var filtered = questions.filter(item => item.category_id === categoryId);
        var seen = new Set();
        var unique = [];
        filtered.forEach(item => {
          var key = (item.group_kpi || '') + '|' + (item.question || '') + '|' + (item.formula_description ||
            '') + '|' + (item.weight || '');
          if (!seen.has(key)) {
            seen.add(key);
            var clone = Ext.apply({}, item);
            if (action === 'duplicate') {
              delete clone.id;
              delete clone.template_id;
            }
            unique.push(clone);
          }
        });
        return unique;
      }

      var techStore = me.tech.getGrid()[0].store;
      techStore.removeAll();
      var techData = getUniqueQuestions(rec.appraisal_questions, 1);
      techStore.loadData(techData, false);
      me.tech.sortStore();
      me.tech.groupKpiStore.load();

      var behaviorStore = me.behavior.getGrid()[0].store;
      behaviorStore.removeAll();
      var behaviorData = getUniqueQuestions(rec.appraisal_questions, 2);
      behaviorStore.loadData(behaviorData, false);
      me.behavior.sortStore();
      me.behavior.groupKpiStore.load();

      var leadershipStore = me.leadership.getGrid()[0].store;
      leadershipStore.removeAll();
      var leadershipData = getUniqueQuestions(rec.appraisal_questions, 3);
      leadershipStore.loadData(leadershipData, false);
      me.leadership.sortStore();
      me.leadership.groupKpiStore.load();
    };

    me.save = function() {
      if (me.form.getForm().isValid()) {
        var form = me.form.getForm();
        var values = form.getValues();
        let gridData = me.collectGridData();

        var formData = new FormData();
        for (let key in values) {
          if (values.hasOwnProperty(key)) {
            formData.append(key, values[key]);
          }
        }

        if (!values.hasOwnProperty('is_locked')) {
          formData.append('is_locked', '0');
        }
        if (!values.hasOwnProperty('is_archived')) {
          formData.append('is_archived', '0');
        }

        for (let gridKey in gridData) {
          if (gridData.hasOwnProperty(gridKey)) {
            let gridItems = gridData[gridKey];

            for (let i = 0; i < gridItems.length; i++) {
              let item = gridItems[i];

              for (let key in item) {
                if (
                  item.hasOwnProperty(key) &&
                  item[key] !== null &&
                  item[key] !== "" &&
                  item[key] !== "null" &&
                  item[key] !== "undefined" &&
                  item[key] !== undefined
                ) {
                  if (key === 'weight' && typeof item[key] === 'string' && item[key].includes(',')) {
                    item[key] = item[key].replace(',', '.');
                  }
                  formData.append(`${gridKey}[${i}][${key}]`, item[key]);
                }
              }
            }
          }
        }

        Ext.Ajax.request({
          url: me.form.url,
          rawData: formData,
          headers: {
            'Content-Type': null
          },
          success: function(response) {
            grids.storeLoad();
            Ext.Msg.alert('Success', 'Data has been saved!');
            me.window.hide();
          },
          failure: function(response) {
            let jsonResponse = {};
            try {
              jsonResponse = JSON.parse(response.responseText);
            } catch (e) {
              jsonResponse = {};
            }

            if (jsonResponse.errors) {
              let errorMessage = '';

              if (typeof jsonResponse.errors === 'object') {
                Object.keys(jsonResponse.errors).forEach(field => {
                  let err = jsonResponse.errors[field];
                  if (Array.isArray(err)) {
                    errorMessage += err.join(', ') + "\n";
                  } else {
                    errorMessage += String(err) + "\n";
                  }
                });
              } else {
                errorMessage = String(jsonResponse.errors);
              }

              Ext.Msg.alert('Error', errorMessage);
            } else if (jsonResponse.message) {
              Ext.Msg.alert('Error', jsonResponse.message);
            } else {
              Ext.Msg.alert('Error', 'Failed to save data.');
            }

          }

        });
      } else {
        Ext.Msg.alert('Error', 'Please fill all required fields!');
      }
    };
  };
</script>
