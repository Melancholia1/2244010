<header id="header" class="header d-flex align-items-center position-relative">
  <div class="container position-relative d-flex align-items-center justify-content-between">

    <a href="{{ route('home') }}" class="logo d-flex align-items-center me-auto me-xl-0">
      <h1 class="sitename">Story</h1>
    </a>

    <nav id="navmenu" class="navmenu">
      <ul>
        <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
        <li><a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">About</a></li>
        <li><a href="{{ route('category') }}" class="{{ request()->routeIs('category') ? 'active' : '' }}">Category</a></li>
        <li><a href="{{ route('author.profile', 1) }}" class="{{ request()->routeIs('author.profile') ? 'active' : '' }}">Author Profile</a></li>
        <li class="dropdown"><a href="#"><span>Pages</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
          <ul>
            <li><a href="{{ route('about') }}">About</a></li>
            <li><a href="{{ route('category') }}">Category</a></li>
            <li><a href="{{ route('author.profile', 1) }}">Author Profile</a></li>
            <li><a href="{{ route('search') }}">Search Results</a></li>
          </ul>
        </li>
        <li><a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a></li>
      </ul>
      <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
    </nav>

    <div class="header-social-links">
      <!-- Social media links will be loaded dynamically from API -->
    </div>

  </div>
</header>
