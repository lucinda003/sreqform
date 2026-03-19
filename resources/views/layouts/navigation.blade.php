<nav x-data="{ open: false }" class="px-4 pt-4 sm:px-6 lg:px-8">
    <div class="mx-auto flex w-full max-w-6xl items-center justify-between rounded-2xl border border-white/65 bg-white/80 px-4 py-3 shadow-lg backdrop-blur-xl sm:px-5">
        <div class="flex items-center gap-5">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-slate-800">
                <x-application-logo class="h-11 w-11" />
                <span class="text-sm font-semibold uppercase tracking-[0.18em]">TEST</span>
            </a>

            <div class="hidden items-center gap-2 sm:flex">
                @if (strtoupper((string) Auth::user()?->department) === 'ADMIN')
                    <a
                        href="{{ route('dashboard') }}"
                        class="rounded-full px-3 py-1.5 text-sm font-medium transition {{ request()->routeIs('dashboard') ? 'bg-slate-900 text-white' : 'text-slate-700 hover:bg-slate-100' }}"
                    >
                        Dashboard
                    </a>
                @endif

                <a
                    href="{{ route('service-requests.index') }}"
                    class="rounded-full px-3 py-1.5 text-sm font-medium transition {{ request()->routeIs('service-requests.*') ? 'bg-slate-900 text-white' : 'text-slate-700 hover:bg-slate-100' }}"
                >
                    Service Requests
                </a>

                <a
                    href="{{ route('profile.edit') }}"
                    class="rounded-full px-3 py-1.5 text-sm font-medium transition {{ request()->routeIs('profile.*') ? 'bg-slate-900 text-white' : 'text-slate-700 hover:bg-slate-100' }}"
                >
                    Profile
                </a>

                @if (strtoupper((string) Auth::user()?->department) === 'ADMIN')
                    <a
                        href="{{ route('admin.users.index') }}"
                        class="rounded-full px-3 py-1.5 text-sm font-medium transition {{ request()->routeIs('admin.users.*') ? 'bg-slate-900 text-white' : 'text-slate-700 hover:bg-slate-100' }}"
                    >
                        Accounts
                    </a>
                @endif
            </div>
        </div>

        <div class="hidden items-center gap-3 sm:flex">
            <div class="text-right leading-tight">
                <p class="text-sm font-semibold text-slate-800">{{ Auth::user()->name }}</p>
                <p class="text-xs text-slate-500">{{ Auth::user()->email }}</p>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="rounded-xl border border-slate-300 px-3 py-2 text-sm font-medium text-slate-700 transition hover:border-slate-500 hover:text-slate-900">
                    Log out
                </button>
            </form>
        </div>

        <button
            @click="open = ! open"
            class="inline-flex items-center justify-center rounded-lg p-2 text-slate-500 transition hover:bg-slate-100 hover:text-slate-700 sm:hidden"
        >
            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="mx-auto mt-3 hidden w-full max-w-6xl rounded-2xl border border-white/65 bg-white/90 px-4 py-3 shadow-lg sm:hidden">
        <div class="space-y-2">
            @if (strtoupper((string) Auth::user()?->department) === 'ADMIN')
                <a
                    href="{{ route('dashboard') }}"
                    class="block rounded-xl px-3 py-2 text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-slate-900 text-white' : 'text-slate-700 hover:bg-slate-100' }}"
                >
                    Dashboard
                </a>
            @endif

            <a
                href="{{ route('service-requests.index') }}"
                class="block rounded-xl px-3 py-2 text-sm font-medium {{ request()->routeIs('service-requests.*') ? 'bg-slate-900 text-white' : 'text-slate-700 hover:bg-slate-100' }}"
            >
                Service Requests
            </a>

            <a
                href="{{ route('profile.edit') }}"
                class="block rounded-xl px-3 py-2 text-sm font-medium {{ request()->routeIs('profile.*') ? 'bg-slate-900 text-white' : 'text-slate-700 hover:bg-slate-100' }}"
            >
                Profile
            </a>

            @if (strtoupper((string) Auth::user()?->department) === 'ADMIN')
                <a
                    href="{{ route('admin.users.index') }}"
                    class="block rounded-xl px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.users.*') ? 'bg-slate-900 text-white' : 'text-slate-700 hover:bg-slate-100' }}"
                >
                    Accounts
                </a>
            @endif
        </div>

        <div class="mt-3 border-t border-slate-200 pt-3">
            <p class="text-sm font-semibold text-slate-800">{{ Auth::user()->name }}</p>
            <p class="text-xs text-slate-500">{{ Auth::user()->email }}</p>

            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf
                <button type="submit" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm font-medium text-slate-700 transition hover:border-slate-500 hover:text-slate-900">
                    Log out
                </button>
            </form>
        </div>
    </div>
</nav>
