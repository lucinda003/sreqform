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
    public function index(): View
    {
        $offices = Office::orderBy('parent_name')->orderBy('name')->get();
        $parentOfficeOptions = $this->parentOfficeOptions();

        return view('admin.offices.index', compact('offices', 'parentOfficeOptions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'parent_name' => ['required', 'string', 'max:255', Rule::in($this->parentOfficeOptions())],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('offices', 'name')->where('parent_name', $request->input('parent_name')),
            ],
        ]);

        Office::create($validated);

        return $this->redirectAfterAction($request, 'Office added successfully.');
    }

    public function update(Request $request, Office $office): RedirectResponse
    {
        $validated = $request->validate([
            'parent_name' => ['required', 'string', 'max:255', Rule::in($this->parentOfficeOptions())],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('offices', 'name')
                    ->where('parent_name', $request->input('parent_name'))
                    ->ignore($office->id),
            ],
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

    private function parentOfficeOptions(): array
    {
        return [
            'DOH CENTRAL OFFICE',
            'CENTERS FOR HEALTH DEVELOPMENT',
            'DOH HOSPITALS',
            'ATTACHED AGENCIES',
        ];
    }
}
