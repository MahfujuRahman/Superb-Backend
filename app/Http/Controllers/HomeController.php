<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\ShippingInfo;
use App\Models\OrderPayment;
use App\Models\User;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Customer\Models\Customer;
use App\Http\Controllers\Customer\Models\CustomerCategory;
use App\Http\Controllers\Customer\Models\CustomerContactHistory;
use App\Http\Controllers\Customer\Models\CustomerNextContactDate;
use App\Http\Controllers\Outlet\Models\CustomerSourceType;
use App\Http\Controllers\Account\Models\AcAccount;
use App\Http\Controllers\Account\Models\AcTransaction;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $recentCustomers = User::where('user_type', 3)->orderBy('id', 'desc')->skip(0)->limit(5)->get();
        $orderPayments = OrderPayment::orderBy('id', 'desc')->skip(0)->limit(5)->get();

        // for upper graph start
        $countOrders = array();
        for ($i = 0; $i <= 8; $i++) {
            $orderStartDate = date("Y-m", strtotime("-$i month", strtotime(date("Y-m")))) . "-01 00:00:00";
            $orderEndDate = date("Y-m-t", strtotime("-$i month", strtotime(date("Y-m")))) . " 23:59:59";
            $countOrders[$i] = Order::whereBetween('order_date', [$orderStartDate, $orderEndDate])->count();
        }

        $totalOrderAmount = array();
        for ($i = 0; $i <= 8; $i++) {
            $orderStartDate = date("Y-m", strtotime("-$i month", strtotime(date("Y-m")))) . "-01 00:00:00";
            $orderEndDate = date("Y-m-t", strtotime("-$i month", strtotime(date("Y-m")))) . " 23:59:59";
            $totalOrderAmount[$i] = Order::whereBetween('order_date', [$orderStartDate, $orderEndDate])->where('order_status', '!=', 4)->sum('total');
        }

        $todaysOrder = array();
        for ($i = 0; $i <= 8; $i++) {
            $orderPlaceDate = date("Y-m-d", strtotime("-$i day", strtotime(date("Y-m-d"))));
            $todaysOrder[$i] = Order::where('created_at', 'LIKE', $orderPlaceDate . '%')->count();
        }

        $registeredUsers = array();
        for ($i = 0; $i <= 8; $i++) {
            $orderStartDate = date("Y-m", strtotime("-$i month", strtotime(date("Y-m")))) . "-01 00:00:00";
            $orderEndDate = date("Y-m-t", strtotime("-$i month", strtotime(date("Y-m")))) . " 23:59:59";
            $registeredUsers[$i] = User::whereBetween('created_at', [$orderStartDate, $orderEndDate])->count();
        }
        // for upper graph end


        // all time best product graph start
        $queryStartDate = date("Y-m", strtotime("-6 month", strtotime(date("Y-m")))) . "-01 00:00:00";
        $queryEndDate = date("Y-m-d") . " 23:59:59";

        $bestSelling = DB::table('order_details')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->selectRaw('products.name, SUM(order_details.qty) as total_qty')
            ->whereBetween('order_details.created_at', [$queryStartDate, $queryEndDate])
            ->groupBy('order_details.product_id')
            ->orderBy('total_qty', 'desc')
            ->skip(0)
            ->limit(3)
            ->get();
        // all time best product graph end


        // success and failed order ratio start
        $countOrdersRatioSuccess = array();
        $countOrdersRatioFailed = array();
        $countOrdersRatioDate = array();

        for ($i = 0; $i <= 9; $i++) {
            $orderRatioStartDate = date("Y-m", strtotime("-$i month", strtotime(date("Y-m")))) . "-01 00:00:00";
            $orderRatioEndDate = date("Y-m-t", strtotime("-$i month", strtotime(date("Y-m")))) . " 23:59:59";

            $countOrdersRatioDate[$i] = date("M-y", strtotime("-$i month", strtotime(date("Y-m"))));
            $countOrdersRatioSuccess[$i] = Order::whereBetween('order_date', [$orderRatioStartDate, $orderRatioEndDate])->where('order_status', 4)->count();
            $countOrdersRatioFailed[$i] = Order::whereBetween('order_date', [$orderRatioStartDate, $orderRatioEndDate])->where('order_status', '!=', 4)->count();
        }
        // success and failed order ratio end

        $thanaOrders = ShippingInfo::with('order')
            ->whereHas('order', function ($query) {
                $query->where('created_at', '>=', now()->subMonths(2));
            })
            ->select('thana', DB::raw('COUNT(DISTINCT order_id) as order_count'))
            ->groupBy('thana')
            ->having('order_count', '>', 0)
            ->get();

        $cityOrders = ShippingInfo::with('order')
            ->whereHas('order', function ($query) {
                $query->where('created_at', '>=', now()->subMonths(2));
            })
            ->select('city', DB::raw('COUNT(DISTINCT order_id) as order_count'))
            ->groupBy('city')
            ->having('order_count', '>', 0)
            ->get();

        $ordersBySource = Order::with('customerSourceType')
            ->select('customer_src_type_id', DB::raw('COUNT(*) as order_count'))
            ->groupBy('customer_src_type_id')
            ->get();


        return view(
            'backend.dashboard',
            compact(
                'recentCustomers',
                'orderPayments',
                'countOrders',
                'totalOrderAmount',
                'todaysOrder',
                'registeredUsers',
                'bestSelling',
                'queryStartDate',
                'countOrdersRatioDate',
                'countOrdersRatioSuccess',
                'countOrdersRatioFailed',
                'thanaOrders',
                'cityOrders',
                'ordersBySource'
            )
        );
    }

    public function crm_index()
    {
        // Existing data queries
        $recentCustomers = Customer::where('status', 'active')->orderBy('id', 'desc')->skip(0)->limit(10)->get();
        $orderPayments = OrderPayment::orderBy('id', 'desc')->skip(0)->limit(5)->get();

        $totalCustomerSourceTypes = CustomerSourceType::where('status', 'active')->count();
        $totalCustomerCategory = CustomerCategory::where('status', 'active')->count();
        $totalCustomerContactHistory = CustomerContactHistory::where('status', 'active')->count();
        $totalPendingCustomerNextContactDate = CustomerNextContactDate::where('status', 'active')->count();

        $totalCustomers = Customer::where('status', 'active')->count();
        $totalLastThirtyDaysCustomers = Customer::where('status', 'active')
            ->where('created_at', '>=', Carbon::now('Asia/Dhaka')->subDays(30))
            ->count();
        $totalLastSixMonthsCustomers = Customer::where('status', 'active')
            ->whereDate('created_at', '>=', Carbon::now('Asia/Dhaka')->subMonths(6))
            ->count();

        $startOfMonth = Carbon::now('Asia/Dhaka')->startOfMonth();
        $today = Carbon::now('Asia/Dhaka')->endOfDay();
        $totalThisMonthCustomers = Customer::where('status', 'active')
            ->whereBetween('created_at', [$startOfMonth, $today])
            ->count();

        // Customer growth for the last 30 days
        $customerGrowth = Customer::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', Carbon::now('Asia/Dhaka')->subDays(30))
            ->groupBy('date')
            ->get();

        // Customer growth for the last 6 months
        $customerGrowthSixMonths = Customer::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->where('created_at', '>=', Carbon::now('Asia/Dhaka')->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // dd($customerGrowthSixMonths);
        $upcomingContactCustomers = CustomerNextContactDate::where('next_date', '>=', now())->count();
        $pendingContactCustomers = CustomerNextContactDate::where('next_date', '>=', now())->where('contact_status', 'pending')->count();
        $missedContactCustomers = CustomerNextContactDate::where('next_date', '>=', now())->where('contact_status', 'missed')->count();
        $doneContactCustomers = CustomerNextContactDate::where('next_date', '>=', now())->where('contact_status', 'done')->count();


        $customerSourceDistribution = CustomerSourceType::withCount('customers')->get();

        return view(
            'backend.crm-dashboard',
            compact(
                'totalCustomerSourceTypes',
                'totalCustomerCategory',
                'totalCustomerContactHistory',
                'totalPendingCustomerNextContactDate',
                'totalCustomers',
                'totalLastThirtyDaysCustomers',
                'totalLastSixMonthsCustomers',
                'totalThisMonthCustomers',
                'recentCustomers',
                'orderPayments',
                'customerGrowth',
                'customerGrowthSixMonths',
                'customerSourceDistribution',
                'upcomingContactCustomers',
                'pendingContactCustomers',
                'missedContactCustomers',
                'doneContactCustomers'
            )
        );
    }

    // public function accounts_index()
    // {

    //     $recentCustomers = User::where('user_type', 3)->orderBy('id', 'desc')->skip(0)->limit(5)->get();
    //     $orderPayments = OrderPayment::orderBy('id', 'desc')->skip(0)->limit(5)->get();

    //     // for upper graph start
    //     $countOrders = array();
    //     for ($i = 0; $i <= 8; $i++) {
    //         $orderStartDate = date("Y-m", strtotime("-$i month", strtotime(date("Y-m")))) . "-01 00:00:00";
    //         $orderEndDate = date("Y-m-t", strtotime("-$i month", strtotime(date("Y-m")))) . " 23:59:59";
    //         $countOrders[$i] = Order::whereBetween('order_date', [$orderStartDate, $orderEndDate])->count();
    //     }

    //     $totalOrderAmount = array();
    //     for ($i = 0; $i <= 8; $i++) {
    //         $orderStartDate = date("Y-m", strtotime("-$i month", strtotime(date("Y-m")))) . "-01 00:00:00";
    //         $orderEndDate = date("Y-m-t", strtotime("-$i month", strtotime(date("Y-m")))) . " 23:59:59";
    //         $totalOrderAmount[$i] = Order::whereBetween('order_date', [$orderStartDate, $orderEndDate])->where('order_status', '!=', 4)->sum('total');
    //     }

    //     $todaysOrder = array();
    //     for ($i = 0; $i <= 8; $i++) {
    //         $orderPlaceDate = date("Y-m-d", strtotime("-$i day", strtotime(date("Y-m-d"))));
    //         $todaysOrder[$i] = Order::where('created_at', 'LIKE', $orderPlaceDate . '%')->count();
    //     }

    //     $registeredUsers = array();
    //     for ($i = 0; $i <= 8; $i++) {
    //         $orderStartDate = date("Y-m", strtotime("-$i month", strtotime(date("Y-m")))) . "-01 00:00:00";
    //         $orderEndDate = date("Y-m-t", strtotime("-$i month", strtotime(date("Y-m")))) . " 23:59:59";
    //         $registeredUsers[$i] = User::whereBetween('created_at', [$orderStartDate, $orderEndDate])->count();
    //     }
    //     // for upper graph end


    //     // all time best product graph start
    //     $queryStartDate = date("Y-m", strtotime("-6 month", strtotime(date("Y-m")))) . "-01 00:00:00";
    //     $queryEndDate = date("Y-m-d") . " 23:59:59";

    //     $bestSelling = DB::table('order_details')
    //         ->join('products', 'order_details.product_id', '=', 'products.id')
    //         ->selectRaw('products.name, SUM(order_details.qty) as total_qty')
    //         ->whereBetween('order_details.created_at', [$queryStartDate, $queryEndDate])
    //         ->groupBy('order_details.product_id')
    //         ->orderBy('total_qty', 'desc')
    //         ->skip(0)
    //         ->limit(3)
    //         ->get();
    //     // all time best product graph end


    //     // success and failed order ratio start
    //     $countOrdersRatioSuccess = array();
    //     $countOrdersRatioFailed = array();
    //     $countOrdersRatioDate = array();

    //     for ($i = 0; $i <= 9; $i++) {
    //         $orderRatioStartDate = date("Y-m", strtotime("-$i month", strtotime(date("Y-m")))) . "-01 00:00:00";
    //         $orderRatioEndDate = date("Y-m-t", strtotime("-$i month", strtotime(date("Y-m")))) . " 23:59:59";

    //         $countOrdersRatioDate[$i] = date("M-y", strtotime("-$i month", strtotime(date("Y-m"))));
    //         $countOrdersRatioSuccess[$i] = Order::whereBetween('order_date', [$orderRatioStartDate, $orderRatioEndDate])->where('order_status', 4)->count();
    //         $countOrdersRatioFailed[$i] = Order::whereBetween('order_date', [$orderRatioStartDate, $orderRatioEndDate])->where('order_status', '!=', 4)->count();
    //     }
    //     // success and failed order ratio end


    //     // return 5;
    //     return view('backend.accounts-dashboard', compact(
    //             'recentCustomers',
    //             'orderPayments',
    //             'countOrders',
    //             'totalOrderAmount',
    //             'todaysOrder',
    //             'registeredUsers',
    //             'bestSelling',
    //             'queryStartDate',
    //             'countOrdersRatioDate',
    //             'countOrdersRatioSuccess',
    //             'countOrdersRatioFailed',
    //         )
    //     );
    // }

    public function accounts_index()
    {
        // Define the date range for the last 12 months
        $startDate12Months = Carbon::now()->subMonths(12)->startOfMonth();
        $endDate12Months = Carbon::now()->endOfMonth();

        // Define the date range for the last 30 days
        $startDate30Days = Carbon::now()->subDays(30)->startOfDay();
        $endDate30Days = Carbon::now()->endOfDay();

        // Fetch accounts for Assets, Liabilities, Equity, Revenue, and Expenses
        $assetAccounts = AcAccount::where('account_name', 'Assets')->orWhere('parent_id', function ($query) {
            $query->select('id')->from('ac_accounts')->where('account_name', 'Assets');
        })->pluck('id');

        $liabilityAccounts = AcAccount::where('account_name', 'Liability')->orWhere('parent_id', function ($query) {
            $query->select('id')->from('ac_accounts')->where('account_name', 'Liability');
        })->pluck('id');

        $equityAccounts = AcAccount::where('account_name', 'Equity')->orWhere('parent_id', function ($query) {
            $query->select('id')->from('ac_accounts')->where('account_name', 'Equity');
        })->pluck('id');

        $revenueAccounts = AcAccount::where('account_name', 'Revenue')->orWhere('parent_id', function ($query) {
            $query->select('id')->from('ac_accounts')->where('account_name', 'Revenue');
        })->pluck('id');

        $expenseAccounts = AcAccount::where('account_name', 'Expense')->orWhere('parent_id', function ($query) {
            $query->select('id')->from('ac_accounts')->where('account_name', 'Expense');
        })->pluck('id');

        // Fetch total revenue (sum of credit amounts for revenue accounts) for the last 12 months
        $totalRevenue12Months = AcTransaction::whereIn('credit_account_id', $revenueAccounts)
            ->whereBetween('transaction_date', [$startDate12Months, $endDate12Months])
            ->sum('credit_amt');

        // Fetch total expenses (sum of debit amounts for expense accounts) for the last 12 months
        $totalExpenses12Months = AcTransaction::whereIn('debit_account_id', $expenseAccounts)
            ->whereBetween('transaction_date', [$startDate12Months, $endDate12Months])
            ->sum('debit_amt');

        // Calculate net profit (revenue - expenses) for the last 12 months
        $netProfit12Months = $totalRevenue12Months - $totalExpenses12Months;
        // dd($assetAccounts, $endDate12Months);

        // Fetch cash flow data for the last 12 months (based on Assets and Liabilities)
        if ($assetAccounts->isNotEmpty()) {
            $cashFlowData12Months = AcTransaction::selectRaw('YEAR(transaction_date) as year, MONTH(transaction_date) as month, 
            SUM(CASE WHEN debit_account_id IN (' . $assetAccounts->implode(',') . ') THEN debit_amt ELSE 0 END) as cash_inflow,
            SUM(CASE WHEN credit_account_id IN (' . $assetAccounts->implode(',') . ') THEN credit_amt ELSE 0 END) as cash_outflow')
                ->whereBetween('transaction_date', [$startDate12Months, $endDate12Months])
                ->groupBy('year', 'month')
                ->orderBy('year', 'asc')
                ->orderBy('month', 'asc')
                ->get();
        } else {
            // Handle the case where no asset accounts are found, maybe return an empty result or handle the error
            $cashFlowData12Months = collect(); // Empty collection
        }

        // Format cash flow data for the last 12 months
        $cashFlowLabels12Months = [];
        $cashFlowIncome12Months = [];
        $cashFlowExpenses12Months = [];
        $cashFlowNet12Months = []; // Net cash flow for the last 12 months
        foreach ($cashFlowData12Months as $data) {
            $cashFlowLabels12Months[] = Carbon::create()->month($data->month)->format('M') . ' ' . $data->year;
            $cashFlowIncome12Months[] = $data->cash_inflow; // Cash inflow (debit to Assets)
            $cashFlowExpenses12Months[] = $data->cash_outflow; // Cash outflow (credit to Assets)
            $cashFlowNet12Months[] = $data->cash_inflow - $data->cash_outflow; // Net cash flow
        }

        // Fetch total revenue (sum of credit amounts for revenue accounts) for the last 30 days
        $totalRevenue30Days = AcTransaction::whereIn('credit_account_id', $revenueAccounts)
            ->whereBetween('transaction_date', [$startDate30Days, $endDate30Days])
            ->sum('credit_amt');

        // Fetch total expenses (sum of debit amounts for expense accounts) for the last 30 days
        $totalExpenses30Days = AcTransaction::whereIn('debit_account_id', $expenseAccounts)
            ->whereBetween('transaction_date', [$startDate30Days, $endDate30Days])
            ->sum('debit_amt');

        // Calculate net profit (revenue - expenses) for the last 30 days
        $netProfit30Days = $totalRevenue30Days - $totalExpenses30Days;



        // Fetch cash flow data for the last 30 days (based on Assets and Liabilities)
        if ($assetAccounts->isNotEmpty()) {
            $cashFlowData30Days = AcTransaction::selectRaw('DATE(transaction_date) as date, 
            SUM(CASE WHEN debit_account_id IN (' . $assetAccounts->implode(',') . ') THEN debit_amt ELSE 0 END) as cash_inflow,
            SUM(CASE WHEN credit_account_id IN (' . $assetAccounts->implode(',') . ') THEN credit_amt ELSE 0 END) as cash_outflow')
                ->whereBetween('transaction_date', [$startDate30Days, $endDate30Days])
                ->groupBy('date')
                ->orderBy('date', 'asc')
                ->get();
        } else {
            // Handle the case where no asset accounts are found, maybe return an empty result or handle the error
            $cashFlowData30Days = collect(); // Empty collection
        }
        // Format cash flow data for the last 30 days
        $cashFlowLabels30Days = [];
        $cashFlowIncome30Days = [];
        $cashFlowExpenses30Days = [];
        $cashFlowNet30Days = []; // Net cash flow for the last 30 days
        foreach ($cashFlowData30Days as $data) {
            $cashFlowLabels30Days[] = Carbon::parse($data->date)->format('M d');
            $cashFlowIncome30Days[] = $data->cash_inflow; // Cash inflow (debit to Assets)
            $cashFlowExpenses30Days[] = $data->cash_outflow; // Cash outflow (credit to Assets)
            $cashFlowNet30Days[] = $data->cash_inflow - $data->cash_outflow; // Net cash flow
        }

        // Fetch recent transactions (last 10 transactions)
        $recentTransactions = AcTransaction::with(['debitAccount', 'creditAccount'])
            ->whereBetween('transaction_date', [$startDate30Days, $endDate30Days])
            ->orderBy('transaction_date', 'desc')
            ->limit(10)
            ->get();

        // Pass data to the view
        return view(
            'backend.accounts-dashboard',
            compact(
                'totalRevenue12Months',
                'totalExpenses12Months',
                'netProfit12Months',
                'cashFlowLabels12Months',
                'cashFlowIncome12Months',
                'cashFlowExpenses12Months',
                'cashFlowNet12Months',
                'totalRevenue30Days',
                'totalExpenses30Days',
                'netProfit30Days',
                'cashFlowLabels30Days',
                'cashFlowIncome30Days',
                'cashFlowExpenses30Days',
                'cashFlowNet30Days',
                'recentTransactions'
            )
        );
    }

    public function dummy_index()
    {

        $recentCustomers = User::where('user_type', 3)->orderBy('id', 'desc')->skip(0)->limit(5)->get();
        $orderPayments = OrderPayment::orderBy('id', 'desc')->skip(0)->limit(5)->get();

        // for upper graph start
        $countOrders = array();
        for ($i = 0; $i <= 8; $i++) {
            $orderStartDate = date("Y-m", strtotime("-$i month", strtotime(date("Y-m")))) . "-01 00:00:00";
            $orderEndDate = date("Y-m-t", strtotime("-$i month", strtotime(date("Y-m")))) . " 23:59:59";
            $countOrders[$i] = Order::whereBetween('order_date', [$orderStartDate, $orderEndDate])->count();
        }

        $totalOrderAmount = array();
        for ($i = 0; $i <= 8; $i++) {
            $orderStartDate = date("Y-m", strtotime("-$i month", strtotime(date("Y-m")))) . "-01 00:00:00";
            $orderEndDate = date("Y-m-t", strtotime("-$i month", strtotime(date("Y-m")))) . " 23:59:59";
            $totalOrderAmount[$i] = Order::whereBetween('order_date', [$orderStartDate, $orderEndDate])->where('order_status', '!=', 4)->sum('total');
        }

        $todaysOrder = array();
        for ($i = 0; $i <= 8; $i++) {
            $orderPlaceDate = date("Y-m-d", strtotime("-$i day", strtotime(date("Y-m-d"))));
            $todaysOrder[$i] = Order::where('created_at', 'LIKE', $orderPlaceDate . '%')->count();
        }

        $registeredUsers = array();
        for ($i = 0; $i <= 8; $i++) {
            $orderStartDate = date("Y-m", strtotime("-$i month", strtotime(date("Y-m")))) . "-01 00:00:00";
            $orderEndDate = date("Y-m-t", strtotime("-$i month", strtotime(date("Y-m")))) . " 23:59:59";
            $registeredUsers[$i] = User::whereBetween('created_at', [$orderStartDate, $orderEndDate])->count();
        }
        // for upper graph end


        // all time best product graph start
        $queryStartDate = date("Y-m", strtotime("-6 month", strtotime(date("Y-m")))) . "-01 00:00:00";
        $queryEndDate = date("Y-m-d") . " 23:59:59";

        $bestSelling = DB::table('order_details')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->selectRaw('products.name, SUM(order_details.qty) as total_qty')
            ->whereBetween('order_details.created_at', [$queryStartDate, $queryEndDate])
            ->groupBy('order_details.product_id')
            ->orderBy('total_qty', 'desc')
            ->skip(0)
            ->limit(3)
            ->get();
        // all time best product graph end


        // success and failed order ratio start
        $countOrdersRatioSuccess = array();
        $countOrdersRatioFailed = array();
        $countOrdersRatioDate = array();

        for ($i = 0; $i <= 9; $i++) {
            $orderRatioStartDate = date("Y-m", strtotime("-$i month", strtotime(date("Y-m")))) . "-01 00:00:00";
            $orderRatioEndDate = date("Y-m-t", strtotime("-$i month", strtotime(date("Y-m")))) . " 23:59:59";

            $countOrdersRatioDate[$i] = date("M-y", strtotime("-$i month", strtotime(date("Y-m"))));
            $countOrdersRatioSuccess[$i] = Order::whereBetween('order_date', [$orderRatioStartDate, $orderRatioEndDate])->where('order_status', 4)->count();
            $countOrdersRatioFailed[$i] = Order::whereBetween('order_date', [$orderRatioStartDate, $orderRatioEndDate])->where('order_status', '!=', 4)->count();
        }
        // success and failed order ratio end

        $thanaOrders = ShippingInfo::with('order')
            ->whereHas('order', function ($query) {
                $query->where('created_at', '>=', now()->subMonths(2));
            })
            ->select('thana', \DB::raw('COUNT(DISTINCT order_id) as order_count'))
            ->groupBy('thana')
            ->having('order_count', '>', 0)
            ->get();

        $cityOrders = ShippingInfo::with('order')
            ->whereHas('order', function ($query) {
                $query->where('created_at', '>=', now()->subMonths(2));
            })
            ->select('city', \DB::raw('COUNT(DISTINCT order_id) as order_count'))
            ->groupBy('city')
            ->having('order_count', '>', 0)
            ->get();

        $ordersBySource = Order::with('customerSourceType')
            ->select('customer_src_type_id', \DB::raw('COUNT(*) as order_count'))
            ->groupBy('customer_src_type_id')
            ->get();


        return view(
            'backend.accounts-dashboard',
            compact(
                'recentCustomers',
                'orderPayments',
                'countOrders',
                'totalOrderAmount',
                'todaysOrder',
                'registeredUsers',
                'bestSelling',
                'queryStartDate',
                'countOrdersRatioDate',
                'countOrdersRatioSuccess',
                'countOrdersRatioFailed',
                'thanaOrders',
                'cityOrders',
                'ordersBySource'
            )
        );
    }






    public function changePasswordPage()
    {
        return view('backend.change_password');
    }

    public function changePassword(Request $request)
    {

        $currentPassword = $request->prev_password;
        $newPassword = $request->new_password;
        $userId = $request->user_id;
        $userInfo = User::where('id', $userId)->first();

        if (Hash::check($currentPassword, $userInfo->password)) {
            User::where('id', $userId)->update([
                'name' => $request->name,
                'password' => Hash::make($newPassword),
            ]);

            Toastr::success('Password Changed', 'Successfully');
            return back();
        } else {
            Toastr::error('Current Password is Wrong', 'Failed');
            return back();
        }
    }

    public function clearCache()
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');

        Toastr::success('Cahce Cleared', 'Successfully');
        return back();
    }

    public function viewPaymentHistory(Request $request)
    {
        if ($request->ajax()) {
            $data = OrderPayment::orderBy('id', 'desc')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->make(true);
        }
        return view('backend.payment_histories');
    }
}
