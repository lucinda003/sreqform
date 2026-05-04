<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApplicationSystem;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ApplicationSystemController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));

        $systems = ApplicationSystem::query()
            ->when($search !== '', function ($query) use ($search): void {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->orderBy('name')
            ->get();

        return view('admin.application-systems.index', compact('systems', 'search'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:application_systems,name'],
        ]);

        ApplicationSystem::create($validated);

        return $this->redirectAfterAction($request, 'Application system added successfully.');
    }

    public function update(Request $request, ApplicationSystem $system): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:application_systems,name,' . $system->id],
            'is_active' => ['boolean'],
        ]);

        $system->update($validated);

        return $this->redirectAfterAction($request, 'Application system updated successfully.');
    }

    public function destroy(Request $request, ApplicationSystem $system): RedirectResponse
    {
        $system->delete();
        return $this->redirectAfterAction($request, 'Application system deleted successfully.');
    }

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'exists:application_systems,id'],
        ]);

        $deletedCount = ApplicationSystem::query()
            ->whereIn('id', $validated['ids'])
            ->delete();

        $suffix = $deletedCount === 1 ? '' : 's';

        return $this->redirectAfterAction(
            $request,
            $deletedCount . ' system' . $suffix . ' deleted successfully.'
        );
    }

    private function redirectAfterAction(Request $request, string $message): RedirectResponse
    {
        if ($request->input('return_to') === 'management') {
            return redirect()
                ->route('admin.management.index', ['tab' => 'systems'])
                ->with('status', $message);
        }

        return redirect()
            ->route('admin.application-systems.index')
            ->with('status', $message);
    }
}
