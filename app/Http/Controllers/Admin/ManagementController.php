<?php

namespace App\Http\Controllers\Admin;

use App\Models\Office;
use App\Models\ApplicationSystem;
use Illuminate\Http\Request;

class ManagementController
{
    public function index(Request $request)
    {
        $requestedTab = strtolower((string) $request->query('tab', ''));
        $activeTab = in_array($requestedTab, ['offices', 'systems'], true) ? $requestedTab : 'offices';
        $officeSearch = trim((string) $request->query('office_search', ''));
        $systemSearch = trim((string) $request->query('system_search', ''));

        $offices = Office::query()
            ->when($officeSearch !== '', function ($query) use ($officeSearch): void {
                $like = '%' . str_replace(['%', '_'], ['\%', '\_'], $officeSearch) . '%';

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
            ->paginate(50, ['*'], 'offices_page')
            ->withQueryString();

        $systems = ApplicationSystem::query()
            ->when($systemSearch !== '', function ($query) use ($systemSearch): void {
                $like = '%' . str_replace(['%', '_'], ['\%', '\_'], $systemSearch) . '%';

                $query->where('name', 'like', $like);
            })
            ->orderBy('name')
            ->paginate(50, ['*'], 'systems_page')
            ->withQueryString();

        return view('admin.management.index', [
            'offices' => $offices,
            'systems' => $systems,
            'activeTab' => $activeTab,
            'officeSearch' => $officeSearch,
            'systemSearch' => $systemSearch,
            'parentOfficeOptions' => $this->parentOfficeOptions(),
        ]);
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
