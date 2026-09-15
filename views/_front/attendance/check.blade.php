@extends('headers.head')

@section('header')
  <style>
    .main-container {
      background-color: #fff;
      min-height: 100vh;
      padding: 16px 24px !important;
    }

    .card {
      background-color: #fff;
      border-radius: 8px;
      border: 1px solid #e0e0e0;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
    }

    .camera-box-desktop {
      width: 100%;
      height: 380px;
      position: relative;
      overflow: hidden;
      border-radius: 8px;
      background-color: #1e293b;
      border: 1px solid #cbd5e1;
    }

    #camera-feed-dt {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    #captured-image-dt {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: none;
    }

    .camera-btn-dt {
      position: absolute !important;
      bottom: 20px !important;
      left: 50% !important;
      transform: translateX(-50%) !important;
      width: 56px !important;
      height: 56px !important;
      border-radius: 50% !important;
      background-color: #ffffff !important;
      display: flex !important;
      justify-content: center !important;
      align-items: center !important;
      box-shadow: 0 4px 14px rgba(0, 0, 0, 0.3) !important;
      cursor: pointer !important;
      z-index: 99 !important;
      border: none !important;
      color: #0073e6 !important;
      margin: 0 !important;
      transition: transform 0.2s ease, background-color 0.2s ease !important;
    }

    .camera-btn-dt:hover {
      transform: translateX(-50%) scale(1.08) !important;
      background-color: #f8fafc !important;
    }

    .confirm-modal-overlay {
      position: fixed;
      top: 0; left: 0; right: 0; bottom: 0;
      background-color: rgba(15, 23, 42, 0.6);
      display: none;
      justify-content: center;
      align-items: center;
      z-index: 9999;
    }

    .confirm-modal-box {
      background-color: #ffffff;
      width: 90%;
      max-width: 400px;
      border-radius: 12px;
      padding: 24px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }
  </style>
@endsection

@section('body')
  <div class="bg-white main-container">
    <!-- Header Title with Back Button -->
    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
      <div class="d-flex align-items-center gap-3">
        <a href="{{ route('attendance.index') }}" class="btn btn-outline-secondary btn-sm">
          <i class="bi bi-arrow-left me-1"></i> Back to Attendance
        </a>
        <div>
          <h5 class="fw-bold text-dark mb-0">Attendance Clock {{ ucfirst($type) }}</h5>
          <p class="text-muted small mb-0" style="font-size: 12px;">Capture your photo and verify location to submit attendance</p>
        </div>
      </div>
      <div>
        <span class="badge bg-light text-dark border px-3 py-2">
          <i class="bi bi-person-fill me-1"></i> {{ $employee->fullname }}
        </span>
      </div>
    </div>

    <!-- Alert Container -->
    <div id="alert-box" class="alert alert-danger shadow-sm rounded-3 mb-3" style="display: none;"></div>

    <!-- 2-Column Desktop Layout -->
    <div class="row g-4">
      <!-- Camera Column -->
      <div class="col-md-6">
        <div class="camera-box-desktop">
          <video id="camera-feed-dt" autoplay playsinline muted></video>
          <img id="captured-image-dt" alt="Captured Photo">
          <button type="button" class="camera-btn-dt" id="take-photo-dt" title="Take Photo">
            <i class="bi bi-camera-fill fs-4"></i>
          </button>
        </div>
      </div>

      <!-- Info Column -->
      <div class="col-md-6">
        <div class="card p-4 shadow-sm h-100 d-flex flex-column justify-content-between">
          <div>
            <h6 class="fw-bold text-dark mb-3 border-bottom pb-2"><i class="bi bi-geo-alt-fill text-primary me-2"></i> Location & Time Verification</h6>

            <div class="d-flex justify-content-between py-2 border-bottom">
              <span class="text-muted small">Date</span>
              <span class="fw-bold text-dark small">{{ date('d F Y') }}</span>
            </div>

            <div class="d-flex justify-content-between py-2 border-bottom">
              <span class="text-muted small">Current Time</span>
              <span class="fw-bold text-dark small" id="dt-time-text">--:--:--</span>
            </div>

            <div class="d-flex justify-content-between py-2 border-bottom">
              <span class="text-muted small">Office Location</span>
              <span class="fw-bold text-dark small">{{ $employee->office ? $employee->office->name : 'Main Office' }}</span>
            </div>

            <div class="d-flex justify-content-between py-2 border-bottom">
              <span class="text-muted small">Distance from Office</span>
              <span class="fw-bold text-dark small" id="dt-distance-text">Calculating...</span>
            </div>

            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
              <span class="text-muted small">Work From</span>
              <select id="work_from" class="form-select form-select-sm" style="max-width: 150px;">
                <option value="office">Office</option>
                <option value="anywhere">Anywhere</option>
              </select>
            </div>

            <div class="d-flex justify-content-between align-items-center py-2">
              <span class="text-muted small">Range Status</span>
              <span id="dt-status-badge" class="badge bg-secondary">Detecting...</span>
            </div>
          </div>

          <div class="d-flex gap-2 mt-4">
            <a href="{{ route('attendance.index') }}" class="btn btn-secondary btn-sm w-50">Cancel</a>
            <button type="button" class="btn btn-primary btn-sm w-50" id="submit-att-dt" disabled>Clock {{ ucfirst($type) }}</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Confirmation Modal -->
  <div class="confirm-modal-overlay" id="confirmation-overlay">
    <div class="confirm-modal-box">
      <h6 class="fw-bold text-dark mb-2">Confirm Clock {{ ucfirst($type) }}</h6>
      <p class="text-muted small mb-4">Are you sure you want to submit your attendance photo and current location data?</p>

      <div class="d-flex gap-2 justify-content-end">
        <button type="button" class="btn btn-secondary btn-sm" id="btn-cancel-confirm">Cancel</button>
        <button type="button" class="btn btn-primary btn-sm" id="btn-final-submit">Confirm & Submit</button>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const type = "{{ $type }}";
      const employee = @json($employee);
      const office = employee.office || null;

      let currentStream = null;
      let photoData = null;
      let userLocation = { latitude: null, longitude: null };
      let distanceFromOffice = null;

      function updateClock() {
        const now = new Date();
        document.getElementById('dt-time-text').textContent = now.toLocaleTimeString();
      }
      setInterval(updateClock, 1000);
      updateClock();

      async function startCamera() {
        if (currentStream) {
          currentStream.getTracks().forEach(track => track.stop());
        }
        try {
          currentStream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: 'user', width: { ideal: 1280 }, height: { ideal: 720 } }
          });
          document.getElementById('camera-feed-dt').srcObject = currentStream;
        } catch (err) {
          showAlert("Camera access denied or unavailable.");
        }
      }

      document.getElementById('take-photo-dt').addEventListener('click', function() {
        const video = document.getElementById('camera-feed-dt');
        const img = document.getElementById('captured-image-dt');
        if (!video.srcObject) return;

        const canvas = document.createElement('canvas');
        canvas.width = video.videoWidth || 640;
        canvas.height = video.videoHeight || 480;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

        photoData = canvas.toDataURL('image/jpeg');
        img.src = photoData;
        img.style.display = 'block';
        video.style.display = 'none';
        checkValidation();
      });

      function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371e3;
        const φ1 = lat1 * Math.PI/180;
        const φ2 = lat2 * Math.PI/180;
        const Δφ = (lat2-lat1) * Math.PI/180;
        const Δλ = (lon2-lon1) * Math.PI/180;
        const a = Math.sin(Δφ/2) * Math.sin(Δφ/2) + Math.cos(φ1) * Math.cos(φ2) * Math.sin(Δλ/2) * Math.sin(Δλ/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        return R * c;
      }

      function getUserLocation() {
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(position => {
            userLocation.latitude = position.coords.latitude;
            userLocation.longitude = position.coords.longitude;

            if (office && office.latitude && office.longitude) {
              distanceFromOffice = calculateDistance(
                userLocation.latitude, userLocation.longitude,
                parseFloat(office.latitude), parseFloat(office.longitude)
              );
              document.getElementById('dt-distance-text').textContent = `${Math.round(distanceFromOffice)} meters`;
            } else {
              document.getElementById('dt-distance-text').textContent = 'Office location not set';
            }
            updateStatusBadge();
            checkValidation();
          }, err => {
            showAlert("Failed to acquire GPS location.");
          });
        }
      }

      function updateStatusBadge() {
        const workFrom = document.getElementById('work_from').value;
        const badge = document.getElementById('dt-status-badge');
        if (workFrom === 'anywhere') {
          badge.textContent = 'Anywhere Mode';
          badge.className = 'badge bg-info';
        } else if (office && distanceFromOffice !== null) {
          if (distanceFromOffice <= (office.max_distance_allowed || 100)) {
            badge.textContent = 'In Range';
            badge.className = 'badge bg-success';
          } else {
            badge.textContent = 'Out of Range';
            badge.className = 'badge bg-danger';
          }
        }
      }

      document.getElementById('work_from').addEventListener('change', function() {
        updateStatusBadge();
        checkValidation();
      });

      function checkValidation() {
        const btn = document.getElementById('submit-att-dt');
        const workFrom = document.getElementById('work_from').value;
        let isValid = photoData !== null && userLocation.latitude !== null;

        if (workFrom === 'office' && office && distanceFromOffice !== null) {
          if (distanceFromOffice > (office.max_distance_allowed || 100)) {
            isValid = false;
          }
        }
        btn.disabled = !isValid;
      }

      function showAlert(msg) {
        const alertBox = document.getElementById('alert-box');
        alertBox.textContent = msg;
        alertBox.style.display = 'block';
      }

      document.getElementById('submit-att-dt').addEventListener('click', function() {
        document.getElementById('confirmation-overlay').style.display = 'flex';
      });

      document.getElementById('btn-cancel-confirm').addEventListener('click', function() {
        document.getElementById('confirmation-overlay').style.display = 'none';
      });

      document.getElementById('btn-final-submit').addEventListener('click', function() {
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('employee_id', employee.id);
        formData.append('latitude', userLocation.latitude);
        formData.append('longitude', userLocation.longitude);
        formData.append('photo', photoData);
        formData.append('distance_from_office', distanceFromOffice || 0);
        formData.append('type', type);
        formData.append('work_from', document.getElementById('work_from').value);

        fetch('{{ route('attendance.submit') }}', {
          method: 'POST',
          body: formData
        }).then(res => res.json()).then(data => {
          if (data.success) {
            window.location.href = "{{ route('attendance.index') }}";
          } else {
            showAlert(data.message || 'Attendance submission failed.');
            document.getElementById('confirmation-overlay').style.display = 'none';
          }
        }).catch(err => {
          showAlert('Server connection error.');
          document.getElementById('confirmation-overlay').style.display = 'none';
        });
      });

      startCamera();
      getUserLocation();
    });
  </script>
@endsection
