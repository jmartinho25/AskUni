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
}