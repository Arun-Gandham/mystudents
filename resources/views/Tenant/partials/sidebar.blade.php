@php
    $route = request()->route() ? request()->route()->getName() : '';
@endphp

<aside class="sidebar d-none d-lg-flex p-3" aria-label="Sidebar">
  <div class="sidebar-inner">
    <div class="menu-scroll">
      <ul class="nav nav-pills flex-column gap-1">
        @can('fees:collect')
        {{-- Dashboard --}}
        <li class="nav-item">
          <a href="{{ tenant_route('tenant.dashboard') }}"
             class="nav-link {{ request()->routeIs('tenant.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2 me-2"></i>
            Dashboard
          </a>
        </li>
        @endcan

        {{-- Roles --}}
        <li class="nav-item">
          <a href="{{ tenant_route('tenant.roles.index') }}"
             class="nav-link {{ request()->routeIs('tenant.roles.*') ? 'active' : '' }}">
            <i class="bi bi-shield-lock me-2"></i> Roles
          </a>
        </li>

        {{-- Permissions --}}
        <li class="nav-item">
          <a href="{{ tenant_route('tenant.permissions.index') }}"
             class="nav-link {{ request()->routeIs('tenant.permissions.*') ? 'active' : '' }}">
            <i class="bi bi-key me-2"></i> Permissions
          </a>
        </li>

        {{-- Academic Years --}}
        <li class="nav-item">
          <a href="{{ tenant_route('tenant.academic_years.index') }}"
             class="nav-link {{ request()->routeIs('tenant.academic_years.*') ? 'active' : '' }}">
            <i class="bi bi-calendar2-range me-2"></i> Academic Years
          </a>
        </li>

        {{-- Grades --}}
        <li class="nav-item">
          <a href="{{ tenant_route('tenant.grades.index') }}"
             class="nav-link {{ request()->routeIs('tenant.grades.*') ? 'active' : '' }}">
            <i class="bi bi-diagram-2 me-2"></i> Grades
          </a>
        </li>

        {{-- Sections --}}
        <li class="nav-item">
          <a href="{{ tenant_route('tenant.sections.index') }}"
             class="nav-link {{ request()->routeIs('tenant.sections.*') ? 'active' : '' }}">
            <i class="bi bi-grid-3x3-gap me-2"></i> Sections
          </a>
        </li>

        {{-- Timetables --}}
        <li class="nav-item">
          <a href="{{ tenant_route('tenant.timetables.index') }}"
             class="nav-link {{ request()->routeIs('tenant.timetables.*') ? 'active' : '' }}">
            <i class="bi bi-calendar3-week me-2"></i> Timetables
          </a>
        </li>

        {{-- Holidays --}}
        <li class="nav-item">
          <a href="{{ tenant_route('tenant.school_holidays.index') }}"
             class="nav-link {{ request()->routeIs('tenant.school_holidays.*') ? 'active' : '' }}">
            <i class="bi bi-calendar-event me-2"></i> Holidays
          </a>
        </li>

        {{-- Calendar --}}
        <li class="nav-item">
          <a href="{{ tenant_route('tenant.calendar.index') }}"
             class="nav-link {{ request()->routeIs('tenant.calendar.*') ? 'active' : '' }}">
            <i class="bi bi-calendar4-week me-2"></i> Calendar
          </a>
        </li>

        {{-- Subjects --}}
        <li class="nav-item">
          <a href="{{ tenant_route('tenant.subjects.index') }}"
             class="nav-link {{ request()->routeIs('tenant.subjects.*') ? 'active' : '' }}">
            <i class="bi bi-journal-bookmark me-2"></i> Subjects
          </a>
        </li>

        {{-- Staff --}}
        <li class="nav-item">
          <a href="{{ tenant_route('tenant.staff.index') }}"
             class="nav-link {{ request()->routeIs('tenant.staff.*') ? 'active' : '' }}">
            <i class="bi bi-people me-2"></i> Staff
          </a>
        </li>

        {{-- Student Applications --}}
        <li class="nav-item">
          <a href="{{ tenant_route('tenant.applications.index') }}"
             class="nav-link {{ request()->routeIs('tenant.applications.*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-text me-2"></i> Student Applications
          </a>
        </li>

        {{-- Student Admissions --}}
        <li class="nav-item">
          <a href="{{ tenant_route('tenant.admissions.index') }}"
             class="nav-link {{ request()->routeIs('tenant.admissions.*') ? 'active' : '' }}">
            <i class="bi bi-person-check me-2"></i> Student Admissions
          </a>
        </li>

        {{-- Students --}}
        <li class="nav-item">
          <a href="{{ tenant_route('tenant.students.index') }}"
             class="nav-link {{ request()->routeIs('tenant.students.*') ? 'active' : '' }}">
            <i class="bi bi-person-lines-fill me-2"></i> Students
          </a>
        </li>

        {{-- Attendance --}}
        @php
          $attendanceActive = request()->routeIs('tenant.staffAttendance.*')
            || request()->routeIs('tenant.studentAttendance.*');
        @endphp
        <li class="nav-item">
          <a class="nav-link {{ $attendanceActive ? '' : 'collapsed' }}" data-bs-toggle="collapse" href="#attendanceMenu">
            <i class="bi bi-clipboard-check me-2"></i>
            Attendance
            <i class="bi bi-chevron-down ms-auto small"></i>
          </a>
          <div class="collapse {{ $attendanceActive ? 'show' : '' }}" id="attendanceMenu">
            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small ms-4">

              <li>
                <a href="{{ tenant_route('tenant.staffAttendance.list') }}"
                   class="nav-link {{ request()->routeIs('tenant.staffAttendance.*') ? 'active' : '' }}">
                  <i class="bi bi-clock-history me-2"></i> Staff Attendance
                </a>
              </li>

              <li>
                <a href="{{ tenant_route('tenant.studentAttendance.index') }}"
                   class="nav-link {{ request()->routeIs('tenant.studentAttendance.*') ? 'active' : '' }}">
                  <i class="bi bi-clipboard-data me-2"></i> Student Attendance
                </a>
              </li>

            </ul>
          </div>
        </li>

        {{-- Exams --}}
        @php
          $examsActive = request()->routeIs('tenant.exams.*');
        @endphp
        <li class="nav-item">
          <a class="nav-link {{ $examsActive ? '' : 'collapsed' }}" data-bs-toggle="collapse" href="#examsMenu">
            <i class="bi bi-journal-check me-2"></i>
            Exams
            <i class="bi bi-chevron-down ms-auto small"></i>
          </a>
          <div class="collapse {{ $examsActive ? 'show' : '' }}" id="examsMenu">
            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small ms-4">

              <li>
                <a href="{{ tenant_route('tenant.exams.index') }}"
                   class="nav-link {{ request()->routeIs('tenant.exams.index') ? 'active' : '' }}">
                  <i class="bi bi-clipboard-check me-2"></i> Manage Exams
                </a>
              </li>

              <li>
                <a href="{{ tenant_route('tenant.exams.index') }}"
                   class="nav-link {{ request()->routeIs('tenant.exams.results.*') ? 'active' : '' }}">
                  <i class="bi bi-clipboard-data me-2"></i> Exam Results
                </a>
              </li>

            </ul>
          </div>
        </li>

        {{-- Fees --}}
        @php
          $feesActive = request()->routeIs('tenant.fees.fee-heads.*')
            || request()->routeIs('tenant.fees.section-fees.*')
            || request()->routeIs('tenant.fees.student-fee-items.*')
            || request()->routeIs('tenant.fees.fee-receipts.*');
        @endphp
        <li class="nav-item">
          <a class="nav-link {{ $feesActive ? '' : 'collapsed' }}" data-bs-toggle="collapse" href="#feesMenu">
            <i class="bi bi-cash-coin me-2"></i>
            Fees
            <i class="bi bi-chevron-down ms-auto small"></i>
          </a>
          <div class="collapse {{ $feesActive ? 'show' : '' }}" id="feesMenu">
            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small ms-4">

              <li>
                <a href="{{ tenant_route('tenant.fees.fee-heads.index') }}"
                   class="nav-link {{ request()->routeIs('tenant.fees.fee-heads.*') ? 'active' : '' }}">
                  <i class="bi bi-tags me-2"></i> Fee Heads
                </a>
              </li>

              <li>
                <a href="{{ tenant_route('tenant.fees.section-fees.index') }}"
                   class="nav-link {{ request()->routeIs('tenant.fees.section-fees.*') ? 'active' : '' }}">
                  <i class="bi bi-collection me-2"></i> Section Fees
                </a>
              </li>

              <li>
                <a href="{{ tenant_route('tenant.fees.student-fee-items.index',['student'=>1]) }}"
                   class="nav-link {{ request()->routeIs('tenant.fees.student-fee-items.*') ? 'active' : '' }}">
                  <i class="bi bi-list-check me-2"></i> Student Fee Items
                </a>
              </li>

              <li>
                <a href="{{ tenant_route('tenant.fees.fee-receipts.create',['student'=>1]) }}"
                   class="nav-link {{ request()->routeIs('tenant.fees.fee-receipts.*') ? 'active' : '' }}">
                  <i class="bi bi-receipt me-2"></i> Receipts / Payments
                </a>
              </li>

            </ul>
          </div>
        </li>

        {{-- System Settings --}}
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
