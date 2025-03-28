<?php

namespace App\Http\Controllers;

use App\Models\User;

use App\Models\AppealForUnblock;
use Illuminate\Http\Request;
use App\Models\ContentReports;
use App\Models\Question;
use App\Models\Answer;
use App\Models\SupportQuestion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{   
    
    
    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->route('home')->with('error', 'User not found.'); 
        }
        
        $posts = $user->posts()->get();
        
        $answers = $user->answers()->paginate(10, ['*'], 'answers_page');
    
        $questions = $user->questions()->paginate(10, ['*'], 'questions_page');
    
        $comments = $user->comments()->paginate(10, ['*'], 'comments_page');
    
        $badges = $user->badges()->get();
    
        $tags = $user->tags()->get();
    
        $likedQuestions = Question::whereHas('post.likes', function ($query) use ($user) {
            $query->where('users_id', $user->id);
        })->paginate(5, ['*'], 'liked_questions_page');
    
        $likedAnswers = Answer::whereHas('post.likes', function ($query) use ($user) {
            $query->where('users_id', $user->id);
        })->paginate(5, ['*'], 'liked_answers_page');
    
        $dislikedQuestions = Question::whereHas('post.dislikes', function ($query) use ($user) {
            $query->where('users_id', $user->id);
        })->paginate(5, ['*'], 'disliked_questions_page');
    
        $dislikedAnswers = Answer::whereHas('post.dislikes', function ($query) use ($user) {
            $query->where('users_id', $user->id);
        })->paginate(5, ['*'], 'disliked_answers_page');
    
        $supportQuestions = SupportQuestion::where('users_id', $id)->with('answers.user')->get();
        
        return view('pages.user.user', compact('user', 'posts', 'answers', 'questions', 'badges', 'comments', 'likedQuestions', 'likedAnswers', 'dislikedQuestions', 'dislikedAnswers', 'supportQuestions'));
    }

    // Show search page
    public function searchPage()
    {
        $this->authorize('searchPage', User::class);
        return view('pages.search');
    }

    // Show notifications page
    public function notificationsPage()
    {
        $this->authorize('notificationsPage', User::class);
        return view('pages.notifications');
    }

    // Show edit user page
    public function editUser($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('editUser', $user);
        return view('pages.user.editUser', ['user' => $user]);
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:50',
            'username' => 'required|string|max:20|unique:users,username,' . $user->id,
            'email' => 'required|email|max:250|unique:users,email,' . $user->id . '|regex:/^[^@]+@fe\.up\.pt$/',
            'password' => 'nullable|string|min:8|confirmed',
            'description' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $user->name = $request->input('name');
        $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->description = $request->input('description');

        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        if ($request->hasFile('photo')) {
            if ($user->photo && $user->photo !== 'profilePictures/default.png' && file_exists(public_path($user->photo))) {
                unlink(public_path($user->photo));
            }
            $file = $request->file('photo');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('profilePictures'), $filename);
            $user->photo = 'profilePictures/' . $filename;
        }

        $user->save();

        return redirect()->route('profile', $user->id)->with('success', 'Profile updated successfully.');
    }
    

    public function score($id)
    {
        $user = User::find($id);
        return response()->json(['score' => $user->score]);
    }

    public function getNotificationsAPI()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }
        $notifications = $user->notifications()->with(['questionNotification', 'answerNotification'])->where('read_status', false)->get();
        
        $result = $notifications->map(function ($notification) use ($user) {
            $url = null;

            if ($notification->questionNotification) {
                $url = route('questions.show', $notification->questionNotification->questions_id);
            } elseif ($notification->answerNotification) {
                $question_id = $notification->answerNotification->answer->question->posts_id;
                $answer_id = $notification->answerNotification->answers_id;
                $url = route('questions.show', $question_id) . '#answer-' . $answer_id;
            } elseif ($notification->badgeNotification) {
                $url = route('profile', $user->id) . '#badges';
            } elseif ($notification->commentNotification) {
                $comment = $notification->commentNotification->comments_id;
                $post = $notification->commentNotification->comment->post;

                if ($post->question) {
                    $question_id = $post->question->posts_id;
                } elseif ($post->answer) {
                    $question_id = $post->answer->question->posts_id;
                }
                
                $url = route('questions.show', $question_id) . '#comment-' . $comment;
            }
    
            return [
                'id' => $notification->id,
                'content' => $notification->content,
                'date' => $notification->date,
                'url' => $url,
            ];
        });
    
        return response()->json($result);
    }
    
    /**
     * Display unblock requests.
     */
    public function unblockRequests()
    {
        $unblockRequests = AppealForUnblock::with('user')->paginate(10);

        return view('pages.admin.appeals', compact('unblockRequests'));
    }

    /**
     * Display user reports.
     */
    public function userReports($id)
    {
        $user = User::findOrFail($id);
        $reports = ContentReports::whereHas('comment', function($query) use ($id) {
            $query->where('users_id', $id);
        })->orWhereHas('post', function($query) use ($id) {
            $query->where('users_id', $id);
        })->with(['comment', 'post'])->paginate(10);

        return view('pages.admin.reports', compact('user', 'reports'));
    }

   


    /**
     * Unblock a user and delete the appeal.
     */
    public function unblock($id)
    {
        $user = User::findOrFail($id);

        if ($user->is_blocked) {
            $user->is_blocked = false;
            $user->save();

            // Delete the appeal
            AppealForUnblock::where('users_id', $id)->delete();

            return redirect()->route('admin.dashboard')->with('success', 'User unblocked successfully.');
        }

        return redirect()->route('admin.dashboard')->with('error', 'User is not blocked.');
    }

    /**
     * Resolve a report.
     */
    public function resolveReport($id)
    {
        $report = ContentReports::findOrFail($id);
        $report->solved = true;
        $report->save();

        return redirect()->back()->with('success', 'Report marked as resolved.');
    }


    /**
     * Display a list of users.
     */
    public function index(Request $request)
    {
        $query = $request->input('query');
        $users = User::when($query, function ($q) use ($query) {
            return $q->where('username', 'like', "%{$query}%");
        })->withTrashed()->paginate(10);

        return view('pages.admin.dashboard', compact('users', 'query'));
    }

    /**
     * Sanitize the search query.
     */
    protected function sanitizeQuery($query)
    {
        return htmlspecialchars(trim($query));
    }

    /**
     * Restore a deleted user.
     */
    public function restore($id)
    {
        $user = User::withTrashed()->find($id);

        if ($user && $user->trashed()) {
            $user->restore();
            return redirect()->route('admin.dashboard')->with('success', 'User restored successfully.');
        }

        return redirect()->route('admin.dashboard')->with('error', 'User not found or not deleted.');
    }

    /**
     * Delete a user.
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return back()->with('error', 'User not found.');
        }

        if ($user->role === 'admin') {
            return back()->with('error', 'You cannot delete another admin.');
        }

        $user->delete();

        return redirect()->route('admin.dashboard')->with('success', 'User deleted successfully.');
    }
    public function autoDestroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return back()->with('error', 'User not found.');
        }

        if (auth()->id() !== $user->id) {
            return back()->with('error', 'You can only delete your own account.');
        }

        auth()->logout();
        $user->delete();
        
        return redirect()->route('home')->with('success', 'Your account has been deleted successfully.');
    }

    public function showSupportContacts($id)
    {
        $user = User::findOrFail($id);
        $supportQuestions = SupportQuestion::where('users_id', $id)->with('answers.user')->get();
        return view('user', compact('user', 'supportQuestions'));
    }
    


}
