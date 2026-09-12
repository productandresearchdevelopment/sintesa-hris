@extends('templates.mobile')

@section('head')
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

    .card {
      border: none;
    }

    .container {
      margin: 0 0;
    }

    #folder-container .card .card-body {
      border-radius: 10px;
      background-color: #f7eed7;
      margin-bottom: 1rem;
      height: auto;
    }

    #folder-container .card h5 {
      word-wrap: break-word;
      white-space: normal;
      overflow: hidden;
      font-weight: 600;
      font-size: 0.9rem;
      font-style: normal-case;
      text-transform: capitalize;
      color: #F0A714
    }

    #file-container .card {
      border-radius: 10px;
      margin-bottom: 1rem;
      height: auto;
      border: 1px solid #ddd;
    }

    #file-container .card .file-info-container-wrapper .filename {
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: normal;
      word-break: break-word;
      line-height: 1.2em;
      max-height: calc(1.2em * 2);
    }

    #file-container .card .file-info-container-wrapper p {
      font-size: 0.9rem;
    }

    #file-container .card .file-info-container-wrapper p:last-child {
      font-size: 0.8rem;
    }

    .breadcrumb {
      margin-top: 14px;
      font-size: 1rem;
      font-weight: 500;
    }

    .breadcrumb .breadcrumb-item .breadcrumb-link {
      color: var(--primary-color)
    }

    a {
      text-decoration: none !important;
    }
  </style>
@endsection

@section('content')
  <div class="px-3" style="min-height: 100%; min-width: 100%; padding-bottom: 100px;">
    <div class="py-3">
      <div class="d-flex align-items-center gap-4" style="cursor: pointer;" onclick="history.back()">
        <i class="bi bi-chevron-left" style="font-size: 1.2rem;font-weight: 500;"></i>
        <p class="m-0" style="font-size: 1.4rem; font-weight: 500;">File Manager</p>
      </div>
    </div>

    <div class="col">
      <div class="row align-items-center mb-2">
        <div class="col-8">
          <div class="position-relative">
            <input id="search-input" type="text" class="form-control bg-light h border-light rounded"
              placeholder="Search..." style="height: 40px; font-size: 1.2rem;">
          </div>
        </div>
        <div class="col-4">
          <div class="dropdown">
            <button class="btn btn-light dropdown-toggle w-100" id="sort-extension" type="button"
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

      <div aria-label="breadcrumb">
        <ol class="breadcrumb" style="padding: 0 3px" id="breadcrumb-list">
        </ol>
      </div>

      <div class="row mt-1" style="padding:0 3px" id="folder-container">
      </div>

      <hr class="mt-2 mb-4" style="border-top: 2px solid #ddd;">
      <div id="file-container" class="mt-3">
      </div>
      <button id="loadMoreButton" class="mt-2 btn btn-primary">Load More</button>
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
      let currentPage = 1;
      const pageSize = 2;

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
          const folderCard = `
            <div class="col-6">
              <div class="card folder-link" data-id="${folder.id}">
                <div class="card-body p-3">
                  <div class="d-flex align-items-center justify-content-between gap-2">
                    <div class="d-flex align-items-center gap-2">
                      <i class="bx bxs-folder h1 mb-0 text-warning"></i>
                      <h5 class="font-size-15 text-truncate mb-0">${folder.name}</h5>
                    </div>
                  </div>
                </div>
              </div>
            </div>`;
          folderContainer.append(folderCard);
        });

        $('.folder-link').on('click', function() {
          const parentId = $(this).closest('.card').data('id');
          const folderName = $(this).text();
          currentPath.push(folderName);
          currentFolderId = parentId;
          folderHistory.push(currentFolderId);
          fetchFolderData(parentId);
          updateBreadcrumb();
          fetchFileData(currentFolderId);
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
            `<li class="breadcrumb-item font-size-12 font-weight-500 ">
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
            if (response.data.length > 0) {
              const filteredData = response.data.filter(file => {
                const isOwnFileByOrganization = file.organizations?.some(org => org.id === user
                  .organization_id);
                const isOwnFileByUser = file.users?.some(fileUser => fileUser.id === user.id);

                return isOwnFileByOrganization || isOwnFileByUser;
              });


              currentPage = 1;
              $('#file-container').empty();

              renderFiles(filteredData, currentPage, pageSize);

              if (filteredData.length > pageSize) {
                $('#loadMoreButton').show();
                $('#loadMoreButton').off('click').on('click', function() {
                  currentPage++;
                  const totalDisplayed = (currentPage - 1) * pageSize;

                  if (totalDisplayed < filteredData.length) {
                    renderFiles(filteredData, currentPage, pageSize);
                  }

                  if (currentPage * pageSize >= filteredData.length) {
                    $(this).hide();
                  }
                });
              } else {
                $('#loadMoreButton').hide();
              }
            } else {
              $('#file-container').html(`
                <div class="card-body">
                    <div class="d-flex flex-column justify-content-center align-items-center gap-2">
                        <img src="{{ asset('/images/nodata.png') }}" alt="No File Found" class="img-fluid" style="max-width: 300px; max-height: 300px;" />
                        <h3 class="text-center font-bold text-black mb-0">No File Found</h3>
                    </div>
                </div>
             `);
              $('#loadMoreButton').hide();
            }
          },
          error: function(xhr, status, error) {
            console.error('Error fetching file data:', error);
          },
        });
      }

      function renderFiles(data, page, size) {
        const start = (page - 1) * size;
        const end = page * size;
        const batch = data.slice(start, end);
        const fileContainer = $("#file-container");

        batch.forEach(file => {
          const isImage = ['jpeg', 'jpg', 'png', 'gif', 'bmp', 'svg', 'webp'].includes(file.extension);
          const isPreviewable = isImage || file.extension === 'pdf' || !!file.link;
          const fileRouteUrl = "{{ route('file', ':id') }}".replace(':id', file.file_id);

          let fileCard = `
            <div class="row px-3 fade-in">
                <div class="card" data-id="${file.id}" data-link="${file.link}" data-file-id="${file.file_id}">
                    <div class="card-body p-2">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-3 file-info-container">
        `;

          if (isImage) {
            fileCard += `
                <img src="${fileRouteUrl}" alt="${file.name}" style="width: 40px; height: 40px; object-fit: cover;" />
            `;
          } else {
            fileCard += `
                <i class="${file.extension === 'xls' || file.extension === 'xlsx' ? 'bi-file-earmark-excel-fill' :
                    file.extension === 'doc' || file.extension === 'docx' ? 'bi-file-earmark-word-fill' :
                    file.extension === 'ppt' || file.extension === 'pptx' ? 'bi-file-earmark-ppt-fill' :
                    file.extension === 'txt' ? 'bi-file-earmark-text-fill' :
                    file.extension === 'pdf' ? 'bi-file-earmark-pdf-fill' :
                    file.extension === 'zip' || file.extension === 'rar' ? 'bi-file-earmark-zip-fill' :
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
                        file.link ? '#B99470' :
                        file.extension === 'mp3' ? '#ffa200' :
                        file.extension === 'mp4' ? '#A02334' :
                        '#666666'
                    };">
                </i>
            `;
          }

          fileCard += `
                                    <div class="file-info-container-wrapper">
                                        <p class="fw-semibold mb-0 filename">${file.name}</p>
                                        <p class="text-muted mb-0"> ${
                                            file.created_at
                                                ? new Intl.DateTimeFormat('en-US', { day: 'numeric', month: 'short', year: 'numeric' }).format(new Date(file.created_at))
                                                : null
                                        }
                                            |
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
                                <div>
                                    <a
                                        class="btn btn-link text-muted p-1 mt-n2"
                                        role="button"
                                        aria-haspopup="true"
                                        href="${file.link ? file.link : fileRouteUrl}"
                                        ${isPreviewable ? 'target="_blank"' : 'download'}
                                    >
                                    <i class="bi ${isPreviewable ? 'bi-eye' : 'bi-download'} me-1 font-size-20 fw-5"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

          const $fileCard = $(fileCard);
          fileContainer.append($fileCard);

          setTimeout(() => {
            $fileCard.removeClass('fade-in');
          }, 5000);
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



      //    =========================================================================== DOWNLOAD FILE
      $('#file-container').on('click', '.card', function(e) {
        if ($(e.target).closest('a').length > 0) return;

        const id = $(this).data('id');
        const fileId = $(this).data('file-id');
        const fileLink = $(this).data('link');
        const fileExtension = $(this).find('.filename').text().split('.').pop().toLowerCase();
        const isPreviewable = ['jpeg', 'jpg', 'png', 'gif', 'bmp', 'svg', 'webp', 'pdf'].includes(
          fileExtension) || fileLink;

        if (isPreviewable) {
          const previewUrl = fileLink ? fileLink : '{{ route('file', ':id') }}'.replace(':id', fileId);
          console.log(previewUrl);
          window.open(previewUrl, '_blank');
        } else {
          const downloadUrl = '{{ route('filemanager.download', ':id') }}'.replace(':id', id);
          const a = document.createElement('a');
          a.href = downloadUrl;
          a.download = '';
          document.body.appendChild(a);
          a.click();
          document.body.removeChild(a);
        }
      });



      fetchFolderData(null, 2);
      fetchFileData();

    });
  </script>
@endsection
