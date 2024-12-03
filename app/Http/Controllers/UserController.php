<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Notification;
use App\Models\QuestionNotification;
use App\Models\AnswerNotification;
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
            return redirect()->route('home')->with('error', 'User not found.'); 
        }
        
        $posts = $user->posts()->get();
        
        $answers = $user->answers()->get();

        $questions = $user->questions()->get();

        $badges = $user->badges()->get();
        
        //$comments = $user->comments()->get();
        
        //$tags = $user->tags()->get();

        return view('pages.user.user', compact('user', 'posts', 'answers','questions', 'badges'));
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
         
        if ($user) {
            $user->deleted_at = now();
            $user->save();
            return redirect()->route('admin.dashboard')->with('success', 'User deleted successfully.');
        }

        return back()->with('error', 'User not found.');
    }


    public function restore($id)
    {
        $user = User::whereNotNull('deleted_at')->find($id); 

        if ($user) {
            $user->deleted_at = null; 
            $user->save();
            return redirect()->route('admin.dashboard')->with('success', 'User restored successfully.');
        }

        return back()->with('error', 'User not found.');
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


  
    public function index(Request $request)
    {
        $query = $request->input('query'); // Obtém o valor da pesquisa da URL

        // Realiza a busca com paginação
        $users = $this->performSearch($query);

        // Retorna para a view com os resultados
        return view('admin.dashboard', compact('users', 'query'));
    }

    protected function performSearch($query)
    {
        // Se houver um termo de pesquisa, filtra os usuários
        if ($query) {
            return User::where('name', 'ILIKE', "%{$query}%")
                ->orWhere('email', 'ILIKE', "%{$query}%")
                ->paginate(10);  // Paginação de 10 resultados por página
        }

        // Se não houver pesquisa, retorna todos os usuários com paginação
        return User::paginate(10);
    }


    public function search(Request $request)
    {
        $query = $request->input('query');
    
        $users = User::where('name', 'like', '%' . $query . '%')
                     ->orWhere('email', 'like', '%' . $query . '%')
                     ->paginate(10);
    
        return view('admin.dashboard', compact('users', 'query'));
    }
    


    






}
