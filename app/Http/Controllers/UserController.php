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

        return view('pages.user.user', compact('user', 'posts', 'answers','questions'));
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
        return view('pages.user.editUser', ['user' => $user]);
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
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('admin.dashboard')->with('error', 'User not found.');
        }

        $user->delete();

        return redirect()->route('admin.dashboard')->with('success', 'User deleted successfully.');
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
