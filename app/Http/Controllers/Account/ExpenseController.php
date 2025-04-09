<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Account\Models\DbExpense;
use App\Http\Controllers\Account\Models\DbExpenseCategory;
use App\Http\Controllers\Account\Models\DbPaymentType;
use App\Http\Controllers\Account\Models\AcAccount;
use App\Http\Controllers\Account\Models\AcTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    public function addNewExpense()
    {
        $accounts = AcAccount::where('status', 'active')->get();
        $expense_categories = DbExpenseCategory::where('status', 'active')->get();
        $payment_types = DbPaymentType::where('status', 'active')->get();
        // $users = User::where('status', 1)->get();
        // return view('backend.expense.create', compact('customer_categories', 'customer_source_types', 'users'));
        return view('backend.expense.create', compact('accounts', 'expense_categories', 'payment_types'));
    }

    public function saveNewExpense(Request $request)
    {
        // dd(request()->all());
        $request->validate([
            'expense_for' => ['required', 'string', 'max:255'],
            'expense_amt' => ['required'],
            'expense_date' => ['required'],
            'payment_type_id' => ['required'],
        ], [
            'expense_for.required' => 'expense for is required.',
            'expense_for.max' => 'expense for must not exceed 100 characters.',
            'expense_amt.required' => 'expense amount is required',
            'expense_date.required' => 'expense date is required',
            'payment_type_id.required' => 'payment type is required',
        ]);

        $clean = preg_replace('/[^a-zA-Z0-9\s]/', '', strtolower(request()->expense_for)); //remove all non alpha numeric
        $slug = preg_replace('!\s+!', '-', $clean);

        $lastCountId = DbExpense::max('count_id') ?? 0;
        $countId = $lastCountId + 1;

        // $customer_category = CustomerCategory::where('id', request()->customer_category_id)->first();
        // $customer_source_type = CustomerSourceType::where('id', request()->customer_source_type_id)->first();
        // dd(5);

        // $lastCountId = DbExpense::max('id') ?? 0;
        // $expense_code = $lastCountId + 1;
        // $expense_code = rand(100, 999) . $expense_code;

        $lastCountAccountId = AcAccount::max('id') ?? 0;
        $lastCountTransactionId = AcTransaction::max('id') ?? 0;
        $transaction_code = rand(100, 999) . $lastCountAccountId + 1 . $lastCountTransactionId + 1;


        DbExpense::insert([
            'store_id' => 1,
            'count_id' => $countId,
            'category_id' => request()->expense_category_id ?? '',
            'payment_type_id' => request()->payment_type_id ?? '',
            'account_id' => request()->expense_account_id ?? '',
            'debit_account_id' => request()->expense_account_id ?? '',
            'credit_account_id' => request()->asset_cash_account_id ?? '',
            'expense_code' => $transaction_code,
            'expense_date' => request()->expense_date ?? '',
            'expense_for' => request()->expense_for ?? '',
            'expense_amt' => request()->expense_amt ?? '',
            'reference_no' => request()->reference_no ?? '',
            'note' => request()->note ?? '',

            'creator' => auth()->user()->id,
            'slug' => $slug . time() . rand(),
            'status' => 'active',
            'created_at' => Carbon::now('Asia/Dhaka')
        ]);


        $transaction_one = AcTransaction::create([
            'store_id' => '',
            'payment_code' => $transaction_code,
            'transaction_date' => request()->expense_date ?? '',
            'transaction_type' => 'EXPENSE',

            'debit_account_id' => request()->asset_cash_account_id ?? '',
            'debit_amt' => request()->expense_amt ?? '',

            'credit_account_id' => '',
            'credit_amt' => '',

            'note' => request()->note ?? 'recorded',

            'creator' => auth()->user()->id,
            'slug' => $slug . rand(1000, 9999) . time(),
            'status' => 'active',
            'created_at' => Carbon::now('Asia/Dhaka')
        ]);

        $transaction_two = AcTransaction::create([
            'store_id' => '',
            'payment_code' => $transaction_code,
            'transaction_date' => request()->expense_date ?? '',
            'transaction_type' => 'EXPENSE',


            'credit_account_id' => request()->expense_account_id ?? '',
            'credit_amt' => request()->expense_amt ?? '',

            'debit_account_id' => '',
            'debit_amt' => '',

            'note' => request()->note ?? 'recorded',

            'creator' => auth()->user()->id,
            'slug' => $slug . rand(100, 999) . time(),
            'status' => 'active',
            'created_at' => Carbon::now('Asia/Dhaka')
        ]);





        Toastr::success('Added successfully!', 'Success');
        return back();

    }


    public function viewAllExpense(Request $request)
    {
        // dd(5);
        if ($request->ajax()) {
            $data = DbExpense::with('user', 'expense_category', 'payment_type')
                ->where('status', 'active')
                ->orderBy('id', 'DESC')
                ->get();

            // dd($data);

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('payment_type', function ($data) {
                    return $data->payment_type ? $data->payment_type->payment_type : 'N/A';
                })
                ->addColumn('user', function ($data) {
                    return $data->user ? $data->user->name : 'N/A';
                })
                ->editColumn('created_at', function ($data) {
                    return date("Y-m-d h:i", strtotime($data->created_at));
                })
                ->addColumn('action', function ($data) {
                    $btn = '<a href="' . url('edit/expense') . '/' . $data->slug . '" class="btn-sm btn-warning rounded editBtn"><i class="fas fa-edit"></i></a>';
                    $btn .= ' <a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $data->slug . '" data-original-title="Delete" class="btn-sm btn-danger rounded deleteBtn"><i class="fas fa-trash-alt"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('backend.expense.view');
    }



    public function editExpense($slug)
    {
        $data = DbExpense::where('status', 'active')->where('slug', $slug)->first();
        $accounts = AcAccount::where('status', 'active')->get();
        $expense_categories = DbExpenseCategory::where('status', 'active')->get();
        $payment_types = DbPaymentType::where('status', 'active')->get();
        // $nestedData = $this->buildTree($accounts);
        $nestedDataAll = $this->buildTree($accounts);
        // $nestedDataAll =AcAccount::where('status', 'active')
        //                                 ->where('account_name', '!=', 'Expense')
        //                                 ->get();
        $nestedData = AcAccount::where('account_name', 'Expense')->with('inc')->where('status', 'active')->get();

        // $transaction = AcTransaction::where('status', 'active')->where('payment_code', $data->expense_code)->get();;


        return view('backend.expense.edit', compact(
            'data',
            'accounts',
            'expense_categories',
            'payment_types',
            'nestedData',
            'nestedDataAll',
        )
        );
    }

    private function buildTree($accounts, $parentId = null)
    {
        $tree = [];

        foreach ($accounts as $account) {
            // Skip 'Expense' account and its children
            if ($account->account_name === 'Expense') {
                continue;  // Skip this account and its children
            }

            if ($account->parent_id == $parentId) {
                // Recursively build the tree for children
                $children = $this->buildTree($accounts, $account->id);

                // Build the node for the current account
                $node = [
                    'id' => $account->id,
                    'text' => $account->account_name,
                ];

                // If there are children, add them to the node
                if (!empty($children)) {
                    $node['inc'] = $children;
                }

                // Add the current node to the tree
                $tree[] = $node;
            }
        }

        return $tree;
    }


    public function updateExpense(Request $request)
    {
        // dd(request()->all());
        $request->validate([
            'expense_for' => ['required', 'string', 'max:255'],
            'expense_amt' => ['required'],
            'expense_date' => ['required'],
            'payment_type_id' => ['required'],
        ], [
            'expense_for.required' => 'expense for is required.',
            'expense_for.max' => 'expense for must not exceed 100 characters.',
            'expense_amt.required' => 'expense amount is required',
            'expense_date.required' => 'expense date is required',
            'payment_type_id.required' => 'payment type is required',
        ]);

        // Check if the selected product_warehouse_room_id exists for the selected product_warehouse_id        
        $data = DbExpense::where('id', request()->expense_id)->first();

        $clean = preg_replace('/[^a-zA-Z0-9\s]/', '', strtolower($data->expense_for)); //remove all non alpha numeric
        $slug = preg_replace('!\s+!', '-', $clean);

        $data->store_id = request()->expense_store_id ?? $data->expense_store_id;
        $data->category_id = request()->expense_category_id ?? $data->expense_category_id;
        $data->account_id = request()->expense_account_id ?? $data->account_id;
        $data->debit_account_id = request()->expense_account_id ?? $data->expense_account_id;
        $data->credit_account_id = request()->asset_cash_account_id ?? $data->asset_cash_account_id;
        $data->payment_type_id = request()->payment_type_id ?? $data->payment_type_id;
        $data->expense_for = request()->expense_for ?? $data->expense_for;
        $data->expense_code = $data->expense_code ?? '';
        $data->expense_date = request()->expense_date ?? $data->expense_date;
        $data->expense_amt = request()->expense_amt ?? $data->expense_amt;
        $data->reference_no = request()->reference_no ?? $data->reference_no;
        $data->note = request()->note ?? $data->note;


        if ($data->expense_for != $request->expense_for) {
            $data->slug = $slug . time() . rand();
        }

        $data->creator = auth()->user()->id;
        $data->status = request()->status ?? $data->status;
        $data->updated_at = Carbon::now('Asia/Dhaka');
        $data->save();






        // $request->validate([
        //     'deposit_date' => ['required'],
        //     'debit_credit_amount' => ['required'],
        // ], [
        //     'deposit_date.required' => 'deposit date is required.',
        //     'debit_credit_amount.required' => 'amount is required',
        // ]);



        // Check if the selected product_warehouse_room_id exists for the selected product_warehouse_id        
        // $data = AcTransaction::where('id', request()->deposit_id)->first();
        // $data_two = AcTransaction::where('payment_code', $data->payment_code)->get();
        // dd($data_two);


        // Fetch all transactions with the same payment_code
        $data_two = AcTransaction::where('payment_code', $data->expense_code)->get();

        // Loop through the transactions and update based on conditions
        foreach ($data_two as $item) {

            if (request()->has('asset_cash_account_id') && $item->credit_account_id != 0) {
                $item->credit_account_id = request()->asset_cash_account_id;
                $item->debit_amt = 0.0000;
                $item->credit_amt = request()->expense_amt;
            }

            if (request()->has('expense_account_id') && $item->debit_account_id != 0) {
                $item->debit_account_id = request()->expense_account_id;
                $item->credit_amt = 0.0000;
                $item->debit_amt = request()->expense_amt;
            }

            if (request()->has('asset_cash_account_id') && $item->credit_account_id == 0) {
                $item->debit_amt = request()->expense_amt;
            }
            if (request()->has('expense_account_id') && $item->debit_account_id == 0) {
                $item->credit_amt = request()->expense_amt;
            }


            $item->transaction_date = request()->deposit_date ?? $item->transaction_date;
            $item->note = request()->note ?? $item->note;
            $item->creator = auth()->user()->id;
            $item->status = request()->status ?? $item->status;
            $item->updated_at = Carbon::now('Asia/Dhaka');
            $item->save();
            // dd($item);
        }










        Toastr::success('Successfully Updated', 'Success!');
        return redirect()->route('ViewAllExpense');
    }


    public function deleteExpense($slug)
    {
        $data = DbExpense::where('slug', $slug)->first();

        // $data->delete();
        $data->status = 'inactive';
        $data->save();

        return response()->json([
            'success' => 'Deleted successfully!',
            'data' => 1
        ]);
    }
}
