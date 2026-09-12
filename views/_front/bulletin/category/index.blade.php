@extends('headers.head')

@section('header')
  <style>
    .section_our_solution .row {
      align-items: center;
    }

    .our_solution_category {
      display: block;
    }

    .our_solution_category .solution_cards_box {
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .solution_cards_box .solution_card {
      flex: 0 50%;
      background: #fff;
      box-shadow: 0 2px 4px 0 rgba(136, 144, 195, 0.2),
        0 5px 15px 0 rgba(37, 44, 97, 0.15);
      border-radius: 15px;
      margin: 8px;
      padding: 10px 15px;
      position: relative;
      z-index: 1;
      overflow: hidden;
      min-height: 210px;
      transition: 0.7s;
    }

    .solution_cards_box .solution_card:hover {
      background: #309df0;
      color: #fff;
      transform: scale(1.1);
      z-index: 9;
    }

    .solution_cards_box .solution_card:hover::before {
      background: rgb(85 108 214 / 10%);
    }

    .solution_cards_box .solution_card .solu_title {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .solution_cards_box .solution_card:hover .solu_title h3,
    .solution_cards_box .solution_card:hover .solu_description p {
      color: #fff;
    }

    .solution_cards_box .solution_card:before {
      content: "";
      position: absolute;
      background: rgb(85 108 214 / 5%);
      width: 170px;
      height: 400px;
      z-index: -1;
      transform: rotate(42deg);
      right: -56px;
      top: -23px;
      border-radius: 35px;
    }

    .solution_cards_box .solution_card:hover .solu_description button {
      background: #fff !important;
      color: #309df0;
    }

    .solution_card .solu_title h3 {
      color: #212121;
      font-size: 1.3rem;
      margin-top: 13px;
      margin-bottom: 13px;
    }

    .solution_card .solu_title span {
      font-size: 0.8rem;
      padding: 4px 8px;
      border-radius: 4px;
      width: 50px;
      text-align: center;
    }

    .solution_card .solu_description p {
      font-size: 15px;
      margin-bottom: 15px;
      overflow: hidden;
      display: -webkit-box;
      -webkit-box-orient: vertical;
      -webkit-line-clamp: 2;
      line-clamp: 2;
      text-overflow: ellipsis;
      color: #212121;
    }


    .solution_card .solu_actions {
      position: absolute;
      bottom: 20px;
      right: 20px;
    }

    .solution_card .solu_actions button {
      border: 0;
      border-radius: 8px;
      background: linear-gradient(140deg,
          #42c3ca 0%,
          #42c3ca 50%,
          #42c3cac7 75%) !important;
      color: #fff;
      font-weight: 500;
      font-size: 0.8rem;
      padding: 5px 8px;
      width: 56px;
    }

    .our_solution_content h1 {
      text-transform: capitalize;
      margin-bottom: 1rem;
      font-size: 2.5rem;
    }

    .our_solution_content p {}

    .hover_color_bubble {
      position: absolute;
      background: rgb(54 81 207 / 15%);
      width: 100rem;
      height: 100rem;
      left: 0;
      right: 0;
      z-index: -1;
      top: 16rem;
      border-radius: 50%;
      transform: rotate(-36deg);
      left: -18rem;
      transition: 0.7s;
    }

    .solution_cards_box .solution_card:hover .hover_color_bubble {
      top: 0rem;
    }

    @media screen and (min-width: 320px) {
      .our_solution_category {
        display: flex;
        width: 100%;
        margin: 0 auto;
      }

      .our_solution_category .solution_cards_box {
        flex: auto;
      }
    }

    @media only screen and (min-width: 768px) {
      .our_solution_category .solution_cards_box {
        flex: 1;
      }
    }

    @media only screen and (min-width: 1024px) {
      .our_solution_category {
        /* width: 50%;
                                                                                                                                                                                                                                                                                                                                                                                                                        margin: 0 auto; */
      }
    }
  </style>
@endsection

@section('body')
  <div class="bg-white p-4 main-container" style="min-height: 100%; min-width: 100%;">
    <div class="d-flex justify-content-center justify-content-md-between flex-wrap mb-3 gap-3 px-2">
      <button type="button" id="create-category"
        class="btn btn-primary d-flex align-items-center justify-content-center gap-1"><i
          class="bi bi-plus-lg d-flex align-items-center justify-content-center"></i>Create</button>
      <div class="position-relative search-container">
        <input type="text" class="form-control ps-5" name="query" id="searchInput" placeholder="Search..."
          style="border-radius: 20px;">
        <i class="bi bi-search text-muted position-absolute"
          style="left: 15px; top: 47%; transform: translateY(-50%); font-size: 1.2rem;"></i>
      </div>
    </div>

    <div class="section_our_solution">
      <div class="row">
        <div class="col-12 col-md-4">
          {{-- Generated Content With Javascript --}}
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="categoryModalLabel">Modal Title</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="categoryForm">
            <input type="hidden" id="categoryId">
            <div class="mb-3" id="categoryFormFields">
              <label for="categoryName" class="form-label">Name</label>
              <input type="text" id="categoryName" class="form-control" required>
              <div class="d-flex align-items-center gap-2">
                <div class="flex-grow-1">
                  <label for="categoryAlias" class="form-label mt-2">Alias</label>
                  <input type="text" id="categoryAlias" class="form-control" required>
                </div>
                <div>
                  <label for="categoryColor" class="form-label mt-2">Color</label>
                  <input type="color" id="categoryColor" class="form-control form-control-color">
                </div>
              </div>
              <label for="categoryDescription" class="form-label mt-2">Description</label>
              <textarea id="categoryDescription" class="form-control" rows="5"></textarea>
            </div>
            <div id="categoryDetails" class="d-none">
              <p class="d-flex flex-column gap-1"><strong>Name:</strong> <span id="detailName"></span></p>
              <p class="d-flex flex-column gap-1"><strong>Alias:</strong> <span id="detailAlias"></span></p>
              <p class="d-flex flex-column gap-1"><strong>Description:</strong> <span id="detailDescription"></span></p>
              <p class="d-flex flex-column gap-1"><strong>Total Bulletins:</strong> <span id="detailBulletins"></span></p>
            </div>
            <div id="deleteConfirmation" class="d-none">
              <p>Are you sure you want to delete this category?</p>
            </div>
            <button type="submit" class="btn btn-primary w-100" id="modalSubmit">Save</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      const categoryModal = new bootstrap.Modal('#categoryModal');
      let currentQuery = '';

      function lightenColor(color, percent) {
        let num = parseInt(color.replace("#", ""), 16);
        let r = (num >> 16) + percent * 255;
        let g = (num >> 8 & 0x00FF) + percent * 255;
        let b = (num & 0x0000FF) + percent * 255;

        r = Math.min(255, Math.max(0, r));
        g = Math.min(255, Math.max(0, g));
        b = Math.min(255, Math.max(0, b));

        return "#" + (1 << 24 | r << 16 | g << 8 | b).toString(16).slice(1);
      }

      function rgbToHex(rgb) {
        const result = rgb.match(/\d+/g).map(Number);
        return (
          "#" +
          result
          .map((value) => value.toString(16).padStart(2, "0"))
          .join("")
          .toUpperCase()
        );
      }

      function toggleRequired(isRequired) {
        $('#categoryName').prop('required', isRequired);
        $('#categoryAlias').prop('required', isRequired);
        $('#categoryDescription').prop('required', false);
        $('#categoryColor').prop('required', isRequired);
      }

      function fetchCategories() {
        showLoading();
        $.ajax({
          url: `{{ route('bulletin.category.data') }}`,
          type: 'GET',
          data: {
            query: currentQuery,
            trash: 1,
          },
          success: function(response) {
            $('.row').empty();

            if (response.data.length === 0) {
              const emptyMessage = `
                <div class="col-12">
                    <div class="card-body">
                      <div class="d-flex flex-column justify-content-center align-items-center gap-2">
                        <img src="{{ asset('/images/nodata.png') }}" alt="No Category Found" class="img-fluid" style="max-width: 300px; max-height: 300px;" />
                        <h3 class="text-center font-bold text-black mb-0">No Category Found</h3>
                      </div>
                    </div>
                </div>`;
              $('.row').append(emptyMessage);
            } else {
              response.data.forEach(category => {
                const categoryDescription = category.description ? `<p>${category.description}</p>` : '';

                const categoryHTML = `
              <div class="col-12 col-md-4">
                <div class="our_solution_category">
                  <div class="solution_cards_box">
                    <div class="solution_card">
                      <div class="hover_color_bubble"></div>
                      <div class="solu_title">
                        <h3>${category.name}</h3>
                        <span style="background-color: ${lightenColor(category.color, 0.4)}; color: ${category.color};">${category.alias}</span>
                      </div>
                      <div class="so_top_count mb-3">
                        <span class="bulletin-count badge bg-primary">${category.bulletins.length || 0} Bulletins</span>
                      </div>
                      <div class="solu_description">
                        ${categoryDescription}
                      </div>
                      <div class="solu_actions">
                        <button type="button" class="btn btn-info btn-sm view-category" data-category-id="${category.id}">Detail</button>
                        <button type="button" class="btn btn-warning btn-sm edit-category" data-category-id="${category.id}">Edit</button>
                        <button type="button" class="btn btn-danger btn-sm delete-category" data-category-id="${category.id}">Delete</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>`;

                $('.row').append(categoryHTML);
              });
            }

            attachCategoryEvents();
          },
          error: function(xhr) {
            console.error('Failed to fetch categories:', xhr.responseText);
            showAlert('danger', 'Failed to fetch categories.');
          },
          complete: function() {
            hideLoading();
          }
        });
      }


      $('#create-category').on('click', function() {
        resetForm();
        $('#categoryModalLabel').text('Create Category');
        $('#categoryFormFields').removeClass('d-none');
        $('#categoryDetails').addClass('d-none');
        $('#deleteConfirmation').addClass('d-none');
        $('#modalSubmit').removeClass('d-none').text('Save').removeClass('btn-danger').addClass(
          'btn-primary'
        );
        toggleRequired(true);
        categoryModal.show();
      });

      $('#categoryForm').on('submit', function(e) {
        e.preventDefault();

        const action = $('#modalSubmit').text().trim();
        if (action === 'Save') {
          const formData = {
            id: $('#categoryId').val(),
            name: $('#categoryName').val(),
            alias: $('#categoryAlias').val(),
            description: $('#categoryDescription').val(),
            color: $('#categoryColor').val().replace('#', ''),
          };

          if (formData.id) {
            updateCategory(formData);
          } else {
            createCategory(formData);
          }
        } else if (action === 'Delete') {
          const categoryId = $('#categoryId').val();
          deleteCategory(categoryId);
        }
      });

      function resetForm() {
        $('#categoryForm')[0].reset();
        $('#categoryId').val('');
      }

      function createCategory(data) {
        $.ajax({
          url: `{{ route('bulletin.category.push') }}`,
          method: 'POST',
          data: {
            ...data,
            _token: '{{ csrf_token() }}'
          },
          success: function(response) {
            showAlert('success', 'Category created successfully.');
            categoryModal.hide();
            fetchCategories();
          },
          error: function(xhr) {
            console.error(xhr.responseText);
            showAlert('danger', 'Failed to create category.');
          },
        });
      }

      function updateCategory(data) {
        $.ajax({
          url: `{{ route('bulletin.category.push') }}/${data.id}`,
          method: 'POST',
          data: {
            ...data,
            _token: '{{ csrf_token() }}',
          },
          success: function(response) {
            showAlert('success', 'Category updated successfully.');
            categoryModal.hide();
            fetchCategories();
          },
          error: function(xhr) {
            console.error(xhr.responseText);
            showAlert('danger', 'Failed to update category.');
          },
        });
      }

      function deleteCategory(categoryId) {
        const deleteBulletinCategoryIds = [categoryId];

        const formData = new FormData();
        formData.append('data', JSON.stringify(deleteBulletinCategoryIds));
        formData.append('_method', 'DELETE');
        formData.append('_token', '{{ csrf_token() }}');

        $.ajax({
          url: `{{ route('bulletin.category.delete') }}`,
          method: 'POST',
          contentType: false,
          processData: false,
          data: formData,
          success: function() {
            showAlert('success', 'Category deleted successfully.');
            categoryModal.hide();
            fetchCategories();
          },
          error: function(xhr) {
            console.error(xhr.responseText);
            showAlert('danger', 'Failed to delete category.');
          },
        });
      }

      function attachCategoryEvents() {
        $('.view-category').on('click', function() {
          const $card = $(this).closest('.solution_card');
          const name = $card.find('.solu_title h3').text();
          const alias = $card.find('.solu_title span').text();
          const description = $card.find('.solu_description p').text() || '-';
          const totalBulletins = $card.find('.so_top_count span').text();

          $('#categoryModalLabel').text('Detail Category');
          $('#categoryFormFields').addClass('d-none');
          $('#deleteConfirmation').addClass('d-none');
          $('#categoryDetails').removeClass('d-none');
          $('#detailName').text(name);
          $('#detailAlias').text(alias);
          $('#detailDescription').text(description);
          $('#detailBulletins').text(totalBulletins);
          $('#modalSubmit').addClass('d-none');

          categoryModal.show();
        });

        $('.edit-category').on('click', function() {
          const $card = $(this).closest('.solution_card');
          const categoryId = $(this).data('category-id');

          resetForm();
          $('#categoryModalLabel').text('Edit Category');
          $('#categoryId').val(categoryId);
          $('#categoryName').val($card.find('.solu_title h3').text());
          $('#categoryAlias').val($card.find('.solu_title span').text());
          $('#categoryDescription').val($card.find('.solu_description p').text());

          const color = $card.find('.solu_title span').css('color');
          const hexColor = color.startsWith("rgb") ? rgbToHex(color) : color;

          $('#categoryColor').val(hexColor);

          $('#categoryFormFields').removeClass('d-none');
          $('#categoryDetails').addClass('d-none');
          $('#deleteConfirmation').addClass('d-none');
          $('#modalSubmit').removeClass('d-none').text('Save').removeClass('btn-danger').addClass(
            'btn-primary'
          );

          toggleRequired(true);
          categoryModal.show();
        });


        $('.delete-category').on('click', function() {
          const categoryId = $(this).data('category-id');

          resetForm();
          $('#categoryModalLabel').text('Delete Category');
          $('#categoryId').val(categoryId);

          $('#categoryFormFields').addClass('d-none');
          $('#categoryDetails').addClass('d-none');
          $('#deleteConfirmation').removeClass('d-none');
          $('#modalSubmit').removeClass('d-none').text('Delete').addClass('btn-danger').removeClass(
            'btn-primary');

          toggleRequired(false);
          categoryModal.show();
        });
      }

      $('#searchInput').on('input', debounce(function() {
        currentQuery = $(this).val().trim();
        $('.row').empty();
        fetchCategories();
      }, 500));

      fetchCategories();
    });
  </script>
@endsection
