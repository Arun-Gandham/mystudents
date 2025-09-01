<header class="app-header">
  <nav class="navbar navbar-expand-lg h-100">
    <div class="container-fluid">
      <!-- Logo -->
      <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('superadmin.dashboard') }}">
        <span class="brand-icon d-inline-flex align-items-center justify-content-center rounded-3">
          <i class="bi bi-motherboard"></i>
        </span>
        <span class="fw-bold d-none d-sm-inline brand-text">SMS Control</span>
        <span class="fw-bold d-none d-sm-inline brand-text">Tenant</span>
      </a>

      <!-- Mobile menu -->
      <button class="btn d-lg-none ms-auto" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar" aria-label="Open menu">
        <i class="bi bi-list fs-3"></i>
      </button>

      <!-- Center search -->
      <form class="mx-auto d-none d-lg-block header-search" role="search" autocomplete="off">
        <div class="input-group">
          <span class="input-group-text bg-transparent border-end-0"><i class="bi bi-search"></i></span>
          <input id="globalSearch" class="form-control border-start-0" type="search"
                 placeholder="Search schools, teachers, students…" aria-label="Search">
        </div>
        <div id="searchResults" class="search-results"></div>
      </form>

      <!-- Right cluster -->
      <div class="d-flex align-items-center gap-2">
        <div class="dropdown">
          <button class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown"><i class="bi bi-palette"></i></button>
          <div class="dropdown-menu dropdown-menu-end p-3" style="width:260px;">
            <div class="mb-2 small text-muted">Theme</div>
            <div class="btn-group w-100 mb-3">
              <button class="btn btn-light border" data-theme="light"><i class="bi bi-sun"></i> Light</button>
              <button class="btn btn-dark" data-theme="dark"><i class="bi bi-moon"></i> Dark</button>
            </div>
            <div class="mb-2 small text-muted">Brand color</div>
            <div class="d-flex flex-wrap gap-2">
              <button class="btn btn-sm border" data-brand="#2563eb" style="background:#2563eb"></button>
              <button class="btn btn-sm border" data-brand="#7c3aed" style="background:#7c3aed"></button>
              <button class="btn btn-sm border" data-brand="#dc2626" style="background:#dc2626"></button>
              <button class="btn btn-sm border" data-brand="#16a34a" style="background:#16a34a"></button>
              <button class="btn btn-sm border" data-brand="#0ea5e9" style="background:#0ea5e9"></button>
              <button class="btn btn-sm border" data-brand="#f59e0b" style="background:#f59e0b"></button>
            </div>
          </div>
        </div>

        <button class="btn position-relative" aria-label="Notifications">
          <i class="bi bi-bell"></i>
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">4</span>
        </button>

        <div class="dropdown">
          <button class="btn btn-outline-secondary d-flex align-items-center gap-2 dropdown-toggle" data-bs-toggle="dropdown">
            <img class="rounded-circle" src="https://i.pravatar.cc/40?img=5" alt="avatar" width="28" height="28">
            <span class="d-none d-sm-inline">Profile</span>
          </button>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profile</a></li>
            <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Settings</a></li>
            <li><hr class="dropdown-divider"></li>
            <li>
            <form method="POST" action="{{ route('superadmin.logout') }}">
              @csrf
              <button type="submit" class="dropdown-item text-danger">
                <i class="bi bi-box-arrow-right me-2"></i>Logout
              </button>
            </form>
          </li>
          </ul>
        </div>
      </div>
    </div>
  </nav>
</header>