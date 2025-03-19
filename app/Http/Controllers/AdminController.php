<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
  public function loginPage()
  {
    return view('content.authentications.admin.admin-login');
  }

  public function adminLogin(Request $request)
  {
    $credentials = $request->only('email', 'password');
    if (auth('admin')->attempt($credentials)) {
      return redirect()->route('admin-dashboard');
    }
    return back()->withErrors(['message' => 'Invalid details, please try again']);
  }

  public function adminDashboard()
  {
    $categoryCount = Category::count();
    $postCount = Post::count();
    $userCount = User::count();
    $productsCount = Product::count();
    return view(
      'content.dashboard.admin.dashboard-admin',
      compact('categoryCount', 'postCount', 'userCount', 'productsCount')
    );
  }

  public function getCreatePartner()
  {
    return view('content.form-elements.admin.create-partner');
    // $posts = Post::with('category')->get();
    // $categories = Category::all();
    // return view('content.tables.admin.admin-partner', compact('posts', 'categories'));
  }

  public function createPartner(Request $request)
  {
    $request->validate([
      'name' => 'required|string|max:255|unique:users,name',
      'email' => 'required|email|max:255|unique:users,email',
      'password' => 'required|string|min:8',
    ]);

    User::create([
      'name' => $request->input('name'),
      'email' => $request->input('email'),
      'password' => Hash::make($request->input('password'))
    ]);

    return redirect()
      ->back()
      ->with('success', 'Partner has been created successfully!');
  }

  public function getPartners()
  {
    $users = User::all();
    return view('content.tables.admin.admin-partner', compact('users'));
  }

  public function getCreateProduct()
  {
    $users = User::all();
    return view('content.form-elements.admin.create-product', compact('users'));
  }

  public function createProduct(Request $request)
  {
        $request->validate([
          'plan_id' => 'required|string|max:10',
          'user_id' => 'required|integer|exists:users,id',
          'duration' => 'required|integer',
          'description' => 'required|string',
          'amount' => 'required|integer',
          'coin' => 'required|integer',
        ]);

        $product = new Product();
        $product->plan_id = $request->input('plan_id');
        $product->user_id = $request->input('user_id');
        $product->duration = $request->input('duration');
        $product->description = $request->input('description');
        $product->amount = $request->input('amount');
        $product->coin = $request->input('coin');
        $product->save();
        return redirect()
          ->back()
          ->with('success', 'Product has been created successfully!');
  }

  public function getProduct()
  {
    $products = Product::with('user')->get();
    return view('content.tables.admin.admin-products', compact('products'));
  }


  public function logout(Request $request)
  {
    auth('admin')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('admin')->with('success', 'You have been logged out.');
  }
}
