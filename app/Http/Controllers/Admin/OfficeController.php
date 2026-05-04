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

        return $this->redirectAfterAction($request, 'Office added successfully.');
    }

    public function update(Request $request, Office $office): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:offices,name,' . $office->id],
            'is_active' => ['boolean'],
        ]);

        $office->update($validated);

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
}
