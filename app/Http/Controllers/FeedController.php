<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Tag;
use App\Models\User;

class FeedController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Buscar as tags que o usuário segue
        $followedTags = $user->tags()->pluck('tags.id');

        // Buscar perguntas que pertencem às tags que o usuário segue
        $relevantQuestions = Question::whereHas('tags', function ($query) use ($followedTags) {
            $query->whereIn('tags.id', $followedTags);
        })->with('post')->paginate(10);

        // Buscar perguntas trending (exemplo: as mais recentes)
        $trendingQuestions = Question::with('post')->orderBy('posts_id', 'desc')->take(10)->get();

        return view('pages.feed', compact('relevantQuestions', 'trendingQuestions'));
    }
}