<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class DislikeController extends Controller
{
    public function store(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $post->dislikes()->attach(auth()->id());

        return back()->with('success');
    }

    public function destroy(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $post->dislikes()->detach(auth()->id());

        return back()->with('success');
    }
}