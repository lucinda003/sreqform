<div
    x-data="{
        showPassword: false,
        togglePassword() {
            if (this.showPassword) {
                this.showPassword = false;
                return;
            }

            this.showPassword = true;

            if (this.showPassword) {
                this.$nextTick(() => this.$refs.passwordCard.scrollIntoView({ behavior: 'smooth', block: 'start' }));
            }
        }
    }"
    class="mx-auto w-full max-w-5xl space-y-4"
>
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-6 py-4">
            <h3 class="text-2xl font-semibold text-slate-900">Profile Information</h3>
            <p class="mt-1 text-sm text-slate-500">Update your personal details and email settings.</p>
        </div>
        <div class="p-6">
            @include('profile.partials.update-profile-information-form')
        </div>
    </div>

    <div x-show="showPassword" x-transition x-ref="passwordCard" class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-6 py-4">
            <h3 class="text-2xl font-semibold text-slate-900">Password Security</h3>
            <p class="mt-1 text-sm text-slate-500">Use a long, unique password and update it often.</p>
        </div>
        <div class="p-6">
            @include('profile.partials.update-password-form')
        </div>
    </div>

    @include('profile.partials.delete-user-form')
</div>
