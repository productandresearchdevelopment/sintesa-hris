@require('form.general')
@require('form.contract')
@require('form.citizen')
@require('form.education')
@require('form.family')
@require('form.job_experience')
@require('form.career')
@require('form.training')

@require('import.contract')
@require('import.career')
@require('import.training')
@require('import.job_experience')
@require('import.family')
@require('import.education')
@require('import.citizen')

<script>
  var Forms = function() {
    let me = Ext.utils.windowForms(this);
    me.data = null;

    me.general = new FormGeneral();
    me.contractInfo = new FormContract();
    me.citizenInfo = new FormCitizen();
    me.educationInfo = new FormEducation();
    me.familyInfo = new FormFamily();
    me.jobExperience = new FormJobExperience();
    me.careerInfo = new FormCareer();
    me.trainingInfo = new FormTraining();

    me.init = function() {
      me.importContract = new FormsImportContract();
      me.importCareer = new FormsImportCareer();
      me.importTraining = new FormsImportTraining();
      me.importJobExperience = new FormsImportJobExperience();
      me.importFamily = new FormsImportFamily();
      me.importEducation = new FormsImportEducation();
      me.importCitizen = new FormsImportCitizen();

      me.form = Ext.widget('form', {
        bodyPadding: 10,
        autoHeight: true,
        border: false,
        scrollable: true,
        layout: {
          type: 'vbox',
          align: 'stretch'
        },
        fieldDefaults: {
          labelAlign: 'top',
        },
        items: [{
            xtype: 'hidden',
            name: 'id'
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
            xtype: 'hidden',
            name: 'grid_data',
            value: []
          },
          {
            xtype: 'hidden',
            name: 'fileInputGeneral',
            value: []
          },
          {
            xtype: 'tabpanel',
            flex: 1,
            scrollable: true,
            layout: 'fit',
            id: 'tabs-employee',
            activeTab: 0,
            items: [{
                title: 'General',
                layout: 'form',
                bodyPadding: 10,
                items: me.general.getFields()
              },
              {
                title: 'Contract',
                layout: 'form',
                items: me.contractInfo.getGrid()
              },
              {
                title: 'Citizen',
                layout: 'form',
                items: me.citizenInfo.getGrid()
              },
              {
                title: 'Education',
                layout: 'fit',
                items: me.educationInfo.getGrid()
              },
              {
                title: 'Family',
                layout: 'fit',
                items: me.familyInfo.getGrid()
              },
              {
                title: 'Job Experience',
                layout: 'fit',
                items: me.jobExperience.getGrid()
              },
              {
                title: 'Career',
                layout: 'form',
                items: me.careerInfo.getGrid()
              },
              {
                title: 'Training',
                layout: 'fit',
                items: me.trainingInfo.getGrid()
              }
            ]
          },
          {
            xtype: 'container',
            layout: {
              type: 'hbox',
              pack: 'end'
            },
            style: {
              'border-top': '1px solid #ccc',
              'margin-top': '15px',
              'padding-top': '15px',
            },
            items: [{
                xtype: 'container',
                flex: 1
              },
              {
                xtype: 'button',
                text: 'Save',
                iconCls: 'icon-save-bright',
                handler: me.save,
              },
              {
                xtype: 'button',
                text: 'Cancel',
                cls: 'btn-red',
                iconCls: 'icon-close',
                handler: me.close,
              }
            ]
          }
        ]
      });

      me.createWindowForm('Form Employee', me.form, {
        maximized: true,
        header: false,
      });
    };

    me.collectGridData = function() {
      const contractData = me.contractInfo.getGrid()[0].store.data.items.map(item => item.data);
      const citizenData = me.citizenInfo.getGrid()[0].store.data.items.map(item => item.data);
      const educationData = me.educationInfo.getGrid()[0].store.data.items.map(item => {
        let education = item.data;
        education.file_id = item.get('file_hidden') || '';
        return education;
      });
      const familyData = me.familyInfo.getGrid()[0].store.data.items.map(item => item.data);
      const jobExperienceData = me.jobExperience.getGrid()[0].store.data.items.map(item => item.data);
      const careerData = me.careerInfo.getGrid()[0].store.data.items.map(item => item.data);
      const trainingData = me.trainingInfo.getGrid()[0].store.data.items.map(item => item.data);

      const allData = {
        contract: contractData,
        citizen: citizenData,
        education: educationData,
        family: familyData,
        jobExperience: jobExperienceData,
        career: careerData,
        training: trainingData
      };

      return allData;
    };

    me.create = function() {
      me.show();
      me.reset();
      me.data = null;

      var tabsEmployee = Ext.getCmp('tabs-employee');
      tabsEmployee.setActiveTab(0);

      var imagePreview = document.getElementById('imagePreview');
      var dropText = document.getElementById('dropText');
      var fileSizeText = document.getElementById('fileSizeText');

      imagePreview.style.display = 'none';
      dropText.style.display = 'block';
      fileSizeText.style.display = 'block';

      var orgStore = me.getField('org_id').store;
      orgStore.load({
        callback: function(records, operation, success) {
          if (success) {
            me.getField('org_id').setValue('0');
          } else {
            console.error('Failed to load organization data');
          }
        }
      });

      me.contractInfo.getGrid()[0].store.removeAll();
      me.educationInfo.getGrid()[0].store.removeAll();
      me.familyInfo.getGrid()[0].store.removeAll();
      me.jobExperience.getGrid()[0].store.removeAll();
      me.careerInfo.getGrid()[0].store.removeAll();
      me.trainingInfo.getGrid()[0].store.removeAll();
      me.citizenInfo.getGrid()[0].store.removeAll();

      var citizenStore = me.citizenInfo.citizenData;
      citizenStore.load({
        callback: function(records, operation, success) {
          if (success) {
            var citizenDataArray = Object.values(citizenStore.data.map);
            var citizenWithTrueProperty = citizenDataArray.filter(item => item.data.property === true ||
              item.data.property === 'true');


            var gridStore = me.citizenInfo.getGrid()[0].store;
            gridStore.loadData(citizenWithTrueProperty.map(item => {
              return {
                citizen_id: item.data.id,
              };
            }));
          } else {
            console.error('Failed to load citizen data');
          }
        }
      });


      me.form.url = '{{ route('employee.create') }}';
      me.form.getForm().findField('_method').setValue('');
    };

    me.edit = function() {
      var rec = grids.getRec(true);
      if (rec) {
        me.show();
        me.reset();
        me.form.getEl().mask('Loading');
        http.request({
          method: 'get',
          url: '{{ route('employee.get', '') }}/' + rec.id,
          success: function(response) {
            me.data = Ext.decode(response.responseText);
            me.editRender(me.data);
            me.form.getEl().unmask();
          },
          failure: function() {
            Ext.Msg.alert('Error', 'Internal Server Error!');
            me.form.getEl().unmask();
            me.close();
          }
        });
      } else {
        Ext.Msg.alert('Warning!', 'Please select data!');
      }
    };

    me.editRender = function(record) {
      var tabsEmployee = Ext.getCmp('tabs-employee');
      tabsEmployee.setActiveTab(0);

      me.form.url = '{{ route('employee.update') }}';
      me.form.getForm().findField('_method').setValue('PUT');

      me.form.getForm().findField('id').setValue(record.id);
      me.form.getForm().findField('nik').setValue(record.nik);
      me.form.getForm().findField('fullname').setValue(record.fullname);
      me.form.getForm().findField('join_date').setValue(record.join_date);
      me.form.getForm().findField('nickname').setValue(record.nickname);
      me.form.getForm().findField('office_id').setValue(record.office_id);
      me.form.getForm().findField('division_id').setValue(record.division_id);
      me.form.getForm().findField('company_id').setValue(record.company_id);
      me.form.getForm().findField('placement_id').setValue(record.placement_id);
      me.form.getForm().findField('shift_start_time').setValue(formatTime(record.shift_start_time));
      me.form.getForm().findField('shift_end_time').setValue(formatTime(record.shift_end_time));
      me.form.getForm().findField('phone').setValue(record.phone);
      me.form.getForm().findField('email').setValue(record.email);
      me.form.getForm().findField('leave_saldo').setValue(record.leave_saldo);
      me.form.getForm().findField('birth_place').setValue(record.birth_place);
      me.form.getForm().findField('birth_date').setValue(record.birth_date);
      me.form.getForm().findField('gender_id').setValue(record.gender_id);
      me.form.getForm().findField('marital_id').setValue(record.marital_id);
      me.form.getForm().findField('religion_id').setValue(record.religion_id);
      me.form.getForm().findField('address').setValue(record.address);
      me.form.getForm().findField('address_city_id').setValue(record.address_city_id);
      me.form.getForm().findField('address_province_id').setValue(record.address_province_id);
      me.form.getForm().findField('address_permanent').setValue(record.address_permanent || null);
      me.form.getForm().findField('address_permanent_city_id').setValue(record.address_permanent_city_id || null);
      me.form.getForm().findField('address_permanent_province_id').setValue(record.address_permanent_province_id ||
        null);
      me.form.getForm().findField('bank_id').setValue(record.bank_id);
      me.form.getForm().findField('bank_account').setValue(record.bank_account);
      me.form.getForm().findField('emergency_relation_id').setValue(record.emergency_relation_id);
      me.form.getForm().findField('emergency_contact_name').setValue(record.emergency_contact_name);
      me.form.getForm().findField('emergency_contact_phone').setValue(record.emergency_contact_phone);
      me.form.getForm().findField('emergency_contact_address').setValue(record.emergency_contact_address);

      var orgId = (record.org_id != null && record.org_id !== '' && record.org_id !== '0') ?
        String(record.org_id) :
        null;
      if (!orgId) return;

      var orgField = me.general.orgPicker;
      var orgStore = me.general.orgStore;

      orgStore.getProxy().extraParams.company_id = record.company_id;
      Ext.Ajax.request({
        url: '{{ route('organization.path', ['id' => ':id']) }}'.replace(':id', orgId),
        method: 'GET',

        success: function(resp) {
          var ids = Ext.decode(resp.responseText) || [];
          ids = ids.map(String);

          if (ids.length && ids[0] === '0') {
            ids.shift();
          }

          if (!ids.length) {
            console.warn('⚠️ PATH EMPTY → fallback setValue');
            orgField.setValue(orgId);
            return;
          }

          orgStore.load({
            callback: function(records, op, success) {

              var tree = orgField.getPicker();
              var root = tree.getRootNode();

              function expandNode(node, pathIds, index) {
                if (index >= pathIds.length) {
                  orgField.setValue(String(node.getId()));
                  orgField.setRawValue(node.get('text'));
                  return;
                }

                var nextId = pathIds[index];
                node.expand(false, function() {

                  var child = node.findChild('id', nextId);
                  if (!child) {
                    orgField.setValue(orgId);
                    return;
                  }
                  expandNode(child, pathIds, index + 1);
                });
              }

              expandNode(root, ids, 0);
            }
          });
        },

        failure: function() {
          console.error('❌ FAILED FETCH PATH');
          orgField.setValue(orgId);
        }
      });

      var imagePreview = document.getElementById('imagePreview');
      var dropText = document.getElementById('dropText');
      var fileSizeText = document.getElementById('fileSizeText');

      if (record.photo_id) {
        imagePreview.src = '{{ route('file', ':id') }}'.replace(':id', record.photo_id);
        imagePreview.style.display = 'block';
        dropText.style.display = 'none';
        fileSizeText.style.display = 'none';
        imagePreview.style.width = '200px';
        imagePreview.style.height = '200px';
      } else {
        imagePreview.style.display = 'none';
        dropText.style.display = 'block';
        fileSizeText.style.display = 'block';
      }

      function formatDateField(dateField) {
        if (dateField) {
          const date = new Date(dateField);
          if (!isNaN(date.getTime())) {
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear();
            return `${month}/${day}/${year}`;
          }
        }
        return null;
      }

      function formatTime(timeStr) {
        return timeStr ? timeStr.substring(0, 5) : null;
      }

      function replaceFieldCustom(records) {
        return records.map(record => {
          if (record.file_id) {
            record.file_id = record.file.filename_origin;
          }
          if (record.join_date || record.start_date || record.end_date || record.birth_date || record
            .graduate || record.date) {
            record.join_date = formatDateField(record.join_date);
            record.start_date = formatDateField(record.start_date);
            record.end_date = formatDateField(record.end_date);
            record.birth_date = formatDateField(record.birth_date);
            record.date = formatDateField(record.date);
          }
          return record;
        });
      }

      if (record.contracts && record.contracts.length > 0) {
        record.contracts = replaceFieldCustom(record.contracts);
      }

      if (record.citizens && record.citizens.length > 0) record.citizens = replaceFieldCustom(record.citizens);
      else {
        var citizenStore = me.citizenInfo.citizenData;
        citizenStore.load({
          callback: function(records, operation, success) {
            if (success) {
              var citizenDataArray = Object.values(citizenStore.data.map);
              var citizenWithTrueProperty = citizenDataArray.filter(item => item.data.property === true ||
                item.data.property === 'true');


              var gridStore = me.citizenInfo.getGrid()[0].store;
              gridStore.loadData(citizenWithTrueProperty.map(item => {
                return {
                  citizen_id: item.data.id,
                };
              }));
            } else {
              console.error('Failed to load citizen data');
            }
          }
        });
      };

      if (record.educations && record.educations.length > 0) {
        record.educations = replaceFieldCustom(record.educations);
      }
      if (record.families && record.families.length > 0) {
        record.families = replaceFieldCustom(record.families);
      }
      if (record.job_experiences && record.job_experiences.length > 0) {
        record.job_experiences = replaceFieldCustom(record.job_experiences);
      }
      if (record.careers && record.careers.length > 0) {
        record.careers = replaceFieldCustom(record.careers);
      }
      if (record.trainings && record.trainings.length > 0) {
        record.trainings = replaceFieldCustom(record.trainings);
      }

      var contractStore = me.contractInfo.getGrid()[0].store;
      contractStore.loadData(record.contracts);

      var citizenStore = me.citizenInfo.getGrid()[0].store;
      citizenStore.loadData(record.citizens);

      var educationStore = me.educationInfo.getGrid()[0].store;
      educationStore.loadData(record.educations);

      var familyStore = me.familyInfo.getGrid()[0].store;
      familyStore.loadData(record.families);

      var jobExperienceStore = me.jobExperience.getGrid()[0].store;
      jobExperienceStore.loadData(record.job_experiences);

      var careerStore = me.careerInfo.getGrid()[0].store;
      careerStore.loadData(record.careers);

      var trainingStore = me.trainingInfo.getGrid()[0].store;
      trainingStore.loadData(record.trainings);
    };

    me.save = function() {
      if (me.form.getForm().isValid()) {
        var form = me.form.getForm();
        var values = form.getValues();
        var fileInputGeneral = Ext.get('fileInputGeneral');
        let gridData = me.collectGridData();

        var formData = new FormData();
        formData.append('_token', values['_token']);
        formData.append('_method', values['_method']);
        formData.append('fileInputGeneral', fileInputGeneral.dom.files[0]);

        for (let key in values) {
          if (values.hasOwnProperty(key) && key !== 'fileInputGeneral') {
            formData.append(key, values[key]);
          }
        }

        for (let gridKey in gridData) {
          if (gridData.hasOwnProperty(gridKey)) {
            let gridItems = gridData[gridKey];

            for (let i = 0; i < gridItems.length; i++) {
              let item = gridItems[i];

              if (gridKey === 'citizen' && (!item.citizen_id || item.value === "" || item.value === null || item
                  .value === "null")) {
                Ext.Msg.alert(
                  'Error',
                  `Field citizen_id dan value is required in row ${i + 1} on ${gridKey} tab!`
                );
                return;
              }

              for (let key in item) {
                if (
                  item.hasOwnProperty(key) &&
                  item[key] !== null &&
                  item[key] !== "" &&
                  item[key] !== "null" &&
                  item[key] !== "undefined" &&
                  item[key] !== undefined
                ) {
                  formData.append(`${gridKey}[${i}][${key}]`, item[key]);
                }
              }
            }
          }
        }

        Ext.Ajax.request({
          url: me.form.url,
          method: 'POST',
          rawData: formData,
          headers: {
            'Content-Type': null
          },
          success: function(response) {
            grids.storeLoad();
            Ext.Msg.alert('Success', 'Data has been saved!');
            me.close();
          },
          failure: function(response) {
            Ext.Msg.alert('Error', 'Failed to save data.');
          }
        });
      } else {
        Ext.Msg.alert('Error', 'Please fill all required fields!');
      }
    };

  };
</script>
