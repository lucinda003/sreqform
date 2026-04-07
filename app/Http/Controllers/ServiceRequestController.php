<?php

namespace App\Http\Controllers;

use App\Models\ServiceRequest;
use App\Models\ServiceRequestMessage;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class ServiceRequestController extends Controller
{
    public function index(Request $request): View
    {
        $statusFilter = trim((string) $request->query('status'));
        $search = trim((string) $request->query('q'));
        $chatRequestFilter = trim((string) $request->query('chat_request'));

        $serviceRequestsQuery = $this->scopeForUser(ServiceRequest::query())->latest();

        if ($statusFilter === 'archived') {
            $serviceRequestsQuery->whereIn('status', ['approved', 'rejected']);
        } elseif (in_array($statusFilter, ['pending', 'checking'], true)) {
            $serviceRequestsQuery->where('status', $statusFilter);
        } else {
            $statusFilter = '';
            $serviceRequestsQuery->whereIn('status', ['pending', 'checking']);
        }

        if ($search !== '') {
            $serviceRequestsQuery->where(function (Builder $builder) use ($search): void {
                $like = '%' . $search . '%';

                $builder
                    ->where('reference_code', 'like', $like)
                    ->orWhere('contact_last_name', 'like', $like)
                    ->orWhere('contact_first_name', 'like', $like)
                    ->orWhere('office', 'like', $like)
                    ->orWhere('application_system_name', 'like', $like)
                    ->orWhere('request_category', 'like', $like)
                    ->orWhere('status', 'like', $like);
            });
        }

        if (in_array($chatRequestFilter, ['pending', 'accepted', 'rejected'], true)) {
            $serviceRequestsQuery->where('contact_chat_status', $chatRequestFilter);
        } else {
            $chatRequestFilter = '';
        }

        $serviceRequests = $serviceRequestsQuery
            ->paginate(15)
            ->withQueryString();

        return view('service-requests.index', [
            'serviceRequests' => $serviceRequests,
            'statusFilter' => $statusFilter,
            'search' => $search,
            'chatRequestFilter' => $chatRequestFilter,
        ]);
    }

    public function chatRequests(Request $request): View
    {
        abort_unless($this->isAdmin(), 403);

        $chatStatus = strtolower(trim((string) $request->query('chat_status', 'pending')));
        $search = trim((string) $request->query('q'));

        $chatRequestsQuery = ServiceRequest::query()
            ->whereNotNull('contact_chat_status')
            ->latest('contact_chat_requested_at')
            ->latest();

        if (in_array($chatStatus, ['pending', 'accepted', 'rejected'], true)) {
            $chatRequestsQuery->where('contact_chat_status', $chatStatus);
        } else {
            $chatStatus = 'all';
        }

        if ($search !== '') {
            $chatRequestsQuery->where(function (Builder $builder) use ($search): void {
                $like = '%' . $search . '%';

                $builder
                    ->where('reference_code', 'like', $like)
                    ->orWhere('contact_last_name', 'like', $like)
                    ->orWhere('contact_first_name', 'like', $like)
                    ->orWhere('office', 'like', $like)
                    ->orWhere('application_system_name', 'like', $like)
                    ->orWhere('contact_chat_status', 'like', $like);
            });
        }

        $chatRequests = $chatRequestsQuery
            ->paginate(12)
            ->withQueryString();

        return view('service-requests.chat-requests', [
            'chatRequests' => $chatRequests,
            'chatStatus' => $chatStatus,
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        $currentDepartment = trim((string) Auth::user()?->department);

        return view('service-requests.create', [
            'departmentOptions' => $this->approvedDepartmentOptions(true, $currentDepartment !== '' ? $currentDepartment : null),
        ]);
    }

    public function track(Request $request): View|RedirectResponse
    {
        $referenceCode = trim((string) $request->query('reference_code'));
        $serviceRequest = null;
        $trackEditUrl = null;
        $chatMessages = collect();
        $chatAccepted = false;
        $chatStatus = null;

        if ($referenceCode !== '') {
            $normalizedReferenceCode = $this->normalizeReferenceCode($referenceCode);
            $serviceRequest = ServiceRequest::query()
                ->whereRaw('REPLACE(UPPER(reference_code), ?, ?) = ?', ['-', '', $normalizedReferenceCode])
                ->first();

            if ($serviceRequest) {
                $trackEditUrl = URL::temporarySignedRoute(
                    'service-requests.track.edit',
                    now()->addMinutes(30),
                    ['referenceCode' => $serviceRequest->reference_code]
                );

                $chatAccepted = $this->isChatAccepted($serviceRequest);
                $chatStatus = strtolower((string) ($serviceRequest->contact_chat_status ?? ''));
                $chatMessages = $chatAccepted ? $this->chatMessagesFor($serviceRequest) : collect();
            }
        }

        return view('service-requests.track', [
            'referenceCode' => $referenceCode,
            'serviceRequest' => $serviceRequest,
            'trackEditUrl' => $trackEditUrl,
            'chatMessages' => $chatMessages,
            'chatAccepted' => $chatAccepted,
            'chatStatus' => $chatStatus,
        ]);
    }

    public function sendTrackEditLink(string $referenceCode): RedirectResponse
    {
        $normalizedReferenceCode = $this->normalizeReferenceCode($referenceCode);
        $serviceRequest = ServiceRequest::query()
            ->whereRaw('REPLACE(UPPER(reference_code), ?, ?) = ?', ['-', '', $normalizedReferenceCode])
            ->firstOrFail();

        $recipientEmail = trim((string) ($serviceRequest->email_address ?? ''));
        if ($recipientEmail === '') {
            return redirect()
                ->route('service-requests.track', ['reference_code' => $serviceRequest->reference_code])
                ->with('status', 'No email address found for this request. Please contact support to update your email.');
        }

        $trackEditUrl = URL::temporarySignedRoute(
            'service-requests.track.edit',
            now()->addMinutes(30),
            ['referenceCode' => $serviceRequest->reference_code]
        );

        $statusMessage = 'Edit link has been sent to your email.';

        try {
            Mail::raw(
                "Your DOH service request edit link is below:\n\n{$trackEditUrl}\n\nThis link expires in 30 minutes.",
                function ($message) use ($recipientEmail, $serviceRequest): void {
                    $message
                        ->to($recipientEmail)
                        ->subject('DOH Service Request Edit Link - ' . $serviceRequest->reference_code);
                }
            );
        } catch (\Throwable $exception) {
            report($exception);
            $statusMessage = 'Unable to send edit link right now. Please try again later.';
        }

        return redirect()
            ->route('service-requests.track', ['reference_code' => $serviceRequest->reference_code])
            ->with('status', $statusMessage);
    }

    public function requestTrackChat(Request $request, string $referenceCode): RedirectResponse|JsonResponse
    {
        $normalizedReferenceCode = $this->normalizeReferenceCode($referenceCode);
        $serviceRequest = ServiceRequest::query()
            ->whereRaw('REPLACE(UPPER(reference_code), ?, ?) = ?', ['-', '', $normalizedReferenceCode])
            ->firstOrFail();

        $chatStatus = strtolower((string) ($serviceRequest->contact_chat_status ?? ''));
        $statusMessage = 'Chat request sent. Please wait for admin approval.';

        if ($chatStatus === 'accepted') {
            $statusMessage = 'Chat request already accepted by admin.';
        } elseif ($chatStatus === 'pending') {
            $statusMessage = 'Chat request already sent. Please wait for admin approval.';
        } else {
            $serviceRequest->update([
                'contact_chat_status' => 'pending',
                'contact_chat_requested_at' => now(),
                'contact_chat_decided_at' => null,
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'status' => strtolower((string) ($serviceRequest->fresh()->contact_chat_status ?? $chatStatus)),
                'message' => $statusMessage,
            ]);
        }

        return redirect()
            ->route('service-requests.track', ['reference_code' => $serviceRequest->reference_code])
            ->with('status', $statusMessage);
    }

    public function postTrackMessage(Request $request, string $referenceCode): RedirectResponse|JsonResponse
    {
        $normalizedReferenceCode = $this->normalizeReferenceCode($referenceCode);
        $serviceRequest = ServiceRequest::query()
            ->whereRaw('REPLACE(UPPER(reference_code), ?, ?) = ?', ['-', '', $normalizedReferenceCode])
            ->firstOrFail();

        if (! $this->isChatAccepted($serviceRequest)) {
            $errorMessage = 'Chat is hidden until admin accepts your request.';

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $errorMessage,
                ], 403);
            }

            return redirect()
                ->route('service-requests.track', ['reference_code' => $serviceRequest->reference_code])
                ->with('status', $errorMessage);
        }

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ]);

        $message = trim((string) $validated['message']);
        if ($message === '') {
            return redirect()
                ->route('service-requests.track', ['reference_code' => $serviceRequest->reference_code])
                ->withErrors(['message' => 'Message cannot be empty.'])
                ->withInput();
        }

        ServiceRequestMessage::create([
            'service_request_id' => $serviceRequest->id,
            'sender_user_id' => null,
            'sender_type' => 'requestor',
            'message' => $message,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'ok',
                'messages' => $this->serializedChatMessages($this->chatMessagesFor($serviceRequest)),
            ]);
        }

        return redirect()
            ->route('service-requests.track', ['reference_code' => $serviceRequest->reference_code])
            ->with('status', 'Message sent to admin personnel.');
    }

    public function trackMessages(string $referenceCode): JsonResponse
    {
        $normalizedReferenceCode = $this->normalizeReferenceCode($referenceCode);
        $serviceRequest = ServiceRequest::query()
            ->whereRaw('REPLACE(UPPER(reference_code), ?, ?) = ?', ['-', '', $normalizedReferenceCode])
            ->firstOrFail();

        $chatStatus = strtolower((string) ($serviceRequest->contact_chat_status ?? ''));
        $chatAccepted = $this->isChatAccepted($serviceRequest);

        return response()->json([
            'chat_status' => $chatStatus !== '' ? $chatStatus : null,
            'chat_accepted' => $chatAccepted,
            'messages' => $chatAccepted
                ? $this->serializedChatMessages($this->chatMessagesFor($serviceRequest))
                : [],
        ]);
    }

    public function postAdminMessage(Request $request, ServiceRequest $serviceRequest): RedirectResponse|JsonResponse
    {
        abort_unless($this->isAdmin(), 403);

        if (! $this->isChatAccepted($serviceRequest)) {
            $errorMessage = 'Chat is not available yet. Accept the chat request first.';

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $errorMessage,
                ], 403);
            }

            return redirect()
                ->route('service-requests.edit', $serviceRequest)
                ->with('status', $errorMessage);
        }

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ]);

        $message = trim((string) $validated['message']);
        if ($message === '') {
            return redirect()
                ->route('service-requests.edit', $serviceRequest)
                ->withErrors(['message' => 'Message cannot be empty.'])
                ->withInput();
        }

        ServiceRequestMessage::create([
            'service_request_id' => $serviceRequest->id,
            'sender_user_id' => Auth::id(),
            'sender_type' => 'admin',
            'message' => $message,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'ok',
                'messages' => $this->serializedChatMessages($this->chatMessagesFor($serviceRequest, 150)),
            ]);
        }

        return redirect()
            ->route('service-requests.edit', $serviceRequest)
            ->with('status', 'Message sent to requestor.');
    }

    public function adminMessages(ServiceRequest $serviceRequest): JsonResponse
    {
        abort_unless($this->isAdmin(), 403);

        $chatStatus = strtolower((string) ($serviceRequest->contact_chat_status ?? ''));
        $chatAccepted = $this->isChatAccepted($serviceRequest);

        return response()->json([
            'chat_status' => $chatStatus !== '' ? $chatStatus : null,
            'chat_accepted' => $chatAccepted,
            'messages' => $chatAccepted
                ? $this->serializedChatMessages($this->chatMessagesFor($serviceRequest, 150))
                : [],
        ]);
    }

    public function adminChatNotifications(): JsonResponse
    {
        abort_unless($this->isAdmin(), 403);

        $pendingRequests = ServiceRequest::query()
            ->where('contact_chat_status', 'pending')
            ->orderByDesc('contact_chat_requested_at')
            ->orderByDesc('updated_at')
            ->limit(25)
            ->get(['id', 'reference_code', 'contact_chat_requested_at', 'updated_at']);

        $notifications = $pendingRequests
            ->map(function (ServiceRequest $serviceRequest): array {
                $requestedAt = $serviceRequest->contact_chat_requested_at ?? $serviceRequest->updated_at;

                return [
                    'key' => 'chat-request-' . (int) $serviceRequest->id . '-' . ($requestedAt?->timestamp ?? 0),
                    'reference_code' => (string) $serviceRequest->reference_code,
                    'message' => (string) $serviceRequest->reference_code . ' send a request chat',
                    'requested_at_label' => $requestedAt?->format('M j, Y g:i A') ?? '',
                    'requested_at_unix' => $requestedAt?->timestamp,
                    'edit_url' => route('service-requests.edit', ['serviceRequest' => $serviceRequest->id]),
                ];
            })
            ->values()
            ->all();

        return response()->json([
            'notifications' => $notifications,
        ]);
    }

    public function decideChatRequest(Request $request, ServiceRequest $serviceRequest): RedirectResponse
    {
        abort_unless($this->isAdmin(), 403);

        $validated = $request->validate([
            'decision' => ['required', 'in:accepted,rejected'],
        ]);

        $serviceRequest->update([
            'contact_chat_status' => $validated['decision'],
            'contact_chat_decided_at' => now(),
            'contact_chat_requested_at' => $serviceRequest->contact_chat_requested_at ?? now(),
        ]);

        if ($validated['decision'] === 'rejected') {
            $serviceRequest->chatMessages()->delete();
        }

        $statusMessage = $validated['decision'] === 'accepted'
            ? 'Chat request accepted. Messaging is now available.'
            : 'Chat request declined.';

        return redirect()
            ->route('service-requests.edit', $serviceRequest)
            ->with('status', $statusMessage);
    }

    public function trackView(string $referenceCode): View
    {
        $normalizedReferenceCode = $this->normalizeReferenceCode($referenceCode);
        $serviceRequest = ServiceRequest::query()
            ->whereRaw('REPLACE(UPPER(reference_code), ?, ?) = ?', ['-', '', $normalizedReferenceCode])
            ->firstOrFail();

        return view('service-requests.print', [
            'serviceRequest' => $serviceRequest,
        ]);
    }

    public function trackEdit(string $referenceCode): View
    {
        $normalizedReferenceCode = $this->normalizeReferenceCode($referenceCode);
        $serviceRequest = ServiceRequest::query()
            ->whereRaw('REPLACE(UPPER(reference_code), ?, ?) = ?', ['-', '', $normalizedReferenceCode])
            ->firstOrFail();

        $signedUpdateUrl = URL::temporarySignedRoute(
            'service-requests.track.update',
            now()->addMinutes(30),
            ['referenceCode' => $serviceRequest->reference_code]
        );

        return view('service-requests.track-edit', [
            'serviceRequest' => $serviceRequest,
            'signedUpdateUrl' => $signedUpdateUrl,
        ]);
    }

    public function trackUpdate(Request $request, string $referenceCode): RedirectResponse
    {
        $normalizedReferenceCode = $this->normalizeReferenceCode($referenceCode);
        $serviceRequest = ServiceRequest::query()
            ->whereRaw('REPLACE(UPPER(reference_code), ?, ?) = ?', ['-', '', $normalizedReferenceCode])
            ->firstOrFail();

        $validated = $this->validatedData($request);

        if ($this->hasDescriptionPhotosColumn()) {
            $validated['description_photos'] = $this->storeDescriptionPhotos(
                $request,
                $serviceRequest->description_photos
            );
        }

        $validated['approved_by_signature'] = $this->storeApprovedSignature(
            $request,
            (string) ($serviceRequest->approved_by_signature ?? '')
        );

        // Keep immutable ownership/scoping fields unchanged for track-based edits.
        $validated['department_code'] = (string) $serviceRequest->department_code;
        $validated['user_id'] = $serviceRequest->user_id;
        $validated['reference_code'] = (string) $serviceRequest->reference_code;
        $validated['status'] = (string) $serviceRequest->status;

        $serviceRequest->update($validated);

        return redirect()
            ->route('service-requests.track', ['reference_code' => $serviceRequest->reference_code])
            ->with('status', 'Request updated successfully. You can now print the updated form.');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatedData($request);
        if ($this->hasDescriptionPhotosColumn()) {
            $validated['description_photos'] = $this->storeDescriptionPhotos($request);
        }
        $validated['approved_by_signature'] = $this->storeApprovedSignature($request);

        // Public requesters and admin-submitted requests are all routed to ADMIN queue.
        $validated['department_code'] = 'ADMIN';

        $authUser = Auth::user();
        if ($authUser && ! $this->isAdmin() && $authUser->department_status !== 'approved') {
            return back()
                ->withErrors(['department_code' => 'Your department is pending admin approval.'])
                ->withInput();
        }

        $validated['reference_code'] = $this->generateReferenceCode(
            $validated['department_code'],
            $validated['request_date']
        );
        $validated['status'] = 'pending';
        $validated['pending_at'] = now();
        $validated['user_id'] = Auth::id();

        $serviceRequest = ServiceRequest::create($validated);

        if ($this->isAdmin()) {
            return redirect()
                ->route('service-requests.edit', $serviceRequest)
                ->with('status', 'Service Request submitted successfully.');
        }

        $signedEmailUrl = URL::temporarySignedRoute(
            'service-requests.capture-email',
            now()->addMinutes(60),
            ['serviceRequest' => $serviceRequest->id]
        );

        return redirect($signedEmailUrl);
    }

    public function captureEmailForm(ServiceRequest $serviceRequest): View
    {
        return view('service-requests.capture-email', [
            'serviceRequest' => $serviceRequest,
            'signedActionUrl' => URL::signedRoute('service-requests.capture-email.store', ['serviceRequest' => $serviceRequest->id]),
        ]);
    }

    public function captureEmailStore(Request $request, ServiceRequest $serviceRequest): RedirectResponse
    {
        $validated = $request->validate([
            'email_address' => ['required', 'string', 'email', 'max:255'],
        ]);

        $serviceRequest->update([
            'email_address' => $validated['email_address'],
        ]);

        $statusMessage = 'Reference Code saved. Check your email inbox for updates.';

        try {
            $referenceCode = $serviceRequest->reference_code;
            Mail::raw(
                "Your DOH service request reference code is {$referenceCode}.\n\nUse this code to track your request status at the Track Your Request page.",
                function ($message) use ($validated, $referenceCode): void {
                    $message
                        ->to($validated['email_address'])
                        ->subject("DOH Service Request Reference Code: {$referenceCode}");
                }
            );
        } catch (\Throwable $exception) {
            report($exception);
            $statusMessage = 'Email saved, but sending failed. You can still track using your reference code.';
        }

        return redirect()
            ->route('service-requests.track', ['reference_code' => $serviceRequest->reference_code])
            ->with('status', $statusMessage);
    }

    public function edit(Request $request, ServiceRequest $serviceRequest): View
    {
        abort_unless($this->canAccess($serviceRequest), 403);

        if (
            $this->isAdmin()
            && $serviceRequest->status === 'pending'
            && $request->query('skip_auto_checking') !== '1'
        ) {
            $serviceRequest->update([
                'status' => 'checking',
                'checking_at' => now(),
                'pending_at' => $serviceRequest->pending_at ?? $serviceRequest->created_at,
            ]);
            $serviceRequest->refresh();
        }

        if ($this->isAdmin()) {
            $now = now();
            $updates = [];

            $actionLogs = is_array($serviceRequest->action_logs) ? $serviceRequest->action_logs : [];
            $firstLog = is_array($actionLogs[0] ?? null) ? $actionLogs[0] : [];

            if (blank($firstLog['date'] ?? null)) {
                $firstLog['date'] = $now->toDateString();
            }

            if (blank($firstLog['time'] ?? null)) {
                $firstLog['time'] = $now->format('H:i');
            }

            if (filled($firstLog['date'] ?? null) || filled($firstLog['time'] ?? null)) {
                $actionLogs[0] = $firstLog;
                $updates['action_logs'] = $actionLogs;
            }

            if (blank($serviceRequest->time_received)) {
                $updates['time_received'] = $now->format('H:i');
            }

            if (blank($serviceRequest->kmits_date)) {
                $updates['kmits_date'] = $now->toDateString();
            }

            if ($updates !== []) {
                $serviceRequest->update($updates);
                $serviceRequest->refresh();
            }
        }

        $currentDepartment = trim((string) Auth::user()?->department);
        $chatMessages = $this->isChatAccepted($serviceRequest)
            ? $this->chatMessagesFor($serviceRequest, 150)
            : collect();

        return view('service-requests.edit', [
            'serviceRequest' => $serviceRequest,
            'departmentOptions' => $this->approvedDepartmentOptions(true, $currentDepartment !== '' ? $currentDepartment : null),
            'chatMessages' => $chatMessages,
        ]);
    }

    public function update(Request $request, ServiceRequest $serviceRequest): RedirectResponse
    {
        abort_unless($this->canAccess($serviceRequest), 403);

        $authUser = Auth::user();
        if (! $this->isAdmin() && $authUser?->department_status !== 'approved') {
            return back()
                ->withErrors(['department_code' => 'Your department is pending admin approval.'])
                ->withInput();
        }

        $validated = $this->isAdmin()
            ? $this->validatedKmitsData($request)
            : $this->validatedData($request);

        if ($this->hasDescriptionPhotosColumn()) {
            $validated['description_photos'] = $this->storeDescriptionPhotos(
                $request,
                $serviceRequest->description_photos
            );
        }

        if (! $this->isAdmin()) {
            $validated['approved_by_signature'] = $this->storeApprovedSignature(
                $request,
                (string) ($serviceRequest->approved_by_signature ?? '')
            );
        }

        // Keep department stable for non-admin users so requests remain in their scoped view.
        if (! $this->isAdmin()) {
            $validated['department_code'] = (string) $serviceRequest->department_code;
        }

        $serviceRequest->update($validated);

        return redirect()
            ->route('service-requests.edit', $serviceRequest)
            ->with('status', 'Service Request updated successfully.');
    }

    public function show(ServiceRequest $serviceRequest): RedirectResponse
    {
        abort_unless($this->canAccess($serviceRequest), 403);

        return redirect()->route('service-requests.edit', $serviceRequest);
    }

    public function print(ServiceRequest $serviceRequest): View
    {
        abort_unless($this->canAccess($serviceRequest), 403);

        return view('service-requests.print', [
            'serviceRequest' => $serviceRequest,
        ]);
    }

    public function downloadPdf(ServiceRequest $serviceRequest): Response
    {
        abort_unless($this->canAccess($serviceRequest), 403);

        $pdf = Pdf::loadView('service-requests.pdf', [
            'serviceRequest' => $serviceRequest,
        ]);

        return $pdf->download($serviceRequest->reference_code . '.pdf');
    }

    public function updateStatus(Request $request, ServiceRequest $serviceRequest): RedirectResponse
    {
        abort_unless($this->canManageStatus($serviceRequest), 403);

        $validated = $request->validate([
            'status' => ['required', 'in:pending,checking,approved,rejected'],
        ]);

        $now = now();
        $updates = [
            'status' => $validated['status'],
        ];

        if ($validated['status'] === 'pending') {
            $updates['pending_at'] = $now;
            $updates['checking_at'] = null;
            $updates['approved_at'] = null;
            $updates['rejected_at'] = null;
            $updates['completed_at'] = null;
            $updates['contact_chat_status'] = null;
            $updates['contact_chat_requested_at'] = null;
            $updates['contact_chat_decided_at'] = null;
        }

        if ($validated['status'] === 'checking') {
            $updates['checking_at'] = $now;
            $updates['pending_at'] = $serviceRequest->pending_at ?? $serviceRequest->created_at;
            $updates['approved_at'] = null;
            $updates['rejected_at'] = null;
            $updates['completed_at'] = null;
        }

        if ($validated['status'] === 'approved') {
            $updates['approved_at'] = $now;
            $updates['completed_at'] = $now;
            $updates['pending_at'] = $serviceRequest->pending_at ?? $serviceRequest->created_at;
            $updates['checking_at'] = $serviceRequest->checking_at ?? $serviceRequest->updated_at;
            $updates['rejected_at'] = null;
        }

        if ($validated['status'] === 'rejected') {
            $updates['rejected_at'] = $now;
            $updates['completed_at'] = $now;
            $updates['pending_at'] = $serviceRequest->pending_at ?? $serviceRequest->created_at;
            $updates['checking_at'] = $serviceRequest->checking_at ?? $serviceRequest->updated_at;
            $updates['approved_at'] = null;
        }

        if ($this->isChatLockedForStatus((string) $validated['status'])) {
            $updates['contact_chat_status'] = null;
            $updates['contact_chat_requested_at'] = null;
            $updates['contact_chat_decided_at'] = null;
        }

        $serviceRequest->update($updates);

        if ($validated['status'] === 'pending' || $this->isChatLockedForStatus((string) $validated['status'])) {
            $serviceRequest->chatMessages()->delete();
        }

        $routeParams = ['serviceRequest' => $serviceRequest];
        if ($validated['status'] === 'pending') {
            $routeParams['skip_auto_checking'] = '1';
        }

        return redirect()
            ->route('service-requests.edit', $routeParams)
            ->with('status', 'Request status updated successfully.');
    }

    private function generateReferenceCode(string $departmentCode, string $requestDate): string
    {
        $datePart = date('dmY', strtotime($requestDate));
        $sequence = ((int) ServiceRequest::query()->max('id')) + 1;

        return sprintf('SRF-%s-%05d', $datePart, $sequence);
    }

    private function normalizeReferenceCode(string $referenceCode): string
    {
        return strtoupper((string) preg_replace('/[^A-Za-z0-9]/', '', trim($referenceCode)));
    }

    private function hasDescriptionPhotosColumn(): bool
    {
        return Schema::hasColumn('service_requests', 'description_photos');
    }

    private function validatedData(Request $request): array
    {
        $validated = $request->validate([
            'request_date' => ['required', 'date'],
            'department_code' => ['nullable', 'string', 'max:30', 'in:ADMIN'],
            'request_category' => ['nullable', 'string', 'max:100'],
            'application_system_name' => ['required', 'string', 'max:255'],
            'expected_completion_date' => ['nullable', 'date'],
            'expected_completion_time' => ['nullable', 'date_format:H:i'],
            'contact_last_name' => ['required', 'string', 'max:255'],
            'contact_first_name' => ['required', 'string', 'max:255'],
            'contact_middle_name' => ['nullable', 'string', 'max:255'],
            'contact_suffix_name' => ['nullable', 'string', 'max:100'],
            'office' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'landline' => ['nullable', 'string', 'max:50', 'regex:/^[0-9]*$/'],
            'fax_no' => ['nullable', 'string', 'max:50', 'regex:/^[0-9]*$/'],
            'mobile_no' => ['required', 'string', 'max:50', 'regex:/^[0-9]+$/'],
            'email_address' => ['nullable', 'string', 'max:255'],
            'description_request' => ['required', 'string'],
            'description_photos' => ['nullable', 'array', 'max:3'],
            'description_photos.*' => ['nullable', 'image', 'max:5120'],
            'approved_by_name' => ['required', 'string', 'max:255'],
            'approved_by_signature' => ['nullable', 'string', 'max:255'],
            'approved_by_signature_mode' => ['nullable', 'in:draw,upload'],
            'approved_by_signature_drawn' => ['nullable', 'string'],
            'approved_by_signature_upload' => ['nullable', 'image', 'max:5120'],
            'approved_by_position' => ['required', 'string', 'max:255'],
            'approved_date' => ['required', 'date'],
            'kmits_date' => ['required', 'date'],
            'time_received' => ['nullable', 'date_format:H:i'],
            'action_log_date' => ['nullable', 'array', 'max:5'],
            'action_log_date.*' => ['nullable', 'date'],
            'action_log_time' => ['nullable', 'array', 'max:5'],
            'action_log_time.*' => ['nullable', 'date_format:H:i'],
            'action_log_action_date' => ['nullable', 'array', 'max:5'],
            'action_log_action_date.*' => ['nullable', 'date'],
            'action_log_action_time' => ['nullable', 'array', 'max:5'],
            'action_log_action_time.*' => ['nullable', 'date_format:H:i'],
            'action_log_action_taken' => ['nullable', 'array', 'max:5'],
            'action_log_action_taken.*' => ['nullable', 'string', 'max:255'],
            'action_log_action_officer' => ['nullable', 'array', 'max:5'],
            'action_log_action_officer.*' => ['nullable', 'string', 'max:255'],
            'noted_by_name' => ['nullable', 'string', 'max:255'],
            'noted_by_position' => ['nullable', 'string', 'max:255'],
            'noted_by_date_signed' => ['nullable', 'date'],
        ]);

        unset($validated['description_photos']);
        unset($validated['approved_by_signature_mode'], $validated['approved_by_signature_drawn'], $validated['approved_by_signature_upload']);

        $dateRows = $validated['action_log_date'] ?? [];
        $timeRows = $validated['action_log_time'] ?? [];
        $actionDateRows = $validated['action_log_action_date'] ?? [];
        $actionTimeRows = $validated['action_log_action_time'] ?? [];
        $actionRows = $validated['action_log_action_taken'] ?? [];
        $officerRows = $validated['action_log_action_officer'] ?? [];

        $actionLogs = [];
        for ($i = 0; $i < 5; $i++) {
            $row = [
                'date' => $dateRows[$i] ?? null,
                'time' => $timeRows[$i] ?? null,
                'action_date' => $actionDateRows[$i] ?? null,
                'action_time' => $actionTimeRows[$i] ?? null,
                'action_taken' => $actionRows[$i] ?? null,
                'action_officer' => $officerRows[$i] ?? null,
            ];

            if (
                filled($row['date'])
                || filled($row['time'])
                || filled($row['action_date'])
                || filled($row['action_time'])
                || filled($row['action_taken'])
                || filled($row['action_officer'])
            ) {
                $actionLogs[] = $row;
            }
        }

        $validated['action_logs'] = $actionLogs !== [] ? $actionLogs : null;

        unset(
            $validated['action_log_date'],
            $validated['action_log_time'],
            $validated['action_log_action_date'],
            $validated['action_log_action_time'],
            $validated['action_log_action_taken'],
            $validated['action_log_action_officer']
        );

        return $validated;
    }

    private function storeDescriptionPhotos(Request $request, ?array $existingPhotos = null): ?array
    {
        if (! $request->hasFile('description_photos')) {
            return $existingPhotos !== [] ? $existingPhotos : null;
        }

        $stored = [];
        foreach ((array) $request->file('description_photos') as $photo) {
            if ($photo === null) {
                continue;
            }

            $stored[] = $photo->store('service-request-photos', 'public');
        }

        return $stored !== [] ? $stored : ($existingPhotos !== [] ? $existingPhotos : null);
    }

    private function storeApprovedSignature(Request $request, ?string $existingSignature = null): ?string
    {
        $mode = trim((string) $request->input('approved_by_signature_mode'));
        $clearRequested = (string) $request->input('approved_by_signature_clear') === '1';
        $uploaded = $request->file('approved_by_signature_upload');
        $existingSignaturePath = trim((string) $existingSignature);

        if ($clearRequested) {
            $this->deleteSignatureFile($existingSignaturePath);
            return '';
        }

        if ($mode === 'upload' && $uploaded !== null) {
            $newPath = $uploaded->store('service-request-signatures', 'public');
            $this->deleteSignatureFile($existingSignaturePath);

            return $newPath;
        }

        if ($mode === 'draw') {
            $drawn = trim((string) $request->input('approved_by_signature_drawn'));

            if ($drawn !== '' && preg_match('/^data:image\/(png|jpeg);base64,/', $drawn, $matches) === 1) {
                $binary = base64_decode(substr($drawn, strpos($drawn, ',') + 1), true);

                if ($binary !== false) {
                    $extension = $matches[1] === 'jpeg' ? 'jpg' : 'png';
                    $path = 'service-request-signatures/' . Str::uuid()->toString() . '.' . $extension;
                    Storage::disk('public')->put($path, $binary);
                    $this->deleteSignatureFile($existingSignaturePath);

                    return $path;
                }
            }
        }

        return filled($existingSignaturePath) ? $existingSignaturePath : '';
    }

    private function deleteSignatureFile(?string $signaturePath): void
    {
        $path = trim((string) $signaturePath);

        if ($path === '' || ! str_starts_with($path, 'service-request-signatures/')) {
            return;
        }

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    private function validatedKmitsData(Request $request): array
    {
        $validated = $request->validate([
            'description_photos' => ['nullable', 'array', 'max:3'],
            'description_photos.*' => ['nullable', 'image', 'max:5120'],
            'action_log_date' => ['nullable', 'array', 'max:5'],
            'action_log_date.*' => ['nullable', 'date'],
            'action_log_time' => ['nullable', 'array', 'max:5'],
            'action_log_time.*' => ['nullable', 'date_format:H:i'],
            'action_log_action_date' => ['nullable', 'array', 'max:5'],
            'action_log_action_date.*' => ['nullable', 'date'],
            'action_log_action_time' => ['nullable', 'array', 'max:5'],
            'action_log_action_time.*' => ['nullable', 'date_format:H:i'],
            'action_log_action_taken' => ['nullable', 'array', 'max:5'],
            'action_log_action_taken.*' => ['nullable', 'string', 'max:255'],
            'action_log_action_officer' => ['nullable', 'array', 'max:5'],
            'action_log_action_officer.*' => ['nullable', 'string', 'max:255'],
            'noted_by_name' => ['nullable', 'string', 'max:255'],
            'noted_by_position' => ['nullable', 'string', 'max:255'],
            'noted_by_date_signed' => ['nullable', 'date'],
        ]);

        unset($validated['description_photos']);

        $dateRows = $validated['action_log_date'] ?? [];
        $timeRows = $validated['action_log_time'] ?? [];
        $actionDateRows = $validated['action_log_action_date'] ?? [];
        $actionTimeRows = $validated['action_log_action_time'] ?? [];
        $actionRows = $validated['action_log_action_taken'] ?? [];
        $officerRows = $validated['action_log_action_officer'] ?? [];

        $actionLogs = [];
        for ($i = 0; $i < 5; $i++) {
            $row = [
                'date' => $dateRows[$i] ?? null,
                'time' => $timeRows[$i] ?? null,
                'action_date' => $actionDateRows[$i] ?? null,
                'action_time' => $actionTimeRows[$i] ?? null,
                'action_taken' => $actionRows[$i] ?? null,
                'action_officer' => $officerRows[$i] ?? null,
            ];

            if (
                filled($row['date'])
                || filled($row['time'])
                || filled($row['action_date'])
                || filled($row['action_time'])
                || filled($row['action_taken'])
                || filled($row['action_officer'])
            ) {
                $actionLogs[] = $row;
            }
        }

        $validated['action_logs'] = $actionLogs !== [] ? $actionLogs : null;

        unset(
            $validated['action_log_date'],
            $validated['action_log_time'],
            $validated['action_log_action_date'],
            $validated['action_log_action_time'],
            $validated['action_log_action_taken'],
            $validated['action_log_action_officer']
        );

        return $validated;
    }

    private function scopeForUser(Builder $query): Builder
    {
        $user = Auth::user();

        if ($this->isAdmin()) {
            return $query;
        }

        if ($user?->department_status !== 'approved') {
            return $query->whereRaw('1 = 0');
        }

        if (blank($user?->department)) {
            return $query->where('user_id', $user?->id);
        }

        return $query->where(function (Builder $builder) use ($user): void {
            $builder
                ->where('department_code', (string) $user->department)
                ->orWhere('user_id', $user?->id);
        });
    }

    private function canAccess(ServiceRequest $serviceRequest): bool
    {
        $user = Auth::user();

        if ($this->isAdmin()) {
            return true;
        }

        if ($user?->department_status !== 'approved') {
            return false;
        }

        if ((int) $serviceRequest->user_id === (int) ($user?->id ?? 0)) {
            return true;
        }

        if (blank($user?->department)) {
            return false;
        }

        return (string) $serviceRequest->department_code === (string) $user->department;
    }

    private function isAdmin(): bool
    {
        return strtoupper((string) Auth::user()?->department) === 'ADMIN';
    }

    private function canManageStatus(ServiceRequest $serviceRequest): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        $user = Auth::user();

        if ($user?->department_status !== 'approved') {
            return false;
        }

        if (blank($user?->department)) {
            return false;
        }

        return (string) $serviceRequest->department_code === (string) $user->department;
    }

    private function isChatLockedForStatus(string $status): bool
    {
        return in_array(strtolower(trim($status)), ['approved', 'rejected', 'completed', 'closed'], true);
    }

    private function isChatAccepted(ServiceRequest $serviceRequest): bool
    {
        return strtolower((string) ($serviceRequest->contact_chat_status ?? '')) === 'accepted';
    }

    private function chatMessagesFor(ServiceRequest $serviceRequest, int $limit = 100): \Illuminate\Support\Collection
    {
        return $serviceRequest->chatMessages()
            ->with(['senderUser:id,name'])
            ->orderByDesc('created_at')
            ->take($limit)
            ->get()
            ->reverse()
            ->values();
    }

    private function serializedChatMessages(\Illuminate\Support\Collection $messages): array
    {
        return $messages
            ->map(function (ServiceRequestMessage $chatMessage): array {
                $isAdminMessage = strtolower((string) $chatMessage->sender_type) === 'admin';
                $senderLabel = $isAdminMessage
                    ? ('Admin' . (filled($chatMessage->senderUser?->name) ? ' - ' . $chatMessage->senderUser->name : ''))
                    : 'Requestor';

                return [
                    'id' => (int) $chatMessage->id,
                    'sender_type' => $isAdminMessage ? 'admin' : 'requestor',
                    'sender_label' => $senderLabel,
                    'message' => (string) $chatMessage->message,
                    'created_at_label' => $chatMessage->created_at?->format('M j, Y g:i A') ?? '',
                ];
            })
            ->values()
            ->all();
    }

    private function approvedDepartmentOptions(bool $excludeAdmin = false, ?string $excludeDepartment = null): array
    {
        $options = User::query()
            ->where('department_status', 'approved')
            ->whereNotNull('department')
            ->pluck('department')
            ->map(fn (string $department): string => trim($department))
            ->filter(fn (string $department): bool => $department !== '')
            ->unique()
            ->sort()
            ->values()
            ->all();

        if ($excludeAdmin) {
            $options = array_values(array_filter(
                $options,
                fn (string $department): bool => strtoupper($department) !== 'ADMIN'
            ));
        }

        if ($excludeDepartment !== null && $excludeDepartment !== '') {
            $options = array_values(array_filter(
                $options,
                fn (string $department): bool => $department !== $excludeDepartment
            ));
        }

        if ($options === []) {
            return ['ADMIN'];
        }

        return $options;
    }
}
