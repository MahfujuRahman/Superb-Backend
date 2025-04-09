@extends('backend.master')

@section('page_title')
    Orders
@endsection
@section('page_heading')
    Orders Details
@endsection
@section('header_css')
    <style>
        @media print {
            .hidden-print{
                display: none !important;
            }
        }
        table tbody tr td{
            padding: 5px 10px !important
        }
        table thead tr th{
            padding: 5px 10px !important
        }
        address{
            font-size: 15px;
        }
        address h6{
            font-size: 15px;
        }
        .order_details_text p{
            font-size: 15px;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">

            <div class="card" id="printableArea">
                <div class="card-body">
                    <div class="row">
                        <div class="col text-left">
                            <h4 class="m-0">{{$generalInfo->company_name}}</h4>
                        </div>
                        <div class="col text-center">
                            @if(file_exists(public_path($generalInfo->logo_dark)))
                            <img src="{{url($generalInfo->logo_dark)}}" alt="" height="50">
                            @endif
                        </div>
                        <div class="col text-right">
                            <h4 class="m-0">Invoice</h4>
                        </div>
                    </div>


                    <div class="row mt-4">
                        <div class="col-6">

                            @if($shippingInfo)
                            <h6 class="font-weight-bold">Shipping Info :</h6>
                            <address class="line-h-24">
                                <b>{{$shippingInfo->full_name}}</b><br>
                                {{$shippingInfo->phone}}<br>
                                {{$shippingInfo->email}}<br>
                                {{$shippingInfo->address}}<br>
                                @if($shippingInfo->thana) Thana : {{$shippingInfo->thana}}<br> @endif
                                {{$shippingInfo->city}} {{$shippingInfo->post_code}}, {{$shippingInfo->country}}<br>
                            </address>
                            @endif

                        </div><!-- end col -->
                        <div class="col-6">
                            <div class="mt-3 float-right order_details_text">
                                <p class="mb-1"><strong>Order NO: </strong> #{{$order->order_no}}</p>
                                <p class="mb-1"><strong>Tran. ID: </strong> #{{$order->trx_id}}</p>
                                <p class="mb-1"><strong>Order Date: </strong> {{date("jS F, Y",strtotime($order->order_date))}}</p>
                                <p class="mb-1"><strong>Order Status: </strong>
                                    @php
                                        if($order->order_status == 0){
                                            echo '<span class="badge badge-soft-warning" style="padding: 2px 10px !important;">Pending</span>';
                                        } elseif($order->order_status == 1) {
                                            echo '<span class="badge badge-soft-info" style="padding: 2px 10px !important;">Approved</span>';
                                        } elseif($order->order_status == 2) {
                                            echo '<span class="badge badge-soft-info" style="padding: 2px 10px !important;">Intransit</span>';
                                        } elseif($order->order_status == 3) {
                                            echo '<span class="badge badge-soft-success" style="padding: 2px 10px !important;">Delivered</span>';
                                        } else {
                                            echo '<span class="badge badge-soft-danger" style="padding: 2px 10px !important;">Cancelled</span>';
                                        }
                                    @endphp
                                </p>
                                <p class="mb-1"><strong>Delivery Method: </strong>
                                    @php
                                        if($order->delivery_method == 1){
                                            echo '<span class="badge badge-soft-success" style="padding: 3px 5px !important;">Home Delivery</span>';
                                        }
                                        if($order->delivery_method == 2){
                                            echo '<span class="badge badge-soft-success" style="padding: 3px 5px !important;">Store Pickup</span>';
                                        }
                                    @endphp
                                </p>
                                <p class="mb-1"><strong>Payment Method: </strong>
                                    @php
                                        if($order->payment_method == NULL){
                                            echo '<span class="badge badge-soft-danger" style="padding: 2px 10px !important;">Unpaid</span>';
                                        } elseif($order->payment_method == 1) {
                                            echo '<span class="badge badge-soft-info" style="padding: 2px 10px !important;">COD</span>';
                                        } elseif($order->payment_method == 2) {
                                            echo '<span class="badge badge-soft-success" style="padding: 2px 10px !important;">bKash</span>';
                                        } elseif($order->payment_method == 3) {
                                            echo '<span class="badge badge-soft-success" style="padding: 2px 10px !important;">Nagad</span>';
                                        } else {
                                            echo '<span class="badge badge-soft-success" style="padding: 2px 10px !important;">Card</span>';
                                        }
                                    @endphp
                                </p>
                                <p class="mb-1"><strong>Payment Status: </strong>
                                    @php
                                        if($order->payment_status == 0){
                                            echo '<span class="badge badge-soft-warning" style="padding: 2px 10px !important;">Unpaid</span>';
                                        } elseif($order->payment_status == 1) {
                                            echo '<span class="badge badge-soft-success" style="padding: 2px 10px !important;">Paid</span>';
                                        } else {
                                            echo '<span class="badge badge-soft-danger" style="padding: 2px 10px !important;">Failed</span>';
                                        }
                                    @endphp
                                </p>
                                @if($order->reference_code)
                                <p class="mb-1"><strong>Reference: </strong>
                                    {{-- @php
                                        if($order->payment_status == 0){
                                            echo '<span class="badge badge-soft-warning" style="padding: 2px 10px !important;">Unpaid</span>';
                                        } elseif($order->payment_status == 1) {
                                            echo '<span class="badge badge-soft-success" style="padding: 2px 10px !important;">Paid</span>';
                                        } else {
                                            echo '<span class="badge badge-soft-danger" style="padding: 2px 10px !important;">Failed</span>';
                                        }
                                    @endphp --}}
                                    <span class="badge badge-soft-success" style="padding: 2px 10px !important;">{{ $order->reference_code }}</span>
                                </p>
                                @endif
                                @if($order->customer_src_type_id)
                                <p class="m-b-10"><strong>Customer Source Type: </strong>
                                    {{-- @php
                                        if($order->payment_status == 0){
                                            echo '<span class="badge badge-soft-warning" style="padding: 2px 10px !important;">Unpaid</span>';
                                        } elseif($order->payment_status == 1) {
                                            echo '<span class="badge badge-soft-success" style="padding: 2px 10px !important;">Paid</span>';
                                        } else {
                                            echo '<span class="badge badge-soft-danger" style="padding: 2px 10px !important;">Failed</span>';
                                        }
                                    @endphp --}}
                                    <span class="badge badge-soft-success" style="padding: 2px 10px !important;">{{ $order->customerSourceType->title }}</span>
                                </p>
                                @endif
                            </div>
                        </div><!-- end col -->
                    </div>
                    <!-- end row -->

                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered mt-4">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 60px;">SL</th>
                                            <th>Item</th>
                                            <th class="text-center">Variant</th>
                                            <th class="text-center">Quantity</th>
                                            <th class="text-center">Unit Cost</th>
                                            <th class="text-right">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $sl = 1;
                                        @endphp
                                        @foreach ($orderDetails as $details)

                                        @php
                                            if($details->color_id)
                                                $colorInfo = App\Models\Color::where('id', $details->color_id)->first();
                                            if($details->storage_id)
                                                $storageInfo = App\Models\StorageType::where('id', $details->storage_id)->first();
                                            if($details->sim_id)
                                                $simInfo = App\Models\Sim::where('id', $details->sim_id)->first();
                                            if($details->region_id)
                                                $regionInfo = DB::table('country')->where('id', $details->region_id)->first();
                                            if($details->warrenty_id)
                                                $warrentyInfo = App\Models\ProductWarrenty::where('id', $details->warrenty_id)->first();
                                            if($details->device_condition_id)
                                                $deviceCondition = App\Models\DeviceCondition::where('id', $details->device_condition_id)->first();
                                            if($details->size_id)
                                                $productSize = App\Models\ProductSize::where('id', $details->size_id)->first();
                                        @endphp

                                        <tr>
                                            <td class="text-center">{{$sl++}}</td>
                                            <td>
                                                <b>{{$details->product_name}}</b>
                                                <br/>
                                                Category : {{$details->category_name}},
                                                Warehouse : {{$details->warehouse_title}},
                                                Room : {{$details->warehouse_room_title}},
                                                Cartoon: {{$details->warehouse_room_cartoon_title}}
                                            </td>
                                            <td class="text-center">
                                                @if($details->color_id) Color: {{$colorInfo ? $colorInfo->name : ''}} | @endif
                                                @if($details->storage_id) Storage: {{$storageInfo ? $storageInfo->ram : ''}}/{{$storageInfo ? $storageInfo->rom : ''}} | @endif
                                                @if($details->sim_id) SIM: {{$simInfo ? $simInfo->name : ''}} @endif
                                                @if($details->size_id) Size: {{$productSize ? $productSize->name : ''}} @endif

                                                <br>
                                                @if($details->region_id) Region: {{$regionInfo ? $regionInfo->name : ''}} | @endif
                                                @if($details->warrenty_id) Warrenty: {{$warrentyInfo ? $warrentyInfo->name : ''}} | @endif
                                                @if($details->device_condition_id) Condition: {{$deviceCondition ? $deviceCondition->name : ''}} @endif
                                            </td>
                                            <td class="text-center">{{$details->qty}} {{$details->unit_name}}</td>
                                            <td class="text-center">৳ {{number_format($details->unit_price, 2)}}</td>
                                            <td class="text-right">৳ {{number_format($details->total_price, 2)}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="clearfix pt-3">
                                <h6 class="text-muted">Billing Address:</h6>
                                @if($billingAddress)
                                <address class="line-h-24">
                                    {{$billingAddress->address}}<br>
                                    {{$billingAddress->city}} {{$billingAddress->post_code}}, {{$billingAddress->country}}<br>
                                </address>
                                @endif
                            </div>

                            @if($userInfo)
                            <div class="clearfix pt-2">
                                <h6 class="text-muted">User Account Info:</h6>
                                <address class="line-h-24">
                                    {{$userInfo->name}}<br>
                                    @if($userInfo->email) {{$userInfo->email}}<br> @endif
                                    @if($userInfo->phone) {{$userInfo->phone}}<br> @endif
                                    @if($userInfo->address) {{$userInfo->address}} @endif
                                </address>
                            </div>
                            @endif

                            @if($order->order_note)
                            <div class="clearfix pt-2">
                                <h6 class="text-muted">Order note by Customer:</h6>
                                <p>
                                    {{$order->order_note}}
                                </p>
                            </div>
                            @endif

                        </div>
                        <div class="col-6 text-right">
                            <div class="float-right">
                                <p><b>Sub-total :</b> ৳ {{number_format($order->sub_total, 2)}}</p>
                                <p><b>Discount @if($order->coupon_code)({{$order->coupon_code}})@endif:</b> ৳ {{number_format($order->discount, 2)}}</p>
                                <p><b>VAT/TAX :</b> ৳ {{number_format($order->vat+$order->tax, 2)}}</p>
                                <p><b>Delivery Charge :</b> ৳ {{number_format($order->delivery_fee, 2)}}</p>
                                <h3><b>Total Order Amount :</b> ৳ {{number_format($order->total, 2)}}</h3>
                            </div>
                            <div class="clearfix"></div>

                            <div class="hidden-print mt-4 mb-4">
                                <div class="text-right">
                                    <a href="javascript:void(0);" onclick="printPageArea('printableArea')" class="btn btn-primary waves-effect waves-light"><i class="fa fa-print m-r-5"></i> Print Invoice</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card p-3">
                <form action="{{url('order/info/update')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="order_id" value="{{$order->id}}">
                    <div class="row">
                        <div class="col-lg-10">
                            <div class="form-group" style="margin-bottom: 0px">
                                <label style="margin-bottom: .2rem; font-weight: 500;">Special Note For Order (Visible by Admin Only) :</label>
                                <textarea name="order_remarks" class="form-control" style="height: 149px !important;" placeholder="Special Note By Admin">{{$order->order_remarks}}</textarea>
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group" style="margin-bottom: .5rem;">
                                <label style="margin-bottom: .2rem; font-weight: 500;">Est. Delivery Date :</label>
                                <input type="date" class="form-control" name="estimated_dd" value="{{$order->estimated_dd}}" required>
                            </div>

                            <div class="form-group" style="margin-bottom: .5rem;">
                                <label style="margin-bottom: .2rem; font-weight: 500;">Order Status :</label>
                                <select name="order_status" class="form-control" required>
                                    <option value="">Change Status</option>
                                    <option value="0" @if($order->order_status == 0) selected @endif @if($order->order_status == 1 || $order->order_status == 2 || $order->order_status == 3 || $order->order_status == 4) disabled @endif>Pending</option>
                                    <option value="1" @if($order->order_status == 1) selected @endif @if($order->order_status == 2 || $order->order_status == 3 || $order->order_status == 4) disabled @endif>Approved</option>
                                    <option value="2" @if($order->order_status == 2) selected @endif @if($order->order_status == 0 || $order->order_status == 3 || $order->order_status == 4) disabled @endif>Intransit</option>
                                    <option value="3" @if($order->order_status == 3) selected @endif @if($order->order_status == 1 || $order->order_status == 0 || $order->order_status == 4) disabled @endif>Delivered</option>
                                    <option value="4" @if($order->order_status == 4) selected @endif @if($order->order_status == 2 || $order->order_status == 3) disabled @endif>Cancel</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success rounded w-100 mt-1">Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>

        </div> <!-- end col -->
    </div>
@endsection

@section("footer_js")
    <script>
        function printPageArea(areaID){
            var printContent = document.getElementById(areaID).innerHTML;
            var originalContent = document.body.innerHTML;
            document.body.innerHTML = printContent;
            window.print();
            document.body.innerHTML = originalContent;
        }
    </script>
@endsection