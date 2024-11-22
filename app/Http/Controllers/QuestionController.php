<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Post;
use Illuminate\Http\Request;

class QuestionController extends Controller
{

    public function index()
    {
        $questions = Question::with('post')->paginate(10);

        return view('/pages/questions.index', compact('questions'));
    }
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
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $post = Post::create([
            'content' => $validated['content'],
            'date' => now(), 
            'users_id' => auth()->user()->id, 
        ]);

        Question::create([
            'posts_id' => $post->id,
            'title' => $validated['title'],
        ]);

        return redirect()->route('questions.index')->with('success', 'Question created successfully');
    }

    

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $question = Question::with(['post', 'answers.comments', 'comments'])->findOrFail($id);
        
        return view('pages.questions.show', compact('question'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Question $question)
    {
        $this->authorize('update', $question);

        return view('pages.editQuestion', compact('question'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Question $question)
    {
        $this->authorize('update', $question);

        // Validate the request data
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        // Update the question in the database
        $question->update([
            'title' => $request->title,
        ]);

        $question->post->update([
            'content' => $request->input('content'),
        ]);

        // Redirect to the question page with a success message
        return redirect()->route('questions.show',$question->posts_id)->with('success', 'Question updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Question $question)
    {
        $this->authorize('delete', $question);
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

    public function search(Request $request)
    {
        $query = $this->sanitizeQuery($request->input('query'));
        $exactMatch = $request->input('exact_match');
        $offset = 10;

        if ($exactMatch) {
            // Exact matches only
            $results = Question::where('title', 'ILIKE', "%{$query}%")->paginate($offset);
        } else {
            // Full-text search
            $results = Question::query()
            ->whereRaw("tsvectors @@ to_tsquery('english', ?)", [$query])
            ->orderByRaw("ts_rank(tsvectors, to_tsquery('english', ?)) DESC", [$query])
            ->union(
                Question::query()->where('title', 'ILIKE', "%{$query}%")
            )
            ->paginate($offset);
        }
    
        return view('pages.search', [
            'query' => $query,
            'results' => $results,
        ]);
    }

    protected function sanitizeQuery($query)
    {
        $query = preg_replace('/[^\w\s]/u', '', $query);
        $query = preg_replace('/\s+/', ' ', $query);
        $query = trim($query);
        $query = str_replace(' ', ' & ', $query);
        $query = substr($query, 0, 255);
        return $query;
    }
}

