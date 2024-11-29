<?php

namespace App\Http\Controllers;

use App\Http\Requests\NotificationUserRequest;
use App\Models\Notification;
use App\Models\NotificationUser;
use Illuminate\Http\Request;

class NotificationUserController extends Controller
{
    public function index()
    {
        $notifications = NotificationUser::with('user', 'notificationUser')->paginate(10);
        return response()->json([
            'message' => 'Notifications retrieved successfully',
            'notifications' => $notifications
        ], 200);
    }

    public function create()
    {
        //
    }

    public function store(NotificationUserRequest $request)
    {
        try {
            $validatedData = $request->validated();

            $notification = NotificationUser::create($validatedData);

            return response()->json([
                'message' => 'Notification created successfully',
                'notification' => $notification,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create notification',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    public function show($id)
    {
        $notification = NotificationUser::with('user', 'notificationUser')->find($id);
        if (!$notification) {
            return response()->json([
                'message' => 'Notification not found',
            ], 404);
        }

        return response()->json([
            'message' => 'Notification retrieved successfully',
            'notification' => $notification,
        ], 200);
    }

    public function update(NotificationUserRequest $request, $id)
    {
        try {
            $validatedData = $request->validated();

            $notification = NotificationUser::find($id);
            if (!$notification) {
                return response()->json([
                    'message' => 'Notification not found',
                ], 404);
            }

            $notification->update($validatedData);

            return response()->json([
                'message' => 'Notification updated successfully',
                'notification' => $notification,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update notification',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    public function destroy($id)
    {
        $notification = NotificationUserRequest::find($id);
        if (!$notification) {
            return response()->json([
                'message' => 'Notification not found',
            ], 404);
        }

        $notification->delete();

        return response()->json([
            'message' => 'Notification deleted successfully',
        ], 200);
    }
}
