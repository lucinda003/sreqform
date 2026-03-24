<x-guest-layout>
    <section class="mx-auto w-full max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-[0_24px_70px_rgba(15,23,42,0.12)]">
            <div class="bg-gradient-to-r from-cyan-700 via-sky-700 to-teal-700 px-6 py-6 text-white sm:px-10">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-white/80">Preview Option 2</p>
                <h1 class="mt-2 text-2xl font-semibold tracking-tight sm:text-3xl">Modern Clean Service Request UI</h1>
                <p class="mt-2 max-w-3xl text-sm text-cyan-50 sm:text-base">
                    This is a preview-only design. Existing live form is unchanged.
                </p>
            </div>

            <div class="bg-slate-50 px-6 py-3 text-sm text-slate-600 sm:px-10">
                Reference Code: <span class="font-semibold text-slate-800">Auto-generated after submit</span>
            </div>

            <form class="space-y-8 px-6 py-8 sm:px-10">
                <div class="grid gap-4 rounded-2xl border border-slate-200 bg-white p-5 sm:grid-cols-3 sm:p-6">
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">Request Date</label>
                        <input type="date" class="auth-input" value="{{ now()->toDateString() }}">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">Request Time</label>
                        <input type="time" class="auth-input" value="{{ now()->format('H:i') }}">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">Category</label>
                        <select class="auth-input">
                            <option>Technical Assistance</option>
                            <option>System Access</option>
                            <option>Data Request</option>
                        </select>
                    </div>
                </div>

                <div class="grid gap-4 rounded-2xl border border-slate-200 bg-white p-5 sm:grid-cols-2 sm:p-6">
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">Application System Name</label>
                        <input type="text" class="auth-input" placeholder="e.g. iHOMIS">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">Expected Completion</label>
                        <div class="grid grid-cols-2 gap-3">
                            <input type="date" class="auth-input">
                            <input type="time" class="auth-input">
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6">
                    <h2 class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Contact Information</h2>
                    <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        <input type="text" class="auth-input" placeholder="Last name">
                        <input type="text" class="auth-input" placeholder="First name">
                        <input type="text" class="auth-input" placeholder="Middle name">
                        <input type="text" class="auth-input" placeholder="Suffix">
                    </div>
                    <div class="mt-4 grid gap-4 sm:grid-cols-2">
                        <input type="text" class="auth-input" placeholder="Office">
                        <input type="text" class="auth-input" placeholder="Address">
                    </div>
                    <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        <input type="text" class="auth-input" placeholder="Landline">
                        <input type="text" class="auth-input" placeholder="Fax No">
                        <input type="text" class="auth-input" placeholder="Mobile No">
                        <input type="email" class="auth-input" placeholder="Email Address">
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6">
                    <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">Description of Request</label>
                    <textarea class="auth-input min-h-[180px]" placeholder="Please clearly write down the details of the request."></textarea>
                </div>

                <div class="grid gap-4 rounded-2xl border border-slate-200 bg-white p-5 sm:grid-cols-3 sm:p-6">
                    <div class="sm:col-span-1">
                        <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">Approved By</label>
                        <input type="text" class="auth-input" placeholder="Name and Signature">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">Position</label>
                        <input type="text" class="auth-input" placeholder="Position">
                    </div>
                    <div>
                        <label class="mb-1 block text-xs font-semibold uppercase tracking-[0.16em] text-slate-500">Date Signed</label>
                        <input type="date" class="auth-input" value="{{ now()->toDateString() }}">
                    </div>
                </div>

                <div class="flex items-center justify-between border-t border-slate-200 pt-6">
                    <a href="{{ route('service-requests.create') }}" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:border-slate-500">Back to Current Form</a>
                    <button type="button" class="auth-button">Preview Submit Button</button>
                </div>
            </form>
        </div>
    </section>
</x-guest-layout>
