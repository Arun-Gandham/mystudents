@php $perms = auth()->user()->getPermissions(); @endphp
<aside class="sidebar d-none d-lg-flex p-3" aria-label="Sidebar">
  <div class="sidebar-inner">
    <!-- Menu (icons + labels; no section headers) -->
    <div class="menu-scroll">
      <ul class="nav nav-pills flex-column gap-1">
        <li class="nav-item">
          <a href="{{ tenant_route('tenant.dashboard') }}" class="nav-link {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i><span class="label">Dashboard</span>
          </a>
        </li>
        @if(in_array('school:view', $perms) || in_array('school:create', $perms) || in_array('school:edit', $perms))
        <li class="nav-item">
          <a class="nav-link collapsed" data-bs-toggle="collapse" href="#schoolsMenu" role="button" 
             aria-expanded="false" aria-controls="schoolsMenu">
            <i class="bi bi-building me-2"></i>
            Schools new
            <i class="bi bi-chevron-down ms-auto small"></i>
          </a>
          <div class="collapse {{ in_array('school:view', $perms) || in_array('school:create', $perms) || in_array('school:edit', $perms) ? 'show' : '' }}" id="schoolsMenu">
            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small ms-4">

              @if(in_array('school:view', $perms))
                <li>
                  <a class="nav-link {{ request()->routeIs('tenant.dashboard') ? 'active' : '' }}"
                     href="{{ tenant_route('tenant.dashboard') }}">
                    <i class="bi bi-list-ul me-2"></i> List Schools
                  </a>
                </li>
              @endif

              @if(in_array('school:create', $perms))
                <li>
                  <a class="nav-link {{ request()->routeIs('tenant.dashboard') ? 'active' : '' }}"
                     href="{{ tenant_route('tenant.dashboard') }}">
                    <i class="bi bi-plus-circle me-2"></i> Add School
                  </a>
                </li>
              @endif

              @if(in_array('school:edit', $perms))
                <li>
                  <a class="nav-link"
                     href="#">
                    <i class="bi bi-pencil-square me-2"></i> Edit School
                  </a>
                </li>
              @endif

            </ul>
          </div>
        </li>
      @endif
        <li class="nav-item"><a href="{{ route('superadmin.school.index') }}" class="nav-link {{ request()->routeIs('superadmin.school.index') ? 'active' : '' }}"><i class="bi bi-buildings"></i><span class="label">Schools</span></a></li>
        <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-people"></i><span class="label">Users</span></a></li>
        <li class="nav-item"><a href="{{ tenant_route('tenant.roles.index') }}" class="nav-link {{ request()->routeIs('tenant.roles.*') ? 'active' : '' }}"><i class="bi bi-shield-lock"></i><span class="label">Roles</span></a></li>
                <li class="nav-item"><a href="{{ tenant_route('tenant.permissions.index') }}" class="nav-link {{ request()->routeIs('tenant.permissions.*') ? 'active' : '' }}"><i class="bi bi-shield-lock"></i><span class="label">Permissions</span></a></li>
        <li class="nav-item"><a href="{{ tenant_route('tenant.academic_years.index') }}" class="nav-link {{ request()->routeIs('tenant.academic_years.*') ? 'active' : '' }}"><i class="bi bi-shield-lock"></i><span class="label">Acadamic Years</span></a></li>
        <li class="nav-item"><a href="{{ tenant_route('tenant.grades.index') }}" class="nav-link {{ request()->routeIs('tenant.grades.*') ? 'active' : '' }}"><i class="bi bi-shield-lock"></i><span class="label">Grades</span></a></li>
        <li class="nav-item"><a href="{{ tenant_route('tenant.sections.index') }}" class="nav-link {{ request()->routeIs('tenant.sections.*') ? 'active' : '' }}"><i class="bi bi-shield-lock"></i><span class="label">Sections</span></a></li>
        <li class="nav-item"><a href="{{ tenant_route('tenant.timetables.index') }}" class="nav-link {{ request()->routeIs('tenant.timetables.*') ? 'active' : '' }}"><i class="bi bi-shield-lock"></i><span class="label">Time Tables</span></a></li>
        <li class="nav-item"><a href="{{ tenant_route('tenant.school_holidays.index') }}" class="nav-link {{ request()->routeIs('tenant.school_holidays.*') ? 'active' : '' }}"><i class="bi bi-shield-lock"></i><span class="label">Holidays</span></a></li>
        <li class="nav-item"><a href="{{ tenant_route('tenant.calendar.index') }}" class="nav-link {{ request()->routeIs('tenant.calendar.index') ? 'active' : '' }}"><i class="bi bi-shield-lock"></i><span class="label">Calender</span></a></li>
        <li class="nav-item"><a href="{{ tenant_route('tenant.subjects.index') }}" class="nav-link {{ request()->routeIs('tenant.subjects.index') ? 'active' : '' }}"><i class="bi bi-shield-lock"></i><span class="label">Subjects</span></a></li>
        <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-cash-stack"></i><span class="label">Billing</span></a></li>
        <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-clipboard-data"></i><span class="label">Reports</span></a></li>
        <li class="nav-item"><a href="#" class="nav-link"><i class="bi bi-gear"></i><span class="label">System Settings</span></a></li>
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
