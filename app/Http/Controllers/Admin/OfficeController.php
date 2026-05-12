<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Office;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

class OfficeController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));
        $offices = Office::query()
            ->when($search !== '', function ($query) use ($search): void {
                $like = '%' . str_replace(['%', '_'], ['\%', '\_'], $search) . '%';

                $query->where(function ($builder) use ($like): void {
                    $builder
                        ->where('parent_name', 'like', $like)
                        ->orWhere('name', 'like', $like)
                        ->orWhere('address', 'like', $like)
                        ->orWhere('regcode', 'like', $like)
                        ->orWhere('licensing_status', 'like', $like)
                        ->orWhere('facility_type', 'like', $like)
                        ->orWhere('classification', 'like', $like)
                        ->orWhere('region', 'like', $like)
                        ->orWhere('province', 'like', $like)
                        ->orWhere('city', 'like', $like)
                        ->orWhere('barangay', 'like', $like)
                        ->orWhere('phone', 'like', $like);
                });
            })
            ->orderBy('region')
            ->orderBy('name')
            ->paginate(50)
            ->withQueryString();
        $parentOfficeOptions = $this->parentOfficeOptions();

        return view('admin.offices.index', compact('offices', 'parentOfficeOptions', 'search'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatedOfficeData($request);

        Office::create($this->officePayload($validated, true));

        return $this->redirectAfterAction($request, 'Office added successfully.');
    }

    public function update(Request $request, Office $office): RedirectResponse
    {
        $validated = $this->validatedOfficeData($request, true);

        $office->update($this->officePayload($validated, false));

        return $this->redirectAfterAction($request, 'Office updated successfully.');
    }

    public function destroy(Request $request, Office $office): RedirectResponse
    {
        $office->delete();
        return $this->redirectAfterAction($request, 'Office deleted successfully.');
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:offices,id'],
        ]);

        $deletedCount = Office::query()
            ->whereIn('id', $validated['ids'])
            ->delete();

        $suffix = $deletedCount === 1 ? '' : 's';

        return $this->redirectAfterAction(
            $request,
            $deletedCount . ' office' . $suffix . ' deleted successfully.'
        );
    }

    private function redirectAfterAction(Request $request, string $message): RedirectResponse
    {
        if ($request->input('return_to') === 'management') {
            return redirect()
                ->route('admin.management.index', ['tab' => 'offices'])
                ->with('status', $message);
        }

        return redirect()
            ->route('admin.offices.index')
            ->with('status', $message);
    }

    private function validatedOfficeData(Request $request, bool $includeStatus = false): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'licensing_status' => ['nullable', 'string', 'max:255'],
            'license_date' => ['nullable', 'date'],
            'facility_type' => ['nullable', 'string', 'max:255'],
            'classification' => ['nullable', 'string', 'max:255'],
            'street' => ['nullable', 'string', 'max:255'],
            'building' => ['nullable', 'string', 'max:255'],
            'region' => ['required', 'string', 'max:255', Rule::in($this->parentOfficeOptions())],
            'province' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'barangay' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
        ];

        if ($includeStatus) {
            $rules['is_active'] = ['boolean'];
        }

        return $request->validate($rules);
    }

    private function officePayload(array $validated, bool $isNew): array
    {
        $payload = [
            'parent_name' => $validated['region'],
            'name' => $validated['name'],
            'address' => $this->buildOfficeAddress($validated),
            'licensing_status' => $validated['licensing_status'] ?? null,
            'license_date' => $validated['license_date'] ?? null,
            'facility_type' => $validated['facility_type'] ?? null,
            'classification' => $validated['classification'] ?? null,
            'street' => $validated['street'] ?? null,
            'building' => $validated['building'] ?? null,
            'region' => $validated['region'] ?? null,
            'province' => $validated['province'] ?? null,
            'city' => $validated['city'] ?? null,
            'barangay' => $validated['barangay'] ?? null,
            'phone' => $validated['phone'] ?? null,
        ];

        if (! $isNew) {
            $payload['is_active'] = (bool) ($validated['is_active'] ?? false);
        }

        return $payload;
    }

    private function buildOfficeAddress(array $data): string
    {
        return collect([
            $data['building'] ?? null,
            $data['street'] ?? null,
            $data['barangay'] ?? null,
            $data['city'] ?? null,
            $data['province'] ?? null,
            $data['region'] ?? null,
        ])
            ->map(fn ($value): string => trim((string) $value))
            ->filter()
            ->unique()
            ->implode(', ');
    }

    private function parentOfficeOptions(): array
    {
        return [
            'REGION I (ILOCOS REGION)',
            'REGION II (CAGAYAN VALLEY)',
            'REGION III (CENTRAL LUZON)',
            'REGION IV-A (CALABARZON)',
            'REGION V (BICOL REGION)',
            'REGION VI (WESTERN VISAYAS)',
            'REGION VII (CENTRAL VISAYAS)',
            'REGION VIII (EASTERN VISAYAS)',
            'REGION IX (ZAMBOANGA PENINSULA)',
            'REGION X (NORTHERN MINDANAO)',
            'REGION XI (DAVAO REGION)',
            'REGION XII (SOCCSKSARGEN)',
            'NATIONAL CAPITAL REGION (NCR)',
            'CORDILLERA ADMINISTRATIVE REGION (CAR)',
            'REGION XIII (CARAGA)',
            'MIMAROPA REGION',
            'NEGROS ISLAND REGION (NIR)',
            'BANGSAMORO AUTONOMOUS REGION IN MUSLIM MINDANAO (BARMM)',
        ];
    }
}
