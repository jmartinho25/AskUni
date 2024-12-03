<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Question;
use App\Models\Answer;

class CommentController extends Controller
{
    public function create($type, $id)
    {
        if ($type === 'question') {
            $parent = Question::findOrFail($id);
        } elseif ($type === 'answer') {
            $parent = Answer::findOrFail($id);
        } else {
            abort(404);
        }

        return view('pages/comments.create', compact('parent', 'type'));
    }

    public function store(Request $request, $type, $id)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        if ($type === 'question') {
            $parent = Question::findOrFail($id);
        } elseif ($type === 'answer') {
            $parent = Answer::findOrFail($id);
        } else {
            abort(404);
        }

        $parent->comments()->create([
            'content' => $request->content,
            'users_id' => auth()->id(),
            'date' => now(),
            'posts_id' => $parent->posts_id,
        ]);

        return redirect()->route($type . 's.show', $parent->posts_id)->with('success', 'Comment added successfully!');
    }

}
