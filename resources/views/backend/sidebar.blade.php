<!-- Left Menu Start -->
<ul class="metismenu list-unstyled" id="side-menu">
    <li>
        <a href="{{ url('/home') }}">
            <i class="feather-home"></i>
            <span> Ecommerce Dashboard</span>
        </a>
    </li>
    <li>
        <a href="{{ url('/crm-home') }}">
            <i class="feather-home"></i>
            <span> CRM Dashboard</span>
        </a>
    </li>
    <li>
        <a href="{{ url('/accounts-home') }}">
            <i class="feather-home"></i>
            <span> Accounts Dashboard</span>
        </a>
    </li>

    <hr style="border-color: #c8c8c836; margin-top: 12px; margin-bottom: 5px;">
    <li class="menu-title" style="color: khaki; text-shadow: 1px 1px 2px black;">Website Config</li>
    <li><a href="{{ url('/general/info') }}"><i class="feather-grid"></i><span>General Info</span></a></li>
    <li><a href="{{ url('/website/theme/page') }}"><i class="mdi mdi-format-color-fill"
                style="font-size: 18px"></i><span>Website Theme Color</span></a></li>
    <li><a href="{{ url('/social/media/page') }}"><i class="mdi mdi-link-variant"
                style="font-size: 17px"></i><span>Social Media Links</span></a></li>
    <li><a href="{{ url('/seo/homepage') }}"><i class="dripicons-search"></i><span>Home Page SEO</span></a></li>
    <li><a href="{{ url('/custom/css/js') }}"><i class="feather-code"></i><span>Custom CSS & JS</span></a></li>
    <li><a href="{{ url('/social/chat/script/page') }}"><i class="mdi mdi-code-brackets"></i><span>Social & Chat Scripts</span></a></li>


    <hr style="border-color: #c8c8c836; margin-top: 12px; margin-bottom: 12px;">
    <li class="menu-title" style="color: khaki; text-shadow: 1px 1px 2px black;">E-commerce Modules</li>
    <li>
        <a href="javascript: void(0);" class="has-arrow"><i class="feather-settings"></i><span>Config</span></a>
        <ul class="sub-menu" aria-expanded="false">

            <li><a href="{{ url('/config/setup') }}">Setup Your Config</a></li>

            {{-- Fshion Insdustry --}}
            @if(DB::table('config_setups')->where('code', 'product_size')->first())
            <li><a href="{{ url('/view/all/sizes') }}">Product Sizes</a></li>
            @endif

            {{-- tech industry --}}
            @if(DB::table('config_setups')->where('code', 'storage')->first())
            <li><a href="{{ url('/view/all/storages') }}">Storage</a></li>
            @endif
            @if(DB::table('config_setups')->where('code', 'sim')->first())
            <li><a href="{{ url('/view/all/sims') }}">SIM Type</a></li>
            @endif
            @if(DB::table('config_setups')->where('code', 'device_condition')->first())
            <li><a href="{{ url('/view/all/device/conditions') }}">Device Condition</a></li>
            @endif
            @if(DB::table('config_setups')->where('code', 'product_warranty')->first())
            <li><a href="{{ url('/view/all/warrenties') }}">Product Warrenty</a></li>
            @endif

            {{-- common --}}
            @if(DB::table('config_setups')->where('code', 'color')->first())
            <li><a href="{{ url('/view/all/colors') }}">Product Colors</a></li>
            @endif
            @if(DB::table('config_setups')->where('code', 'measurement_unit')->first())
            <li><a href="{{ url('/view/all/units') }}">Measurement Units</a></li>
            @endif


            <li><a href="{{ url('/view/all/brands') }}">Product Brands</a></li>
            <li><a href="{{ url('/view/all/models') }}">Models of Brand</a></li>
            <li><a href="{{ url('/view/all/flags') }}">Product Flags</a></li>

        </ul>
    </li>
    <li>
        <a href="javascript: void(0);" class="has-arrow"><i class="feather-settings"></i><span>CRM</span></a>
        <ul class="sub-menu" aria-expanded="false">
            <li>
                <a href="javascript: void(0);" class="has-arrow"><i class="feather-box"></i><span>Customer Src Type</span></a>
                <ul class="sub-menu" aria-expanded="false">
                    <li><a href="{{ url('/add/new/customer-source') }}">Add Type</a></li>
                    <li>
                        <a href="{{ url('/view/all/customer-source') }}">
                            All Source Types
                            <span style="color:lightgreen" title="Total CS Types">
                                ({{DB::table('customer_source_types')->count()}})
                            </span>
                        </a>
                    </li>
                   
                </ul>
            </li>
            <li>
                <a href="javascript: void(0);" class="has-arrow"><i class="feather-box"></i><span>Customer Category</span></a>
                <ul class="sub-menu" aria-expanded="false">
                    <li><a href="{{ url('/add/new/customer-category') }}">Add Category</a></li>
                    <li>
                        <a href="{{ url('/view/all/customer-category') }}">
                            All Categories
                            <span style="color:lightgreen" title="Total Categories">
                                ({{DB::table('customer_categories')->count()}})
                            </span>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript: void(0);" class="has-arrow"><i class="feather-box"></i><span>Customer</span></a>
                <ul class="sub-menu" aria-expanded="false">
                    <li><a href="{{ url('/add/new/customers') }}">Add Customer</a></li>
                    <li>
                        <a href="{{ url('/view/all/customer') }}">
                            All Customers
                            <span style="color:lightgreen" title="Total Customers">
                                ({{DB::table('customers')->count()}})
                            </span>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript: void(0);" class="has-arrow"><i class="feather-box"></i><span>Contact History</span></a>
                <ul class="sub-menu" aria-expanded="false">
                    <li><a href="{{ url('/add/new/customer-contact-history') }}">Add Contact</a></li>
                    <li>
                        <a href="{{ route('ViewAllCustomerContactHistories') }}">
                            All Contacts
                            <span style="color:lightgreen" title="Total Contact Histories">
                                ({{DB::table('customer_contact_histories')->count()}})
                            </span>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript: void(0);" class="has-arrow"><i class="feather-box"></i><span>Ecom Customer</span></a>
                <ul class="sub-menu" aria-expanded="false">
                    <li><a href="{{ url('/add/new/customer-ecommerce') }}">Add Customer</a></li>
                    <li>
                        <a href="{{ route('ViewAllCustomerEcommerce') }}">
                            All Customer
                            <span style="color:lightgreen" title="Total Contact Histories">
                                ({{DB::table('users')->where('user_type', 3)->count()}})
                            </span>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript: void(0);" class="has-arrow"><i class="feather-box"></i><span>Next Date Contact</span></a>
                <ul class="sub-menu" aria-expanded="false">
                    <li><a href="{{ url('/add/new/customer-next-contact-date') }}">Add Next Date</a></li>
                    <li>
                        <a href="{{ url('/view/all/customer-next-contact-date') }}">
                            All Next Contacts
                            <span style="color:lightgreen" title="Total Contact Histories">
                                ({{DB::table('customer_next_contact_dates')->count()}})
                            </span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </li>

    <li>
        <a href="javascript: void(0);" class="has-arrow"><i class="feather-settings"></i><span>Accounts</span></a>
        <ul class="sub-menu" aria-expanded="false">
            <li>
                <a href="javascript: void(0);" class="has-arrow"><i class="feather-box"></i><span>Payment Type</span></a>
                <ul class="sub-menu" aria-expanded="false">
                    <li><a href="{{ url('/add/new/payment-type') }}">Add Type</a></li>
                    <li>
                        <a href="{{ url('/view/all/payment-type') }}">
                            All Payment Types
                            <span style="color:lightgreen" title="Total CS Types">
                                ({{DB::table('db_paymenttypes')->count()}})
                            </span>
                        </a>
                    </li>
                   
                </ul>
            </li>
            <li>
                <a href="javascript: void(0);" class="has-arrow"><i class="feather-box"></i><span>Expense Category</span></a>
                <ul class="sub-menu" aria-expanded="false">
                    <li><a href="{{ url('/add/new/expense-category') }}">Add Category</a></li>
                    <li>
                        <a href="{{ url('/view/all/expense-category') }}">
                            All Categories
                            <span style="color:lightgreen" title="Total Categories">
                                ({{DB::table('db_expense_categories')->count()}})
                            </span>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript: void(0);" class="has-arrow"><i class="feather-box"></i><span>Account</span></a>
                <ul class="sub-menu" aria-expanded="false">
                    <li><a href="{{ url('/add/new/ac-account') }}">Add Account</a></li>
                    <li>
                        <a href="{{ url('/view/all/ac-account') }}">
                            All Accounts
                            <span style="color:lightgreen" title="Total Accounts">
                                ({{DB::table('ac_accounts')->count()}})
                            </span>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript: void(0);" class="has-arrow"><i class="feather-box"></i><span>Expense</span></a>
                <ul class="sub-menu" aria-expanded="false">
                    <li><a href="{{ url('/add/new/expense') }}">Add Expense</a></li>
                    <li>
                        <a href="{{ route('ViewAllExpense') }}">
                            All Expenses
                            <span style="color:lightgreen" title="Total Expenses">
                                ({{DB::table('db_expenses')->count()}})
                            </span>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript: void(0);" class="has-arrow"><i class="feather-box"></i><span>Deposit</span></a>
                <ul class="sub-menu" aria-expanded="false">
                    <li><a href="{{ url('/add/new/deposit') }}">Add Deposit</a></li>
                    <li>
                        <a href="{{ route('ViewAllDeposit') }}">
                            All Deposits
                            <span style="color:lightgreen" title="Total Deposits">
                                ({{DB::table('ac_transactions')->count()}})
                            </span>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript: void(0);" class="has-arrow"><i class="feather-box"></i><span>Journal</span></a>
                <ul class="sub-menu" aria-expanded="false">                  
                    <li><a href="{{ route('journal.index') }}">Index</a></li>
                </ul>
            </li>
            <li>
                <a href="javascript: void(0);" class="has-arrow"><i class="feather-box"></i><span>Ledger</span></a>
                <ul class="sub-menu" aria-expanded="false">
                    <li><a href="{{ route('ledger.index') }}">Index</a></li>
                </ul>
            </li>        
            <li>
                <a href="javascript: void(0);" class="has-arrow"><i class="feather-box"></i><span>Balance Sheet</span></a>
                <ul class="sub-menu" aria-expanded="false">                  
                    <li><a href="{{ route('ledger.balance_sheet') }}">Index</a></li>
                </ul>
            </li>
            <li>
                <a href="javascript: void(0);" class="has-arrow"><i class="feather-box"></i><span>Income Statement</span></a>
                <ul class="sub-menu" aria-expanded="false">
                    {{-- <li><a href="{{ route('ledger.show') }}">Show</a></li> --}}     
                    <li><a href="{{ route('ledger.income_statement') }}">Index</a></li>
                </ul>
            </li>
        </ul>
    </li>
  
    <li>
        <a href="javascript: void(0);" class="has-arrow"><i class="feather-sliders"></i><span>Category</span></a>
        <ul class="sub-menu" aria-expanded="false">
            <li><a href="{{ url('/add/new/category') }}">Add New Category</a></li>
            <li><a href="{{ url('/view/all/category') }}">View All Categories</a></li>
        </ul>
    </li>
    <li>
        <a href="javascript: void(0);" class="has-arrow"><i class="feather-command"></i><span>Subcategory</span></a>
        <ul class="sub-menu" aria-expanded="false">
            <li><a href="{{ url('/add/new/subcategory') }}">Add New Subcategory</a></li>
            <li><a href="{{ url('/view/all/subcategory') }}">View All Subcategories</a></li>
        </ul>
    </li>
    <li>
        <a href="javascript: void(0);" class="has-arrow"><i class="feather-git-pull-request"></i><span>Child
                Category</span></a>
        <ul class="sub-menu" aria-expanded="false">
            <li><a href="{{ url('/add/new/childcategory') }}">Add New Child Category</a></li>
            <li><a href="{{ url('/view/all/childcategory') }}">View All Child Categories</a></li>
        </ul>
    </li>
    <li>
        <a href="javascript: void(0);" class="has-arrow"><i class="feather-box"></i><span>Manage Products</span></a>
        <ul class="sub-menu" aria-expanded="false">
            <li><a href="{{ url('/add/new/product') }}">Add New Product</a></li>
            <li>
                <a href="{{ url('/view/all/product') }}">
                    View All Products
                    <span style="color:lightgreen" title="Total Products">
                        ({{DB::table('products')->count()}})
                    </span>
                </a>
            </li>
            <li>
                <a href="{{ url('/view/product/reviews') }}">
                    Products's Review
                    <span style="color:goldenrod" title="Indicate Pending Review">
                        (@php
                            echo DB::table('product_reviews')
                                ->where('status', 0)
                                ->count();
                        @endphp)
                    </span>
                </a>
            </li>
            <li>
                <a href="{{ url('/view/product/question/answer') }}">
                    Product Ques/Ans
                    <span style="color:goldenrod" title="Indicate Unanswered Questions">
                        (@php
                            echo DB::table('product_question_answers')
                                ->whereNull('answer')
                                ->orWhere('answer', '=', '')
                                ->count();
                        @endphp)
                    </span>
                </a>
            </li>
        </ul>
    </li>
    <li>
        <a href="javascript: void(0);" class="has-arrow"><i class="feather-box"></i><span>Product Warehouse</span></a>
        <ul class="sub-menu" aria-expanded="false">
            <li><a href="{{ url('/add/new/product-warehouse') }}">Add New Warehouse</a></li>
            <li>
                <a href="{{ url('/view/all/product-warehouse') }}">
                    View All Warehouses
                    <span style="color:lightgreen" title="Total Product Warehouses">
                        ({{DB::table('product_warehouses')->count()}})
                    </span>
                </a>
            </li>
           
        </ul>
    </li>
    <li>
        <a href="javascript: void(0);" class="has-arrow"><i class="feather-box"></i><span>Warehouse Room</span></a>
        <ul class="sub-menu" aria-expanded="false">
            <li><a href="{{ url('/add/new/product-warehouse-room') }}">Add Warehouse Room</a></li>
            <li>
                <a href="{{ url('/view/all/product-warehouse-room') }}">
                    View All Warehouse Rooms
                    <span style="color:lightgreen" title="Total Product Warehouse Rooms">
                        ({{DB::table('product_warehouse_rooms')->count()}})
                    </span>
                </a>
            </li>
        </ul>
    </li>
    <li>
        <a href="javascript: void(0);" class="has-arrow"><i class="feather-box"></i><span>Room Cartoon</span></a>
        <ul class="sub-menu" aria-expanded="false">
            <li><a href="{{ url('/add/new/product-warehouse-room-cartoon') }}">Add Cartoon</a></li>
            <li>
                <a href="{{ url('/view/all/product-warehouse-room-cartoon') }}">
                    View All Warehouse Room Cartoons
                    <span style="color:lightgreen" title="Total Product Warehouse Room cartoons">
                        ({{DB::table('product_warehouse_room_cartoons')->count()}})
                    </span>
                </a>
            </li>
        </ul>
    </li>
    <li>
        <a href="javascript: void(0);" class="has-arrow"><i class="feather-box"></i><span>Product Supplier</span></a>
        <ul class="sub-menu" aria-expanded="false">
            <li><a href="{{ url('/add/new/product-supplier') }}">Add New Supplier</a></li>
            <li>
                <a href="{{ url('/view/all/product-supplier') }}">
                    View All Product Suppliers
                    <span style="color:lightgreen" title="Total Product Suppliers">
                        ({{DB::table('product_suppliers')->count()}})
                    </span>
                </a>
            </li>
        </ul>
    </li>
    <li>
        <a href="javascript: void(0);" class="has-arrow"><i class="feather-box"></i><span>Product Purchase</span></a>
        <ul class="sub-menu" aria-expanded="false">
            <li><a href="{{ url('/view/all/purchase-product/charge') }}">Other Charge Types</a></li>
            <li><a href="{{ url('/add/new/purchase-product/quotation') }}">Add Quotation</a></li>
            <li>
                <a href="{{ url('/view/all/purchase-product/quotation') }}">
                    View All Quotations
                    <span style="color:lightgreen" title="Total Product Purchase Quotations">
                        ({{DB::table('product_purchase_quotations')->count()}})
                    </span>
                </a>
            </li>
            {{-- <a href="javascript: void(0);" class="has-arrow"><i class="feather-box"></i><span>Order</span></a> --}}
            
            <li><a href="{{ url('/add/new/purchase-product/order') }}">Add Order</a></li>
            <li>
                <a href="{{ url('/view/all/purchase-product/order') }}">
                    View All Orders
                    <span style="color:lightgreen" title="Total Product Purchase Orders">
                        ({{DB::table('product_purchase_orders')->count()}})
                    </span>
                </a>
            </li>
            
        </ul>
    </li>
    {{-- <li>
        <a href="javascript: void(0);" class="has-arrow"><i class="feather-box"></i><span>Order</span></a>
        <ul class="sub-menu" aria-expanded="false">
            <li><a href="{{ url('/add/new/purchase-product/order') }}">Add Order</a></li>
            <li>
                <a href="{{ url('/view/all/purchase-product/order') }}">
                    View All Orders
                    <span style="color:lightgreen" title="Total Product Purchase Orders">
                        ({{DB::table('product_purchase_orders')->count()}})
                    </span>
                </a>
            </li>
        </ul>
    </li> --}}
    
    <li>
        <a href="javascript: void(0);" class="has-arrow"><i class="feather-shopping-cart"></i><span>Manage Orders</span></a>
        <ul class="sub-menu" aria-expanded="false">
            <li><a style="color: white !important;" href="{{ url('/create/new/order') }}">Create Order</a>
            <li><a style="color: white !important;" href="{{ url('/view/orders') }}">All Orders (@php echo DB::table('orders')->count(); @endphp)</a>
            </li>
            <li><a style="color: skyblue !important;" href="{{ url('/view/pending/orders') }}">Pending Orders
                    (@php
                        echo DB::table('orders')
                            ->where('order_status', 0)
                            ->count();
                    @endphp)</a>
            </li>
            <li><a style="color: wheat !important;" href="{{ url('/view/approved/orders') }}">Approved Orders
                    (@php
                        echo DB::table('orders')
                            ->where('order_status', 1)
                            ->count();
                    @endphp)</a></li>
            <li><a style="color: violet !important;" href="{{ url('/view/intransit/orders') }}">Intransit Orders
                    (@php
                        echo DB::table('orders')
                            ->where('order_status', 2)
                            ->count();
                    @endphp)</a></li>
            <li><a style="color: #0c0 !important;" href="{{ url('/view/delivered/orders') }}">Delivered Orders
                    (@php
                        echo DB::table('orders')
                            ->where('order_status', 3)
                            ->count();
                    @endphp)</a></li>
            <li><a style="color: tomato !important;" href="{{ url('/view/picked/orders') }}">Picked Orders
                    (@php
                        echo DB::table('orders')
                            ->where('order_status', 5)
                            ->count();
                    @endphp)</a></li>
            <li><a style="color: red !important;" href="{{ url('/view/cancelled/orders') }}">Cancelled Orders
                    (@php
                        echo DB::table('orders')
                            ->where('order_status', 4)
                            ->count();
                    @endphp)</a></li>
        </ul>
    </li>
    {{-- <li>
        <a href="javascript: void(0);" class="has-arrow"><i class="feather-box"></i><span>Old Purchase Product</span></a>
        <ul class="sub-menu" aria-expanded="false">
            <li><a href="{{ url('/add/new/product-purchase/quotation') }}">Add Quotation</a></li>
            <li>
                <a href="{{ url('/view/all/product-purchase/quotation') }}">
                    All Quotations
                    <span style="color:lightgreen" title="Total Product Quotations">
                        ({{DB::table('product_purchase_quotations')->count()}})
                    </span>
                </a>
            </li>
            <li><a href="{{ url('/add/new/product-purchase/order') }}">Add Order</a></li>
            <li>
                <a href="{{ url('/view/all/product-purchase/order') }}">
                    All Orders
                    <span style="color:lightgreen" title="Total Product Orders">
                        ({{DB::table('product_purchase_orders')->count()}})
                    </span>
                </a>
            </li>
        </ul>
    </li> --}}
    <li>
        <a href="javascript: void(0);" class="has-arrow"><i class="feather-gift"></i><span>Promo Codes</span></a>
        <ul class="sub-menu" aria-expanded="false">
            <li><a href="{{ url('/add/new/code') }}">Add New Promo Code</a></li>
            <li><a href="{{ url('/view/all/promo/codes') }}">View All Promo Codes</a></li>
        </ul>
    </li>

    {{-- <li><a href="{{ url('/file-manager') }}"><i class="fas fa-folder-open"></i><span>File Manager</span></a></li> --}}
    <li>
        <a href="javascript: void(0);" class="has-arrow"><i class="feather-bell"></i><span>Push Notification</span></a>
        <ul class="sub-menu" aria-expanded="false">
            <li><a href="{{ url('/send/notification/page') }}">Send Notification</a></li>
            <li><a href="{{ url('/view/all/notifications') }}">Prevoious Notifications</a></li>
        </ul>
    </li>
    <li>
        <a href="javascript: void(0);" class="has-arrow"><i class="fas fa-sms"></i><span>SMS Service</span></a>
        <ul class="sub-menu" aria-expanded="false">
            <li><a href="{{ url('/view/sms/templates') }}">SMS Templates</a></li>
            <li><a href="{{ url('/send/sms/page') }}">Send SMS</a></li>
            <li><a href="{{ url('/view/sms/history') }}">SMS History</a></li>
        </ul>
    </li>
    <li>
        <a href="javascript: void(0);" class="has-arrow"><i class="feather-settings"></i><span>System</span></a>
        <ul class="sub-menu" aria-expanded="false">
            <li><a href="{{ url('/view/email/credential') }}">Email Configure (SMTP)</a></li>
            <li><a href="{{ url('/view/email/templates') }}">Email Templates</a></li>
            <li><a href="{{ url('/setup/sms/gateways') }}">SMS Gateway</a></li>
            <li><a href="{{ url('/setup/payment/gateways') }}">Payment Gateway</a></li>
        </ul>
    </li>

    {{-- <li><a href="{{ url('/view/all/customers') }}"><i class="feather-users"></i><span>Customers</span></a></li>
    <li>
        <a href="javascript: void(0);" class="has-arrow"><i class="feather-box"></i><span>Customer Src Type</span></a>
        <ul class="sub-menu" aria-expanded="false">
            <li><a href="{{ url('/add/new/customer-source') }}">Add New Type</a></li>
            <li>
                <a href="{{ url('/view/all/customer-source') }}">
                    View All CS Types
                    <span style="color:lightgreen" title="Total CS Types">
                        ({{DB::table('customer_source_types')->count()}})
                    </span>
                </a>
            </li>
           
        </ul>
    </li> --}}
    <li>
        <a href="javascript: void(0);" class="has-arrow"><i class="feather-box"></i><span>Supplier Src Type</span></a>
        <ul class="sub-menu" aria-expanded="false">
            <li><a href="{{ url('/add/new/supplier-source') }}">Add New Type</a></li>
            <li>
                <a href="{{ url('/view/all/supplier-source') }}">
                    View All Supplier Types
                    <span style="color:lightgreen" title="Total CS Types">
                        ({{DB::table('supplier_source_types')->count()}})
                    </span>
                </a>
            </li>
           
        </ul>
    </li>
    <li>
        <a href="javascript: void(0);" class="has-arrow"><i class="feather-box"></i><span>Outlet</span></a>
        <ul class="sub-menu" aria-expanded="false">
            <li><a href="{{ url('/add/new/outlet') }}">Add New </a></li>
            <li>
                <a href="{{ url('/view/all/outlet') }}">
                    View All Outlets
                    <span style="color:lightgreen" title="Total Outlets">
                        ({{DB::table('outlets')->count()}})
                    </span>
                </a>
            </li>
           
        </ul>
    </li>
    <li>
        <a href="javascript: void(0);" class="has-arrow"><i class="feather-box"></i><span>Video Gallery</span></a>
        <ul class="sub-menu" aria-expanded="false">
            <li><a href="{{ url('/add/new/video-gallery') }}">Add New </a></li>
            <li>
                <a href="{{ url('/view/all/video-gallery') }}">
                    View All Videos
                    <span style="color:lightgreen" title="Total Videos">
                        ({{DB::table('video_galleries')->count()}})
                    </span>
                </a>
            </li>
           
        </ul>
    </li>



    <li><a href="{{ url('/view/customers/wishlist') }}"><i class="feather-heart"></i><span>Customer's Wishlist</span></a></li>
    <li><a href="{{ url('/view/delivery/charges') }}"><i class="feather-truck"></i><span>Delivery Charges</span></a></li>
    <li><a href="{{ url('/view/upazila/thana') }}"><i class="dripicons-location"></i><span>Upazila & Thana</span></a></li>
    <li><a href="{{ url('/view/payment/history') }}"><i class="feather-dollar-sign"></i><span>Payment History</span></a></li>

    <li>
        <a href="javascript: void(0);" class="has-arrow"><i class="feather-printer"></i><span>Generate Report</span></a>
        <ul class="sub-menu" aria-expanded="false">
            <li><a href="{{ url('/sales/report') }}">Sales Report</a></li>
        </ul>
    </li>
    <li>
        <a href="javascript: void(0);" class="has-arrow"><i class="feather-download-cloud"></i><span>Download
                Backup</span></a>
        <ul class="sub-menu" aria-expanded="false">
            <li><a href="{{ url('/download/database/backup') }}">Database Backup</a></li>
            <li><a href="{{ url('/download/product/files/backup') }}">Product Images Backup</a></li>
            <li><a href="{{ url('/download/user/files/backup') }}">User Images Backup</a></li>
            <li><a href="{{ url('/download/banner/files/backup') }}">Banner Images Backup</a></li>
            <li><a href="{{ url('/download/category/files/backup') }}">Category Icon Backup</a></li>
            <li><a href="{{ url('/download/subcategory/files/backup') }}">Subcategory Backup</a></li>
            <li><a href="{{ url('/download/flag/files/backup') }}">Flag Icon Backup</a></li>
            <li><a href="{{ url('/download/ticket/files/backup') }}">Ticket Files Backup</a></li>
            <li><a href="{{ url('/download/blog/files/backup') }}">Blog Files Backup</a></li>
            <li><a href="{{ url('/download/other/files/backup') }}">Other Images Backup</a></li>
        </ul>
    </li>

    <hr style="border-color: #c8c8c836; margin-top: 12px; margin-bottom: 12px;">
    <li class="menu-title" style="color: khaki; text-shadow: 1px 1px 2px black;">CRM Modules</li>
    <li>
        <a href="javascript: void(0);" class="has-arrow"><i class="fas fa-headset"></i><span>Support
                Ticket</span></a>
        <ul class="sub-menu" aria-expanded="false">
            <li><a style="color: skyblue !important;" href="{{ url('/pending/support/tickets') }}">Pending Supports
                    (@php
                        echo DB::table('support_tickets')
                            ->where('status', 0)
                            ->orWhere('status', 1)
                            ->count();
                    @endphp)</a></li>
            <li><a style="color: #0c0 !important;" href="{{ url('/solved/support/tickets') }}">Solved Supports
                    (@php
                        echo DB::table('support_tickets')
                            ->where('status', 2)
                            ->count();
                    @endphp)</a></li>
            <li><a style="color: goldenrod !important;" href="{{ url('/on/hold/support/tickets') }}">On Hold Supports
                    (@php
                        echo DB::table('support_tickets')
                            ->where('status', 4)
                            ->count();
                    @endphp)</a></li>
            <li><a style="color: red !important;" href="{{ url('/rejected/support/tickets') }}">Rejected Supports
                    (@php
                        echo DB::table('support_tickets')
                            ->where('status', 3)
                            ->count();
                    @endphp)</a></li>
        </ul>
    </li>
    <li><a href="{{ url('/view/all/contact/requests') }}"><i class="feather-phone-forwarded"></i><span>Contact Request</span></a></li>
    <li><a href="{{ url('/view/all/subscribed/users') }}"><i class="feather-user-check"></i><span>Subscribed Users</span></a></li>


    <hr style="border-color: #c8c8c836; margin-top: 12px; margin-bottom: 12px;">
    <li class="menu-title" style="color: khaki; text-shadow: 1px 1px 2px black;">Content Management</li>
    <li>
        <a href="javascript: void(0);" class="has-arrow"><i class="feather-image"></i><span>Sliders & Banners</span></a>
        <ul class="sub-menu" aria-expanded="false">
            <li><a href="{{ url('/view/all/sliders') }}">View All Sliders</a></li>
            <li><a href="{{ url('/view/all/banners') }}">View All Banners</a></li>
            <li><a href="{{ url('/view/promotional/banner') }}">Promotional Banner</a></li>
        </ul>
    </li>
    <li>
        <a href="javascript: void(0);" class="has-arrow"><i class="feather-message-square"></i><span>Testimonials</span></a>
        <ul class="sub-menu" aria-expanded="false">
            <li><a href="{{ url('/add/testimonial') }}">Add New Testimonial</a></li>
            <li><a href="{{ url('/view/testimonials') }}">View All Testimonials</a></li>
        </ul>
    </li>
    <li>
        <a href="javascript: void(0);" class="has-arrow"><i class="feather-file-text"></i><span>Manage Blogs</span></a>
        <ul class="sub-menu" aria-expanded="false">
            <li><a href="{{ url('/blog/categories') }}">Blog Categories</a></li>
            <li><a href="{{ url('/add/new/blog') }}">Write a Blog</a></li>
            <li><a href="{{ url('/view/all/blogs') }}">View All Blogs</a></li>
        </ul>
    </li>
    <li>
        <a href="javascript: void(0);" class="has-arrow"><i class="feather-alert-triangle"></i><span>Terms & Policies</span></a>
        <ul class="sub-menu" aria-expanded="false">
            <li><a href="{{ url('/terms/and/condition') }}">Terms & Condition</a></li>
            <li><a href="{{ url('/view/privacy/policy') }}">Privacy Policy</a></li>
            <li><a href="{{ url('/view/shipping/policy') }}">Shipping Policy</a></li>
            <li><a href="{{ url('/view/return/policy') }}">Return Policy</a></li>
        </ul>
    </li>
    <li>
        <a href="javascript: void(0);" class="has-arrow"><i class="feather-file-plus"></i><span>Custom Pages</span></a>
        <ul class="sub-menu" aria-expanded="false">
            <li><a href="{{ url('/create/new/page') }}">Create New Page</a></li>
            <li><a href="{{ url('/view/all/pages') }}">View All Pages</a></li>
        </ul>
    </li>
    <li><a href="{{ url('/about/us/page') }}"><i class="feather-globe"></i><span>About Us</span></a></li>
    <li><a href="{{ url('/view/all/faqs') }}"><i class="far fa-question-circle"></i><span>FAQ's</span></a></li>


    <hr style="border-color: #c8c8c836; margin-top: 12px; margin-bottom: 5px;">
    <li class="menu-title" style="color: khaki; text-shadow: 1px 1px 2px black;">User Role Permission</li>
    <li><a href="{{ url('/view/system/users') }}"><i class="fas fa-user-shield"></i><span>System Users</span></a></li>
    <li><a href="{{ url('/view/permission/routes') }}"><i class="feather-git-merge"></i><span>Permission Routes</span></a></li>
    <li><a href="{{ url('/view/user/roles') }}"><i class="feather-user-plus"></i><span>User Roles</span></a></li>
    <li><a href="{{ url('/view/user/role/permission') }}"><i class="mdi mdi-security"></i><span>Assign Role Permission</span></a></li>
    <hr style="border-color: #c8c8c836; margin-top: 12px; margin-bottom: 12px;">

    <li>
        <a href="javascript: void(0);" class="has-arrow"><i class="feather-box"></i><span>Demo Products</span></a>
        <ul class="sub-menu" aria-expanded="false">
            <li><a href="{{ url('/generate/demo/products') }}">Generate Products</a></li>
            <li><a href="{{ url('/remove/demo/products/page') }}">Remove Products</a></li>
        </ul>
    </li>
    <li><a href="{{ url('/clear/cache') }}"><i class="feather-rotate-cw"></i><span>Clear Cache</span></a></li>
    <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="feather-log-out"></i><span>Logout</span></a></li>

</ul>