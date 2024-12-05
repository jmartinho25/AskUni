<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class LikeController extends Controller
{
    public function store(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $post->likes()->attach(auth()->id());

        return back()->with('success');
    }

    public function destroy(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $post->likes()->detach(auth()->id());

        return back()->with('success');
    }
}