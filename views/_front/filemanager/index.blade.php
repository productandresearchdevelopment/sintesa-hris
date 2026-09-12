@extends('headers.head')

@section('header')
  <style>
    .search-box .form-control {
      border-radius: 10px;
      padding-left: 40px
    }

    .search-box .search-icon {
      position: absolute;
      left: 13px;
      top: 50%;
      -webkit-transform: translateY(-50%);
      transform: translateY(-50%);
      fill: #545965;
      width: 16px;
      height: 16px
    }

    .container {
      margin: 0 0;
    }

    .card {
      position: relative;
      display: -webkit-box;
      display: -ms-flexbox;
      display: flex;
      -webkit-box-orient: vertical;
      -webkit-box-direction: normal;
      -ms-flex-direction: column;
      flex-direction: column;
      min-width: 0;
      word-wrap: break-word;
      background-color: #fff;
      background-clip: border-box;
      /* border: 1px solid #eff0f2; */
      border-radius: 1rem;
      margin-bottom: 1rem;
    }

    .me-3 {
      margin-right: 1rem !important;
    }

    .font-size-24 {
      font-size: 24px !important;
    }

    .avatar-title {
      -webkit-box-align: center;
      -ms-flex-align: center;
      align-items: center;
      background-color: #3b76e1;
      color: #fff;
      display: -webkit-box;
      display: -ms-flexbox;
      display: flex;
      font-weight: 500;
      height: 100%;
      -webkit-box-pack: center;
      -ms-flex-pack: center;
      justify-content: center;
      width: 100%;
    }

    .bg-soft-info {
      background-color: rgba(87, 201, 235, .25) !important;
    }

    .bg-soft-primary {
      background-color: rgba(59, 118, 225, .25) !important;
    }

    .d-flex align-items-center justify-content-sm-start .button-add-container {
      display: flex;
      justify-content: center;
      align-items: center;
      width: 100%;
    }

    .button-add-container button {
      width: 100%;
    }

    .file-info-container {
      max-width: 100%;
    }

    .file-info-container-wrapper {
      max-width: 70%;
    }

    @media (min-width: 425px) {
      .button-add-container {
        justify-content: flex-start;
      }

      .button-add-container button {
        width: max-content;
      }

      .file-info-container-wrapper {
        max-width: 100%;
      }
    }

    a {
      text-decoration: none !important;
    }
  </style>
@endsection

@section('body')
  <div class="bg-white p-2 main-container" style="min-height: 100%; min-width: 100%;">
    <div class="col-xl-12">
      <div class="card">
        <div class="card-body">
          <div class="button-add-container">
            <div class="mb-4">
              {{-- <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                  aria-haspopup="true" aria-expanded="false">
                  <i class="mdi mdi-plus me-1"></i> Create New
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                  <a class="dropdown-item" href="#" id ="createFolder"><i class="mdi mdi-folder-outline me-1"></i>
                    Folder</a>
                  <a class="dropdown-item" href="#" id="createFile"><i class="mdi mdi-file-outline me-1"></i>
                    File</a>
                  <a class="dropdown-item" href="#" id="createLink"><i class="mdi mdi-file-outline me-1"></i>
                    LINK</a>
                </div>
              </div> --}}
            </div>
          </div>

          <div class="row">
            <div class="col-lg-4 col-sm-12">
              <h5 class="font-size-16 me-3 mb-0">Folders</h5>
              <div aria-label="breadcrumb">
                <ol class="breadcrumb" id="breadcrumb-list">
                </ol>
              </div>
              <div class="row mt-1" id="folder-container">
              </div>
            </div>

            <div class="col-lg-8 col-sm-12">
              <div class="row">
                <div class="col">
                  <h5 class="font-size-16 me-3 mb-0">Files</h5>
                </div>
                <div class="col d-flex justify-content-end">
                  <div class="d-flex align-items-center mb-2">
                    <p class="mb-0 me-2">Sort by:</p>
                    <div class="dropdown">
                      <button class="btn btn-light dropdown-toggle" id="sort-extension" type="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        File Type
                      </button>
                      <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" data-value="">All</a></li>
                        <li><a class="dropdown-item" href="#" data-value="pdf">PDF</a></li>
                        <li><a class="dropdown-item" href="#" data-value="xlsx">Excel</a></li>
                        <li><a class="dropdown-item" href="#" data-value="docx">Word</a></li>
                        <li><a class="dropdown-item" href="#" data-value="ppt">PowerPoint</a></li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-12 mt-2">
                <div class="search-box mb-2">
                  <div class="position-relative">
                    <input id="search-input" type="text" class="form-control border-2 rounded" placeholder="Search...">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24"
                      class="eva eva-search-outline search-icon">
                      <g data-name="Layer 2">
                        <g data-name="search">
                          <rect width="24" height="24" opacity="0"></rect>
                          <path
                            d="M20.71 19.29l-3.4-3.39A7.92 7.92 0 0 0 19 11a8 8 0 1 0-8 8 7.92 7.92 0 0 0 4.9-1.69l3.39 3.4a1 1 0 0 0 1.42 0 1 1 0 0 0 0-1.42zM5 11a6 6 0 1 1 6 6 6 6 0 0 1-6-6z">
                          </path>
                        </g>
                      </g>
                    </svg>
                  </div>
                </div>
              </div>
              <div id="file-container" class="mt-3">
              </div>
            </div>
          </div>

        </div>
      </div>

    </div>
  </div>

  <!-- MODAL -->
  <div class="modal fade" id="dynamicModal" tabindex="-1" aria-labelledby="dynamicModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="dynamicModalLabel"></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="dynamicForm">
            <div id="dynamicFields"></div>
            <button type="submit" class="btn btn-primary"></button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      const user = @json($user);
      const folder = @json($folder);
      const rootFolder = @json($parentFolder);
      let currentPath = [];
      let filterFolder = null;
      let currentSearch = '';
      let currentSort = '';
      let currentFolderId = null;
      let currentFolderParentId = null;
      let folderSelected = null;
      let folderHistory = [];

      // =======================================================================  FOLDER DATA
      function fetchFolderData(parentId = null, level = null) {

        $.ajax({
          type: "GET",
          url: "{{ route('filemanager.folder.data') }}",
          data: {
            node: parentId,
            level: level
          },
          success: function(response) {
            if (Array.isArray(response)) {
              renderFolders(response);
            } else {
              console.error('Unexpected data format:', response);
            }
          },
          error: function(xhr, status, error) {
            console.error('Error fetching folder data:', error);
          },
          complete: function() {
            hideLoading();
          }
        });
      }

      function renderFolders(data) {
        const folderContainer = $("#folder-container");
        folderContainer.empty();

        data.forEach(folder => {
          //   const folderCard = `
        //     <div class="row">
        //         <div class="card shadow-none border" data-id="${folder.id}">
        //             <div class="card-body p-3">
        //                 <div class="col">
        //                     <div class="d-flex align-items-center justify-content-between gap-2">
        //                         <div class="d-flex align-items-center gap-2">
        //                             <i class="bx bxs-folder h1 mb-0 text-warning"></i>
        //                             <h5 class="font-size-15 text-truncate mb-0">
        //                                 <a href="javascript:void(0);" class="text-body folder-link">${folder.name}</a>
        //                             </h5>
        //                         </div>
        //                         <div class="dropdown mb-0">
        //                           <a class="btn btn-link text-muted p-1 mt-n2" role="button" data-bs-toggle="dropdown"
        //                             aria-haspopup="true">
        //                             <i class="mdi mdi-dots-vertical font-size-20 "></i>
        //                           </a>
        //                           <div class="dropdown-menu dropdown-menu-end">
        //                             <a class="dropdown-item" href="#" id="editFolder"><i class="bi bi-trash3 me-1"></i> Edit</a>
        //                             <a class="dropdown-item" href="#" id="deleteFolder"><i class="bi bi-trash3 me-1"></i> Delete</a>
        //                           </div>
        //                         </div>
        //                     </div>
        //                 </div>
        //             </div>
        //         </div>
        //     </div>`;
          const folderCard = `
            <div class="row">
                <div class="card shadow-none border" data-id="${folder.id}">
                    <div class="card-body p-3">
                        <div class="col">
                            <div class="d-flex align-items-center justify-content-between gap-2">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bx bxs-folder h1 mb-0 text-warning"></i>
                                    <h5 class="font-size-15 text-truncate mb-0">
                                        <a href="javascript:void(0);" class="text-body folder-link">${folder.name}</a>
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;

          folderContainer.append(folderCard);
        });

        // <div class="d-flex mt-3">
        //     <div class="overflow-hidden me-auto">
        //         <p class="text-muted text-truncate mb-0">${folder.path}</p>
        //     </div>
        //     <div class="align-self-end ms-2">
        //         <p class="text-muted mb-0 font-size-13">
        //             <i class="mdi mdi-clock"></i>
        //             ${new Date(folder.updated_at).toLocaleString()}
        //         </p>
        //     </div>
        // </div>


        $('.folder-link').on('click', function() {
          const parentId = $(this).closest('.card').data('id');
          const folderName = $(this).text();
          currentPath.push(folderName); // Tambahkan nama folder ke jalur
          currentFolderId = parentId; // Set folder aktif
          folderHistory.push(currentFolderId); // Simpan ID folder saat ini
          fetchFolderData(parentId); // Ambil data folder anak
          updateBreadcrumb(); // Perbarui breadcrumb
          fetchFileData(currentFolderId); // Hanya ambil file dalam folder aktif
        });
      }

      //   BREADCRUMB FOLDER

      function updateBreadcrumb() {
        const breadcrumbList = $("#breadcrumb-list");
        breadcrumbList.empty();

        breadcrumbList.append(`
            <li class="breadcrumb-item">
                <a href="javascript:void(0);" data-index="-1" class="breadcrumb-link">Home</a>
            </li>
        `);

        currentPath.forEach((name, index) => {
          const isActive = index === currentPath.length - 1;

          const breadcrumbItem = isActive ?
            `<li class="breadcrumb-item active" aria-current="page">${name}</li>` :
            `<li class="breadcrumb-item">
                    <a href="javascript:void(0);" data-index="${index}" class="breadcrumb-link">${name}</a>
                  </li>`;

          breadcrumbList.append(breadcrumbItem);
        });

        $(".breadcrumb-link").on("click", function() {
          const index = $(this).data("index");

          if (index === -1) {
            currentPath = [];
            currentFolderId = null;
            folderHistory = [];
            fetchFolderData(null, 2);
          } else {
            currentPath = currentPath.slice(0, index + 1);
            currentFolderId = folderHistory[index];
            fetchFolderData(currentFolderId);
          }

          updateBreadcrumb();
          fetchFileData(currentFolderId);
        });
      }

      updateBreadcrumb()



      // =======================================================================  FILE DATA

      function fetchFileData(parentId = null) {
        $.ajax({
          type: "GET",
          url: "{{ route('filemanager.data') }}",
          data: {
            'filter-folder': parentId,
            'search': currentSearch,
            'sort-extension': currentSort,
            'trash': 1,
          },
          success: function(response) {
            const filteredData = response.data.filter(file => {
              const isOwnFileByOrganization = file.organizations?.some(org => org.id === user
                .organization_id);
              const isOwnFileByUser = file.users?.some(fileUser => fileUser.id === user.id);

              return isOwnFileByOrganization || isOwnFileByUser;
            });

            // console.log(user, 'user');
            // console.log(filteredData, 'filteredData');

            renderFiles(filteredData);
          },
          error: function(xhr, status, error) {
            console.error('Error fetching file data:', error);
          },
        });
      }

      function renderFiles(data) {
        const fileContainer = $("#file-container");
        fileContainer.empty();

        data.forEach(file => {
          //   const fileCard = `
        //         <div class="row">
        //             <div class="card shadow-none border w-100" data-id="${file.id}" data-tag="${file.tag}">
        //                 <div class="card-body p-3">
        //                     <div class="d-flex align-items-center justify-content-between">
        //                         <div class="d-flex align-items-center gap-3 file-info-container">
        //                              <i class="
        //                                 ${file.extension === 'xls' || file.extension === 'xlsx' ? 'bi-file-earmark-excel-fill' :
        //                                 file.extension === 'doc' || file.extension === 'docx' ? 'bi-file-earmark-word-fill' :
        //                                 file.extension === 'ppt' || file.extension === 'pptx' ? 'bi-file-earmark-ppt-fill' :
        //                                 file.extension === 'txt' ? 'bi-file-earmark-text-fill' :
        //                                 file.extension === 'pdf' ? 'bi-file-earmark-pdf-fill' :
        //                                 file.extension === 'zip' || file.extension === 'rar' ? 'bi-file-earmark-zip-fill' :
        //                                 file.type === 'image' ? 'bi-image-fill' :
        //                                 file.link ? 'bi-file-earmark-code-fill' :
        //                                 file.extension === 'mp3' ? 'bi-file-earmark-music-fill' :
        //                                 file.extension === 'mp4' ? 'bi-file-earmark-play-fill' :
        //                                 'bi-file-earmark-fill'
        //                             }" style="
        //                                 font-size: 40px;
        //                                 color: ${
        //                                     file.extension === 'xls' || file.extension === 'xlsx' ? '#068800' :
        //                                     file.extension === 'doc' || file.extension === 'docx' ? '#145adc' :
        //                                     file.extension === 'ppt' || file.extension === 'pptx' ? '#ff9000' :
        //                                     file.extension === 'txt' ? '#0654af' :
        //                                     file.extension === 'pdf' ? '#e10a0a' :
        //                                     file.extension === 'zip' || file.extension === 'rar' ? '#8100ce' :
        //                                     file.type === 'image' ? '#48CFCB' :
        //                                     file.link ? '#B99470' :
        //                                     file.extension === 'mp3' ? '#ffa200' :
        //                                     file.extension === 'mp4' ? '#A02334' :
        //                                     '#666666'
        //                                 };">
        //                             </i>
        //                             <div class="file-info-container-wrapper">
        //                                 <p class="fw-semibold mb-0">${file.name}</p>
        //                                 <p class="text-muted mb-0"> ${
        //                                     file.created_at
        //                                     ? new Date(file.created_at).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })
        //                                     : null
        //                                 }
        //                                     -
        //                                 ${file.size
        //                                 ? file.size < 1000
        //                                     ? file.size + ' B'
        //                                     : file.size < 1000000
        //                                     ? (file.size / 1000).toFixed(2) + ' KB'
        //                                     : file.size < 1000000000
        //                                     ? (file.size / 1000000).toFixed(2) + ' MB'
        //                                     : (file.size / 1000000000).toFixed(2) + ' GB'
        //                                 : '<i class="bi bi-infinity"></i>'
        //                             }</p>
        //                             </div>
        //                         </div>
        //                         <div class="dropdown mb-0">
        //                           <a class="btn btn-link text-muted p-1 mt-n2" role="button" data-bs-toggle="dropdown"
        //                             aria-haspopup="true">
        //                             <i class="mdi mdi-dots-vertical font-size-20 "></i>
        //                           </a>
        //                           <div class="dropdown-menu dropdown-menu-end">
        //                             <a class="dropdown-item" href="#" id="setName"><i class="bi bi-trash3 me-1"></i> Edit</a>
        //                             <a class="dropdown-item" href="#" id="setTag"><i class="bi bi-trash3 me-1"></i> Set Tag</a>
        //                             <a class="dropdown-item" href="#" id="deleteFile"><i class="bi bi-trash3 me-1"></i> Delete</a>
        //                             <a class="dropdown-item" href="#" id="downloadFile"><i class="bi bi-trash3 me-1"></i> Download</a>
        //                           </div>
        //                         </div>
        //                     </div>
        //                 </div>
        //             </div>
        //         </div>
        //         `;
          const fileCard = `
                <div class="row">
                    <div class="card shadow-none border w-100" data-id="${file.id}" data-tag="${file.tag}">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-3 file-info-container">
                                     <i class="
                                        ${file.extension === 'xls' || file.extension === 'xlsx' ? 'bi-file-earmark-excel-fill' :
                                        file.extension === 'doc' || file.extension === 'docx' ? 'bi-file-earmark-word-fill' :
                                        file.extension === 'ppt' || file.extension === 'pptx' ? 'bi-file-earmark-ppt-fill' :
                                        file.extension === 'txt' ? 'bi-file-earmark-text-fill' :
                                        file.extension === 'pdf' ? 'bi-file-earmark-pdf-fill' :
                                        file.extension === 'zip' || file.extension === 'rar' ? 'bi-file-earmark-zip-fill' :
                                        file.type === 'image' ? 'bi-image-fill' :
                                        file.link ? 'bi-file-earmark-code-fill' :
                                        file.extension === 'mp3' ? 'bi-file-earmark-music-fill' :
                                        file.extension === 'mp4' ? 'bi-file-earmark-play-fill' :
                                        'bi-file-earmark-fill'
                                    }" style="
                                        font-size: 40px;
                                        color: ${
                                            file.extension === 'xls' || file.extension === 'xlsx' ? '#068800' :
                                            file.extension === 'doc' || file.extension === 'docx' ? '#145adc' :
                                            file.extension === 'ppt' || file.extension === 'pptx' ? '#ff9000' :
                                            file.extension === 'txt' ? '#0654af' :
                                            file.extension === 'pdf' ? '#e10a0a' :
                                            file.extension === 'zip' || file.extension === 'rar' ? '#8100ce' :
                                            file.type === 'image' ? '#48CFCB' :
                                            file.link ? '#B99470' :
                                            file.extension === 'mp3' ? '#ffa200' :
                                            file.extension === 'mp4' ? '#A02334' :
                                            '#666666'
                                        };">
                                    </i>
                                    <div class="file-info-container-wrapper">
                                        <p class="fw-semibold mb-0">${file.name}</p>
                                        <p class="text-muted mb-0"> ${
                                            file.created_at
                                            ? new Date(file.created_at).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })
                                            : null
                                        }
                                            -
                                        ${file.size
                                        ? file.size < 1000
                                            ? file.size + ' B'
                                            : file.size < 1000000
                                            ? (file.size / 1000).toFixed(2) + ' KB'
                                            : file.size < 1000000000
                                            ? (file.size / 1000000).toFixed(2) + ' MB'
                                            : (file.size / 1000000000).toFixed(2) + ' GB'
                                        : '<i class="bi bi-infinity"></i>'
                                    }</p>
                                    </div>
                                </div>
                                <div class="dropdown mb-0">
                                  <a class="btn btn-link text-muted p-1 mt-n2" role="button" data-bs-toggle="dropdown"
                                    aria-haspopup="true">
                                    <i class="mdi mdi-dots-vertical font-size-20 "></i>
                                  </a>
                                  <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="#" id="downloadFile"><i class="bi bi-trash3 me-1"></i> Download</a>
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                `;

          fileContainer.append(fileCard);
        });
      }


      //    ======================================================================= SEARCH
      $('#search-input').on('input', function() {
        currentSearch = $(this).val();
        fetchFileData(currentFolderId);
      });

      //    ======================================================================= SORT BY DROPDOWN
      $('.dropdown-item').on('click', function() {
        currentSort = $(this).data('value');
        $('#sort-extension').text($(this).text());
        fetchFileData(currentFolderId);
      });


      function openDynamicModal(title, fields, submitText, callback) {
        $('#dynamicModalLabel').text(title);
        $('#dynamicForm button[type="submit"]').text(submitText);
        $('#dynamicFields').empty();

        console.log(fields, 'fields');
        fields.forEach(field => {
          let fieldHtml = '';
          if (field.type === 'tree') {
            fieldHtml = `
                    <div class="mb-3">
                        <label for="${field.name}" class="form-label">${field.label}</label>
                        <span id="${field.name}" name="${field.name}" class="form-control overflow-x-auto">${field.value}</span>
                    </div>
                `;
            if (field.name === 'folder_id') {
              const currentFolderData = folder.filter(folder => folder.id === field.value)[0];
              currentFolderParentId = currentFolderData.parent_id;
            }
          } else {
            fieldHtml = `
                <div class="mb-3">
                    <label for="${field.name}" class="form-label">${field.label}</label>
                    <input type="${field.type}" class="form-control" id="${field.name}" name="${field.name}" ${field.required ? 'required' : ''} ${field.value ? `value="${field.value}"` : ''}>
                </div>
                `;
          }

          //   const fieldHtml = `
        //     <div class="mb-3">
        //         <label for="${field.name}" class="form-label">${field.label}</label>
        //         <input type="${field.type}" class="form-control" id="${field.name}" name="${field.name}" ${field.required ? 'required' : ''} ${field.value ? `value="${field.value}"` : ''}>
        //     </div>
        // `;
          $('#dynamicFields').append(fieldHtml);
          updateFolderTree();
        });

        $('#dynamicForm').off('submit').on('submit', function(event) {
          event.preventDefault();
          const formData = new FormData(this);
          formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
          callback(formData);
        });

        $('#dynamicModal').modal('show');
      }

      function updateFolderTree() {

        const jstreeData = folder.length > 0 ?
          folder.map(item => ({
            id: item.id || "#",
            parent: item.parent_id || "#",
            text: item.name || "Unknown"
          })) : [];

        $('#folder_id').jstree("destroy").empty();

        $('#folder_id').jstree({
          core: {
            data: jstreeData,
            check_callback: true
          },
          plugins: ["wholerow"]
        }).on('ready.jstree', function(e, data) {
          const jstreeInstance = $(this).jstree(true);
          jstreeInstance.close_all();
          if (currentFolderParentId) {
            jstreeInstance.select_node(currentFolderParentId);
            jstreeInstance.open_node(currentFolderParentId);
            folderSelected = currentFolderParentId;
          }
        }).on('select_node.jstree', function(e, data) {
          const selectedNode = data.node.text;
          folderSelected = data.node.id;
        }).on('error.jstree', function(e, error) {
          console.error('jstree error:', error);
        });
      }


      //  ======================================================================== CREATE FOLDER
      $('#createFolder').on('click', function() {
        openDynamicModal('New Folder', [{
          name: 'name',
          label: 'Nama Folder',
          type: 'text',
          required: true
        }], 'Submit', function(formData) {
          formData.append('parent_id', currentFolderId ? currentFolderId : rootFolder.id);
          $.ajax({
            url: "{{ route('filemanager.folder.push') }}",
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
              if (response.success) {
                fetchFolderData(currentFolderId ? currentFolderId : rootFolder.id);
                fetchFileData(currentFolderId ? currentFolderId : rootFolder.id);
                $('#dynamicModal').modal('hide');
              }
            },
            error: function(xhr, status, error) {
              console.error('Error creating folder:', error);
            }
          });
        });
      });


      //  ======================================================================== CREATE FILE
      $('#createFile').on('click', function() {
        openDynamicModal('New File', [{
            name: 'file_name',
            label: 'Nama File',
            type: 'text',
            required: true
          },
          {
            name: 'file',
            label: 'Pilih File',
            type: 'file',
            required: true
          },
        ], 'Submit', function(formData) {
          formData.append('folder_id', currentFolderId ? currentFolderId : rootFolder.id);
          $.ajax({
            url: "{{ route('filemanager.push.file') }}",
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
              if (response.success) {
                fetchFileData(currentFolderId);
                $('#dynamicModal').modal('hide');
              }
            },
            error: function(xhr, status, error) {
              console.error('Error uploading file:', error);
            }
          });
        });
      });

      //    ======================================================================= CREATE LINK
      $('#createLink').on('click', function() {
        openDynamicModal('New Link', [{
            name: 'name',
            label: 'Nama ',
            type: 'text',
            required: true
          },
          {
            name: 'link',
            label: 'Link',
            type: 'text',
            required: true
          },
        ], 'Submit', function(formData) {
          formData.append('folder_id', currentFolderId);
          $.ajax({
            url: "{{ route('filemanager.push.link') }}",
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
              if (response.success) {
                fetchFileData(currentFolderId);
                $('#dynamicModal').modal('hide');
              }
            },
            error: function(xhr, status, error) {
              console.error('Error uploading file:', error);
            }
          });
        });
      });

      //  ======================================================================== DELETE FILE
      $('#file-container').on('click', '#deleteFile', function() {
        const fileId = $(this).closest('.card').data('id');
        const fileName = $(this).closest('.card').find('.fw-semibold').text();

        openDynamicModal('Confirm Delete', [{
          name: 'id',
          label: `Are you sure you want to delete "${fileName}"?`,
          type: 'hidden',
          value: fileId,
          required: true
        }], 'Delete', function(formData) {
          formData.append('data', JSON.stringify([fileId]));
          formData.append('_method', 'DELETE');
          $.ajax({
            url: "{{ route('filemanager.delete') }}",
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
              console.log('Response:', response);
              if (response.success) {
                fetchFileData(currentFolderId);
                $('#dynamicModal').modal('hide');
              } else {
                alert('Failed to delete the file.');
              }
            },
            error: function(xhr, status, error) {
              console.error('Error deleting file:', error);
              alert('An error occurred while deleting the file. Please try again.');
            }
          });
        });
      });

      //  ======================================================================== SET NAME
      $('#file-container').on('click', '#setName', function() {
        const fileId = $(this).closest('.card').data('id');
        const currentName = $(this).closest('.card').find('.fw-semibold').text();

        openDynamicModal('Set File Name', [{
          name: 'name',
          label: 'New File Name',
          type: 'text',
          value: currentName,
          required: true
        }], 'Save Changes', function(formData) {
          formData.append('id', fileId);
          formData.append('folder_id', currentFolderId);
          $.ajax({
            url: "{{ route('filemanager.set.name') }}",
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
              if (response.success) {
                fetchFileData(currentFolderId);
                $('#dynamicModal').modal('hide');
              }
            },
            error: function(xhr, status, error) {
              console.error('Error updating file name:', error);
              alert('An error occurred while updating the file name. Please try again.');
            }
          });
        });
      });

      //  ======================================================================== SET TAG
      $('#file-container').on('click', '#setTag', function() {
        const fileCard = $(this).closest('.card');
        const fileId = fileCard.data('id');
        const currentTag = fileCard.data('tag');

        openDynamicModal('Set Tag', [{
          name: 'tag',
          label: 'New Tag',
          type: 'text',
          value: currentTag,
          required: true
        }], 'Save Changes', function(formData) {
          formData.append('id', fileId);
          formData.append('folder_id', currentFolderId);
          $.ajax({
            url: "{{ route('filemanager.set.tag') }}",
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
              if (response.success) {
                fetchFileData(currentFolderId);
                $('#dynamicModal').modal('hide');
              }
            },
            error: function(xhr, status, error) {
              console.error('Error updating file name:', error);
              alert('An error occurred while updating the file name. Please try again.');
            }
          });
        });
      });

      //  =========================================================================== EDIT FOLDER
      $('#folder-container').on('click', '#editFolder', function() {
        const folderCard = $(this).closest('.card');
        const folderId = folderCard.data('id');
        const folderName = folderCard.find('.folder-link').text();

        openDynamicModal('Edit Folder', [{
          name: 'folder_id',
          label: 'Parent',
          type: 'tree',
          value: folderId,
        }, {
          name: 'name',
          label: 'Name',
          type: 'text',
          value: folderName,
          required: true
        }], 'Save Changes', function(formData) {
          if (folderSelected) {
            formData.append('parent_id', folderSelected);
          }

          const urlFormData = '{{ route('filemanager.folder.push', ':id') }}'.replace(':id', folderId);

          $.ajax({
            url: urlFormData,
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
              if (response.success) {
                showAlert('success', 'Folder updated successfully.');
                fetchFolderData(currentFolderId, 2);
                $('#dynamicModal').modal('hide');
                currentFolderParentId = null;
              } else {
                alert('Failed to update the folder.');
              }
            },
            error: function(xhr, status, error) {
              console.error('Error updating folder:', error);
              alert('An error occurred while updating the folder. Please try again.');
            }
          });
        });
      });

      //  =========================================================================== DELETE FOLDER
      $('#folder-container').on('click', '#deleteFolder', function() {

        const folderCard = $(this).closest('.card');
        const folderId = folderCard.data('id');
        const folderName = folderCard.find('.folder-link').text();

        openDynamicModal('Confirm Delete', [{
          name: 'id',
          label: `Are you sure you want to delete "${folderName}"?`,
          type: 'hidden',
          value: folderId,
          required: true
        }], 'Delete', function(formData) {
          formData.append('data', JSON.stringify([folderId]));
          formData.append('_method', 'DELETE');
          $.ajax({
            url: "{{ route('filemanager.folder.delete') }}",
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
              if (response.success) {
                fetchFileData(currentFolderId);
                $('#dynamicModal').modal('hide');
              } else {
                alert('Failed to delete the file.');
              }
            },
            error: function(xhr, status, error) {
              console.error('Error deleting file:', error);
              alert('An error occurred while deleting the file. Please try again.');
            }
          });
        });
      });

      //    =========================================================================== DOWNLOAD FILE
      $('#file-container').on('click', '#downloadFile', function() {
        const fileId = $(this).closest('.card').data('id');
        let downloadUrl = '{{ route('filemanager.download', ':id') }}'.replace(':id', fileId);
        window.location.href = downloadUrl;
      });

      fetchFolderData(null, 2);
      fetchFileData();

    });
  </script>
@endsection
