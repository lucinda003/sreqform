<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApplicationSystem;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ApplicationSystemController extends Controller
{
    public function index(): View
    {
        $systems = ApplicationSystem::orderBy('name')->get();
        return view('admin.application-systems.index', compact('systems'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:application_systems,name'],
        ]);

        ApplicationSystem::create($validated);

        return redirect()->route('admin.application-systems.index')->with('status', 'Application system added successfully.');
    }

    public function update(Request $request, ApplicationSystem $system): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:application_systems,name,' . $system->id],
            'is_active' => ['boolean'],
        ]);

        $system->update($validated);

        return redirect()->route('admin.application-systems.index')->with('status', 'Application system updated successfully.');
    }

    public function destroy(ApplicationSystem $system): RedirectResponse
    {
        $system->delete();
        return redirect()->route('admin.application-systems.index')->with('status', 'Application system deleted successfully.');
    }
}
