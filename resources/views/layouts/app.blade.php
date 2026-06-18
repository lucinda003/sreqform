<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="srs-login-url" content="{{ route('login') }}">

    <title>{{ isset($pageTitle) && $pageTitle !== '' ? $pageTitle : config('app.name', 'Laravel') }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/dohlogo.svg') }}">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @auth
        <style>
            .srs-mobile-unavailable {
                position: fixed;
                inset: 0;
                z-index: 9999;
                display: none;
                align-items: center;
                justify-content: center;
                background: #0f172a;
                color: #ffffff;
                padding: 24px;
            }

            .srs-mobile-unavailable-card {
                width: 100%;
                max-width: 360px;
                text-align: center;
            }

            .srs-mobile-unavailable-icon {
                width: 56px;
                height: 56px;
                margin: 0 auto 20px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 18px;
                background: rgba(255, 255, 255, 0.1);
                color: #fda4af;
            }

            .srs-mobile-unavailable-icon svg {
                width: 28px;
                height: 28px;
            }

            .srs-mobile-unavailable-title {
                margin: 0;
                font-size: 24px;
                line-height: 1.2;
                font-weight: 800;
                letter-spacing: -0.01em;
            }

            .srs-mobile-unavailable-copy {
                margin: 12px 0 0;
                color: #cbd5e1;
                font-size: 14px;
                line-height: 1.7;
            }

            body.srs-phone-blocked .srs-mobile-unavailable {
                display: flex !important;
            }

            body.srs-phone-blocked .app-shell {
                display: none !important;
            }

            body.srs-phone-blocked {
                overflow: hidden;
            }
        </style>
    @endauth
</head>

<body
    class="app-body {{ request()->routeIs('dashboard') || request()->routeIs('admin.dashboard') ? 'dashboard-login-bg' : '' }} antialiased"
    data-authenticated="{{ Auth::check() ? '1' : '0' }}">
    @auth
        <div class="srs-mobile-unavailable" aria-live="polite">
            <div class="srs-mobile-unavailable-card">
                <div class="srs-mobile-unavailable-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <rect x="7" y="2" width="10" height="20" rx="2" />
                        <path d="M11 18h2" />
                    </svg>
                </div>
                <h1 class="srs-mobile-unavailable-title">Mobile view not available yet</h1>
                <p class="srs-mobile-unavailable-copy">Please open SRS on a desktop or wider browser window to continue.</p>
            </div>
        </div>
        <script>
            (function () {
                function syncPhoneBlock() {
                    var screenWidth = Math.min(window.screen.width || window.innerWidth, window.screen.height || window.innerHeight);
                    document.body.classList.toggle('srs-phone-blocked', screenWidth <= 767);
                }

                syncPhoneBlock();
                window.addEventListener('resize', syncPhoneBlock);
                window.addEventListener('orientationchange', syncPhoneBlock);
            })();
        </script>
    @endauth

    @php
        $usesCustomDashboardLayout = request()->routeIs('dashboard')
            || request()->routeIs('admin.dashboard')
            || request()->routeIs('service-requests.*')
            || request()->routeIs('profile.*')
            || request()->routeIs('admin.users.*')
            || request()->routeIs('admin.management.*')
            || request()->routeIs('admin.offices.*')
            || request()->routeIs('admin.application-systems.*');
    @endphp
    <div class="app-shell min-h-screen">
        @unless ($usesCustomDashboardLayout)
            @include('layouts.navigation')
        @endunless

        <!-- Page Heading -->
        @isset($header)
            @unless ($usesCustomDashboardLayout)
                <header class="px-4 pb-3 pt-6 sm:px-6 lg:px-8">
                    <div
                        class="mx-auto w-full max-w-6xl rounded-2xl border border-white/65 bg-white/70 px-5 py-4 shadow-lg backdrop-blur-xl sm:px-6">
                        {{ $header }}
                    </div>
                </header>
            @endunless
        @endisset

        <!-- Page Content -->
        <main class="{{ $usesCustomDashboardLayout ? '' : 'px-4 pb-8 sm:px-6 lg:px-8' }}">
            {{ $slot }}
        </main>
    </div>

    @auth
        <dialog id="srs-logout-dialog" class="w-[calc(100%-2rem)] max-w-md rounded-2xl border border-slate-200 p-0 shadow-2xl backdrop:bg-slate-950/45 backdrop:backdrop-blur-sm">
            <div class="overflow-hidden rounded-2xl bg-white">
                <div class="flex items-start gap-4 px-6 pb-5 pt-6">
                    <div class="mt-0.5 flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-rose-50 text-rose-600">
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path d="M13 3h4a1 1 0 011 1v12a1 1 0 01-1 1h-4M8 14l4-4-4-4M12 10H2" />
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h2 class="text-lg font-bold leading-6 text-slate-950">Log out of SRS?</h2>
                        <p class="mt-2 text-sm leading-6 text-slate-600">You will be signed out in this tab and your other open SRS tabs will be redirected to the login page.</p>
                    </div>
                </div>
                <div class="flex flex-col-reverse gap-3 border-t border-slate-100 bg-slate-50/70 px-6 py-4 sm:flex-row sm:justify-end">
                    <button type="button" class="rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50" data-srs-logout-cancel>
                        Cancel
                    </button>
                    <button type="button" class="rounded-xl bg-rose-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-rose-700" data-srs-logout-confirm>
                        Log out
                    </button>
                </div>
            </div>
        </dialog>

        <script>
            (function () {
                var logoutKey = 'srs_logout_at';
                var loginUrl = document.querySelector('meta[name="srs-login-url"]')?.content || '/login';
                var logoutDialog = document.getElementById('srs-logout-dialog');
                var pendingLogoutForm = null;

                document.querySelectorAll('[data-srs-logout-form]').forEach(function (form) {
                    if (form.dataset.srsLogoutBound === '1') {
                        return;
                    }

                    form.dataset.srsLogoutBound = '1';
                    form.addEventListener('submit', function (event) {
                        if (form.dataset.srsLogoutConfirmed === '1') {
                            try {
                                localStorage.setItem(logoutKey, String(Date.now()));
                            } catch (error) {
                                // Storage can be disabled in private or restricted browser modes.
                            }

                            return;
                        }

                        event.preventDefault();
                        pendingLogoutForm = form;

                        if (logoutDialog && typeof logoutDialog.showModal === 'function') {
                            logoutDialog.showModal();
                            return;
                        }

                        if (window.confirm('Log out of SRS?')) {
                            form.dataset.srsLogoutConfirmed = '1';
                            form.submit();
                        }
                    });
                });

                logoutDialog?.querySelector('[data-srs-logout-cancel]')?.addEventListener('click', function () {
                    pendingLogoutForm = null;
                    logoutDialog.close();
                });

                logoutDialog?.querySelector('[data-srs-logout-confirm]')?.addEventListener('click', function () {
                    if (!pendingLogoutForm) {
                        logoutDialog.close();
                        return;
                    }

                    pendingLogoutForm.dataset.srsLogoutConfirmed = '1';
                    logoutDialog.close();
                    pendingLogoutForm.requestSubmit();
                });

                logoutDialog?.addEventListener('click', function (event) {
                    if (event.target === logoutDialog) {
                        pendingLogoutForm = null;
                        logoutDialog.close();
                    }
                });

                window.addEventListener('storage', function (event) {
                    if (event.key !== logoutKey || !event.newValue) {
                        return;
                    }

                    window.location.replace(loginUrl);
                });
            })();
        </script>
    @endauth
</body>

</html>
