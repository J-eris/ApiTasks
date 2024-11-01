<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttachmentRequest;
use App\Models\Attachment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AttachmentController extends Controller
{

    public function index():JsonResponse
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

    public function store(AttachmentRequest $request)
    {
        try {
            $attachment = Attachment::create($request->all());

            return response()->json([
                'message' => 'Attachment created successfully',
                'attachment' => $attachment,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create attachment',
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
