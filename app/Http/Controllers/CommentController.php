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
        if ($type === 'question') {
            return redirect()->route('questions.show', $parent->posts_id)->with('success', 'Comment added successfully!');
        }elseif ($type === 'answer') {
            return redirect()->route('questions.show', $parent->questions_id)->with('success', 'Comment added successfully!');
        }else {
            abort(404);
        }
    }

    public function edit(Comment $comment)
    {
        return view('pages/comments.edit', compact('comment'));
    }

    public function update(Request $request, Comment $comment)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $comment->update([
            'content' => $request->content,
        ]);

        if($comment->question!=null){
            return redirect()->route('questions.show', $comment->question->posts_id)->with('success', 'Comment updated successfully');
        }elseif ($comment->answer!=null) {
            return redirect()->route('questions.show', $comment->answer->questions_id)->with('success', 'Comment updated successfully');
        }
        else{
            abort(404);
        }
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        if ($comment->question != null) {
            return redirect()->route('questions.show', $comment->question->posts_id)->with('success', 'Comment deleted successfully');
        } elseif ($comment->answer != null) {
            return redirect()->route('questions.show', $comment->answer->questions_id)->with('success', 'Comment deleted successfully');
        } else {
            abort(404);
        }
    }   

}
