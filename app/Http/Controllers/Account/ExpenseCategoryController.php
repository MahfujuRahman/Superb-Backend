<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Account\Models\DbExpenseCategory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ExpenseCategoryController extends Controller
{
    public function addNewExpenseCategory()
    {
        // $payment_types = DbExpenseCategory::where('status', 'active')->get();
        // $users = User::where('status', 1)->get();
        // return view('backend.expensecategory.create', compact('customer_categories', 'customer_source_types', 'users'));
        return view('backend.expensecategory.create');
    }

    public function saveNewExpenseCategory(Request $request)
    {
        // dd(request()->all());
        $request->validate([
            'category_name' => ['required', 'string', 'max:100'],
            'category_code' => ['required', 'string', 'max:100'],
        ], [
            'category_name.required' => 'category name is required.',
            'category_name.max' => 'category name must not exceed 100 characters.',
            'category_code.required' => 'category code is required.',
            'category_code.max' => 'category code must not exceed 100 characters.',
        ]);

        $clean = preg_replace('/[^a-zA-Z0-9\s]/', '', strtolower(request()->category_name)); //remove all non alpha numeric
        $slug = preg_replace('!\s+!', '-', $clean);

        // $customer_category = CustomerCategory::where('id', request()->customer_category_id)->first();
        // $customer_source_type = CustomerSourceType::where('id', request()->customer_source_type_id)->first();
        // dd(5);

        DbExpenseCategory::insert([
            'store_id' => 1,
            'category_name' => request()->category_name ?? '',
            'category_code' => request()->category_code ?? '',
            'description' => request()->description ?? '',

            'creator' => auth()->user()->id,
            'slug' => $slug . time(),
            'status' => 'active',
            'created_at' => Carbon::now('Asia/Dhaka')
        ]);

        Toastr::success('Added successfully!', 'Success');
        return back();

    }


    public function viewAllExpenseCategory(Request $request)
    {
        if ($request->ajax()) {
            $data = DbExpenseCategory::with('user')
                        ->where('status', 'active')
                        ->orderBy('id', 'DESC')
                        ->get();

            return Datatables::of($data)
                ->addIndexColumn()                       
                ->addColumn('user', function ($data) {
                    return $data->user ? $data->user->name : 'N/A';
                })
                ->editColumn('created_at', function ($data) {
                    return date("Y-m-d h:i", strtotime($data->created_at));
                })        
                ->addColumn('action', function ($data) {
                    $btn = '<a href="' . url('edit/expense-category') . '/' . $data->slug . '" class="btn-sm btn-warning rounded editBtn"><i class="fas fa-edit"></i></a>';
                    $btn .= ' <a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $data->slug . '" data-original-title="Delete" class="btn-sm btn-danger rounded deleteBtn"><i class="fas fa-trash-alt"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('backend.expensecategory.view');
    }


    public function editExpenseCategory($slug)
    {
        $data = DbExpenseCategory::where('status', 'active')->where('slug', $slug)->first();
        return view('backend.expensecategory.edit', compact('data'));
    }

    public function updateExpenseCategory(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'category_name' => ['required', 'string', 'max:100'],
            'category_code' => ['required', 'string', 'max:100'],
        ], [
            'category_name.required' => 'category name is required.',
            'category_name.max' => 'category name must not exceed 100 characters.',
            'category_code.required' => 'category code is required.',
            'category_code.max' => 'category code must not exceed 100 characters.',
        ]);

        // Check if the selected product_warehouse_room_id exists for the selected product_warehouse_id        
        $data = DbExpenseCategory::where('id', request()->expense_category_id)->first();

        $clean = preg_replace('/[^a-zA-Z0-9\s]/', '', strtolower($data->category_name)); //remove all non alpha numeric
        $slug = preg_replace('!\s+!', '-', $clean);

        $data->store_id = request()->store_id ?? $data->store_id;
        $data->category_name = request()->category_name ?? $data->category_name;
        $data->category_code = request()->category_code ?? $data->category_code;
        $data->description = request()->description ?? $data->description;
        

        if ($data->category_name != $request->category_name) {
            $data->slug = $slug . time();
        }

        $data->creator = auth()->user()->id;
        $data->status = request()->status ?? $data->status;
        $data->updated_at = Carbon::now();
        $data->save();

        Toastr::success('Successfully Updated', 'Success!');
        return redirect()->route('ViewAllExpenseCategory');
    }


    public function deleteExpenseCategory($slug)
    {
        $data = DbExpenseCategory::where('slug', $slug)->first();

        // $data->delete();
        $data->status = 'inactive';
        $data->save();
        
        return response()->json([
            'success' => 'Deleted successfully!',
            'data' => 1
        ]);
    }
}
