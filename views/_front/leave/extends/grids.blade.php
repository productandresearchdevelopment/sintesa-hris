<script>
  var Grids = function() {
    let me = Ext.utils.grids(this);

    me.selected = null;
    me.currentView = 'ongoing';
    me.currentType = 'all';
    me.currentStatus = 'all';

    me.init = function() {
      me.store = me.httpStore('{{ route('leave.data') }}', [{
          name: 'id',
          type: 'int'
        },
        {
          name: 'employ_id',
          type: 'int'
        },
        {
          name: 'type_id',
          type: 'int'
        },
        {
          name: 'type',
          type: 'auto'
        },
        {
          name: 'employee',
          type: 'auto'
        },
        {
          name: 'start_date',
          type: 'string'
        },
        {
          name: 'end_date',
          type: 'string'
        },
        {
          name: 'duration',
          type: 'float'
        },
        {
          name: 'description',
          type: 'string'
        },
        {
          name: 'leave_saldo',
          type: 'float'
        },
        {
          name: 'file_id',
          type: 'auto'
        },
        {
          name: 'file',
          type: 'auto'
        },
        {
          name: 'profile_picture',
          type: 'string'
        },
        {
          name: 'cancel_at',
          type: 'string'
        },
        {
          name: 'cancel_note',
          type: 'string'
        },
        {
          name: 'approved1_by',
          type: 'auto'
        },
        {
          name: 'approved1_status',
          type: 'auto'
        },
        {
          name: 'approved1_note',
          type: 'string'
        },
        {
          name: 'approved1_date',
          type: 'string'
        },
        {
          name: 'approved2_by',
          type: 'auto'
        },
        {
          name: 'approved2_status',
          type: 'auto'
        },
        {
          name: 'approved2_note',
          type: 'string'
        },
        {
          name: 'approved2_date',
          type: 'string'
        },
        {
          name: 'allowed_by',
          type: 'auto'
        },
        {
          name: 'allowed_status',
          type: 'auto'
        },
        {
          name: 'allowed_note',
          type: 'string'
        },
        {
          name: 'allowed_date',
          type: 'string'
        },
        {
          name: 'single_evaluator',
          type: 'boolean'
        },
        {
          name: 'can_evaluate',
          type: 'boolean'
        },
        {
          name: 'can_approve_1',
          type: 'boolean'
        },
        {
          name: 'can_approve_2',
          type: 'boolean'
        },
        {
          name: 'can_allow',
          type: 'boolean'
        },
        {
          name: 'can_cancel',
          type: 'boolean'
        },
        {
          name: 'is_user_leave',
          type: 'boolean'
        },
        {
          name: 'is_super',
          type: 'boolean'
        },
        {
          name: 'created_at',
          type: 'date'
        },
        {
          name: 'updated_at',
          type: 'date'
        }
      ]);

      me.store.proxy.extraParams = {
        view: 'ongoing',
        leave_type: 'all',
        status: 'all'
      };

      me.menus = Ext.create('Ext.menu.Menu', {
        items: [
          @if ($user->hasRoute('leave.push'))
            {
              text: 'Add Leave',
              iconCls: 'icon-add',
              handler: function() {
                forms.create();
              }
            }, {
              text: 'Edit',
              iconCls: 'icon-edit',
              id: 'menu-leave-edit',
              handler: function() {
                var rec = me.getRec(true);
                if (rec) {
                  var data = (rec && rec.data) ? rec.data : rec;
                  forms.edit(data);
                } else {
                  Ext.msg.warning('Please select a leave record!');
                }
              }
            },
          @endif {
            text: 'Detail',
            iconCls: 'icon-show',
            handler: function() {
              var rec = me.getRec(true);
              if (rec) {
                var data = (rec && rec.data) ? rec.data : rec;
                details.show(data);
              } else {
                Ext.msg.warning('Please select a leave record!');
              }
            }
          },
          '-',
          @if ($user->hasRoute('leave.approve'))
            {
              text: 'Approve',
              iconCls: 'icon-yes',
              id: 'menu-leave-approve',
              handler: function() {
                var rec = me.getRec(true);
                if (rec) {
                  var data = (rec && rec.data) ? rec.data : rec;
                  approval.approve(data);
                } else {
                  Ext.msg.warning('Please select a leave record!');
                }
              }
            },
          @endif
          @if ($user->hasRoute('leave.reject'))
            {
              text: 'Reject',
              iconCls: 'icon-no',
              id: 'menu-leave-reject',
              handler: function() {
                var rec = me.getRec(true);
                if (rec) {
                  var data = (rec && rec.data) ? rec.data : rec;
                  approval.reject(data);
                } else {
                  Ext.msg.warning('Please select a leave record!');
                }
              }
            },
          @endif
          @if ($user->hasRoute('leave.cancel'))
            {
              text: 'Cancel Leave',
              iconCls: 'icon-close',
              id: 'menu-leave-cancel',
              handler: function() {
                var rec = me.getRec(true);
                if (rec) {
                  var data = (rec && rec.data) ? rec.data : rec;
                  approval.cancel(data);
                } else {
                  Ext.msg.warning('Please select a leave record!');
                }
              }
            },
          @endif
          @if ($user->hasRoute('leave.export.excel'))
            '-',
            {
              text: 'Export Excel',
              iconCls: 'icon-excel',
              handler: function() {
                me.exportExcel();
              }
            }
          @endif
        ]
      });

      // Leave types combo data
      var rawTypes = @json($types);
      var typeData = [{
        id: 'all',
        name: 'All Leave Types'
      }];
      if (rawTypes && rawTypes.length) {
        rawTypes.forEach(function(t) {
          typeData.push({
            id: t.id.toString(),
            name: t.name
          });
        });
      }

      var typeStore = Ext.create('Ext.data.Store', {
        fields: ['id', 'name'],
        data: typeData
      });

      var statusStore = Ext.create('Ext.data.Store', {
        fields: ['id', 'name'],
        data: [{
            id: 'all',
            name: 'All Status'
          },
          {
            id: 'pending',
            name: 'Pending'
          },
          {
            id: 'checked',
            name: 'Checked'
          },
          {
            id: 'approved',
            name: 'Approved'
          },
          {
            id: 'rejected',
            name: 'Rejected'
          },
          {
            id: 'cancelled',
            name: 'Cancelled'
          }
        ]
      });

      var viewStore = Ext.create('Ext.data.Store', {
        fields: ['id', 'name'],
        data: [{
            id: 'ongoing',
            name: 'Ongoing'
          },
          {
            id: 'archived',
            name: 'Archived'
          }
        ]
      });

      me.grid = Ext.create('Ext.grid.Panel', {
        region: 'center',
        store: me.store,
        selType: 'checkboxmodel',
        border: true,
        cls: 'large-grid',
        tbar: [{
            text: 'Menu',
            iconCls: 'icon-menu',
            menu: me.menus
          },
          @if ($user->hasRoute('leave.export.excel'))
            {
              text: 'Export Data',
              iconCls: 'icon-save-dark',
              menu: [{
                text: 'Export Excel',
                iconCls: 'icon-excel',
                handler: function() {
                  me.exportExcel();
                }
              }]
            },
          @endif
          '->',
          {
            xtype: 'searchfield',
            flex: 1,
            maxWidth: 300,
            minWidth: 180,
            store: me.store,
            param: 'search',
            emptyText: 'Search...'
          }
        ],
        columns: [{
            text: "EMPLOYEE",
            dataIndex: 'employee',
            minWidth: 220,
            flex: 1,
            renderer: function(val, meta, rec) {
              let emp = rec.data.employee || {};
              let photo = rec.data.profile_picture || '{{ asset('images/nouser copy.png') }}';
              let name = emp.fullname || emp.nickname || 'Unknown Employee';
              let org = (emp.organization && emp.organization.name) ? emp.organization.name : (emp.nik ||
                '');
              return `
                <div style="display: flex; align-items: center; gap: 10px; padding: 2px 0;">
                  <img src="${photo}" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover; border: 1px solid #e2e8f0;" onerror="this.src='{{ asset('images/nouser copy.png') }}'"/>
                  <div style="line-height: 1.25;">
                    <div style="font-weight: 700; color: #1e293b;">${name}</div>
                    <div style="font-size: 11px; color: #64748b;">${org}</div>
                  </div>
                </div>
              `;
            }
          },
          {
            text: "LEAVE TYPE",
            dataIndex: 'type',
            width: 170,
            align: 'center',
            renderer: function(val, meta, rec) {
              let t = rec.data.type || {};
              if (!t || !t.name) return '-';
              let name = Ext.util.Format.htmlEncode(t.name);
              let color = t.color ? (t.color.startsWith('#') ? t.color : ('#' + t.color)) : '#0073E6';
              return `<span class="badge" style="background-color: ${color}15; color: ${color}; border: 1px solid ${color}40; font-weight: 600; font-size: 11px; padding: 4px 10px; border-radius: 6px; display: inline-block;">${name}</span>`;
            }
          },
          {
            text: "START DATE",
            dataIndex: 'start_date',
            width: 120,
            align: 'center',
            renderer: function(val) {
              return val ? Ext.Date.format(new Date(val), 'Y-m-d') : '-';
            }
          },
          {
            text: "END DATE",
            dataIndex: 'end_date',
            width: 120,
            align: 'center',
            renderer: function(val) {
              return val ? Ext.Date.format(new Date(val), 'Y-m-d') : '-';
            }
          },
          {
            text: "DURATION",
            dataIndex: 'duration',
            width: 110,
            align: 'center',
            renderer: function(val) {
              return `<span class="badge bg-light text-dark font-weight-bold" style="font-size: 11px; border: 1px solid #cbd5e1; padding: 4px 8px;">${val || 0} Day(s)</span>`;
            }
          },
          {
            text: "STATUS",
            dataIndex: 'approved2_status',
            width: 120,
            align: 'center',
            renderer: function(val, meta, rec) {
              let r = rec.data;
              var isSingle = r.single_evaluator || !r.employee?.organization?.authorized2;
              var isApproved = isSingle ? (r.approved1_status === 1) : (r.approved2_status === 1);

              if (r.cancel_at) {
                return '<span class="badge bg-secondary" style="font-size: 11px; padding: 4px 8px;">Cancelled</span>';
              }
              if (r.approved1_status === 0 || r.approved2_status === 0) {
                return '<span class="badge bg-danger" style="font-size: 11px; padding: 4px 8px;">Rejected</span>';
              }
              if (isApproved) {
                return '<span class="badge bg-success" style="font-size: 11px; padding: 4px 8px;">Approved</span>';
              }
              if (r.approved1_status === 1) {
                return '<span class="badge bg-primary" style="font-size: 11px; padding: 4px 8px;">Checked</span>';
              }
              return '<span class="badge bg-warning text-dark" style="font-size: 11px; padding: 4px 8px;">Pending</span>';
            }
          }
        ],
        bbar: me.bottomBar([{
            xtype: 'combobox',
            width: 160,
            store: typeStore,
            displayField: 'name',
            valueField: 'id',
            value: 'all',
            editable: false,
            emptyText: 'Leave Type',
            listeners: {
              change: function(combo, newVal) {
                me.currentType = newVal;
                me.filterStore();
              }
            }
          },
          {
            xtype: 'combobox',
            width: 130,
            store: statusStore,
            displayField: 'name',
            valueField: 'id',
            value: 'all',
            editable: false,
            emptyText: 'Status',
            listeners: {
              change: function(combo, newVal) {
                me.currentStatus = newVal;
                me.filterStore();
              }
            }
          },
          {
            xtype: 'combobox',
            width: 110,
            store: viewStore,
            displayField: 'name',
            valueField: 'id',
            value: 'ongoing',
            editable: false,
            emptyText: 'View Mode',
            listeners: {
              change: function(combo, newVal) {
                me.currentView = newVal;
                me.filterStore();
              }
            }
          }
        ]),
        viewConfig: {
          stripeRows: true,
          listeners: {
            itemcontextmenu: function(obj, rec, node, index, e) {
              e.stopEvent();
              me.selected = rec;
              me.updateMenuVisibility(rec.data || rec);
              me.menus.showAt(e.getXY());
            },
            itemclick: function(obj, rec) {
              me.selected = rec;
              me.updateMenuVisibility(rec.data || rec);
            },
            itemdblclick: function(obj, rec) {
              me.selected = rec;
              details.show(rec.data || rec);
            }
          }
        }
      });
    };

    me.filterStore = function() {
      me.store.proxy.extraParams = {
        view: me.currentView || 'ongoing',
        leave_type: me.currentType || 'all',
        status: me.currentStatus || 'all'
      };
      me.store.load();
    };

    me.updateMenuVisibility = function(data) {
      if (!data) return;
      var editBtn = Ext.getCmp('menu-leave-edit');
      var approveBtn = Ext.getCmp('menu-leave-approve');
      var rejectBtn = Ext.getCmp('menu-leave-reject');
      var cancelBtn = Ext.getCmp('menu-leave-cancel');

      var isSingle = data.single_evaluator || !data.employee?.organization?.authorized2;
      var isApproved = isSingle ? (data.approved1_status === 1) : (data.approved2_status === 1);
      var isRejected = data.approved1_status === 0 || data.approved2_status === 0;
      var isCancelled = !!data.cancel_at;
      var isFinished = isApproved || isRejected || isCancelled;

      if (editBtn) {
        var canEdit = data.is_user_leave && !isFinished && data.approved1_status === null;
        editBtn.setDisabled(!canEdit);
      }

      if (cancelBtn) {
        var canCancel = data.can_cancel && !isFinished;
        cancelBtn.setDisabled(!canCancel);
      }

      if (approveBtn) {
        var canApprove = false;
        if (!isFinished) {
          if (isSingle) {
            canApprove = data.can_evaluate && data.approved1_status === null;
          } else {
            canApprove = (data.can_approve_1 && data.approved1_status === null) ||
              (data.can_approve_2 && data.approved1_status === 1 && data.approved2_status === null);
          }
        }
        approveBtn.setDisabled(!canApprove);
      }

      if (rejectBtn) {
        var canReject = false;
        if (!isFinished) {
          if (isSingle) {
            canReject = data.can_evaluate && data.approved1_status === null;
          } else {
            canReject = (data.can_approve_1 && data.approved1_status === null) ||
              (data.can_approve_2 && data.approved1_status === 1 && data.approved2_status === null);
          }
        }
        rejectBtn.setDisabled(!canReject);
      }
    };

    me.exportExcel = function() {
      let filters = me.store.proxy.extraParams || {};
      let query = '';
      if (me.store.filters && me.store.filters.items && me.store.filters.items.length) {
        query = me.store.filters.items[0].value;
      }
      filters.search = query;

      let params = [];
      for (var key in filters) {
        var value = filters[key];
        if (value !== undefined && value !== null) {
          params.push(key + '=' + encodeURIComponent(value));
        }
      }

      window.location = '{{ route('leave.export.excel') }}?' + params.join('&');
    };
  };
</script>
