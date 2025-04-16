<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PartnerServiceReport;

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
    $today = now()->toDateString();
    $startOfWeek = now()->startOfWeek()->toDateString();
    $startOfMonth = now()->startOfMonth()->toDateString();
    $yesterday = now()->subDay()->toDateString();

    $totalSalesToday = PartnerServiceReport::where('service_type', $service_name)->whereDate('added_date', $today)
                            ->selectRaw('SUM(charge_amount * count) as total')
                            ->value('total') ?? 0;
    $totalServicesToday = PartnerServiceReport::where('service_type', $service_name)->whereDate('added_date', $today)
                            ->sum('count') ?? 0;

                            $totalRevenueToday = PartnerServiceReport::where('service_type', $service_name)->whereDate('added_date', $today)
                            ->selectRaw('SUM(charge_amount * count) as total')
                            ->value('total') ?? 0;

  

    // Total Revenue This Month
    $totalRevenueThisMonth = PartnerServiceReport::where('service_type', $service_name)->whereBetween('added_date', [$startOfMonth, $today])
                            ->selectRaw('SUM(charge_amount * count) as total')
                            ->value('total') ?? 0;
                            
    $totalCountThisMonth = PartnerServiceReport::where('service_type', $service_name)->whereBetween('added_date', [$startOfMonth, $today])
                            ->selectRaw('SUM(count) as total')
                            ->value('total') ?? 0;

    // Total Revenue All Time
    $totalRevenueAllTime = PartnerServiceReport::where('service_type', $service_name)->selectRaw('SUM(charge_amount * count) as total')
                            ->value('total') ?? 0;
   $totalCountAllTime = PartnerServiceReport::where('service_type', $service_name)->selectRaw('SUM(count) as total')
                            ->value('total') ?? 0;

  $lastSixMonths = [];
                                              for ($i = 5; $i >= 0; $i--) {
                                                        $month = Carbon::now()->subMonths($i);
                                                        $startOfMonth = $month->startOfMonth()->toDateString();
                                                        $endOfMonth = $month->endOfMonth()->toDateString();
                                                    
                                                        $total = PartnerServiceReport::where('service_type', $service_name)->whereBetween('added_date', [$startOfMonth, $endOfMonth])
                                                                    ->selectRaw('SUM(charge_amount * count) as total')
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

    $total = PartnerServiceReport::where('service_type', $service_name)
                ->whereBetween('added_date', [$startOfMonth, $endOfMonth])
                ->selectRaw('SUM(charge_amount * count) as total')
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
