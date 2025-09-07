@php $perms = auth()->check() ? auth()->user()->getPermissions() : []; @endphp
<aside class="sidebar d-none d-lg-flex p-3" aria-label="Sidebar">
  <div class="sidebar-inner">
    <div class="menu-scroll">
      <ul class="nav nav-pills flex-column gap-1">

        {{-- Dashboard --}}
        <li class="nav-item">
          <a href="{{ tenant_route('tenant.dashboard') }}" 
             class="nav-link {{ request()->routeIs('tenant.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2 me-2"></i>
            <span class="label">Dashboard</span>
          </a>
        </li>

        {{-- Schools --}}
        @if(in_array('school:view', $perms) || in_array('school:create', $perms) || in_array('school:edit', $perms))
        <li class="nav-item">
          <a class="nav-link collapsed" data-bs-toggle="collapse" href="#schoolsMenu">
            <i class="bi bi-building me-2"></i>
            Schools
            <i class="bi bi-chevron-down ms-auto small"></i>
          </a>
          <div class="collapse {{ in_array('school:view', $perms) ? 'show' : '' }}" id="schoolsMenu">
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
                <a class="nav-link" href="{{ tenant_route('tenant.dashboard') }}">
                  <i class="bi bi-plus-circle me-2"></i> Add School
                </a>
              </li>
              @endif
              @if(in_array('school:edit', $perms))
              <li>
                <a class="nav-link" href="#">
                  <i class="bi bi-pencil-square me-2"></i> Edit School
                </a>
              </li>
              @endif
            </ul>
          </div>
        </li>
        @endif

        <li class="nav-item">
          <a href="{{ tenant_route('tenant.roles.index') }}" 
             class="nav-link {{ request()->routeIs('tenant.roles.*') ? 'active' : '' }}">
            <i class="bi bi-shield-lock me-2"></i> Roles
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ tenant_route('tenant.permissions.index') }}" 
             class="nav-link {{ request()->routeIs('tenant.permissions.*') ? 'active' : '' }}">
            <i class="bi bi-key me-2"></i> Permissions
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ tenant_route('tenant.academic_years.index') }}" 
             class="nav-link {{ request()->routeIs('tenant.academic_years.*') ? 'active' : '' }}">
            <i class="bi bi-calendar2-range me-2"></i> Academic Years
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ tenant_route('tenant.grades.index') }}" 
             class="nav-link {{ request()->routeIs('tenant.grades.*') ? 'active' : '' }}">
            <i class="bi bi-diagram-2 me-2"></i> Grades
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ tenant_route('tenant.sections.index') }}" 
             class="nav-link {{ request()->routeIs('tenant.sections.*') ? 'active' : '' }}">
            <i class="bi bi-grid-3x3-gap me-2"></i> Sections
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ tenant_route('tenant.timetables.index') }}" 
             class="nav-link {{ request()->routeIs('tenant.timetables.*') ? 'active' : '' }}">
            <i class="bi bi-calendar3-week me-2"></i> Time Tables
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ tenant_route('tenant.school_holidays.index') }}" 
             class="nav-link {{ request()->routeIs('tenant.school_holidays.*') ? 'active' : '' }}">
            <i class="bi bi-calendar-event me-2"></i> Holidays
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ tenant_route('tenant.calendar.index') }}" 
             class="nav-link {{ request()->routeIs('tenant.calendar.index') ? 'active' : '' }}">
            <i class="bi bi-calendar4-week me-2"></i> Calendar
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ tenant_route('tenant.subjects.index') }}" 
             class="nav-link {{ request()->routeIs('tenant.subjects.index') ? 'active' : '' }}">
            <i class="bi bi-journal-bookmark me-2"></i> Subjects
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ tenant_route('tenant.staff.index') }}" 
             class="nav-link {{ request()->routeIs('tenant.staff.*') ? 'active' : '' }}">
            <i class="bi bi-people me-2"></i> Staff
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ tenant_route('tenant.applications.index') }}" 
             class="nav-link {{ request()->routeIs('tenant.applications.*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-text me-2"></i> Student Applications
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ tenant_route('tenant.admissions.index') }}" 
             class="nav-link {{ request()->routeIs('tenant.admissions.*') ? 'active' : '' }}">
            <i class="bi bi-person-check me-2"></i> Student Admissions
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ tenant_route('tenant.students.index') }}" 
             class="nav-link {{ request()->routeIs('tenant.students.*') ? 'active' : '' }}">
            <i class="bi bi-person-lines-fill me-2"></i> Students
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ tenant_route('tenant.staffAttendance.list') }}" 
             class="nav-link {{ request()->routeIs('tenant.staffAttendance.*') ? 'active' : '' }}">
            <i class="bi bi-clock-history me-2"></i> Staff Attendance
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ tenant_route('tenant.studentAttendance.index') }}" 
             class="nav-link {{ request()->routeIs('tenant.studentAttendance.*') ? 'active' : '' }}">
            <i class="bi bi-clipboard-check me-2"></i> Students Attendance
          </a>
        </li>

        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="bi bi-gear me-2"></i> System Settings
          </a>
        </li>

      </ul>
    </div>

    <div class="sidebar-bottom">
      <button id="collapseToggle" class="btn btn-outline-secondary" title="Toggle sidebar">
        <i class="bi bi-layout-sidebar-inset"></i>
        <span class="btn-text">Collapse</span>
      </button>
    </div>
  </div>
</aside>
