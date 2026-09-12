@extends('templates.mobile')

@section('head')
  <style>
    .camera-container {
      width: 100%;
      height: 350px;
      position: relative;
      overflow: hidden;
      border-radius: 12px;
      margin-bottom: 20px;
      background-color: #333;
    }

    #camera-feed {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    #captured-image {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: none;
    }

    .camera-button {
      position: absolute;
      bottom: 20px;
      left: 50%;
      transform: translateX(-50%);
      width: 60px;
      height: 60px;
      border-radius: 50%;
      background-color: white;
      display: flex;
      justify-content: center;
      align-items: center;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      cursor: pointer;
      z-index: 10;
    }

    .camera-button i {
      font-size: 24px;
      color: var(--primary-color);
    }

    .switch-camera-button {
      position: absolute;
      top: 20px;
      right: 20px;
      width: 45px;
      height: 45px;
      border-radius: 50%;
      background-color: rgba(255, 255, 255, 0.9);
      display: flex;
      justify-content: center;
      align-items: center;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
      cursor: pointer;
      z-index: 10;
      backdrop-filter: blur(10px);
    }

    .switch-camera-button i {
      font-size: 20px;
      color: var(--primary-color);
    }

    .switch-camera-button.hidden {
      display: none;
    }

    .location-info {
      background-color: white;
      border-radius: 12px;
      padding: 15px;
      margin-bottom: 20px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .info-row {
      display: flex;
      justify-content: space-between;
      margin-bottom: 10px;
    }

    .info-label {
      color: #6c757d;
      font-size: 14px;
    }

    .info-value {
      font-weight: 500;
      font-size: 14px;
    }

    .work-from-dropdown {
      background-color: #f8f9fa;
      border: 1px solid #e9ecef;
      border-radius: 6px;
      padding: 6px 10px;
      font-size: 14px;
      font-weight: 500;
      color: #495057;
      cursor: pointer;
      min-width: 100px;
    }

    .work-from-dropdown:focus {
      outline: none;
      border-color: var(--primary-color);
      box-shadow: 0 0 0 2px rgba(var(--primary-color-rgb), 0.1);
    }

    .status-badge {
      display: inline-block;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
    }

    .in-range {
      background-color: #d1fae5;
      color: #047857;
    }

    .out-range {
      background-color: #fee2e2;
      color: #b91c1c;
    }

    .anywhere-mode {
      background-color: #dbeafe;
      color: #1d4ed8;
    }

    .button-group {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 10px;
      margin-top: 20px;
    }

    .btn-cancel {
      background-color: #f3f4f6;
      color: #4b5563;
      border: none;
      border-radius: 10px;
      padding: 12px;
      font-weight: 600;
      text-align: center;
    }

    .btn-confirm {
      background-color: var(--primary-color);
      color: white;
      border: none;
      border-radius: 10px;
      padding: 12px;
      font-weight: 600;
      text-align: center;
    }

    .confirmation-overlay {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: rgba(0, 0, 0, 0.7);
      display: none;
      justify-content: center;
      align-items: center;
      z-index: 1000;
    }

    .confirmation-box {
      background-color: white;
      width: 90%;
      max-width: 350px;
      border-radius: 12px;
      padding: 20px;
    }

    .confirmation-title {
      font-weight: 600;
      font-size: 18px;
      margin-bottom: 15px;
    }

    .confirmation-message {
      font-size: 14px;
      color: #4b5563;
      margin-bottom: 20px;
    }

    .disabled {
      opacity: 0.6;
      pointer-events: none;
    }
  </style>
@endsection

@section('content')
  <div class="px-3" style="min-height: 100%; padding-bottom: 100px;">
    <div class="py-3">
      <div class="d-flex align-items-center gap-4" style="cursor: pointer;"
        onclick="window.location.href='{{ route('attendance.index') }}'">
        <i class="bi bi-chevron-left" style="font-size: 1.2rem;"></i>
        <p class="m-0" style="font-size: 1.1rem;">{{ $type == 'in' ? 'Clock In' : 'Clock Out' }}</p>
      </div>
    </div>

    <div class="camera-container">
      <video id="camera-feed" autoplay playsinline></video>
      <img id="captured-image" src="" alt="Captured photo">
      <div class="camera-button" id="take-photo">
        <i class="bi bi-camera"></i>
      </div>
      <div class="switch-camera-button" id="switch-camera">
        <i class="bi bi-arrow-repeat"></i>
      </div>
    </div>

    <div class="location-info">
      <div class="info-row">
        <span class="info-label">Date</span>
        <span class="info-value" id="current-date">Loading...</span>
      </div>
      <div class="info-row">
        <span class="info-label">Time</span>
        <span class="info-value" id="current-time">Loading...</span>
      </div>
      <div class="info-row">
        <span class="info-label">Office</span>
        <span class="info-value">
          <span id="location-office">Checking...</span>
        </span>
      </div>
      <div class="info-row">
        <span class="info-label">Work From</span>
        <span class="info-value">
          <select class="work-from-dropdown" id="work-from-select">
            <option value="office">Office</option>
            <option value="anywhere">Anywhere</option>
          </select>
        </span>
      </div>
      <div class="info-row">
        <span class="info-label">Location</span>
        <span class="info-value">
          <span id="loading-location">Getting location...</span>
          <span id="location-coordinates" style="display: none;"></span>
        </span>
      </div>
      <div class="info-row">
        <span class="info-label">Distance from office</span>
        <span class="info-value" id="distance-value">Calculating...</span>
      </div>
      <div class="info-row">
        <span class="info-label">Status</span>
        <span class="info-value">
          <span id="location-status" class="status-badge">Checking...</span>
        </span>
      </div>
    </div>

    <div class="button-group">
      <button class="btn-cancel" onclick="history.back()">Cancel</button>
      <button class="btn-confirm disabled" id="confirm-button">Confirm
        {{ $type == 'in' ? 'Clock In' : 'Clock Out' }}</button>
    </div>
  </div>

  <div class="confirmation-overlay" id="confirmation-modal">
    <div class="confirmation-box">
      <div class="confirmation-title">Confirm {{ $type == 'in' ? 'Clock In' : 'Clock Out' }}</div>
      <div class="confirmation-message">
        Are you sure you want to {{ $type == 'in' ? 'clock in' : 'clock out' }} at this location?
        <br><br>
        <strong>Time:</strong> <span id="confirm-time"></span><br>
        <strong>Work From:</strong> <span id="confirm-work-from"></span><br>
        <strong>Distance from office:</strong> <span id="confirm-distance"></span>
      </div>
      <div class="button-group">
        <button class="btn-cancel" onclick="closeConfirmation()">Cancel</button>
        <button class="btn-confirm" id="final-confirm">Yes, Confirm</button>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script>
    let latitude = null;
    let longitude = null;
    let photoBase64 = null;
    let distanceFromOffice = null;
    let isInRange = false;
    let workFromMode = 'office';
    const officeName = "{{ $employee->office->name ?? '' }}";
    const maxDistanceInMeters = {{ $employee->office->max_distance_allowed ?? 100 }};
    const officeLatitude = {{ $employee->office->latitude ?? 0 }};
    const officeLongitude = {{ $employee->office->longitude ?? 0 }};
    const employeeId = "{{ $employee->id ?? 0 }}";
    const clockType = "{{ $type }}";
    let stream = null;
    let isFrozen = false;
    let currentFacingMode = 'environment';
    let availableCameras = [];
    let currentCameraIndex = 0;
    let isInitializingCamera = false;

    $(document).ready(function() {
      updateDateTime();
      setInterval(updateDateTime, 1000);

      initCamera();
      getLocation();

      $('#take-photo').click(capturePhoto);
      $('#switch-camera').click(switchCamera);

      $('#work-from-select').change(function() {
        workFromMode = $(this).val();
        updateLocationStatus();
      });

      $('#confirm-button').click(function() {
        if ($(this).hasClass('disabled')) return;

        $('#confirm-time').text($('#current-time').text());
        $('#confirm-work-from').text($('#work-from-select option:selected').text());
        $('#confirm-distance').text($('#distance-value').text());
        $('#confirmation-modal').css('display', 'flex');
      });

      $('#final-confirm').click(submitAttendance);
    });

    function updateDateTime() {
      if (isFrozen) return;

      const now = new Date();
      const dateOptions = {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      };
      const timeOptions = {
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: true
      };

      $('#current-date').text(now.toLocaleDateString('en-US', dateOptions));
      $('#current-time').text(now.toLocaleTimeString('en-US', timeOptions));
    }

    async function checkCameraDevices() {
      try {
        const devices = await navigator.mediaDevices.enumerateDevices();
        const videoDevices = devices.filter(device => device.kind === 'videoinput' && device.deviceId && device.label);

        console.log('Available video devices:', videoDevices);

        if (videoDevices.length > 1) {
          availableCameras = videoDevices;
          $('#switch-camera').removeClass('hidden');
          console.log('Multiple cameras found, showing switch button');
        } else {
          $('#switch-camera').addClass('hidden');
          console.log('Single camera or no cameras found');
        }
      } catch (error) {
        console.error('Error checking camera devices:', error);
        $('#switch-camera').addClass('hidden');
      }
    }

    async function initCamera() {
      if (isInitializingCamera) {
        console.log('Camera initialization already in progress');
        return;
      }

      isInitializingCamera = true;

      try {
        if (stream) {
          stream.getTracks().forEach(track => {
            track.stop();
            console.log('Stopped existing track:', track.kind, track.label);
          });
          stream = null;
        }

        await new Promise(resolve => setTimeout(resolve, 100));

        let constraints;

        if (availableCameras.length > 0) {
          constraints = {
            video: {
              deviceId: {
                exact: availableCameras[currentCameraIndex].deviceId
              },
              width: {
                ideal: 1280
              },
              height: {
                ideal: 720
              }
            },
            audio: false
          };
          console.log('Using specific camera:', availableCameras[currentCameraIndex].label);
        } else {
          constraints = {
            video: {
              facingMode: currentFacingMode,
              width: {
                ideal: 1280
              },
              height: {
                ideal: 720
              }
            },
            audio: false
          };
          console.log('Using facingMode:', currentFacingMode);
        }

        console.log('Camera constraints:', constraints);

        const videoStream = await navigator.mediaDevices.getUserMedia(constraints);
        stream = videoStream;

        const video = document.getElementById('camera-feed');
        video.srcObject = videoStream;

        await new Promise((resolve) => {
          video.onloadedmetadata = () => {
            resolve();
          };
        });

        console.log('Camera initialized successfully');
        if (availableCameras.length === 0) {
          await checkCameraDevices();
        }

      } catch (error) {
        console.error("Camera error:", error);

        if (error.name === 'OverconstrainedError' || error.name === 'NotFoundError') {
          console.log('Trying fallback with facingMode');
          try {
            const fallbackConstraints = {
              video: {
                facingMode: currentFacingMode,
                width: {
                  ideal: 1280
                },
                height: {
                  ideal: 720
                }
              },
              audio: false
            };

            const fallbackStream = await navigator.mediaDevices.getUserMedia(fallbackConstraints);
            stream = fallbackStream;

            const video = document.getElementById('camera-feed');
            video.srcObject = fallbackStream;

            console.log('Fallback camera initialized');

            await checkCameraDevices();

          } catch (fallbackError) {
            console.error("Fallback camera error:", fallbackError);
            showAlert('warning', "Could not access camera. Please check permissions.");
          }
        } else {
          showAlert('warning', "Could not access camera. Please check permissions.");
        }
      } finally {
        isInitializingCamera = false;
      }
    }

    async function switchCamera() {
      if (availableCameras.length <= 1 || isInitializingCamera) {
        console.log('Cannot switch camera:', {
          availableCameras: availableCameras.length,
          isInitializing: isInitializingCamera
        });
        return;
      }

      console.log('Switching camera...');

      currentCameraIndex = (currentCameraIndex + 1) % availableCameras.length;
      currentFacingMode = currentFacingMode === 'environment' ? 'user' : 'environment';

      console.log('Switching to camera index:', currentCameraIndex, availableCameras[currentCameraIndex].label);

      await initCamera();
    }

    function getLocation() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
          function(position) {
            if (isFrozen) return;
            latitude = position.coords.latitude;
            longitude = position.coords.longitude;

            console.log("Location obtained:", latitude, longitude);

            $('#loading-location').hide();
            $('#location-coordinates').text(latitude.toFixed(6) + ', ' + longitude.toFixed(6)).show();

            distanceFromOffice = calculateDistance(latitude, longitude, officeLatitude, officeLongitude);
            $('#distance-value').text(distanceFromOffice.toFixed(0) + ' meters');

            isInRange = distanceFromOffice <= maxDistanceInMeters;

            $('#location-office').text(officeName);

            updateLocationStatus();
            checkEnableConfirm();
          },
          function(error) {
            console.error("Geolocation error:", error);
            $('#location-office').text("Office not found");
            $('#loading-location').text("Could not get location");
            $('#distance-value').text("Unknown");
            $('#location-status').text('Location Error').removeClass('in-range anywhere-mode').addClass('out-range');
          }
        );
      } else {
        $('#location-office').text("Office not found");
        $('#loading-location').text("Geolocation not supported");
        $('#distance-value').text("Unknown");
        $('#location-status').text('Not Supported').removeClass('in-range anywhere-mode').addClass('out-range');
      }
    }

    function updateLocationStatus() {
      const statusElement = $('#location-status');

      if (workFromMode === 'anywhere') {
        statusElement
          .text('Work From Anywhere')
          .removeClass('in-range out-range')
          .addClass('anywhere-mode');
      } else {
        if (isInRange) {
          statusElement
            .text('In Range')
            .removeClass('out-range anywhere-mode')
            .addClass('in-range');
        } else {
          statusElement
            .text('Out of Range')
            .removeClass('in-range anywhere-mode')
            .addClass('out-range');
        }
      }
    }

    function calculateDistance(lat1, lon1, lat2, lon2) {
      const R = 6371e3;
      const φ1 = lat1 * Math.PI / 180;
      const φ2 = lat2 * Math.PI / 180;
      const Δφ = (lat2 - lat1) * Math.PI / 180;
      const Δλ = (lon2 - lon1) * Math.PI / 180;

      const a = Math.sin(Δφ / 2) * Math.sin(Δφ / 2) +
        Math.cos(φ1) * Math.cos(φ2) *
        Math.sin(Δλ / 2) * Math.sin(Δλ / 2);
      const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

      return R * c;
    }

    function capturePhoto() {
      const video = document.getElementById('camera-feed');
      const canvas = document.createElement('canvas');
      const context = canvas.getContext('2d');

      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;
      context.drawImage(video, 0, 0, canvas.width, canvas.height);

      canvas.toBlob(function(blob) {
        photoBase64 = blob;
        isFrozen = true;

        const imageUrl = URL.createObjectURL(blob);
        document.getElementById('captured-image').src = imageUrl;
        document.getElementById('camera-feed').style.display = 'none';
        document.getElementById('captured-image').style.display = 'block';

        $('#take-photo i').removeClass('bi-camera').addClass('bi-x-circle');
        $('#take-photo').off('click').click(retakePhoto);
        $('#switch-camera').addClass('hidden');

        checkEnableConfirm();
      }, 'image/jpeg');
    }

    function retakePhoto() {
      document.getElementById('camera-feed').style.display = 'block';
      document.getElementById('captured-image').style.display = 'none';
      photoBase64 = null;
      isFrozen = false;

      $('#take-photo i').removeClass('bi-x-circle').addClass('bi-camera');
      $('#take-photo').off('click').click(capturePhoto);
      if (availableCameras.length > 1) {
        $('#switch-camera').removeClass('hidden');
      }

      checkEnableConfirm();
      getLocation();
    }

    function checkEnableConfirm() {
      if (photoBase64 && latitude && longitude) {
        $('#confirm-button').removeClass('disabled');
      } else {
        $('#confirm-button').addClass('disabled');
      }
    }

    function closeConfirmation() {
      $('#confirmation-modal').css('display', 'none');
    }

    function submitAttendance() {
      $('#final-confirm').text('Processing...').prop('disabled', true);

      const formData = new FormData();
      formData.append('employee_id', employeeId);
      formData.append('latitude', latitude);
      formData.append('longitude', longitude);
      formData.append('photo', photoBase64);
      formData.append('distance_from_office', distanceFromOffice);
      formData.append('work_from', workFromMode);
      formData.append('type', clockType);
      formData.append('_token', '{{ csrf_token() }}');

      $.ajax({
        url: '{{ route('attendance.submit') }}',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
          if (response.success) {
            window.location.href = '{{ route('attendance.index') }}?success=' + (clockType === 'in' ? 'clockin' :
              'clockout');
          } else {
            showAlert('danger', response.message || 'There was an error submitting your attendance');
            $('#final-confirm').text('Yes, Confirm').prop('disabled', false);
          }
        },
        error: function(xhr) {
          showAlert('danger', (xhr.responseJSON?.message || 'Something went wrong'));
          $('#final-confirm').text('Yes, Confirm').prop('disabled', false);
        }
      });
    }

    $(window).on('beforeunload', function() {
      if (stream) {
        stream.getTracks().forEach(track => track.stop());
      }
    });
  </script>
@endsection
