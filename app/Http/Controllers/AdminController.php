<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\PartnerServiceReport;
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
    $today = now()->toDateString();
    $startOfWeek = now()->startOfWeek()->toDateString();
    $startOfMonth = now()->startOfMonth()->toDateString();
    $yesterday = now()->subDay()->toDateString();

    $categoryCount = Category::count();
    $postCount = Post::count();
    $userCount = User::count();
    $productsCount = Product::count();

    $totalSalesToday = PartnerServiceReport::whereDate('added_date', $today)
                            ->selectRaw('SUM(charge_amount * count) as total')
                            ->value('total') ?? 0;
    $totalServicesToday = PartnerServiceReport::whereDate('added_date', $today)
                            ->sum('count') ?? 0;

                            $totalRevenueToday = PartnerServiceReport::whereDate('added_date', $today)
                            ->selectRaw('SUM(charge_amount * count) as total')
                            ->value('total') ?? 0;

    // Total Revenue This Week
    $totalRevenueThisWeek = PartnerServiceReport::whereBetween('added_date', [$startOfWeek, $today])
                            ->selectRaw('SUM(charge_amount * count) as total')
                            ->value('total') ?? 0;

    // Total Revenue This Month
    $totalRevenueThisMonth = PartnerServiceReport::whereBetween('added_date', [$startOfMonth, $today])
                            ->selectRaw('SUM(charge_amount * count) as total')
                            ->value('total') ?? 0;

    // Total Revenue All Time
    $totalRevenueAllTime = PartnerServiceReport::selectRaw('SUM(charge_amount * count) as total')
                            ->value('total') ?? 0;

    $totalRevenueYesterday = PartnerServiceReport::whereDate('added_date', $yesterday)
                                                    ->selectRaw('SUM(charge_amount * count) as total')
                                                    ->value('total') ?? 0;
    return view(
      'content.dashboard.admin.dashboard-admin',
      compact('totalRevenueThisMonth', 'totalRevenueThisWeek', 'totalSalesToday',
        'totalServicesToday', 'totalRevenueAllTime', 'totalRevenueYesterday')
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
      'service_name' => 'required|string|unique:users,service_name|max:255',
      'type' => 'required|in:martad,partner',
    ]);

    User::create([
      'name' => $request->input('name'),
      'email' => $request->input('email'),
      'service_name' =>  $request->input('service_name'),
      'type' =>  $request->input('type'),
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


  public function getStatistics(Request $request)
  {
    // $serviceTypes = User::select('service_name')
    // ->distinct()
    // ->orderBy('service_name')
    // ->pluck('service_name');

    $serviceTypes = PartnerServiceReport::select('service_type')
    ->distinct()
    ->orderBy('service_type')
    ->pluck('service_type');
        // Start with the base query
        $partnerServices = PartnerServiceReport::query();

        // Apply filters based on the request inputs
    
        // Filter by Added Date
        if ($request->filled('added_date')) {
            $partnerServices->whereDate('added_date', $request->added_date);
        }

        // if ($request->filled('partner_type')) {
        //   $partnerServices->whereDate('partner_type', $request->added_date);
        // }
    
        // Filter by Logged Date
        if ($request->filled('logged_date')) {
            $partnerServices->whereDate('created_at', $request->logged_date);
        }
    
        // Filter by From and To date range
        if ($request->filled('from') && $request->filled('to')) {
            $partnerServices->whereBetween('added_date', [$request->from, $request->to]);
        }
    
        // Filter by Service Type
        if ($request->filled('service_type')) {
            $partnerServices->where('service_type', 'like', '%' . $request->service_type . '%');
        }
    
        // Filter by Charge Amount
        if ($request->filled('charge_amount')) {
            $partnerServices->where('charge_amount', 'like', '%' . $request->charge_amount . '%');
        }
        
        if (
          !$request->filled('logged_date') &&
          !$request->filled('from') &&
          !$request->filled('to') &&
          !$request->filled('added_date')
      ) {
          $partnerServices->whereDate('created_at', now()->toDateString());
      }
        // Get paginated results
        $partnerServices = $partnerServices->paginate(10);
    
        // Return the view with the filtered results
        return view('content.form-elements.admin.all-statistics', compact('partnerServices', 'serviceTypes'));
    }



  public function logout(Request $request)
  {
    auth('admin')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('martad.admin')->with('success', 'You have been logged out.');
  }
}
