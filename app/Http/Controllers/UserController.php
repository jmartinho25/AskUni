<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
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
            return redirect()->back(); 
        }
        
        $posts = $user->posts()->get();
        
        $answers = $user->answers()->get();

        $questions = $user->questions()->get();
        
        //$comments = $user->comments()->get();
        
        //$tags = $user->tags()->get();

        return view('pages.user', compact('user', 'posts', 'answers','questions'));
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
    public function editUser()
    {
        $user = Auth::user();
        $this->authorize('editUser', $user);
        return view('pages.editUser', ['user' => $user]);
    }

    public function updateUser(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username,' . $user->id,
            'email' => 'required|email|max:100|unique:users,email,' . $user->id . '|regex:/^[^@]+@fe\.up\.pt$/',
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
            $file = $request->file('photo');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('profilePictures'), $filename);
            $user->photo = 'profilePictures/' . $filename;
        }

        $user->save();

        return redirect()->route('profile', $user->id)->with('success', 'Profile updated successfully.');
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', User::class);

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }

    public function score($id)
    {
        $user = User::find($id);
        return response()->json(['score' => $user->score]);
    }

    public function getUserQuestionsAPI($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }
        $questions = $user->questions()->get();

        $result = $questions->map(function ($question) {
            return [
                'id' => $question->question->posts_id,
                'title' => $question->question->title,
                'content' => $question->content,
                'date' => $question->date,
            ];
        });

        return response()->json($result);
    }

    public function getNotificationsAPI()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }
        $notifications = $user->notifications()->where('read_status', false)->get();

        $result = $notifications->map(function ($notification) {
            return [
                'id' => $notification->id,
                'content' => $notification->content,
                'date' => $notification->date,
            ];
        });

        return response()->json($result);
    }
}
