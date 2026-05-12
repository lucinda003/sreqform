<?php

namespace App\Http\Controllers;

use App\Models\ServiceRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        $data = $this->prepareDashboardData($request);
        
        return view('dashboard', $data);
    }

    public function indexAjax(Request $request): JsonResponse
    {
        $data = $this->prepareDashboardData($request);
        
        // Return rendered HTML instead of JSON for easier AJAX insertion
        $html = view('dashboard-content', $data)->render();
        
        return response()->json(['html' => $html]);
    }

    private function prepareDashboardData(Request $request): array
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

        $user = Auth::user();
        $userId = (int) ($user?->id ?? 0);
        $isSuperAdmin = strtolower(trim((string) ($user?->role ?? ''))) === 'super admin';
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
        if ($isSuperAdmin) {
            $receivedRequests = (clone $baseQuery)->whereNotNull('received_by_user_id')->count();
            $receivedCheckingRequests = (clone $baseQuery)->where('status', 'checking')->count();
            $receivedApprovedRequests = (clone $baseQuery)->where('status', 'approved')->count();
        } else {
            $receivedRequests = $userId > 0
                ? (clone $baseQuery)->where('received_by_user_id', $userId)->count()
                : 0;
            $receivedCheckingRequests = $userId > 0
                ? (clone $baseQuery)
                    ->where('received_by_user_id', $userId)
                    ->where('status', 'checking')
                    ->count()
                : 0;
            $receivedApprovedRequests = $userId > 0
                ? (clone $baseQuery)
                    ->where('received_by_user_id', $userId)
                    ->where('status', 'approved')
                    ->count()
                : 0;
        }
        $requestMessages = (clone $chatBaseQuery)->count();

        $recentRequests = $isSuperAdmin
            ? (clone $baseQuery)->latest()->take(8)->get()
            : (clone $baseQuery)
                ->where(function (Builder $builder) use ($userId): void {
                    $builder
                        ->where('received_by_user_id', $userId)
                        ->orWhere(function (Builder $approvedBuilder) use ($userId): void {
                            $approvedBuilder
                                ->where('status', 'approved')
                                ->where(function (Builder $ownerBuilder) use ($userId): void {
                                    $ownerBuilder
                                        ->where('approved_by_user_id', $userId)
                                        ->orWhere(function (Builder $legacyBuilder) use ($userId): void {
                                            $legacyBuilder
                                                ->whereNull('approved_by_user_id')
                                                ->where('user_id', $userId);
                                        });
                                });
                        });
                })
                ->latest()
                ->take(8)
                ->get();

        $recentChatRequests = (clone $chatBaseQuery)
            ->orderByDesc('contact_chat_requested_at')
            ->orderByDesc('updated_at')
            ->take(8)
            ->get();

        return [
            'totalRequests' => $totalRequests,
            'todayRequests' => $todayRequests,
            'thisWeekRequests' => $thisWeekRequests,
            'uniqueOffices' => $uniqueOffices,
            'pendingRequests' => $pendingRequests,
            'checkingRequests' => $checkingRequests,
            'approvedRequests' => $approvedRequests,
            'receivedRequests' => $receivedRequests,
            'receivedCheckingRequests' => $receivedCheckingRequests,
            'receivedApprovedRequests' => $receivedApprovedRequests,
            'requestMessages' => $requestMessages,
            'recentRequests' => $recentRequests,
            'recentChatRequests' => $recentChatRequests,
            'range' => $range,
        ];
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

        $userId = (int) $user->id;
        $department = trim((string) ($user->department ?? ''));

        return $query->where(function (Builder $builder) use ($userId, $department): void {
            $builder
                ->where(function (Builder $approvedBuilder) use ($userId): void {
                    $approvedBuilder
                        ->where('status', 'approved')
                        ->where(function (Builder $ownerBuilder) use ($userId): void {
                            $ownerBuilder
                                ->where('approved_by_user_id', $userId)
                                ->orWhere(function (Builder $legacyBuilder) use ($userId): void {
                                    $legacyBuilder
                                        ->whereNull('approved_by_user_id')
                                        ->where('user_id', $userId);
                                });
                        });
                })
                ->orWhere(function (Builder $nonFinalBuilder) use ($userId, $department): void {
                    $nonFinalBuilder
                        ->where('status', '!=', 'approved')
                        ->where(function (Builder $accessBuilder) use ($userId, $department): void {
                            $accessBuilder->where('user_id', $userId);

                            if ($department !== '') {
                                $accessBuilder->orWhere('department_code', $department);
                            }
                        });
                });
        });
    }
}
