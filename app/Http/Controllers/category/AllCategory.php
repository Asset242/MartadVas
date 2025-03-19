<?php

namespace App\Http\Controllers\category;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class AllCategory extends Controller
{
  public function index()
  {
    $categories = Category::all();
    return view('content.form-elements.all-category', compact('categories'));
  }
  public function create()
  {
    return view('content.form-elements.create-category');
  }
}
