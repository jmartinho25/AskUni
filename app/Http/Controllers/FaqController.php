<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;
use App\Models\FAQ;

class FaqController extends Controller
{
    use HasFactory;
    public function index()
    {
        $faqs = FAQ::paginate(5);
        return view('pages/faq.index', compact('faqs'));
    }

    public function create()
    {
        return view('pages/faq.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
        ]);

        FAQ::create([
            'question' => $validated['question'],
            'answer' => $validated['answer'],
        ]);

        return redirect()->route('faq.index')->with('success', 'FAQ created successfully');
    }

    public function edit($id)
    {
        $faq = FAQ::findOrFail($id);
        return view('pages/faq.edit', compact('faq'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
        ]);

        $faq = FAQ::findOrFail($id);
        $faq->update([
            'question' => $validated['question'],
            'answer' => $validated['answer'],
        ]);

        return redirect()->route('faq.index')->with('success', 'FAQ updated successfully');
    }

    public function destroy($id)
    {
        $faq = FAQ::findOrFail($id);
        $faq->delete();
        return redirect()->route('faq.index')->with('success', 'FAQ deleted successfully');
    }
}
