<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Post;
use App\Models\Tag;
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
        $this->authorize('create', Question::class);

        $allTags = Tag::all();

        return view('pages.questions.create', compact('allTags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Question::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'tags' => 'array|max:5',
            'tags.*' => 'integer|exists:tags,id',
        ]);

        $post = Post::create([
            'content' => $validated['content'],
            'date' => now(), 
            'users_id' => auth()->user()->id, 
        ]);

        $question = Question::create([
            'posts_id' => $post->id,
            'title' => $validated['title'],
        ]);

        $question->tags()->sync($request->input('tags', []));
    
        return redirect()->route('questions.index')->with('success', 'Question created successfully');
    }

    

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $question = Question::with([
            'post.user' => function ($query) {
                $query->withTrashed(); 
            },
            'answers.comments',
            'comments',
            'tags',
        ])->find($id);

        if (!$question) {
            return redirect()->route('home')->with('error', 'Question does not exist.');
        }

        return view('pages.questions.show', compact('question'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Question $question)
    {
        $this->authorize('update', $question);

        $allTags = Tag::all();

        return view('pages.editQuestion', compact('question', 'allTags'));
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
            'tags' => 'array|max:5',
            'tags.*' => 'integer|exists:tags,id',
        ]);

        // Update the question in the database
        $question->update([
            'title' => $request->title,
        ]);

        $question->post->update([
            'content' => $request->input('content'),
        ]);

        $question->tags()->sync($request->input('tags', []));
    
        return redirect()->route('questions.show', $question->posts_id)->with('success', 'Question updated successfully');
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

    public function search(Request $request)
    {
        $query = $this->sanitizeQuery($request->input('query'));
        $exactMatch = $request->input('exact_match');
        $order = $request->input('order', 'relevance');
        $tags = $request->input('tags', []);
        $offset = 10;
    
        $results = $this->performSearch($query, $exactMatch, $offset, $order, $tags);
    
        $allTags = Tag::all();
    
        return view('pages.search', [
            'query' => $query,
            'results' => $results,
            'tags' => $tags,
            'allTags' => $allTags,
        ]);
    }
    
    public function searchAPI(Request $request)
    {
        $query = $this->sanitizeQuery($request->input('query'));
        $exactMatch = $request->input('exact_match');
        $order = $request->input('order', 'relevance');
        $tags = $request->input('tags', []);
        $offset = 10;
    
        $results = $this->performSearch($query, $exactMatch, $offset, $order, $tags);
    
        $results->getCollection()->transform(function ($question) {
            return [
                'posts_id' => $question->posts_id,
                'title' => $question->title,
                'date' => $question->post->date,
                'user_id' => $question->post->user ? $question->post->user->id : null,
                'username' => $question->post->user ? $question->post->user->username : 'Deleted User',
            ];
        });
    
        return response()->json([
            'results' => $results->items(),
            'pagination' => (string) $results->appends(['query' => $query, 'exact_match' => $exactMatch, 'order' => $order, 'tags' => $tags])->links()
        ]);
    }


    protected function performSearch($query, $exactMatch, $offset, $order = null, $tags = [])
    {
        $queryBuilder = Question::with('post.user')
            ->join('posts', 'questions.posts_id', '=', 'posts.id')
            ->leftJoin('posts_tags', 'posts.id', '=', 'posts_tags.posts_id')
            ->leftJoin('tags', 'posts_tags.tags_id', '=', 'tags.id')
            ->select('questions.*', 'posts.date');
    
        if ($exactMatch) {
            $queryBuilder->where('questions.title', 'ILIKE', "%{$query}%");
        } else {
            $queryBuilder->selectRaw("
                    questions.*, 
                    CASE 
                        WHEN questions.title ILIKE ? THEN 1 
                        ELSE 2 
                    END AS rank_order,
                    ts_rank(questions.tsvectors, to_tsquery('english', ?)) AS rank", ["%{$query}%", $query])
                ->where(function ($q) use ($query) {
                    $q->whereRaw("questions.tsvectors @@ to_tsquery('english', ?)", [$query])
                      ->orWhere('questions.title', 'ILIKE', "%{$query}%");
                });
        }
    
        if (!empty($tags)) {
            $queryBuilder->whereIn('posts_tags.tags_id', $tags)
                ->groupBy('questions.posts_id', 'posts.id')
                ->havingRaw('COUNT(DISTINCT posts_tags.tags_id) = ?', [count($tags)]);
        }
    
        switch ($order) {
            case 'date_desc':
                $queryBuilder->orderBy('posts.date', 'DESC');
                break;
            case 'date_asc':
                $queryBuilder->orderBy('posts.date', 'ASC');
                break;
            default:
                if (!$exactMatch){
                    $queryBuilder->orderBy('rank_order') // prioritize exact matches
                                ->orderBy('rank', 'DESC'); // order by ts_rank within each group
                }
                break;
        }
    
        return $queryBuilder->paginate($offset);
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

