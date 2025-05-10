<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use App\Models\VasReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PartnerController extends Controller
{
    //
    public function login(Request $request)
    {
        $login = $request->input('email'); // Can be email or username
        $password = $request->input('password');
    
        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
    
        if (auth('web')->attempt([$fieldType => $login, 'password' => $password])) {
            return redirect()->route('partner.dashboard');
        }
    
        return back()->withErrors(['message' => 'Invalid credentials, please try again.']);
    }
    

  public function homeDash() {
    $user = auth('web')->user();
    $categoryCount = Category::count();
    $postCount = Post::count();
    return view(
        view: 'content.dashboard.dashboards-analytics',
        data: compact('categoryCount', 'postCount', 'user')
      );
  }

  public function home(Request $request) {
    $user = auth('web')->user();
    $service_name = $user->service_name;
    $service_id = $user->service_id;
    $today = now()->toDateString();
    $startOfWeek = now()->startOfWeek()->toDateString();
    $startOfMonth = now()->startOfMonth()->toDateString();
    $yesterday = now()->subDay()->toDateString();

    $totalSalesToday = VasReport::where('service_id', $service_id)->whereDate('date', $today)
                            ->selectRaw('SUM(price_point * count) as total')
                            ->value('total') ?? 0;
    $totalServicesToday = VasReport::where('service_id', $service_id)->whereDate('date', $today)
                            ->sum('count') ?? 0;

                            $totalRevenueToday = VasReport::where('service_id', $service_id)->whereDate('date', $today)
                            ->selectRaw('SUM(price_point * count) as total')
                            ->value('total') ?? 0;

  

    // Total Revenue This Month
    $totalRevenueThisMonth = VasReport::where('service_id', $service_id)->whereBetween('date', [$startOfMonth, $today])
                            ->selectRaw('SUM(price_point * count) as total')
                            ->value('total') ?? 0;
                            
    $totalCountThisMonth = VasReport::where('service_id', $service_id)->whereBetween('date', [$startOfMonth, $today])
                            ->selectRaw('SUM(count) as total')
                            ->value('total') ?? 0;

    // Total Revenue All Time
    $totalRevenueAllTime = VasReport::where('service_id', $service_id)->selectRaw('SUM(price_point * count) as total')
                            ->value('total') ?? 0;
   $totalCountAllTime = VasReport::where('service_id', $service_id)->selectRaw('SUM(count) as total')
                            ->value('total') ?? 0;

  $lastSixMonths = [];
                                              for ($i = 5; $i >= 0; $i--) {
                                                        $month = Carbon::now()->subMonths($i);
                                                        $startOfMonth = $month->startOfMonth()->toDateString();
                                                        $endOfMonth = $month->endOfMonth()->toDateString();
                                                    
                                                        $total = VasReport::where('service_id', $service_id)->whereBetween('date', [$startOfMonth, $endOfMonth])
                                                                    ->selectRaw('SUM(price_point * count) as total')
                                                                    ->value('total') ?? 0;
                                                    
                                                        $lastSixMonths[] = [
                                                            'month' => $month->format('F'),  // E.g., January
                                                            'total' => $total
                                                        ];
                                                    }
                                                    $partnerRevenueTrend = [];
for ($i = 5; $i >= 0; $i--) {
    $month = Carbon::now()->subMonths($i);
    $startOfMonth = $month->startOfMonth()->toDateString();
    $endOfMonth = $month->endOfMonth()->toDateString();

    $total = VasReport::where('service_id', $service_id)
                ->whereBetween('date', [$startOfMonth, $endOfMonth])
                ->selectRaw('SUM(price_point * count) as total')
                ->value('total') ?? 0;

    $partnerRevenueTrend[] = [
        'month' => $month->format('F'),
        'total' => $total
    ];
}

                                                    return view(
                                                      view: 'content.dashboard.dashboards-analytics',
                                                      data:  compact('totalRevenueThisMonth',
                                                      'totalServicesToday', 'totalRevenueAllTime', 'totalCountAllTime', 'partnerRevenueTrend', 'totalCountThisMonth', 'lastSixMonths', 'user')
                                                    );
                                                  }

  public function partnerStatistics(Request $request) {
    $user = auth('web')->user();
    $service_id = $user->service_id;
    $serviceTypes = User::select('service_id', 'service_name')
    ->whereNotNull('service_id')
    ->distinct()
    ->orderBy('service_name')
    ->get()
    ->mapWithKeys(function ($item) {
        return [$item->service_id => $item->service_name ?? $item->service_id];
    });


    $productTypes = VasReport::where('service_id', $service_id)->select('product_name')
    ->distinct()
    ->orderBy('product_name')
    ->pluck('product_name');
    
    // $amounts =  VasReport::select('price_point')->distinct()->orderBy('price_point', 'desc')->pluck('price_point');
    $amounts = VasReport::where('service_id', $service_id)->select('price_point')
    ->distinct()
    ->orderBy('price_point', 'asc') // or just 'asc'
    ->pluck('price_point');

        // Start with the base query
        // $partnerServices = VasReport::where('service_id', $service_id)->query();
        $partnerServices = VasReport::query()->where('service_id', $service_id);


        // Apply filters based on the request inputs
    
        // Filter by Added Date
       

        // if ($request->filled('partner_type')) {
        //   $partnerServices->whereDate('partner_type', $request->date);
        // }
    
        // Filter by Logged Date
        if ($request->filled('logged_date')) {
            $partnerServices->whereDate('created_at', $request->logged_date);
        }
    
        // Filter by From and To date range
        if ($request->filled('from') && $request->filled('to')) {
            $partnerServices->whereBetween('date', [$request->from, $request->to]);
        }
        if ($request->filled('from') && !$request->filled('to')) {
          // $to = Carbon::today();
          $to =  now()->toDateString();
          $partnerServices->whereBetween('date', [$request->from, $request->to]);
      }
  
        // Filter by Service Type
        // if ($request->filled('service_type')) {
        //     $partnerServices->where('service_id', 'like', '%' . $request->service_id . '%');
        // }

      //   if ($request->filled('service_type')) {
      //     $partnerServices->where('service_id', $request->service_type);
      // }

        if ($request->filled('product_types')) {
          $partnerServices->where('product_name',$request->product_types);
      }
    
        // Filter by Charge Amount
        if ($request->filled('price_point')) {
            $partnerServices->where('price_point',$request->price_point);
        }
        
      //   if (!$request->filled('from') && !$request->filled('to')) {
      //     // $partnerServices->whereDate('created_at', now()->toDateString());
      //     $partnerServices->orderBy('added_date', 'desc');
      // } else {
      //   $partnerServices->orderBy('added_date', 'desc');

      // }
      $partnerServices->orderBy('date', 'desc');
      $totalAmount = (clone $partnerServices)->sum(DB::raw('price_point * count'));
      $totalCount = (clone $partnerServices)->sum('count');
        // Get paginated results
        $partnerServices = $partnerServices->paginate(10);
        $totalRecords = $partnerServices->total();
    
        // Return the view with the filtered results
        return view('content.form-elements.statistics', compact(
          'partnerServices', 
          'serviceTypes', 
          'amounts', 
          'totalAmount', 
          'totalRecords',
          'productTypes',
          'user',
          'totalCount'));

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
