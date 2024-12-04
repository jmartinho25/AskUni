<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::all();
        return view('pages.tags.index', compact('tags'));
    }

    public function show(Request $request, $name)
    {
        $tag = Tag::where('name', $name)->firstOrFail();
        $sort = $request->query('sort', 'newest');
        $offset = 10;
    
        if ($sort == 'popularity') {
            $questions = $tag->questions()
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
            $questions = $tag->questions()
                ->join('posts', 'questions.posts_id', '=', 'posts.id')
                ->with('post.user')
                ->orderBy('posts.date', 'DESC')
                ->select('questions.*')
                ->paginate($offset);
        }
    
        return view('pages.tags.show', compact('tag', 'questions', 'sort'));
    }
}
