<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageRequest;
use App\Models\Message;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    // Obtener todas las conversaciones para un usuario específico
    public function getConversations($userId)
    {
        try {
            // Obtener la última mensaje de cada conversación
            $conversations = Message::select(
                DB::raw('CASE WHEN sender_id = ' . $userId . ' THEN receiver_id ELSE sender_id END as other_user_id'),
                DB::raw('MAX(created_at) as last_message_time')
            )
                ->where('sender_id', $userId)
                ->orWhere('receiver_id', $userId)
                ->groupBy('other_user_id')
                ->orderBy('last_message_time', 'desc')
                ->get();

            // Procesar las conversaciones para obtener información del otro usuario y el último mensaje
            $formattedConversations = $conversations->map(function ($conv) use ($userId) {
                $otherUserId = $conv->other_user_id;
                $lastMessage = Message::where(function ($query) use ($userId, $otherUserId) {
                    $query->where('sender_id', $userId)->where('receiver_id', $otherUserId);
                })->orWhere(function ($query) use ($userId, $otherUserId) {
                    $query->where('sender_id', $otherUserId)->where('receiver_id', $userId);
                })->orderBy('created_at', 'desc')->first();

                return [
                    'conversation_id' => $otherUserId,
                    'user' => [
                        'id' => $otherUserId,
                        'name' => $lastMessage->receiver->id === $otherUserId ? $lastMessage->receiver->name : $lastMessage->sender->name,
                        'avatar' => $lastMessage->receiver->avatar ?? $lastMessage->sender->avatar, // Asegúrate de tener el campo 'avatar'
                    ],
                    'last_message' => $lastMessage->message,
                    'last_message_time' => $lastMessage->created_at,
                ];
            });

            return response()->json([
                'message' => 'Conversaciones recuperadas exitosamente',
                'conversations' => $formattedConversations,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al recuperar conversaciones',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    // Obtener todos los mensajes entre dos usuarios
    public function getMessagesBetweenUsers($userId, $otherUserId)
    {
        try {
            $messages = Message::where(function ($query) use ($userId, $otherUserId) {
                $query->where('sender_id', $userId)->where('receiver_id', $otherUserId);
            })->orWhere(function ($query) use ($userId, $otherUserId) {
                $query->where('sender_id', $otherUserId)->where('receiver_id', $userId);
            })->orderBy('created_at', 'asc')
                ->get();

            return response()->json([
                'message' => 'Mensajes recuperados exitosamente',
                'messages' => $messages
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al recuperar mensajes',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    public function index()
    {
        $messages = Message::with('sender', 'receiver')->paginate(10);
        return response()->json([
            'message' => 'Messages retrieved successfully',
            'messages' => $messages
        ], 200);
    }


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
