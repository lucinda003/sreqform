<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Office;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class OfficeController extends Controller
{
    public function index(): View
    {
        $offices = Office::orderBy('name')->get();
        return view('admin.offices.index', compact('offices'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:offices,name'],
        ]);

        Office::create($validated);

        return redirect()->route('admin.offices.index')->with('status', 'Office added successfully.');
    }

    public function update(Request $request, Office $office): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:offices,name,' . $office->id],
            'is_active' => ['boolean'],
        ]);

        $office->update($validated);

        return redirect()->route('admin.offices.index')->with('status', 'Office updated successfully.');
    }

    public function destroy(Office $office): RedirectResponse
    {
        $office->delete();
        return redirect()->route('admin.offices.index')->with('status', 'Office deleted successfully.');
    }
}
