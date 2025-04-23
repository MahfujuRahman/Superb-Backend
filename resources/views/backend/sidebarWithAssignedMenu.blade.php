<!-- Left Menu Start -->
<?php
    function checkAuth($routes){
        if(App\Models\UserRolePermission::where('user_id', Auth::user()->id)->where('route', 'like', '%'.$routes.'%')->exists()){
            return true;
        } else {
            return false;
        }
    }

    $configModule = App\Models\UserRolePermission::where('user_id', Auth::user()->id)
                                                ->where('route', 'like', '%view/all/units%')
                                                ->orWhere('route', 'like', '%view/all/flags%')
                                                ->orWhere('route', 'like', '%view/all/sizes%')
                                                ->orWhere('route', 'like', '%view/all/brands%')
                                                ->orWhere('route', 'like', '%view/all/models%')
                                                ->orWhere('route', 'like', '%view/all/colors%')
                                                ->orWhere('route', 'like', '%view/all/storages%')
                                                ->orWhere('route', 'like', '%view/all/sims%')
                                                ->orWhere('route', 'like', '%view/all/device/conditions%')
                                                ->orWhere('route', 'like', '%view/all/warrenties%')
                                                ->orWhere('route', 'like', '%config/setup%')
                                                ->get();

    $categoryModule = App\Models\UserRolePermission::where('user_id', Auth::user()->id)->where('route', 'like', '%add/new/category%')->orWhere('route', 'like', '%view/new/category%')->get();

    $subCategoryModule = App\Models\UserRolePermission::where('user_id', Auth::user()->id)->where(function($query) {
                                                            $query->where('route', 'like', '%add/new/subcategory%')
                                                                ->orWhere('route', 'like', '%view/all/subcategory%');
                                                        })->get();

    $childCategoryModule = App\Models\UserRolePermission::where('user_id', Auth::user()->id)->where('route', 'like', '%add/new/childcategory%')->orWhere('route', 'like', '%view/all/childcategory%')->get();

    $productModule = App\Models\UserRolePermission::where('user_id', Auth::user()->id)
                                                ->where('route', 'like', '%add/new/product%')
                                                ->orWhere('route', 'like', '%view/all/product%')
                                                ->orWhere('route', 'like', '%view/product/reviews%')
                                                ->orWhere('route', 'like', '%view/product/question/answer%')
                                                ->get();

    $orderModule = App\Models\UserRolePermission::where('user_id', Auth::user()->id)
                                                ->where('route', 'like', '%view/orders%')
                                                ->where('route', 'like', '%create/new/order%')
                                                ->orWhere('route', 'like', '%view/pending/orders%')
                                                ->orWhere('route', 'like', '%view/approved/orders%')
                                                ->orWhere('route', 'like', '%view/delivered/orders%')
                                                ->orWhere('route', 'like', '%view/cancelled/orders%')
                                                ->get();

    $promoCodeModule = App\Models\UserRolePermission::where('user_id', Auth::user()->id)->where('route', 'like', '%add/new/code%')->orWhere('route', 'like', '%view/all/promo/codes%')->get();
    $sliderBannerModule = App\Models\UserRolePermission::where('user_id', Auth::user()->id)->where('route', 'like', '%view/all/sliders%')->orWhere('route', 'like', '%view/all/banners%')->orWhere('route', 'like', '%view/promotional/banner%')->get();

    $termsPolicyModule = App\Models\UserRolePermission::where('user_id', Auth::user()->id)
                                                    ->where('route', 'like', '%terms/and/condition%')
                                                    ->orWhere('route', 'like', '%view/privacy/policy%')
                                                    ->orWhere('route', 'like', '%view/shipping/policy%')
                                                    ->orWhere('route', 'like', '%view/return/policy%')
                                                    ->get();

    // $customerUserModule = App\Models\UserRolePermission::where('user_id', Auth::user()->id)->where('route', 'like', '%view/all/sliders%')->orWhere('route', 'like', '%view/all/banners%')->get();

    $supportTicketModule = App\Models\UserRolePermission::where('user_id', Auth::user()->id)
                                                    ->where('route', 'like', '%pending/support/tickets%')
                                                    ->orWhere('route', 'like', '%solved/support/tickets%')
                                                    ->orWhere('route', 'like', '%on/hold/support/tickets%')
                                                    ->orWhere('route', 'like', '%rejected/support/tickets%')
                                                    ->get();

    $testimonialModule = App\Models\UserRolePermission::where('user_id', Auth::user()->id)->where('route', 'like', '%add/testimonial%')->orWhere('route', 'like', '%view/testimonials%')->get();
    $pushNotificationModule = App\Models\UserRolePermission::where('user_id', Auth::user()->id)->where('route', 'like', '%send/notification/page%')->orWhere('route', 'like', '%view/all/notifications%')->get();
    $smsServiceModule = App\Models\UserRolePermission::where('user_id', Auth::user()->id)->where('route', 'like', '%view/sms/templates%')->orWhere('route', 'like', '%send/sms/page%')->orWhere('route', 'like', '%view/sms/history%')->get();

    $systemModule = App\Models\UserRolePermission::where('user_id', Auth::user()->id)
                                                ->where('route', 'like', '%view/email/credential%')
                                                ->orWhere('route', 'like', '%view/email/templates%')
                                                ->orWhere('route', 'like', '%setup/sms/gateways%')
                                                ->orWhere('route', 'like', '%setup/payment/gateways%')
                                                ->get();

    // $webPagesModule = App\Models\UserRolePermission::where('user_id', Auth::user()->id)->where('route', 'like', '%about/us/page%')->orWhere('route', 'like', '%view/all/faqs%')->get();
    $blogModule = App\Models\UserRolePermission::where('user_id', Auth::user()->id)->where('route', 'like', '%blog/categories%')->orWhere('route', 'like', '%add/new/blog%')->orWhere('route', 'like', '%view/all/blogs%')->get();
    $customPageModule = App\Models\UserRolePermission::where('user_id', Auth::user()->id)
                                                ->where('route', 'like', '%create/new/page%')
                                                ->orWhere('route', 'like', '%view/all/pages%')
                                                ->get();

    $backupModule = App\Models\UserRolePermission::where('user_id', Auth::user()->id)->where('route', 'like', '%backup%')->get();

    $reportModule = App\Models\UserRolePermission::where('user_id', Auth::user()->id)->where('route', 'like', '%sales/report%')->get();

    $demoProductsModule = App\Models\UserRolePermission::where('user_id', Auth::user()->id)
                                                ->where('route', 'like', '%generate/demo/products%')
                                                ->orWhere('route', 'like', '%remove/demo/products/page%')
                                                ->get();
?>


<ul class="metismenu list-unstyled" id="side-menu">
    <li class="menu-title">Menu</li>

    <li><a href="{{ url('/home') }}"><i class="feather-home"></i><span>Dashboard</span></a></li>


    @if(checkAuth("general/info") || checkAuth("website/theme/page") || checkAuth("social/media/page") || checkAuth("seo/homepage") || checkAuth("custom/css/js") || checkAuth("social/chat/script"))
    <hr style="border-color: #c8c8c836; margin-top: 12px; margin-bottom: 5px;">
    <li class="menu-title" style="color: khaki; text-shadow: 1px 1px 2px black;">Website Config</li>
    @endif
    @dd(checkAuth("general/info"))
    @if(checkAuth("general/info")) <li><a href="{{ url('/general/info') }}"><i class="feather-grid"></i><span>General Info</span></a></li> @endif
    @if(checkAuth("website/theme/page")) <li><a href="{{ url('/website/theme/page') }}"><i class="mdi mdi-format-color-fill" style="font-size: 18px"></i><span>Website Theme Color</span></a></li> @endif
    @if(checkAuth("social/media/page")) <li><a href="{{ url('/social/media/page') }}"><i class="mdi mdi-link-variant" style="font-size: 17px"></i><span>Social Media Links</span></a></li> @endif
    @if(checkAuth("seo/homepage")) <li><a href="{{ url('/seo/homepage') }}"><i class="dripicons-search"></i><span>Home Page SEO</span></a></li> @endif
    @if(checkAuth("custom/css/js")) <li><a href="{{ url('/custom/css/js') }}"><i class="feather-code"></i><span>Custom CSS & JS</span></a></li> @endif
    @if(checkAuth("social/chat/script")) <li><a href="{{ url('/social/chat/script') }}"><i class="mdi mdi-code-brackets"></i><span>Social & Chat Scripts</span></a></li> @endif


    @if(count($configModule) > 0 || count($categoryModule) > 0 || count($subCategoryModule) > 0 || count($childCategoryModule) > 0 || count($productModule) > 0 || count($orderModule) > 0 || count($promoCodeModule) > 0 || count($pushNotificationModule) > 0 || count($smsServiceModule) > 0 || count($systemModule) > 0 || checkAuth("view/all/customers") || checkAuth("view/customers/wishlist") || checkAuth("view/delivery/charges") || count($reportModule) > 0 || count($backupModule) > 0)
    <hr style="border-color: #c8c8c836; margin-top: 12px; margin-bottom: 12px;">
    <li class="menu-title" style="color: khaki; text-shadow: 1px 1px 2px black;">E-commerce Modules</li>
    @endif

    @if ($configModule && count($configModule) > 0)
    <li>
        <a href="javascript: void(0);" class="has-arrow"><i class="feather-settings"></i><span>Config</span></a>
        <ul class="sub-menu" aria-expanded="false">

            <li><a href="{{ url('/config/setup') }}">Setup Your Config</a></li>

            @if(DB::table('config_setups')->where('code', 'product_size')->where('status', 1)->first())
                @if(checkAuth("view/all/sizes")) <li><a href="{{ url('/view/all/sizes') }}">Product Sizes</a></li> @endif
            @endif
            @if(DB::table('config_setups')->where('code', 'storage')->where('status', 1)->first())
                @if(checkAuth("view/all/storages")) <li><a href="{{ url('/view/all/storages') }}">Storage (RAM/ROM)</a></li> @endif
            @endif
            @if(DB::table('config_setups')->where('code', 'sim')->where('status', 1)->first())
                @if(checkAuth("view/all/sims")) <li><a href="{{ url('/view/all/sims') }}">SIM Type</a></li> @endif
            @endif
            @if(DB::table('config_setups')->where('code', 'device_condition')->where('status', 1)->first())
                @if(checkAuth("view/all/device/conditions")) <li><a href="{{ url('/view/all/device/conditions') }}">Device Condition</a></li> @endif
            @endif
            @if(DB::table('config_setups')->where('code', 'product_warranty')->where('status', 1)->first())
                @if(checkAuth("view/all/warrenties")) <li><a href="{{ url('/view/all/warrenties') }}">Product Warrenty</a></li> @endif
            @endif

            @if(DB::table('config_setups')->where('code', 'color')->where('status', 1)->first())
                @if(checkAuth("view/all/colors")) <li><a href="{{ url('/view/all/colors') }}">Product Colors</a></li> @endif
            @endif
            @if(DB::table('config_setups')->where('code', 'measurement_unit')->where('status', 1)->first())
                @if(checkAuth("view/all/units")) <li><a href="{{ url('/view/all/units') }}">Measurement Units</a></li> @endif
            @endif


            @if(checkAuth("view/all/flags")) <li><a href="{{ url('/view/all/flags') }}">Product Flags</a></li> @endif
            @if(checkAuth("view/all/brands")) <li><a href="{{ url('/view/all/brands') }}">Product Brands</a></li> @endif
            @if(checkAuth("view/all/models")) <li><a href="{{ url('/view/all/models') }}">Models of Brand</a></li> @endif
        </ul>
    </li>
    @endif

    @if ($categoryModule && count($categoryModule) > 0)
    <li>
        <a href="javascript: void(0);" class="has-arrow"><i class="feather-sliders"></i><span>Category</span></a>
        <ul class="sub-menu" aria-expanded="false">
            @if(checkAuth("add/new/category")) <li><a href="{{ url('/add/new/category') }}">Add New Category</a></li> @endif
            @if(checkAuth("view/all/category")) <li><a href="{{ url('/view/all/category') }}">View All Categories</a></li> @endif
        </ul>
    </li>
    @endif

    @if ($subCategoryModule && count($subCategoryModule) > 0)
    <li>
        <a href="javascript: void(0);" class="has-arrow"><i class="feather-command"></i><span>Subcategory</span></a>
        <ul class="sub-menu" aria-expanded="false">
            @if(checkAuth("add/new/subcategory")) <li><a href="{{ url('/add/new/subcategory') }}">Add New Subcategory</a></li> @endif
            @if(checkAuth("view/all/subcategory")) <li><a href="{{ url('/view/all/subcategory') }}">View All Subcategories</a></li> @endif
        </ul>
    </li>
    @endif

    @if ($childCategoryModule && count($childCategoryModule) > 0)
    <li>
        <a href="javascript: void(0);" class="has-arrow"><i class="feather-git-pull-request"></i><span>Child Category</span></a>
        <ul class="sub-menu" aria-expanded="false">
            @if(checkAuth("add/new/childcategory")) <li><a href="{{ url('/add/new/childcategory') }}">Add New Child Category</a></li> @endif
            @if(checkAuth("view/all/childcategory")) <li><a href="{{ url('/view/all/childcategory') }}">View All Child Categories</a></li> @endif
        </ul>
    </li>
    @endif

    @if ($productModule && count($productModule) > 0)
    <li>
        <a href="javascript: void(0);" class="has-arrow"><i class="feather-box"></i><span>Manage Products</span></a>
        <ul class="sub-menu" aria-expanded="false">
            @if(checkAuth("add/new/product")) <li><a href="{{ url('/add/new/product') }}">Add New Product</a></li> @endif
            @if(checkAuth("view/all/product"))
            <li>
                <a href="{{ url('/view/all/product') }}">
                    View All Products
                    <span style="color:lightgreen" title="Total Products">
                        ({{DB::table('products')->where('status', 1)->count()}})
                    </span>
                </a>
            </li>
            @endif
            @if(checkAuth("view/product/reviews"))
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
            @endif
            @if(checkAuth("view/product/question/answer"))
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
            @endif
        </ul>
    </li>
    @endif

    @if ($orderModule && count($orderModule) > 0)
    <li>
        <a href="javascript: void(0);" class="has-arrow"><i class="feather-shopping-cart"></i><span>Manage Orders</span></a>
        <ul class="sub-menu" aria-expanded="false">
            @if(checkAuth("create/new/order")) <li><a style="color: white !important;" href="{{ url('/create/new/order') }}">Create Order</a></li> @endif
            @if(checkAuth("view/orders")) <li><a style="color: white !important;" href="{{ url('/view/orders') }}">All Orders (@php echo DB::table('orders')->count(); @endphp)</a></li> @endif
            @if(checkAuth("view/pending/orders"))
            <li><a style="color: skyblue !important;"
                    href="{{ url('/view/pending/orders') }}">Pending Orders (@php
                        echo DB::table('orders')
                            ->where('order_status', 0)
                            ->count();
                    @endphp)</a>
            </li>
            @endif
            @if(checkAuth("view/approved/orders"))
            <li><a style="color: wheat !important;"
                    href="{{ url('/view/approved/orders') }}">Approved Orders
                    (@php
                        echo DB::table('orders')
                            ->where('order_status', 1)
                            ->orWhere('order_status', 2)
                            ->count();
                    @endphp)</a>
            </li>
            @endif
            @if(checkAuth("view/delivered/orders"))
            <li><a style="color: #0c0 !important;"
                    href="{{ url('/view/delivered/orders') }}">Delivered Orders
                    (@php
                        echo DB::table('orders')
                            ->where('order_status', 3)
                            ->count();
                    @endphp)</a>
            </li>
            @endif
            @if(checkAuth("view/cancelled/orders"))
            <li><a style="color: red !important;"
                    href="{{ url('/view/cancelled/orders') }}">Cancelled Orders
                    (@php
                        echo DB::table('orders')
                            ->where('order_status', 4)
                            ->count();
                    @endphp)</a></li>
            @endif
        </ul>
    </li>
    @endif

    @if ($promoCodeModule && count($promoCodeModule) > 0)
    <li>
        <a href="javascript: void(0);" class="has-arrow"><i class="feather-gift"></i><span>Promo Codes</span></a>
        <ul class="sub-menu" aria-expanded="false">
            @if(checkAuth("add/new/code")) <li><a href="{{ url('/add/new/code') }}">Add New Promo Code</a></li> @endif
            @if(checkAuth("view/all/promo/codes")) <li><a href="{{ url('/view/all/promo/codes') }}">View All Promo Codes</a></li> @endif
        </ul>
    </li>
    @endif

    {{-- <li><a href="{{ url('/file-manager') }}"><i class="fas fa-folder-open"></i><span>File Manager</span></a></li> --}}

    @if ($pushNotificationModule && count($pushNotificationModule) > 0)
    <li>
        <a href="javascript: void(0);" class="has-arrow"><i class="feather-bell"></i><span>Push Notification</span></a>
        <ul class="sub-menu" aria-expanded="false">
            @if(checkAuth("send/notification/page")) <li><a href="{{ url('/send/notification/page') }}">Send Notification</a></li> @endif
            @if(checkAuth("view/all/notifications")) <li><a href="{{ url('/view/all/notifications') }}">Prevoious Notifications</a></li> @endif
        </ul>
    </li>
    @endif

    @if ($smsServiceModule && count($smsServiceModule) > 0)
    <li>
        <a href="javascript: void(0);" class="has-arrow"><i class="fas fa-sms"></i><span>SMS Service</span></a>
        <ul class="sub-menu" aria-expanded="false">
            @if(checkAuth("view/sms/templates")) <li><a href="{{ url('/view/sms/templates') }}">SMS Templates</a></li> @endif
            @if(checkAuth("send/sms/page")) <li><a href="{{ url('/send/sms/page') }}">Send SMS</a></li> @endif
            @if(checkAuth("view/sms/history")) <li><a href="{{ url('/view/sms/history') }}">SMS History</a></li> @endif
        </ul>
    </li>
    @endif

    @if ($systemModule && count($systemModule) > 0)
    <li>
        <a href="javascript: void(0);" class="has-arrow"><i class="feather-settings"></i><span>System</span></a>
        <ul class="sub-menu" aria-expanded="false">
            @if(checkAuth("view/email/credential")) <li><a href="{{ url('/view/email/credential') }}">Email Configure (SMTP)</a></li> @endif
            @if(checkAuth("view/email/templates")) <li><a href="{{ url('/view/email/templates') }}">Email Templates</a></li> @endif
            @if(checkAuth("setup/sms/gateways")) <li><a href="{{ url('/setup/sms/gateways') }}">SMS Gateway</a></li> @endif
            @if(checkAuth("setup/payment/gateways")) <li><a href="{{ url('/setup/payment/gateways') }}">Payment Gateway</a></li> @endif
        </ul>
    </li>
    @endif

    @if(checkAuth("view/all/customers")) <li><a href="{{ url('/view/all/customers') }}"><i class="feather-users"></i><span>Customers</span></a></li> @endif
    @if(checkAuth("view/customers/wishlist")) <li><a href="{{ url('/view/customers/wishlist') }}"><i class="feather-heart"></i><span>Customer's Wishlist</span></a></li> @endif
    @if(checkAuth("view/delivery/charges")) <li><a href="{{ url('/view/delivery/charges') }}"><i class="feather-truck"></i><span>Delivery Charges</span></a></li> @endif
    @if(checkAuth("view/upazila/thana")) <li><a href="{{ url('/view/upazila/thana') }}"><i class="dripicons-location"></i><span>Upazila & Thana</span></a></li> @endif

    @if ($reportModule && count($reportModule) > 0)
    <li>
        <a href="javascript: void(0);" class="has-arrow"><i class="feather-printer"></i><span>Generate Report</span></a>
        <ul class="sub-menu" aria-expanded="false">
            @if(checkAuth("sales/report")) <li><a href="{{ url('/sales/report') }}">Sales Report</a></li> @endif
        </ul>
    </li>
    @endif

    @if ($backupModule && count($backupModule) > 0)
    <li>
        <a href="javascript: void(0);" class="has-arrow"><i class="feather-download-cloud"></i><span>Download Backup</span></a>
        <ul class="sub-menu" aria-expanded="false">
            @if(checkAuth("download/database/backup")) <li><a href="{{ url('/download/database/backup') }}">Database Backup</a></li> @endif
            @if(checkAuth("download/product/files/backup")) <li><a href="{{ url('/download/product/files/backup') }}">Product Images Backup</a></li> @endif
            @if(checkAuth("download/user/files/backup")) <li><a href="{{ url('/download/user/files/backup') }}">User Images Backup</a></li> @endif
            @if(checkAuth("download/banner/files/backup")) <li><a href="{{ url('/download/banner/files/backup') }}">Banner Images Backup</a></li> @endif
            @if(checkAuth("download/category/files/backup")) <li><a href="{{ url('/download/category/files/backup') }}">Category Icon Backup</a></li> @endif
            @if(checkAuth("download/subcategory/files/backup")) <li><a href="{{ url('/download/subcategory/files/backup') }}">Subcategory Backup</a></li> @endif
            @if(checkAuth("download/flag/files/backup")) <li><a href="{{ url('/download/flag/files/backup') }}">Flag Icon Backup</a></li> @endif
            @if(checkAuth("download/ticket/files/backup")) <li><a href="{{ url('/download/ticket/files/backup') }}">Ticket Files Backup</a></li> @endif
            @if(checkAuth("download/blog/files/backup")) <li><a href="{{ url('/download/blog/files/backup') }}">Blog Files Backup</a></li> @endif
            @if(checkAuth("download/other/files/backup")) <li><a href="{{ url('/download/other/files/backup') }}">Other Images Backup</a></li> @endif
        </ul>
    </li>
    @endif


    @if(count($supportTicketModule) > 0 || checkAuth("view/all/contact/requests") || checkAuth("view/all/subscribed/users"))
    <hr style="border-color: #c8c8c836; margin-top: 12px; margin-bottom: 12px;">
    <li class="menu-title" style="color: khaki; text-shadow: 1px 1px 2px black;">CRM Modules</li>
    @endif

    @if ($supportTicketModule && count($supportTicketModule) > 0)
    <li>
        <a href="javascript: void(0);" class="has-arrow"><i class="fas fa-headset"></i><span>Support Ticket</span></a>
        <ul class="sub-menu" aria-expanded="false">
            @if(checkAuth("pending/support/tickets"))
            <li><a style="color: skyblue !important;"
                    href="{{ url('/pending/support/tickets') }}">Pending Supports
                    (@php
                        echo DB::table('support_tickets')
                            ->where('status', 0)
                            ->orWhere('status', 1)
                            ->count();
                    @endphp)</a>
            </li>
            @endif
            @if(checkAuth("solved/support/tickets"))
            <li><a style="color: #0c0 !important;"
                    href="{{ url('/solved/support/tickets') }}">Solved Supports
                    (@php
                        echo DB::table('support_tickets')
                            ->where('status', 2)
                            ->count();
                    @endphp)</a>
            </li>
            @endif
            @if(checkAuth("on/hold/support/tickets"))
            <li><a style="color: goldenrod !important;"
                    href="{{ url('/on/hold/support/tickets') }}">On Hold Supports
                    (@php
                        echo DB::table('support_tickets')
                            ->where('status', 4)
                            ->count();
                    @endphp)</a>
            </li>
            @endif
            @if(checkAuth("rejected/support/tickets"))
            <li><a style="color: red !important;"
                    href="{{ url('/rejected/support/tickets') }}">Rejected Supports
                    (@php
                        echo DB::table('support_tickets')
                            ->where('status', 3)
                            ->count();
                    @endphp)</a>
            </li>
            @endif
        </ul>
    </li>
    @endif
    @if(checkAuth("view/all/contact/requests")) <li><a href="{{ url('/view/all/contact/requests') }}"><i class="feather-phone-forwarded"></i><span>Contact Request</span></a></li> @endif
    @if(checkAuth("view/all/subscribed/users")) <li><a href="{{ url('/view/all/subscribed/users') }}"><i class="feather-user-check"></i><span>Subscribed Users</span></a></li> @endif


    @if(count($sliderBannerModule) > 0 || count($termsPolicyModule) > 0 || count($testimonialModule) > 0 || count($customPageModule) > 0 || checkAuth("about/us/page") || checkAuth("view/all/faqs") || count($blogModule) > 0)
    <hr style="border-color: #c8c8c836; margin-top: 12px; margin-bottom: 12px;">
    <li class="menu-title" style="color: khaki; text-shadow: 1px 1px 2px black;">Content Management</li>
    @endif

    @if ($sliderBannerModule && count($sliderBannerModule) > 0)
    <li>
        <a href="javascript: void(0);" class="has-arrow"><i class="feather-image"></i><span>Sliders & Banners</span></a>
        <ul class="sub-menu" aria-expanded="false">
            @if(checkAuth("view/all/sliders")) <li><a href="{{ url('/view/all/sliders') }}">View All Sliders</a></li> @endif
            @if(checkAuth("view/all/banners")) <li><a href="{{ url('/view/all/banners') }}">View All Banners</a></li> @endif
            @if(checkAuth("view/promotional/banner")) <li><a href="{{ url('/view/promotional/banner') }}">Promotional Banner</a></li> @endif
        </ul>
    </li>
    @endif

    @if ($testimonialModule && count($testimonialModule) > 0)
    <li>
        <a href="javascript: void(0);" class="has-arrow"><i class="feather-message-square"></i><span>Testimonials</span></a>
        <ul class="sub-menu" aria-expanded="false">
            @if(checkAuth("add/testimonial")) <li><a href="{{ url('/add/testimonial') }}">Add New Testimonial</a></li> @endif
            @if(checkAuth("view/testimonials")) <li><a href="{{ url('/view/testimonials') }}">View All Testimonials</a></li> @endif
        </ul>
    </li>
    @endif

    @if ($blogModule && count($blogModule) > 0)
    <li>
        <a href="javascript: void(0);" class="has-arrow"><i class="feather-file-text"></i><span>Manage Blogs</span></a>
        <ul class="sub-menu" aria-expanded="false">
            @if(checkAuth("blog/categories")) <li><a href="{{ url('/blog/categories') }}">Blog Categories</a></li> @endif
            @if(checkAuth("add/new/blog")) <li><a href="{{ url('/add/new/blog') }}">Write a Blog</a></li> @endif
            @if(checkAuth("view/all/blogs")) <li><a href="{{ url('/view/all/blogs') }}">View All Blogs</a></li> @endif
        </ul>
    </li>
    @endif

    @if ($termsPolicyModule && count($termsPolicyModule) > 0)
    <li>
        <a href="javascript: void(0);" class="has-arrow"><i class="feather-alert-triangle"></i><span>Terms & Policies</span></a>
        <ul class="sub-menu" aria-expanded="false">
            @if(checkAuth("terms/and/condition")) <li><a href="{{ url('/terms/and/condition') }}">Terms & Condition</a></li> @endif
            @if(checkAuth("view/privacy/policy")) <li><a href="{{ url('/view/privacy/policy') }}">Privacy Policy</a></li> @endif
            @if(checkAuth("view/shipping/policy")) <li><a href="{{ url('/view/shipping/policy') }}">Shipping Policy</a></li> @endif
            @if(checkAuth("view/return/policy")) <li><a href="{{ url('/view/return/policy') }}">Return Policy</a></li> @endif
        </ul>
    </li>
    @endif

    @if ($customPageModule && count($customPageModule) > 0)
    <li>
        <a href="javascript: void(0);" class="has-arrow"><i class="feather-file-plus"></i><span>Custom Pages</span></a>
        <ul class="sub-menu" aria-expanded="false">
            @if(checkAuth("create/new/page")) <li><a href="{{ url('/create/new/page') }}">Create New Page</a></li> @endif
            @if(checkAuth("view/all/pages")) <li><a href="{{ url('/view/all/pages') }}">View All Pages</a></li> @endif
        </ul>
    </li>
    @endif

    @if(checkAuth("about/us/page")) <li><a href="{{ url('/about/us/page') }}"><i class="feather-globe"></i><span>About Us</span></a></li> @endif
    @if(checkAuth("view/all/faqs")) <li><a href="{{ url('/view/all/faqs') }}"><i class="far fa-question-circle"></i><span>FAQ's</span></a></li> @endif





    @if(checkAuth("view/permission/routes") || checkAuth("view/user/roles") || checkAuth("view/user/role/permission") || checkAuth("view/system/users"))
    <hr style="border-color: #c8c8c836; margin-top: 12px; margin-bottom: 5px;">
    <li class="menu-title" style="color: khaki; text-shadow: 1px 1px 2px black;">User Role Permission</li>
    @endif

    @if(checkAuth("view/system/users")) <li><a href="{{ url('/view/system/users') }}"><i class="fas fa-user-shield"></i><span>System Users</span></a></li> @endif
    @if(checkAuth("view/permission/routes")) <li><a href="{{ url('/view/permission/routes') }}"><i class="feather-git-merge"></i><span>Permission Routes</span></a></li> @endif
    @if(checkAuth("view/user/roles")) <li><a href="{{ url('/view/user/roles') }}"><i class="feather-user-plus"></i><span>User Roles</span></a></li> @endif
    @if(checkAuth("view/user/role/permission")) <li><a href="{{ url('/view/user/role/permission') }}"><i class="mdi mdi-security"></i><span>Assign Role Permission</span></a></li> @endif

    <hr style="border-color: #c8c8c836; margin-top: 12px; margin-bottom: 12px;">
    @if ($demoProductsModule && count($demoProductsModule) > 0)
    <li>
        <a href="javascript: void(0);" class="has-arrow"><i class="feather-box"></i><span>Demo Products</span></a>
        <ul class="sub-menu" aria-expanded="false">
            @if(checkAuth("generate/demo/products"))<li><a href="{{ url('/generate/demo/products') }}">Generate Products</a></li>@endif
            @if(checkAuth("remove/demo/products/page"))<li><a href="{{ url('/remove/demo/products') }}">Remove Products</a></li>@endif
        </ul>
    </li>
    @endif
    @if(checkAuth("clear/cache")) <li><a href="{{ url('/clear/cache') }}"><i class="feather-rotate-cw"></i><span>Clear Cache</span></a></li> @endif
    <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="feather-log-out"></i><span>Logout</span></a></li>

</ul>