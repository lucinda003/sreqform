<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>

<form method="post" action="{{ route('profile.update') }}" class="space-y-6" id="profile-information-form">
    @csrf
    @method('patch')

    @if (session('status') && ! in_array(session('status'), ['profile-updated', 'verification-link-sent'], true))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">
            {{ session('status') }}
        </div>
    @endif

    <div class="grid gap-5 md:grid-cols-2">
        <div class="md:col-span-2">
            <label for="name" class="block text-sm font-semibold text-slate-700">Full Name</label>
            <input
                id="name"
                name="name"
                type="text"
                class="mt-1.5 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-800 outline-none transition focus:border-slate-500 focus:ring-1 focus:ring-slate-500"
                value="{{ old('name', $user->name) }}"
                required
                autofocus
                autocomplete="name"
            />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <label for="email" class="block text-sm font-semibold text-slate-700">Email Address</label>
            <input
                id="email"
                name="email"
                type="email"
                class="mt-1.5 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-800 outline-none transition focus:border-slate-500 focus:ring-1 focus:ring-slate-500"
                value="{{ old('email', $user->email) }}"
                required
                autocomplete="username"
            />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <div>
            <label for="department" class="block text-sm font-semibold text-slate-700">Department Code</label>
            <input
                id="department"
                type="text"
                value="{{ $user->department ?: 'N/A' }}"
                readonly
                class="mt-1.5 w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-2.5 text-sm font-semibold text-slate-700 outline-none"
            />

            @php
                $departmentStatusClasses = $user->department_status === 'approved'
                    ? 'border-emerald-200 bg-emerald-50 text-emerald-700'
                    : 'border-amber-200 bg-amber-50 text-amber-700';
            @endphp

            <div class="mt-2 flex items-center justify-between gap-2">
                <p class="text-xs text-slate-500">Department code is assigned by admin.</p>
                <span class="inline-flex rounded-full border px-2.5 py-1 text-xs font-semibold uppercase {{ $departmentStatusClasses }}">
                    {{ $user->department_status ?? 'pending' }}
                </span>
            </div>
        </div>

        <div class="md:col-span-2">
            @php
                $profileSignatureUnlockedUntil = (int) session('profile_signature_unlocked_until:' . $user->id, 0);
                $profileSignatureUnlocked = $profileSignatureUnlockedUntil > now()->timestamp;
                $storedProfileSignature = trim((string) ($user->profile_signature ?? ''));
                $profileSignaturePreviewSource = $storedProfileSignature !== '' && str_starts_with($storedProfileSignature, 'service-request-signatures/')
                    ? \App\Support\EncryptedSignature::dataUriFromPath($storedProfileSignature)
                    : $storedProfileSignature;
                $profileSignaturePreviewSource = old('profile_signature_drawn') ?: $profileSignaturePreviewSource;
                $profileSignaturePreview = $profileSignatureUnlocked ? $profileSignaturePreviewSource : '';
                $maskedProfileEmail = (string) ($user->email ?? '');
                if (str_contains($maskedProfileEmail, '@')) {
                    [$profileEmailName, $profileEmailDomain] = explode('@', $maskedProfileEmail, 2);
                    $maskedProfileEmail = \Illuminate\Support\Str::limit($profileEmailName, 2, '') . '***@' . $profileEmailDomain;
                }
            @endphp

            <div class="flex flex-wrap items-center justify-between gap-2">
                <label class="block text-sm font-semibold text-slate-700">Default Signature</label>
                @if ($profileSignatureUnlocked)
                    <span
                        id="profile-signature-unlock-timer"
                        data-unlocked-until="{{ $profileSignatureUnlockedUntil }}"
                        class="rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700"
                    >
                        Unlocked: --:-- left
                    </span>
                @endif
            </div>
            <input type="hidden" name="profile_signature_drawn" id="profile-signature-drawn" value="">
            <input type="hidden" name="profile_signature_clear" id="profile-signature-clear" value="0">

            <div class="mt-1.5 rounded-xl border border-slate-300 bg-white p-3">
                <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <div class="relative flex min-h-[78px] flex-1 items-center justify-center rounded-lg border border-dashed border-slate-300 bg-slate-50 px-3 py-2">
                        @if ($profileSignatureUnlocked)
                            <img
                                id="profile-signature-preview"
                                src="{{ $profileSignaturePreview }}"
                                alt="Profile signature preview"
                                class="{{ $profileSignaturePreview !== '' ? '' : 'hidden' }} max-h-16 max-w-full object-contain"
                            >
                            <span id="profile-signature-placeholder" class="{{ $profileSignaturePreview !== '' ? 'hidden' : '' }} text-xs font-semibold text-slate-500">
                                No signature saved
                            </span>
                        @else
                            <img id="profile-signature-preview" alt="Profile signature preview" class="hidden max-h-16 max-w-full object-contain">
                            <span id="profile-signature-placeholder" class="hidden text-xs font-semibold text-slate-500">No signature saved</span>
                            <div class="flex flex-col items-center justify-center gap-1 text-center text-slate-600">
                                <div class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-300 bg-white text-slate-700">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="5" y="11" width="14" height="10" rx="2"></rect>
                                        <path d="M8 11V8a4 4 0 0 1 8 0v3"></path>
                                    </svg>
                                </div>
                                <p class="text-sm font-bold text-slate-800">Signature locked</p>
                                <p class="text-xs text-slate-500">Enter the email code to view or edit your default signature.</p>
                            </div>
                        @endif
                    </div>

                    <div class="flex flex-wrap gap-2 md:max-w-sm md:justify-end">
                        @if ($profileSignatureUnlocked)
                            <button type="button" id="profile-signature-open" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-slate-400 hover:bg-slate-50">
                                Draw Signature
                            </button>
                            <button type="button" id="profile-signature-clear-btn" class="rounded-xl border border-rose-300 px-4 py-2 text-sm font-semibold text-rose-700 transition hover:border-rose-400 hover:bg-rose-50">
                                Clear
                            </button>
                        @else
                            <button type="submit" form="profile-signature-send-code-form" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-slate-400 hover:bg-slate-50">
                                Send Code
                            </button>
                            <div class="flex w-full gap-2">
                                <input
                                    type="text"
                                    name="profile_signature_code"
                                    form="profile-signature-verify-code-form"
                                    inputmode="numeric"
                                    pattern="[0-9]{6}"
                                    maxlength="6"
                                    autocomplete="one-time-code"
                                    placeholder="6-digit code"
                                    class="min-w-0 flex-1 rounded-xl border border-slate-300 px-3 py-2 text-sm font-semibold text-slate-800 outline-none focus:border-slate-500"
                                >
                                <button type="submit" form="profile-signature-verify-code-form" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-700">
                                    Unlock
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
                <p class="mt-2 text-xs text-slate-500">
                    @if ($profileSignatureUnlocked)
                        This signature will auto-load in print preview for your own signing slot until the timer expires.
                    @else
                        A 6-digit unlock code will be sent to {{ $maskedProfileEmail }}.
                    @endif
                </p>
            </div>

            <x-input-error class="mt-2" :messages="$errors->get('profile_signature_drawn')" />
            <x-input-error class="mt-2" :messages="$errors->get('profile_signature_code')" />
        </div>
    </div>

    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
        <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3">
            <p class="text-sm text-amber-800">
                {{ __('Your email address is unverified.') }}
                <button form="send-verification" class="font-semibold underline underline-offset-2 hover:text-amber-900">
                    {{ __('Send verification link') }}
                </button>
            </p>

            @if (session('status') === 'verification-link-sent')
                <p class="mt-2 text-xs font-semibold uppercase tracking-[0.12em] text-emerald-700">
                    {{ __('A new verification link was sent.') }}
                </p>
            @endif
        </div>
    @endif

    <div class="flex flex-wrap items-center justify-end gap-3 border-t border-slate-100 pt-5">
        @if (session('status') === 'profile-updated')
            <p
                x-data="{ show: true }"
                x-show="show"
                x-transition.opacity
                x-init="setTimeout(() => show = false, 2200)"
                class="rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700"
            >{{ __('Saved') }}</p>
        @endif

        <button
            type="button"
            class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-slate-400 hover:bg-slate-50"
            x-on:click="togglePassword()"
            x-text="showPassword ? 'Hide Password Security' : 'Open Password Security'"
        ></button>

        <button
            type="button"
            class="rounded-xl border border-rose-300 px-4 py-2 text-sm font-semibold text-rose-700 transition hover:border-rose-400 hover:bg-rose-50"
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        >Delete Account</button>

        <button
            type="submit"
            class="rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-2"
        >
            Save Profile
        </button>
    </div>
</form>

<form id="profile-signature-send-code-form" method="POST" action="{{ route('profile.signature.send-code') }}" class="hidden">
    @csrf
</form>

<form id="profile-signature-verify-code-form" method="POST" action="{{ route('profile.signature.verify') }}" class="hidden">
    @csrf
</form>

<div id="profile-signature-modal" class="fixed inset-0 z-[120] hidden items-center justify-center bg-slate-950/70 p-3" aria-hidden="true">
    <div class="flex max-h-[92vh] w-full max-w-3xl flex-col gap-3 rounded-xl border border-slate-300 bg-white p-2 shadow-2xl">
        <div class="flex items-center justify-between border-b border-slate-200 pb-2">
            <h2 class="px-1 text-[18px] font-extrabold uppercase tracking-wide text-slate-900">Draw Signature</h2>
            <button type="button" id="profile-signature-close" class="inline-flex h-10 w-10 cursor-pointer items-center justify-center rounded-full border border-slate-300 text-2xl leading-none text-slate-800 hover:bg-slate-50" aria-label="Close signature modal">&times;</button>
        </div>
        <div class="h-64 overflow-hidden rounded-xl border border-slate-300 bg-slate-50 sm:h-72">
            <canvas id="profile-signature-canvas" class="block h-full w-full bg-white" style="touch-action:none; cursor:crosshair;"></canvas>
        </div>
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <label for="profile-stroke-thickness-label" class="text-sm font-bold text-slate-600">Kapal:</label>
                <button type="button" id="profile-stroke-thinner" class="inline-flex h-9 w-9 cursor-pointer items-center justify-center rounded-lg border border-slate-300 text-lg font-bold text-slate-900 hover:bg-slate-50">-</button>
                <input id="profile-stroke-thickness-label" type="number" min="0.5" max="12" step="0.5" value="2.4" class="h-9 w-16 cursor-text rounded-md border border-slate-300 text-center text-sm font-bold text-slate-900">
                <button type="button" id="profile-stroke-thicker" class="inline-flex h-9 w-9 cursor-pointer items-center justify-center rounded-lg border border-slate-300 text-lg font-bold text-slate-900 hover:bg-slate-50">+</button>
            </div>
            <div class="flex flex-wrap justify-end gap-2">
                <button type="button" id="profile-signature-modal-clear" class="cursor-pointer rounded-xl border border-slate-300 px-4 py-2 text-xs font-extrabold uppercase tracking-wide text-slate-900 hover:bg-slate-50">Clear</button>
                <button type="button" id="profile-signature-remove" class="cursor-pointer rounded-xl border border-slate-300 px-4 py-2 text-xs font-extrabold uppercase tracking-wide text-slate-900 hover:bg-slate-50">Remove Signature</button>
                <button type="button" id="profile-signature-cancel" class="cursor-pointer rounded-xl border border-slate-300 px-4 py-2 text-xs font-extrabold uppercase tracking-wide text-slate-900 hover:bg-slate-50">Cancel</button>
                <button type="button" id="profile-signature-apply" class="cursor-pointer rounded-xl bg-teal-700 px-5 py-2 text-xs font-extrabold uppercase tracking-wide text-white hover:bg-teal-800">Use Signature</button>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
    const initProfileSignatureControls = function () {
        const modal = document.getElementById('profile-signature-modal');
        const canvas = document.getElementById('profile-signature-canvas');
        const openBtn = document.getElementById('profile-signature-open');
        const closeBtn = document.getElementById('profile-signature-close');
        const cancelBtn = document.getElementById('profile-signature-cancel');
        const clearPadBtn = document.getElementById('profile-signature-modal-clear');
        const removeBtn = document.getElementById('profile-signature-remove');
        const applyBtn = document.getElementById('profile-signature-apply');
        const clearBtn = document.getElementById('profile-signature-clear-btn');
        const strokeThinner = document.getElementById('profile-stroke-thinner');
        const strokeThicker = document.getElementById('profile-stroke-thicker');
        const strokeInput = document.getElementById('profile-stroke-thickness-label');
        const drawnInput = document.getElementById('profile-signature-drawn');
        const clearInput = document.getElementById('profile-signature-clear');
        const preview = document.getElementById('profile-signature-preview');
        const placeholder = document.getElementById('profile-signature-placeholder');
        const unlockTimer = document.getElementById('profile-signature-unlock-timer');

        if (unlockTimer && unlockTimer.dataset.timerInitialized !== '1') {
            unlockTimer.dataset.timerInitialized = '1';
            const unlockedUntil = parseInt(unlockTimer.dataset.unlockedUntil || '0', 10) * 1000;
            const updateUnlockTimer = function () {
                const remainingSeconds = Math.max(0, Math.ceil((unlockedUntil - Date.now()) / 1000));
                const minutes = Math.floor(remainingSeconds / 60);
                const seconds = remainingSeconds % 60;
                unlockTimer.textContent = remainingSeconds > 0
                    ? 'Unlocked: ' + String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0') + ' left'
                    : 'Unlocked expired';

                if (remainingSeconds <= 0) {
                    window.clearInterval(unlockTimerInterval);
                    window.setTimeout(function () {
                        window.location.reload();
                    }, 1000);
                }
            };
            const unlockTimerInterval = window.setInterval(updateUnlockTimer, 1000);
            updateUnlockTimer();
        }

        if (!modal || !canvas || !openBtn || !closeBtn || !cancelBtn || !clearPadBtn || !removeBtn || !applyBtn || !clearBtn || !strokeThinner || !strokeThicker || !strokeInput || !drawnInput || !clearInput || !preview || !placeholder) {
            return;
        }

        if (modal.dataset.profileSignatureInitialized === '1') {
            return;
        }
        modal.dataset.profileSignatureInitialized = '1';

        const ctx = canvas.getContext('2d');
        if (!ctx) return;

        let drawing = false;
        let padDirty = false;
        let strokeWidth = 2.4;
        const minStroke = 0.5;
        const maxStroke = 12;
        const strokeStep = 0.5;

        const updateStrokeInput = function () {
            strokeInput.value = strokeWidth.toFixed(1);
        };

        const configureCanvas = function () {
            const ratio = window.devicePixelRatio || 1;
            const rect = canvas.getBoundingClientRect();
            canvas.width = Math.max(1, Math.floor(rect.width * ratio));
            canvas.height = Math.max(1, Math.floor(rect.height * ratio));
            ctx.setTransform(1, 0, 0, 1, 0, 0);
            ctx.lineWidth = strokeWidth * ratio;
            ctx.lineCap = 'round';
            ctx.strokeStyle = '#0f172a';
        };

        const clearCanvas = function () {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        };

        const markPadDirty = function () {
            padDirty = true;
        };

        const pointFromEvent = function (event) {
            const rect = canvas.getBoundingClientRect();
            const source = event.touches ? event.touches[0] : event;
            const scaleX = rect.width > 0 ? canvas.width / rect.width : 1;
            const scaleY = rect.height > 0 ? canvas.height / rect.height : 1;
            return {
                x: (source.clientX - rect.left) * scaleX,
                y: (source.clientY - rect.top) * scaleY,
            };
        };

        const startDrawing = function (event) {
            drawing = true;
            markPadDirty();
            const point = pointFromEvent(event);
            ctx.beginPath();
            ctx.moveTo(point.x, point.y);
            event.preventDefault();
        };

        const moveDrawing = function (event) {
            if (!drawing) return;
            const point = pointFromEvent(event);
            ctx.lineTo(point.x, point.y);
            ctx.stroke();
            event.preventDefault();
        };

        const endDrawing = function () {
            drawing = false;
        };

        const getCenteredSignature = function () {
            const width = canvas.width;
            const height = canvas.height;
            const imageData = ctx.getImageData(0, 0, width, height);
            const data = imageData.data;
            let minX = width;
            let minY = height;
            let maxX = -1;
            let maxY = -1;

            for (let y = 0; y < height; y++) {
                for (let x = 0; x < width; x++) {
                    if (data[(y * width + x) * 4 + 3] > 0) {
                        if (x < minX) minX = x;
                        if (y < minY) minY = y;
                        if (x > maxX) maxX = x;
                        if (y > maxY) maxY = y;
                    }
                }
            }

            if (maxX < minX || maxY < minY) return '';

            const cropWidth = maxX - minX + 1;
            const cropHeight = maxY - minY + 1;
            const targetCanvas = document.createElement('canvas');
            targetCanvas.width = width;
            targetCanvas.height = height;
            const targetCtx = targetCanvas.getContext('2d');
            if (!targetCtx) return canvas.toDataURL('image/png');

            const scale = Math.min((width * 0.9) / cropWidth, (height * 0.8) / cropHeight, 1);
            const drawWidth = cropWidth * scale;
            const drawHeight = cropHeight * scale;
            targetCtx.clearRect(0, 0, width, height);
            targetCtx.drawImage(canvas, minX, minY, cropWidth, cropHeight, (width - drawWidth) / 2, (height - drawHeight) / 2, drawWidth, drawHeight);

            return targetCanvas.toDataURL('image/png');
        };

        const setSignature = function (signatureData) {
            drawnInput.value = signatureData;
            clearInput.value = signatureData === '' ? '1' : '0';
            if (signatureData !== '') {
                preview.src = signatureData;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            } else {
                preview.removeAttribute('src');
                preview.classList.add('hidden');
                placeholder.classList.remove('hidden');
            }
        };

        const openModal = function () {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
            padDirty = false;
            window.requestAnimationFrame(function () {
                configureCanvas();
                clearCanvas();
            });
        };

        const closeModal = function () {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
            drawing = false;
        };

        canvas.addEventListener('mousedown', startDrawing);
        canvas.addEventListener('mousemove', moveDrawing);
        window.addEventListener('mouseup', endDrawing);
        canvas.addEventListener('touchstart', startDrawing, { passive: false });
        canvas.addEventListener('touchmove', moveDrawing, { passive: false });
        canvas.addEventListener('touchend', endDrawing);

        openBtn.addEventListener('click', openModal);
        closeBtn.addEventListener('click', closeModal);
        cancelBtn.addEventListener('click', closeModal);
        clearPadBtn.addEventListener('click', function () {
            clearCanvas();
            markPadDirty();
        });
        removeBtn.addEventListener('click', function () {
            setSignature('');
            closeModal();
        });
        clearBtn.addEventListener('click', function () {
            setSignature('');
        });
        strokeThinner.addEventListener('click', function () {
            strokeWidth = Math.max(minStroke, parseFloat((strokeWidth - strokeStep).toFixed(1)));
            ctx.lineWidth = strokeWidth;
            updateStrokeInput();
        });
        strokeThicker.addEventListener('click', function () {
            strokeWidth = Math.min(maxStroke, parseFloat((strokeWidth + strokeStep).toFixed(1)));
            ctx.lineWidth = strokeWidth;
            updateStrokeInput();
        });
        strokeInput.addEventListener('input', function () {
            const next = parseFloat(strokeInput.value);
            if (!Number.isFinite(next)) return;
            strokeWidth = Math.min(maxStroke, Math.max(minStroke, next));
            ctx.lineWidth = strokeWidth;
        });
        strokeInput.addEventListener('change', function () {
            const next = parseFloat(strokeInput.value);
            strokeWidth = Number.isFinite(next)
                ? Math.min(maxStroke, Math.max(minStroke, parseFloat(next.toFixed(1))))
                : 2.4;
            ctx.lineWidth = strokeWidth;
            updateStrokeInput();
        });
        applyBtn.addEventListener('click', function () {
            if (!padDirty) {
                closeModal();
                return;
            }

            const signatureData = getCenteredSignature();
            if (signatureData !== '') {
                setSignature(signatureData);
            }
            closeModal();
        });
        modal.addEventListener('click', function (event) {
            if (event.target === modal) closeModal();
        });
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && !modal.classList.contains('hidden')) closeModal();
        });

        updateStrokeInput();
    };

    window.initProfileSignatureControls = initProfileSignatureControls;

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initProfileSignatureControls);
    } else {
        initProfileSignatureControls();
    }
    })();
</script>
