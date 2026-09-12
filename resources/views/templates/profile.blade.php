  <script>
    $(document).ready(function() {
      function toggleLoading(button, state) {
        if (state) {
          button.prop('disabled', true);
          button.html(
            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...');
          $('#cancel-upload').prop('disabled', true);
        } else {
          button.prop('disabled', false);
          button.text('Save Changes');
          $('#cancel-upload').prop('disabled', false);
        }
      }

      // Photo Upload
      const originalImage = $('#profile-image').attr('src');

      $('#photo-upload').on('change', function(event) {
        const file = event.target.files[0];
        if (file) {
          const reader = new FileReader();
          reader.onload = function(e) {
            $('#profile-image').attr('src', e.target.result);
            $('#confirmation-buttons').removeClass('d-none');
          };
          reader.readAsDataURL(file);
        }
      });

      $('#confirm-upload').on('click', function() {
        const button = $(this);
        toggleLoading(button, true);

        const formData = new FormData($('#photo-form')[0]);
        formData.append('_token', '{{ csrf_token() }}');

        $.ajax({
          url: '{{ route('profile.upload') }}',
          method: 'POST',
          data: formData,
          contentType: false,
          processData: false,
          success: function(data) {
            toggleLoading(button, false);
            if (data.success) {
              $('#feedback').html(
                `<div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> Photo updated successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>`
              );
              $('#confirmation-buttons').addClass('d-none');

              const imageData = data.data.photo_id;
              $(parent.document).find('#profile-image-navbar').attr('src', `{{ route('file', ':id') }}`
                .replace(':id',
                  imageData));

            } else {
              $('#feedback').html(
                `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> Failed to update photo: ${data.message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>`
              );
            }
          },
          error: function() {
            toggleLoading(button, false);
            $('#feedback').html(
              `<div class="alert alert-danger alert-dismissible fade show" role="alert">
              <strong>Error!</strong> Failed to update photo
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>`
            );
          }
        });
      });

      $('#cancel-upload').on('click', function() {
        $('#profile-image').attr('src', originalImage);
        $('#confirmation-buttons').addClass('d-none');
        $('#photo-upload').val('');
      });

      // Profile Update
      $('#profile-form').on('submit', function(event) {
        event.preventDefault();

        const button = $(this).find('button[type="submit"]');
        toggleLoading(button, true);

        const formData = new FormData(this);
        formData.append('_token', '{{ csrf_token() }}');

        $.ajax({
          url: '{{ route('profile.edit') }}',
          method: 'POST',
          data: formData,
          contentType: false,
          processData: false,
          success: function(data) {
            toggleLoading(button, false);
            if (data.success) {
              $('#feedback').html(
                `<div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> Profile updated successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>`
              );
              const userData = data.data;

              $('#name').val(userData.name);
              $('#email').val(userData
                .email);

              $('#profile-name').text(userData.name ?? 'Unknown User');
              $(parent.document).find('#profile-name-navbar-desktop').text(userData.name ?? 'Unknown User');
              $(parent.document).find('#profile-name-navbar-mobile').text(userData.name ?? 'Unknown User');
            } else {
              $('#feedback').html(
                `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> Failed to update profile: ${data.message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>`
              );
            }
          },
          error: function() {
            toggleLoading(button, false);
            $('#feedback').html(
              `<div class="alert alert-danger alert-dismissible fade show" role="alert">
              <strong>Error!</strong> Failed to update profile
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>`
            );
          }
        });
      });

      // Password Change
      $('#password-form').on('submit', function(event) {
        event.preventDefault();

        const button = $(this).find('button[type="submit"]');
        toggleLoading(button, true);

        const formData = new FormData(this);
        formData.append('_token', '{{ csrf_token() }}');

        $.ajax({
          url: '{{ route('profile.password') }}',
          method: 'POST',
          data: formData,
          contentType: false,
          processData: false,
          success: function(data) {
            toggleLoading(button, false);
            $('#feedback').html('');
            if (data.success) {
              $('#feedback').html(
                `<div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> Password changed successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>`
              );

              $('#old').val('');
              $('#new').val('');
              $('#confirm').val('');
            } else {
              $('#feedback').html(
                `<div class="alert alert-danger alert-dismissible fade show">
                <strong>Error!</strong> ${data.message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>`
              );
            }
          },
          error: function() {
            toggleLoading(button, false);
            $('#feedback').html(
              `<div class="alert alert-danger alert-dismissible fade show" role="alert">
              <strong>Error!</strong> An error occurred while changing the password.
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>`
            );
          },
        });
      });
    });
  </script>

  <div id="feedback" class="mt-3"></div>
  <div class="row">
    <!-- Profil Kiri -->
    <div class="col-12 col-md-4 mb-4 mb-md-0">
      <div class="card border h-100">
        <div class="card-body h-100 position-relative">
          <div class="d-flex justify-content-center align-items-center flex-column position-relative h-100">
            <div class="avatar avatar-2xl position-relative">
              <img id="profile-image"
                src="{{ $user->photo_id ? route('file', $user->photo_id) : asset('templates/mazer/jpg/' . rand(1, 8) . '.jpg') }}"
                alt="Avatar" class="rounded-full w-24 h-24">

              <form id="photo-form" enctype="multipart/form-data"
                class="position-absolute bottom-0 end-0 badge rounded-pill bg-light p-2" style="cursor: pointer;">
                @csrf
                <label for="photo-upload" class="d-flex align-items-center" style="cursor: pointer;">
                  <i class="iconly-boldEdit text-primary" style="font-size: 20px"></i>
                  <input type="file" name="photo" id="photo-upload" class="d-none">
                </label>
              </form>
            </div>

            <div id="confirmation-buttons" class="d-none mt-3">
              <button id="confirm-upload" class="btn btn-success me-2">Yes</button>
              <button id="cancel-upload" class="btn btn-danger">No</button>
            </div>

            <h3 class="mt-3 text-center" id="profile-name">{{ $user->name ?? 'Unknown User' }}</h3>
            <p class="text-small" id="profile-role">{{ $user->role->name ?? 'Unknown Role' }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Profil Kanan -->
    <div class="col-12 col-md-8">
      <div class="card border h-100">
        <div class="card-body h-100">
          <form id="profile-form" method="post">
            @csrf

            <div class="form-group mb-4">
              <label for="username" class="form-label">Username</label>
              <input type="text" name="username" id="username" class="form-control"
                value="{{ $user->username ?? '' }}" placeholder="Your Username" disabled>
            </div>

            <div class="form-group mb-4">
              <label for="name" class="form-label">Name</label>
              <input type="text" name="name" id="name" class="form-control" value="{{ $user->name ?? '' }}"
                placeholder="Your Name" required>
            </div>

            <div class="form-group mb-4">
              <label for="email" class="form-label">Email</label>
              <input type="email" name="email" id="email" class="form-control" value="{{ $user->email ?? '' }}"
                placeholder="Your Email">
            </div>

            <div class="form-group">
              <button type="submit" class="btn btn-primary w-full">Save Changes</button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="row mt-4">
    <!-- Change Password Section -->
    <div class="col-12">
      <div class="card border">
        <div class="card-header">
          <h5 class="card-title">Change Password</h5>
        </div>
        <div class="card-body">
          <form id="password-form">
            @csrf
            <div class="form-group my-2">
              <label for="old" class="form-label">Old Password</label>
              <input type="password" name="old" id="old" class="form-control"
                placeholder="Enter your old password">
            </div>
            <div class="form-group my-2">
              <label for="new" class="form-label">New Password</label>
              <input type="password" name="new" id="new" class="form-control"
                placeholder="Enter new password">
            </div>
            <div class="form-group my-2">
              <label for="confirm" class="form-label">Confirm Password</label>
              <input type="password" name="confirm" id="confirm" class="form-control"
                placeholder="Enter confirm password">
            </div>

            <div class="form-group mb-2 mt-4 d-flex justify-content-end">
              <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
