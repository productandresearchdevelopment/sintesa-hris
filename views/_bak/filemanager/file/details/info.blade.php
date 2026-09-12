<script>
  var InfoDetail = function() {
    let me = this;
    me.init = function() {
      me.panel = Ext.create('Ext.panel.Panel', {
        id: 'detail-tab-info',
        title: 'INFO',
        border: true,
        autoScroll: true,
        flex: 1,
        html: `<div id="view-detail" style="position: absolute; top: 0; left:0; right:0; background: #FAFAFA">
                              <div style="font-size: 11px; padding: 30px 0px; color: #ccc; text-align: center">
                                  NO DISPLAY DATA
                              </div>
                         </div>`
      });
    }

    me.set = function(data) {
      if (data) {
        const fileRoute = "{{ route('file', ':id') }}";
        data.extension = data.extension || data.file?.filename?.split('.').pop().toLowerCase();

        let content = '';

        if (data.file) {
          if (['jpg', 'jpeg', 'png', 'gif'].includes(data.extension)) {
            content = `<img src="${fileRoute.replace(':id', data.file.id)}" class="img-fluid" alt="...">`;
          } else if (['mp3', 'ogg', 'wav'].includes(data.extension)) {
            content = `<audio controls>
               <source src="${fileRoute.replace(':id', data.file.id)}" type="audio/${data.extension}">
               Your browser does not support the audio element.
             </audio>`;
          } else if (['mp4', 'webm', 'ogv'].includes(data.extension)) {
            content = `<video controls playsinline width="100%">
               <source src="${fileRoute.replace(':id', data.file.id)}" type="video/${data.extension}">
               Your browser does not support the video element.
             </video>`;
          } else {
            content = `<div class="file-icon text-center">`;
            if (data.extension === 'pdf') {
              content += `<i class="bi bi-file-earmark-pdf-fill" style="color: #e10a0a; font-size: 60px"></i>
                          <p class="mt-2">PDF Document</p>`;
            } else if (['doc', 'docx'].includes(data.extension)) {
              content += `<i class="bi bi-file-earmark-word-fill" style="color: #145adc; font-size: 60px"></i>
                          <p class="mt-2">Word Document</p>`;
            } else if (['ppt', 'pptx'].includes(data.extension)) {
              content += `<i class="bi bi-file-earmark-ppt-fill" style="color: #ff9000; font-size: 60px"></i>
                          <p class="mt-2">PowerPoint Presentation</p>`;
            } else if (['zip', 'rar'].includes(data.extension)) {
              content += `<i class="bi bi-file-earmark-zip-fill" style="color:#8100ce;font-size: 60px"></i>
                          <p class="mt-2">ZIP Archive</p>`;
            } else if (['xls', 'xlsx'].includes(data.extension)) {
              content += `<i class="bi bi-file-earmark-excel-fill" style="color:#068800;font-size: 60px"></i>
                          <p class="mt-2">Excel</p>`;
            } else {
              content += `<i class="bi bi-file-earmark-code-fill" style="color: #0075f3;font-size: 60px"></i>
                          <p class="mt-2">File</p>`;
            }
            content +=
              `<a href="${fileRoute.replace(':id', data.file.id)}" target="_blank" class="fs-6">View</a></div>`;
          }
        } else {
          content +=
            `<div class="file-icon text-center">
                          <i class="bi bi-file-earmark-code-fill" style="color: #B99470; font-size: 60px"></i>
                          <p class="mt-2">File</p>
                          <a href="${data.link}" target="_blank" class="fs-6">View</a></div>`;
        }

        data.content = content;

        // Determine the file type description
        if (['xls', 'xlsx'].includes(data.extension)) {
          data.type_file = 'Excel Spreadsheet';
        } else if (['doc', 'docx'].includes(data.extension)) {
          data.type_file = 'Word Document';
        } else if (['ppt', 'pptx'].includes(data.extension)) {
          data.type_file = 'Presentation';
        } else if (data.extension === 'txt') {
          data.type_file = 'Text';
        } else if (data.extension === 'pdf') {
          data.type_file = 'PDF File';
        } else if (data.extension === 'zip') {
          data.type_file = 'Zip File';
        } else if (data.type === 'image') {
          data.type_file = 'Image';
        } else if (data.link) {
          data.type_file = 'Link Browser';
        } else {
          data.type_file = `Application/${data.extension.toUpperCase()}`;
        }

        // Prepare the size information
        if (data.file && data.file.size !== null) {
          if (data.file.size < 1000) {
            data.size = `${data.file.size} B`;
          } else if (data.file.size < 1000000) {
            data.size = `${(data.file.size / 1000).toFixed(2)} KB`;
          } else if (data.file.size < 1000000000) {
            data.size = `${(data.file.size / 1000000).toFixed(2)} MB`;
          } else {
            data.size = `${(data.file.size / 1000000000).toFixed(2)} GB`;
          }
        } else if (data.link) {
          data.size = 'Not applicable (Link)';
        } else {
          data.size = 'Not available';
        }

        // Prepare the created_at information
        data.created_at = data.created_at ? data.created_at : '-';

        me.panel.update(String.format(`@require('tpl.info')`, data));

        setTimeout(function() {
          // function
        }, 200)
      }
    }

    me.init();
  }
</script>
