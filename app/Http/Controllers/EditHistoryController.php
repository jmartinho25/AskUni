<?php

namespace App\Http\Controllers;

use App\Models\EditHistory;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;

class EditHistoryController extends Controller
{
    public function getEditHistoryAPI($id)
    {
        $editHistory = EditHistory::find($id);

        if (!$editHistory) {
            return response()->json(['error' => 'Edit history not found'], 404);
        }

        if ($editHistory->posts_id) {
            $model = Post::findOrFail($editHistory->posts_id);
        } elseif ($editHistory->comments_id) {
            $model = Comment::findOrFail($editHistory->comments_id);
        } else {
            return response()->json(['error' => 'Invalid edit history record'], 400);
        }

        $user = auth()->user();
        if ($user->id !== $model->users_id && !$user->hasRole('admin')) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        return response()->json($editHistory);
    }

}