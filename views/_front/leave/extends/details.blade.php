<script>
  var Details = function() {
    let me = this;

    me.currentData = null;

    me.init = function() {
      me.panel = Ext.create('Ext.panel.Panel', {
        bodyPadding: 20,
        autoScroll: true,
        border: false,
        html: '<div class="text-center text-muted p-4">No data selected</div>'
      });

      me.window = Ext.create('Ext.window.Window', {
        title: 'Leave Request Detail',
        width: 600,
        height: 520,
        modal: true,
        closeAction: 'hide',
        layout: 'fit',
        items: [me.panel],
        buttons: [{
          text: 'Close',
          cls: 'btn-red',
          iconCls: 'icon-close',
          handler: function() {
            me.window.hide();
          }
        }]
      });
    };

    me.renderStatusBadge = function(status, date, note, title, approverName) {
      var badgeClass = 'bg-secondary';
      var statusText = 'Pending';
      var noteHtml = note ?
        `<div class="mt-1" style="font-size: 11.5px; color: #475569;"><em>Note:</em> ${Ext.util.Format.htmlEncode(note)}</div>` :
        '';

      var metaParts = [];
      if (approverName) metaParts.push(`By: <strong>${Ext.util.Format.htmlEncode(approverName)}</strong>`);
      if (date) metaParts.push(`${date}`);
      var metaHtml = metaParts.length > 0 ?
        `<div style="font-size: 11px; color: #64748b; margin-top: 2px;">${metaParts.join(' &bull; ')}</div>` : '';

      if (status === 1) {
        badgeClass = 'bg-success';
        statusText = 'Approved';
      } else if (status === 0) {
        badgeClass = 'bg-danger';
        statusText = 'Rejected';
      }

      return `
        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 10px 14px; margin-bottom: 8px;">
          <div style="display: flex; justify-content: space-between; align-items: center;">
            <span style="font-weight: 700; font-size: 12.5px; color: #334155;">${title}</span>
            <span class="badge ${badgeClass}" style="font-size: 11px;">${statusText}</span>
          </div>
          ${metaHtml}
          ${noteHtml}
        </div>
      `;
    };

    me.show = function(data) {
      if (!data) return;
      me.currentData = data;
      me.window.show();
      me.panel.getEl().mask('Loading...');

      Ext.Ajax.request({
        url: '{{ route('leave.get') }}/' + data.id,
        method: 'GET',
        success: function(response) {
          me.panel.getEl().unmask();
          var res = Ext.decode(response.responseText);
          if (res && res.success && res.data) {
            me.renderContent(res.data);
          } else {
            me.renderContent(data);
          }
        },
        failure: function() {
          me.panel.getEl().unmask();
          me.renderContent(data);
        }
      });
    };

    me.renderContent = function(d) {
      var emp = d.employee || {};
      var type = d.type || {};
      var photo = d.profile_picture || '{{ asset('images/nouser copy.png') }}';
      var empName = emp.fullname || emp.nickname || 'Employee';
      var orgName = (emp.organization && emp.organization.name) ? emp.organization.name : '-';
      var nik = emp.nik || '-';
      var fileRoute = '{{ route('file', ':id') }}';

      var org = emp.organization || {};
      var auth1 = org.authorized1 || {};
      var auth2 = org.authorized2 || {};
      var auth1Id = (typeof auth1 === 'object') ? auth1.id : auth1;
      var auth2Id = (typeof auth2 === 'object') ? auth2.id : auth2;
      var isSingleEvaluator = d.single_evaluator || (auth1Id && auth2Id && auth1Id === auth2Id) || (auth1Id && !auth2Id);
      var isApproved = isSingleEvaluator ? (d.approved1_status === 1) : (d.approved2_status === 1);

      var overallStatusHtml = '';
      if (d.cancel_at) {
        overallStatusHtml =
          '<span class="badge bg-secondary" style="font-size: 12px; padding: 5px 10px;">Cancelled</span>';
      } else if (d.approved1_status === 0 || d.approved2_status === 0) {
        overallStatusHtml =
          '<span class="badge bg-danger" style="font-size: 12px; padding: 5px 10px;">Rejected</span>';
      } else if (isApproved) {
        overallStatusHtml =
          '<span class="badge bg-success" style="font-size: 12px; padding: 5px 10px;">Approved</span>';
      } else if (d.approved1_status === 1) {
        overallStatusHtml =
          '<span class="badge bg-primary" style="font-size: 12px; padding: 5px 10px;">Checked (Step 1 Approved)</span>';
      } else {
        overallStatusHtml =
          '<span class="badge bg-warning text-dark" style="font-size: 12px; padding: 5px 10px;">Pending</span>';
      }

      var attachmentHtml = '<span class="text-muted" style="font-size: 12px;">No attachment</span>';
      if (d.file_id) {
        var downloadUrl = fileRoute.replace(':id', d.file_id);
        attachmentHtml = `
          <a href="${downloadUrl}" target="_blank" class="btn btn-sm btn-outline-primary" style="font-size: 12px; text-decoration: none;">
            <i class="bi bi-file-earmark-arrow-down me-1"></i> Download Attachment
          </a>
        `;
      }

      var cancelNoteHtml = '';
      if (d.cancel_at) {
        cancelNoteHtml = `
          <div class="alert alert-secondary py-2 px-3 mt-3 mb-0" style="font-size: 12px;">
            <strong>Cancelled on:</strong> ${d.cancel_at}<br/>
            <strong>Reason:</strong> ${Ext.util.Format.htmlEncode(d.cancel_note || '-')}
          </div>
        `;
      }

      var getAuthName = function(auth, fallback) {
        if (!auth) return fallback;
        if (typeof auth === 'object' && auth.name) return auth.name;
        if (typeof auth === 'string' && auth.trim()) return auth.trim();
        return fallback;
      };

      var auth1Title = isSingleEvaluator ? ('Approval: ' + getAuthName(auth1, 'Evaluator')) : ('Step 1: ' + getAuthName(auth1, 'Authorized 1'));
      var auth2Title = 'Step 2: ' + getAuthName(auth2, 'Authorized 2');

      var approver1Name = d.approver1 ? (d.approver1.fullname || d.approver1.name) : (d.approved1_by || '');
      var approver2Name = d.approver2 ? (d.approver2.fullname || d.approver2.name) : (d.approved2_by || '');

      var date1 = d.approved1_at || d.approved1_date || '';
      var date2 = d.approved2_at || d.approved2_date || '';

      var timelineHtml = me.renderStatusBadge(d.approved1_status, date1, d.approved1_note, auth1Title, approver1Name);
      if (!isSingleEvaluator && auth2Id) {
        timelineHtml += me.renderStatusBadge(d.approved2_status, date2, d.approved2_note, auth2Title, approver2Name);
      }

      var html = `
        <div style="font-family: inherit;">
          <!-- Header Profile -->
          <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #e2e8f0; padding-bottom: 15px; margin-bottom: 15px;">
            <div style="display: flex; align-items: center; gap: 14px;">
              <img src="${photo}" style="width: 48px; height: 48px; border-radius: 50%; object-fit: cover; border: 2px solid #e2e8f0;" onerror="this.src='{{ asset('images/nouser copy.png') }}'"/>
              <div>
                <div style="font-size: 16px; font-weight: 800; color: #0f172a;">${empName}</div>
                <div style="font-size: 12px; color: #64748b;">NIK: ${nik} | ${orgName}</div>
              </div>
            </div>
            <div>${overallStatusHtml}</div>
          </div>

          <!-- Leave Details Card -->
          <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px; margin-bottom: 15px;">
            <div class="row g-2 mb-2">
              <div class="col-6">
                <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Leave Type</div>
                <div style="font-size: 13.5px; font-weight: 700; color: #0f172a;">${type.name || '-'}</div>
              </div>
              <div class="col-6">
                <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Duration</div>
                <div style="font-size: 13.5px; font-weight: 700; color: #0f172a;">${d.duration || 1} Day(s)</div>
              </div>
            </div>
            <div class="row g-2 mb-2">
              <div class="col-6">
                <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Start Date</div>
                <div style="font-size: 13px; color: #334155;">${d.start_date || '-'}</div>
              </div>
              <div class="col-6">
                <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">End Date</div>
                <div style="font-size: 13px; color: #334155;">${d.end_date || '-'}</div>
              </div>
            </div>
            <div class="mb-2">
              <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Notes / Reason</div>
              <div style="font-size: 12.5px; color: #334155; margin-top: 2px;">${Ext.util.Format.htmlEncode(d.description || '-')}</div>
            </div>
            <div>
              <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 4px;">Attachment</div>
              ${attachmentHtml}
            </div>
          </div>

          <!-- Approval Progress Timeline -->
          <div style="font-size: 12px; font-weight: 800; color: #475569; text-transform: uppercase; margin-bottom: 8px;">
            Approval Timeline
          </div>
          ${timelineHtml}

          ${cancelNoteHtml}
        </div>
      `;

      me.panel.update(html);
    };
  };
</script>
