<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Question;

class HomeController extends Controller
{
    public function index()
    {
        // Buscar as perguntas mais populares diretamente do banco de dados
        $trendingQuestions = Question::orderBy('posts_id', 'desc')->take(10)->get();

        return view('pages.home', compact('trendingQuestions'));
    }

    public function topQuestions()
    {
        $trendingQuestions = Question::orderBy('posts_id', 'desc')->take(10)->get();

        return response()->json($trendingQuestions);
    }
}
