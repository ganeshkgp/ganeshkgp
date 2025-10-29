<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\ContactMessageReceived;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $messages = ContactMessage::orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $messages
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|min:10|max:2000',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();
        $data['ip_address'] = $request->ip();
        $data['user_agent'] = $request->userAgent();

        $message = ContactMessage::create($data);

        // Send email notification
        try {
            Mail::to('ganeshr848@gmail.com')->send(new ContactMessageReceived($message));
        } catch (\Exception $e) {
            // Log the error but don't fail the response
            \Log::error('Failed to send contact email: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Your message has been sent successfully. I\'ll get back to you soon!',
            'data' => $message
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ContactMessage $message): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $message
        ]);
    }

    /**
     * Mark message as read.
     */
    public function markAsRead(ContactMessage $message): JsonResponse
    {
        $message->markAsRead();

        return response()->json([
            'success' => true,
            'message' => 'Message marked as read',
            'data' => $message
        ]);
    }

    /**
     * Mark message as replied.
     */
    public function markAsReplied(ContactMessage $message): JsonResponse
    {
        $message->markAsReplied();

        return response()->json([
            'success' => true,
            'message' => 'Message marked as replied',
            'data' => $message
        ]);
    }

    /**
     * Archive message.
     */
    public function archive(ContactMessage $message): JsonResponse
    {
        $message->archive();

        return response()->json([
            'success' => true,
            'message' => 'Message archived',
            'data' => $message
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContactMessage $message): JsonResponse
    {
        $message->delete();

        return response()->json([
            'success' => true,
            'message' => 'Message deleted successfully'
        ]);
    }
}
