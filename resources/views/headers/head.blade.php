<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ config('app.name') }}</title>


{{-- RESOURCES FONT FAMILLY --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&display=swap"
  rel="stylesheet">
{{-- RESOURCES ICON PACK --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="{{ asset('plugins/pe7icon/pe-icon-7-stroke/css/pe-icon-7-stroke.css') }}" />
<link rel="stylesheet" href="https://cdn.lineicons.com/4.0/lineicons.css" />
<link rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/5.3.45/css/materialdesignicons.css"
  integrity="sha256-NAxhqDvtY0l4xn+YVa6WjAcmd94NNfttjNsDmNatFVc=" crossorigin="anonymous" />
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="shortcut icon" href="{{ asset('images/logo-hr.ico') }}" type="image/ico">

{{-- JQUERY --}}
<script src="{{ asset('templates/mazer/js/jquery/jquery.min.js') }}"></script>


{{-- BOOTSTRAP --}}
{{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
  integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
  integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
  integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script> --}}


{{-- DATEPICKER --}}
<script src="https://unpkg.com/gijgo@1.9.14/js/gijgo.min.js" type="text/javascript"></script>
<link href="https://unpkg.com/gijgo@1.9.14/css/gijgo.min.css" rel="stylesheet" type="text/css" />

{{-- MAZER --}}
<script src="{{ asset('templates/mazer/js/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
<script src=" {{ asset('templates/mazer/js/app.js') }}"></script>
<script src="{{ asset('templates/mazer/js/initTheme.js') }}"></script>
<link rel="stylesheet" crossorigin href="{{ asset('templates/mazer/css/app.css') }}">
<link rel="stylesheet" crossorigin href="{{ asset('templates/mazer/css/iconly.css') }}">

{{-- SUMMERNOTE --}}
<link rel="stylesheet" crossorigin href="{{ asset('templates/mazer/css/summernote/summernote-lite.css') }}">
<link rel="stylesheet" crossorigin href="{{ asset('templates/mazer/css/summernote/form-editor-summernote.css') }}">
<script src=" {{ asset('templates/mazer/css/summernote/summernote-lite.min.js') }}"></script>

<link rel="stylesheet" type="text/css" href="{{ asset('css/icons.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ asset('css/style-helper.css') }}" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>

<script src="{{ asset('js/StringFormat.js') }}"></script>
<script src="{{ asset('js/utils/Utils.js') }}"></script>
<script src="{{ asset('js/utils/DateUtils.js') }}"></script>
<script src="{{ asset('js/utils/ArrayUtils.js') }}"></script>

<style>
  .main-container {
    padding: 1rem;
  }

  #alertContainer {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    max-width: 300px;
  }

  #alertContainer .alert {
    margin-bottom: 10px;
    opacity: 0;
    transition: opacity 0.3s ease;
  }

  #alertContainer .alert.show {
    opacity: 1;
  }

  .loading-overlay {
    display: flex;
    flex-direction: column;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 9999;
    justify-content: center;
    align-items: center;
  }

  .loading-spinner {
    width: 50px;
    height: 50px;
  }

  @media (min-width: 425px) {
    .main-container {
      padding: 2rem;
    }
  }

  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: translateY(20px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .fade-in {
    animation: fadeIn 0.5s ease-in-out;
  }
</style>

<script>
  function showAlert(type, message) {
    $('#alertContainer').html('');

    let icon;
    switch (type) {
      case 'success':
        icon = '<i class="bi bi-check-circle"></i>';
        break;
      case 'warning':
        icon = '<i class="bi bi-exclamation-triangle"></i>';
        break;
      case 'danger':
        icon = '<i class="bi bi-file-excel"></i>';
        break;
      case 'info':
        icon = '<i class="bi bi-info-circle"></i>';
        break;
      case 'primary':
        icon = '<i class="bi bi-star"></i>';
        break;
      case 'secondary':
        icon = '<i class="bi bi-star"></i>';
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

@yield('header')

<body>
  <div id="alertContainer"></div>
  <div id="loadingOverlay" class="loading-overlay d-none">
    <img src="{{ asset('images/loading.gif') }}" alt="Loading..." class="loading-spinner">
    <div class="loading-text text-white">Loading...</div>
  </div>

  @yield('body')
</body>
