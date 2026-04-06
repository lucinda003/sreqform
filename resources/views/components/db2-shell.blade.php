@props([
    'title' => null,
    'subtitle' => null,
])

@php
    $isAdmin = strtoupper((string) Auth::user()?->department) === 'ADMIN';
    $isArchiveView = request()->routeIs('service-requests.index') && request()->query('status') === 'archived';
    $isServiceRequestView = request()->routeIs('service-requests.index') && ! $isArchiveView;
@endphp

<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap');

    .db2 * { box-sizing: border-box; }
    .db2 { font-family: 'DM Sans', sans-serif; display: flex; min-height: 100vh; background: #f4f6f4; color: #1e293b; }

    .db2-sidebar {
        width: 220px;
        flex-shrink: 0;
        background: #fff;
        border-right: 1px solid #e8ede8;
        display: flex;
        flex-direction: column;
        position: fixed;
        top: 0;
        left: 0;
        bottom: 0;
        z-index: 10;
    }
    .db2-sidebar-logo {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 18px 18px 14px;
        border-bottom: 1px solid #eef2ee;
        cursor: pointer;
    }
    .db2-sidebar-logo img { width: 36px; height: 36px; }
    .db2-sidebar-logo-text { font-size: 11px; font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase; color: #1a3a2a; line-height: 1.3; }
    .db2-nav { flex: 1; padding: 12px 10px; display: flex; flex-direction: column; gap: 2px; }
    .db2-nav-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 12px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 500;
        color: #64748b;
        text-decoration: none;
        transition: all 0.15s;
    }
    .db2-nav-item:hover { background: #f0f7f0; color: #1a5c3a; }
    .db2-nav-item.active { background: #2d7a6e; color: #fff; }
    .db2-nav-item svg { width: 18px; height: 18px; flex-shrink: 0; }
    .db2-nav-label { white-space: nowrap; }
    .db2-sidebar-logout {
        padding: 12px 10px;
        border-top: 1px solid #eef2ee;
    }
    .db2-logout-btn {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 12px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 500;
        color: #94a3b8;
        background: transparent;
        border: none;
        cursor: pointer;
        width: 100%;
        text-decoration: none;
        transition: all 0.15s;
    }
    .db2-logout-btn:hover { background: #fff1f2; color: #be123c; }
    .db2-logout-btn svg { width: 18px; height: 18px; }

    .db2-main { margin-left: 220px; flex: 1; display: flex; flex-direction: column; min-height: 100vh; }

    .db2-topbar {
        background: #fff;
        border-bottom: 1px solid #eef2ee;
        padding: 12px 24px;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
        position: sticky;
        top: 0;
        z-index: 5;
    }
    .db2-search {
        display: flex;
        align-items: center;
        gap: 8px;
        background: #f4f6f4;
        border: 1px solid #e2e8e2;
        border-radius: 10px;
        padding: 8px 14px;
        width: 280px;
    }
    .db2-search input {
        border: none;
        background: transparent;
        font-size: 13px;
        color: #1e293b;
        outline: none;
        width: 100%;
        font-family: 'DM Sans', sans-serif;
    }
    .db2-search input::placeholder { color: #94a3b8; }
    .db2-search svg { width: 15px; height: 15px; color: #94a3b8; flex-shrink: 0; }
    .db2-topbar-right { display: flex; align-items: center; gap: 10px; }
    .db2-icon-btn {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #f4f6f4;
        border: 1px solid #e2e8e2;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background 0.15s;
    }
    .db2-icon-btn:hover { background: #e8ede8; }
    .db2-icon-btn svg { width: 16px; height: 16px; color: #475569; }
    .db2-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #2d7a6e;
        color: #fff;
        font-size: 13px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .db2-content { padding: 24px; flex: 1; }

    .db2-page-head {
        margin-bottom: 18px;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }
    .db2-page-title { font-size: 1.5rem; font-weight: 600; color: #0f172a; }
    .db2-page-subtitle { font-size: 13px; color: #64748b; margin-top: 3px; }
    .db2-page-actions { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }

    .db2.sidebar-collapsed .db2-sidebar { width: 78px; }
    .db2.sidebar-collapsed .db2-main { margin-left: 78px; }
    .db2.sidebar-collapsed .db2-sidebar-logo {
        justify-content: center;
        padding-left: 8px;
        padding-right: 8px;
    }
    .db2.sidebar-collapsed .db2-sidebar-logo-text,
    .db2.sidebar-collapsed .db2-nav-label,
    .db2.sidebar-collapsed .db2-logout-label {
        display: none;
    }
    .db2.sidebar-collapsed .db2-nav-item,
    .db2.sidebar-collapsed .db2-logout-btn {
        justify-content: center;
        padding-left: 10px;
        padding-right: 10px;
    }

    @media (max-width: 1024px) {
        .db2 { flex-direction: column; }
        .db2-sidebar {
            position: static;
            width: 100%;
            border-right: none;
            border-bottom: 1px solid #e8ede8;
        }
        .db2-nav {
            flex-direction: row;
            flex-wrap: nowrap;
            overflow-x: auto;
        }
        .db2-sidebar-logout {
            border-top: none;
            border-top: 1px solid #eef2ee;
        }
        .db2-main { margin-left: 0; }
        .db2.sidebar-collapsed .db2-sidebar { width: 100%; }
        .db2.sidebar-collapsed .db2-main { margin-left: 0; }
        .db2.sidebar-collapsed .db2-sidebar-logo-text,
        .db2.sidebar-collapsed .db2-nav-label,
        .db2.sidebar-collapsed .db2-logout-label {
            display: inline;
        }
        .db2.sidebar-collapsed .db2-nav-item,
        .db2.sidebar-collapsed .db2-logout-btn {
            justify-content: flex-start;
            padding-left: 12px;
            padding-right: 12px;
        }
    }

    @media (max-width: 640px) {
        .db2-topbar { padding: 10px 14px; }
        .db2-search { display: none; }
        .db2-content { padding: 14px; }
        .db2-page-title { font-size: 1.25rem; }
    }
</style>

<div class="db2">
    <aside class="db2-sidebar">
        <div class="db2-sidebar-logo" data-db2-sidebar-toggle role="button" tabindex="0" aria-label="Toggle sidebar" aria-expanded="true">
            <img src="{{ asset('images/dohlogo.svg') }}" alt="DOH">
            <span class="db2-sidebar-logo-text">Department<br>of Health</span>
        </div>
        <nav class="db2-nav">
            <a href="{{ route('dashboard') }}" class="db2-nav-item {{ request()->routeIs('dashboard') || request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg viewBox="0 0 20 20" fill="currentColor"><path d="M2 10.5a1 1 0 011-1h3a1 1 0 011 1v6a1 1 0 01-1 1H3a1 1 0 01-1-1v-6zM9 3.5a1 1 0 011-1h3a1 1 0 011 1v13a1 1 0 01-1 1h-3a1 1 0 01-1-1v-13zM16 7.5a1 1 0 011-1h1a1 1 0 011 1v9a1 1 0 01-1 1h-1a1 1 0 01-1-1v-9z"/></svg>
                <span class="db2-nav-label">Dashboard</span>
            </a>
            <a href="{{ route('service-requests.index') }}" class="db2-nav-item {{ $isServiceRequestView ? 'active' : '' }}">
                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M9 5H7a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h0a2 2 0 002-2M9 5a2 2 0 012-2h0a2 2 0 012 2"/></svg>
                <span class="db2-nav-label">Service Request</span>
            </a>
            <a href="{{ route('service-requests.index', ['status' => 'archived']) }}" class="db2-nav-item {{ $isArchiveView ? 'active' : '' }}">
                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 4h12v3H4zM4 7h12v9a2 2 0 01-2 2H6a2 2 0 01-2-2V7z"/><path d="M8 11h4"/></svg>
                <span class="db2-nav-label">Archive</span>
            </a>
            <a href="{{ route('profile.edit') }}" class="db2-nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="10" cy="7" r="3"/><path d="M4 17c0-3.3 2.7-6 6-6s6 2.7 6 6"/></svg>
                <span class="db2-nav-label">Profile</span>
            </a>
            @if ($isAdmin)
                <a href="{{ route('admin.users.index') }}" class="db2-nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="7" cy="7" r="3"/><path d="M1 17c0-3.3 2.7-6 6-6s6 2.7 6 6"/><path d="M13 7a3 3 0 010 6M19 17c0-2.2-1.3-4.1-3-5"/></svg>
                    <span class="db2-nav-label">Accounts</span>
                </a>
            @endif
        </nav>
        <div class="db2-sidebar-logout">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="db2-logout-btn">
                    <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M13 3h4a1 1 0 011 1v12a1 1 0 01-1 1h-4M8 14l4-4-4-4M12 10H2"/></svg>
                    <span class="db2-logout-label">Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <div class="db2-main">
        <div class="db2-topbar">
            <div class="db2-topbar-right">
                <div class="db2-icon-btn" aria-hidden="true">
                    <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 2a6 6 0 00-6 6v3l-1.5 2.5h15L16 11V8a6 6 0 00-6-6zM8 17a2 2 0 004 0"/></svg>
                </div>
                <div class="db2-avatar">{{ strtoupper(substr(Auth::user()?->name ?? 'A', 0, 1)) }}</div>
            </div>
        </div>

        <div class="db2-content">
            @if ($title || $subtitle || isset($actions))
                <div class="db2-page-head">
                    <div>
                        @if ($title)
                            <h1 class="db2-page-title">{{ $title }}</h1>
                        @endif
                        @if ($subtitle)
                            <p class="db2-page-subtitle">{{ $subtitle }}</p>
                        @endif
                    </div>

                    @isset($actions)
                        <div class="db2-page-actions">
                            {{ $actions }}
                        </div>
                    @endisset
                </div>
            @endif

            {{ $slot }}
        </div>
    </div>
</div>

<script>
    (function () {
        const root = document.querySelector('.db2');
        const toggleBtn = root ? root.querySelector('[data-db2-sidebar-toggle]') : null;
        if (!root || !toggleBtn) {
            return;
        }

        const storageKey = 'db2-sidebar-collapsed';
        const desktopQuery = window.matchMedia('(min-width: 1025px)');

        const applySidebarState = (isCollapsed) => {
            if (!desktopQuery.matches) {
                root.classList.remove('sidebar-collapsed');
                toggleBtn.setAttribute('aria-expanded', 'true');
                return;
            }

            root.classList.toggle('sidebar-collapsed', isCollapsed);
            toggleBtn.setAttribute('aria-expanded', isCollapsed ? 'false' : 'true');
        };

        applySidebarState(localStorage.getItem(storageKey) === '1');

        toggleBtn.addEventListener('click', function () {
            if (!desktopQuery.matches) {
                return;
            }

            const willCollapse = !root.classList.contains('sidebar-collapsed');
            root.classList.toggle('sidebar-collapsed', willCollapse);
            localStorage.setItem(storageKey, willCollapse ? '1' : '0');
            toggleBtn.setAttribute('aria-expanded', willCollapse ? 'false' : 'true');
        });

        toggleBtn.addEventListener('keydown', function (event) {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                toggleBtn.click();
            }
        });

        const handleResize = () => applySidebarState(localStorage.getItem(storageKey) === '1');

        if (desktopQuery.addEventListener) {
            desktopQuery.addEventListener('change', handleResize);
        } else {
            desktopQuery.addListener(handleResize);
        }
    })();
</script>
