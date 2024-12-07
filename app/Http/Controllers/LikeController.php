<?php
namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function store($id)
    {
        $post = Post::findOrFail($id);
        $user = auth()->user();

        if ($post->isLikedBy($user)) {
            $post->likes()->where('users_id', $user->id)->delete();
        } else {
            $post->likes()->insert(['users_id' => $user->id, 'posts_id' => $post->id]);

            if ($post->isDislikedBy($user)) {
                $post->dislikes()->where('users_id', $user->id)->delete();
            }
        }

        return response()->json([
            'success' => true,
            'likesCount' => $post->likesCount(),
            'dislikesCount' => $post->dislikesCount(),
        ]);
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $user = auth()->user();

        if ($post->isLikedBy($user)) {
            $post->likes()->where('users_id', $user->id)->delete();
        }

        return response()->json([
            'success' => true,
            'likesCount' => $post->likesCount(), 
            'dislikesCount' => $post->dislikesCount(),
        ]);
    }
}