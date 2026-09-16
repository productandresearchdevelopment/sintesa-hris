<script>
  var Approval = function() {
    let me = this;

    me.currentData = null;
    me.currentAction = null;

    me.init = function() {
      me.noteField = Ext.create('Ext.form.field.TextArea', {
        name: 'notes',
        fieldLabel: 'Notes / Reason',
        labelAlign: 'top',
        allowBlank: false,
        height: 100,
        width: '100%',
        emptyText: 'Enter your notes here...'
      });

      me.form = Ext.widget('form', {
        bodyPadding: 15,
        border: false,
        items: [
          {
            xtype: 'component',
            id: 'approval-dialog-info',
            html: '',
            margin: '0 0 10 0'
          },
          me.noteField
        ],
        buttons: [
          {
            text: 'Submit',
            id: 'btn-approval-submit',
            iconCls: 'icon-save-bright',
            handler: function() {
              me.submitAction();
            }
          },
          {
            text: 'Cancel',
            cls: 'btn-red',
            iconCls: 'icon-close',
            handler: function() {
              me.window.hide();
            }
          }
        ]
      });

      me.window = Ext.create('Ext.window.Window', {
        title: 'Action',
        width: 440,
        modal: true,
        closeAction: 'hide',
        layout: 'fit',
        items: [me.form]
      });
    };

    me.approve = function(data) {
      if (!data) return;
      me.currentData = data;
      me.currentAction = 'approve';
      me.noteField.setValue('Approved');
      me.noteField.allowBlank = false;

      var infoCmp = Ext.getCmp('approval-dialog-info');
      if (infoCmp) {
        var empName = data.employee ? (data.employee.fullname || data.employee.nickname) : 'Employee';
        var typeName = data.type ? data.type.name : 'Leave';
        infoCmp.update(`<div class="alert alert-info py-2 px-3 mb-2" style="font-size: 13px;">
          Approve leave request for <strong>${empName}</strong> (${typeName}, ${data.duration || 1} days)?
        </div>`);
      }

      me.window.setTitle('Approve Leave Request');
      var btnSubmit = Ext.getCmp('btn-approval-submit');
      if (btnSubmit) {
        btnSubmit.setText('Approve');
        btnSubmit.setIconCls('icon-yes');
      }
      me.window.show();
    };

    me.reject = function(data) {
      if (!data) return;
      me.currentData = data;
      me.currentAction = 'reject';
      me.noteField.setValue('');
      me.noteField.allowBlank = false;

      var infoCmp = Ext.getCmp('approval-dialog-info');
      if (infoCmp) {
        var empName = data.employee ? (data.employee.fullname || data.employee.nickname) : 'Employee';
        var typeName = data.type ? data.type.name : 'Leave';
        infoCmp.update(`<div class="alert alert-danger py-2 px-3 mb-2" style="font-size: 13px;">
          Reject leave request for <strong>${empName}</strong> (${typeName}, ${data.duration || 1} days). Please provide a rejection reason.
        </div>`);
      }

      me.window.setTitle('Reject Leave Request');
      var btnSubmit = Ext.getCmp('btn-approval-submit');
      if (btnSubmit) {
        btnSubmit.setText('Reject');
        btnSubmit.setIconCls('icon-no');
      }
      me.window.show();
    };

    me.cancel = function(data) {
      if (!data) return;
      me.currentData = data;
      me.currentAction = 'cancel';
      me.noteField.setValue('');
      me.noteField.allowBlank = false;

      var infoCmp = Ext.getCmp('approval-dialog-info');
      if (infoCmp) {
        infoCmp.update(`<div class="alert alert-warning py-2 px-3 mb-2" style="font-size: 13px;">
          Are you sure you want to cancel this leave request? Please provide a cancellation reason.
        </div>`);
      }

      me.window.setTitle('Cancel Leave Request');
      var btnSubmit = Ext.getCmp('btn-approval-submit');
      if (btnSubmit) {
        btnSubmit.setText('Cancel Request');
        btnSubmit.setIconCls('icon-close');
      }
      me.window.show();
    };

    me.submitAction = function() {
      var notes = me.noteField.getValue();
      if (!notes || !notes.trim()) {
        Ext.Msg.alert('Warning', 'Notes / Reason field is required.');
        return;
      }

      var url = '';
      if (me.currentAction === 'approve') {
        url = '{{ route('leave.approve', ':id') }}'.replace(':id', me.currentData.id);
      } else if (me.currentAction === 'reject') {
        url = '{{ route('leave.reject', ':id') }}'.replace(':id', me.currentData.id);
      } else if (me.currentAction === 'cancel') {
        url = '{{ route('leave.cancel', ':id') }}'.replace(':id', me.currentData.id);
      }

      me.form.getEl().mask('Processing...');
      Ext.Ajax.request({
        url: url,
        method: 'POST',
        params: {
          '_token': '{{ csrf_token() }}',
          'notes': notes
        },
        success: function(response) {
          me.form.getEl().unmask();
          var res = Ext.decode(response.responseText);
          if (res && res.success) {
            grids.storeLoad();
            me.window.hide();
            Ext.example.msg('Success', res.message || 'Action executed successfully.');
          } else {
            Ext.Msg.alert('Error', (res && res.message) ? res.message : 'Action failed.');
          }
        },
        failure: function(response) {
          me.form.getEl().unmask();
          var message = 'Server error';
          try {
            var res = Ext.decode(response.responseText);
            if (res && res.message) message = res.message;
          } catch(e) {}
          Ext.Msg.alert('Error', message);
        }
      });
    };
  };
</script>
