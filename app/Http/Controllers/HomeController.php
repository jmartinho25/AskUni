<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Question;

class HomeController extends Controller
{
    public function index()
    {
        $trendingQuestions = Question::orderBy('posts_id', 'desc')->take(10)->get();

        $allQuestions = Question::orderBy('posts_id', 'desc')->paginate(10);

        return view('pages.home', compact('trendingQuestions','allQuestions'));
    }

}
