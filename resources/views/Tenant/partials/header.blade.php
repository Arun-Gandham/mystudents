<header class="app-header">
  <nav class="navbar navbar-expand-lg h-100">
    <div class="container-fluid">
      <!-- Logo -->
      <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('superadmin.dashboard') }}">
        <span class="brand-icon d-inline-flex align-items-center justify-content-center rounded-3">
          <i class="bi bi-motherboard"></i>
        </span>
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
                 placeholder="Search teachers, students…" aria-label="Search">
        </div>
        <div id="searchResults" class="search-results"></div>
      </form>

      <!-- Right cluster -->
      <div class="d-flex align-items-center gap-2">

        <!-- Notifications -->
        <button class="btn position-relative" aria-label="Notifications">
          <i class="bi bi-bell"></i>
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">4</span>
        </button>

        <!-- Profile -->
        <div class="dropdown">
          <button class="btn btn-outline-secondary d-flex align-items-center gap-2 dropdown-toggle" data-bs-toggle="dropdown">
              <img class="rounded-circle" src="https://i.pravatar.cc/40?img=5" alt="avatar" width="28" height="28">
              <span class="d-none d-sm-inline">
                  {{ auth()->user() ? \Illuminate\Support\Str::limit(auth()->user()->full_name, 20, '...') : '' }}
              </span>
          </button>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profile</a></li>
            <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Settings</a></li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <form method="POST" action="{{ tenant_route('tenant.logout') }}">
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