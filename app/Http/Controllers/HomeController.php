<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Question;

class HomeController extends Controller
{
    public function index()
    {
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

        $allQuestions = Question::orderBy('posts_id', 'desc')->paginate(10);

        return view('pages.home', compact('trendingQuestions','allQuestions'));
    }

}
