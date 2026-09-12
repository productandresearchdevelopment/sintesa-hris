@extends('templates.mobile')

@section('head')
  <style>
    .custom-image {
      height: 300px;
      width: 100%;
      object-fit: cover;
      border-radius: 10px;
    }

    .filter-container {
      white-space: nowrap;
    }

    .filter-button {
      flex-shrink: 0;
    }

    .button-filter {
      background-color: var(--background-color);
      color: var(--gray-font);
      border: 2px solid var(--gray-border);
      border-radius: 20px;
      padding: 8px 24px;
      font-size: 0.9rem;
      font-weight: 500;
      white-space: nowrap;
      transition: all 0.3s ease-in-out;
    }

    .button-filter:hover {
      background-color: var(--primary-color);
      color: var(--white);
      border-color: var(--primary-color);
    }

    .button-filter:focus,
    .button-filter.active {
      background-color: var(--primary-color);
      color: var(--white);
      border-color: var(--primary-color);
      outline: none;
    }

    @media (max-width: 768px) {
      .custom-image {
        height: 120px;
      }
    }

    @media (min-width: 769px) and (max-width: 1024px) {
      .custom-image {
        height: 250px;
      }
    }
  </style>
@endsection

@section('content')
  <div class="px-3" style="min-height: 100%; min-width: 100%; padding-bottom: 100px;">
    <div class="py-3">
      <div class="d-flex align-items-center gap-4" style="cursor: pointer;" onclick="history.back()">
        <i class="bi bi-chevron-left" style="font-size: 1.2rem;"></i>
        <p class="m-0" style="font-size: 1.1rem;">Bulletin</p>
      </div>
    </div>
    <div class="">
      <div class="position-relative search-container mt-2">
        <input type="text" class="form-control ps-5" name="query" id="searchInput" placeholder="Search..."
          style="border-radius: 10px;">
        <i class="bi bi-search text-muted position-absolute"
          style="left: 15px; top: 50%; transform: translateY(-50%); font-size: 1.2rem;"></i>
      </div>
    </div>

    <div class="mt-3">
      <div class="d-flex gap-2 overflow-auto py-2 filter-container">
        <button class="button-filter active" data-category="all">All</button>
        @foreach ($category as $item)
          <button class="button-filter" data-category="{{ $item['id'] }}">{{ $item['name'] }}</button>
        @endforeach
      </div>
    </div>

    <div class="d-flex flex-column gap-3">
      <div id="bulletin-list" class="mt-1">
      </div>
      <button id="loadMoreButton" class="mt-2 btn btn-primary">Load More</button>
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
              return bulletin.category.organizations.some(org => org.id === userLogin.organization_id);
            })

            if (filteredBulletins.length > 0) {
              const filteredData = currentCategory !== 'all' ?
                filteredBulletins.filter(bulletin => bulletin.category.id === currentCategory) :
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
                <div class="card-body">
                    <div class="d-flex flex-column justify-content-center align-items-center gap-2">
                        <img src="{{ asset('/images/nodata.png') }}" alt="No Bulletin Found" class="img-fluid" style="max-width: 300px; max-height: 300px;" />
                        <h3 class="text-center font-bold text-black mb-0">No Bulletin Found</h3>
                    </div>
                </div>
             `);
                $('#loadMoreButton').hide();
              }
            } else {
              $('#bulletin-list').html(`
                <div class="card-body">
                    <div class="d-flex flex-column justify-content-center align-items-center gap-2">
                        <img src="{{ asset('/images/nodata.png') }}" alt="No Bulletin Found" class="img-fluid" style="max-width: 300px; max-height: 300px;" />
                        <h3 class="text-center font-bold text-black mb-0">No Bulletin Found</h3>
                    </div>
                </div>
             `);
              $('#loadMoreButton').hide();
            }
          },
          error: function() {
            alert('Failed to load data.');
          },
        });
      }

      function renderBulletins(data, page, size) {
        const start = (page - 1) * size;
        const end = page * size;
        const batch = data.slice(start, end);

        batch.forEach(bulletin => {
          const bulletinImage = '{{ route('file', ':id') }}'.replace(':id', bulletin.cover_image_id);
          const bulletinElement = $(`
                <div class="row g-0 border-bottom py-3 fade-in" data-id="${bulletin.id}">
                    <div class="col-5">
                        <img src="${bulletinImage}" class="custom-image" alt="Card image">
                    </div>
                    <div class="col-7 px-3">
                        <div class="card-body">
                            <p class="text-muted mb-1" style="font-size: 0.9rem;">${bulletin.category.name}</p>
                            <h5 class="card-title" style="font-weight: semibold; font-size: 1rem;">${bulletin.title}</h5>
                            <div class="d-flex align-items-center gap-2 mt-2" style="font-size: 0.9rem; color: #6c757d;">
                                <span>${toNormalCase(bulletin.created_by.name)} </span>
                                <span class="dot" style="width: 5px; height: 5px; background-color: #6c757d; border-radius: 50%; display: inline-block;"></span>
                                <span>${formatDate(bulletin.created_at)}</span>
                            </div>
                        </div>
                    </div>
                </div>
          `);

          $('#bulletin-list').append(bulletinElement);

          setTimeout(() => {
            bulletinElement.removeClass('fade-in');
          }, 5000);
        });

        $('.row.g-0.border-bottom.py-3').off('click').on('click', function() {
          const bulletinId = $(this).data('id');
          const routeUrl = '{{ route('bulletin.view', ':id') }}'.replace(':id', bulletinId);
          window.location.href = routeUrl;
        });
      }

      fetchBulletins();
    });
  </script>
@endsection
