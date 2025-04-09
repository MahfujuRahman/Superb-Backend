<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function salesReport(){
        return view('backend.report.sales_report');
    }

    public function generateSalesReport(Request $request){

        $startDate = date("Y-m-d", strtotime(str_replace("/","-",$request->start_date)))." 00:00:00";
        $endDate = date("Y-m-d", strtotime(str_replace("/","-",$request->end_date)))." 23:59:59";
        $orderStatus = $request->order_status;
        $paymentStatus = $request->payment_status;
        $paymentMethod = $request->payment_method;

        $query = Order::query();
        $query->whereBetween('order_date', [$startDate, $endDate]);

        if ($orderStatus != '') {
            $query->where('order_status', $orderStatus);
        }
        if ($paymentStatus != '') {
            $query->where('payment_status', $paymentStatus);
        }
        if ($paymentMethod != '') {
            $query->where('payment_method', $paymentMethod);
        }
        $data = $query->orderBy('id', 'desc')->get();

        $returnHTML = view('backend.report.sales_report_view', compact('startDate', 'endDate', 'data'))->render();
        return response()->json(['variant' => $returnHTML]);

    }
}
