<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttachmentRequest;
use App\Models\Attachment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{

    public function index(): JsonResponse
    {
        $attachments = Attachment::with('auction')->paginate(10);

        if ($attachments->isEmpty()) {
            return response()->json([
                'message' => 'No attachments found',
            ], 404);
        }

        return response()->json([
            'message' => 'Attachments retrieved successfully',
            'attachments' => $attachments
        ], 200);
    }

    public function store(Request $request)
    {
        // Validar la solicitud
        $request->validate([
            'auction_id' => 'required|exists:auctions,id',
            'file' => 'required_if:file_type,image|file|mimes:jpg,jpeg,png,gif|max:20480', // 20MB
            'file_type' => 'required|in:image,video,link',
            'file_path' => 'required_if:file_type,link|url|nullable',
        ]);

        try {
            if ($request->file_type === 'image') {
                if ($request->hasFile('file')) {
                    $file = $request->file('file');
                    $path = $file->store('attachments', 'public');
                    $filePath = Storage::url($path);
                } else {
                    return response()->json(['error' => 'Archivo inválido'], 400);
                }
            } else {
                $filePath = $request->input('file_path');
            }

            $attachment = Attachment::create([
                'file_type' => $request->file_type,
                'file_path' => $filePath,
                'auction_id' => $request->auction_id,
            ]);

            return response()->json([
                'message' => 'Adjunto creado exitosamente',
                'attachment' => $attachment,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear el adjunto',
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    public function show($id)
    {
        $attachment = Attachment::with('auction')->find($id);

        if ($attachment) {
            return response()->json([
                'message' => 'Attachment retrieved successfully',
                'attachment' => $attachment,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Attachment not found',
            ], 404);
        }
    }

    public function update(AttachmentRequest $request, $id)
    {
        $attachment = Attachment::find($id);

        if ($attachment) {
            $attachment->update($request->all());

            return response()->json([
                'message' => 'Attachment updated successfully',
                'attachment' => $attachment,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Attachment not found',
            ], 404);
        }
    }

    public function destroy($id)
    {
        $attachment = Attachment::find($id);

        if ($attachment) {
            $attachment->delete();
            return response()->json([
                'message' => 'Attachment deleted successfully',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Attachment not found',
            ], 404);
        }
    }
}
