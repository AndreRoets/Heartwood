<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Heartwood Valley Realm Nominations')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ time() }}">
    <script src="https://cdn.tailwindcss.com"></script> {{-- Add this for modal styling --}}
    @stack('styles')
</head>
<body>

        <header class="header">
            <nav class="header__nav container flex justify-between items-center">
                {{-- Hamburger menu button for mobile --}}
                <button class="mobile-menu-toggle lg:hidden p-2 rounded-md text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                        aria-controls="mobile-menu" aria-expanded="false">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                {{-- Mobile Logout/Login button (visible on mobile, hidden on desktop) --}}
                <div class="mobile-user-actions lg:hidden">
                    @auth
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="button button--secondary button--small">Log Out</button>
                        </form>
                    @else
                        <button type="button" class="button button--secondary button--small" id="login-button-mobile">Log In</button>
                    @endauth
                </div>

                {{-- Main menu (hidden on mobile, visible on desktop) --}}
                <div class="hidden lg:block"> {{-- Wrapper div for desktop menu --}}
                    <ul class="header__menu space-x-4 py-4">
                        <li><a href="{{ route('home') }}" class="header__link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
                        <li><a href="{{ route('builds.index') }}" class="header__link {{ request()->routeIs('builds.index') ? 'active' : '' }}">Builds</a></li>
                        <li class="header__dropdown">
                            <a href="#" class="header__link header__dropdown-toggle">The Heartwood Awards</a>
                            <ul class="header__dropdown-menu">
                                <li><a href="{{ route('nominations.create') }}" class="header__link {{ request()->routeIs('nominations.create') ? 'active' : '' }}">Nominate</a></li>
                                <li><a href="{{ route('nominations.index') }}" class="header__link {{ request()->routeIs('nominations.index') ? 'active' : '' }}">Vote</a></li>
                                <li><a href="{{ route('nominations.results') }}" class="header__link {{ request()->routeIs('nominations.results') ? 'active' : '' }}">Results</a></li>
                            </ul>
                        </li>
                        <li><a href="#" class="header__link">About/Board</a></li>
                    </ul>
                </div>

                {{-- Desktop Logout/Login button (hidden on mobile, visible on desktop) --}}
                <div class="desktop-user-actions hidden lg:block absolute right-0 top-1/2 -translate-y-1/2 pr-4">
                    @auth
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="button button--secondary">Log Out</button>
                        </form>
                    @else
                        <button type="button" class="button button--secondary header__login" id="login-button-desktop">Log In</button>
                    @endauth
                </div>
            </nav>
        </header>

    <main class="main-content">
        @yield('content')
    </main>

    {{-- Mobile menu overlay (outside nav, will be toggled by JS) --}}
    <div id="mobile-menu" class="mobile-menu-overlay" role="dialog" aria-modal="true">
        <div class="flex justify-end p-4">
            <button class="mobile-menu-close text-white text-2xl">&times;</button>
        </div>
        <ul class="mobile-menu-links flex flex-col items-center space-y-6">
            <li><a href="{{ route('home') }}" class="header__link text-xl {{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
            <li><a href="{{ route('builds.index') }}" class="header__link text-xl {{ request()->routeIs('builds.index') ? 'active' : '' }}">Builds</a></li>
            <li class="header__dropdown">
                <a href="#" class="header__link header__dropdown-toggle text-xl">The Heartwood Awards</a>
                <ul class="header__dropdown-menu mobile-dropdown-menu">
                    <li><a href="{{ route('nominations.create') }}" class="header__link text-lg {{ request()->routeIs('nominations.create') ? 'active' : '' }}">Nominate</a></li>
                    <li><a href="{{ route('nominations.index') }}" class="header__link text-lg {{ request()->routeIs('nominations.index') ? 'active' : '' }}">Vote</a></li>
                    <li><a href="{{ route('nominations.results') }}" class="header__link text-lg {{ request()->routeIs('nominations.results') ? 'active' : '' }}">Results</a></li>
                </ul>
            </li>
            <li><a href="#" class="header__link text-xl">About/Board</a></li>
        </ul>
    </div>

    <footer class="footer">
                <div class="container footer__content">
                    <p class="footer__copyright">&copy; {{ date('Y') }} Heartwood Valley. All rights reserved.</p>
                </div>    </footer>

    @guest
    <!-- Login Modal -->
    <div id="login-modal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden items-center justify-center" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="bg-gray-800 p-8 rounded-lg shadow-lg w-full max-w-sm relative text-white">
            <!-- Close Button -->
            <button id="close-modal-button" class="absolute top-4 right-4 text-gray-400 hover:text-white">
                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- Tabs -->
            <div class="border-b border-gray-700 mb-6">
                <nav class="-mb-px flex" aria-label="Tabs">
                    <button id="login-tab-button" class="w-1/2 py-4 px-1 text-center border-b-2 font-medium text-sm border-blue-500 text-white">
                        Login
                    </button>
                    <button id="register-tab-button" class="w-1/2 py-4 px-1 text-center border-b-2 font-medium text-sm border-transparent text-gray-400 hover:text-gray-300 hover:border-gray-500">
                        Register
                    </button>
                </nav>
            </div>

            @if ($errors->any())
                <div class="bg-red-500 text-white p-3 rounded mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Login Form -->
            <div id="login-form">
                <form method="POST" action="{{ route('login.attempt') }}">
                    @csrf
                    <input type="hidden" name="_form_type" value="login">
                    <div class="mb-4">
                        <label for="login_username" class="block mb-2 text-sm font-medium">Username</label>
                        <input type="text" name="username" id="login_username" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required autofocus value="{{ old('_form_type') === 'login' ? old('username') : '' }}">
                    </div>
                    <div class="flex items-center justify-between">
                        <button type="submit" class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                            Login
                        </button>
                    </div>
                </form>
                <div class="text-center mt-4">
                    <p class="text-sm text-gray-400">Don't have an account?
                        <a href="#" id="show-register-link" class="font-medium text-blue-500 hover:underline">Sign up</a>
                    </p>
                </div>
            </div>

            <!-- Register Form -->
            <div id="register-form" class="hidden">
                <form method="POST" action="{{ route('register.attempt') }}">
                    @csrf
                    <input type="hidden" name="_form_type" value="register">
                    <div class="mb-4">
                        <label for="register_username" class="block mb-2 text-sm font-medium">Username</label>
                        <input type="text" name="username" id="register_username" class="bg-gray-700 border border-gray-600 text-white text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required value="{{ old('_form_type') === 'register' ? old('username') : '' }}">
                    </div>
                    <button type="submit" class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        Register
                    </button>
                </form>
                <div class="text-center mt-4">
                    <p class="text-sm text-gray-400">Already have an account?
                        <a href="#" id="show-login-link" class="font-medium text-blue-500 hover:underline">Log in</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
    @endguest


    <script>
        // Parallax inputs for crystals (mouse + tilt)
        (function(){
            const root = document.documentElement;
            const hero = document.getElementById('hero');
            if (!hero) return; // Don't run on pages without hero

            const clamp = (n, min, max) => Math.max(min, Math.min(max, n));

            function setParallax(x, y) {
                const rx = (x / window.innerWidth)  * 2 - 1; // -1..1
                const ry = (y / window.innerHeight) * 2 - 1; // -1..1
                const px = clamp(rx * 20, -24, 24);
                const py = clamp(ry * 12, -14, 14);
                root.style.setProperty('--px', px + 'px');
                root.style.setProperty('--py', py + 'px');
            }

            window.addEventListener('mousemove', (e) => setParallax(e.clientX, e.clientY), { passive: true });

            window.addEventListener('deviceorientation', (e) => {
                if (e.gamma == null || e.beta == null) return;
                const x = ((e.gamma + 45) / 90) * window.innerWidth;
                const y = ((e.beta  + 45) / 90) * window.innerHeight;
                setParallax(x, y);
            }, { passive: true });

            hero.addEventListener('mouseleave', () => {
                document.documentElement.style.setProperty('--px', '0px');
                document.documentElement.style.setProperty('--py', '0px');
            });
        })();

        document.addEventListener('DOMContentLoaded', () => {
            // Header blur on scroll
            const header = document.querySelector('.header');
            window.addEventListener('scroll', () => {
                header.classList.toggle('scrolled', window.scrollY > 50);
            });

            // Login Modal Logic
            const loginButtonMobile = document.getElementById('login-button-mobile');
            const loginButtonDesktop = document.getElementById('login-button-desktop');
            const loginModal = document.getElementById('login-modal');
            const closeModalButton = document.getElementById('close-modal-button');
            const loginTabButton = document.getElementById('login-tab-button');
            const registerTabButton = document.getElementById('register-tab-button');
            const loginForm = document.getElementById('login-form');
            const registerForm = document.getElementById('register-form');
            const showRegisterLink = document.getElementById('show-register-link');
            const showLoginLink = document.getElementById('show-login-link');

            function showTab(tabName) {
                if (tabName === 'login') {
                    loginForm.classList.remove('hidden');
                    registerForm.classList.add('hidden');
                    loginTabButton.classList.add('border-blue-500', 'text-white');
                    loginTabButton.classList.remove('text-gray-400', 'border-transparent');
                    registerTabButton.classList.add('text-gray-400', 'border-transparent');
                    registerTabButton.classList.remove('border-blue-500', 'text-white');
                } else {
                    loginForm.classList.add('hidden');
                    registerForm.classList.remove('hidden');
                    registerTabButton.classList.add('border-blue-500', 'text-white');
                    registerTabButton.classList.remove('text-gray-400', 'border-transparent');
                    loginTabButton.classList.add('text-gray-400', 'border-transparent');
                    loginTabButton.classList.remove('border-blue-500', 'text-white');
                }
            }

            function showModal() {
                if (!loginModal) return;
                loginModal.classList.remove('hidden');
                loginModal.classList.add('flex');

                // Reset to login tab by default unless there are registration errors
                @php
                    $isRegistrationError = $errors->any() && old('_form_type') === 'register';
                @endphp

                @if($isRegistrationError)
                    showTab('register');
                @else
                    showTab('login');
                @endif
            }

            function hideModal() {
                if (!loginModal) return;
                loginModal.classList.add('hidden');
                loginModal.classList.remove('flex');
            }

            if (loginButtonMobile) {
                loginButtonMobile.addEventListener('click', showModal);
            }
            if (loginButtonDesktop) {
                loginButtonDesktop.addEventListener('click', showModal);
            }

            if (loginTabButton) {
                loginTabButton.addEventListener('click', () => showTab('login'));
            }
            if (registerTabButton) {
                registerTabButton.addEventListener('click', () => showTab('register'));
            }
            if (showRegisterLink) {
                showRegisterLink.addEventListener('click', (e) => { e.preventDefault(); showTab('register'); });
            }
            if (showLoginLink) {
                showLoginLink.addEventListener('click', (e) => { e.preventDefault(); showTab('login'); });
            }

            if(closeModalButton) {
                closeModalButton.addEventListener('click', hideModal);
            }

        
            if(loginModal) {
                loginModal.addEventListener('click', (event) => {
                    if (event.target === loginModal) {
                        hideModal();
                    }
                });
            }

            // If there are login errors, show the modal on page load.
            @if ($errors->any() || $errors->has('auth'))
                showModal();
            @endif

              // --- Mobile Menu Logic (fixed) ---
              const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
              const mobileMenuOverlay = document.getElementById('mobile-menu');
              const mobileMenuClose   = document.querySelector('.mobile-menu-close');

              if (mobileMenuToggle && mobileMenuOverlay) { // safety

                  // ensure basic a11y at
                  mobileMenuToggle.setAttribute('aria-controls', mobileMenuOverlay.id);
                  if (!mobileMenuToggle.hasAttribute('aria-expanded')) {
                    mobileMenuToggle.setAttribute('aria-expanded', 'false');
                  }

                  const getFirstFocusable = () =>
                    mobileMenuOverlay.querySelector('a[href], button:not([disabled]), [tabindex]:not([tabindex="-1"])');

                  const openMobileMenu = () => {
                    mobileMenuOverlay.classList.add('is-open');
                    document.body.classList.add('overflow-hidden');
                    mobileMenuToggle.setAttribute('aria-expanded', 'true');
                    const first = getFirstFocusable();
                    if (first) setTimeout(() => first.focus(), 0);

                  };

                  const closeMobileMenu = () => {
                    mobileMenuOverlay.classList.remove('is-open');
                    document.body.classList.remove('overflow-hidden');
                    mobileMenuToggle.setAttribute('aria-expanded', 'false');
                    mobileMenuToggle.focus();
                  };

                  mobileMenuToggle.addEventListener('click', openMobileMenu);
                  if (mobileMenuClose) mobileMenuClose.addEventListener('click', closeMobileMenu);

                  // Close on ESC key
                  document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && mobileMenuOverlay.classList.contains('is-open')) {
                      closeMobileMenu();
                    }
                  });

                  // Close on overlay background click
                  mobileMenuOverlay.addEventListener('click', (e) => {
                    if (e.target === mobileMenuOverlay) closeMobileMenu();
                  });

                  // Mobile submenu toggle (only inside overlay)
                  mobileMenuOverlay.querySelectorAll('.header__dropdown-toggle').forEach((toggle) => {
                    const menu = toggle.nextElementSibling;
                    toggle.setAttribute('aria-expanded', 'false');
                    toggle.addEventListener('click', (e) => {
                      e.preventDefault();
                      if (!menu || !menu.classList.contains('header__dropdown-menu')) return;
                      const isOpen = menu.classList.toggle('active');
                      toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                    });
                  });
              }
        });
    </script>
    @stack('scripts')
</body>
</html>