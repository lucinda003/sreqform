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

        $offices = Office::query()
            ->orderBy('name')
            ->get();

        $systems = ApplicationSystem::query()
            ->orderBy('name')
            ->get();

        return view('admin.management.index', [
            'offices' => $offices,
            'systems' => $systems,
            'activeTab' => $activeTab,
        ]);
    }
}
