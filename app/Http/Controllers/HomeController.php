<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('home', [
            'posts' => Post::with('author', 'tags', 'category')->latest()->paginate(6),
        ]);
    }
}
