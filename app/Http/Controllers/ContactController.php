<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mime\Part\TextPart;

class ContactController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'message' => 'required|string',
                'phone' => 'required|string|max:15',
            ]);

            $data = [
                'name' => $request->name,
                'message' => $request->message,
                'phone' => $request->phone,
                'user' => $request->user,
            ];

            // Update the email body to use a formatted HTML template
            Mail::send([], [], function ($message) use ($data) {
                $message->to('birrletej12@gmail.com')
                    ->from('appform@ph.beamlak.dev', 'App Feedback Form')
                    ->subject('User Feedback')
                    ->setBody(new TextPart(
                        '<html><body>'.
                        '<h2>'.$data['user'].' Feedback</h2>'.
                        '<p><strong>Name:</strong> '.htmlspecialchars($data['name']).'</p>'.
                        '<p><strong>Phone:</strong> '.htmlspecialchars($data['phone']).'</p>'.
                        '<p><strong>Message:</strong></p>'.
                        '<p>'.nl2br(htmlspecialchars($data['message'])).'</p>'.
                        '<p><strong>Timestamp:</strong> '.date('Y-m-d H:i:s').'</p>'.
                        '</body></html>',
                        'utf-8',
                        'html'
                    ));
            });

            return redirect()->back()->with('message', ['name' => 'Feedback registered successfully.', 'type' => 'success']);
        } catch (Exception $e) {
            Log::error('Error in ContactController: '.$e->getMessage(), ['exception' => $e]);

            return redirect()->back()->with('message', ['name' => 'An error occurred while processing your feedback. '.$e->getMessage(), 'type' => 'error']);
        }
    }
}
