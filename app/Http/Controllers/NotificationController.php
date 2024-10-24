<?php

namespace App\Http\Controllers;

use App\Http\Requests\NotificationRequest;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notifications = Notification::with('user')->paginate(10);
        return response()->json([
            'message' => 'Notifications retrieved successfully',
            'notifications' => $notifications
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NotificationRequest $request)
    {
        try {
            $validatedData = $request->validated();

            $notification = Notification::create($validatedData);

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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $notification = Notification::with('user')->find($id);

        if ($notification) {
            return response()->json([
                'message' => 'Notification retrieved successfully',
                'notification' => $notification
            ], 200);
        } else {
            return response()->json([
                'message' => 'Notification not found'
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function update(NotificationRequest $request, $id)
    {
        $notification = Notification::find($id);

        if ($notification) {
            $validatedData = $request->validated();
            $notification->update($validatedData);

            return response()->json([
                'message' => 'Notification updated successfully',
                'notification' => $notification,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Notification not found',
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $notification = Notification::find($id);

        if ($notification) {
            $notification->delete();
            return response()->json([
                'message' => 'Notification deleted successfully',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Notification not found',
            ], 404);
        }
    }
}
