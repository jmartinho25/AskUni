<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContentReports;

class ReportController extends Controller
{
    public function create(Request $request)
    {
        $type = $request->query('type');
        $id = $request->query('id');
        $redirect_url = $request->query('redirect_url');
        return view('pages.report.create', compact('type', 'id', 'redirect_url'));
    }


    public function store(Request $request)
    {

        $request->validate([
            'report_reason' => 'required|string|max:255',
            'comments_id' => 'nullable|integer',
            'posts_id' => 'nullable|integer',
        ]);

        $report = new ContentReports();
        $report->report_reason = $request->report_reason;
        $report->date = now();
        $report->solved = false;
        $report->comments_id = $request->comments_id;
        $report->posts_id = $request->posts_id;
        $report->save();

        return redirect($request->input('redirect_url'))->with('success', 'Content reported successfully.');
    }
}