<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SupportQuestion;

class SupportController extends Controller
{
    public function index()
    {
        $supportQuestions = SupportQuestion::with(['user', 'answers.user'])->paginate(10);
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
        $supportQuestions = SupportQuestion::where('users_id', $user->id)->with('answers.user')->paginate(10);
        return view('pages.user.my-support-questions', compact('supportQuestions'));
    }

    public function create()
    {
        return view('pages.user.create-support-question');
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $supportQuestion = new SupportQuestion();
        $supportQuestion->users_id = auth()->id();
        $supportQuestion->content = $request->input('content');
        $supportQuestion->date = now();
        $supportQuestion->solved = false;
        $supportQuestion->save();

        return redirect()->route('my.support.questions')->with('success', 'Support question created successfully.');
    }
}