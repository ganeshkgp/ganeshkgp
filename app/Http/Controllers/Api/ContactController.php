<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\NewContactMessage;
use App\Models\ContactMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $contactMessage = ContactMessage::create($validated);

        $notificationEmail = config('app.notification_email');
        if ($notificationEmail) {
            Mail::to($notificationEmail)->send(new NewContactMessage($contactMessage));
        }

        return response()->json([
            'message' => 'Thank you! Your message has been received. I\'ll get back to you soon.',
        ], 201);
    }
}
