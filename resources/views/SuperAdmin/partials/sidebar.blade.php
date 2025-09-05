<aside class="sidebar d-none d-lg-flex p-3" aria-label="Sidebar">
  <div class="sidebar-inner">
    <!-- Menu (icons + labels; no section headers) -->
    <div class="menu-scroll">
      <ul class="nav nav-pills flex-column gap-1">
        <li class="nav-item">
          <a href="{{ route('superadmin.dashboard') }}" class="nav-link {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i><span class="label">Dashboard</span>
          </a>
        </li>
        <li class="nav-item"><a href="{{ route('superadmin.school.index') }}" class="nav-link {{ request()->routeIs('superadmin.school.index') ? 'active' : '' }}"><i class="bi bi-buildings"></i><span class="label">Schools</span></a></li>
       </ul>
    </div>

    <!-- Collapse at bottom -->
    <div class="sidebar-bottom">
      <button id="collapseToggle" class="btn btn-outline-secondary" title="Toggle sidebar">
        <i class="bi bi-layout-sidebar-inset"></i>
        <span class="btn-text">Collapse</span> {{-- hidden by CSS; icon-only --}}
      </button>
    </div>
  </div>
</aside>