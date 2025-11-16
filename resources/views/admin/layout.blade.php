<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - Heartwood</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ time() }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white">

    <header class="header">
        <nav class="header__nav container flex justify-center items-center">
            {{-- Hamburger menu button for mobile --}}
            <button class="mobile-menu-toggle lg:hidden p-2 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            {{-- Mobile Logout button (visible on mobile, hidden on desktop) --}}
            <div class="mobile-logout-btn lg:hidden">
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="button button--secondary button--small">Log Out</button>
                </form>
            </div>

            {{-- Main menu (will be styled as overlay on mobile, regular nav on desktop) --}}
            <ul class="header__menu hidden lg:flex lg:space-x-4">
                <li><a href="{{ route('admin.dashboard') }}" class="header__link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a></li>
                <li><a href="{{ route('admin.categories.index') }}" class="header__link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">Categories</a></li>
                <li><a href="{{ route('admin.nominations.index') }}" class="header__link {{ request()->routeIs('admin.nominations.*') ? 'active' : '' }}">Nominations</a></li>
                {{-- Desktop Logout button (hidden on mobile, visible on desktop) --}}
                <li class="desktop-logout-btn hidden lg:block">
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="button button--secondary">Log Out</button>
                    </form>
                </li>
            </ul>
        </nav>
    </header>

    <main class="container py-12 main-content">
        @yield('content')
    </main>

    <div class="mobile-menu-overlay fixed inset-0 bg-gray-900 bg-opacity-95 z-50 hidden">
        <div class="flex justify-end p-4">
            <button class="mobile-menu-close text-white text-2xl">&times;</button>
        </div>
        <ul class="mobile-menu-links flex flex-col items-center justify-center h-full space-y-6">
            <li><a href="{{ route('admin.dashboard') }}" class="header__link text-3xl {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a></li>
            <li><a href="{{ route('admin.categories.index') }}" class="header__link text-3xl {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">Categories</a></li>
            <li><a href="{{ route('admin.nominations.index') }}" class="header__link text-3xl {{ request()->routeIs('admin.nominations.*') ? 'active' : '' }}">Nominations</a></li>
        </ul>
    </div>
</body>
</html>