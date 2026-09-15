@extends('headers.head')

@section('header')
  <style>
    .search-container {
      order: 1;
      width: 100%;
      max-width: 100%;
    }

    .search-container input {
      max-width: 100%;
    }

    .category-filter-container {
      order: 2;
      width: 100%;
      max-width: 100%;
    }

    .category-filter-container button {
      width: 100%;
    }

    .bulletin-category {
      font-size: 0.8rem;
      font-weight: bold;
      text-transform: uppercase;
      padding: 0.2rem 0.5rem;
    }

    .highlight-bulletin,
    .side-bulletin {
      position: relative;
    }

    .highlight-bulletin img,
    .side-bulletin img {
      border-radius: 0.5rem;
      object-fit: cover;
      filter: brightness(0.6);
    }

    .highlight-bulletin .text-overlay {
      position: absolute;
      bottom: 1.6rem;
      left: 1.6rem;
      right: 1.6rem;
      color: white;
    }

    .side-bulletin .text-overlay {
      position: absolute;
      bottom: 1rem;
      left: 1rem;
      right: 1rem;
      color: white;
    }

    .highlight-bulletin img {
      width: 100%;
      height: 100%;
    }

    .highlight-title {
      font-size: 0.8rem;
      font-weight: bold;
      overflow: hidden;
      display: -webkit-box;
      -webkit-box-orient: vertical;
      -webkit-line-clamp: 2;
      line-clamp: 2;
      text-overflow: ellipsis;
    }

    .side-bulletin {
      display: flex;
      gap: 1rem;
      margin-bottom: 1rem;
    }

    .side-bulletin:is(:last-child) {
      margin-bottom: 0;
    }

    .side-bulletin img {
      width: 100%;
      height: auto;
      max-height: 300px;
      object-fit: cover;
    }

    .side-bulletin .bulletin-category {
      margin-bottom: 0.5rem;
      display: inline-block;
    }


    .side-bulletin .side-bulletin-title {
      font-size: 0.8rem;
      font-weight: bold;
      overflow: hidden;
      display: -webkit-box;
      -webkit-box-orient: vertical;
      -webkit-line-clamp: 2;
      line-clamp: 2;
      text-overflow: ellipsis;
    }

    .card,
    .side-bulletin,
    .highlight-bulletin {
      cursor: pointer;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover,
    .side-bulletin:hover,
    .highlight-bulletin:hover {
      transform: scale(1.02);
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    #dropZone.dragover {
      border-color: #007bff;
      background-color: #e9f7ff;
    }

    #dropZone {
      height: 200px;
    }

    #previewImage {
      max-width: 300px;
      max-height: 180px;
      object-fit: cover;
    }

    @media (min-width: 768px) {
      .search-container {
        order: 2;
        max-width: 300px;
      }

      .search-container input {
        max-width: 300px;
      }

      .category-filter-container {
        order: 1;
        width: max-content;
        max-width: max-content;
      }

      .category-filter-container button {
        width: max-content;
      }

      .highlight-title {
        font-size: 1.2rem;
        overflow: visible;
        display: block;
        -webkit-box-orient: unset;
        -webkit-line-clamp: unset;
        line-clamp: unset;
        text-overflow: unset;
      }

      .side-bulletin .side-bulletin-title {
        overflow: visible;
        display: block;
        -webkit-box-orient: unset;
        -webkit-line-clamp: unset;
        line-clamp: unset;
        text-overflow: unset;
      }

      #dropZone {
        height: 300px;
      }

      #previewImage {
        max-width: 400px;
        max-height: 300px;
      }
    }
  </style>
@endsection

@section('body')
  <div class="bg-white p-4 main-container" style="min-height: 100%; min-width: 100%;">

    <div
      class="mb-4 d-flex flex-column flex-md-row justify-content-center justify-content-md-between align-items-center gap-3">
      <div class="d-flex align-items-center gap-2">
        @if ($user->role->name === 'ADMIN BULLETIN')
          <button id="createBulletinButton"
            class="btn btn-sm btn-primary d-flex align-items-center justify-content-center gap-1" type="button"
            data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="bi bi-plus-lg d-flex align-items-center justify-content-center"></i>
            Create
          </button>
        @endif
        <div class="dropdown category-filter-container">
          <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="categoryFilterDropdown"
            data-bs-toggle="dropdown" aria-expanded="false">
            Category
          </button>
          <ul class="dropdown-menu" aria-labelledby="categoryFilterDropdown" id="categoryFilter"
            style="box-shadow:0 2px 4px rgba(0, 0, 0, 0.1);">
            <li><a class="dropdown-item" href="#" data-category=""="all">All</a></li>
            @foreach ($category as $item)
              <li><a class="dropdown-item" href="#" data-category="{{ $item['id'] }}">{{ $item['name'] }}</a>
              </li>
            @endforeach
          </ul>
        </div>
      </div>

      <div class="position-relative search-container">
        <input type="text" class="form-control ps-5" name="query" id="searchInput" placeholder="Search..."
          style="border-radius: 20px;">
        <i class="bi bi-search text-muted position-absolute"
          style="left: 15px; top: 47%; transform: translateY(-50%); font-size: 1.2rem;"></i>
      </div>
    </div>

    <div class="row bulletin-section mb-3 mb-lg-4">
      <!-- Highlight Bulletin -->
      <div class="col-md-8 mb-3 mb-md-0">
        <div id="highlight-bulletin" class="highlight-bulletin">
          <img id="highlight-cover" src="" alt="Highlight Image">
          <div class="text-overlay">
            <span id="highlight-category" class="bulletin-category">Category</span>
            <div id="highlight-title" class="highlight-title text-white mt-2">Title Goes Here</div>
            <div id="highlight-description" class="text-white d-none d-lg-block">Description</div>
            <div class="highlight-actions mt-2">
              <button id="highlight-read-more-button" class="btn btn-sm btn-info">Read More</button>
              <button id="highlight-edit-button" class="btn btn-sm btn-warning">Edit</button>
              <button id="highlight-delete-button" class="btn btn-sm btn-danger">Delete</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Side Bulletins -->
      <div class="col-md-4">
        <div id="side-bulletins" class="side-bulletin-list">
          <!-- Side bulletins go here -->
        </div>
      </div>
    </div>

    <!-- Grid List -->
    <div class="row" id="bulletin-list" class="bulletin-list">
      <!-- Bulletins grid -->
    </div>

    <div class="text-center mt-4">
      <button id="loadMoreButton" class="btn btn-primary">Load More</button>
    </div>

  </div>

  <div id="bulletinModal" class="modal fade" tabindex="-1" aria-labelledby="bulletinModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 id="bulletinModalTitle" class="modal-title"></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <h5 id="bulletinModalLabel" class="modal-title text-center mb-2"></h5>
          <div id="modalCategory"
            style="font-size: 0.8rem; font-weight: bold; text-transform: uppercase; padding: 0.2rem 0.5rem; max-width: max-content;"
            class="text-center mx-auto mb-2"></div>
          <div id="modalAuthorTime" style="font-size: 0.9rem; color: #666; font-weight: normal; text-align: center;"
            class="mb-4"></div>
          <div id="modalImageContainer" style="max-width: 500px; max-height: 300px;" class="mx-auto mb-4">
            <img id="modalImage" src="" alt="Bulletin Image" class="w-100 h-100"
              style="object-fit: cover; object-position: center; max-width: 500px; max-height: 300px;">
          </div>
          <div id="modalDescription"></div>

          <!-- Form for Create and Update -->
          <div id="modalForm" style="display: none;">
            <form id="bulletinForm">
              @csrf
              <div class="mb-3">
                <label for="bulletinTitle" class="form-label">Title<span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="bulletinTitle" name="title" required>
              </div>
              <div class="mb-3">
                <label for="bulletinDescription" class="form-label">Description</label>
                <input type="text" class="form-control" id="bulletinDescription" name="description">
              </div>
              <div class="mb-3">
                <label for="bulletinCategory" class="form-label">Category<span class="text-danger">*</span></label>
                <select class="form-select" id="bulletinCategory" name="category_id" required>
                  <option value="">Select Category</option>
                  @foreach ($category as $item)
                    <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                  @endforeach
                </select>
              </div>
              <div class="mb-3">
                <label for="bulletinContent" class="form-label">Content<span class="text-danger">*</span></label>
                <textarea class="form-control" id="bulletinContent" rows="3" name="content" required></textarea>
              </div>
              <div class="mb-3">
                <label class="form-label">Cover Image<span class="text-danger">*</span></label>
                <div id="dropZone"
                  class="border p-3 text-center d-flex flex-column justify-content-center align-items-center"
                  style="border: 2px dashed #ccc; cursor: pointer;">
                  <p id="dropZoneText" class="mb-0">Drag & Drop Image Here or Click to Select</p>
                  <img id="previewImage" src="" alt="Preview Image" class="w-100 d-none">
                </div>
                <input type="file" class="form-control d-none" id="bulletinImage" name="file" accept="image/*">
              </div>
              <button type="submit" class="btn btn-primary">Save</button>
            </form>
          </div>

          <!-- Delete confirmation -->
          <div id="modalDeleteConfirmation" style="display: none;">
            <p>Are you sure you want to delete this bulletin?</p>
            <div class="modal-footer pb-0">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="button" id="confirmDeleteButton" class="btn btn-danger">Delete</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      $('#bulletinContent').summernote({
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'italic', 'underline', 'clear']],
          ['fontname', ['fontname']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['height', ['height']],
          ['table', ['table']],
          ['insert', ['link', 'picture', 'hr']],
          ['view', ['fullscreen', 'codeview']],
          ['mybutton', ['myVideo']]
        ],
        disableDragAndDrop: true,
        buttons: {
          myVideo: function(context) {
            var ui = $.summernote.ui;
            var button = ui.button({
              contents: '<i class="bi bi-camera-video-fill" style="font-size: 1.2rem;"></i>',
              tooltip: 'Insert Video',
              click: function() {
                var url = prompt('Enter YouTube or Vimeo video URL:');

                if (!url) {
                  alert('URL cannot be empty.');
                  return;
                }

                var youtubeRegex = /^(https?:\/\/)?(www\.)?(youtube\.com\/watch\?v=|youtu\.be\/)/;
                var vimeoRegex = /^(https?:\/\/)?(www\.)?vimeo\.com\/\d+/;

                if (youtubeRegex.test(url)) {
                  var videoId = url.includes('youtu.be') ?
                    url.split('youtu.be/')[1].split('?')[0] :
                    new URLSearchParams(new URL(url).search).get('v');

                  if (videoId) {
                    url = `https://www.youtube.com/embed/${videoId}?rel=0&autoplay=0`;
                  } else {
                    alert('Invalid YouTube URL.');
                    return;
                  }
                } else if (vimeoRegex.test(url)) {
                  // Vimeo URL tetap sama
                } else {
                  alert('Only YouTube or Vimeo URLs are supported.');
                  return;
                }

                var div = document.createElement('div');
                div.classList.add('embed-container');
                var iframe = document.createElement('iframe');
                iframe.src = url;
                iframe.setAttribute('frameborder', 0);
                iframe.setAttribute('width', '100%');
                iframe.setAttribute('height', '315');
                iframe.setAttribute('allowfullscreen', true);
                div.appendChild(iframe);
                context.invoke('editor.insertNode', div);
              }
            });

            return button.render();
          }
        },
        height: 300
      });

      let currentPage = 1;
      let bulletins = [];
      let currentQuery = '';
      let currentCategory = 'all';
      let isEditMode = false;
      let editBulletinId = null;

      const sideItemsDesktop = 3;
      const itemsPerPageMobile = 3;
      const itemsPerPageDesktopGrid = 6;
      const user = @json($user);

      function lightenColor(color, percent) {
        if (!color || typeof color !== 'string') {
          return "#e0f2fe";
        }
        let cleanColor = color.replace("#", "");
        if (cleanColor.length === 3) {
          cleanColor = cleanColor.split('').map(c => c + c).join('');
        }
        let num = parseInt(cleanColor, 16);
        if (isNaN(num)) return "#e0f2fe";
        let r = (num >> 16) + percent * 255;
        let g = (num >> 8 & 0x00FF) + percent * 255;
        let b = (num & 0x0000FF) + percent * 255;

        r = Math.min(255, Math.max(0, r));
        g = Math.min(255, Math.max(0, g));
        b = Math.min(255, Math.max(0, b));

        return "#" + (1 << 24 | r << 16 | g << 8 | b).toString(16).slice(1);
      }

      function formatDate(dateString) {
        const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        const date = new Date(dateString);
        const day = date.getDate();
        const month = months[date.getMonth()];
        const year = date.getFullYear();
        return `${day} ${month} ${year}`;
      }

      function renderHighlight(bulletin) {
        const coverSrc = bulletin.cover_image_id ?
          '{{ route('file', ':id') }}'.replace(':id', bulletin.cover_image_id) :
          '{{ asset('images/noimage.png') }}';
        $('#highlight-cover').attr('src', coverSrc);
        const catName = bulletin?.category?.name || 'Uncategorized';
        const catColor = bulletin?.category?.color || '#0073e6';
        $('#highlight-category').text(catName);
        $('#highlight-category').css({
          'background-color': lightenColor(catColor, 0.4),
          'color': catColor
        });
        $('#highlight-title').text(bulletin.title);
        $('#highlight-description').text(bulletin.description || "");

        if (bulletin?.created_by?.id === user?.id && user?.role?.name === 'ADMIN BULLETIN') {
          $('#highlight-edit-button').show().addClass('edit-bulletin').data('bulletin', JSON.stringify(bulletin));
          $('#highlight-delete-button').show().addClass('delete-bulletin').data('bulletin', JSON.stringify(
            bulletin));
        } else {
          $('#highlight-edit-button').hide();
          $('#highlight-delete-button').hide();
        }

        $('#highlight-read-more-button').addClass('read-more-bulletin').data('bulletin', JSON.stringify(bulletin));
      }


      function renderGridBulletins(data) {
        data.forEach(bulletin => {
          const bulletinHTML = createBulletinHTML(bulletin, 'grid');
          const element = $(bulletinHTML).addClass('fade-in');
          $('#bulletin-grid').append(element);
        });
      }

      function renderSideBulletins(data) {
        data.forEach(bulletin => {
          const sideBulletinHTML = createBulletinHTML(bulletin, 'side');
          const element = $(sideBulletinHTML).addClass('fade-in');
          $('#side-bulletins').append(element);
        });
      }

      function createBulletinHTML(bulletin, type) {
        const bulletinImage = bulletin.cover_image_id ?
          '{{ route('file', ':id') }}'.replace(':id', bulletin.cover_image_id) :
          '{{ asset('images/noimage.png') }}';
        const categoryColor = bulletin?.category?.color || '#0073e6';
        const categoryName = bulletin?.category?.name || 'Uncategorized';
        const lighterCategoryColor = lightenColor(categoryColor, 0.4);


        if (type === 'side') {
          return `
            <div class="side-bulletin">
                <img src="${bulletinImage}" alt="Side Image">
                <div class="text-overlay">
                    <span class="bulletin-category" style="background-color: ${lighterCategoryColor}; color: ${categoryColor};">${categoryName}</span>
                    <div class="side-bulletin-title">${bulletin.title}</div>
                    <div class="side-actions mt-2">
                        <button id="side-read-more-button" class="btn btn-sm btn-info read-more-bulletin" data-id="${bulletin.id}">Read More</button>
                        ${bulletin?.created_by?.id === user?.id && user?.role?.name === 'ADMIN BULLETIN' ? `<button id="side-edit-button" class="btn btn-sm btn-warning edit-bulletin" data-id="${bulletin.id}">Edit</button>
                                                                                                                                                <button id="side-delete-button" class="btn btn-sm btn-danger delete-bulletin" data-id="${bulletin.id}">Delete</button>` : ''}
                    </div>
                </div>
            </div>`;
        } else {
          return `
            <div class="col-md-4">
                <div class="card">
                    <div class="position-relative">
                        <img src="${bulletinImage}" class="card-img-top" alt="Bulletin Image">
                        <span class="bulletin-category position-absolute" style="top: 10px; right: 10px; background-color: ${lighterCategoryColor}; color: ${categoryColor}; padding: 0.3rem 0.5rem;">${categoryName}</span>
                    </div>
                    <div class="card-body p-3">
                        <div class="text-muted">${formatDate(bulletin.created_at)}</div>
                        <div class="card-title">${bulletin.title}</div>
                        <div class="card-actions mt-2">
                            <button id="card-read-more-button" class="btn btn-sm btn-info read-more-bulletin" data-id="${bulletin.id}">Read More</button>
                            ${bulletin?.created_by?.id === user?.id && user?.role?.name === 'ADMIN BULLETIN' ? `<button id="card-edit-button" class="btn btn-sm btn-warning edit-bulletin" data-id="${bulletin.id}">Edit</button>
                                                                                                                                                    <button id="card-delete-button" class="btn btn-sm btn-danger delete-bulletin" data-id="${bulletin.id}">Delete</button>` : ''}
                        </div>
                    </div>
                </div>
            </div>`;
        }
      }

      function toggleComponentsBasedOnQuery() {
        if (currentQuery || currentCategory !== 'all') {
          $('#highlight-bulletin').hide();
          if (isMobile()) {
            $('#bulletin-list').hide();
            $('#side-bulletins').show();
          } else {
            $('#side-bulletins').hide();
            $('#bulletin-list').show();
          }
        } else {
          $('#highlight-bulletin').show();
          $('#bulletin-list, #side-bulletins').show();
        }
      }

      function loadMore() {
        const isMobileView = isMobile();
        let startIndex, endIndex;

        if (isMobileView) {
          startIndex = currentPage * itemsPerPageMobile;
          endIndex = startIndex + itemsPerPageMobile;
        } else {
          startIndex = currentPage * itemsPerPageDesktopGrid + sideItemsDesktop;
          endIndex = startIndex + itemsPerPageDesktopGrid;
        }

        const dataToDisplay = bulletins.slice(startIndex, endIndex);

        if (isMobileView) {
          renderSideBulletins(dataToDisplay);
        } else {
          renderGridBulletins(dataToDisplay);
        }

        if (endIndex >= bulletins.length) {
          $('#loadMoreButton').hide();
        } else {
          $('#loadMoreButton').show();
        }

        currentPage++;
      }

      function fetchBulletins() {
        showLoading();
        $.ajax({
          url: '{{ route('bulletin.data') }}',
          type: 'GET',
          data: {
            query: currentQuery,
            trash: 1,
          },
          success: function(data) {

            if (data.data.length > 0) {
              toggleComponentsBasedOnQuery();

              const filteredData = currentCategory !== 'all' && currentCategory ?
                data.data.filter(bulletin => bulletin.category.id === currentCategory) :
                data.data;

              if (filteredData.length > 0) {
                if (currentQuery === '' || currentQuery === 'all') {
                  renderHighlight(filteredData[0]);
                }

                bulletins = currentQuery || currentCategory !== 'all' ? filteredData : filteredData.slice(1);

                if (currentQuery || currentCategory !== 'all') {
                  if (isMobile()) {
                    renderSideBulletins(bulletins.slice(0, itemsPerPageMobile));
                  } else {
                    renderGridBulletins(bulletins.slice(0, itemsPerPageDesktopGrid));
                  }
                } else {
                  if (isMobile()) {
                    renderSideBulletins(bulletins.slice(0, itemsPerPageMobile));
                  } else {
                    renderSideBulletins(bulletins.slice(0, sideItemsDesktop));
                    renderGridBulletins(bulletins.slice(sideItemsDesktop, sideItemsDesktop +
                      itemsPerPageDesktopGrid));
                  }
                }

                if (bulletins.length > currentPage * (isMobile() ? itemsPerPageMobile :
                    itemsPerPageDesktopGrid)) {
                  $('#loadMoreButton').show();
                } else {
                  $('#loadMoreButton').hide();
                }
              } else {
                $('#highlight-bulletin').hide();
                $('#side-bulletins').hide();
                $('#loadMoreButton').hide();
                $('#bulletin-list').html(`
                        <div class="card-body">
                            <div class="d-flex flex-column justify-content-center align-items-center gap-2">
                                <img src="{{ asset('/images/nodata.png') }}" alt="No Bulletin Found" class="img-fluid" style="max-width: 300px; max-height: 300px;" />
                                <h3 class="text-center font-bold text-black mb-0">No Bulletin Found</h3>
                            </div>
                        </div>
                    `);
              }
            } else {
              $('#highlight-bulletin').hide();
              $('#side-bulletins').hide();
              $('#loadMoreButton').hide();
              $('#bulletin-list').html(`
                    <div class="card-body">
                      <div class="d-flex flex-column justify-content-center align-items-center gap-2">
                        <img src="{{ asset('/images/nodata.png') }}" alt="No Bulletin Found" class="img-fluid" style="max-width: 300px; max-height: 300px;" />
                        <h3 class="text-center font-bold text-black mb-0">No Bulletin Found</h3>
                      </div>
                    </div>
                `);
            }
          },
          error: function() {
            alert('Failed to load data.');
          },
          complete: function() {
            hideLoading();
          }
        });
      }

      function renderComponents() {
        $('#side-bulletins').empty();
        $('#bulletin-list').empty();
        $('#loadMoreButton').show();

        if (isMobile()) {
          fetchBulletins();
        } else {
          fetchBulletins();
        }
      }

      const debouncedRenderComponents = debounce(renderComponents, 500);
      window.addEventListener('resize', debouncedRenderComponents);
      renderComponents();

      $('#loadMoreButton').click(loadMore);

      $('#searchInput').on('input', debounce(function() {
        currentQuery = $(this).val().trim();
        currentPage = 1;
        bulletins = [];
        $('#side-bulletins').empty();
        $('#bulletin-list').empty();
        $('#loadMoreButton').hide();
        fetchBulletins();
      }, 500));

      $(document).on('click', '#categoryFilter .dropdown-item', function() {
        currentCategory = $(this).data('category') || 'all';
        $(this).closest('.dropdown').find('.btn').text($(this).text());
        currentPage = 1;
        bulletins = [];
        $('#side-bulletins').empty();
        $('#bulletin-list').empty();
        $('#loadMoreButton').hide();
        fetchBulletins();
      });

      $(document).on('click', '.read-more-bulletin', function() {
        const bulletinId = $(this).data('id');
        const bulletinData = bulletinId ? bulletins.filter(bulletin => bulletin.id === bulletinId)[0] :
          JSON.parse($(this).data('bulletin'));

        if (!bulletinData) {
          console.error('Bulletin data not found');
          return;
        }

        const image = `{{ route('file', ':id') }}`.replace(':id', bulletinData.cover_image_id);
        const formattedDate = new Date(bulletinData.created_at).toLocaleDateString('en-US', {
          weekday: 'long',
          year: 'numeric',
          month: 'long',
          day: '2-digit',
        });
        const formattedTime = new Date(bulletinData.created_at).toLocaleTimeString('en-US', {
          hour: '2-digit',
          minute: '2-digit',
          hour12: true,
        });
        const authorTimeText = `${bulletinData.created_by.name} - ${formattedDate} | ${formattedTime}`;

        $('#bulletinModalLabel').show();
        // $('#modalImageContainer').show();
        $('#modalImageContainer').hide();
        $('#modalCategory').show();
        $('#modalDescription').show();
        $('#modalAuthorTime').show();

        $('.modal-dialog').removeClass('modal-dialog-centered').addClass(
          'modal-fullscreen modal-dialog-scrollable');

        $('#bulletinModalTitle').hide();
        $('#modalForm').hide();
        $('#modalDeleteConfirmation').hide();

        $('#bulletinModalLabel').text(bulletinData.title);
        $('#modalImage').attr('src', image);
        $('#modalCategory').text(bulletinData.category.name).css('background-color', lightenColor(bulletinData
          .category.color, 0.4)).css('color', bulletinData.category.color);
        $('#modalDescription').html(bulletinData.content || "No content available");
        $('#modalAuthorTime').text(authorTimeText);

        $('#bulletinModal').modal('show');
      });

      const dropZone = $('#dropZone');
      const inputFile = $('#bulletinImage');
      const previewImage = $('#previewImage');

      dropZone.on('click', () => inputFile.click());

      dropZone.on('dragover', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).addClass('dragover');
      });

      dropZone.on('dragleave', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).removeClass('dragover');
      });

      dropZone.on('drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).removeClass('dragover');
        const files = e.originalEvent.dataTransfer.files;
        handleFile(files[0]);
      });

      inputFile.on('change', function() {
        if (this.files && this.files[0]) {
          handleFile(this.files[0]);
        }
      });

      function handleFile(file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          previewImage.attr('src', e.target.result).removeClass('d-none');
          $('#dropZoneText').hide();
        };
        reader.readAsDataURL(file);
      }

      $('#createBulletinButton').click(function() {
        isEditMode = false;
        editBulletinId = null;

        $('#bulletinModalLabel').hide();
        $('#modalImageContainer').hide();
        $('#modalCategory').hide();
        $('#modalDescription').hide();
        $('#modalAuthorTime').hide();

        $('.modal-dialog').removeClass('modal-dialog-centered').addClass(
          'modal-fullscreen modal-dialog-scrollable');

        $('#bulletinModalTitle').show().text('Create Bulletin');
        $('#modalForm').show();
        $('#modalDeleteConfirmation').hide();

        $('#bulletinForm')[0].reset();
        $('#bulletinContent').summernote('code', '');
        $('#bulletinModal').modal('show');

        previewImage.addClass('d-none').attr('src', '');
        $('#dropZoneText').show();
      });

      $(document).on('click', '.edit-bulletin', function() {
        const bulletinId = $(this).data('id');
        const bulletinData = bulletinId ? bulletins.filter(bulletin => bulletin.id === bulletinId)[0] :
          JSON.parse($(this).data('bulletin'));

        isEditMode = true;
        editBulletinId = bulletinData.id;

        if (!bulletinData) {
          console.error('Bulletin data not found');
          return;
        }

        $('.modal-dialog').removeClass('modal-dialog-centered').addClass(
          'modal-fullscreen modal-dialog-scrollable');

        $('#bulletinModalLabel').hide();
        $('#modalImageContainer').hide();
        $('#modalCategory').hide();
        $('#modalDescription').hide();
        $('#modalAuthorTime').hide();

        $('#bulletinModalTitle').show().text('Edit Bulletin');
        $('#modalForm').show();
        $('#modalDeleteConfirmation').hide();

        $('#bulletinForm')[0].reset();

        $('#bulletinTitle').val(bulletinData.title);
        $('#bulletinCategory').val(bulletinData.category_id);
        $('#bulletinContent').summernote('code', bulletinData.content || '');
        $('#bulletinDescription').val(bulletinData.description);

        if (bulletinData.cover_image_id) {
          const imageUrl = `{{ route('file', ':id') }}`.replace(':id', bulletinData.cover_image_id);
          previewImage.attr('src', imageUrl).removeClass('d-none');
          $('#dropZoneText').hide();
        } else {
          previewImage.addClass('d-none').attr('src', '');
          $('#dropZoneText').show();
        }

        $('#bulletinModal').modal('show');
      });

      $(document).on('click', '.delete-bulletin', function() {
        $('#modalForm').hide();
        $('#bulletinModalLabel').hide();
        $('#modalImageContainer').hide();
        $('#modalCategory').hide();
        $('#modalDescription').hide();
        $('#modalAuthorTime').hide();

        const bulletinId = $(this).data('id');
        const bulletinData = bulletinId ? bulletins.filter(bulletin => bulletin.id === bulletinId)[0] : JSON
          .parse($(this).data('bulletin'));
        const deleteBulletinIds = [bulletinData.id];

        $('#bulletinModalTitle').show().text('Delete Bulletin');
        $('#modalDeleteConfirmation').show();

        $('.modal-dialog').removeClass('modal-fullscreen modal-dialog-scrollable').addClass(
          'modal-dialog-centered');

        const formData = new FormData();
        formData.append('data', JSON.stringify(deleteBulletinIds));
        formData.append('_method', 'DELETE');
        formData.append('_token', '{{ csrf_token() }}');

        $('#confirmDeleteButton').off('click').on('click', function() {
          $.ajax({
            url: `{{ route('bulletin.delete') }}`,
            method: 'POST',
            contentType: false,
            processData: false,
            data: formData,
            success: function(response) {
              $('#side-bulletins').empty();
              $('#bulletin-list').empty();
              fetchBulletins();
              $('#bulletinModal').modal('hide');
              showAlert('success', 'Bulletin deleted successfully.');
            },
            error: function(xhr) {
              try {
                const response = JSON.parse(xhr.responseText);
                const errorMessage = response.message ||
                  'An error occurred while deleting the bulletin.';
                showAlert('danger', errorMessage);
              } catch (e) {
                console.error('Error parsing JSON response:', xhr.responseText);
                showAlert('danger', 'An error occurred while deleting the bulletin.');
              }
            }
          });
        });

        $('#bulletinModal').modal('show');
      });

      $('#bulletinForm').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        let ajaxUrl = isEditMode ?
          `{{ route('bulletin.update') }}` :
          `{{ route('bulletin.create') }}`;

        if (isEditMode) {
          formData.append('_method', 'PUT');
          formData.append('id', editBulletinId);
        }

        $.ajax({
          url: ajaxUrl,
          method: 'POST',
          data: formData,
          contentType: false,
          processData: false,
          success: function(response) {
            $('#side-bulletins').empty();
            $('#bulletin-list').empty();
            $('#bulletinModal').modal('hide');
            $('#bulletinForm')[0].reset();
            fetchBulletins();
            const successMessage = isEditMode ? 'Bulletin updated successfully.' :
              'Bulletin created successfully.';
            showAlert('success', successMessage);
          },
          error: function(xhr) {
            try {
              const response = JSON.parse(xhr.responseText);
              const message = response.message || 'An error occurred.';
              console.error(message);
              showAlert('danger', message);
            } catch (e) {
              console.error('Failed to parse JSON response:', e);
              showAlert('danger', 'An error occurred.');
            }
          }
        });
      });

    });
  </script>
@endsection
