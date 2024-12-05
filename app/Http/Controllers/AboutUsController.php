<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AboutUsController extends Controller
{
    protected $filePath = 'resources/about_us.md';

    public function index()
    {
        $content = File::exists(base_path($this->filePath)) ? File::get(base_path($this->filePath)) : '';
        return view('pages.about.index', compact('content'));
    }

    public function edit()
    {
        $content = File::exists(base_path($this->filePath)) ? File::get(base_path($this->filePath)) : '';
        return view('pages.about.edit', compact('content'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        File::put(base_path($this->filePath), $validated['content']);

        return redirect()->route('aboutUs.index')->with('success', 'About Us section updated successfully');
    }
}