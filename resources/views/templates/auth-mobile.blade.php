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
  <meta name="theme-color" content="#0073e6">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="default">
  <meta name="apple-mobile-web-app-title" content="Sintesa HRIS">
  <link rel="apple-touch-icon" href="{{ asset('images/logo-sintesa.jpg') }}">
  <!-- iOS Splash Screens -->
  <link rel="apple-touch-startup-image" href="{{ asset('images/icons/icon-512x512.png') }}">
  <link rel="apple-touch-startup-image" href="{{ asset('images/icons/icon-512x512.png') }}" media="(device-width: 430px) and (device-height: 932px) and (-webkit-device-pixel-ratio: 3)">
  <link rel="apple-touch-startup-image" href="{{ asset('images/icons/icon-512x512.png') }}" media="(device-width: 393px) and (device-height: 852px) and (-webkit-device-pixel-ratio: 3)">
  <link rel="apple-touch-startup-image" href="{{ asset('images/icons/icon-512x512.png') }}" media="(device-width: 428px) and (device-height: 926px) and (-webkit-device-pixel-ratio: 3)">
  <link rel="apple-touch-startup-image" href="{{ asset('images/icons/icon-512x512.png') }}" media="(device-width: 390px) and (device-height: 844px) and (-webkit-device-pixel-ratio: 3)">
  <link rel="apple-touch-startup-image" href="{{ asset('images/icons/icon-512x512.png') }}" media="(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3)">

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



  <script>
    function showAlert(type, message) {
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

  @yield('head')
</head>

<body>
  <div id="alertContainer"></div>
  <div id="loadingOverlay" class="loading-overlay d-none">
    <img src="{{ asset('images/loading.gif') }}" alt="Loading..." class="loading-spinner">
    <div class="loading-text text-white">Loading...</div>
  </div>

  @yield('content')

  <script>
    if ('serviceWorker' in navigator) {
      window.addEventListener('load', function() {
        navigator.serviceWorker.register('/sw.js').then(function(reg) {
          console.log('PWA ServiceWorker registered with scope:', reg.scope);
        }).catch(function(err) {
          console.log('PWA ServiceWorker registration failed:', err);
        });
      });
    }
  </script>
</body>

</html>
