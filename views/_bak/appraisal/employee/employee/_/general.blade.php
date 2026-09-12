<script>
  var FormGeneral = function() {
    let me = Ext.utils.windowForms(this);
    me.data = null;

    me.getYearStore = function() {
      let years = [],
        currentYear = new Date().getFullYear();
      for (let i = currentYear - 20; i <= currentYear + 20; i++) {
        years.push({
          year: i
        });
      }
      return Ext.create('Ext.data.Store', {
        fields: ['year'],
        data: years
      });
    };

    me.getFields = function() {
      return [{
          xtype: 'hidden',
          name: 'id'
        },
        {
          xtype: 'hidden',
          name: 'appraisal_period_organization_id'
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
          xtype: 'combo',
          fieldLabel: 'Period',
          afterLabelTextTpl: '<span style="color:red;">*</span>',
          name: 'period',
          store: me.getYearStore(),
          displayField: 'year',
          valueField: 'year',
          queryMode: 'local',
          forceSelection: true,
          editable: false,
          allowBlank: false,
        },
        {
          xtype: 'combo',
          fieldLabel: 'Semester',
          afterLabelTextTpl: '<span style="color:red;">*</span>',
          name: 'smester',
          store: [
            [1, '1'],
            [2, '2']
          ],
          queryMode: 'local',
          forceSelection: true,
          editable: false,
          allowBlank: false,
        },
        {
          xtype: 'datefield',
          fieldLabel: 'Start Date',
          afterLabelTextTpl: '<span style="color:red;">*</span>',
          name: 'start_date',
          format: 'Y-m-d',
          allowBlank: false,
        },
        {
          xtype: 'datefield',
          fieldLabel: 'End Date',
          afterLabelTextTpl: '<span style="color:red;">*</span>',
          name: 'end_date',
          format: 'Y-m-d',
          allowBlank: false,
        },
        {
          xtype: 'checkbox',
          fieldLabel: 'Is Closed',
          name: 'is_closed',
          inputValue: '1',
          uncheckedValue: '0'
        },
      ];
    };
  };
</script>
