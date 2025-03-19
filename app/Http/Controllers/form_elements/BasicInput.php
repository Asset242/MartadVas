<?php

namespace App\Http\Controllers\form_elements;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class BasicInput extends Controller
{
  public function index()
  {
    $categories = Category::all();
    return view('content.form-elements.forms-basic-inputs', compact('categories'));
  }
  public function edit(Post $post)
  {
    $categories = Category::all();
    return view('content.form-elements.edit', compact("post", "categories"));
  }
}
