@php
    use App\Services\MenuService;
    $menus = MenuService::getMenu();
    $currentRoute = request()->route() ? request()->route()->getName() : '';
@endphp

  <div class="sidebar-inner">
    <a class="navbar-brand d-flex align-items-center gap-2 justify-content-center" href="{{ tenant_route('tenant.dashboard') }}">
        @if(!empty($school->logo_url))
          <img src="{{ asset('storage/'.$school->logo_url) }}?v={{ time() }}" alt="{{ $school->name }} Logo" class="img-fluid w-50">
        @else
          <img src="{{ asset('images/default-logo.png') }}" alt="Default Logo" class="img-fluid w-50">
        @endif
      </a>
    <div class="menu-scroll">
      <!-- Logo -->
      

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
                      @php $childRoute = $child['route'] ?? ''; @endphp
                      <a href="{{ !empty($childRoute) && $childRoute !== '#' ? tenant_route($childRoute) : '#' }}"
                         class="nav-link {{ !empty($childRoute) && request()->routeIs($childRoute) ? 'active' : '' }}">
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
              @php $menuRoute = $menu['route'] ?? ''; @endphp
              <a href="{{ !empty($menuRoute) && $menuRoute !== '#' ? tenant_route($menuRoute) : '#' }}"
                 class="nav-link {{ !empty($menuRoute) && request()->routeIs($menuRoute) ? 'active' : '' }}">
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
