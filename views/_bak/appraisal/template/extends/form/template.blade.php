<script>
  var FormTemplate = function() {
    let me = Ext.utils.windowForms(this);
    me.data = null;

    var isReadOnly =
      {{ in_array(strtolower(optional($user->role)->name), ['hrga', 'developer', 'superadmin']) ? 'false' : 'true' }};

    me.divisionData = Ext.create('Ext.data.Store', {
      fields: ['id', 'name'],
      proxy: {
        type: 'ajax',
        url: '{{ route('division.data') }}',
        reader: {
          root: 'data',
          totalProperty: 'count'
        }
      },
      autoLoad: true
    });

    me.periodData = Ext.create('Ext.data.Store', {
      fields: ['id', 'period', 'smester'],
      proxy: {
        type: 'ajax',
        url: '{{ route('appraisal.period.data') }}',
        reader: {
          root: 'data',
          totalProperty: 'count'
        }
      },
      autoLoad: true,
      listeners: {
        load: function(store) {
          let uniqueRecords = [];
          let seenPeriods = new Set();

          store.each(function(record) {
            let period = record.get('period');
            if (!seenPeriods.has(period)) {
              seenPeriods.add(period);
              uniqueRecords.push(record);
            }
          });

          store.loadData(uniqueRecords, false);
        }
      }
    });

    me.getFields = function() {
      return [{
          xtype: 'hidden',
          name: 'id'
        },
        {
          xtype: 'hidden',
          name: 'duplicate_id'
        },
        {
          xtype: 'hidden',
          name: '_token',
          value: '{{ csrf_token() }}'
        },
        {
          xtype: 'hidden',
          name: '_method',
          value: 'PUT'
        },

        {
          xtype: 'textfield',
          fieldLabel: 'Title',
          afterLabelTextTpl: '<span style="color:red;">*</span>',
          name: 'title',
          allowBlank: false,
          readOnly: isReadOnly
        },
        {
          xtype: 'combo',
          afterLabelTextTpl: '<span style="color:red;">*</span>',
          name: 'division_id',
          fieldLabel: 'Division',
          store: me.divisionData,
          displayField: 'name',
          valueField: 'id',
          queryMode: 'local',
          typeAhead: true,
          allowBlank: false,
          readOnly: isReadOnly,
          listeners: {
            select: function(combo, record) {
              this.setValue(record);
            }
          }
        },
        {
          xtype: 'combo',
          fieldLabel: 'Period Year',
          afterLabelTextTpl: '<span style="color:red;">*</span>',
          name: 'period_year',
          store: me.periodData,
          displayField: 'period',
          valueField: 'period',
          queryMode: 'local',
          forceSelection: true,
          editable: false,
          allowBlank: false,
          readOnly: isReadOnly
        },
        {
          xtype: 'combo',
          fieldLabel: 'Period SMT',
          afterLabelTextTpl: '<span style="color:red;">*</span>',
          name: 'period_smt',
          store: [
            [1, '1'],
            [2, '2']
          ],
          queryMode: 'local',
          forceSelection: true,
          editable: false,
          allowBlank: false,
          readOnly: isReadOnly
        },
        {
          xtype: 'container',
          layout: {
            type: 'hbox',
            align: 'middle'
          },
          margin: '0 0 10 0',
          items: [{
              xtype: 'checkbox',
              fieldLabel: 'Is Locked',
              name: 'is_locked',
              inputValue: '1',
              uncheckedValue: '0',
              flex: 1,
              margin: '0 10 0 0',
              readOnly: isReadOnly
            },
            {
              xtype: 'checkbox',
              fieldLabel: 'Is Archived',
              name: 'is_archived',
              inputValue: '1',
              uncheckedValue: '0',
              flex: 1,
              readOnly: isReadOnly
            }
          ]
        },
        {
          xtype: 'textarea',
          fieldLabel: 'Description',
          name: 'description',
          flex: 1,
          readOnly: isReadOnly
        }
      ];
    };
  };
</script>
