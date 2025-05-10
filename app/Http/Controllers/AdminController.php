<?php

namespace App\Http\Controllers;

// use DB;
use Carbon\Carbon;
use App\Models\Post;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\VasReport;
use App\Models\VasImportLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PartnerServiceReport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;


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


    $totalSalesToday = VasReport::whereDate('date', $today)
                            ->selectRaw('SUM(price_point * count) as total')
                            ->value('total') ?? 0;
    $totalServicesToday = VasReport::whereDate('date', $today)
                            ->sum('count') ?? 0;

                            $totalRevenueToday = VasReport::whereDate('date', $today)
                            ->selectRaw('SUM(price_point * count) as total')
                            ->value('total') ?? 0;

    // Total Revenue This Week
    $totalRevenueThisWeek = VasReport::whereBetween('date', [$startOfWeek, $today])
                            ->selectRaw('SUM(price_point * count) as total')
                            ->value('total') ?? 0;

    // Total Revenue This Month
    $totalRevenueThisMonth = VasReport::whereBetween('date', [$startOfMonth, $today])
                            ->selectRaw('SUM(price_point * count) as total')
                            ->value('total') ?? 0;

    // Total Revenue All Time
    $totalRevenueAllTime = VasReport::selectRaw('SUM(price_point * count) as total')
                            ->value('total') ?? 0;

    $totalRevenueYesterday = VasReport::whereDate('date', $yesterday)
                                                    ->selectRaw('SUM(price_point * count) as total')
                                                    ->value('total') ?? 0;
  $lastSixMonths = [];
                                              for ($i = 5; $i >= 0; $i--) {
                                                        $month = Carbon::now()->subMonths($i);
                                                        $startOfMonth = $month->startOfMonth()->toDateString();
                                                        $endOfMonth = $month->endOfMonth()->toDateString();
                                                    
                                                        $total = VasReport::whereBetween('date', [$startOfMonth, $endOfMonth])
                                                                    ->selectRaw('SUM(price_point * count) as total')
                                                                    ->value('total') ?? 0;
                                                    
                                                        $lastSixMonths[] = [
                                                            'month' => $month->format('F'),  // E.g., January
                                                            'total' => $total
                                                        ];
                                                    }
    return view(
      'content.dashboard.admin.dashboard-admin',
      compact('totalRevenueThisMonth', 'totalRevenueThisWeek', 'totalSalesToday',
        'totalServicesToday', 'totalRevenueAllTime', 'totalRevenueYesterday', 'lastSixMonths')
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
      'service_id' => 'required|string|unique:users,service_id|max:255',
      'type' => 'required|in:martad,partner',
    ]);

    User::create([
      'name' => $request->input('name'),
      'email' => $request->input('email'),
      'service_name' =>  $request->input('service_name'),
      'type' =>  $request->input('type'),
      'service_id' => $request->input('service_id'),
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

    // $serviceTypes = VasReport::select('service_id')
    // ->distinct()
    // ->orderBy('service_id')
    // ->pluck('service_id');

    $serviceTypes = User::select('service_id', 'service_name')
    ->whereNotNull('service_id')
    ->distinct()
    ->orderBy('service_name')
    ->get()
    ->mapWithKeys(function ($item) {
        return [$item->service_id => $item->service_name ?? $item->service_id];
    });


    $productTypes = VasReport::select('product_name')
    ->distinct()
    ->orderBy('product_name')
    ->pluck('product_name');
    
    // $amounts =  VasReport::select('price_point')->distinct()->orderBy('price_point', 'desc')->pluck('price_point');
    $amounts = VasReport::select('price_point')
    ->distinct()
    ->orderBy('price_point', 'asc') // or just 'asc'
    ->pluck('price_point');

        // Start with the base query
        $partnerServices = VasReport::query();

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

        if ($request->filled('service_type')) {
          $partnerServices->where('service_id', $request->service_type);
      }

        if ($request->filled('product_types')) {
          $partnerServices->where('product_name', 'like', '%' . $request->product_types . '%');
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
        return view('content.form-elements.admin.all-statistics', compact(
          'partnerServices', 
          'serviceTypes', 
          'amounts', 
          'totalAmount', 
          'totalRecords',
          'productTypes',
          'totalCount'));
    }



  public function logout(Request $request)
  {
    auth('admin')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('martad.admin')->with('success', 'You have been logged out.');
  }


public function importVASReport(Request $request)
{
  ini_set('max_execution_time', 0);
  $request->validate([
    'file' => 'required|mimes:xlsx,xls',
]);

$file = $request->file('file');
$spreadsheet = IOFactory::load($file->getPathname());
$sheet = $spreadsheet->getActiveSheet();

$inserted = 0;
$totalRevenue = 0.0;
$now = Carbon::now();

foreach ($sheet->getRowIterator() as $index => $row) {
    $cellIterator = $row->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(false);

    $data = [];
    foreach ($cellIterator as $cell) {
        $data[] = $cell->getValue();
    }

    // Skip first row or if data is empty or header-like
    if ($index === 1 || empty(array_filter($data)) || strtolower(trim($data[6])) === 'transaction') {
        continue;
    }

    $dateValue = $data[0];
    $date = is_numeric($dateValue)
        ? Date::excelToDateTimeObject($dateValue)->format('Y-m-d')
        : $now->format('Y-m-d');

    // Skip duplicate
    // $exists = DB::table('vas_reports')->where([
    //     ['date', '=', $date],
    //     ['product_id', '=', $data[4] ?? null],
    //     ['service_id', '=', $data[2] ?? null],
    //     ['transaction', '=', $data[6] ?? null],
    // ])->exists();

    // if ($exists) {
    //     continue;
    // }

    DB::table('vas_reports')->insert([
        'date' => $date,
        // 'service_partner' => $data[1] ?? null,
        'service_id' => $data[2] ?? null,
        'price_point' => $data[3] ?? null,
        'product_id' => $data[4] ?? null,
        'product_name' => $data[5] ?? null,
        'transaction' => $data[6] ?? null,
        'count' => is_numeric($data[7]) ? (int)$data[7] : 0,
        'revenue' => is_numeric($data[8]) ? (float)$data[8] : 0.0,
        'created_at' => $now,
        'updated_at' => $now,
    ]);

    $inserted++;
    $totalRevenue += is_numeric($data[8]) ? (float)$data[8] : 0.0;
}

// Save log
VasImportLog::create([
    'imported_at' => $now,
    'records_inserted' => $inserted,
    'total_revenue' => $totalRevenue,
]);

return back()->with('success', "$inserted records imported. Total revenue: ₦" . number_format($totalRevenue, 2));
    // return response()->json(['message' => 'VAS report imported successfully']);
}

public function getImport(Request $request) {
  
  $logs = VasImportLog::orderBy('imported_at', 'desc')->get();
  return view('content.form-elements.admin.import-report', compact('logs'));
}

}
