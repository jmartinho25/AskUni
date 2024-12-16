<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\AppealForUnblock;
use App\Models\ContentReports;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');
        $users = User::query()
            ->when($query, function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                  ->orWhere('email', 'like', '%' . $query . '%');
            })
            ->orderBy('name') 
            ->paginate(10);

        return view('pages.admin.dashboard', compact('users', 'query'));
    }

    public function elevate($id)
    {
        $user = User::findOrFail($id);

        if ($user->hasRole('admin') || $user->deleted_at) {
            return redirect()->route('admin.dashboard')->with('error', 'User cannot be elevated to admin.');
        }

        $adminRole = Role::where('name', 'admin')->first();
        $user->roles()->attach($adminRole);

        return redirect()->route('admin.dashboard')->with('success', 'User has been elevated to admin.');
    }

    public function block($id)
    {
        $user = User::findOrFail($id);

        if ($user->is_blocked) {
            return redirect()->route('admin.dashboard')->with('error', 'User is already blocked.');
        }

        $user->is_blocked = true;
        $user->save();

        return redirect()->route('admin.dashboard')->with('success', 'User has been blocked.');
    }
    public function unblock($id)
    {
        $user = User::findOrFail($id);

        if (!$user->is_blocked) {
            return redirect()->route('admin.dashboard')->with('error', 'User is not blocked.');
        }

        $user->is_blocked = false;
        $user->save();

        $appeal = AppealForUnblock::where('users_id', $user->id)->first();
        if ($appeal) {
            $appeal->delete(); 
        }

        return redirect()->route('admin.dashboard')->with('success', 'User has been unblocked!');
    }
    public function viewReportedContent(Request $request)
    {
        $query = $request->input('query');
        $reportedContent = ContentReports::with('post.user', 'comment.user')
            ->orderBy('solved', 'asc')
            ->paginate(10);

        if ($query) {
            $reportedContent->getCollection()->transform(function($report) use ($query) {
                $user = $report->post ? $report->post->user : ($report->comment ? $report->comment->user : null);
                if ($user && stripos($user->name, $query) !== false) {
                    return $report;
                }
            })->filter();
        }

        $groupedReports = $reportedContent->getCollection()->groupBy(function($report) {
            return $report->post ? $report->post->user_id : ($report->comment ? $report->comment->user_id : null);
        });

        return view('pages.admin.reported-content', compact('groupedReports', 'query', 'reportedContent'));
    }
    
    
}