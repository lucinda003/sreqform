<?php

namespace App\Http\Controllers\Admin;

use App\Models\Office;
use App\Models\ApplicationSystem;
use Illuminate\Http\Request;

class ManagementController
{
    public function index()
    {
        $offices = Office::orderBy('name')->get();
        $systems = ApplicationSystem::orderBy('name')->get();

        return view('admin.management.index', [
            'offices' => $offices,
            'systems' => $systems,
        ]);
    }
}
