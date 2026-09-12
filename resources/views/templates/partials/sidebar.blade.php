<div class="sidebar active">
  <div class="sidebar-header active">
    <div class="d-flex justify-content-center align-items-center">
      <div class="logo">
        <a href="{{ route('main') }}" class="d-flex align-items-center text-decoration-none">
          <img src="{{ asset('images/logo-sintesa.jpg') }}" alt="Logo Sintesa" class="me-2" style="height: 38px; width: auto; object-fit: contain;">
          <div class="brand-text text-start">
            <span class="d-block leading-tight" style="font-size: 1.25rem; font-weight: 900; letter-spacing: -0.3px;"><span style="color: #0073e6;">SINTESA</span> <span style="color: #00a651;">HRIS</span></span>
            <small class="text-uppercase d-block" style="font-size: 0.62rem; letter-spacing: 0.8px; font-weight: 800; color: #00a651;">SINTESA TALENTA ASIA</small>
          </div>
        </a>
      </div>
      <div class="sidebar-toggler x">
        <a href="#" class="d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
      </div>
    </div>
  </div>
  @php($level = 0)
  <div class="nav-container">
    @require('sidebar-menu', ['items' => $treeMenu, 'level' => $level])
  </div>
</div>
<div class="sidebar-backdrop" id="backdrop"></div>
