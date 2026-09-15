@extends('templates.mobile')

@section('head')
  <style>
    html, body {
      background-color: #ffffff !important;
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    .bulletin-page-wrapper {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      padding-bottom: 40px;
    }

    .bulletin-header-banner {
      background: linear-gradient(135deg, #0073e6 0%, #00a651 100%);
      padding: 16px 20px 44px 20px;
      color: #ffffff;
      position: relative;
      border-bottom-left-radius: 28px;
      border-bottom-right-radius: 28px;
      box-shadow: 0 10px 30px rgba(0, 115, 230, 0.2);
    }

    .top-action-bar {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .btn-back-link {
      width: 38px;
      height: 38px;
      border-radius: 12px;
      background: rgba(255, 255, 255, 0.2);
      backdrop-filter: blur(8px);
      border: none;
      color: #ffffff;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      text-decoration: none;
      transition: all 0.2s ease;
      cursor: pointer;
    }

    .btn-back-link:active {
      transform: scale(0.92);
      background: rgba(255, 255, 255, 0.3);
    }

    .header-page-title {
      font-size: 17px;
      font-weight: 700;
      letter-spacing: -0.3px;
      margin: 0;
      color: #ffffff;
    }

    .content-body {
      padding: 0 16px;
      margin-top: -24px;
      z-index: 10;
      position: relative;
    }

    @media (min-width: 769px) {
      .bulletin-header-banner {
        display: none !important;
      }
      .content-body {
        margin-top: 0 !important;
        padding: 0 !important;
        max-width: 1000px;
        margin: 0 auto !important;
      }
    }

    /* Filter Card */
    .search-filter-card {
      background: #ffffff;
      border-radius: 20px;
      padding: 16px;
      border: 1px solid #f1f5f9;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
      margin-bottom: 16px;
    }

    .search-box-wrapper {
      position: relative;
    }

    .search-input {
      border-radius: 14px !important;
      padding-left: 38px !important;
      height: 44px !important;
      border: 1px solid #e2e8f0 !important;
      font-size: 13.5px !important;
      background: #f8fafc !important;
    }

    .search-input:focus {
      background: #ffffff !important;
      border-color: #0073e6 !important;
      box-shadow: 0 0 0 3px rgba(0, 115, 230, 0.15) !important;
    }

    .search-icon {
      left: 12px;
      top: 50%;
      transform: translateY(-50%);
      font-size: 15px;
      color: #94a3b8;
      position: absolute;
      pointer-events: none;
    }

    .filter-container-scroll {
      display: flex;
      gap: 8px;
      overflow-x: auto;
      padding-bottom: 4px;
      scrollbar-width: none;
    }
    .filter-container-scroll::-webkit-scrollbar {
      display: none;
    }

    .button-filter {
      background: #f8fafc;
      color: #64748b;
      border: 1px solid #e2e8f0;
      border-radius: 50px;
      padding: 6px 18px;
      font-size: 12.5px;
      font-weight: 700;
      white-space: nowrap;
      transition: all 0.2s ease;
      cursor: pointer;
    }

    .button-filter:hover,
    .button-filter.active {
      background: #0073e6;
      color: #ffffff;
      border-color: #0073e6;
      box-shadow: 0 4px 12px rgba(0, 115, 230, 0.2);
    }

    /* Bulletin Item Card */
    .bulletin-item-card {
      background: #ffffff;
      border-radius: 20px;
      border: 1px solid #f1f5f9;
      box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
      margin-bottom: 12px;
      overflow: hidden;
      transition: all 0.2s ease;
      cursor: pointer;
    }

    .bulletin-item-card:active {
      transform: scale(0.98);
      background: #f8fafc;
    }

    .bulletin-cover-img-box {
      width: 100%;
      height: 95px;
      background: #f1f5f9;
      overflow: hidden;
    }

    .bulletin-cover-img-box img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .bulletin-cat-pill {
      font-size: 10.5px;
      font-weight: 800;
      color: #0073e6;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      display: block;
    }

    .bulletin-card-title {
      font-size: 14px;
      font-weight: 800;
      color: #0f172a;
      line-height: 1.3;
      margin: 2px 0 4px 0;
    }

    .text-truncate-2 {
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }
  </style>
@endsection

@section('content')
  <div class="bulletin-page-wrapper">
    <div class="bulletin-header-banner">
      <div class="top-action-bar">
        <a href="{{ route('main') }}" class="btn-back-link">
          <i class="bi bi-arrow-left"></i>
        </a>
        <h1 class="header-page-title">Company Bulletin</h1>
        <div style="width: 38px;"></div>
      </div>
    </div>

    <div class="content-body">
      <!-- Search & Category Filter Card -->
      <div class="search-filter-card mb-3">
        <div class="search-box-wrapper mb-3">
          <input type="text" class="form-control search-input" name="query" id="searchInput" placeholder="Search news, training, announcement...">
          <i class="bi bi-search search-icon"></i>
        </div>

        <div class="filter-container-scroll">
          <button class="button-filter active" data-category="all">All</button>
          @foreach ($category as $item)
            <button class="button-filter" data-category="{{ $item['id'] }}">{{ $item['name'] }}</button>
          @endforeach
        </div>
      </div>

      <!-- Bulletin List Container -->
      <div id="bulletin-list"></div>

      <div class="text-center mt-3 mb-4">
        <button id="loadMoreButton" class="btn btn-primary rounded-pill px-4 py-2 fw-bold" style="display: none; font-size: 13px;">Load More Articles</button>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      const userLoginHtml = '{{ auth()->user() }}';
      const userLoginJson = userLoginHtml.replace(/&quot;/g, '\"');
      const userLogin = JSON.parse(userLoginJson);

      let currentCategory = 'all';
      let currentQuery = '';
      let category = @json($category);
      let currentPage = 1;
      const pageSize = 10;

      function formatDate(dateString) {
        const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        const date = new Date(dateString);
        const day = date.getDate();
        const month = months[date.getMonth()];
        const year = date.getFullYear();
        return `${day} ${month} ${year}`;
      }

      function toNormalCase(name) {
        if (!name) return 'HRGA Admin';
        return name
          .split(' ')
          .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
          .join(' ');
      }

      $('.button-filter').click(function() {
        $('.button-filter').removeClass('active');
        $(this).addClass('active');

        currentCategory = $(this).data('category');
        fetchBulletins();
      });

      $('#searchInput').on('input', function() {
        currentQuery = $(this).val();
        fetchBulletins();
      });

      function fetchBulletins() {
        $.ajax({
          url: '{{ route('bulletin.data') }}',
          type: 'GET',
          data: {
            query: currentQuery,
            trash: 1,
          },
          success: function(data) {
            const filteredBulletins = data.data.filter(bulletin => {
              if (!bulletin.category || !bulletin.category.organizations) return true;
              return bulletin.category.organizations.some(org => org.id === userLogin.organization_id);
            });

            if (filteredBulletins.length > 0) {
              const filteredData = currentCategory !== 'all' ?
                filteredBulletins.filter(bulletin => bulletin.category && bulletin.category.id == currentCategory) :
                filteredBulletins;

              if (filteredData.length > 0) {
                currentPage = 1;
                $('#bulletin-list').empty();

                renderBulletins(filteredData, currentPage, pageSize);

                if (filteredData.length > pageSize) {
                  $('#loadMoreButton').show();
                  $('#loadMoreButton').off('click').on('click', function() {
                    currentPage++;
                    const totalDisplayed = (currentPage - 1) * pageSize;

                    if (totalDisplayed < filteredData.length) {
                      renderBulletins(filteredData, currentPage, pageSize);
                    }

                    if (currentPage * pageSize >= filteredData.length) {
                      $(this).hide();
                    }
                  });
                } else {
                  $('#loadMoreButton').hide();
                }
              } else {
                $('#bulletin-list').html(`
                  <div class="search-filter-card text-center py-5">
                    <img src="{{ asset('/images/nodata.png') }}" alt="No Bulletin Found" class="img-fluid mb-2" style="max-width: 180px;" />
                    <h5 class="fw-bold text-dark mb-1">No Bulletins Found</h5>
                    <p class="text-secondary small mb-0">Try changing your search terms or filter category.</p>
                  </div>
                `);
                $('#loadMoreButton').hide();
              }
            } else {
              $('#bulletin-list').html(`
                <div class="search-filter-card text-center py-5">
                  <img src="{{ asset('/images/nodata.png') }}" alt="No Bulletin Found" class="img-fluid mb-2" style="max-width: 180px;" />
                  <h5 class="fw-bold text-dark mb-1">No Bulletins Found</h5>
                  <p class="text-secondary small mb-0">There are no bulletins currently published for your organization.</p>
                </div>
              `);
              $('#loadMoreButton').hide();
            }
          },
          error: function() {
            console.error('Failed to load bulletin data.');
          }
        });
      }

      function renderBulletins(data, page, size) {
        const start = (page - 1) * size;
        const end = page * size;
        const batch = data.slice(start, end);

        batch.forEach(bulletin => {
          const fallbackImages = [
            'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?q=80&w=600&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1531482615713-2afd69097998?q=80&w=600&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1551836022-d5d88e9218df?q=80&w=600&auto=format&fit=crop'
          ];
          const randomFallback = fallbackImages[bulletin.id % fallbackImages.length];
          let bulletinImage = randomFallback;
          if (bulletin.cover_image_id) {
            bulletinImage = '{{ route('file', ':id') }}'.replace(':id', bulletin.cover_image_id);
          } else if (bulletin.content) {
            const match = bulletin.content.match(/<img[^>]+src=["']([^"']+)["']/i);
            if (match && match[1]) {
              bulletinImage = match[1];
            }
          }
          const authorName = bulletin.created_by ? toNormalCase(bulletin.created_by.name || bulletin.created_by.fullname) : 'HRGA Admin';
          const catName = bulletin.category ? bulletin.category.name : 'NEWS';

          const bulletinElement = $(`
            <div class="card bulletin-item-card" data-id="${bulletin.id}">
              <div class="row g-0 align-items-center">
                <div class="col-4">
                  <div class="bulletin-cover-img-box">
                    <img src="${bulletinImage}" alt="${bulletin.title}" onerror="this.onerror=null;this.src='${randomFallback}';">
                  </div>
                </div>
                <div class="col-8">
                  <div class="p-3">
                    <span class="bulletin-cat-pill">${catName}</span>
                    <h6 class="bulletin-card-title text-truncate-2">${bulletin.title}</h6>
                    <div class="d-flex align-items-center gap-2 text-secondary" style="font-size: 11.5px; font-weight: 600;">
                      <span><i class="bi bi-person me-1"></i>${authorName}</span>
                      <span>•</span>
                      <span>${formatDate(bulletin.created_at)}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          `);

          $('#bulletin-list').append(bulletinElement);
        });

        $('.bulletin-item-card').off('click').on('click', function() {
          const bulletinId = $(this).data('id');
          const routeUrl = '{{ route('bulletin.view', ':id') }}'.replace(':id', bulletinId);
          window.location.href = routeUrl;
        });
      }

      fetchBulletins();
    });
  </script>
@endsection
