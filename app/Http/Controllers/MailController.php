<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\MailModel;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Exception;
use Carbon\Carbon;
use App\Models\User;

class MailController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('emails.form');
    }

    public function send(Request $request)
    {
        $missingVariables = [];
        $requiredEnvVariables = [
            'MAIL_MAILER',
            'MAIL_HOST',
            'MAIL_PORT',
            'MAIL_USERNAME',
            'MAIL_PASSWORD',
            'MAIL_ENCRYPTION',
            'MAIL_FROM_ADDRESS',
            'MAIL_FROM_NAME',
        ];

        foreach ($requiredEnvVariables as $envVar) {
            if (empty(env($envVar))) {
                $missingVariables[] = $envVar;
            }
        }

        if (empty($missingVariables)) {
            $request->validate(['email' => 'required|email']);

            $user = User::where('email', $request->email)->first();

            if ($user) {
                $token = Str::random(60);
                $user->reset_token = $token;
                $user->reset_token_created_at = now();
                $user->save();

                $mailData = [
                    'username' => $user->name,
                    'email' => $request->email,
                    'resetLink' => url('/password/reset/' . $token . '?email=' . urlencode($request->email))
                ];

                try {
                    Mail::to($request->email)->send(new MailModel($mailData));
                    $status = 'Success!';
                    $message = 'A recuperation email has been sent to ' . $request->email;
                } catch (Exception $e) {
                    $status = 'Error!';
                    $message = 'An error occurred during the email sending process to ' . $request->email;
                    Log::error('Email sending error: ' . $e->getMessage());
                }
            } else {
                $status = 'Error!';
                $message = 'No user found with that email address.';
            }
        } else {
            $status = 'Error!';
            $message = 'The SMTP server cannot be reached due to missing environment variables: ' . implode(', ', $missingVariables);
        }

        $request->session()->flash('status', $status);
        $request->session()->flash('message', $message);
        $request->session()->flash('details', $missingVariables);
        return redirect()->route('emails.feedback');
    }

    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);
    
        $user = User::where('email', $request->email)
                    ->where('reset_token', $request->token)
                    ->first();
    
        if (!$user || Carbon::parse($user->reset_token_created_at)->addMinutes(30) < now()) {
            return back()->withErrors(['email' => 'Invalid token or email, or the token has expired.']);
        }
    
        $user->forceFill([
            'password' => Hash::make($request->password),
            'reset_token' => null,
            'reset_token_created_at' => null,
            'remember_token' => Str::random(60),
        ])->save();
    
        Auth::login($user);
    
        return redirect('/feed')->with('success', 'Your password has been reset successfully. You\'re now logged in!');
    }
}