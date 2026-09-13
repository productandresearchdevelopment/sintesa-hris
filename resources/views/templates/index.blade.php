<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ config('app.name') }}</title>

  <link rel="stylesheet" href="{{ asset('css/mobile.css') }}" />
  <link rel="shortcut icon" href="{{ asset('images/logo-hr.ico') }}" type="image/ico">

  <!-- PWA Manifest & Meta Tags -->
  <link rel="manifest" href="{{ asset('manifest.json') }}">
  <meta name="theme-color" content="#0073e6">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="default">
  <meta name="apple-mobile-web-app-title" content="Sintesa HRIS">
  <link rel="apple-touch-icon" href="{{ asset('images/logo-sintesa.jpg') }}">

  {{-- SWEETALERT --}}
  <link rel="stylesheet" href="{{ asset('templates/mazer/css/sweetalert/sweetalert2.min.css') }}">
  <link rel="stylesheet" crossorigin
    href="{{ asset('templates/mazer/css/sweetalert/extra-component-sweetalert.css') }}">

  {{-- MAZER MAIN CSS --}}
  <link rel="stylesheet" crossorigin href="{{ asset('templates/mazer/css/app.css') }}">

  {{-- ICON --}}
  <link rel="stylesheet" crossorigin href="{{ asset('templates/mazer/css/iconly.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/pe7icon/pe-icon-7-stroke/css/pe-icon-7-stroke.css') }}" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>

</head>

<body>
  <script src="{{ asset('templates/mazer/js/initTheme.js') }}"></script>
  <script src="{{ asset('templates/mazer/js/jquery/jquery.min.js') }}"></script>

  <div id="app">
    @require('partials.sidebar')
    <div id="main">
      @require('partials.top')
      <div class="page-content">
        <iframe id="iframe-content" src="{{ route('dashboard.index') }}"></iframe>
        {{-- @include('components.chatbot') --}}
      </div>
      <div id="page-profile" class="p-4 rounded-4 border" style="display: none;">
        @require('profile')
      </div>
      @require('partials.footer')
    </div>
  </div>

  <script src="{{ asset('templates/mazer/js/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
  <script src=" {{ asset('templates/mazer/js/app.js') }}"></script>
  <script src="{{ asset('templates/mazer/js/sweetalert/sweetalert2.min.js') }}"></script>
  <script src="{{ asset('templates/mazer/js/sweetalert/pages/sweetalert2.js') }}"></script>
  <script src="https://unpkg.com/@popperjs/core@2/dist/umd/popper.js"></script>
  <script src="https://unpkg.com/@popperjs/core@2"></script>

  <script>
    $(document).ready(function() {
      const $sidebar = $('.sidebar');
      const $sidebarHeader = $('.sidebar-header');
      const $backdrop = $('.sidebar-backdrop');
      const $mainContent = $('#main');
      const $hamBtn = $('.ham-btn');
      const $body = $('body');

      // Fungsi untuk membuka sidebar
      const openSidebar = () => {
        $body.css('overflow', 'hidden');
        $sidebar.removeClass('inactive').addClass('active');
        $sidebarHeader.removeClass('inactive').addClass('active');
        if ($(window).width() < 1199) {
          $backdrop.show();
          $mainContent.css('margin-left', '0');
        } else {
          $backdrop.hide();
          $mainContent.css('margin-left', '300px');
        }
      };

      // Fungsi untuk menutup sidebar
      const closeSidebar = () => {
        $sidebar.removeClass('active').addClass('inactive');
        $sidebarHeader.removeClass('active').addClass('inactive');
        $backdrop.hide();
        $mainContent.css('margin-left', '0');
      };

      // Fungsi untuk toggle sidebar
      const toggleSidebar = () => {
        $sidebar.hasClass('active') ? closeSidebar() : openSidebar();
      };

      // Event listener pada sidebar-toggler dan hamBtn
      $('.sidebar-toggler, .ham-btn').click(function(e) {
        e.preventDefault();
        toggleSidebar();
      });

      // Menutup sidebar saat klik backdrop
      $backdrop.click(closeSidebar);

      // Handle resize window
      const handleResize = () => {
        if ($(window).width() >= 1199) {
          $backdrop.hide();
          $mainContent.css('margin-left', '300px');
          $sidebar.removeClass('inactive').addClass('active');
          $sidebarHeader.removeClass('inactive').addClass('active');
        } else if ($sidebar.hasClass('active')) {
          closeSidebar();
        }
        $('.sidebar .dropdown-menu').removeClass('show'); // Tutup semua dropdown saat resize
      };

      // Tambahkan event listener untuk resize
      $(window).resize(handleResize);

      handleResize();

      // Handle dropdown toggle for nested menus
      $('.sidebar .nav-item.dropdown .dropdown-toggle').click(function(event) {
        event.stopPropagation();

        const $dropdownMenu = $(this).next('.dropdown-menu');
        const $currentLevel = $(this).closest('li').data('level');

        closeDropdownsAtLevel($currentLevel, $dropdownMenu);

        if ($dropdownMenu.hasClass('show')) {
          $dropdownMenu.removeClass('show');
        } else {
          $dropdownMenu.addClass('show');

          let offset = [0, 0];
          let placement = 'right-start';

          if ($currentLevel === 2) {
            offset = [0, 45];
          } else if ($currentLevel > 2) {
            offset = [0, 8];
          }

          // Cek lebar layar untuk menentukan apakah flip diaktifkan
          const flipEnabled = window.innerWidth < 768;
          const fallbackPlacements = window.innerWidth < 768 ? ['left-start', 'bottom-start'] : [];

          Popper.createPopper(this, $dropdownMenu[0], {
            placement: placement,
            modifiers: [{
                name: 'flip',
                options: {
                  fallbackPlacements,
                  enabled: flipEnabled,
                },
              },
              {
                name: 'offset',
                options: {
                  offset,
                },
              },
              {
                name: 'preventOverflow',
                options: {
                  boundary: 'viewport',
                  padding: {
                    top: 40,
                    bottom: 40
                  },
                },
              },
              {
                name: 'computeStyles',
                options: {
                  adaptive: false,
                },
              },
            ],
          });
        }
      });

      // Fungsi untuk menutup dropdown pada level tertentu
      function closeDropdownsAtLevel(level, $currentMenu) {
        $('li[data-level="' + level + '"] .dropdown-menu').each(function() {
          if (this !== $currentMenu[0]) {
            $(this).removeClass('show');
            closeChildDropdowns($(this));
          }
        });
      }

      // Fungsi untuk menutup dropdown anak
      function closeChildDropdowns($parentMenu) {
        $parentMenu.find('.sidebar .dropdown-menu').each(function() {
          $(this).removeClass('show');
        });
      }

      // Menutup semua dropdown saat klik di luar
      $(document).click(function(event) {
        if (!$(event.target).closest('.sidebar .dropdown-menu').length) {
          $('.sidebar .dropdown-menu').removeClass('show');
        }
      });

      // Sync active sidebar highlighting and title/breadcrumbs on internal iframe page load
      $('#iframe-content').on('load', function() {
        try {
          var iframeFullUrl = this.contentWindow.location.href;
          var iframeUrlPath = this.contentWindow.location.pathname;

          iframeUrlPath = iframeUrlPath.split('?')[0].split('#')[0];
          if (iframeUrlPath.endsWith('/') && iframeUrlPath.length > 1) {
            iframeUrlPath = iframeUrlPath.slice(0, -1);
          }

          var matchedLink = null;

          $('.sidebar .nav-link').each(function() {
            var href = $(this).attr('href');
            if (!href || href === '#' || href.startsWith('javascript:')) return;

            var hrefPath = href.split('?')[0].split('#')[0];
            if (hrefPath.indexOf('://') !== -1) {
              try {
                hrefPath = new URL(hrefPath).pathname;
              } catch (e) {}
            }
            if (hrefPath.endsWith('/') && hrefPath.length > 1) {
              hrefPath = hrefPath.slice(0, -1);
            }

            if (iframeUrlPath === hrefPath) {
              matchedLink = $(this);
              return false;
            }
          });

          if (!matchedLink) {
            $('.sidebar .nav-link').each(function() {
              var href = $(this).attr('href');
              if (!href || href === '#' || href.startsWith('javascript:')) return;

              var hrefPath = href.split('?')[0].split('#')[0];
              if (hrefPath.indexOf('://') !== -1) {
                try {
                  hrefPath = new URL(hrefPath).pathname;
                } catch (e) {}
              }
              if (hrefPath.endsWith('/') && hrefPath.length > 1) {
                hrefPath = hrefPath.slice(0, -1);
              }

              if (iframeUrlPath.startsWith(hrefPath) && hrefPath !== '/' && hrefPath !== '') {
                matchedLink = $(this);
                return false;
              }
            });
          }

          if (matchedLink) {
            $('.sidebar .nav-link').removeClass('active');
            matchedLink.addClass('active');

            var pageName = matchedLink.attr('data-page');
            if (pageName) {
              var headingTitle = pageName.charAt(0).toUpperCase() + pageName.slice(1).toLowerCase();
              $('#heading-title').text(headingTitle);
              updateBreadcrumbs(pageName);
            }
          }
        } catch (e) {
          console.error("Sidebar sync error:", e);
        }
      });

      // Event handler untuk item nav-link
      $('.sidebar .nav-link:not(.dropdown-toggle)').click(function(e) {
        e.preventDefault();

        var route = $(this).attr('href');
        var pageName = $(this).attr('data-page');

        if (route) {
          switchPage(route, pageName);
        }
        return false;
      });

      // Event handler untuk item dropdown
      $('.dropdown-item').click(function(e) {
        e.preventDefault();

        var pageName = $(this).attr('data-page');
        if (pageName === 'logout') {
          document.cookie = "lastPage=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
          window.location.href = '{{ route('logout') }}';
        } else {
          switchPage(pageName, pageName);
        }
        $(this).closest('.dropdown-menu').removeClass('show');
        return false;
      });

      // Fungsi untuk menangani perpindahan halaman
      function switchPage(route, pageName) {
        route = route || '{{ route('dashboard.index') }}';

        if (route === 'profile') {
          $('#iframe-content').hide();
          $('#iframe-content').attr('src', '');
          $('#page-profile').show();

          const lastPageData = {
            route: '{{ route('dashboard.index') }}',
            name: 'dashboard'
          };
          document.cookie = "lastPage=" + encodeURIComponent(JSON.stringify(lastPageData)) + "; path=/";
        } else {
          $('#page-profile').hide();
          $('#iframe-content').show();
          $('#iframe-content').attr('src', route);

          const lastPageData = {
            route: route,
            name: pageName
          };
          document.cookie = "lastPage=" + encodeURIComponent(JSON.stringify(lastPageData)) + "; path=/";
        }

        var headingTitle = pageName.charAt(0).toUpperCase() + pageName.slice(1).toLowerCase();
        $(parent.document).find('#heading-title').text(headingTitle);

        updateBreadcrumbs(pageName);
        updateActiveSidebar(route);
      }

      // 🔴 VERSION LAMA (Dashboard bisa diklik)
      // function updateBreadcrumbs(pageName) {
      //   var breadcrumbHtml = '';
      //   if (pageName === 'dashboard' || pageName === '') {
      //     breadcrumbHtml = '<li class="breadcrumb-item active" aria-current="page">Dashboard</li>';
      //   } else {
      //     breadcrumbHtml =
      //       '<li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}" data-page="dashboard">Dashboard</a></li>';
      //     breadcrumbHtml += '<li class="breadcrumb-item active" aria-current="page">' + pageName.charAt(0)
      //       .toUpperCase() +
      //       pageName.slice(1) + '</li>';
      //   }
      //   $(parent.document).find('.breadcrumb').html(breadcrumbHtml);
      // }


      // ✅ VERSION BARU (Dashboard NON-ACTIVE / tidak bisa diklik)
      function updateBreadcrumbs(pageName) {
        var breadcrumbHtml = '';

        if (pageName === 'dashboard' || pageName === '') {
          breadcrumbHtml =
            '<li class="breadcrumb-item active" aria-current="page">Dashboard</li>';
        } else {
          breadcrumbHtml =
            '<li class="breadcrumb-item">Dashboard</li>';

          breadcrumbHtml +=
            '<li class="breadcrumb-item active" aria-current="page">' +
            pageName.charAt(0).toUpperCase() +
            pageName.slice(1) +
            '</li>';
        }

        $(parent.document).find('.breadcrumb').html(breadcrumbHtml);
      }

      // Fungsi untuk memperbarui sidebar
      function updateActiveSidebar(route) {
        // Menghapus kelas active dari semua nav-link
        $('.sidebar .nav-link').removeClass('active');

        // Menambahkan kelas active pada nav-link yang sesuai dengan pageName
        $('.sidebar .nav-link').each(function() {
          var href = $(this).attr('href');
          var level = parseInt($(this).attr('data-level'));
          if (href === route) {
            $(this).addClass('active');
          }
        });
      }

      // Event delegation untuk klik pada breadcrumbs
      $(parent.document).on('click', '.breadcrumb-item a', function() {
        var route = $(this).attr('href');
        var pageName = $(this).attr('data-page');
        if (route) {
          switchPage(route, pageName);
        }
        return false;
      });

      function getLastPageFromCookie() {
        const name = "lastPage=";
        const decodedCookie = decodeURIComponent(document.cookie);
        const cookieArray = decodedCookie.split(';');
        for (let i = 0; i < cookieArray.length; i++) {
          let cookie = cookieArray[i].trim();
          if (cookie.indexOf(name) === 0) {
            return JSON.parse(cookie.substring(name.length, cookie.length));
          }
        }
        return null;
      }

      const lastPage = getLastPageFromCookie();
      if (lastPage) {
        switchPage(lastPage.route, lastPage.name);
      } else {
        switchPage('{{ route($homePage['route']) }}', '{{ $homePage['text'] }}');
      }
    });
  </script>

  @if (session('success'))
    <script>
      Swal.mixin({
        toast: true,
        icon: 'success',
        title: '{{ session('success') }}',
        animation: true,
        position: 'top-right',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
          toast.addEventListener('mouseenter', Swal.stopTimer)
          toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
      }).fire();
    </script>
  @endif

  @if (session('error'))
    <script>
      Swal.mixin({
        toast: true,
        icon: 'error',
        title: '{{ session('error') }}',
        animation: true,
        position: 'top-right',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
          toast.addEventListener('mouseenter', Swal.stopTimer)
          toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
      }).fire();
    </script>
  @endif

  <script>
    let deferredPromptDesktop = null;

    if ('serviceWorker' in navigator) {
      window.addEventListener('load', function() {
        navigator.serviceWorker.register('/sw.js').then(function(reg) {
          console.log('PWA ServiceWorker registered with scope:', reg.scope);
        }).catch(function(err) {
          console.log('PWA ServiceWorker registration failed:', err);
        });
      });
    }

    window.addEventListener('beforeinstallprompt', (e) => {
      e.preventDefault();
      deferredPromptDesktop = e;
      const btn = document.getElementById('btnPwaInstallDesktop');
      if (btn) {
        btn.classList.remove('d-none');
        btn.classList.add('d-inline-flex');
      }
    });

    document.getElementById('btnPwaInstallDesktop')?.addEventListener('click', async () => {
      if (deferredPromptDesktop) {
        deferredPromptDesktop.prompt();
        const { outcome } = await deferredPromptDesktop.userChoice;
        console.log('Desktop Install outcome:', outcome);
        deferredPromptDesktop = null;
        const btn = document.getElementById('btnPwaInstallDesktop');
        if (btn) btn.classList.add('d-none');
      }
    });
  </script>

</body>

</html>
