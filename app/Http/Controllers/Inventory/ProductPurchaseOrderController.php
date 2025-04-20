<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Inventory\Models\ProductSupplier;
use App\Http\Controllers\Inventory\Models\ProductWarehouse;
use App\Http\Controllers\Inventory\Models\ProductWarehouseRoom;
use App\Http\Controllers\Inventory\Models\ProductWarehouseRoomCartoon;
use App\Http\Controllers\Inventory\Models\ProductPurchaseOrder;
use App\Http\Controllers\Inventory\Models\ProductPurchaseOrderProduct;
use App\Http\Controllers\Inventory\Models\ProductPurchaseQuotation;
use App\Http\Controllers\Inventory\Models\ProductPurchaseQuotationProduct;
use App\Http\Controllers\Inventory\Models\ProductPurchaseOtherCharge;
use App\Http\Controllers\Inventory\Models\ProductStock;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Brian2694\Toastr\Facades\Toastr;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductPurchaseOrderController extends Controller
{
    public function addNewPurchaseProductOrder()
    {
        $products = Product::where('status', 'active')->get();
        $suppliers = ProductSupplier::where('status', 'active')->get();
        $productWarehouses = ProductWarehouse::where('status', 'active')->get();
        $productWarehouseRooms = ProductWarehouseRoom::where('status', 'active')->get();
        $productWarehouseRoomCartoons = ProductWarehouseRoomCartoon::where('status', 'active')->get();
        $other_charges_types = ProductPurchaseOtherCharge::where('status', 'active')->get();
        return view('backend.purchase_product_order.create', compact('products', 'suppliers', 'productWarehouses', 'productWarehouseRooms', 'productWarehouseRoomCartoons', 'other_charges_types'));
    }

    public function calc_other_charges($other_charges, $subtotal)
    {

        $percent_total = 0;
        $fixed_total = 0;

        foreach ($other_charges as $charge) {
            if ($charge['type'] === 'percent') {
                $percent_total += ($subtotal * $charge['amount']) / 100;
            } else {
                $fixed_total += $charge['amount'];
            }
        }

        $total = $percent_total + $fixed_total;
        return $total;
    }

    public function saveNewPurchaseProductOrder(Request $request)
    {
        // dd(request()->all());
        // $request->validate([
        //     'title' => ['required', 'string', 'max:255'],
        //     'product_warehouse_id' => ['required'],
        //     'product_warehouse_room_id' => ['required'],
        // ], [
        //     'title.required' => 'title is required.',
        // ]);

        $other_charge_total = $this->calc_other_charges(request()->other_charges, request()->subtotal);

        $random_no = random_int(100, 999) . random_int(1000, 9999);
        $slug = Str::orderedUuid() . uniqid() . $random_no;


        $user = auth()->user();
        $order = new ProductPurchaseOrder();
        $order->product_warehouse_id = request()->purchase_product_warehouse_id;
        $order->product_warehouse_room_id = request()->purchase_product_warehouse_room_id;
        $order->product_warehouse_room_cartoon_id = request()->purchase_product_warehouse_room_cartoon_id;
        $order->product_supplier_id = request()->supplier_id;
        $order->date = request()->purchase_date;

        // $order->other_charge_type = request()->other_charges_type;
        // $order->other_charge_percentage = request()->other_charges_input_amount;
        // $order->other_charge_amount = request()->other_charges_amt;

        $order->other_charge_type = json_encode(request()->other_charges);
        // $order->other_charge_percentage = request()->other_charges_input_amount;
        $order->other_charge_amount = $other_charge_total;




        $order->discount_type = request()->discount_to_all_type;
        $order->discount_amount = request()->discount_on_all;
        $order->calculated_discount_amount = request()->discount_to_all_amt;
        $order->round_off = request()->total_round_off_amt;
        $order->subtotal = request()->subtotal_amt;
        $order->total = request()->grand_total_amt;
        $order->note = request()->purchase_note;
        // $order->code = $new_code;
        // $order->reference = $new_reference;
        $order->order_status = 'pending';
        $order->creator = $user->id;

        $order->status = 'active';
        $order->created_at = Carbon::now();
        $order->save();



        foreach ($request->product as $productItem) {

            $unit_price = $productItem['prices'];
            $discount_percent = $productItem['discounts'];
            $tax_percent = $productItem['taxes'];
            $discounted_price = $unit_price * (1 - ($discount_percent / 100));
            $final_price_per_unit = $discounted_price * (1 + ($tax_percent / 100));

            $product_slug = Str::orderedUuid() . $random_no . $order->id . uniqid();

            // $product = Product::where('id', $productItem['id'])->first();
            ProductPurchaseOrderProduct::create([
                'product_warehouse_id' => request()->purchase_product_warehouse_id,
                'product_warehouse_room_id' => request()->purchase_product_warehouse_room_id,
                'product_warehouse_room_cartoon_id' => request()->purchase_product_warehouse_room_cartoon_id,
                'product_supplier_id' => request()->supplier_id,
                'product_purchase_order_id' => $order->id,
                'product_id' => $productItem['id'],
                'product_name' => $productItem['name'],
                'qty' => $productItem['quantities'],
                'product_price' => $productItem['prices'],
                'discount_type' => 'in_percentage',
                'discount_amount' => $productItem['discounts'],
                'tax' => $productItem['taxes'],
                'purchase_price' => $final_price_per_unit,
                'slug' => $product_slug,
            ]);
        }

        $last = ProductPurchaseOrder::orderBy('id', 'desc')->first();
        if ($last) {
            $new_code = "OP" . $order->id . ($last->code + 1) . $random_no;
        } else {
            $new_code = "OP" . $order->id . "00001" . $random_no;
        }

        $reference = ProductPurchaseOrder::orderBy('id', 'desc')->first();
        if ($reference) {
            $new_reference = $order->id . ($reference->reference + 1) . $random_no;
        } else {
            $new_reference = "001" . $order->id . $random_no;
        }

        $order->code = $new_code;
        $order->reference = $new_reference;
        $order->slug = $order->id . $slug;
        $order->save();


        Toastr::success('Quotation has been added successfully!', 'Success');
        return back();
    }

    public function viewAllPurchaseProductOrder(Request $request)
    {
        if ($request->ajax()) {

            $data = ProductPurchaseOrder::with('creator', 'order_products')
                // ->where('status', 'active')
                ->orderBy('id', 'desc') // Order by the ID
                ->get();

            // dd($data);
            return Datatables::of($data)
                // ->editColumn('creator', function ($data) {
                //     return $data->creator ? $data->creator->name : 'N/A'; // Access creator name
                // })
                ->editColumn('status', function ($data) {
                    return $data->status == "active" ? 'Active' : 'Inactive';
                })
                ->editColumn('created_at', function ($data) {
                    return date("Y-m-d", strtotime($data->created_at));
                })
                ->addIndexColumn()
                // ->addColumn('action', function ($data) {
                //     $btn = '<a href="' . url('edit/purchase-product/quotation') . '/' . $data->slug . '" class="btn-sm btn-warning rounded editBtn"><i class="fas fa-edit"></i></a>';
                //     $btn .= ' <a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $data->slug . '" data-original-title="Delete" class="btn-sm btn-danger rounded deleteBtn"><i class="fas fa-trash-alt"></i></a>';
                //     return $btn;
                // })
                ->addColumn('action', function ($data) {
                    $btn = '<div class="dropdown">';
                    $btn .= '<button class="btn-sm btn-primary dropdown-toggle rounded" type="button" id="actionDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                    $btn .= 'Action';
                    $btn .= '</button>';
                    $btn .= '<div class="dropdown-menu" aria-labelledby="actionDropdown">';
                    $btn .= '<a class="dropdown-item" href="' . url('edit/purchase-product/order') . '/' . $data->slug . '"><i class="fas fa-edit"></i> Edit</a>';
                    $btn .= '<a class="dropdown-item" href="' . url('edit/purchase-product/order/confirm') . '/' . $data->slug . '"><i class="fas fa-edit"></i> Confirm Order</a>';
                    $btn .= '<a class="dropdown-item" href="javascript:void(0)" data-toggle="tooltip" data-id="' . $data->slug . '" data-original-title="Delete" class="deleteBtn"><i class="fas fa-trash-alt"></i> Delete</a>';
                    $btn .= '</div>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('backend.purchase_product_order.view');
    }



    public function editPurchaseProductOrder($slug)
    {
        // dd($slug);
        $data = ProductPurchaseOrder::where('slug', $slug)->first();
        $productWarehouses = ProductWarehouse::where('status', 'active')->get();
        $productWarehouseRooms = ProductWarehouseRoom::where('product_warehouse_id', $data->product_warehouse_id)->where('status', 'active')->get();
        $productWarehouseRoomCartoon = ProductWarehouseRoomCartoon::where('product_warehouse_id', $data->product_warehouse_id)->where('product_warehouse_room_id', $data->product_warehouse_room_id)->where('status', 'active')->get();
        $suppliers = ProductSupplier::where('status', 'active')->get();

        $other_charges_types = ProductPurchaseOtherCharge::where('status', 'active')->get();

        return view('backend.purchase_product_order.edit', compact('data', 'productWarehouses', 'productWarehouseRooms', 'productWarehouseRoomCartoon', 'suppliers', 'other_charges_types'));
    }

    public function apiEditPurchaseProduct($slug)
    {
        // dd($slug);
        $data = ProductPurchaseOrder::with('order_products')->where('slug', $slug)->first();
        $productWarehouses = ProductWarehouse::where('status', 'active')->get();
        $productWarehouseRooms = ProductWarehouseRoom::where('product_warehouse_id', $data->product_warehouse_id)->where('status', 'active')->get();
        $productWarehouseRoomCartoon = ProductWarehouseRoomCartoon::where('product_warehouse_id', $data->product_warehouse_id)->where('product_warehouse_room_id', $data->product_warehouse_room_id)->where('status', 'active')->get();
        $suppliers = ProductSupplier::where('status', 'active')->get();

        return response()->json([
            'data' => $data,
        ]);

        // return view('backend.purchase_product_quotation.edit', compact('data', 'productWarehouses', 'productWarehouseRooms', 'productWarehouseRoomCartoon', 'suppliers'));
    }

    public function updatePurchaseProductOrder(Request $request)
    {

        $other_charge_total = $this->calc_other_charges(request()->other_charges, request()->subtotal);

        $order = ProductPurchaseOrder::where('id', request()->purchase_product_order_id)->first();

        $user = auth()->user();
        $order->product_warehouse_id = request()->purchase_product_warehouse_id;
        $order->product_warehouse_room_id = request()->purchase_product_warehouse_room_id;
        $order->product_warehouse_room_cartoon_id = request()->purchase_product_warehouse_room_cartoon_id;
        $order->product_supplier_id = request()->supplier_id;
        $order->product_purchase_quotation_id = $order->product_purchase_quotation_id ?? '';
        $order->date = request()->purchase_date;

        // $order->other_charge_type = request()->other_charges_type;
        // $order->other_charge_percentage = request()->other_charges_input_amount;
        // $order->other_charge_amount = request()->other_charges_amt;

        $order->other_charge_type = json_encode(request()->other_charges);
        // $order->other_charge_percentage = request()->other_charges_input_amount;
        $order->other_charge_amount = $other_charge_total;



        $order->discount_type = request()->discount_to_all_type;
        $order->discount_amount = request()->discount_on_all;
        $order->calculated_discount_amount = request()->discount_to_all_amt;
        $order->round_off = request()->total_round_off_amt;
        $order->subtotal = request()->subtotal;
        $order->total = request()->grand_total_amt;
        $order->note = request()->purchase_note;
        // $order->is_ordered = 'pending';
        $order->creator = $user->id;
        $order->status = 'active';
        $order->created_at = Carbon::now();
        $order->save();

        // Get all existing product IDs for the given order
        $existingProductIds = ProductPurchaseOrderProduct::where('product_purchase_order_id', $order->id)
            ->pluck('product_id')
            ->toArray();

        $requestProductIds = []; // To track products in the request

        foreach ($request->product as $productItem) {
            if (!isset($productItem['id'])) {
                continue;
            }

            $unit_price = $productItem['prices'];
            $discount_percent = $productItem['discounts'];
            $tax_percent = $productItem['taxes'];
            $discounted_price = $unit_price * (1 - ($discount_percent / 100));
            $final_price_per_unit = $discounted_price * (1 + ($tax_percent / 100));

            $product_slug = Str::orderedUuid() . $order->id . uniqid();

            // Determine the product_id
            $product_id = !empty($productItem['product_id']) ? $productItem['product_id'] : $productItem['id'];

            // Track product IDs in the request
            $requestProductIds[] = $product_id;

            // Check if product already exists in the database
            $existingProduct = ProductPurchaseOrderProduct::where('product_purchase_order_id', $order->id)
                ->where('product_id', $product_id)
                ->first();

            if ($existingProduct) {
                // Update the existing record
                $existingProduct->update([
                    'product_warehouse_id' => $request->purchase_product_warehouse_id,
                    'product_warehouse_room_id' => $request->purchase_product_warehouse_room_id,
                    'product_warehouse_room_cartoon_id' => $request->purchase_product_warehouse_room_cartoon_id,
                    'product_supplier_id' => $request->supplier_id,
                    'product_name' => $productItem['name'],
                    'qty' => $productItem['quantities'],
                    'product_price' => $productItem['prices'],
                    'discount_type' => 'in_percentage',
                    'discount_amount' => $productItem['discounts'],
                    'tax' => $productItem['taxes'],
                    'purchase_price' => $final_price_per_unit,
                    'slug' => $product_slug,
                    'updated_at' => now(),
                ]);
            } else {
                // Insert new record
                ProductPurchaseOrderProduct::create([
                    'product_purchase_order_id' => $order->id,
                    'product_id' => $product_id,
                    'product_warehouse_id' => $request->purchase_product_warehouse_id,
                    'product_warehouse_room_id' => $request->purchase_product_warehouse_room_id,
                    'product_warehouse_room_cartoon_id' => $request->purchase_product_warehouse_room_cartoon_id,
                    'product_supplier_id' => $request->supplier_id,
                    'product_name' => $productItem['name'],
                    'qty' => $productItem['quantities'],
                    'product_price' => $productItem['prices'],
                    'discount_type' => 'in_percentage',
                    'discount_amount' => $productItem['discounts'],
                    'tax' => $productItem['taxes'],
                    'purchase_price' => $final_price_per_unit,
                    'slug' => $product_slug,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Delete records that are not present in the request
        $recordsToDelete = array_diff($existingProductIds, $requestProductIds);

        if (!empty($recordsToDelete)) {
            ProductPurchaseOrderProduct::where('product_purchase_order_id', $order->id)
                ->whereIn('product_id', $recordsToDelete)
                ->delete();
        }

        Toastr::success('Updation Has been Successful', 'Success!');
        return redirect()->route('ViewAllPurchaseProductOrder');
    }

    

    public function editPurchaseProductOrderConfirm($slug)
    {
        $data = ProductPurchaseOrder::with('order_products')->where('slug', $slug)->first();

        if ($data->order_status == 'received') {
            Toastr::error('Order has been received already!', 'Error');
            return back();
        }

        $data->order_status = 'received';
        $data->save();

        $random_no = random_int(100, 999) . random_int(1000, 9999);
        $slug = Str::orderedUuid() . uniqid() . $random_no;

        // Insert records into ProductStock for each product in the order
        foreach ($data->order_products as $product) {
            $product_stock = new ProductStock();
            $product_stock->product_warehouse_id = $product->product_warehouse_id;
            $product_stock->product_warehouse_room_id = $product->product_warehouse_room_id;
            $product_stock->product_warehouse_room_cartoon_id = $product->product_warehouse_room_cartoon_id;
            $product_stock->product_supplier_id = $product->product_supplier_id;
            $product_stock->product_purchase_order_id = $product->product_purchase_order_id;
            $product_stock->product_id = $product->product_id;
            $product_stock->date = $data->date;
            $product_stock->qty = $product->qty;
            $product_stock->purchase_price = $product->purchase_price;
            $product_stock->status = 'active';
            $product_stock->slug = $slug;
            $product_stock->save();
        }


        Toastr::success('Order Confirmation Has been Successfull', 'Success!');
        return redirect()->route('ViewAllPurchaseProductOrder');
    }


    public function deletePurchaseProductOrder($slug)
    {
        $data = ProductPurchaseOrder::where('slug', $slug)->first();

        $data->delete();
        // $data->status = 'inactive';
        // $data->save();
        return response()->json([
            'success' => 'Deleted successfully!',
            'data' => 1
        ]);
    }


    public function searchProduct(Request $request)
    {
        // Check if the search query is an exact match
        $query = request()->query('query');
        $products = Product::where('name', 'LIKE', "%{$query}%")
            ->select('id', 'name', 'price', 'slug')
            ->limit(10)  // Limit to 200 products
            ->get();  // Use `get()` to return all matched products in a single request

        return response()->json($products);
    }
}
