<style>
  .apps-container {
    position: relative;
  }

  .apps-button {
    width: 20px;
    height: 20px;
    padding: 0px;
    background: transparent;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    justify-content: center;
    align-items: center;
    transition: all 0.2s ease;
  }

  .apps-button:hover {
    background: rgba(0, 0, 0, 0.05);
  }

  .apps-button svg {
    width: 20px;
    height: 20px;
    fill: #5f6368;
  }

  .separator {
    width: 1px;
    height: 24px;
    background: #dadce0;
    margin: 0 8px;
  }

  .apps-panel {
    position: absolute;
    top: 30px;
    right: 0;
    width: 400px;
    max-width: 90vw;
    height: auto;
    max-height: 500px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.3s ease;
    z-index: 1000;
    border: 1px solid #dadce0;
    display: flex;
    flex-direction: column;
  }

  .apps-panel.active {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
  }

  .apps-panel-header {
    padding: 12px 16px;
    border-bottom: 1px solid #e0e0e0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #fff;
  }

  .apps-panel-header h3 {
    margin: 0;
    font-size: 14px;
    font-weight: 600;
    color: #202124;
  }

  .apps-close {
    background: transparent;
    border: none;
    cursor: pointer;
    padding: 4px;
    display: flex;
    border-radius: 50%;
    transition: background 0.2s ease;
  }

  .apps-close:hover {
    background: #e0e0e0;
  }

  .apps-close svg {
    width: 18px;
    height: 18px;
    fill: #5f6368;
  }

  .apps-panel-body {
    flex: 1;
    position: relative;
    overflow: hidden;
  }

  .apps-iframe {
    width: 100%;
    height: 100%;
    border: none;
  }

  .apps-loader {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
    color: #5f6368;
  }

  .apps-loader.hidden {
    display: none;
  }

  .apps-error {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
    color: #d93025;
    display: none;
  }

  .apps-error.show {
    display: block;
  }

  .apps-error svg {
    width: 48px;
    height: 48px;
    margin-bottom: 12px;
    opacity: 0.5;
  }

  .spinner {
    width: 40px;
    height: 40px;
    border: 3px solid #e0e0e0;
    border-top-color: #1a73e8;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
    margin: 0 auto 12px;
  }

  @keyframes spin {
    to {
      transform: rotate(360deg);
    }
  }

  .backdrop {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: transparent;
    display: none;
    z-index: 999;
  }

  .backdrop.active {
    display: block;
  }
</style>

<div class="page-heading mb-4">
  <div class="page-title">
    <div class="row">
      <div class="col-8 col-sm-6 d-flex gap-3 align-items-center justify-content-start">
        <header>
          <a href="#" class="ham-btn d-block">
            <i class="bi bi-justify fs-3"></i>
          </a>
        </header>
        <div class="d-flex gap-1 flex-column align-items-start justify-content-start">
          <h3 class="mb-0" id="heading-title" style="font-size: 18px; font-weight: bold">
            Dashboard
          </h3>
          <ol class="breadcrumb mb-0">
            @if (Request::is('/'))
              <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            @else
              <li class="breadcrumb-item"><a data-page="dashboard" href="#">Dashboard</a></li>
              <li class="breadcrumb-item active" aria-current="page">
                {{ Str::ucfirst(Request::segment(1)) }}
              </li>
            @endif
          </ol>
        </div>
      </div>
      <div class="col-4 col-sm-6">
        <div class="d-flex align-items-center justify-content-end gap-3">

          {{-- <div class="apps-container">
            <button class="apps-button" id="appsButton" title="My Applications">
              <svg viewBox="0 0 24 24">
                <path
                  d="M6,8c1.1,0 2,-0.9 2,-2s-0.9,-2 -2,-2 -2,0.9 -2,2 0.9,2 2,2zM12,20c1.1,0 2,-0.9 2,-2s-0.9,-2 -2,-2 -2,0.9 -2,2 0.9,2 2,2zM6,20c1.1,0 2,-0.9 2,-2s-0.9,-2 -2,-2 -2,0.9 -2,2 0.9,2 2,2zM6,14c1.1,0 2,-0.9 2,-2s-0.9,-2 -2,-2 -2,0.9 -2,2 0.9,2 2,2zM12,14c1.1,0 2,-0.9 2,-2s-0.9,-2 -2,-2 -2,0.9 -2,2 0.9,2 2,2zM16,6c0,1.1 0.9,2 2,2s2,-0.9 2,-2 -0.9,-2 -2,-2 -2,0.9 -2,6zM12,8c1.1,0 2,-0.9 2,-2s-0.9,-2 -2,-2 -2,0.9 -2,2 0.9,2 2,2zM18,14c1.1,0 2,-0.9 2,-2s-0.9,-2 -2,-2 -2,0.9 -2,2 0.9,2 2,2zM18,20c1.1,0 2,-0.9 2,-2s-0.9,-2 -2,-2 -2,0.9 -2,2 0.9,2 2,2z" />
              </svg>
            </button>

            <div class="backdrop" id="backdrop"></div>

            <div class="apps-panel" id="appsPanel">
              <div class="apps-panel-header">
                <h3>My Applications</h3>
                <button class="apps-close" id="closeApps">
                  <svg viewBox="0 0 24 24">
                    <path
                      d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" />
                  </svg>
                </button>
              </div>
              <div class="apps-panel-body">
                <div class="apps-loader" id="appsLoader">
                  <div class="spinner"></div>
                  <p>Loading applications...</p>
                </div>
                <div class="apps-error" id="appsError">
                  <svg viewBox="0 0 24 24" fill="currentColor">
                    <path
                      d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
                  </svg>
                  <p>Failed to load applications</p>
                </div>
                <iframe id="appsIframe" class="apps-iframe"></iframe>
              </div>
            </div>
          </div>

          <div class="separator"></div> --}}

          <nav aria-label="breadcrumb" class="breadcrumb-header">
            <div class="d-flex align-items-center">
              <div class="dropdown d-block">
                <a href="#" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false"
                  class="d-flex align-items-center text-dark font-bold">
                  <img id="profile-image-navbar"
                    src="{{ $user->photo_id ? route('file', $user->photo_id) : asset('templates/mazer/jpg/' . rand(1, 8) . '.jpg') }}"
                    alt="Avatar" style="width: 40px; height: 40px; border-radius: 50%;">
                  <span id="profile-name-navbar-mobile"
                    class="d-none d-sm-inline mt-2 ms-3">{{ $user->name ?? 'Unknown User' }}</span>
                  <i class="ms-2 bi bi-caret-down-fill"></i>
                </a>
                <ul class="dropdown-menu" aria-labelledby="profileDropdown">
                  <div class="d-flex d-sm-none flex-column align-items-center text-center border-bottom pb-2 mb-2"
                    style="width: 100%;">
                    <strong>Signed in as</strong>
                    <span id="profile-name-navbar-desktop" class="text-muted">{{ $user->name ?? 'Unknown User' }}</span>
                  </div>
                  <li><a class="dropdown-item" data-page="profile" href="#">Profile</a></li>
                  <li><a class="dropdown-item" data-page="logout" href="#">Logout</a></li>
                </ul>
              </div>
            </div>
          </nav>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  //   const ssoToken = "{{ session('token_sso') }}";
  const userId = "{{ $user->id }}";
  const apiUrl = "{{ env('SSO_API_URL', 'https://sso.sintesa-hris.com/api/view/dropdown') }}";

  const appsButton = document.getElementById('appsButton');
  const appsPanel = document.getElementById('appsPanel');
  const backdrop = document.getElementById('backdrop');
  const closeApps = document.getElementById('closeApps');
  const appsIframe = document.getElementById('appsIframe');
  const appsLoader = document.getElementById('appsLoader');
  const appsError = document.getElementById('appsError');

  let isLoaded = false;

  function togglePanel() {
    if (!appsPanel) return;
    const isActive = appsPanel.classList.toggle('active');
    if (backdrop) backdrop.classList.toggle('active');

    if (isActive && !isLoaded) {
      loadApps();
    }

    if (isActive) {
      document.body.style.overflow = 'hidden';
    } else {
      document.body.style.overflow = '';
    }
  }

  function closePanel() {
    if (appsPanel) appsPanel.classList.remove('active');
    if (backdrop) backdrop.classList.remove('active');
    document.body.style.overflow = '';
  }

  function loadApps() {
    if (!userId) {
      showError('Session expired. Please login again.');
      return;
    }

    if (appsLoader) appsLoader.classList.remove('hidden');
    if (appsIframe) appsIframe.style.display = 'none';
    if (appsError) appsError.classList.remove('show');

    const url = `${apiUrl}?uid=${encodeURIComponent(userId)}`;
    if (appsIframe) appsIframe.src = url;
  }

  function showError(message) {
    if (appsLoader) appsLoader.classList.add('hidden');
    if (appsIframe) appsIframe.style.display = 'none';
    if (appsError) {
      appsError.classList.add('show');
      appsError.querySelector('p').textContent = message;
    }
  }

  if (appsButton) {
    appsButton.addEventListener('click', (e) => {
      e.stopPropagation();
      togglePanel();
    });
  }

  if (closeApps) closeApps.addEventListener('click', closePanel);
  if (backdrop) backdrop.addEventListener('click', closePanel);

  document.addEventListener('click', (e) => {
    if (appsButton && appsPanel && !appsButton.contains(e.target) && !appsPanel.contains(e.target)) {
      closePanel();
    }
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && appsPanel && appsPanel.classList.contains('active')) {
      closePanel();
    }
  });

  if (appsIframe) {
    appsIframe.addEventListener('load', () => {
      if (appsIframe.src && appsIframe.src !== 'about:blank') {
        if (appsLoader) appsLoader.classList.add('hidden');
        appsIframe.style.display = 'block';
        isLoaded = true;
      }
    });

    appsIframe.addEventListener('error', () => {
      showError('Failed to load applications. Please try again.');
    });
  }
</script>
