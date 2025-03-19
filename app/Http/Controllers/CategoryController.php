<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
  public function index()
  {
    $categories = Category::all();
    return view('all-category', compact('categories'));
  }

  public function store(Request $request)
  {
      $request->validate([
          'name' => 'required|string|max:255|unique:categories,name',
      ]);
      Category::create([
          'name' => $request->input('name'),
      ]);
      return redirect()->route('categories.all')->with('success', 'Category created successfully!');
  }
}
