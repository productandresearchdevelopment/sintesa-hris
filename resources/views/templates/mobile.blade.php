<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Home</title>

  <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">
  <link rel="shortcut icon" href="{{ asset('images/logo-hr.ico') }}" type="image/ico">

  <!-- PWA Manifest & Meta Tags -->
  <link rel="manifest" href="{{ asset('manifest.json?v=2') }}">
  <meta name="theme-color" content="#ffffff">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="default">
  <meta name="apple-mobile-web-app-title" content="Sintesa HRIS">
  <link rel="apple-touch-icon" href="{{ asset('images/icons/icon-192x192.png') }}">
  <!-- iOS Splash Screens -->
  <link rel="apple-touch-startup-image" href="{{ asset('images/splash/apple-splash-1290-2796.png') }}" media="(device-width: 430px) and (device-height: 932px) and (-webkit-device-pixel-ratio: 3)">
  <link rel="apple-touch-startup-image" href="{{ asset('images/splash/apple-splash-1179-2556.png') }}" media="(device-width: 393px) and (device-height: 852px) and (-webkit-device-pixel-ratio: 3)">
  <link rel="apple-touch-startup-image" href="{{ asset('images/splash/apple-splash-1170-2532.png') }}" media="(device-width: 390px) and (device-height: 844px) and (-webkit-device-pixel-ratio: 3)">
  <link rel="apple-touch-startup-image" href="{{ asset('images/splash/apple-splash-1284-2778.png') }}" media="(device-width: 428px) and (device-height: 926px) and (-webkit-device-pixel-ratio: 3)">
  <link rel="apple-touch-startup-image" href="{{ asset('images/splash/apple-splash-1125-2436.png') }}" media="(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3)">
  <link rel="apple-touch-startup-image" href="{{ asset('images/splash/apple-splash-828-1792.png') }}" media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 2)">
  <link rel="apple-touch-startup-image" href="{{ asset('images/splash/apple-splash-1242-2688.png') }}" media="(device-width: 414px) and (device-height: 896px) and (-webkit-device-pixel-ratio: 3)">
  <link rel="apple-touch-startup-image" href="{{ asset('images/splash/apple-splash-1242-2208.png') }}" media="(device-width: 414px) and (device-height: 736px) and (-webkit-device-pixel-ratio: 3)">
  <link rel="apple-touch-startup-image" href="{{ asset('images/splash/apple-splash-750-1334.png') }}" media="(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2)">

  {{-- RESOURCES ICON PACK --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="{{ asset('plugins/pe7icon/pe-icon-7-stroke/css/pe-icon-7-stroke.css') }}" />
  <link rel="stylesheet" href="https://cdn.lineicons.com/4.0/lineicons.css" />
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/5.3.45/css/materialdesignicons.css"
    integrity="sha256-NAxhqDvtY0l4xn+YVa6WjAcmd94NNfttjNsDmNatFVc=" crossorigin="anonymous" />
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>

  {{-- JQUERY --}}
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

  {{-- BOOTSTRAP --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>

  {{-- OWL CAROUSEL --}}
  <link href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css"
    rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

  {{-- JS TREE --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>

  {{-- SELECT2 --}}
  <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

  <style>
    html, body {
      background-color: #ffffff !important;
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    #alertContainer {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 9999;
      max-width: 350px;
      width: calc(100% - 40px);
      pointer-events: none;
    }

    #alertContainer .alert {
      pointer-events: auto;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
      border-radius: 8px;
    }

    .pwa-install-banner {
      position: fixed;
      bottom: 20px;
      left: 50%;
      transform: translateX(-50%);
      width: calc(100% - 32px);
      max-width: 480px;
      background: #ffffff;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.18);
      border: 1px solid #e2e8f0;
      border-radius: 16px;
      padding: 12px 16px;
      z-index: 99999;
      animation: slideUp 0.3s ease-out;
    }

    @keyframes slideUp {
      from { transform: translate(-50%, 100%); opacity: 0; }
      to { transform: translate(-50%, 0); opacity: 1; }
    }

    /* Global Mobile Dropdown & Select2 Consistency */
    .dropdown-menu {
      max-height: 260px !important;
      overflow-y: auto !important;
      border-radius: 18px !important;
      border: 1px solid #e2e8f0 !important;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12) !important;
      padding: 6px !important;
      min-width: 180px !important;
      max-width: 290px !important;
      z-index: 99999 !important;
      background: #ffffff !important;
    }

    .dropdown-menu::-webkit-scrollbar,
    .select2-results__options::-webkit-scrollbar {
      width: 5px;
    }
    .dropdown-menu::-webkit-scrollbar-track,
    .select2-results__options::-webkit-scrollbar-track {
      background: #f8fafc;
      border-radius: 10px;
    }
    .dropdown-menu::-webkit-scrollbar-thumb,
    .select2-results__options::-webkit-scrollbar-thumb {
      background: #cbd5e1;
      border-radius: 10px;
    }

    .dropdown-item {
      font-size: 13px !important;
      font-weight: 600 !important;
      padding: 10px 14px !important;
      border-radius: 12px !important;
      color: #334155 !important;
      white-space: normal !important;
      word-break: break-word !important;
      line-height: 1.35 !important;
      transition: all 0.15s ease !important;
    }

    .dropdown-item:hover,
    .dropdown-item:focus,
    .dropdown-item.active {
      background-color: #eff6ff !important;
      color: #0073e6 !important;
    }

    /* Global Mobile Modal Consistency */
    .modal-content {
      border-radius: 24px !important;
      border: none !important;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.18) !important;
      overflow: hidden !important;
      background: #ffffff !important;
    }

    .modal-header {
      background: linear-gradient(135deg, #0073e6 0%, #00a651 100%) !important;
      color: #ffffff !important;
      padding: 16px 20px !important;
      border: none !important;
    }

    .modal-header .modal-title {
      color: #ffffff !important;
      font-weight: 800 !important;
      font-size: 17px !important;
    }

    .modal-header .btn-close {
      filter: brightness(0) invert(1) !important;
      opacity: 0.8;
    }
    .modal-header .btn-close:hover {
      opacity: 1;
    }

    .modal-body {
      padding: 20px !important;
    }

    .modal-body .form-control,
    .modal-body .form-select {
      border-radius: 14px !important;
      border: 1.5px solid #e2e8f0 !important;
      padding: 10px 14px !important;
      font-size: 13.5px !important;
      font-weight: 600 !important;
      background-color: #f8fafc !important;
      color: #0f172a !important;
    }

    .modal-body .form-control:focus,
    .modal-body .form-select:focus {
      border-color: #0073e6 !important;
      background-color: #ffffff !important;
      box-shadow: 0 0 0 3px rgba(0, 115, 230, 0.15) !important;
      outline: none !important;
    }

    .modal-footer {
      border-top: 1px solid #f1f5f9 !important;
      padding: 14px 20px !important;
      background: #ffffff !important;
    }

    .modal-footer .btn-primary,
    .modal-footer .save-item {
      background: linear-gradient(135deg, #0073e6 0%, #005bb5 100%) !important;
      color: #ffffff !important;
      border: none !important;
      border-radius: 50px !important;
      padding: 10px 24px !important;
      font-size: 13.5px !important;
      font-weight: 800 !important;
      box-shadow: 0 4px 14px rgba(0, 115, 230, 0.35) !important;
    }

    .modal-footer .btn-light,
    .modal-footer .btn-secondary {
      background: #f1f5f9 !important;
      color: #475569 !important;
      border: none !important;
      border-radius: 50px !important;
      padding: 10px 20px !important;
      font-size: 13.5px !important;
      font-weight: 700 !important;
    }
  </style>

  <script>
    function showAlert(type, message) {
      if (type === 'error') type = 'danger';
      $('#alertContainer').html('');

      let icon;
      switch (type) {
        case 'success':
          icon = '<i class="fas fa-check-circle"></i>';
          break;
        case 'warning':
          icon = '<i class="fas fa-exclamation-triangle"></i>';
          break;
        case 'danger':
          icon =
            '<i class="fas fa-exclamation-circle"></i>';
          break;
        case 'info':
          icon = '<i class="fas fa-info-circle"></i>';
          break;
        case 'primary':
          icon = '<i class="fas fa-star"></i>';
          break;
        case 'secondary':
          icon = '<i class="fas fa-star-half-alt"></i>';
          break;
        default:
          icon = '';
      }

      const alertHtml = `
        <div class="alert alert-${type} d-flex align-items-center show" role="alert">
          ${icon} <span class="ms-2">${message}</span>
        </div>
      `;

      $('#alertContainer').html(alertHtml);
      setTimeout(() => {
        $('#alertContainer .alert').removeClass('show');
        setTimeout(() => {
          $('#alertContainer').html('');
        }, 300);
      }, 5000);
    }

    function showLoading() {
      $('#loadingOverlay').removeClass('d-none');
      $('#loadingOverlay').fadeIn();
    }

    function hideLoading() {
      $('#loadingOverlay').fadeOut();
    }

    function isMobile() {
      return window.matchMedia("(max-width: 768px)").matches;
    }

    function debounce(func, delay) {
      let timer;
      return function(...args) {
        clearTimeout(timer);
        timer = setTimeout(() => func.apply(this, args), delay);
      };
    }
  </script>

  <script>
    let users = null;
    let employees = null;
    let leaves = null;
    let helpdesks = null;
    let helpdeskCategories = null;
    let organizations = null;
    let divisions = null;
    let companies = null;
    let placements = null;
    let genders = null;
    let maritals = null;
    let religions = null;
    let banks = null;
    let emergencyRelations = null;
    let leaveTypes = null;
    let cities = null;
    let provinces = null;

    const dataCache = {};
    const pendingPromises = {};

    async function getData(endpoint, params = {}) {
      const cacheKey = endpoint + '?' + JSON.stringify(params);
      if (dataCache[cacheKey]) {
        return dataCache[cacheKey];
      }
      if (pendingPromises[cacheKey]) {
        return pendingPromises[cacheKey];
      }

      pendingPromises[cacheKey] = (async () => {
        try {
          const url = new URL(endpoint, window.location.origin);
          Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));
          const response = await fetch(url);
          if (!response.ok) {
            throw new Error('Network response was not ok');
          }
          const result = await response.json();

          let resData;
          if (!result.data) {
            resData = result;
          } else {
            resData = result.data || [];
          }

          dataCache[cacheKey] = resData;
          return resData;
        } catch (error) {
          console.error('Error fetching data from:', endpoint, error);
          return [];
        } finally {
          delete pendingPromises[cacheKey];
        }
      })();

      return pendingPromises[cacheKey];
    }

    async function fetchAllTableData() {
      const commonEndpoint = '{{ route('globaldata.data') }}';
      const cityEndpoint = '{{ route('city.data') }}';

      const [
        resUsers,
        resEmployees,
        resLeaves,
        resHelpdesks,
        resHelpdeskCategories,
        resOrganizations,
        resDivisions,
        resCompanies,
        resPlacements,
        resGenders,
        resMaritals,
        resReligions,
        resBanks,
        resEmergencyRelations,
        resLeaveTypes,
        resCities,
        resProvinces
      ] = await Promise.all([
        getData('{{ route('auth.user.data') }}'),
        getData('{{ route('employee.data') }}'),
        getData('{{ route('leave.data') }}'),
        getData('{{ route('helpdesk.data') }}'),
        getData('{{ route('helpdesk.category.data') }}'),
        getData('{{ route('organization.data') }}'),
        getData('{{ route('division.data') }}'),
        getData('{{ route('company.data') }}'),
        getData('{{ route('placement.data') }}'),
        getData(commonEndpoint, {
          group: 'gender'
        }),
        getData(commonEndpoint, {
          group: 'marital'
        }),
        getData(commonEndpoint, {
          group: 'religion'
        }),
        getData(commonEndpoint, {
          group: 'bank'
        }),
        getData(commonEndpoint, {
          group: 'emergency_relation'
        }),
        getData(commonEndpoint, {
          group: 'leave_type'
        }),
        getData(cityEndpoint, {
          type: 'city'
        }),
        getData(cityEndpoint, {
          type: 'province'
        })
      ]);

      users = resUsers;
      employees = resEmployees;
      leaves = resLeaves;
      helpdesks = resHelpdesks;
      helpdeskCategories = resHelpdeskCategories;
      organizations = resOrganizations;
      divisions = resDivisions;
      companies = resCompanies;
      placements = resPlacements;
      genders = resGenders;
      maritals = resMaritals;
      religions = resReligions;
      banks = resBanks;
      emergencyRelations = resEmergencyRelations;
      leaveTypes = resLeaveTypes;
      cities = resCities;
      provinces = resProvinces;

      return {
        users,
        employees,
        leaves,
        helpdesks,
        helpdeskCategories,
        organizations,
        divisions,
        companies,
        placements,
        genders,
        maritals,
        religions,
        banks,
        emergencyRelations,
        leaveTypes,
        cities,
        provinces,
      };
    }
  </script>

  @yield('head')
</head>

<body>
  <div id="alertContainer"></div>
  <div id="loadingOverlay" class="loading-overlay d-none">
    <img src="{{ asset('images/loading.gif') }}" alt="Loading..." class="loading-spinner">
    <div class="loading-text text-white">Loading...</div>
  </div>

  <!-- PWA Install Banner -->
  <div id="pwaInstallBanner" class="pwa-install-banner d-none">
    <div class="d-flex align-items-center justify-content-between">
      <div class="d-flex align-items-center me-2">
        <img src="{{ asset('images/logo-mini.png') }}" alt="App Icon" style="width: 40px; height: 40px; border-radius: 10px; object-fit: cover;" class="me-2 shadow-sm">
        <div>
          <div class="fw-bold" style="font-size: 13px; color: #1e293b;">Install Sintesa HRIS</div>
          <small class="text-muted" style="font-size: 11px;">Akses cepat & dapat dibuka offline</small>
        </div>
      </div>
      <div class="d-flex align-items-center">
        <button id="btnPwaInstall" class="btn btn-sm btn-primary rounded-pill px-3 py-1 me-2 fw-semibold" style="font-size: 12px; background-color: #0073e6; border: none;">
          Install
        </button>
        <button type="button" class="btn-close" style="font-size: 10px;" onclick="closePwaBanner()"></button>
      </div>
    </div>
  </div>

  @yield('content')

  @yield('scripts')

  <script>
    let deferredPrompt = null;

    if ('serviceWorker' in navigator) {
      window.addEventListener('load', function() {
        navigator.serviceWorker.register('/sw.js').then(function(reg) {
          console.log('PWA ServiceWorker registered with scope:', reg.scope);
        }).catch(function(err) {
          console.log('PWA ServiceWorker registration failed:', err);
        });
      });
    }

    // Capture Chrome/Android install prompt
    window.addEventListener('beforeinstallprompt', (e) => {
      e.preventDefault();
      deferredPrompt = e;
      document.getElementById('pwaInstallBanner').classList.remove('d-none');
    });

    document.getElementById('btnPwaInstall')?.addEventListener('click', async () => {
      if (deferredPrompt) {
        deferredPrompt.prompt();
        const { outcome } = await deferredPrompt.userChoice;
        console.log('Install outcome:', outcome);
        deferredPrompt = null;
        closePwaBanner();
      }
    });

    function closePwaBanner() {
      document.getElementById('pwaInstallBanner').classList.add('d-none');
    }

    // Safari iOS helper check
    const isIos = () => {
      const userAgent = window.navigator.userAgent.toLowerCase();
      return /iphone|ipad|ipod/.test(userAgent);
    };
    const isInStandaloneMode = () => ('standalone' in window.navigator) && (window.navigator.standalone);

    if (isIos() && !isInStandaloneMode()) {
      // Prompt iOS users after 3 seconds if not installed
      setTimeout(() => {
        const iosBanner = document.getElementById('pwaInstallBanner');
        if (iosBanner && iosBanner.classList.contains('d-none')) {
          iosBanner.querySelector('.text-muted').innerText = 'Tekan Share lalu "Tambahkan ke Utama"';
          iosBanner.querySelector('#btnPwaInstall').style.display = 'none';
          iosBanner.classList.remove('d-none');
        }
      }, 3000);
    }
  </script>
</body>

</html>
