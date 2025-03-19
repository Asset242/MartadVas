<?php

namespace App\Http\Controllers\tables;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class Basic extends Controller
{
  public function index()
  {
    $posts = Post::with('category')->get();
    return view('content.tables.tables-basic', compact('posts'));
  }
}
