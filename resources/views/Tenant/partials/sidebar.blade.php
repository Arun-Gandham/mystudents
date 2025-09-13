@php
    use App\Services\MenuService;
    $menus = MenuService::getMenu();
    $currentRoute = request()->route() ? request()->route()->getName() : '';
@endphp

  <div class="sidebar-inner">
    <div class="menu-scroll">
      <!-- Logo -->
      <a class="navbar-brand d-flex align-items-center gap-2" href="{{ tenant_route('tenant.dashboard') }}">
        @if(!empty($school->logo_url))
          <img src="{{ asset('storage/'.$school->logo_url) }}" alt="{{ $school->name }} Logo" class="img-fluid">
        @else
          <img src="{{ asset('images/default-logo.png') }}" alt="Default Logo" class="img-fluid">
        @endif
      </a>

      <ul class="nav nav-pills flex-column gap-1">
        @forelse ($menus as $menu)
          @if (!empty($menu['children']))
            {{-- Collapsible --}}
            <li class="nav-item">
              <a class="nav-link {{ collect($menu['children'])->pluck('route')->contains($currentRoute) ? '' : 'collapsed' }}"
                 data-bs-toggle="collapse"
                 href="#menu_{{ \Illuminate\Support\Str::slug($menu['label']) }}">
                <i class="{{ $menu['icon'] ?? 'bi bi-circle' }} me-2"></i>
                {{ $menu['label'] ?? 'Unnamed' }}
                <i class="bi bi-chevron-down ms-auto small"></i>
              </a>
              <div class="collapse {{ collect($menu['children'])->pluck('route')->contains($currentRoute) ? 'show' : '' }}"
                   id="menu_{{ \Illuminate\Support\Str::slug($menu['label']) }}">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small ms-4">
                  @foreach ($menu['children'] as $child)
                    <li>
                      <a href="{{ !empty($child['route']) && $child['route'] !== '#' ? tenant_route($child['route']) : '#' }}"
                         class="nav-link {{ request()->routeIs($child['route']) ? 'active' : '' }}">
                        <i class="{{ $child['icon'] ?? 'bi bi-circle' }} me-2"></i>
                        {{ $child['label'] ?? 'Unnamed' }}
                      </a>
                    </li>
                  @endforeach
                </ul>
              </div>
            </li>
          @else
            {{-- Single --}}
            <li class="nav-item">
              <a href="{{ !empty($menu['route']) && $menu['route'] !== '#' ? tenant_route($menu['route']) : '#' }}"
                 class="nav-link {{ request()->routeIs($menu['route']) ? 'active' : '' }}">
                <i class="{{ $menu['icon'] ?? 'bi bi-circle' }} me-2"></i>
                {{ $menu['label'] ?? 'Unnamed' }}
              </a>
            </li>
          @endif
        @empty
          <li class="nav-item">
            <span class="nav-link disabled">No menu available</span>
          </li>
        @endforelse
      </ul>
    </div>
  </div>
