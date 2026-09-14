<script>
  var Grids = function() {
    let me = Ext.utils.grids(this);

    me.init = function() {
      me.store = me.httpStore('{{ route('employee.data') }}', [{
          name: 'id',
          type: 'auto'
        },
        {
          name: 'org_id',
          type: 'int'
        },
        {
          name: 'organization',
          type: 'auto'
        },
        {
          name: 'division_id',
          type: 'int'
        },
        {
          name: 'division',
          type: 'auto'
        },
        {
          name: 'company_id',
          type: 'int'
        },
        {
          name: 'company',
          type: 'auto'
        },
        {
          name: 'placement_id',
          type: 'int'
        },
        {
          name: 'placement',
          type: 'auto'
        },
        {
          name: 'nik',
          type: 'string'
        },
        {
          name: 'nickname',
          type: 'string'
        },
        {
          name: 'fullname',
          type: 'string'
        },
        {
          name: 'birth_place',
          type: 'string'
        },
        {
          name: 'birth_date',
          type: 'date'
        },
        {
          name: 'phone',
          type: 'string'
        },
        {
          name: 'email',
          type: 'string'
        },
        {
          name: 'gender_id',
          type: 'int'
        },
        {
          name: 'gender',
          type: 'auto'
        },
        {
          name: 'marital_id',
          type: 'int'
        },
        {
          name: 'marital',
          type: 'auto'
        },
        {
          name: 'religion_id',
          type: 'int'
        },
        {
          name: 'religion',
          type: 'auto'
        },
        {
          name: 'join_date',
          type: 'date'
        },
        {
          name: 'leave_saldo',
          type: 'int'
        },
        {
          name: 'address',
          type: 'string'
        },
        {
          name: 'address_city_id',
          type: 'int'
        },
        {
          name: 'address_city',
          type: 'auto'
        },
        {
          name: 'address_province_id',
          type: 'int'
        },
        {
          name: 'address_province',
          type: 'auto'
        },
        {
          name: 'address_permanent',
          type: 'string'
        },
        {
          name: 'address_permanent_city_id',
          type: 'int'
        },
        {
          name: 'address_permanent_city',
          type: 'auto'
        },
        {
          name: 'address_permanent_province_id',
          type: 'int'
        },
        {
          name: 'address_permanent_province',
          type: 'auto'
        },
        {
          name: 'bank_id',
          type: 'int'
        },
        {
          name: 'bank',
          type: 'auto'
        },
        {
          name: 'bank_account',
          type: 'string'
        },
        {
          name: 'bank_alias',
          type: 'string'
        },
        {
          name: 'emergency_relation_id',
          type: 'int'
        },
        {
          name: 'emergency_relation',
          type: 'auto'
        },
        {
          name: 'emergency_contact_name',
          type: 'string'
        },
        {
          name: 'emergency_contact_phone',
          type: 'string'
        },
        {
          name: 'emergency_contact_address',
          type: 'string'
        },
        {
          name: 'photo_id',
          type: 'int'
        },
        {
          name: 'photo',
          type: 'auto'
        },
        {
          name: 'last_contract',
          type: 'auto'
        },
        {
          name: 'contracts',
          type: 'auto'
        },
        {
          name: 'citizens',
          type: 'auto'
        },
        {
          name: 'educations',
          type: 'auto'
        },
        {
          name: 'last_career',
          type: 'auto'
        },
        {
          name: 'careers',
          type: 'auto'
        },
        {
          name: 'families',
          type: 'auto'
        },
        {
          name: 'job_experiences',
          type: 'auto'
        },
        {
          name: 'trainings',
          type: 'auto'
        },
        {
          name: 'employee_requests',
          type: 'auto'
        },
        {
          name: 'deleted_at',
          type: 'date'
        }
      ]);

      me.companyData = Ext.create('Ext.data.Store', {
        fields: ['id', 'name'],
        proxy: {
          type: 'ajax',
          url: '{{ route('company.data') }}',
          reader: {
            root: 'data',
            totalProperty: 'count'
          }
        },
        autoLoad: true,
        listeners: {
          load: function(store) {
            store.insert(0, {
              id: 0,
              name: 'All Company'
            });
          }
        }
      });

      me.organizationData = Ext.create('Ext.data.TreeStore', {
        folderSort: false,
        root: {
          id: '0',
          text: 'PT Sintesa Talenta Asia',
          icon: '{{ asset('images/icons/home.png') }}',
          expanded: true
        },
        proxy: {
          type: 'ajax',
          url: '{{ route('organization.data') }}',
          extraParams: {
            company_id: null
          }
        },
      });

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
        autoLoad: true,
        listeners: {
          load: function(store) {
            store.insert(0, {
              id: 0,
              name: 'All Division'
            });
          }
        }
      });

      me.placementData = Ext.create('Ext.data.Store', {
        fields: ['id', 'name'],
        proxy: {
          type: 'ajax',
          url: '{{ route('placement.data') }}',
          reader: {
            root: 'data',
            totalProperty: 'count'
          }
        },
        autoLoad: true,
        listeners: {
          load: function(store) {
            store.insert(0, {
              id: 0,
              name: 'All Placement'
            });
          }
        }
      });

      //   me.positionData = Ext.create('Ext.data.Store', {
      //     fields: ['id', 'name'],
      //     proxy: {
      //       type: 'ajax',
      //       url: '{{ route('globaldata.data') }}',
      //       extraParams: {
      //         group: 'position'
      //       },
      //       reader: {
      //         root: 'data',
      //         totalProperty: 'count'
      //       }
      //     },
      //     autoLoad: true,
      //     listeners: {
      //       load: function(store) {
      //         store.insert(0, {
      //           id: 0,
      //           name: 'All Position'
      //         });
      //       }
      //     }
      //   });

      me.contractData = Ext.create('Ext.data.Store', {
        fields: ['id', 'name'],
        proxy: {
          type: 'ajax',
          url: '{{ route('globaldata.data') }}',
          extraParams: {
            group: 'contract_status'
          },
          reader: {
            root: 'data',
            totalProperty: 'count'
          }
        },
        autoLoad: true,
        listeners: {
          load: function(store) {
            store.insert(0, {
              id: 0,
              name: 'All Contract'
            });
          }
        }
      });

      me.careerData = Ext.create('Ext.data.Store', {
        fields: ['id', 'name'],
        proxy: {
          type: 'ajax',
          url: '{{ route('globaldata.data') }}',
          extraParams: {
            group: 'career'
          },
          reader: {
            root: 'data',
            totalProperty: 'count'
          }
        },
        autoLoad: true,
        listeners: {
          load: function(store) {
            store.insert(0, {
              id: 0,
              name: 'All Career'
            });
          }
        }
      });

      me.formFilter = Ext.create('Ext.form.Panel', {
        bodyPadding: 20,
        width: 400,
        autoScroll: true,
        height: 'auto',
        maxHeight: 400,
        defaults: {
          labelAlign: 'top'
        },
        layout: {
          type: 'vbox',
          align: 'stretch'
        },
        items: [{
            id: 'company_filter',
            xtype: 'combobox',
            fieldLabel: 'Company',
            labelAlign: 'top',
            store: me.companyData,
            displayField: 'name',
            valueField: 'id',
            queryMode: 'local',
            flex: 1,
            listeners: {
              select: function(combo, record) {
                const selectedRecord = Array.isArray(record) ? record[0] : record;
                if (selectedRecord) {
                  const companyId = selectedRecord.get('id');
                  me.organizationData.proxy.extraParams.company_id = companyId;
                  me.organizationData.load();
                }
              }
            },
          },
          Ext.create('Ext.ux.form.field.TreeCombo', {
            id: 'organization_filter',
            name: 'org_id',
            fieldLabel: 'Organization',
            labelAlign: 'top',
            rootVisible: true,
            canSelectFolders: true,
            editable: false,
            flex: 1,
            store: me.organizationData,
            listeners: {
              select: function(combo, record) {
                this.setValue(record.getId());
              }
            }
          }),
          {
            xtype: 'container',
            layout: 'hbox',
            items: [{
                id: 'division_filter',
                xtype: 'combobox',
                fieldLabel: 'Division',
                labelAlign: 'top',
                store: me.divisionData,
                displayField: 'name',
                valueField: 'id',
                queryMode: 'local',
                flex: 1,
                margin: '0 10 0 0',
              },
              {
                id: 'placement_filter',
                xtype: 'combobox',
                fieldLabel: 'Placement',
                labelAlign: 'top',
                store: me.placementData,
                displayField: 'name',
                valueField: 'id',
                queryMode: 'local',
                flex: 1
              }
            ]
          },
          {
            xtype: 'container',
            layout: 'hbox',
            margin: '10 0 0 0',
            items: [{
                id: 'contract_filter',
                xtype: 'combobox',
                fieldLabel: 'Contract',
                labelAlign: 'top',
                store: me.contractData,
                displayField: 'name',
                valueField: 'id',
                queryMode: 'local',
                margin: '0 10 0 0',
                flex: 1
              },
              {
                id: 'career_filter',
                xtype: 'combobox',
                fieldLabel: 'Career',
                labelAlign: 'top',
                store: me.careerData,
                displayField: 'name',
                valueField: 'id',
                queryMode: 'local',
                flex: 1,
              },
            ]
          },
        ]
      });

      me.filterPopup = Ext.create('Ext.window.Window', {
        title: 'Form Filters',
        modal: true,
        layout: 'fit',
        closeAction: 'hide',
        items: [me.formFilter],
        listeners: {
          show: function() {
            let extraParams = me.store.proxy.extraParams || {};

            let cmpCombo = Ext.getCmp('company_filter');
            let orgCombo = Ext.getCmp('organization_filter');
            let divCombo = Ext.getCmp('division_filter');
            let plcCombo = Ext.getCmp('placement_filter');
            let conCombo = Ext.getCmp('contract_filter');
            let carCombo = Ext.getCmp('career_filter');

            if (cmpCombo) cmpCombo.setValue(extraParams.company || 0);
            if (orgCombo) orgCombo.setValue(extraParams.organization || '0');
            if (divCombo) divCombo.setValue(extraParams.division || 0);
            if (plcCombo) plcCombo.setValue(extraParams.placement || 0);
            if (conCombo) conCombo.setValue(extraParams.contract || 0);
            if (carCombo) carCombo.setValue(extraParams.career || 0);
          }
        },
        buttons: [{
            text: 'Apply',
            cls: 'btn-green',
            handler: function() {
              const filters = {};

              me.formFilter.items.items.forEach(item => {
                if ((item.xtype === 'combobox' || item.xtype === 'treecombo') && item.fieldLabel &&
                  typeof item.fieldLabel ===
                  'string') {
                  const paramName = item.fieldLabel.toLowerCase().replace(/\s+/g, '_');
                  filters[paramName] = item.getValue() === null ? 'all' : item.getValue();
                }

                if (item.xtype === 'container') {
                  item.items.items.forEach(innerItem => {
                    if ((innerItem.xtype === 'combobox' || innerItem.xtype === 'treecombo') &&
                      innerItem.fieldLabel && typeof innerItem
                      .fieldLabel === 'string') {
                      const paramName = innerItem.fieldLabel.toLowerCase().replace(/\s+/g, '_');
                      filters[paramName] = innerItem.getValue() === null ? 'all' : innerItem
                        .getValue();
                    }
                  });
                }
              });

              me.store.proxy.extraParams = filters;
              me.store.load();
              me.filterPopup.close();
            }
          },
          {
            text: 'Cancel',
            cls: 'btn-red',
            handler: function() {
              me.filterPopup.close();
            }
          }
        ]
      });

      me.menus = Ext.create('Ext.menu.Menu', {
        items: [
          @if ($user->hasRoute('employee.create') || $user->hasRoute('employee.update'))
            {
              text: 'Create',
              iconCls: 'icon-add',
              handler: forms.create
            }, {
              text: 'Edit',
              iconCls: 'icon-edit',
              handler: forms.edit
            },
          @endif

          @if ($user->hasRoute('employee.request.approve') || $user->hasRoute('employee.request.reject'))
            '-',
            {
              text: 'Approve Request',
              iconCls: 'icon-yes',
              handler: function() {
                formsApproval.open();
              }
            },
            {
              text: 'Reject Request',
              iconCls: 'icon-no',
              handler: function() {
                formsReject.open();
              }
            },
            '-',
          @endif

          @if ($user->hasRoute('employee.delete'))
            {
              text: 'Delete',
              iconCls: 'icon-remove',
              handler: function() {
                let recs = me.getValues();
                if (recs.length) {
                  Ext.ajaxConfirm('Remove Employee', {
                    mask: me.grid,
                    url: '{{ route('employee.delete') }}',
                    params: {
                      '_method': 'DELETE',
                      '_token': '{{ csrf_token() }}',
                      data: Ext.encode(recs)
                    },
                    success: me.storeLoad
                  });
                } else Ext.msg.warning('Please select data!');
              }
            },
          @endif

          @if ($user->hasRoute('employee.restore') || $user->hasRoute('owners.forcedelete'))
            @if ($user->hasRoute('employee.restore'))
              {
                text: 'Restore',
                iconCls: 'icon-refresh',
                handler: function() {
                  let recs = me.getValues();
                  if (recs.length) {
                    Ext.ajaxConfirm('Restore Employee', {
                      mask: me.grid,
                      url: '{{ route('employee.restore') }}',
                      params: {
                        '_method': 'PUT',
                        '_token': '{{ csrf_token() }}',
                        data: Ext.encode(recs)
                      },
                      success: me.storeLoad
                    });
                  } else Ext.msg.warning(
                    'Please select data!');
                }
              },
            @endif

            @if ($user->hasRoute('employee.forcedelete'))
              {
                text: 'Forever Remove',
                iconCls: 'icon-remove',
                handler: function() {
                  let recs = me.getValues();
                  if (recs.length) {
                    Ext.ajaxConfirm('Forever Remove Employee', {
                      mask: me.grid,
                      url: '{{ route('employee.forcedelete') }}',
                      params: {
                        '_method': 'DELETE',
                        '_token': '{{ csrf_token() }}',
                        data: Ext.encode(recs)
                      },
                      success: me.storeLoad
                    });
                  } else Ext.msg.warning(
                    'Please select data!');
                }
              },
            @endif

            @if ($user->hasRoute('employee.import'))
              '-',
              {
                text: 'Import Data',
                iconCls: 'icon-excel',
                menu: [{
                    text: 'Download Format',
                    iconCls: 'icon-cloud',
                    handler: function() {
                      window.location = '{{ route('employee.export.excel.format.import') }}';
                    }
                  },
                  {
                    text: 'Upload File',
                    iconCls: 'icon-excel',
                    handler: function() {
                      formsImport.open();
                    }
                  },
                ]
              },
            @endif
          @endif
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
          @if ($user->hasRoute('employee.export.excel') || $user->hasRoute('employee.export.pdf'))
            {
              text: 'Export Data',
              iconCls: 'icon-save-dark',
              menu: [
                @if ($user->hasRoute('employee.export.excel'))
                  {
                    text: 'Export Excel',
                    iconCls: 'icon-excel',
                    handler: function() {
                      let filters = me.store.proxy.extraParams;
                      let query = '';
                      if (me.store.filters.items.length) query = me.store.filters.items[0].value;
                      filters.query = query;

                      let params = [];
                      for (var key in filters) {
                        var value = filters[key];
                        params.push(key + '=' + encodeURIComponent(value));
                      }

                      window.location = '{{ route('employee.export.excel') }}?' + params.join('&');
                    }
                  },
                @endif
                @if ($user->hasRoute('employee.export.pdf'))
                  {
                    text: 'Export PDF',
                    iconCls: 'icon-pdf',
                    handler: function() {
                      let recs = me.getValues();
                      if (recs.length) {
                        if (recs.length === 1) {
                          window.location = '{{ route('employee.export.pdf', ':id') }}'.replace(':id',
                            recs[0]);
                        } else {
                          Ext.Ajax.request({
                            url: '{{ route('employee.export.pdf.multiple') }}',
                            method: 'POST',
                            params: {
                              _token: '{{ csrf_token() }}',
                              data: Ext.encode(recs)
                            },
                            success: function(response) {
                              let result = Ext.decode(response.responseText);
                              if (result.url) {
                                window.location.href = result.url;
                              } else {
                                Ext.example.msg('Error',
                                  'An error occurred while generating the download.');
                              }
                            },
                            failure: function(response) {
                              Ext.example.msg('Error',
                                'An error occurred while downloading the files.');
                            }
                          });
                        }
                      } else {
                        Ext.Msg.alert('Warning', 'Please select data!');
                      }
                    }
                  }
                @endif
              ]
            },
          @endif
          '->', {
            xtype: 'searchfield',
            flex: 1,
            maxWidth: 300,
            minWidth: 180,
            store: me.store
          }
        ],
        columns: [{
            text: 'Status',
            dataIndex: 'last_contract',
            minWidth: 100,
            renderer: function(val) {
              const today = new Date();

              if (!val || !val.status || !val.status.name) {
                return `<div style="background-color: red; padding: 5px; border-radius: 3px; color: white; font-weight: bold; display: flex; justify-content: center; align-items: center;" title="No contract data">
                    INACTIVE
                </div>`;
              }

              const status = val.status.name.toUpperCase();
              const endDate = val.end_date ? new Date(val.end_date) : null;

              if (['RESIGN', 'TERMINATE', 'RETIREMENT'].includes(status)) {
                return `<div style="background-color: red; padding: 5px; border-radius: 3px; color: white; font-weight: bold; display: flex; justify-content: center; align-items: center;" title="${status} status">
                    INACTIVE
                </div>`;
              }

              if (status === 'PERMANENT') {
                return `<div style="background-color: blue; padding: 5px; border-radius: 3px; color: white; font-weight: bold; display: flex; justify-content: center; align-items: center;" title="Permanent contract">
                    ACTIVE
                </div>`;
              }

              if (status === 'FREELANCE') {
                return `<div style="background-color: orange; padding: 5px; border-radius: 3px; color: white; font-weight: bold; display: flex; justify-content: center; align-items: center;" title="Freelance contract">
                    ACTIVE
                </div>`;
              }

              if (endDate) {
                const timeDifference = endDate - today;
                const daysRemaining = Math.floor(timeDifference / (1000 * 60 * 60 * 24));
                let backgroundColor = 'red';
                let statusText = 'INACTIVE';
                let tooltip = 'Contract expired on ' + val.end_date;

                if (endDate >= today) {
                  statusText = 'ACTIVE';
                  if (daysRemaining <= 30) {
                    backgroundColor = 'purple';
                    tooltip = 'Expires in ' + daysRemaining + ' days';
                  } else {
                    backgroundColor = 'green';
                    tooltip = 'Contract active for ' + daysRemaining + ' days';
                  }
                }

                return `<div style="background-color: ${backgroundColor}; padding: 5px; border-radius: 3px; color: white; font-weight: bold; display: flex; justify-content: center; align-items: center;" title="${tooltip}">
                    ${statusText}
                </div>`;
              }

              return `<div style="background-color: red; padding: 5px; border-radius: 3px; color: white; font-weight: bold; display: flex; justify-content: center; align-items: center;" title="No end date">
                    INACTIVE
                </div>`;
            }
          },
          {
            text: "#",
            dataIndex: "employee_requests",
            minWidth: 100,
            align: 'center',
            sortable: false,
            menuDisabled: true,
            renderer: function(val) {
              const hasPendingApproval = val.some(request => request
                .approved_status ===
                null);

              if (hasPendingApproval) {
                return `
                    <i class="bi bi-envelope-fill text-warning" style="font-size: 24px" title="Pending approval"></i>
                `;
              }

              return '';
            }
          },
          {
            text: "Nik",
            dataIndex: 'nik',
            minWidth: 200
          },
          {
            text: "Nickname",
            dataIndex: 'nickname',
            minWidth: 200
          },
          {
            text: "Fullname",
            dataIndex: 'fullname',
            minWidth: 200
          },
          {
            text: "Company",
            dataIndex: 'company',
            minWidth: 200,
            renderer: function(val) {
              return val ? val.name : '-';
            }
          },
          {
            text: "Organization",
            dataIndex: 'organization',
            minWidth: 200,
            renderer: function(val) {
              return val ? val.name : '-';
            }
          },
          {
            text: "Division",
            dataIndex: 'division',
            minWidth: 200,
            renderer: function(val) {
              return val ? val.name : '-';
            }
          },
          {
            text: "Placement",
            dataIndex: 'placement',
            minWidth: 200,
            renderer: function(val) {
              return val ? val.name : '-';
            }
          },
          {
            text: "Contract",
            dataIndex: 'last_contract',
            minWidth: 200,
            renderer: function(val) {
              return val ? val.status?.name : '-';
            }
          },
          {
            text: "Career",
            dataIndex: 'last_career',
            minWidth: 200,
            renderer: function(val) {
              return val ? val.career?.name : '-';
            }
          },
          {
            text: "Phone",
            dataIndex: 'phone',
            minWidth: 200
          },
          {
            text: "Email",
            dataIndex: 'email',
            minWidth: 200
          },
          {
            text: "Address",
            dataIndex: 'address',
            minWidth: 200
          },
          {
            text: "City",
            dataIndex: 'address_city',
            minWidth: 200,
            renderer: function(val) {
              return val ? val.city : '-';
            }
          },
        ],
        bbar: me.bottomBar([{
            xtype: 'filter',
            id: 'trash',
            name: 'Trash',
            param: 'trash',
            iconCls: 'icon-trash',
            items: [{
                id: 1,
                name: 'ACTIVE'
              },
              {
                id: 2,
                name: 'TRASH'
              }
            ]
          },
          {
            xtype: 'button',
            text: 'FILTERS',
            iconCls: 'icon-filter',
            handler: function() {
              me.filterPopup.show();
            }
          }
        ]),
        viewConfig: {
          stripeRows: false,
          getRowClass: function(rec) {
            if (rec.get('deleted_at')) return 'disabled';
          },
          listeners: {
            itemcontextmenu: function(obj, rec, node, index, e) {
              e.stopEvent();

              let hasDeletedAt = rec.get('deleted_at') !== null;
              let hasPendingApproval = rec.get('employee_requests').some(
                request => request.approved_status === null
              );

              me.updateMenuItemsVisibility(rec);

              let hasApprovalItems =
                false;

              me.menus.items.each(function(item) {
                if (item.text === 'Approve Request' || item.text === 'Reject Request') {
                  item.setVisible(hasPendingApproval);
                  if (hasPendingApproval) {
                    hasApprovalItems = true;
                  }
                }
              });

              me.menus.items.each(function(item) {
                if (item.id === 'menuseparator-1141' || item.id === 'menuseparator-1144') {
                  if (hasApprovalItems) {
                    item.show();
                  } else {
                    item.hide();
                  }
                }
              });

              me.menus.showAt(e.getXY());
            },
            itemclick: function(obj, rec) {
              let hasDeletedAt = rec.get('deleted_at') !== null;
              me.updateMenuItemsVisibility(rec)
              if (!hasDeletedAt) {
                viewDetail.load("{{ route('employee.view') }}/" + rec.get(
                  'id'))
              }
            },
            itemdblclick: function(obj, rec) {
              let hasDeletedAt = rec.get('deleted_at') !== null;
              if (!hasDeletedAt) {
                viewDetail.expand();
              }
            },
          }
        }
      });
    }
  }
</script>
