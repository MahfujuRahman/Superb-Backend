<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Account\Models\AcAccount;
use App\Http\Controllers\Account\Models\AcTransaction;
// use App\Http\Controllers\Account\Models\DbExpenseCategory;
// use App\Http\Controllers\Account\Models\DbPaymentType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TransactionController extends Controller
{
    public function addNewDeposit()
    {
        $accounts = AcAccount::where('status', 'active')->get();
        // $expense_categories = DbExpenseCategory::where('status', 'active')->get();
        // $payment_types = DbPaymentType::where('status', 'active')->get();
        // $users = User::where('status', 1)->get();
        // return view('backend.transaction.create', compact('customer_categories', 'customer_source_types', 'users'));
        return view('backend.transaction.create', compact('accounts'));
    }

    public function saveNewDeposit(Request $request)
    {
        // dd(request()->all());
        $request->validate([
            'deposit_date' => ['required'],
            'debit_id' => ['required'],
            'credit_id' => ['required'],
            'debit_credit_amount' => ['required'],
        ], [
            'deposit_date.required' => 'deposit date is required.',
            'debit_id.required' => 'account type is required.',
            'credit_id.required' => 'account is required',
            'debit_credit_amount.required' => 'amount is required',
        ]);

        $clean = preg_replace('/[^a-zA-Z0-9\s]/', '', strtolower(request()->debit_id)); //remove all non alpha numeric
        $slug = preg_replace('!\s+!', '-', $clean);

        $lastCountId = AcTransaction::max('id') ?? 0;
        $countId = $lastCountId + 1;

        // $customer_category = CustomerCategory::where('id', request()->customer_category_id)->first();
        // $customer_source_type = CustomerSourceType::where('id', request()->customer_source_type_id)->first();
        // dd(5);

        $deposit_one = AcTransaction::create([
            'store_id' => '',
            'payment_code' => rand(100, 999) . $countId,
            'transaction_date' => request()->deposit_date ?? '',
            'transaction_type' => 'DEPOSIT',
            'debit_account_id' => request()->debit_id ?? '',
            'debit_amt' => request()->debit_credit_amount ?? '',

            'credit_account_id' => '',
            'credit_amt' => '',

            'note' => request()->note ?? 'recorded',

            'creator' => auth()->user()->id,
            'slug' => $slug . rand(100, 999) . time(),
            'status' => 'active',
            'created_at' => Carbon::now('Asia/Dhaka')
        ]);

        $deposit_two = AcTransaction::create([
            'store_id' => '',
            'payment_code' => $deposit_one->payment_code,
            'transaction_date' => request()->deposit_date ?? '',
            'transaction_type' => 'DEPOSIT',
            'credit_account_id' => request()->credit_id ?? '',
            'credit_amt' => request()->debit_credit_amount ?? '',

            'debit_account_id' => '',
            'debit_amt' => '',

            'note' => request()->note ?? 'recorded',

            'creator' => auth()->user()->id,
            'slug' => $slug . rand(1000, 9999) . time(),
            'status' => 'active',
            'created_at' => Carbon::now('Asia/Dhaka')
        ]);

        Toastr::success('Added successfully!', 'Success');
        return back();

    }


    public function viewAllDeposit(Request $request)
    {
        // dd(5);
        if ($request->ajax()) {
            $data = AcTransaction::with('debitAccount', 'creditAccount', 'user')
                ->where('transaction_type', 'DEPOSIT')
                ->where('status', 'active')
                ->orderBy('id', 'DESC')
                ->get();

            // dd($data);

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('transactionDate', function ($data) {
                    return $data->transaction_date ?? '';
                })
                ->addColumn('payment_code', function ($data) {
                    return $data->payment_code ?? '';
                })
                ->addColumn('transactionType', function ($data) {
                    return $data->transaction_type ?? '';
                })
                ->editColumn('debit', function ($data) {
                    if ($data->debitAccount) {
                        return '<span style="color:green; font-weight: 600;">' . $data->debitAccount->account_name . '</span>';
                    } else {
                        return '<span style="color:red; font-weight: 600;">N/A</span>';
                    }
                })
                ->editColumn('debitAmount', function ($data) {
                    if ($data->debit_amt) {
                        return '<span style="color:green; font-weight: 600;">' . $data->debit_amt . '</span>';
                    } else {
                        return '<span style="color:red; font-weight: 600;">N/A</span>';
                    }
                })


                ->editColumn('credit', function ($data) {
                    if ($data->creditAccount) {
                        return '<span style="color:green; font-weight: 600;">' . $data->creditAccount->account_name . '</span>';
                    } else {
                        return '<span style="color:red; font-weight: 600;">N/A</span>';
                    }
                })


                ->editColumn('creditAmount', function ($data) {
                    if ($data->credit_amt) {
                        return '<span style="color:green; font-weight: 600;">' . $data->credit_amt . '</span>';
                    } else {
                        return '<span style="color:red; font-weight: 600;">N/A</span>';
                    }
                })

                ->addColumn('user', function ($data) {
                    return $data->user ? ucfirst($data->user->name) : 'N/A';
                })
                ->editColumn('created_at', function ($data) {
                    return date("Y-m-d h:i a", strtotime($data->created_at));
                })
                ->addColumn('action', function ($data) {
                    $btn = '<a href="' . url('edit/deposit') . '/' . $data->slug . '" class="btn-sm btn-warning rounded editBtn"><i class="fas fa-edit"></i></a>';
                    $btn .= ' <a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $data->slug . '" data-original-title="Delete" class="btn-sm btn-danger rounded deleteBtn"><i class="fas fa-trash-alt"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action', 'debit', 'credit', 'debitAmount', 'creditAmount'])
                ->make(true);
        }
        return view('backend.transaction.view');
    }


    private function buildTree($accounts, $parentId = null)
    {
        $tree = [];

        foreach ($accounts as $account) {
            if ($account->parent_id == $parentId) {
                $children = $this->buildTree($accounts, $account->id);
                $node = [
                    'id' => $account->id,
                    'text' => $account->account_name,
                ];

                if (!empty($children)) {
                    $node['inc'] = $children;
                }

                $tree[] = $node;
            }
        }

        return $tree;
    }


    public function editDeposit($slug)
    {
        $data = AcTransaction::where('status', 'active')->where('slug', $slug)->first();
        $accounts = AcAccount::where('status', 'active')->get();
        $nestedData = $this->buildTree($accounts);

        return view('backend.transaction.edit', compact('data', 'nestedData'));
    }

    public function updateDeposit(Request $request)
    {
        // dd(request()->all());
        // dd(request()->all());
        $request->validate([
            'deposit_date' => ['required'],
            'debit_credit_amount' => ['required'],
        ], [
            'deposit_date.required' => 'deposit date is required.',
            'debit_credit_amount.required' => 'amount is required',
        ]);



        // Check if the selected product_warehouse_room_id exists for the selected product_warehouse_id        
        $data = AcTransaction::where('id', request()->deposit_id)->first();
        $data_two = AcTransaction::where('payment_code', $data->payment_code)->get();
        // dd($data_two);
     


        // Fetch all transactions with the same payment_code
        $data_two = AcTransaction::where('payment_code', $data->payment_code)->get();

        // Loop through the transactions and update based on conditions
        foreach ($data_two as $item) {

            if(request()->has('credit_id') && $item->credit_account_id != 0) {                
                $item->credit_account_id = request()->credit_id;
                $item->debit_amt = 0.0000;
                $item->credit_amt = request()->debit_credit_amount;
            }

            if(request()->has('debit_id') && $item->debit_account_id != 0) {                
                $item->debit_account_id = request()->debit_id;
                $item->credit_amt = 0.0000;
                $item->debit_amt = request()->debit_credit_amount;
            }

            if(request()->has('credit_id') && $item->credit_account_id == 0) {
                $item->debit_amt = request()->debit_credit_amount;
            }
            if(request()->has('debit_id') && $item->debit_account_id == 0) {
                $item->credit_amt = request()->debit_credit_amount;
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
        return redirect()->route('ViewAllDeposit');
    }



    public function deleteDeposit($slug)
    {
        $data = AcTransaction::where('slug', $slug)->first();

        // $data->delete();
        $data->status = 'inactive';
        $data->save();

        return response()->json([
            'success' => 'Deleted successfully!',
            'data' => 1
        ]);
    }





    public function showLedger() {
        return view('');
    }



}
