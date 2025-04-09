<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Inventory\Models\ProductWarehouse;
use App\Http\Controllers\Inventory\Models\ProductWarehouseRoom;
use App\Http\Controllers\Inventory\Models\ProductWarehouseRoomCartoon;
use App\Http\Controllers\Outlet\Models\CustomerSourceType;
use App\Http\Controllers\Outlet\Models\Outlet;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderPlacedEmail;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use App\Models\BillingAddress;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\OrderPayment;
use App\Models\OrderProgress;
use App\Models\ShippingInfo;

class PosController extends Controller
{
    public function createNewOrder(){
        $categories = Category::where('status', 1)->orderBy('name', 'asc')->get();
        $brands = Brand::where('status', 1)->orderBy('name', 'asc')->get();
        $products = Product::where('status', 1)->orderBy('name', 'asc')->get();
        $customers = User::where('user_type', 3)->orderBy('name', 'asc')->get();
        $districts = DB::table('districts')->orderBy('name', 'asc')->get();

        $customer_source_types = CustomerSourceType::where('status', 'active')->get();
        $outlets = Outlet::where('status', 'active')->get();
        $warehouses = ProductWarehouse::where('status', 'active')->get();
        $warehouse_rooms = ProductWarehouseRoom::where('status', 'active')->get();
        $room_cartoons = ProductWarehouseRoomCartoon::where('status', 'active')->get();

        return view('backend.orders.pos.create', compact('categories', 'brands', 'products', 'customers', 'districts', 'customer_source_types', 'outlets', 'warehouses', 'warehouse_rooms', 'room_cartoons'));
    }

    public function productLiveSearch(Request $request){

        $query = Product::where('status', 1);

        if ($request->product_name) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->product_name . '%')
                  ->orWhere('code', 'LIKE', '%' . $request->product_name . '%');
            });
        }

        if($request->category_id){
            // $query->where('category_id', $request->category_id);
            $query->whereRaw("FIND_IN_SET(?, category_id)", [$request->category_id]);
        }
        if($request->brand_id){
            $query->where('brand_id', $request->brand_id);
        }
        $products = $query->orderBy('name', 'asc')->get();

        $searchResults = view('backend.orders.pos.live_search_products', compact('products'))->render();
        return response()->json(['searchResults' => $searchResults]);
    }

    public function getProductVariants(Request $request){

        $product = Product::where('id', $request->product_id)->first();

        $colors = DB::table('product_variants')
                ->leftJoin('colors', 'product_variants.color_id', 'colors.id')
                ->select('colors.*')
                ->where('product_variants.product_id', $product->id)
                ->where('product_variants.stock', '>', 0)
                ->groupBy('product_variants.color_id')
                ->get();

        $sizes = DB::table('product_variants')
                ->leftJoin('product_sizes', 'product_variants.size_id', 'product_sizes.id')
                ->select('product_sizes.*')
                ->where('product_variants.product_id', $product->id)
                ->where('product_variants.stock', '>', 0)
                ->groupBy('product_variants.size_id')
                ->get();

        return response()->json([
            'product' => $product,
            'colors' => $colors,
            'sizes' => $sizes,
        ]);

    }

    public function checkProductVariant(Request $request){

        $query = DB::table('product_variants')->where('product_id', $request->product_id);
        if($request->color_id != ''){
            $query->where('color_id', $request->color_id);
        }
        if($request->size_id != ''){
            $query->where('size_id', $request->size_id);
        }

        $data = $query->where('stock', '>', 0)->orderBy('discounted_price', 'asc')->orderBy('price', 'asc')->first();
        if($data){
            return response()->json([
                'price' => $data->discounted_price > 0 ? $data->discounted_price : $data->price,
                'stock' => $data->stock
            ]);

        }else {
            return response()->json(['price' => 0, 'stock' => 0]);
        }

    }

    public function addToCart(Request $request){

        // dd(request()->all());

        if($request->color_id > 0 || $request->size_id > 0){

            $query = DB::table('product_variants')
                            ->leftJoin('products', 'product_variants.product_id', 'products.id')
                            ->leftJoin('colors', 'product_variants.color_id', 'colors.id')
                            ->leftJoin('product_sizes', 'product_variants.size_id', 'product_sizes.id')
                            ->select('product_variants.*', 'products.image as product_image', 'products.code as product_code', 'products.name as product_name', 'colors.name as color_name', 'product_sizes.name as size_name');

            if($request->color_id > 0){
                $query->where('product_variants.color_id', $request->color_id);
            }
            if($request->size_id > 0){
                $query->where('product_variants.size_id', $request->size_id);
            }

            $productInfo = $query->where('product_variants.product_id', $request->product_id)->first();

            $cart = session()->get('cart', []);
            $productKey = $request->product_id."_".$request->color_id."_".$request->size_id."_".$request->purchase_product_warehouse_id."_".$request->purchase_product_warehouse_room_id."_".$request->purchase_product_warehouse_room_cartoon_id;

            if(isset($cart[$productKey])) {
                $cart[$productKey]['quantity']++;
            } else {
                $cart[$productKey] = [
                    "product_id" => $productInfo->product_id,
                    "code" => $productInfo->product_code,
                    "name" => $productInfo->product_name,
                    "quantity" => 1,
                    "price" => $productInfo->discounted_price > 0 ? $productInfo->discounted_price : $productInfo->price,
                    "image" => $productInfo->product_image,
                    "color_id" => $request->color_id,
                    "color_name" => $productInfo->color_name,
                    "size_id" => $request->size_id,
                    "size_name" => $productInfo->size_name,
                    "purchase_product_warehouse_id" => request()->purchase_product_warehouse_id,
                    "purchase_product_warehouse_room_id" => request()->purchase_product_warehouse_room_id,
                    "purchase_product_warehouse_room_cartoon_id" => request()->purchase_product_warehouse_room_cartoon_id,
                ];
            }

        } else {
            $productInfo = Product::where('id', $request->product_id)->first();

            $cart = session()->get('cart', []);
            $productKey = $request->product_id."_".$request->color_id."_".$request->size_id."_".$request->purchase_product_warehouse_id."_".$request->purchase_product_warehouse_room_id."_".$request->purchase_product_warehouse_room_cartoon_id;
            if(isset($cart[$productKey])) {
                $cart[$productKey]['quantity']++;
            } else {
                $cart[$productKey] = [
                    "product_id" => $productInfo->id,
                    "code" => $productInfo->code,
                    "name" => $productInfo->name,
                    "quantity" => 1,
                    "price" => $productInfo->discount_price > 0 ? $productInfo->discount_price : $productInfo->price,
                    "image" => $productInfo->image,
                    "color_id" => null,
                    "color_name" => null,
                    "size_id" => null,
                    "size_name" => null,
                    "purchase_product_warehouse_id" => request()->purchase_product_warehouse_id,
                    "purchase_product_warehouse_room_id" => request()->purchase_product_warehouse_room_id,
                    "purchase_product_warehouse_room_cartoon_id" => request()->purchase_product_warehouse_room_cartoon_id,
                ];
            }
        }

        session()->put('cart', $cart);

        $returnHTML = view('backend.orders.pos.cart_items')->render();
        $cartCalculationHTML = view('backend.orders.pos.cart_calculation')->render();
        return response()->json([
            'rendered_cart' => $returnHTML,
            'cart_calculation' => $cartCalculationHTML,
        ]);

    }

    public function getSavedAddress($user_id){
        $savedAddressed = DB::table('user_addresses')
        ->where('user_id', $user_id)
        ->get();

        $userInfo = User::where('id', $user_id)->first();
        $savedAddressHTML = view('backend.orders.pos.saved_address', compact('savedAddressed'))->render();
        return response()->json([
            'saved_address' => $savedAddressHTML,
            'user_info' => $userInfo
        ]);
    }

    public function removeCartItem($cartIndex){
        $cart = session()->get('cart');
        if(isset($cart[$cartIndex])) {
            unset($cart[$cartIndex]);
            session()->put('cart', $cart);
        }

        // removing discount because some coupon code have minimum order value
        session(['pos_discount' => 0]);

        $returnHTML = view('backend.orders.pos.cart_items')->render();
        $cartCalculationHTML = view('backend.orders.pos.cart_calculation')->render();
        return response()->json([
            'rendered_cart' => $returnHTML,
            'cart_calculation' => $cartCalculationHTML,
        ]);
    }

    public function updateCartItem($cartIndex, $qty){
        $cart = session()->get('cart');
        if(isset($cart[$cartIndex])) {
            $cart[$cartIndex]['quantity'] = $qty;
            session()->put('cart', $cart);
        }

        // removing discount because some coupon code have minimum order value
        session(['pos_discount' => 0]);

        $returnHTML = view('backend.orders.pos.cart_items')->render();
        $cartCalculationHTML = view('backend.orders.pos.cart_calculation')->render();
        return response()->json([
            'rendered_cart' => $returnHTML,
            'cart_calculation' => $cartCalculationHTML,
        ]);
    }

    public function applyCoupon(Request $request){
        $couponCode = $request->coupon_code;
        $couponInfo = DB::table('promo_codes')->where('code', $couponCode)->first();
        if($couponInfo){

            if($couponInfo->effective_date && $couponInfo->effective_date > date("Y-m-d")){
                return response()->json([
                    'status' => 0,
                    'message' => "Coupon is not Applicable"
                ]);
            }

            if($couponInfo->expire_date && $couponInfo->expire_date < date("Y-m-d")){
                return response()->json([
                    'status' => 0,
                    'message' => "Coupon is Expired"
                ]);
            }

            $subTotal = 0;
            foreach((array) session('cart') as $id => $details){
                $subTotal += $details['price'] * $details['quantity'];
            }

            if($couponInfo->minimum_order_amount && $couponInfo->minimum_order_amount > $subTotal){
                return response()->json([
                    'status' => 0,
                    'message' => "Minimum Amount is not Matched"
                ]);
            }

            $discount = 0;
            if($couponInfo->type == 1){
                $discount = $couponInfo->value;
            } else {
                $discount = ($subTotal*$couponInfo->value)/100;
            }

            if($discount > $subTotal){
                return response()->json([
                    'status' => 0,
                    'message' => "Discount Cannot be greater than Order Amount"
                ]);
            }

            session([
                'coupon' => $couponCode,
                'pos_discount' => $discount
            ]);
            $cartCalculationHTML = view('backend.orders.pos.cart_calculation')->render();
            return response()->json([
                'status' => 1,
                'message' => "Coupon Applied",
                'cart_calculation' => $cartCalculationHTML
            ]);

        } else {
            return response()->json([
                'status' => 0,
                'message' => "Coupon Not Found"
            ]);
        }
    }

    public function saveNewCustomer(Request $request){
        User::insert([
            'name' => $request->customer_name,
            'phone' => $request->customer_phone,
            'email' => $request->customer_email,
            'email_verified_at' => Carbon::now(),
            'verification_code' => 000000,
            'password' => Hash::make($request->password),
            'user_type' => 3,
            'balance' => 0,
            'status' => 1,
            'created_at' => Carbon::now()
        ]);

        Toastr::success('New Customer Created', 'Success');
        return back();
    }

    public function updateOrderTotal($shipping_charge, $discount){
        $shipping_charge = is_numeric($shipping_charge) ? $shipping_charge : 0;
        $discount = is_numeric($discount) ? $discount : 0;

        session(['shipping_charge' => $shipping_charge]);
        session(['pos_discount' => $discount]);
        $cartCalculationHTML = view('backend.orders.pos.cart_calculation')->render();
        return response()->json([
            'cart_calculation' => $cartCalculationHTML
        ]);
    }

    public function districtWiseThana(Request $request){

        $districtWiseDeliveryCharge = 0;
        $districtInfo = DB::table('districts')->where('id', $request->district_id)->first();
        if($districtInfo){
            $districtWiseDeliveryCharge = $districtInfo->delivery_charge;
        }

        session(['shipping_charge' => $districtWiseDeliveryCharge]);

        $data = DB::table('upazilas')->where("district_id", $request->district_id)->select('name', 'id')->orderBy('name', 'asc')->get();
        $cartCalculationHTML = view('backend.orders.pos.cart_calculation')->render();
        return response()->json([
            'data' => $data,
            'cart_calculation' => $cartCalculationHTML
        ]);
    }

    public function districtWiseThanaByName(Request $request){

        $districtWiseDeliveryCharge = 0;
        $districtInfo = DB::table('districts')->where('name', $request->district_id)->first();
        if($districtInfo){
            $districtWiseDeliveryCharge = $districtInfo->delivery_charge;
        }

        session(['shipping_charge' => $districtWiseDeliveryCharge]);

        $data = DB::table('upazilas')
        ->leftJoin('districts', 'upazilas.district_id', 'districts.id')
        ->where("districts.name", $request->district_id)
        ->select('upazilas.name', 'upazilas.id')
        ->orderBy('upazilas.name', 'asc')
        ->get();

        $cartCalculationHTML = view('backend.orders.pos.cart_calculation')->render();
        return response()->json([
            'data' => $data,
            'cart_calculation' => $cartCalculationHTML
        ]);
    }

    public function changeDeliveryMethod(Request $request){
        if($request->delivery_method == 1){
            session(['shipping_charge' => 0]);
            $cartCalculationHTML = view('backend.orders.pos.cart_calculation')->render();
            return response()->json([
                'cart_calculation' => $cartCalculationHTML
            ]);
        } else {
            $districtWiseDeliveryCharge = 0;
            $districtInfo = DB::table('districts')->where('id', $request->shipping_district_id)->first();
            if($districtInfo){
                $districtWiseDeliveryCharge = $districtInfo->delivery_charge;
            }
            session(['shipping_charge' => $districtWiseDeliveryCharge]);
            $cartCalculationHTML = view('backend.orders.pos.cart_calculation')->render();
            return response()->json([
                'cart_calculation' => $cartCalculationHTML
            ]);
        }
    }

    public function saveCustomerAddress(Request $request){
        UserAddress::insert([
            'user_id' => $request->customer_id,
            'address_type' => $request->address_type,
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'post_code' => $request->post_code,
            'country' => 'Bangladesh',
            'city' => $request->customer_address_district_id,
            'state' => $request->customer_address_thana_id,
            'slug' => time().rand(999999,100000),
            'created_at' => Carbon::now()
        ]);

        Toastr::success('New Address Added', 'Success');
        return back();
    }

    public function placeOrder(Request $request){

        // dd(request()->all());

        if(!session('cart') || (session('cart') && count(session('cart')) <= 0)){
            Toastr::error('No Products Found in Cart', 'Failed to Place Order');
            return back();
        }

        date_default_timezone_set("Asia/Dhaka");
        $total = 0;
        foreach((array) session('cart') as $details){
            $total += $details['price'] * $details['quantity'];
        }

        $discount = $request->discount ? $request->discount : 0;
        $deliveryCost = $request->shipping_charge ? $request->shipping_charge : 0;
        $couponCode = session('coupon') ? session('coupon') : null;

        $orderId = DB::table('orders')->insertGetId([
            'order_no' => date("ymd").DB::table('orders')->where('order_date', 'LIKE', date("Y-m-d").'%')->count()+1,
            'order_from' => 3, //pos order
            'user_id' => $request->customer_id ? $request->customer_id : null,
            'order_date' => date("Y-m-d H:i:s"),
            'estimated_dd' => date('Y-m-d', strtotime("+7 day", strtotime(date("Y-m-d")))),
            'delivery_method' => $request->delivery_method,
            'payment_method' => 1,
            'payment_status' => 0,
            'trx_id' => time().str::random(5),
            'order_status' => 1,
            'sub_total' => $total,
            'coupon_code' => $couponCode,
            'discount' => $discount,
            'delivery_fee' => $request->delivery_method == 2 ? $deliveryCost : 0,
            'vat' => 0,
            'tax' => 0,
            'total' => $total+$deliveryCost-$discount,
            'order_note' => $request->special_note,
            // 'warehouse_id' => request()->purchase_product_warehouse_id,
            // 'room_id' => request()->purchase_product_warehouse_room_id,
            // 'cartoon_id' => request()->purchase_product_warehouse_room_cartoon_id,
            'customer_src_type_id' => request()->customer_source_type_id,
            'outlet_id' => request()->outlet_id,
            'reference_code' => request()->reference_code ?? '',
            'complete_order' => 1,
            'slug' => str::random(5) . time(),
            'created_at' => Carbon::now()
        ]);

        DB::table('order_progress')->insert([
            'order_id' => $orderId,
            'order_status' => 0,
            'created_at' => Carbon::now()
        ]);

        DB::table('order_progress')->insert([
            'order_id' => $orderId,
            'order_status' => 1,
            'created_at' => Carbon::now()
        ]);

        $totalRewardPointsEarned = 0;
        foreach(session('cart') as $details){

            $product = DB::table('products')->where('id', $details['product_id'])->first();
            $totalRewardPointsEarned = $totalRewardPointsEarned + $product->reward_points;

            // decrement the stock
            if($details['color_id'] || $details['size_id']){
                $productInfo = DB::table('product_variants')
                                ->where('product_id', $details['product_id'])
                                ->where('size_id', $details['size_id'])
                                ->where('color_id', $details['color_id'])
                                ->first();

                DB::table('product_variants')
                ->where('product_id', $details['product_id'])
                ->where('size_id', $details['size_id'])
                ->where('color_id', $details['color_id'])->update([
                    'stock' => $productInfo->stock - $details['quantity'],
                ]);
            } else {
                DB::table('products')->where('id', $details['product_id'])->update([
                    'stock' => $product->stock - $details['quantity'],
                ]);
            }

            DB::table('order_details')->insert([
                'order_id' => $orderId,
                'product_id' => $details['product_id'],
                'store_id' => $product->store_id,

                // VARIANT
                'color_id' => $details['color_id'],
                'size_id' => $details['size_id'],
                'region_id' => null,
                'sim_id' => null,
                'storage_id' => null,
                'warrenty_id' => null,
                'device_condition_id' => null,

                'warehouse_id' => $details['purchase_product_warehouse_id'],
                'warehouse_room_id' => $details['purchase_product_warehouse_room_id'],
                'warehouse_room_cartoon_id' => $details['purchase_product_warehouse_room_cartoon_id'],

            // 'warehouse_id' => request()->purchase_product_warehouse_id,
            // 'room_id' => request()->purchase_product_warehouse_room_id,
            // 'cartoon_id' => request()->purchase_product_warehouse_room_cartoon_id,

                'reward_points' => $product->reward_points,
                'qty' => $details['quantity'],
                'unit_id' => $product->unit_id,
                'unit_price' => $details['price'],
                'total_price' => $details['price'] * $details['quantity'],
                'created_at' => Carbon::now()
            ]);
        }

        if($request->customer_id && $totalRewardPointsEarned > 0){
            $userInfo = User::where('id', $request->customer_id)->first();
            $userInfo->balance = $userInfo->balance + $totalRewardPointsEarned;
            $userInfo->save();
        }

        $shippingDistrictInfo = DB::table('districts')->where('id', $request->shipping_district_id)->first();
        $shippingThanaInfo = DB::table('upazilas')->where('id', $request->shipping_thana_id)->first();
        DB::table('shipping_infos')->insert([
            'order_id' => $orderId,
            'full_name' => $request->shipping_name,
            'phone' => $request->shipping_phone,
            'email' => $request->shipping_email,
            'gender' => null,
            'address' => $request->shipping_address,
            'thana' => $shippingThanaInfo ? $shippingThanaInfo->name : null,
            'post_code' => $request->shipping_postal_code,
            'city' => $shippingDistrictInfo ? $shippingDistrictInfo->name : null,
            'country' => 'Bangladesh',
            'created_at' => Carbon::now()
        ]);

        $billingDistrictInfo = DB::table('districts')->where('id', $request->billing_district_id)->first();
        $billingThanaInfo = DB::table('upazilas')->where('id', $request->billing_thana_id)->first();

        DB::table('billing_addresses')->insert([
            'order_id' => $orderId,
            'address' => $request->billing_address ? $request->billing_address : $request->shipping_address,
            'post_code' => $request->billing_postal_code ? $request->billing_postal_code : $request->shipping_postal_code,
            'city' => $billingDistrictInfo ? $billingDistrictInfo->name : ($shippingDistrictInfo ? $shippingDistrictInfo->name : null),
            'thana' => $billingThanaInfo ? $billingThanaInfo->name : ($shippingThanaInfo ? $shippingThanaInfo->name : null),
            'country' => 'Bangladesh',
            'created_at' => Carbon::now()
        ]);

        if($request->shipping_email && !DB::table('subscribed_users')->where('email', $request->shipping_email)->exists()){
            DB::table('subscribed_users')->insert([
                'email' => $request->shipping_email,
                'created_at' => Carbon::now()
            ]);
        }

        $orderInfo = DB::table('orders')->where('id', $orderId)->first();
        DB::table('order_payments')->insert([
            'order_id' => $orderId,
            'payment_through' => "COD",
            'tran_id' => $orderInfo->trx_id,
            'val_id' => NULL,
            'amount' => $orderInfo->total,
            'card_type' => NULL,
            'store_amount' => $orderInfo->total,
            'card_no' => NULL,
            'status' => "VALID",
            'tran_date' => date("Y-m-d H:i:s"),
            'currency' => "BDT",
            'card_issuer' => NULL,
            'card_brand' => NULL,
            'card_sub_brand' => NULL,
            'card_issuer_country' => NULL,
            'created_at' => Carbon::now()
        ]);


        // sending order sms start
        if($request->shipping_phone && env('APP_ENV') != 'local'){

            try {

                $orderSmsString = "Dear Customer, Your Order #".$orderInfo->order_no." placed successfully at ".env('APP_NAME').". Total amount: ".$orderInfo->total."TK. Expected delivery within 3-7 days.";

                $smsGateway = DB::table('sms_gateways')->where('status', 1)->first();
                if($smsGateway && $smsGateway->provider_name == 'Reve'){
                    Http::get($smsGateway->api_endpoint, [
                        'apikey' => $smsGateway->api_key,
                        'secretkey' => $smsGateway->secret_key,
                        "callerID" => $smsGateway->sender_id,
                        "toUser" => $request->shipping_phone,
                        "messageContent" => $orderSmsString
                    ]);
                }
                if($smsGateway && $smsGateway->provider_name == 'KhudeBarta'){
                    Http::get($smsGateway->api_endpoint, [
                        'apikey' => $smsGateway->api_key,
                        'secretkey' => $smsGateway->secret_key,
                        "callerID" => $smsGateway->sender_id,
                        "toUser" => $this->formatBangladeshiPhoneNumber($request->shipping_phone),
                        "messageContent" => $orderSmsString
                    ]);
                }
                if($smsGateway && $smsGateway->provider_name == 'ElitBuzz'){
                    Http::get($smsGateway->api_endpoint, [
                        'api_key' => $smsGateway->api_key,
                        "type" => "text",
                        "contacts" => $request->shipping_phone,
                        "senderid" => $smsGateway->sender_id,
                        "msg" => $orderSmsString
                    ]);
                }

            } catch(\Exception $e) {
                // write code for handling error from here
            }
        }
        // sending order sms end

        // sending order email
        try {
            $emailConfig = DB::table('email_configures')->where('status', 1)->orderBy('id', 'desc')->first();
            $userEmail = $request->shipping_email;

            if($emailConfig && $userEmail && env('APP_ENV') != 'local'){
                $decryption = "";
                if($emailConfig){

                    $ciphering = "AES-128-CTR";
                    $options = 0;
                    $decryption_iv = '1234567891011121';
                    $decryption_key = "GenericCommerceV1";
                    $decryption = openssl_decrypt ($emailConfig->password, $ciphering, $decryption_key, $options, $decryption_iv);

                    config([
                        'mail.mailers.smtp.host' => $emailConfig->host,
                        'mail.mailers.smtp.port' => $emailConfig->port,
                        'mail.mailers.smtp.username' => $emailConfig->email,
                        'mail.mailers.smtp.password' => $decryption != "" ? $decryption : '',
                        'mail.mailers.smtp.encryption' => $emailConfig ? ($emailConfig->encryption == 1 ? 'tls' : ($emailConfig->encryption == 2 ? 'ssl' : '')) : '',
                    ]);

                    Mail::to(trim($userEmail))->send(new OrderPlacedEmail($orderInfo));
                }
            }

        } catch(\Exception $e) {
            // write code for handling error from here
        }
        // sending order email done

        session()->forget('coupon');
        session()->forget('pos_discount');
        session()->forget('shipping_charge');
        session()->forget('cart');

        Toastr::success('Order Placed Successfully', 'Success');
        return back();

    }


    // public function editPlaceOrder($slug) {
    //     // $data = Order::where('slug', $slug)->where('status', 'active')->first();

    //     $order = Order::where('slug', $slug)->first();
    //     $userInfo = User::where('id', $order->user_id)->first();
    //     $shippingInfo = ShippingInfo::where('order_id', $order->id)->first();
    //     $billingAddress = BillingAddress::where('order_id', $order->id)->first();
    //     $orderDetails = DB::table('order_details')
    //                         ->leftJoin('products', 'order_details.product_id', 'products.id')
    //                         ->leftJoin('categories', 'products.category_id', 'categories.id')
    //                         ->leftJoin('units', 'order_details.unit_id', 'units.id')
    //                         ->select('order_details.*', 'products.name as product_name', 'units.name as unit_name', 'categories.name as category_name')
    //                         ->where('order_id', $order->id)
    //                         ->get();
    //     $generalInfo = DB::table('general_infos')->select('logo', 'logo_dark', 'company_name')->first();

    //     $customer_source_types = CustomerSourceType::where('status', 'active')->get();
    //     $outlets = Outlet::where('status', 'active')->get();
    //     $warehouses = ProductWarehouse::where('status', 'active')->get();
    //     $warehouse_rooms = ProductWarehouseRoom::where('status', 'active')->get();
    //     $room_cartoons = ProductWarehouseRoomCartoon::where('status', 'active')->get();

    //     return view('backend.orders.pos.edit', compact('order', 'shippingInfo', 'billingAddress', 'orderDetails', 'userInfo', 'generalInfo', 'customer_source_types', 'outlets', 'warehouses', 'warehouse_rooms', 'room_cartoons'));
    // }

    public function formatBangladeshiPhoneNumber($phoneNumber) {
        // Remove any non-numeric characters from the phone number
        $phoneNumber = preg_replace('/\D/', '', $phoneNumber);

        // Check if the number starts with '88'
        if (substr($phoneNumber, 0, 2) !== '88') {
            // If not, prepend '88' to the number
            $phoneNumber = '88' . $phoneNumber;
        }

        return $phoneNumber;
    }

}