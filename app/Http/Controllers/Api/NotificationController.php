<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(private NotificationService $notificationService) {}

    public function index(Request $request): JsonResponse
    {
        $notifications = $this->notificationService->getForUser($request->user());
        return response()->json($notifications);
    }

    public function markRead(Request $request): JsonResponse
    {
        $this->notificationService->markAllRead($request->user());
        return response()->json(['message' => 'Notificações marcadas como lidas.']);
    }

    public function unreadCount(Request $request): JsonResponse
    {
        $count = $this->notificationService->unreadCount($request->user());
        return response()->json(['unread_count' => $count]);
    }
}