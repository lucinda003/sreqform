<?php

namespace App\Http\Controllers;

use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        if (strtoupper((string) Auth::user()?->department) !== 'ADMIN') {
            return redirect()->route('service-requests.index');
        }

        $range = $request->query('range', 'all');
        if (! in_array($range, ['all', 'today', 'week'], true)) {
            $range = 'all';
        }

        $baseQuery = ServiceRequest::query();
        if ($range === 'today') {
            $baseQuery->whereDate('created_at', today());
        }
        if ($range === 'week') {
            $baseQuery->whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ]);
        }

        $totalRequests = (clone $baseQuery)->count();
        $todayRequests = ServiceRequest::whereDate('created_at', today())->count();
        $thisWeekRequests = ServiceRequest::whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek(),
        ])->count();
        $uniqueOffices = (clone $baseQuery)->distinct('office')->count('office');
        $pendingRequests = (clone $baseQuery)->where('status', 'pending')->count();
        $approvedRequests = (clone $baseQuery)->where('status', 'approved')->count();
        $rejectedRequests = (clone $baseQuery)->where('status', 'rejected')->count();

        $recentRequests = (clone $baseQuery)
            ->latest()
            ->take(8)
            ->get();

        return view('dashboard', [
            'totalRequests' => $totalRequests,
            'todayRequests' => $todayRequests,
            'thisWeekRequests' => $thisWeekRequests,
            'uniqueOffices' => $uniqueOffices,
            'pendingRequests' => $pendingRequests,
            'approvedRequests' => $approvedRequests,
            'rejectedRequests' => $rejectedRequests,
            'recentRequests' => $recentRequests,
            'range' => $range,
        ]);
    }
}
