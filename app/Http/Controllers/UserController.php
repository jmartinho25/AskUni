<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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

        // Retornar a view com os dados necessários
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
        $this->authorize('editUser', Auth::user());
        return view('pages.editUser', ['user' => Auth::user()]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    // Handle user edit
    public function edit(Request $request, User $user)
    {
        $this->authorize('edit', User::class);

        // Validate the forms entries
        $request->validate([
            'name' => 'max:255',
            'username' => 'unique:users,username,' . $user->id . '|max:255',
            'email' => 'email|unique:users,email,' . $user->id . '|max:255',
            'description' => 'max:255',
            'score' => 'nullable|integer|min:0|max:100' 
        ]);

        if ($request->password) {
            $request->validate([
                'password' => 'string|min:6|confirmed',
            ]);
            $user->password = bcrypt($request->password);
        }

        
        if ($request->file('photo')) {
            if (!in_array(pathinfo($request->file('photo')->getClientOriginalName(), PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png'])) {
                return redirect('user/edit')->with('error', 'File not supported');
            }
            // Call the controller to update the photo provided
            ImageController::update($user->id, 'profile', $request);
        }

        
        $user->name = $request->input('name');
        $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->description = $request->input('description');
        $user->score = $request->input('score') ? $request->input('score') : $user->score; 
        $user->is_blocked = $request->input('is_blocked') ? true : false; 
        $user->save()   ;

        // Redirect to user's page
        return redirect('user/' . $user->id);
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
}
