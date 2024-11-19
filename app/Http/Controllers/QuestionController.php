<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Post;
use Illuminate\Http\Request;

class QuestionController extends Controller
{

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Return a view for creating a new question
        return view('pages/questions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate incoming data
        $request->validate([
            'content' => 'required|string|max:255', // Post content
            'date' => 'required|date', // Post date
            'title' => 'required|string|max:255', // Question title
        ]);

        // Create the Post
        $post = Post::create([
            'content' => $request->content, // Post content
            'date' => $request->date, // Post date
            'users_id' => auth()->user()->id, // Assuming the authenticated user is the owner of the post
        ]);

        // Create the Question associated with the Post
        $question = Question::create([
            'posts_id' => $post->id, // Relationship with the Post
            'title' => $request->title, // Question title
        ]);

        // Redirect to the questions index page with a success message
        return redirect()->route('questions.index')->with('success', 'Question created successfully');
    }

    

    /**
     * Display the specified resource.
     */
    public function show(Question $question)
    {
        // Return a view to display the details of a specific question
        return view('questions.show', compact('question'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Question $question)
    {
        // Return a view to edit the specified question
        return view('questions.edit', compact('question'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Question $question)
    {
        // Validate the request data
        $request->validate([
            'posts_id' => 'required|exists:posts,id',
            'title' => 'required|string|max:255',
            'answers_id' => 'nullable|exists:answers,id',
        ]);

        // Update the question in the database
        $question->update([
            'posts_id' => $request->posts_id,
            'title' => $request->title,
            'answers_id' => $request->answers_id,
        ]);

        // Redirect to the questions index page with a success message
        return redirect()->route('questions.index')->with('success', 'Question updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Question $question)
    {
        // Delete the question from the database
        $question->delete();

        // Redirect to the questions index page with a success message
        return redirect()->route('questions.index')->with('success', 'Question deleted successfully');
    }

    /**
     * Get the question details via API.
     */
    public function getQuestionAPI($id)
    {
        $question = Question::with('post.user')->find($id);

        if (!$question) {
            return response()->json(['error' => 'Question not found.'], 404);
        }

        $post = $question->post;
        $user = $post->user;

        return response()->json([
            'id' => $question->posts_id,
            'title' => $question->title,
            'content' => $post ? $post->content : null,
            'date' => $post ? $post->date : null,
            'user_id' => $user ? $user->id : null,
            'name' => $user ? $user->name : null,
        ]);
    }

    public function deleteQuestionAPI($id)
    {
        $question = Question::find($id);

        if (!$question) {
            return response()->json(['error' => 'Question not found.'], 404);
        }

        $question->delete();

        return response()->json(['message' => 'Question deleted successfully.']);
    }

}

