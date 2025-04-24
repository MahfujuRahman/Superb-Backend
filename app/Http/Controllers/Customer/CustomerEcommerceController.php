<?php

namespace App\Http\Controllers\Customer;

use DataTables;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Hash;

class CustomerEcommerceController extends Controller
{
    public function addNewCustomerEcommerce()
    {
        return view('backend.customer_ecommerce.create');
    }

    public function saveNewCustomerEcommerce(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255', 'unique:users'],
            'address' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:2048'],
            // 'code' => ['required', 'string', 'max:255', 'unique:customer_sources,code'],
        ]);

        $image = null;
        if ($request->hasFile('image')) {
            $get_image = $request->file('image');
            $image_name = str::random(5) . time() . '.' . $get_image->getClientOriginalExtension();
            $location = public_path('userProfileImages/');
            $get_image->move($location, $image_name);
            $image = "userProfileImages/" . $image_name;
        }

        User::insert([
            'name' => request()->name,
            'phone' => request()->phone,
            'email' => request()->email,
            'address' => request()->address,
            'password' => \Illuminate\Support\Facades\Hash::make(request()->password),
            'image' => $image ?? null,
            'user_type' => 3,

            'status' => 1,
            'created_at' => Carbon::now()
        ]);

        Toastr::success('Added successfully!', 'Success');
        return back();
    }

    public function viewAllCustomerEcommerce(Request $request)
    {
        if ($request->ajax()) {
            $data = User::where('user_type', 3)
                ->orderBy('id', 'desc')  // Order by ID in descending order
                ->get();

            return Datatables::of($data)
                ->editColumn('status', function ($data) {
                    if ($data->status == 1) {
                        return 'Active';
                    } else {
                        return 'Inactive';
                    }
                })
                ->addColumn('name', function ($data) {
                    return $data->name ? $data->name : 'N/A';
                })
                ->editColumn('image', function ($data) {
                    if ($data->image && file_exists(public_path($data->image))) {
                        return $data->image;
                    }
                })
                ->addColumn('phone', function ($data) {
                    return $data->phone ? $data->phone : 'N/A';
                })
                ->addColumn('email', function ($data) {
                    return $data->email ? $data->email : 'N/A';
                })
                ->addColumn('address', function ($data) {
                    return $data->address ? $data->address : 'N/A';
                })
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $btn = ' <a href="' . url('edit/customer-ecommerce') . '/' . $data->id . '" class="btn-sm btn-warning rounded editBtn"><i class="fas fa-edit"></i></a>';
                    $btn .= ' <a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $data->id . '" data-original-title="Delete" class="btn-sm btn-danger rounded deleteBtn"><i class="fas fa-trash-alt"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('backend.customer_ecommerce.view');
    }


    public function editCustomerEcommerce($slug)
    {
        $data = User::where('id', $slug)->first();
        return view('backend.customer_ecommerce.edit', compact('data'));
    }

    public function updateCustomerEcommerce(Request $request)
    {
        $user = User::findOrFail($request->id);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255', 'unique:users,email,' . $user->id . ',id'],
            'address' => ['required', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:2048'],
        ]);

        $image = $user->image;

        if ($request->hasFile('image')) {
            // delete old image if exists
            if ($user->image && file_exists(public_path($user->image))) {
                unlink(public_path($user->image));
            }

            $get_image = $request->file('image');
            $image_name = Str::random(5) . time() . '.' . $get_image->getClientOriginalExtension();
            $location = public_path('userProfileImages/');
            $get_image->move($location, $image_name);
            $image = "userProfileImages/" . $image_name;
        }

        $user->update([
            'name' => $request->name ?? $user->name,
            'phone' => $request->phone ?? $user->phone,
            'email' => $request->email ?? $user->email,
            'address' => $request->address ?? $user->address,
            'password' => $request->filled('password') ? Hash::make($request->password) : $user->password,
            'image' => $image ?? $user->image,
            'user_type' => 3,
            'status' => 1,
            'updated_at' => Carbon::now()
        ]);

        $data = $user;

        Toastr::success('Updated Successfully', 'Success!');
        return view('backend.customer_ecommerce.edit', compact('data'));
    }


    public function deleteCustomerEcommerce($slug)
    {
        $data = user::where('id', $slug)->first();

        if ($data->image && file_exists(public_path($data->image))) {
            unlink(public_path($data->image));
        }

        $data->delete();
        
        return response()->json([
            'success' => 'Deleted successfully!',
            'data' => 1
        ]);
    }
}
