<?php

namespace App\Http\Controllers;

use App\Models\AppealForUnblock;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AppealForUnblockController extends Controller
{
    public function index()
    {
        // Retorna a visão da página de "Appeal for Unblock"
        return view('pages.appeal_for_unblock.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'content' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return redirect()->back()->withErrors(['email' => 'Email or password is incorrect.']);
        }

        if (!$user->is_blocked) {
            return redirect()->route('feed.index')->with('error', 'You are not blocked, no appeal needed.'); // Redireciona para o feed
        }

        $appeal = AppealForUnblock::where('users_id', $user->id)->first();

        if ($appeal) {
            $appeal->content = $request->content;
            $appeal->save();
        } else {
            AppealForUnblock::create([
                'content' => $request->content,
                'users_id' => $user->id,
            ]);
        }

            return redirect()->route('appealForUnblock.index')->with('success', 'Appeal submitted successfully.');
    }
}