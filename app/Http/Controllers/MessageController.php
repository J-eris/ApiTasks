<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageRequest;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $messages = Message::with('sender', 'receiver')->paginate(10);
        return response()->json([
            'message' => 'Messages retrieved successfully',
            'messages' => $messages
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
    public function store(MessageRequest $request)
    {
        try {
            $validatedData = $request->validated();

            $message = Message::create($validatedData);

            return response()->json([
                'message' => 'Message created successfully',
                'message_data' => $message,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create message',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $message = Message::with('sender', 'receiver')->find($id);

        if ($message) {
            return response()->json([
                'message' => 'Message retrieved successfully',
                'message' => $message,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Message not found',
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function edit(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function update(MessageRequest $request, $id)
    {
        $message = Message::find($id);

        if ($message) {
            $validatedData = $request->validated();

            $message->update($validatedData);
            return response()->json([
                'message' => 'Message updated successfully',
                'message' => $message,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Message not found',
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $message = Message::find($id);

        if ($message) {
            $message->delete();
            return response()->json([
                'message' => 'Message deleted successfully',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Message not found',
            ], 404);
        }
    }
}
