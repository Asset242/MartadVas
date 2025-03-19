<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class Analytics extends Controller
{
  public function index()
  {
    $categoryCount = Category::count();
    $postCount = Post::count();

  
    return view('content.dashboard.dashboards-analytics' , compact('categoryCount', 'postCount'));
  }
}
