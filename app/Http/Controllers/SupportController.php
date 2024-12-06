<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupportQuestion;

class SupportController extends Controller
{
    public function index()
    {
        $supportQuestions = SupportQuestion::with(['user', 'answers.user'])->get();
        return view('pages/admin.support.contacts', compact('supportQuestions'));
    }

    public function solve($id)
    {
        $question = SupportQuestion::findOrFail($id);
        $this->authorize('update', $question);

        $question->solved = true;
        $question->save();

        return redirect()->back()->with('success', 'Question marked as solved.');
    }

    public function mySupportQuestions()
    {
        $user = auth()->user();
        $supportQuestions = SupportQuestion::where('users_id', $user->id)->with('answers.user')->get();
        return view('pages.user.my-support-questions', compact('supportQuestions'));
    }
}