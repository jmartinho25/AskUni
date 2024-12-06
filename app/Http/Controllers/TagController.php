<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::all()->groupBy('category');
        return view('pages.tags.index', compact('tags'));
    }

    public function follow(Request $request, $name)
    {
        $tag = Tag::where('name', $name)->firstOrFail();
        $user = Auth::user();
    
        $this->authorize('follow', $tag);
    
        if ($user->tags->contains($tag->id)) {
            $user->tags()->detach($tag->id);
            $following = false;
        } else {
            $user->tags()->attach($tag->id);
            $following = true;
        }
    
        return response()->json(['following' => $following]);
    }

    public function show(Request $request, $name)
    {
        $tag = Tag::where('name', $name)->firstOrFail();
        $sort = $request->query('sort', 'newest');
        $questions = $this->fetchQuestions($tag, $sort);
        $isFollowing = Auth::check() && Auth::user()->tags->contains($tag->id);
    
        return view('pages.tags.show', compact('tag', 'questions', 'sort', 'isFollowing'));
    }

    public function getQuestionsAPI(Request $request, $name)
    {
        $tag = Tag::where('name', $name)->firstOrFail();
        $sort = $request->query('sort', 'newest');
        $questions = $this->fetchQuestions($tag, $sort);

        return response()->json([
            'data' => $questions->items(),
            'links' => (string) $questions->links(),
        ]);
    }

    private function fetchQuestions($tag, $sort, $offset = 10)
    {
        if ($sort == 'popularity') {
            return $tag->questions()
                ->with(['post.user'])
                ->select('questions.*')
                ->selectSub(function ($query) {
                    $query->from('posts')
                        ->join('users_likes_posts', 'users_likes_posts.posts_id', '=', 'posts.id')
                        ->whereColumn('questions.posts_id', 'posts.id')
                        ->selectRaw('count(*)');
                }, 'likes_count')
                ->selectSub(function ($query) {
                    $query->from('posts')
                        ->join('users_dislikes_posts', 'users_dislikes_posts.posts_id', '=', 'posts.id')
                        ->whereColumn('questions.posts_id', 'posts.id')
                        ->selectRaw('count(*)');
                }, 'dislikes_count')
                ->orderByRaw('(
                    (select count(*) 
                    from posts 
                    join users_likes_posts on users_likes_posts.posts_id = posts.id 
                    where questions.posts_id = posts.id) 
                    - 
                    (select count(*) 
                    from posts 
                    join users_dislikes_posts on users_dislikes_posts.posts_id = posts.id 
                    where questions.posts_id = posts.id)
                ) DESC')
                ->paginate($offset);
        }
        else {
            return $tag->questions()
                ->join('posts', 'questions.posts_id', '=', 'posts.id')
                ->with('post.user')
                ->orderBy('posts.date', 'DESC')
                ->select('questions.*')
                ->paginate($offset);
        }
    }


    public function manage()
    {
        $this->authorize('manage', Tag::class);
        $tags = Tag::all();
        return view('pages.tags.manage', compact('tags'));
    }

    public function store(Request $request)
    {
        $this->authorize('manage', Tag::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:tags,name',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Tag::create($validated);

        return redirect()->route('tags.manage')->with('success', 'Tag created successfully.');
    }

    public function update(Request $request, $id)
    {
        $tag = Tag::findOrFail($id);
        $this->authorize('manage', $tag);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:tags,name,' . $tag->id,
            'category' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);

        $tag->update($validated);

        return redirect()->route('tags.manage')->with('success', 'Tag updated successfully.');
    }

    public function destroy($id)
    {
        $tag = Tag::findOrFail($id);
        $this->authorize('manage', $tag);
        $tag->delete();

        return redirect()->route('tags.manage')->with('success', 'Tag deleted successfully.');
    }
}