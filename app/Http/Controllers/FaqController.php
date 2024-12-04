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
        $faqs = FAQ::all();
        return view('pages/faq.index', compact('faqs'));
    }
}
