<x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
    <form
        method="post"
        action="{{ route('profile.destroy') }}"
        class="p-6"
        x-data="{
            confirmStep: false,
            submitting: false,
            hasPassword() {
                return this.$refs.deletePassword.value.trim().length > 0;
            },
            continueToConfirm() {
                if (!this.hasPassword()) {
                    this.$refs.deletePassword.focus();
                    return;
                }

                this.confirmStep = true;
            },
            handleSubmit(event) {
                if (!this.confirmStep) {
                    event.preventDefault();
                    this.continueToConfirm();
                    return;
                }

                if (!this.hasPassword()) {
                    event.preventDefault();
                    this.$refs.deletePassword.focus();
                    return;
                }

                this.submitting = true;
            }
        }"
        x-on:submit="handleSubmit($event)"
    >
        @csrf
        @method('delete')

        <h2 class="border-b border-slate-100 pb-3 text-lg font-semibold text-slate-900">
            {{ __('Confirm Permanent Deletion') }}
        </h2>

        <p class="mt-3 text-sm text-slate-600">
            {{ __('This action cannot be undone. Enter your current password to confirm account deletion.') }}
        </p>

        <div class="mt-6">
            <label for="password" class="block text-sm font-semibold text-slate-700">Current Password</label>

            <input
                id="password"
                name="password"
                type="password"
                x-ref="deletePassword"
                class="mt-1.5 w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-800 outline-none transition focus:border-rose-500 focus:ring-1 focus:ring-rose-500"
                placeholder="{{ __('Enter current password') }}"
            />

            <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
        </div>

        <div x-cloak x-show="confirmStep" x-transition class="mt-5 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
            Are you sure you want to permanently delete this account?
        </div>

        <div class="mt-7 flex justify-end gap-2 border-t border-slate-100 pt-4">
            <button
                type="button"
                class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-slate-500 hover:text-slate-900"
                x-show="!confirmStep"
                x-on:click="$dispatch('close')"
                :disabled="submitting"
            >
                {{ __('Cancel') }}
            </button>

            <button
                type="button"
                x-show="!confirmStep"
                x-on:click="continueToConfirm()"
                class="rounded-xl border border-rose-700 bg-rose-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-rose-800"
                :disabled="submitting"
            >
                Continue
            </button>

            <button
                type="button"
                x-show="confirmStep"
                x-on:click="confirmStep = false"
                class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-slate-500 hover:text-slate-900"
                :disabled="submitting"
            >
                No
            </button>

            <button
                type="submit"
                x-show="confirmStep"
                x-ref="confirmDeleteBtn"
                class="rounded-xl border border-rose-700 bg-rose-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-rose-800"
                :disabled="submitting"
            >
                Yes, Delete Account
            </button>
        </div>
    </form>
</x-modal>
