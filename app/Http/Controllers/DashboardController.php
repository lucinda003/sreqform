<?php

namespace App\Http\Controllers;

use App\Models\ServiceRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $range = $request->query('range', 'all');
        if (! in_array($range, ['all', 'today', 'week'], true)) {
            $range = 'all';
        }

        $baseQuery = $this->scopeForDashboard(ServiceRequest::query());
        if ($range === 'today') {
            $baseQuery->whereDate('created_at', today());
        }

        if ($range === 'week') {
            $baseQuery->whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ]);
        }

        $chatBaseQuery = $this->scopeForDashboard(
            ServiceRequest::query()->whereNotNull('contact_chat_requested_at')
        );

        if ($range === 'today') {
            $chatBaseQuery->whereDate('contact_chat_requested_at', today());
        }

        if ($range === 'week') {
            $chatBaseQuery->whereBetween('contact_chat_requested_at', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ]);
        }

        $totalRequests = (clone $baseQuery)->count();
        $todayRequests = $this->scopeForDashboard(ServiceRequest::query())
            ->whereDate('created_at', today())
            ->count();
        $thisWeekRequests = $this->scopeForDashboard(ServiceRequest::query())
            ->whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ])
            ->count();
        $uniqueOffices = (clone $baseQuery)->distinct('office')->count('office');
        $pendingRequests = (clone $baseQuery)->where('status', 'pending')->count();
        $checkingRequests = (clone $baseQuery)->where('status', 'checking')->count();
        $approvedRequests = (clone $baseQuery)->where('status', 'approved')->count();
        $rejectedRequests = (clone $baseQuery)->where('status', 'rejected')->count();
        $requestMessages = (clone $chatBaseQuery)->count();

        $recentRequests = (clone $baseQuery)
            ->latest()
            ->take(8)
            ->get();

        $recentChatRequests = (clone $chatBaseQuery)
            ->orderByDesc('contact_chat_requested_at')
            ->orderByDesc('updated_at')
            ->take(8)
            ->get();

        return view('dashboard', [
            'totalRequests' => $totalRequests,
            'todayRequests' => $todayRequests,
            'thisWeekRequests' => $thisWeekRequests,
            'uniqueOffices' => $uniqueOffices,
            'pendingRequests' => $pendingRequests,
            'checkingRequests' => $checkingRequests,
            'approvedRequests' => $approvedRequests,
            'rejectedRequests' => $rejectedRequests,
            'requestMessages' => $requestMessages,
            'recentRequests' => $recentRequests,
            'recentChatRequests' => $recentChatRequests,
            'range' => $range,
        ]);
    }

    private function scopeForDashboard(Builder $query): Builder
    {
        $user = Auth::user();

        if ($user === null) {
            return $query->whereRaw('1 = 0');
        }

        if (strtoupper((string) $user->department) === 'ADMIN') {
            return $query;
        }

        if ($user->department_status !== 'approved') {
            return $query->whereRaw('1 = 0');
        }

        return $query->where(function (Builder $builder) use ($user): void {
            $builder->where('user_id', (int) $user->id);

            $department = trim((string) ($user->department ?? ''));
            if ($department !== '') {
                $builder->orWhere(function (Builder $legacyBuilder) use ($department): void {
                    $legacyBuilder
                        ->whereNull('user_id')
                        ->where('department_code', $department);
                });
            }
        });
    }
}
