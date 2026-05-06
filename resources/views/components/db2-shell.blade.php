@props([
    'title' => null,
    'subtitle' => null,
])

@php
    $isAdmin = strtoupper((string) Auth::user()?->department) === 'ADMIN';
    $isArchiveView = request()->routeIs('service-requests.index') && request()->query('status') === 'archived';
    $isReceiveView = request()->routeIs('service-requests.index') && request()->query('received') === 'me';
    $isAssignedView = request()->routeIs('service-requests.index') && request()->query('assigned') === 'me';
    $isServiceRequestView = request()->routeIs('service-requests.index') && ! $isArchiveView && ! $isReceiveView && ! $isAssignedView;
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
    }
    .db2-sidebar-logo-main {
        display: flex;
        align-items: center;
        gap: 14px;
        min-width: 0;
    }
    .db2-sidebar-logo img { width: 60px; height: 60px; }
    .db2-sidebar-logo-text { font-size: 15px; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase; color: #1a3a2a; line-height: 1.2; }
    .db2-sidebar-toggle-btn {
        width: 28px;
        height: 28px;
        border-radius: 999px;
        border: 1px solid #dbe7df;
        background: #f8fbf9;
        color: #1e293b;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        flex-shrink: 0;
    }
    .db2-sidebar-toggle-btn:hover {
        background: #2d7a6e;
        border-color: #c8ddd0;
    }
    .db2-sidebar-toggle-btn svg {
        width: 15px;
        height: 15px;
    }

    .db2-sidebar-toggle-floating {
        position: absolute;
        right: -50px;
        top: 12px;
        width: 38px;
        height: 38px;
        background: #2d7a6e;
        border: 1px solid #dbe7df;
        box-shadow: none;
        z-index: 12;
    }

    .db2-sidebar-toggle-floating svg {
        width: 19px;
        height: 19px;
    }
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
        min-height: 64px;
        position: sticky;
        top: 0;
        z-index: 5;
    }
    .db2-topbar-chip {
        display: inline-flex;
        align-items: center;
        border: 1px solid #cbd5e1;
        border-radius: 999px;
        background: #f8fafc;
        color: #334155;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        padding: 6px 10px;
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

    .db2-notif-wrap {
        position: relative;
    }

    .db2-notif-count {
        position: absolute;
        top: -4px;
        right: -4px;
        min-width: 18px;
        height: 18px;
        border-radius: 999px;
        background: #dc2626;
        color: #fff;
        font-size: 10px;
        font-weight: 700;
        line-height: 18px;
        text-align: center;
        padding: 0 5px;
        border: 2px solid #fff;
    }

    .db2-notif-panel {
        position: absolute;
        top: 44px;
        right: 0;
        width: min(360px, calc(100vw - 26px));
        border: 1px solid #cbd5e1;
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 18px 36px rgba(15, 23, 42, 0.25);
        overflow: hidden;
        z-index: 40;
    }

    .db2-notif-panel-head {
        padding: 10px 12px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #0f172a;
        border-bottom: 1px solid #e2e8f0;
        background: #f8fafc;
    }

    .db2-notif-panel-head-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .db2-notif-head-link {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0;
        text-transform: none;
        color: #1d4ed8;
        text-decoration: none;
    }

    .db2-notif-head-link:hover {
        text-decoration: underline;
    }

    .db2-notif-search-wrap {
        padding: 8px;
        border-bottom: 1px solid #e2e8f0;
        background: #fff;
    }

    .db2-notif-search {
        width: 100%;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 7px 10px;
        font-size: 12px;
        color: #0f172a;
        outline: none;
    }

    .db2-notif-search:focus {
        border-color: #64748b;
    }

    .db2-notif-list {
        max-height: 320px;
        overflow-y: auto;
        padding: 8px;
        display: flex;
        flex-direction: column;
        gap: 7px;
    }

    .db2-notif-empty {
        margin: 0;
        font-size: 13px;
        color: #64748b;
        padding: 6px 4px;
    }

    .db2-notif-item {
        display: block;
        text-decoration: none;
        border: 1px solid #bfdbfe;
        border-radius: 9px;
        background: #eff6ff;
        padding: 8px 10px;
        transition: background 0.15s ease;
    }

    .db2-notif-item:hover {
        background: #dbeafe;
    }

    .db2-notif-item-title {
        margin: 0;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #1d4ed8;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .db2-notif-item-new {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 32px;
        border-radius: 999px;
        padding: 2px 7px;
        font-size: 10px;
        line-height: 1;
        font-weight: 800;
        color: #fff;
        background: #dc2626;
    }

    .db2-notif-item-text {
        margin: 3px 0 0;
        font-size: 13px;
        font-weight: 600;
        color: #0f172a;
        word-break: break-word;
    }

    .db2-notif-item-time {
        margin: 4px 0 0;
        font-size: 11px;
        color: #64748b;
    }

    .hidden { display: none !important; }

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
    .db2.sidebar-collapsed .db2-sidebar-logo-main {
        justify-content: center;
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
        .db2-sidebar-toggle-floating {
            right: 10px;
            top: 10px;
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
        <div class="db2-sidebar-logo">
            <div class="db2-sidebar-logo-main">
                <img src="{{ asset('images/dohlogo.svg') }}" alt="DOH">
                <span class="db2-sidebar-logo-text">Department<br>of Health</span>
            </div>
        </div>
        <button type="button" class="db2-sidebar-toggle-btn db2-sidebar-toggle-floating" data-db2-sidebar-toggle aria-label="Toggle sidebar" aria-expanded="true">
            <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path d="M4 6H16"></path>
                <path d="M4 10H16"></path>
                <path d="M4 14H16"></path>
            </svg>
        </button>
        <nav class="db2-nav">
            <a href="{{ route('dashboard') }}" class="db2-nav-item {{ request()->routeIs('dashboard') || request()->routeIs('admin.dashboard') ? 'active' : '' }}" data-dashboard-nav-link>
                <svg viewBox="0 0 20 20" fill="currentColor"><path d="M2 10.5a1 1 0 011-1h3a1 1 0 011 1v6a1 1 0 01-1 1H3a1 1 0 01-1-1v-6zM9 3.5a1 1 0 011-1h3a1 1 0 011 1v13a1 1 0 01-1 1h-3a1 1 0 01-1-1v-13zM16 7.5a1 1 0 011-1h1a1 1 0 011 1v9a1 1 0 01-1 1h-1a1 1 0 01-1-1v-9z"/></svg>
                <span class="db2-nav-label">Dashboard</span>
            </a>
            <a href="{{ route('service-requests.index') }}" class="db2-nav-item {{ $isServiceRequestView ? 'active' : '' }}">
                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M9 5H7a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h0a2 2 0 002-2M9 5a2 2 0 012-2h0a2 2 0 012 2"/></svg>
                <span class="db2-nav-label">Service Request</span>
            </a>
            <a href="{{ route('service-requests.index', ['received' => 'me']) }}" class="db2-nav-item {{ $isReceiveView ? 'active' : '' }}">
                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 4h12v12H4z"/><path d="M7 10l3 3 4-6"/></svg>
                <span class="db2-nav-label">Receive</span>
            </a>
            <a href="{{ route('service-requests.index', ['assigned' => 'me']) }}" class="db2-nav-item {{ $isAssignedView ? 'active' : '' }}">
                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M5 4h10a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V6a2 2 0 012-2z"/><path d="M7 8h6M7 12h4"/></svg>
                <span class="db2-nav-label">Assigned</span>
            </a>
            <a href="{{ route('service-requests.index', ['status' => 'archived']) }}" class="db2-nav-item {{ $isArchiveView ? 'active' : '' }}">
                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 4h12v3H4zM4 7h12v9a2 2 0 01-2 2H6a2 2 0 01-2-2V7z"/><path d="M8 11h4"/></svg>
                <span class="db2-nav-label">Archive</span>
            </a>
            <a href="{{ route('service-requests.chat-requests') }}" class="db2-nav-item {{ request()->routeIs('service-requests.chat-requests') ? 'active' : '' }}">
                <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 5a2 2 0 012-2h10a2 2 0 012 2v7a2 2 0 01-2 2H9l-4 3v-3H5a2 2 0 01-2-2V5z"/><path d="M7 8h6M7 11h4"/></svg>
                <span class="db2-nav-label">Chat Requests</span>
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
                <a href="{{ route('admin.management.index') }}" class="db2-nav-item {{ request()->routeIs('admin.management.*', 'admin.offices.*', 'admin.application-systems.*') ? 'active' : '' }}">
                    <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="4" width="7" height="12" rx="1"/><rect x="11" y="4" width="7" height="12" rx="1"/></svg>
                    <span class="db2-nav-label">Management</span>
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
                @if (! $isAdmin)
                    <span class="db2-topbar-chip">{{ strtoupper((string) Auth::user()?->department) }}</span>
                @endif
                <div class="db2-notif-wrap" data-db2-notif-wrap data-poll-endpoint="{{ route('service-requests.notifications') }}">
                    <button type="button" class="db2-icon-btn" data-db2-notif-toggle aria-label="View notifications">
                        <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 2a6 6 0 00-6 6v3l-1.5 2.5h15L16 11V8a6 6 0 00-6-6zM8 17a2 2 0 004 0"/></svg>
                        <span class="db2-notif-count hidden" data-db2-notif-count>0</span>
                    </button>

                    <div class="db2-notif-panel hidden" data-db2-notif-panel>
                        <div class="db2-notif-panel-head">
                            <div class="db2-notif-panel-head-row">
                                <span>Notifications</span>
                                <a href="{{ route('service-requests.chat-requests', ['chat_status' => 'pending']) }}" target="_blank" rel="noopener" class="db2-notif-head-link">View all</a>
                            </div>
                        </div>
                        <div class="db2-notif-search-wrap">
                            <input id="db2-notif-search" name="notification_search" type="text" class="db2-notif-search" data-db2-notif-search placeholder="Search notifications..." autocomplete="off">
                        </div>
                        <div class="db2-notif-list" data-db2-notif-list>
                            <p class="db2-notif-empty" data-db2-notif-empty>No notifications yet.</p>
                        </div>
                    </div>
                </div>
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

        const handleResize = () => applySidebarState(localStorage.getItem(storageKey) === '1');

        if (desktopQuery.addEventListener) {
            desktopQuery.addEventListener('change', handleResize);
        } else {
            desktopQuery.addListener(handleResize);
        }

        const notifWrap = root.querySelector('[data-db2-notif-wrap]');
        const notifToggle = root.querySelector('[data-db2-notif-toggle]');
        const notifPanel = root.querySelector('[data-db2-notif-panel]');
        const notifList = root.querySelector('[data-db2-notif-list]');
        const notifEmpty = root.querySelector('[data-db2-notif-empty]');
        const notifCount = root.querySelector('[data-db2-notif-count]');
        const notifSearch = root.querySelector('[data-db2-notif-search]');

        if (!notifWrap || !notifToggle || !notifPanel || !notifList || !notifEmpty || !notifCount) {
            return;
        }

        const pollEndpoint = notifWrap.getAttribute('data-poll-endpoint') || '';
        const seenKeys = new Set();
        let notificationHistory = [];
        let unreadCount = 0;
        let initialized = false;

        const escapeHtml = function (value) {
            return String(value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        };

        const isFreshNotification = function (item) {
            const requestedAtUnix = Number(item.requested_at_unix || 0);
            if (!Number.isFinite(requestedAtUnix) || requestedAtUnix <= 0) {
                return false;
            }

            const ageSeconds = (Date.now() / 1000) - requestedAtUnix;
            return ageSeconds >= 0 && ageSeconds < 120;
        };

        const updateBadge = function () {
            if (unreadCount > 0) {
                notifCount.textContent = String(unreadCount);
                notifCount.classList.remove('hidden');
                return;
            }

            notifCount.classList.add('hidden');
        };

        const renderNotificationHistory = function () {
            const searchText = notifSearch
                ? String(notifSearch.value || '').trim().toLowerCase()
                : '';
            const filteredNotifications = (Array.isArray(notificationHistory) ? notificationHistory : []).filter(function (item) {
                if (searchText === '') {
                    return true;
                }

                const haystack = [
                    item.message || '',
                    item.requested_at_label || '',
                    item.edit_url || '',
                ].join(' ').toLowerCase();

                return haystack.includes(searchText);
            });

            if (filteredNotifications.length === 0) {
                notifList.innerHTML = '';
                notifEmpty.textContent = searchText === '' ? 'No notifications yet.' : 'No matching notifications.';
                notifEmpty.classList.remove('hidden');
                notifList.appendChild(notifEmpty);
                return;
            }

            notifEmpty.classList.add('hidden');

            notifList.innerHTML = filteredNotifications.map(function (item) {
                const titleText = item.title || 'Notification';
                const message = escapeHtml(item.message || 'New notification');
                const requestedAt = escapeHtml(item.requested_at_label || '');
                const editUrl = escapeHtml(item.edit_url || '#');
                const isNew = isFreshNotification(item);
                const title = escapeHtml(isNew ? 'New ' + titleText : titleText);
                const newBadge = isNew ? '<span class="db2-notif-item-new">NEW</span>' : '';

                return '<a href="' + editUrl + '" class="db2-notif-item">' +
                    '<p class="db2-notif-item-title">' + title + newBadge + '</p>' +
                    '<p class="db2-notif-item-text">' + message + '</p>' +
                    '<p class="db2-notif-item-time">' + requestedAt + '</p>' +
                    '</a>';
            }).join('');
        };

        const addNotification = function (item, countAsUnread) {
            const key = String(item.key || '');
            if (key === '' || seenKeys.has(key)) {
                return;
            }

            seenKeys.add(key);
            notificationHistory.unshift(item);

            notificationHistory.sort(function (a, b) {
                const aTs = Number(a.requested_at_unix || 0);
                const bTs = Number(b.requested_at_unix || 0);

                if (aTs !== bTs) {
                    return bTs - aTs;
                }

                const aKey = String(a.key || '');
                const bKey = String(b.key || '');
                return bKey.localeCompare(aKey);
            });

            if (notificationHistory.length > 25) {
                notificationHistory = notificationHistory.slice(0, 25);
            }

            if (countAsUnread) {
                unreadCount += 1;
                updateBadge();
            }

            renderNotificationHistory();
        };

        if (notifSearch) {
            notifSearch.addEventListener('input', function () {
                renderNotificationHistory();
            });
        }

        notifToggle.addEventListener('click', function () {
            notifPanel.classList.toggle('hidden');

            if (!notifPanel.classList.contains('hidden')) {
                unreadCount = 0;
                updateBadge();
            }
        });

        document.addEventListener('click', function (event) {
            if (notifPanel.classList.contains('hidden')) {
                return;
            }

            if (!notifWrap.contains(event.target)) {
                notifPanel.classList.add('hidden');
            }
        });

        notifList.addEventListener('click', function (event) {
            const link = event.target.closest('.db2-notif-item');
            if (!link) {
                return;
            }

            const href = link.getAttribute('href') || '';
            if (href === '') {
                return;
            }

            event.preventDefault();
            window.location.assign(href);
        });

        const pollNotifications = async function () {
            if (pollEndpoint === '') {
                return;
            }

            try {
                const response = await fetch(pollEndpoint, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });

                if (!response.ok) {
                    return;
                }

                const payload = await response.json();
                const notifications = Array.isArray(payload.notifications) ? payload.notifications : [];

                if (!initialized) {
                    notifications.forEach(function (item) {
                        addNotification(item, isFreshNotification(item));
                    });
                    initialized = true;
                    return;
                }

                notifications.forEach(function (item) {
                    const panelOpen = !notifPanel.classList.contains('hidden');
                    addNotification(item, !panelOpen);
                });

                renderNotificationHistory();
            } catch (error) {
                // Keep existing notification state when polling temporarily fails.
            }
        };

        renderNotificationHistory();
        pollNotifications();
        window.setInterval(pollNotifications, 4000);
        window.setInterval(renderNotificationHistory, 15000);
    })();

    const runInjectedScripts = function (container) {
        container.querySelectorAll('script').forEach(function (script) {
            const nextScript = document.createElement('script');
            Array.from(script.attributes).forEach(function (attribute) {
                nextScript.setAttribute(attribute.name, attribute.value);
            });
            nextScript.textContent = script.textContent;
            script.replaceWith(nextScript);
        });
    };

    const loadDashboardContent = async function (targetUrl) {
        const requestedUrl = new URL(targetUrl || "{{ route('dashboard') }}", window.location.origin);
        const dashboardAjaxUrl = new URL("/api/dashboard", window.location.origin);
        dashboardAjaxUrl.search = requestedUrl.search;

        const response = await fetch(dashboardAjaxUrl.toString(), {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        });

        if (!response.ok) {
            throw new Error('Dashboard AJAX request failed.');
        }

        const data = await response.json();
        const html = data.html || '';
        const contentArea = document.querySelector('.db2-content');

        if (!contentArea || html === '') {
            throw new Error('Dashboard content missing.');
        }

        contentArea.innerHTML = html;
        runInjectedScripts(contentArea);

        window.history.pushState({ dashboard: true }, 'Dashboard', requestedUrl.pathname + requestedUrl.search);
        document.title = 'Dashboard';

        document.querySelectorAll('.db2-nav-item').forEach(item => {
            item.classList.remove('active');
        });

        const dashboardNavLink = Array.from(document.querySelectorAll('.db2-nav-item')).find(function (link) {
            try {
                const linkUrl = new URL(link.href, window.location.origin);
                return linkUrl.pathname === new URL("{{ route('dashboard') }}", window.location.origin).pathname;
            } catch (error) {
                return false;
            }
        });
        if (dashboardNavLink) {
            dashboardNavLink.classList.add('active');
        }

        contentArea.scrollIntoView({ behavior: 'smooth', block: 'start' });
    };

    const handleDashboardNavigation = async function (event, dashboardLink) {
        if (!dashboardLink) return;

        let linkUrl;
        try {
            linkUrl = new URL(dashboardLink.href, window.location.origin);
        } catch (error) {
            return;
        }

        const dashboardPath = "/dashboard";
        const adminDashboardPath = "/admin/dashboard";

        if (linkUrl.pathname !== dashboardPath && linkUrl.pathname !== adminDashboardPath) {
            return;
        }

        event.preventDefault();

        try {
            await loadDashboardContent(dashboardLink.href);
        } catch (error) {
            window.location.assign(dashboardLink.href);
        }
    };

    document.querySelectorAll('[data-dashboard-nav-link]').forEach(function (dashboardLink) {
        dashboardLink.addEventListener('click', function (event) {
            event.stopPropagation();
            handleDashboardNavigation(event, dashboardLink);
        });
    });

    // AJAX Dashboard Navigation
    document.addEventListener('click', async function (event) {
        if (event.defaultPrevented) return;

        const dashboardLink = event.target.closest('[data-dashboard-nav-link], a[href="{{ route('dashboard') }}"], a[href="{{ route('admin.dashboard') }}"]');
        if (!dashboardLink) return;

        await handleDashboardNavigation(event, dashboardLink);
    });

    document.addEventListener('click', async function (event) {
        const dashboardRangeLink = event.target.closest('[data-dashboard-ajax-link]');
        if (!dashboardRangeLink) return;

        event.preventDefault();

        try {
            await loadDashboardContent(dashboardRangeLink.href);
        } catch (error) {
            window.location.assign(dashboardRangeLink.href);
        }
    });

    const serviceRequestsIndexPath = new URL("{{ route('service-requests.index') }}", window.location.origin).pathname;
    const serviceRequestsAjaxPath = new URL("{{ route('service-requests.ajax') }}", window.location.origin).pathname;

    const serviceRequestPageMeta = function (targetUrl) {
        const requestedUrl = new URL(targetUrl || "{{ route('service-requests.index') }}", window.location.origin);
        const status = requestedUrl.searchParams.get('status') || '';
        const received = requestedUrl.searchParams.get('received') || '';
        const assigned = requestedUrl.searchParams.get('assigned') || '';

        if (status === 'archived' || status === 'approved') {
            return {
                state: { archive: true },
                title: 'Archive',
                navHref: "{{ route('service-requests.index', ['status' => 'archived']) }}",
            };
        }

        if (received === 'me') {
            return {
                state: { receive: true },
                title: 'Receive',
                navHref: "{{ route('service-requests.index', ['received' => 'me']) }}",
            };
        }

        if (assigned === 'me') {
            return {
                state: { assigned: true },
                title: 'Assigned',
                navHref: "{{ route('service-requests.index', ['assigned' => 'me']) }}",
            };
        }

        return {
            state: { serviceRequests: true },
            title: 'Service Requests',
            navHref: "{{ route('service-requests.index') }}",
        };
    };

    const setActiveServiceRequestNav = function (navHref) {
        document.querySelectorAll('.db2-nav-item').forEach(item => {
            item.classList.remove('active');
        });

        const targetNavPath = new URL(navHref, window.location.origin).pathname;
        const targetNavSearch = new URL(navHref, window.location.origin).search;
        const navLink = Array.from(document.querySelectorAll('.db2-nav-item')).find(function (link) {
            try {
                const linkUrl = new URL(link.href, window.location.origin);
                return linkUrl.pathname === targetNavPath && linkUrl.search === targetNavSearch;
            } catch (error) {
                return false;
            }
        });

        if (navLink) {
            navLink.classList.add('active');
        }
    };

    const loadServiceRequestsContent = async function (targetUrl) {
        const requestedUrl = new URL(targetUrl || "{{ route('service-requests.index') }}", window.location.origin);
        const ajaxUrl = new URL(serviceRequestsAjaxPath, window.location.origin);
        ajaxUrl.search = requestedUrl.search;

        const response = await fetch(ajaxUrl.toString(), {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        });

        if (!response.ok) {
            throw new Error('Service requests AJAX request failed.');
        }

        const data = await response.json();
        const html = data.html || '';
        const contentArea = document.querySelector('.db2-content');

        if (!contentArea || html === '') {
            throw new Error('Service requests content missing.');
        }

        const meta = serviceRequestPageMeta(requestedUrl.toString());

        contentArea.innerHTML = html;
        runInjectedScripts(contentArea);

        window.history.pushState(meta.state, meta.title, requestedUrl.pathname + requestedUrl.search);
        document.title = meta.title;

        setActiveServiceRequestNav(meta.navHref);
        contentArea.scrollIntoView({ behavior: 'smooth', block: 'start' });
    };

    // AJAX Service Request Navigation
    (function () {
        const serviceRequestLink = document.querySelector('a[href="{{ route('service-requests.index') }}"]');
        if (!serviceRequestLink) return;

        serviceRequestLink.addEventListener('click', async function (event) {
            event.preventDefault();
            
            try {
                await loadServiceRequestsContent(this.href);
            } catch (error) {
                // Fallback to normal navigation if AJAX fails
                window.location.href = this.href;
            }
        });
    })();

    // AJAX Receive Navigation
    (function () {
        const receiveLink = document.querySelector('a[href="{{ route('service-requests.index', ['received' => 'me']) }}"]');
        if (!receiveLink) return;

        receiveLink.addEventListener('click', async function (event) {
            event.preventDefault();

            try {
                await loadServiceRequestsContent(this.href);
            } catch (error) {
                window.location.href = this.href;
            }
        });
    })();

    // AJAX Assigned Navigation
    (function () {
        const assignedLink = document.querySelector('a[href="{{ route('service-requests.index', ['assigned' => 'me']) }}"]');
        if (!assignedLink) return;

        assignedLink.addEventListener('click', async function (event) {
            event.preventDefault();

            try {
                await loadServiceRequestsContent(this.href);
            } catch (error) {
                window.location.href = this.href;
            }
        });
    })();

    // AJAX Archive Navigation
    (function () {
        const archiveLink = document.querySelector('a[href="{{ route('service-requests.index', ['status' => 'archived']) }}"]');
        if (!archiveLink) return;

        archiveLink.addEventListener('click', async function (event) {
            event.preventDefault();
            
            try {
                await loadServiceRequestsContent(this.href);
            } catch (error) {
                // Fallback to normal navigation if AJAX fails
                window.location.href = this.href;
            }
        });
    })();

    document.addEventListener('click', async function (event) {
        if (event.defaultPrevented) return;

        const serviceRequestContentLink = event.target.closest('.db2-content a[data-srf-section-link]');
        if (!serviceRequestContentLink) return;

        let linkUrl;
        try {
            linkUrl = new URL(serviceRequestContentLink.href, window.location.origin);
        } catch (error) {
            return;
        }

        if (linkUrl.origin !== window.location.origin || linkUrl.pathname !== serviceRequestsIndexPath) {
            return;
        }

        event.preventDefault();

        try {
            await loadServiceRequestsContent(serviceRequestContentLink.href);
        } catch (error) {
            window.location.assign(serviceRequestContentLink.href);
        }
    });

    // AJAX Chat Requests Navigation
    (function () {
        const chatRequestsLink = document.querySelector('a[href="{{ route('service-requests.chat-requests') }}"]');
        if (!chatRequestsLink) return;

        chatRequestsLink.addEventListener('click', async function (event) {
            event.preventDefault();
            
            try {
                const response = await fetch(this.href, {
                    headers: {
                        'Accept': 'text/html',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });

                if (!response.ok) {
                    window.location.href = this.href;
                    return;
                }

                const html = await response.text();
                const parsed = new DOMParser().parseFromString(html, 'text/html');
                const nextContent = parsed.querySelector('.db2-content');

                // Update page content with new chat requests data
                const contentArea = document.querySelector('.db2-content');
                if (contentArea && nextContent) {
                    contentArea.innerHTML = nextContent.innerHTML;
                    runInjectedScripts(contentArea);
                    
                    // Update URL without reloading
                    window.history.pushState({ chatRequests: true }, 'Chat Requests', "{{ route('service-requests.chat-requests') }}");
                    
                    // Update page title
                    document.title = 'Chat Requests';
                    
                    // Update active nav item highlight
                    document.querySelectorAll('.db2-nav-item').forEach(item => {
                        item.classList.remove('active');
                    });
                    chatRequestsLink.classList.add('active');
                    
                    // Scroll to top of content area
                    contentArea.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            } catch (error) {
                // Fallback to normal navigation if AJAX fails
                window.location.href = this.href;
            }
        });
    })();

    // AJAX Profile Navigation
    (function () {
        const profileLink = document.querySelector('a[href="{{ route('profile.edit') }}"]');
        if (!profileLink) return;

        profileLink.addEventListener('click', async function (event) {
            event.preventDefault();
            
            try {
                const response = await fetch("{{ route('profile.ajax') }}", {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });

                if (!response.ok) {
                    window.location.href = this.href;
                    return;
                }

                const data = await response.json();
                const html = data.html || '';
                
                // Update page content with new profile data
                const contentArea = document.querySelector('.db2-content');
                if (contentArea && html) {
                    contentArea.innerHTML = html;
                    runInjectedScripts(contentArea);
                    
                    // Update URL without reloading
                    window.history.pushState({ profile: true }, 'Profile', "{{ route('profile.edit') }}");
                    
                    // Update page title
                    document.title = 'Profile';
                    
                    // Update active nav item highlight
                    document.querySelectorAll('.db2-nav-item').forEach(item => {
                        item.classList.remove('active');
                    });
                    profileLink.classList.add('active');
                    
                    // Scroll to top of content area
                    contentArea.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            } catch (error) {
                // Fallback to normal navigation if AJAX fails
                window.location.href = this.href;
            }
        });
    })();

    // AJAX Accounts Navigation
    (function () {
        const accountsLink = document.querySelector('a[href="{{ route('admin.users.index') }}"]');
        if (!accountsLink) return;

        accountsLink.addEventListener('click', async function (event) {
            event.preventDefault();
            
            try {
                const response = await fetch(this.href, {
                    headers: {
                        'Accept': 'text/html',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });

                if (!response.ok) {
                    window.location.href = this.href;
                    return;
                }

                const html = await response.text();
                const parsed = new DOMParser().parseFromString(html, 'text/html');
                const nextContent = parsed.querySelector('.db2-content');

                // Update page content with new accounts data
                const contentArea = document.querySelector('.db2-content');
                if (contentArea && nextContent) {
                    contentArea.innerHTML = nextContent.innerHTML;
                    runInjectedScripts(contentArea);
                    
                    // Update URL without reloading
                    window.history.pushState({ accounts: true }, 'Accounts', "{{ route('admin.users.index') }}");
                    
                    // Update page title
                    document.title = 'Accounts';
                    
                    // Update active nav item highlight
                    document.querySelectorAll('.db2-nav-item').forEach(item => {
                        item.classList.remove('active');
                    });
                    accountsLink.classList.add('active');
                    
                    // Scroll to top of content area
                    contentArea.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            } catch (error) {
                // Fallback to normal navigation if AJAX fails
                window.location.href = this.href;
            }
        });
    })();

    // AJAX Management Navigation
    (function () {
        const managementLink = document.querySelector('a[href="{{ route('admin.management.index') }}"]');
        if (!managementLink) return;

        managementLink.addEventListener('click', async function (event) {
            event.preventDefault();

            try {
                const response = await fetch(this.href, {
                    headers: {
                        'Accept': 'text/html',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });

                if (!response.ok) {
                    window.location.href = this.href;
                    return;
                }

                const html = await response.text();
                const parsed = new DOMParser().parseFromString(html, 'text/html');
                const nextContent = parsed.querySelector('.db2-content');
                const contentArea = document.querySelector('.db2-content');

                if (contentArea && nextContent) {
                    contentArea.innerHTML = nextContent.innerHTML;
                    runInjectedScripts(contentArea);

                    window.history.pushState({ management: true }, 'Management', "{{ route('admin.management.index') }}");
                    document.title = 'Management | ' + (document.title.split('|')[1] || '{{ config('app.name', 'Laravel') }}');

                    document.querySelectorAll('.db2-nav-item').forEach(item => {
                        item.classList.remove('active');
                    });
                    managementLink.classList.add('active');

                    contentArea.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            } catch (error) {
                window.location.href = this.href;
            }
        });
    })();
</script>
