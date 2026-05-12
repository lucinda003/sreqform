@props([
    'prefix' => '',
    'parentOfficeOptions' => [],
    'autofocus' => false,
])

@php
    $fieldId = static fn (string $name): string => trim((string) $prefix) !== '' ? $prefix . '_' . $name : $name;
    $fieldValue = static fn (string $name): string => trim((string) old($name, ''));
@endphp

<div>
    <label class="auth-label block text-sm font-medium text-slate-700" for="{{ $fieldId('name') }}">Name</label>
    <input class="auth-input mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm" id="{{ $fieldId('name') }}" name="name" type="text" value="{{ $fieldValue('name') }}" placeholder="e.g., Northern Mindanao Medical Center" required @if ($autofocus) autofocus @endif>
</div>

<div class="grid gap-4 sm:grid-cols-2">
    <div>
        <label class="auth-label block text-sm font-medium text-slate-700" for="{{ $fieldId('licensing_status') }}">Licensing Status</label>
        <input class="auth-input mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm" id="{{ $fieldId('licensing_status') }}" name="licensing_status" type="text" value="{{ $fieldValue('licensing_status') }}">
    </div>

    <div>
        <label class="auth-label block text-sm font-medium text-slate-700" for="{{ $fieldId('license_date') }}">License Date</label>
        <input class="auth-input mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm" id="{{ $fieldId('license_date') }}" name="license_date" type="date" value="{{ $fieldValue('license_date') }}">
    </div>
</div>

<div class="grid gap-4 sm:grid-cols-2">
    <div>
        <label class="auth-label block text-sm font-medium text-slate-700" for="{{ $fieldId('facility_type') }}">Facility Type</label>
        <input class="auth-input mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm" id="{{ $fieldId('facility_type') }}" name="facility_type" type="text" value="{{ $fieldValue('facility_type') }}">
    </div>

    <div>
        <label class="auth-label block text-sm font-medium text-slate-700" for="{{ $fieldId('classification') }}">Classification</label>
        <input class="auth-input mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm" id="{{ $fieldId('classification') }}" name="classification" type="text" value="{{ $fieldValue('classification') }}">
    </div>
</div>

<div class="grid gap-4 sm:grid-cols-2">
    <div>
        <label class="auth-label block text-sm font-medium text-slate-700" for="{{ $fieldId('building') }}">Building</label>
        <input class="auth-input mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm" id="{{ $fieldId('building') }}" name="building" type="text" value="{{ $fieldValue('building') }}">
    </div>

    <div>
        <label class="auth-label block text-sm font-medium text-slate-700" for="{{ $fieldId('street') }}">Street</label>
        <input class="auth-input mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm" id="{{ $fieldId('street') }}" name="street" type="text" value="{{ $fieldValue('street') }}">
    </div>
</div>

<div class="grid gap-4 sm:grid-cols-2">
    <div>
        <label class="auth-label block text-sm font-medium text-slate-700" for="{{ $fieldId('region') }}">Region</label>
        <select class="auth-input mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm" id="{{ $fieldId('region') }}" name="region" required>
            <option value="">Select region</option>
            @foreach (($parentOfficeOptions ?? []) as $parentOfficeOption)
                <option value="{{ $parentOfficeOption }}" @selected(old('region') === $parentOfficeOption)>{{ $parentOfficeOption }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="auth-label block text-sm font-medium text-slate-700" for="{{ $fieldId('province') }}">Province</label>
        <input class="auth-input mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm" id="{{ $fieldId('province') }}" name="province" type="text" value="{{ $fieldValue('province') }}">
    </div>
</div>

<div class="grid gap-4 sm:grid-cols-2">
    <div>
        <label class="auth-label block text-sm font-medium text-slate-700" for="{{ $fieldId('city') }}">City</label>
        <input class="auth-input mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm" id="{{ $fieldId('city') }}" name="city" type="text" value="{{ $fieldValue('city') }}">
    </div>

    <div>
        <label class="auth-label block text-sm font-medium text-slate-700" for="{{ $fieldId('barangay') }}">Barangay</label>
        <input class="auth-input mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm" id="{{ $fieldId('barangay') }}" name="barangay" type="text" value="{{ $fieldValue('barangay') }}">
    </div>
</div>

<div>
    <label class="auth-label block text-sm font-medium text-slate-700" for="{{ $fieldId('phone') }}">Phone</label>
    <input class="auth-input mt-1 block w-full rounded-lg border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500 sm:text-sm" id="{{ $fieldId('phone') }}" name="phone" type="text" value="{{ $fieldValue('phone') }}">
</div>
