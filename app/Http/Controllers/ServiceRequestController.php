<?php

namespace App\Http\Controllers;

use App\Models\ServiceRequest;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class ServiceRequestController extends Controller
{
    public function index(): View
    {
        $serviceRequests = $this->scopeForUser(ServiceRequest::query())
            ->latest()
            ->paginate(10);

        return view('service-requests.index', [
            'serviceRequests' => $serviceRequests,
        ]);
    }

    public function create(): View
    {
        $currentDepartment = trim((string) Auth::user()?->department);

        return view('service-requests.create', [
            'departmentOptions' => $this->approvedDepartmentOptions(true, $currentDepartment !== '' ? $currentDepartment : null),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatedData($request);

        $authUser = Auth::user();
        if (! $this->isAdmin() && $authUser?->department_status !== 'approved') {
            return back()
                ->withErrors(['department_code' => 'Your department is pending admin approval.'])
                ->withInput();
        }

        $validated['reference_code'] = $this->generateReferenceCode(
            $validated['department_code'],
            $validated['request_date']
        );
        $validated['status'] = 'pending';
        $validated['user_id'] = Auth::id();

        $serviceRequest = ServiceRequest::create($validated);

        return redirect()
            ->route('service-requests.show', $serviceRequest)
            ->with('status', 'Service Request submitted successfully.');
    }

    public function edit(ServiceRequest $serviceRequest): View
    {
        abort_unless($this->canAccess($serviceRequest), 403);

        $currentDepartment = trim((string) Auth::user()?->department);
        return view('service-requests.edit', [
            'serviceRequest' => $serviceRequest,
            'departmentOptions' => $this->approvedDepartmentOptions(true, $currentDepartment !== '' ? $currentDepartment : null),
        ]);
    }

    public function update(Request $request, ServiceRequest $serviceRequest): RedirectResponse
    {
        abort_unless($this->canAccess($serviceRequest), 403);

        $validated = $this->validatedData($request);

        $authUser = Auth::user();
        if (! $this->isAdmin() && $authUser?->department_status !== 'approved') {
            return back()
                ->withErrors(['department_code' => 'Your department is pending admin approval.'])
                ->withInput();
        }

        $serviceRequest->update($validated);

        return redirect()
            ->route('service-requests.show', $serviceRequest)
            ->with('status', 'Service Request updated successfully.');
    }

    public function show(ServiceRequest $serviceRequest): View
    {
        abort_unless($this->canAccess($serviceRequest), 403);

        return view('service-requests.show', [
            'serviceRequest' => $serviceRequest,
        ]);
    }

    public function print(ServiceRequest $serviceRequest): View
    {
        abort_unless($this->canAccess($serviceRequest), 403);

        return view('service-requests.print', [
            'serviceRequest' => $serviceRequest,
        ]);
    }

    public function downloadPdf(ServiceRequest $serviceRequest): Response
    {
        abort_unless($this->canAccess($serviceRequest), 403);

        $pdf = Pdf::loadView('service-requests.pdf', [
            'serviceRequest' => $serviceRequest,
        ]);

        return $pdf->download($serviceRequest->reference_code . '.pdf');
    }

    public function updateStatus(Request $request, ServiceRequest $serviceRequest): RedirectResponse
    {
        abort_unless($this->canAccess($serviceRequest), 403);

        $validated = $request->validate([
            'status' => ['required', 'in:pending,approved,rejected'],
        ]);

        $serviceRequest->update([
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('service-requests.show', $serviceRequest)
            ->with('status', 'Request status updated successfully.');
    }

    private function generateReferenceCode(string $departmentCode, string $requestDate): string
    {
        $cleanDepartment = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $departmentCode) ?? '');
        $cleanDepartment = $cleanDepartment !== '' ? $cleanDepartment : 'GEN';

        $datePart = date('Ymd', strtotime($requestDate));

        $sequence = ServiceRequest::query()
            ->whereDate('request_date', $requestDate)
            ->where('department_code', $departmentCode)
            ->count() + 1;

        return sprintf('%s-%s-%03d', $cleanDepartment, $datePart, $sequence);
    }

    private function validatedData(Request $request): array
    {
        $validated = $request->validate([
            'request_date' => ['required', 'date'],
            'department_code' => ['required', 'string', 'max:30', 'in:ADMIN,Role 1,Role 2,Role 3,Role 4,Role 5,Role 6,Role 7,Role 8,Role 9'],
            'contact_last_name' => ['required', 'string', 'max:255'],
            'contact_first_name' => ['required', 'string', 'max:255'],
            'contact_middle_name' => ['nullable', 'string', 'max:255'],
            'office' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'landline' => ['nullable', 'string', 'max:50'],
            'fax_no' => ['nullable', 'string', 'max:50'],
            'mobile_no' => ['required', 'string', 'max:50'],
            'description_request' => ['required', 'string'],
            'approved_by_name' => ['required', 'string', 'max:255'],
            'approved_by_signature' => ['nullable', 'string', 'max:255'],
            'approved_by_position' => ['required', 'string', 'max:255'],
            'approved_date' => ['required', 'date'],
            'kmits_date' => ['required', 'date'],
            'time_received' => ['nullable', 'date_format:H:i'],
            'actions_taken' => ['nullable', 'string'],
            'action_log_date' => ['nullable', 'array', 'max:5'],
            'action_log_date.*' => ['nullable', 'date'],
            'action_log_time' => ['nullable', 'array', 'max:5'],
            'action_log_time.*' => ['nullable', 'date_format:H:i'],
            'action_log_action_taken' => ['nullable', 'array', 'max:5'],
            'action_log_action_taken.*' => ['nullable', 'string', 'max:255'],
            'action_log_action_officer' => ['nullable', 'array', 'max:5'],
            'action_log_action_officer.*' => ['nullable', 'string', 'max:255'],
            'noted_by_name' => ['nullable', 'string', 'max:255'],
            'noted_by_position' => ['nullable', 'string', 'max:255'],
            'noted_by_date_signed' => ['nullable', 'date'],
        ]);

        $validated['approved_by_signature'] = $validated['approved_by_signature'] ?? '';

        $dateRows = $validated['action_log_date'] ?? [];
        $timeRows = $validated['action_log_time'] ?? [];
        $actionRows = $validated['action_log_action_taken'] ?? [];
        $officerRows = $validated['action_log_action_officer'] ?? [];

        $actionLogs = [];
        for ($i = 0; $i < 5; $i++) {
            $row = [
                'date' => $dateRows[$i] ?? null,
                'time' => $timeRows[$i] ?? null,
                'action_taken' => $actionRows[$i] ?? null,
                'action_officer' => $officerRows[$i] ?? null,
            ];

            if (filled($row['date']) || filled($row['time']) || filled($row['action_taken']) || filled($row['action_officer'])) {
                $actionLogs[] = $row;
            }
        }

        $validated['action_logs'] = $actionLogs !== [] ? $actionLogs : null;

        unset(
            $validated['action_log_date'],
            $validated['action_log_time'],
            $validated['action_log_action_taken'],
            $validated['action_log_action_officer']
        );

        return $validated;
    }

    private function scopeForUser(Builder $query): Builder
    {
        $user = Auth::user();

        if ($this->isAdmin()) {
            return $query;
        }

        if ($user?->department_status !== 'approved') {
            return $query->whereRaw('1 = 0');
        }

        if (blank($user?->department)) {
            return $query->whereRaw('1 = 0');
        }

        return $query->where('department_code', (string) $user->department);
    }

    private function canAccess(ServiceRequest $serviceRequest): bool
    {
        $user = Auth::user();

        if ($this->isAdmin()) {
            return true;
        }

        if ($user?->department_status !== 'approved') {
            return false;
        }

        if (blank($user?->department)) {
            return false;
        }

        return (string) $serviceRequest->department_code === (string) $user->department;
    }

    private function isAdmin(): bool
    {
        return strtoupper((string) Auth::user()?->department) === 'ADMIN';
    }

    private function approvedDepartmentOptions(bool $excludeAdmin = false, ?string $excludeDepartment = null): array
    {
        $options = User::query()
            ->where('department_status', 'approved')
            ->whereNotNull('department')
            ->pluck('department')
            ->map(fn (string $department): string => trim($department))
            ->filter(fn (string $department): bool => $department !== '')
            ->unique()
            ->sort()
            ->values()
            ->all();

        if ($excludeAdmin) {
            $options = array_values(array_filter(
                $options,
                fn (string $department): bool => strtoupper($department) !== 'ADMIN'
            ));
        }

        if ($excludeDepartment !== null && $excludeDepartment !== '') {
            $options = array_values(array_filter(
                $options,
                fn (string $department): bool => $department !== $excludeDepartment
            ));
        }

        if ($options === []) {
            return ['Role 1', 'Role 2', 'Role 3', 'Role 4', 'Role 5', 'Role 6', 'Role 7', 'Role 8', 'Role 9'];
        }

        return $options;
    }
}
