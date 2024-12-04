<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Question;
use App\Models\Tag;
use App\Models\User;

class FeedController extends Controller
{
    public function index()
    {
        
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login');
        }
        $followedTags = $user->tags()->pluck('tags.id');

        $relevantQuestions = Question::whereHas('tags', function ($query) use ($followedTags) {
            $query->whereIn('tags.id', $followedTags);
        })
        ->with(['post', 'user' => function ($query) {
            $query->withTrashed(); 
        }, 'tags'])
        ->paginate(10);

        
        $trendingQuestions = Question::with(['post', 'user' => function ($query) {
            $query->withTrashed(); 
        }])
        ->leftJoin('posts', 'questions.posts_id', '=', 'posts.id')
        ->leftJoin('users_likes_posts', 'posts.id', '=', 'users_likes_posts.posts_id')
        ->select('questions.*', \DB::raw('COUNT(users_likes_posts.users_id) as likes_count'))
        ->groupBy('questions.posts_id')
        ->orderBy('likes_count', 'desc')
        ->take(10)
        ->get();

        return view('pages.feed', compact('relevantQuestions', 'trendingQuestions'));
    }
}
