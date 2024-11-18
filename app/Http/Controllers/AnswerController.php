<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use Illuminate\Http\Request;

class AnswerController extends Controller
{

    /**
     * Show the form for creating a new answer.
     */
    public function create()
    {
        // Return a view for creating a new answer
        return view('answers.create');
    }

    /**
     * Store a newly created answer in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'posts_id' => 'required|exists:posts,id', // The post must exist
            'questions_id' => 'nullable|exists:questions,id', // The question must exist if provided
        ]);

        // Create a new answer in the database
        $answer = Answer::create([
            'posts_id' => $request->posts_id,
            'questions_id' => $request->questions_id, // Optional
        ]);

        // Redirect to the answers index page with a success message
        return redirect()->route('answers.index')->with('success', 'Answer created successfully');
    }

    /**
     * Display the specified answer.
     */
    public function show(Answer $answer)
    {
        // Return a view to display the details of a specific answer
        return view('answers.show', compact('answer'));
    }

    /**
     * Show the form for editing the specified answer.
     */
    public function edit(Answer $answer)
    {
        // Return a view to edit the specified answer
        return view('answers.edit', compact('answer'));
    }

    /**
     * Update the specified answer in storage.
     */
    public function update(Request $request, Answer $answer)
    {
        // Validate the request data
        $request->validate([
            'posts_id' => 'required|exists:posts,id', // The post must exist
            'questions_id' => 'nullable|exists:questions,id', // The question must exist if provided
        ]);

        // Update the answer in the database
        $answer->update([
            'posts_id' => $request->posts_id,
            'questions_id' => $request->questions_id, // Optional
        ]);

        // Redirect to the answers index page with a success message
        return redirect()->route('answers.index')->with('success', 'Answer updated successfully');
    }

    /**
     * Remove the specified answer from storage.
     */
    public function destroy(Answer $answer)
    {
        // Delete the answer from the database
        $answer->delete();

        // Redirect to the answers index page with a success message
        return redirect()->route('answers.index')->with('success', 'Answer deleted successfully');
    }
}