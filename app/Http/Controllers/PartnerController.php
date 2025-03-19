<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PartnerController extends Controller
{
    //
    public function login(Request $request)
  {
    $credentials = $request->only('email', 'password');
    if (auth('web')->attempt($credentials)) {
      return redirect()->route('partner.dashboard');
    }
    return back()->withErrors(['message' => 'Invalid details, please try again']);
  }

  public function home() {
    $user = auth('web')->user();
    $categoryCount = Category::count();
    $postCount = Post::count();
    return view(
        view: 'content.dashboard.dashboards-analytics',
        data: compact('categoryCount', 'postCount', 'user')
      );
  }

  public function logout(Request $request)
  {
        // Use the 'admin' guard to log out the admin
        auth('web')->logout();

        // Invalidate the session
        $request->session()->invalidate();

        // Regenerate the session token to prevent session fixation attacks
        $request->session()->regenerateToken();

        // Redirect to the login page
        return redirect()->route('login')->with('success', 'You have been logged out.');
    }
}
