<?php

use App\Models\Product; 
use App\Http\Middleware\DemoMode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckUserType;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\PosController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CkeditorController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\WishListController;
use App\Http\Controllers\WithdrawController;
use App\Http\Controllers\PromoCodeController;
use App\Http\Controllers\CustomPageController;
use App\Http\Controllers\SmsServiceController;
use App\Http\Controllers\GeneralInfoController;
use App\Http\Controllers\ProductSizeController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProductColorController;
use App\Http\Controllers\ChildCategoryController;
use App\Http\Controllers\ContactRequestontroller;
use App\Http\Controllers\Outlet\OutletController;
use App\Http\Controllers\SupportTicketController;
use App\Http\Controllers\Account\LedgerController;
use App\Http\Controllers\DeliveryChargeController;
use App\Http\Controllers\TermsAndPolicyController;
use App\Http\Controllers\Account\AccountController;
use App\Http\Controllers\Account\ExpenseController;
use App\Http\Controllers\SubscribedUsersController;
use App\Http\Controllers\PermissionRoutesController;
use App\Http\Controllers\ProductSizeValueController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Account\PaymenttypeController;
use App\Http\Controllers\Account\TransactionController;
use App\Http\Controllers\Gallery\VideoGalleryController;
use App\Http\Controllers\Outlet\CustomerSourceController;
use App\Http\Controllers\Outlet\SupplierSourceController;
use App\Http\Controllers\Account\ExpenseCategoryController;
use App\Http\Controllers\Customer\CustomerCategoryController;
use App\Http\Controllers\Inventory\ProductSupplierController;
use App\Http\Controllers\Customer\CustomerEcommerceController;
use App\Http\Controllers\Inventory\ProductWarehouseController;
use App\Http\Controllers\Inventory\Models\ProductWarehouseRoom;
use App\Http\Controllers\Inventory\ProductPurchaseOrderController;
use App\Http\Controllers\Inventory\ProductWarehouseRoomController;
use App\Http\Controllers\Customer\CustomerContactHistoryController;
use App\Http\Controllers\Inventory\ProductPurchaseChargeController;
use App\Http\Controllers\Customer\CustomerNextContactDateController;
use App\Http\Controllers\Inventory\Models\ProductWarehouseRoomCartoon;
use App\Http\Controllers\Inventory\ProductPurchaseQuotationController;
use App\Http\Controllers\Inventory\ProductWarehouseRoomCartoonController;

Route::get('/', function () {
    return redirect('/login');
});

require __DIR__.'/cache.php';

// Auth::routes();
Auth::routes([
    'login' => true,
    'register' => false, // Registration Routes...
    'reset' => false, // Password Reset Routes...
    'verify' => false, // Email Verification Routes...
]);

Route::middleware([CheckUserType::class, DemoMode::class])->group(function(){
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/crm-home', [HomeController::class, 'crm_index'])->name('crm.home');
    Route::get('/accounts-home', [HomeController::class, 'accounts_index'])->name('accounts.home');
    Route::get('/change/password/page', [HomeController::class, 'changePasswordPage'])->name('changePasswordPage');
    Route::post('/change/password', [HomeController::class, 'changePassword'])->name('changePassword');
    Route::get('ckeditor', [CkeditorController::class, 'index']);
    Route::post('ckeditor/upload', [CkeditorController::class, 'upload'])->name('ckeditor.upload');
});


// payment routes start
Route::get('sslcommerz/order/payment', [PaymentController::class, 'orderPayment'])->name('order.payment');
Route::post('sslcommerz/success', [PaymentController::class, 'success'])->name('payment.success');
Route::post('sslcommerz/failure', [PaymentController::class, 'failure'])->name('failure');
Route::post('sslcommerz/cancel', [PaymentController::class, 'cancel'])->name('cancel');
Route::post('sslcommerz/ipn', [PaymentController::class, 'ipn'])->name('payment.ipn');
// payment routes end


// file manager routes start
Route::get('/file-manager', function () {
    return view('backend.file_manager');
})->middleware(['auth']);

Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});
// file manager routes end


require 'configRoutes.php';
require 'systemRoutes.php';


Route::group(['middleware' => ['auth', 'CheckUserType', 'DemoMode']], function () {

    Route::get('/view/payment/history', [HomeController::class, 'viewPaymentHistory'])->name('ViewPaymentHistory');

    // category routes
    Route::get('/add/new/category', [CategoryController::class, 'addNewCategory'])->name('AddNewCategory');
    Route::post('/save/new/category', [CategoryController::class, 'saveNewCategory'])->name('SaveNewCategory');
    Route::get('/view/all/category', [CategoryController::class, 'viewAllCategory'])->name('ViewAllCategory');
    Route::get('/delete/category/{slug}', [CategoryController::class, 'deleteCategory'])->name('DeleteCategory');
    Route::get('/feature/category/{slug}', [CategoryController::class, 'featureCategory'])->name('FeatureCategory');
    Route::get('/edit/category/{slug}', [CategoryController::class, 'editCategory'])->name('EditCategory');
    Route::post('/update/category', [CategoryController::class, 'updateCategory'])->name('UpdateCategory');
    Route::get('/rearrange/category', [CategoryController::class, 'rearrangeCategory'])->name('RearrangeCategory');
    Route::post('/save/rearranged/order', [CategoryController::class, 'saveRearrangeCategoryOrder'])->name('SaveRearrangeCategoryOrder');


    // subcategory routes
    Route::get('/add/new/subcategory', [SubcategoryController::class, 'addNewSubcategory'])->name('AddNewSubcategory');
    Route::post('/save/new/subcategory', [SubcategoryController::class, 'saveNewSubcategory'])->name('SaveNewSubcategory');
    Route::get('/view/all/subcategory', [SubcategoryController::class, 'viewAllSubcategory'])->name('ViewAllSubcategory');
    Route::get('/delete/subcategory/{slug}', [SubcategoryController::class, 'deleteSubcategory'])->name('DeleteSubcategory');
    Route::get('/feature/subcategory/{id}', [SubcategoryController::class, 'featureSubcategory'])->name('FeatureSubcategory');
    Route::get('/edit/subcategory/{slug}', [SubcategoryController::class, 'editSubcategory'])->name('EditSubcategory');
    Route::post('/update/subcategory', [SubcategoryController::class, 'updateSubcategory'])->name('UpdateSubcategory');
    Route::get('/rearrange/subcategory', [SubcategoryController::class, 'rearrangeSubcategory'])->name('RearrangeSubcategory');
    Route::post('/save/rearranged/subcategory', [SubcategoryController::class, 'saveRearrangedSubcategory'])->name('SaveRearrangedSubcategory');


    // childcategory routes
    Route::get('/add/new/childcategory', [ChildCategoryController::class, 'addNewChildcategory'])->name('AddNewChildcategory');
    Route::post('/category/wise/subcategory', [ChildCategoryController::class, 'subcategoryCategoryWise'])->name('SubcategoryCategoryWise');
    Route::post('/save/new/childcategory', [ChildCategoryController::class, 'saveNewChildcategory'])->name('SaveNewChildcategory');
    Route::get('/view/all/childcategory', [ChildCategoryController::class, 'viewAllChildcategory'])->name('ViewAllChildcategory');
    Route::get('/delete/childcategory/{slug}', [ChildCategoryController::class, 'deleteChildcategory'])->name('DeleteChildcategory');
    Route::get('/edit/childcategory/{slug}', [ChildCategoryController::class, 'editChildcategory'])->name('EditChildcategory');
    Route::post('/update/childcategory', [ChildCategoryController::class, 'updateChildcategory'])->name('UpdateChildcategory');


    // product routes
    Route::get('/add/new/product', [ProductController::class, 'addNewProduct'])->name('AddNewProduct');
    Route::post('/subcategory/wise/childcategory', [ProductController::class, 'childcategorySubcategoryWise'])->name('ChildcategorySubcategoryWise');
    Route::post('/save/new/product', [ProductController::class, 'saveNewProduct'])->name('SaveNewProduct');
    Route::get('/view/all/product', [ProductController::class, 'viewAllProducts'])->name('ViewAllProducts');
    Route::get('/delete/product/{slug}', [ProductController::class, 'deleteProduct'])->name('DeleteProduct');
    Route::get('/edit/product/{slug}', [ProductController::class, 'editProduct'])->name('EditProduct');
    Route::post('/update/product', [ProductController::class, 'updateProduct'])->name('UpdateProduct');
    Route::post('/add/another/variant', [ProductController::class, 'addAnotherVariant'])->name('AddAnotherVariant');
    Route::get('/delete/product/variant/{id}', [ProductController::class, 'deleteProductVariant'])->name('DeleteProductVariant');
    Route::get('/products/from/excel', [ProductController::class, 'productsFromExcel'])->name('ProductsFromExcel');
    Route::post('/upload/product/from/excel', [ProductController::class, 'uploadProductsFromExcel'])->name('UploadProductsFromExcel');
    // demo products route
    Route::get('generate/demo/products', [ProductController::class, 'generateDemoProducts'])->name('GenerateDemoProducts');
    Route::post('save/generated/demo/products', [ProductController::class, 'saveGeneratedDemoProducts'])->name('SaveGeneratedDemoProducts');
    Route::get('remove/demo/products/page', [ProductController::class, 'removeDemoProductsPage'])->name('RemoveDemoProductsPage');
    Route::get('remove/demo/products', [ProductController::class, 'removeDemoProducts'])->name('RemoveDemoProducts');


    // product review
    Route::get('/view/product/reviews', [ProductController::class, 'viewAllProductReviews'])->name('ViewAllProductReviews');
    Route::get('/approve/product/review/{slug}', [ProductController::class, 'approveProductReview'])->name('ApproveProductReview');
    Route::get('/delete/product/review/{slug}', [ProductController::class, 'deleteProductReview'])->name('DeleteProductReview');
    Route::get('/get/product/review/info/{id}', [ProductController::class, 'getProductReviewInfo'])->name('GetProductReviewInfo');
    Route::post('/submit/reply/product/review', [ProductController::class, 'submitReplyOfProductReview'])->name('SubmitReplyOfProductReview');


    // product question answer
    Route::get('/view/product/question/answer', [ProductController::class, 'viewAllQuestionAnswer'])->name('ViewAllQuestionAnswer');
    Route::get('/delete/question/answer/{id}', [ProductController::class, 'deleteQuestionAnswer'])->name('DeleteQuestionAnswer');
    Route::get('/get/question/answer/info/{id}', [ProductController::class, 'getQuestionAnswerInfo'])->name('GetQuestionAnswerInfo');
    Route::post('/submit/question/answer', [ProductController::class, 'submitAnswerOfQuestion'])->name('SubmitAnswerOfQuestion');


    // product warehouse routes
    Route::get('/add/new/product-warehouse', [ProductWarehouseController::class, 'addNewProductWarehouse'])->name('AddNewProductWarehouse');
    //  Route::post('/subcategory/wise/childcategory', [ProductController::class, 'childcategorySubcategoryWise'])->name('ChildcategorySubcategoryWise');
    Route::post('/save/new/product-warehouse', [ProductWarehouseController::class, 'saveNewProductWarehouse'])->name('SaveNewProductWarehouse');
    Route::get('/view/all/product-warehouse', [ProductWarehouseController::class, 'viewAllProductWarehouse'])->name('ViewAllProductWarehouse');
    Route::get('/delete/product-warehouse/{slug}', [ProductWarehouseController::class, 'deleteProductWarehouse'])->name('DeleteProductWarehouse');
    Route::get('/edit/product-warehouse/{slug}', [ProductWarehouseController::class, 'editProductWarehouse'])->name('EditProductWarehouse');
    Route::post('/update/product-warehouse', [ProductWarehouseController::class, 'updateProductWarehouse'])->name('UpdateProductWarehouse');
    //  Route::post('/add/another/variant', [ProductController::class, 'addAnotherVariant'])->name('AddAnotherVariant');
    //  Route::get('/delete/product/variant/{id}', [ProductController::class, 'deleteProductVariant'])->name('DeleteProductVariant');
    //  Route::get('/products/from/excel', [ProductController::class, 'productsFromExcel'])->name('ProductsFromExcel');
    //  Route::post('/upload/product/from/excel', [ProductController::class, 'uploadProductsFromExcel'])->name('UploadProductsFromExcel');


     // product warehouse rooms routes
     Route::get('/add/new/product-warehouse-room', [ProductWarehouseRoomController::class, 'addNewProductWarehouseRoom'])->name('AddNewProductWarehouseRoom');
     //  Route::post('/subcategory/wise/childcategory', [ProductController::class, 'childcategorySubcategoryWise'])->name('ChildcategorySubcategoryWise');
     Route::post('/save/new/product-warehouse-room', [ProductWarehouseRoomController::class, 'saveNewProductWarehouseRoom'])->name('SaveNewProductWarehouseRoom');
     Route::get('/view/all/product-warehouse-room', [ProductWarehouseRoomController::class, 'viewAllProductWarehouseRoom'])->name('ViewAllProductWarehouseRoom');
     Route::get('/delete/product-warehouse-room/{slug}', [ProductWarehouseRoomController::class, 'deleteProductWarehouseRoom'])->name('DeleteProductWarehouseRoom');
     Route::get('/edit/product-warehouse-room/{slug}', [ProductWarehouseRoomController::class, 'editProductWarehouseRoom'])->name('EditProductWarehouseRoom');
     Route::post('/update/product-warehouse-room', [ProductWarehouseRoomController::class, 'updateProductWarehouseRoom'])->name('UpdateProductWarehouseRoom');
     Route::post('/get-product-warehouse-rooms', [ProductWarehouseRoomController::class, 'getProductWarehouseRooms'])->name('get.product.warehouse.rooms');
     Route::get('/get-warehouse-rooms/{warehouseId}', function ($warehouseId) {
        $rooms = ProductWarehouseRoom::where('product_warehouse_id', $warehouseId)->get();
        return response()->json(['rooms' => $rooms]);
    });
    

     // product warehouse room cartoon routes
     Route::get('/add/new/product-warehouse-room-cartoon', [ProductWarehouseRoomCartoonController::class, 'addNewProductWarehouseRoomCartoon'])->name('AddNewProductWarehouseRoomCartoon');
     //  Route::post('/subcategory/wise/childcategory', [ProductController::class, 'childcategorySubcategoryWise'])->name('ChildcategorySubcategoryWise');
     Route::post('/save/new/product-warehouse-room-cartoon', [ProductWarehouseRoomCartoonController::class, 'saveNewProductWarehouseRoomCartoon'])->name('SaveNewProductWarehouseRoomCartoon');
     Route::get('/view/all/product-warehouse-room-cartoon', [ProductWarehouseRoomCartoonController::class, 'viewAllProductWarehouseRoomCartoon'])->name('ViewAllProductWarehouseRoomCartoon');
     Route::get('/delete/product-warehouse-room-cartoon/{slug}', [ProductWarehouseRoomCartoonController::class, 'deleteProductWarehouseRoomCartoon'])->name('DeleteProductWarehouseRoom');
     Route::get('/edit/product-warehouse-room-cartoon/{slug}', [ProductWarehouseRoomCartoonController::class, 'editProductWarehouseRoomCartoon'])->name('EditProductWarehouseRoomCartoon');
     Route::post('/update/product-warehouse-room-cartoon', [ProductWarehouseRoomCartoonController::class, 'updateProductWarehouseRoomCartoon'])->name('UpdateProductWarehouseRoomCartoon');
     Route::post('/get-product-warehouse-room-cartoons', [ProductWarehouseRoomCartoonController::class, 'getProductWarehouseRoomCartoon'])->name('get.product.warehouse.room.cartoon');

    // product supplier routes
    Route::get('/add/new/product-supplier', [ProductSupplierController::class, 'addNewProductSupplier'])->name('AddNewProductSupplier');    
    Route::post('/save/new/product-supplier', [ProductSupplierController::class, 'saveNewProductSupplier'])->name('SaveNewProductSupplier');
    Route::get('/view/all/product-supplier', [ProductSupplierController::class, 'viewAllProductSupplier'])->name('ViewAllProductSupplier');
    Route::get('/delete/product-supplier/{slug}', [ProductSupplierController::class, 'deleteProductSupplier'])->name('DeleteProductSupplier');
    Route::get('/edit/product-supplier/{slug}', [ProductSupplierController::class, 'editProductSupplier'])->name('EditProductSupplier');
    Route::post('/update/product-supplier', [ProductSupplierController::class, 'updateProductSupplier'])->name('UpdateProductSupplier');


    Route::get('/get-warehouse-rooms', [ProductWarehouseController::class, 'getWarehouseRooms']);
    Route::get('/get-warehouse-room-cartoons', [ProductWarehouseController::class, 'getWarehouseRoomCartoons']);

    // Route::get('get-rooms/{warehouseId}', [WarehouseController::class, 'getRooms'])->name('get.rooms');
    // Route::get('get-cartoons/{roomId}', [WarehouseController::class, 'getCartoons'])->name('get.cartoons');

    Route::get('/api/get-rooms/{warehouseId}', [ProductWarehouseController::class, 'apiGetetWarehouseRooms']);
    Route::get('/api/get-cartoons/{warehouseId}/{roomId}', [ProductWarehouseController::class, 'apiGetetWarehouseRoomCartoons']);


    // purchase product quotation routes
    Route::get('/add/new/purchase-product/quotation', [ProductPurchaseQuotationController::class, 'addNewPurchaseProductQuotation'])->name('AddNewPurchaseProductQuotation');    
    Route::post('/save/new/purchase-product/quotation', [ProductPurchaseQuotationController::class, 'saveNewPurchaseProductQuotation'])->name('SaveNewPurchaseProductQuotation');
    Route::get('/view/all/purchase-product/quotation', [ProductPurchaseQuotationController::class, 'viewAllPurchaseProductQuotation'])->name('ViewAllPurchaseProductQuotation');
    Route::get('/delete/purchase-product/quotation/{slug}', [ProductPurchaseQuotationController::class, 'deletePurchaseProductQuotation'])->name('DeletePurchaseProductQuotation');
    Route::get('/edit/purchase-product/quotation/{slug}', [ProductPurchaseQuotationController::class, 'editPurchaseProductQuotation'])->name('EditPurchaseProductQuotation');
    Route::get('/edit/purchase-product/sales/quotation/{slug}', [ProductPurchaseQuotationController::class, 'editPurchaseProductSalesQuotation'])->name('EditPurchaseProductSalesQuotation');
    Route::get('api/edit/purchase-product/quotation/{slug}', [ProductPurchaseQuotationController::class, 'apiEditPurchaseProduct'])->name('ApiEditPurchaseProductQuotation');
    Route::post('/update/purchase-product/quotation', [ProductPurchaseQuotationController::class, 'updatePurchaseProductQuotation'])->name('UpdatePurchaseProductQuotation');
    Route::post('/update/purchase-product/sales/quotation', [ProductPurchaseQuotationController::class, 'updatePurchaseProductSalesQuotation'])->name('UpdatePurchaseProductSalesQuotation');

    Route::get('/api/products/search', [ProductPurchaseQuotationController::class, 'searchProduct'])->name('SearchProduct');    

    // purchase product order routes
    Route::get('/add/new/purchase-product/order', [ProductPurchaseOrderController::class, 'addNewPurchaseProductOrder'])->name('AddNewPurchaseProductOrder');    
    Route::post('/save/new/purchase-product/order', [ProductPurchaseOrderController::class, 'saveNewPurchaseProductOrder'])->name('SaveNewPurchaseProductOrder');
    Route::get('/view/all/purchase-product/order', [ProductPurchaseOrderController::class, 'viewAllPurchaseProductOrder'])->name('ViewAllPurchaseProductOrder');
    Route::get('/delete/purchase-product/order/{slug}', [ProductPurchaseOrderController::class, 'deletePurchaseProductOrder'])->name('DeletePurchaseProductOrder');
    Route::get('/edit/purchase-product/order/{slug}', [ProductPurchaseOrderController::class, 'editPurchaseProductOrder'])->name('EditPurchaseProductOrder');
    Route::get('/edit/purchase-product/order/confirm/{slug}', [ProductPurchaseOrderController::class, 'editPurchaseProductOrderConfirm'])->name('EditPurchaseProductOrderConfirm');
    Route::get('api/edit/purchase-product/order/{slug}', [ProductPurchaseOrderController::class, 'apiEditPurchaseProduct'])->name('ApiEditPurchaseProductOrder');
    Route::post('/update/purchase-product/order', [ProductPurchaseOrderController::class, 'updatePurchaseProductOrder'])->name('UpdatePurchaseProductOrder');
    

    // purchase product other charge
    Route::get('/add/new/purchase-product/charge', [ProductPurchaseChargeController::class, 'addNewPurchaseProductCharge'])->name('AddNewPurchaseProductCharge');    
    Route::post('/save/new/purchase-product/charge', [ProductPurchaseChargeController::class, 'saveNewPurchaseProductCharge'])->name('SaveNewPurchaseProductCharge');
    Route::get('/view/all/purchase-product/charge', [ProductPurchaseChargeController::class, 'viewAllPurchaseProductCharge'])->name('ViewAllPurchaseProductCharge');
    Route::get('/delete/purchase-product/charge/{slug}', [ProductPurchaseChargeController::class, 'deletePurchaseProductCharge'])->name('DeletePurchaseProductCharge');
    Route::get('/edit/purchase-product/charge/{slug}', [ProductPurchaseChargeController::class, 'editPurchaseProductCharge'])->name('EditPurchaseProductCharge');      
    Route::post('/update/purchase-product/charge', [ProductPurchaseChargeController::class, 'updatePurchaseProductCharge'])->name('UpdatePurchaseProductCharge');


    // Customer Source Type 
    Route::get('/add/new/customer-source', [CustomerSourceController::class, 'addNewCustomerSource'])->name('AddNewCustomerSource');    
    Route::post('/save/new/customer-source', [CustomerSourceController::class, 'saveNewCustomerSource'])->name('SaveNewCustomerSource');
    Route::get('/view/all/customer-source', [CustomerSourceController::class, 'viewAllCustomerSource'])->name('ViewAllCustomerSource');
    Route::get('/delete/customer-source/{slug}', [CustomerSourceController::class, 'deleteCustomerSource'])->name('DeleteCustomerSource');
    Route::get('/edit/customer-source/{slug}', [CustomerSourceController::class, 'editCustomerSource'])->name('EditCustomerSource');      
    Route::post('/update/customer-source', [CustomerSourceController::class, 'updateCustomerSource'])->name('UpdateCustomerSource');

    // Customer Category 
    Route::get('/add/new/customer-category', [CustomerCategoryController::class, 'addNewCustomerCategory'])->name('AddNewCustomerCategory');    
    Route::post('/save/new/customer-category', [CustomerCategoryController::class, 'saveNewCustomerCategory'])->name('SaveNewCustomerCategory');
    Route::get('/view/all/customer-category', [CustomerCategoryController::class, 'viewAllCustomerCategory'])->name('ViewAllCustomerCategory');
    Route::get('/delete/customer-category/{slug}', [CustomerCategoryController::class, 'deleteCustomerCategory'])->name('DeleteCustomerCategory');
    Route::get('/edit/customer-category/{slug}', [CustomerCategoryController::class, 'editCustomerCategory'])->name('EditCustomerCategory');      
    Route::post('/update/customer-category', [CustomerCategoryController::class, 'updateCustomerCategory'])->name('UpdateCustomerCategory');

    // Customer Ecommerce
    Route::get('/add/new/customer-ecommerce', [CustomerEcommerceController::class, 'addNewCustomerEcommerce'])->name('AddNewCustomerEcommerce');    
    Route::post('/save/new/customer-ecommerce', [CustomerEcommerceController::class, 'saveNewCustomerEcommerce'])->name('SaveNewCustomerEcommerce');
    Route::get('/view/all/customer-ecommerce', [CustomerEcommerceController::class, 'viewAllCustomerEcommerce'])->name('ViewAllCustomerEcommerce');
    Route::get('/delete/customer-ecommerce/{slug}', [CustomerEcommerceController::class, 'deleteCustomerEcommerce'])->name('DeleteCustomerEcommerce');
    Route::get('/edit/customer-ecommerce/{slug}', [CustomerEcommerceController::class, 'editCustomerEcommerce'])->name('EditCustomerEcommerce');      
    Route::post('/update/customer-ecommerce', [CustomerEcommerceController::class, 'updateCustomerEcommerce'])->name('UpdateCustomerEcommerce');

    // Customer 
    Route::get('/add/new/customers', [CustomerController::class, 'addNewCustomer'])->name('AddNewCustomers');    
    Route::post('/save/new/customers', [CustomerController::class, 'saveNewCustomer'])->name('SaveNewCustomers');
    Route::get('/view/all/customer', [CustomerController::class, 'viewAllCustomer'])->name('ViewAllCustomer');
    Route::get('/delete/customers/{slug}', [CustomerController::class, 'deleteCustomer'])->name('DeleteCustomers');
    Route::get('/edit/customers/{slug}', [CustomerController::class, 'editCustomer'])->name('EditCustomers');      
    Route::post('/update/customers', [CustomerController::class, 'updateCustomer'])->name('UpdateCustomers');

    // Customer Contact History
    Route::get('/add/new/customer-contact-history', [CustomerContactHistoryController::class, 'addNewCustomerContactHistory'])->name('AddNewCustomerContactHistories');    
    Route::post('/save/new/customer-contact-history', [CustomerContactHistoryController::class, 'saveNewCustomerContactHistory'])->name('SaveNewCustomerContactHistories');
    Route::get('/view/all/customer-contact-history', [CustomerContactHistoryController::class, 'viewAllCustomerContactHistory'])->name('ViewAllCustomerContactHistories');
    Route::get('/delete/customer-contact-history/{slug}', [CustomerContactHistoryController::class, 'deleteCustomerContactHistory'])->name('DeleteCustomerContactHistories');
    Route::get('/edit/customer-contact-history/{slug}', [CustomerContactHistoryController::class, 'editCustomerContactHistory'])->name('EditCustomerContactHistories');      
    Route::post('/update/customer-contact-history', [CustomerContactHistoryController::class, 'updateCustomerContactHistory'])->name('UpdateCustomerContactHistories');

    // Customer Next Contact Date
    Route::get('/add/new/customer-next-contact-date', [CustomerNextContactDateController::class, 'addNewCustomerNextContactDate'])->name('AddNewCustomerNextContactDate');    
    Route::post('/save/new/customer-next-contact-date', [CustomerNextContactDateController::class, 'saveNewCustomerNextContactDate'])->name('SaveNewCustomerNextContactDate');
    Route::get('/view/all/customer-next-contact-date', [CustomerNextContactDateController::class, 'viewAllCustomerNextContactDate'])->name('ViewAllCustomerNextContactDate');
    Route::get('/delete/customer-next-contact-date/{slug}', [CustomerNextContactDateController::class, 'deleteCustomerNextContactDate'])->name('DeleteCustomerNextContactDate');
    Route::get('/edit/customer-next-contact-date/{slug}', [CustomerNextContactDateController::class, 'editCustomerNextContactDate'])->name('EditCustomerNextContactDate');      
    Route::post('/update/customer-next-contact-date', [CustomerNextContactDateController::class, 'updateCustomerNextContactDate'])->name('UpdateCustomerNextContactDate');

    // Supplier Source Type 
    Route::get('/add/new/supplier-source', [SupplierSourceController::class, 'addNewSupplierSource'])->name('AddNewSupplierSource');    
    Route::post('/save/new/supplier-source', [SupplierSourceController::class, 'saveNewSupplierSource'])->name('SaveNewSupplierSource');
    Route::get('/view/all/supplier-source', [SupplierSourceController::class, 'viewAllSupplierSource'])->name('ViewAllSupplierSource');
    Route::get('/delete/supplier-source/{slug}', [SupplierSourceController::class, 'deleteSupplierSource'])->name('DeleteSupplierSource');
    Route::get('/edit/supplier-source/{slug}', [SupplierSourceController::class, 'editSupplierSource'])->name('EditSupplierSource');      
    Route::post('/update/supplier-source', [SupplierSourceController::class, 'updateSupplierSource'])->name('UpdateSupplierSource');

    // Outlets
     Route::get('/add/new/outlet', [OutletController::class, 'addNewOutlet'])->name('AddNewOutlet');    
     Route::post('/save/new/outlet', [OutletController::class, 'saveNewOutlet'])->name('SaveNewOutlet');
     Route::get('/view/all/outlet', [OutletController::class, 'viewAllOutlet'])->name('ViewAllOutlet');
     Route::get('/delete/outlet/{slug}', [OutletController::class, 'deleteOutlet'])->name('DeleteOutlet');
     Route::get('/edit/outlet/{slug}', [OutletController::class, 'editOutlet'])->name('EditOutlet');      
     Route::post('/update/outlet', [OutletController::class, 'updateOutlet'])->name('UpdateOutlet');

    // Video Gallery
    Route::get('/add/new/video-gallery', [VideoGalleryController::class, 'addNewVideoGallery'])->name('AddNewVideoGallery');    
    Route::post('/save/new/video-gallery', [VideoGalleryController::class, 'saveNewVideoGallery'])->name('SaveNewVideoGallery');
    Route::get('/view/all/video-gallery', [VideoGalleryController::class, 'viewAllVideoGallery'])->name('ViewAllVideoGallery');
    Route::get('/delete/video-gallery/{slug}', [VideoGalleryController::class, 'deleteVideoGallery'])->name('DeleteVideoGallery');
    Route::get('/edit/video-gallery/{slug}', [VideoGalleryController::class, 'editVideoGallery'])->name('EditVideoGallery');      
    Route::post('/update/video-gallery', [VideoGalleryController::class, 'updateVideoGallery'])->name('UpdateVideoGallery');

    // Payment Type
    Route::get('/add/new/payment-type', [PaymenttypeController::class, 'addNewPaymentType'])->name('AddNewPaymentType');    
    Route::post('/save/new/payment-type', [PaymenttypeController::class, 'saveNewPaymentType'])->name('SaveNewPaymentType');
    Route::get('/view/all/payment-type', [PaymenttypeController::class, 'viewAllPaymentType'])->name('ViewAllPaymentType');
    Route::get('/delete/payment-type/{slug}', [PaymenttypeController::class, 'deletePaymentType'])->name('DeletePaymentType');
    Route::get('/edit/payment-type/{slug}', [PaymenttypeController::class, 'editPaymentType'])->name('EditPaymentType');      
    Route::post('/update/payment-type', [PaymenttypeController::class, 'updatePaymentType'])->name('UpdatePaymentType');


    // Expense Category
    Route::get('/add/new/expense-category', [ExpenseCategoryController::class, 'addNewExpenseCategory'])->name('AddNewExpenseCategory');    
    Route::post('/save/new/expense-category', [ExpenseCategoryController::class, 'saveNewExpenseCategory'])->name('SaveNewExpenseCategory');
    Route::get('/view/all/expense-category', [ExpenseCategoryController::class, 'viewAllExpenseCategory'])->name('ViewAllExpenseCategory');
    Route::get('/delete/expense-category/{slug}', [ExpenseCategoryController::class, 'deleteExpenseCategory'])->name('DeleteExpenseCategory');
    Route::get('/edit/expense-category/{slug}', [ExpenseCategoryController::class, 'editExpenseCategory'])->name('EditExpenseCategory');      
    Route::post('/update/expense-category', [ExpenseCategoryController::class, 'updateExpenseCategory'])->name('UpdateExpenseCategory');

    // Account
    Route::get('/add/new/ac-account', [AccountController::class, 'addNewAcAccount'])->name('AddNewAcAccount');    
    Route::post('/save/new/ac-account', [AccountController::class, 'saveNewAcAccount'])->name('SaveNewAcAccount');
    Route::get('/view/all/ac-account', [AccountController::class, 'viewAllAcAccount'])->name('ViewAllAcAccount');
    Route::get('/delete/ac-account/{slug}', [AccountController::class, 'deleteAcAccount'])->name('DeleteAcAccount');
    Route::get('/edit/ac-account/{slug}', [AccountController::class, 'editAcAccount'])->name('EditAcAccount');      
    Route::post('/update/ac-account', [AccountController::class, 'updateAcAccount'])->name('UpdateAcAccount')
    ;
    Route::get('/get/ac-account/json', [AccountController::class, 'getJsonAcAccount'])->name('GetJsonAcAccount');    
    Route::get('/get/ac-account-espense/json', [AccountController::class, 'getJsonAcAccountExpense'])->name('GetJsonAcAccountExpense');    


    // Expense 
    Route::get('/add/new/expense', [ExpenseController::class, 'addNewExpense'])->name('AddNewExpense');    
    Route::post('/save/new/expense', [ExpenseController::class, 'saveNewExpense'])->name('SaveNewExpense');
    Route::get('/view/all/expense', [ExpenseController::class, 'viewAllExpense'])->name('ViewAllExpense');
    Route::get('/delete/expense/{slug}', [ExpenseController::class, 'deleteExpense'])->name('DeleteExpense');
    Route::get('/edit/expense/{slug}', [ExpenseController::class, 'editExpense'])->name('EditExpense');      
    Route::post('/update/expense', [ExpenseController::class, 'updateExpense'])->name('UpdateExpense');


    // Deposit 
    Route::get('/add/new/deposit', [TransactionController::class, 'addNewDeposit'])->name('AddNewDeposit');    
    Route::post('/save/new/deposit', [TransactionController::class, 'saveNewDeposit'])->name('SaveNewDeposit');
    Route::get('/view/all/deposit', [TransactionController::class, 'viewAllDeposit'])->name('ViewAllDeposit');
    Route::get('/delete/deposit/{slug}', [TransactionController::class, 'deleteDeposit'])->name('DeleteDeposit');
    Route::get('/edit/deposit/{slug}', [TransactionController::class, 'editDeposit'])->name('EditDeposit');      
    Route::post('/update/deposit', [TransactionController::class, 'updateDeposit'])->name('UpdateDeposit');


    // Ledger 
    Route::get('/ledger', [LedgerController::class, 'index'])->name('ledger.index');    
    Route::get('/ledger/journal', [LedgerController::class, 'journal'])->name('journal.index');
    Route::get('/ledger/balance-sheet', [LedgerController::class, 'balanceSheet'])->name('ledger.balance_sheet');
    Route::get('/ledger/income-statement', [LedgerController::class, 'incomeStatement'])->name('ledger.income_statement');

    // Route::get('/search/products',  [ProductController::class, 'searchProduct'])->name('searchProduct');
    // Route::get('/search/products', function (Illuminate\Http\Request $request) {
    //     $query = request()->get('search');
    //     $products = Product::where('name', 'LIKE', "%{$query}%")
    //                   ->select('id', 'name', 'price')
    //                   ->limit(10)
    //                   ->get();
    
    //     return response()->json($products);
    // });
        // #7EA01D
        // #846F51
    

    // terms and policies routes
    Route::get('/terms/and/condition', [TermsAndPolicyController::class, 'viewTermsAndCondition'])->name('ViewTermsAndCondition');
    Route::post('/update/terms', [TermsAndPolicyController::class, 'updateTermsAndCondition'])->name('UpdateTermsAndCondition');
    Route::get('/view/privacy/policy', [TermsAndPolicyController::class, 'viewPrivacyPolicy'])->name('ViewPrivacyPolicy');
    Route::post('/update/privacy/policy', [TermsAndPolicyController::class, 'updatePrivacyPolicy'])->name('UpdatePrivacyPolicy');
    Route::get('/view/shipping/policy', [TermsAndPolicyController::class, 'viewShippingPolicy'])->name('ViewShippingPolicy');
    Route::post('/update/shipping/policy', [TermsAndPolicyController::class, 'updateShippingPolicy'])->name('UpdateShippingPolicy');
    Route::get('/view/return/policy', [TermsAndPolicyController::class, 'viewReturnPolicy'])->name('ViewReturnPolicy');
    Route::post('/update/return/policy', [TermsAndPolicyController::class, 'updateReturnPolicy'])->name('UpdateReturnPolicy');


    // customers and system users routes
    Route::get('/view/all/customers', [UserController::class, 'viewAllCustomers'])->name('ViewAllCustomers');
    Route::get('/view/system/users', [UserController::class, 'viewAllSystemUsers'])->name('ViewAllSystemUsers');
    Route::get('/add/new/system/user', [UserController::class, 'addNewSystemUsers'])->name('AddNewSystemUsers');
    Route::post('/create/system/user', [UserController::class, 'createSystemUsers'])->name('CreateSystemUsers');
    Route::get('/edit/system/user/{id}', [UserController::class, 'editSystemUser'])->name('EditSystemUser');
    Route::get('/delete/system/user/{id}', [UserController::class, 'deleteSystemUser'])->name('DeleteSystemUser');
    Route::post('/update/system/user', [UserController::class, 'updateSystemUser'])->name('UpdateSystemUser');
    Route::get('make/user/superadmin/{id}', [UserController::class, 'makeSuperAdmin'])->name('MakeSuperAdmin');
    Route::get('revoke/user/superadmin/{id}', [UserController::class, 'revokeSuperAdmin'])->name('RevokeSuperAdmin');
    Route::get('/change/user/status/{id}', [UserController::class, 'changeUserStatus'])->name('ChangeUserStatus');
    Route::get('/delete/customer/{id}', [UserController::class, 'deleteCustomer'])->name('DeleteCustomer');
    Route::get('download/customer/excel', [UserController::class, 'downloadCustomerExcel'])->name('DownloadCustomerExcel');


    // general info routes
    Route::get('/about/us/page', [GeneralInfoController::class, 'aboutUsPage'])->name('AboutUsPage');
    Route::post('/update/about/us', [GeneralInfoController::class, 'updateAboutUsPage'])->name('UpdateAboutUsPage');
    Route::get('/general/info', [GeneralInfoController::class, 'generalInfo'])->name('GeneralInfo');
    Route::post('/update/general/info', [GeneralInfoController::class, 'updateGeneralInfo'])->name('UpdateGeneralInfo');
    Route::get('/website/theme/page', [GeneralInfoController::class, 'websiteThemePage'])->name('WebsiteThemePage');
    Route::post('/update/website/theme/color', [GeneralInfoController::class, 'updateWebsiteThemeColor'])->name('UpdateWebsiteThemeColor');
    Route::get('/social/media/page', [GeneralInfoController::class, 'socialMediaPage'])->name('SocialMediaPage');
    Route::post('/update/social/media/link', [GeneralInfoController::class, 'updateSocialMediaLinks'])->name('UpdateSocialMediaLinks');
    Route::get('/seo/homepage', [GeneralInfoController::class, 'seoHomePage'])->name('SeoHomePage');
    Route::post('/update/seo/homepage', [GeneralInfoController::class, 'updateSeoHomePage'])->name('UpdateSeoHomePage');
    Route::get('/custom/css/js', [GeneralInfoController::class, 'customCssJs'])->name('CustomCssJs');
    Route::post('/update/custom/css/js', [GeneralInfoController::class, 'updateCustomCssJs'])->name('UpdateCustomCssJs');
    Route::get('/social/chat/script/page', [GeneralInfoController::class, 'socialChatScriptPage'])->name('SocialChatScriptPage');
    Route::post('/update/google/recaptcha', [GeneralInfoController::class, 'updateGoogleRecaptcha'])->name('UpdateGoogleRecaptcha');
    Route::post('/update/google/analytic', [GeneralInfoController::class, 'updateGoogleAnalytic'])->name('UpdateGoogleAnalytic');
    Route::post('/update/google/tag/manager', [GeneralInfoController::class, 'updateGoogleTagManager'])->name('updateGoogleTagManager');
    Route::post('/update/social/login/info', [GeneralInfoController::class, 'updateSocialLogin'])->name('UpdateSocialLogin');
    Route::post('/update/facebook/pixel', [GeneralInfoController::class, 'updateFacebookPixel'])->name('UpdateFacebookPixel');
    Route::post('/update/messenger/chat/info', [GeneralInfoController::class, 'updateMessengerChat'])->name('UpdateMessengerChat');
    Route::post('/update/tawk/chat/info', [GeneralInfoController::class, 'updateTawkChat'])->name('UpdateTawkChat');
    Route::post('/update/crisp/chat/info', [GeneralInfoController::class, 'updateCrispChat'])->name('UpdateCrispChat');
    Route::get('/change/guest/checkout/status', [GeneralInfoController::class, 'changeGuestCheckoutStatus'])->name('ChangeGuestCheckoutStatus');


    // faq routes
    Route::get('/view/all/faqs', [FaqController::class, 'viewAllFaqs'])->name('ViewAllFaqs');
    Route::get('/add/new/faq', [FaqController::class, 'addNewFaq'])->name('AddNewFaq');
    Route::post('/save/faq', [FaqController::class, 'saveFaq'])->name('SaveFaq');
    Route::get('/delete/faq/{slug}', [FaqController::class, 'deleteFaq'])->name('DeleteFaq');
    Route::get('/edit/faq/{slug}', [FaqController::class, 'editFaq'])->name('EditFaq');
    Route::post('/update/faq', [FaqController::class, 'updateFaq'])->name('UpdateFaq');


    // sliders and banners routes
    Route::get('/view/all/sliders', [BannerController::class, 'viewAllSliders'])->name('ViewAllSliders');
    Route::get('/add/new/slider', [BannerController::class, 'addNewSlider'])->name('AddNewSlider');
    Route::post('/save/new/slider', [BannerController::class, 'saveNewSlider'])->name('SaveNewSlider');
    Route::get('/edit/slider/{slug}', [BannerController::class, 'editSlider'])->name('EditSlider');
    Route::post('/update/slider', [BannerController::class, 'updateSlider'])->name('UpdateSlider');
    Route::get('/rearrange/slider', [BannerController::class, 'rearrangeSlider'])->name('RearrangeSlider');
    Route::post('/update/slider/rearranged/order', [BannerController::class, 'updateRearrangedSliders'])->name('UpdateRearrangedSliders');
    Route::get('/delete/data/{slug}', [BannerController::class, 'deleteData'])->name('DeleteSliderBanner');
    Route::get('/view/all/banners', [BannerController::class, 'viewAllBanners'])->name('ViewAllBanners');
    Route::get('/add/new/banner', [BannerController::class, 'addNewBanner'])->name('AddNewBanner');
    Route::post('/save/new/banner', [BannerController::class, 'saveNewBanner'])->name('SaveNewBanner');
    Route::get('/edit/banner/{slug}', [BannerController::class, 'editBanner'])->name('EditBanner');
    Route::post('/update/banner', [BannerController::class, 'updateBanner'])->name('UpdateBanner');
    Route::get('/rearrange/banners', [BannerController::class, 'rearrangeBanners'])->name('RearrangeBanners');
    Route::post('/update/banners/rearranged/order', [BannerController::class, 'updateRearrangedBanners'])->name('UpdateRearrangedBanners');
    Route::get('/view/promotional/banner', [BannerController::class, 'viewPromotionalBanner'])->name('ViewPromotionalBanner');
    Route::post('/update/promotional/banner', [BannerController::class, 'updatePromotionalBanner'])->name('UpdatePromotionalBanner');
    Route::get('/remove/promotional/banner/header/icon', [BannerController::class, 'removePromotionalHeaderIcon'])->name('RemovePromotionalHeaderIcon');
    Route::get('/remove/promotional/banner/product/image', [BannerController::class, 'removePromotionalProductImage'])->name('RemovePromotionalProductImage');
    Route::get('/remove/promotional/banner/bg/image', [BannerController::class, 'removePromotionalBackgroundImage'])->name('RemovePromotionalBackgroundImage');


    // contact request routes
    Route::get('/view/all/contact/requests', [ContactRequestontroller::class, 'viewAllContactRequests'])->name('ViewAllContactRequests');
    Route::get('/delete/contact/request/{id}', [ContactRequestontroller::class, 'deleteContactRequests'])->name('DeleteContactRequests');
    Route::get('/change/request/status/{id}', [ContactRequestontroller::class, 'changeRequestStatus'])->name('ChangeRequestStatus');
    
    
    // pos routes
    Route::get('/create/new/order', [PosController::class, 'createNewOrder'])->name('CreateNewOrder');
    Route::post('/product/live/search', [PosController::class, 'productLiveSearch'])->name('ProductLiveSearch');
    Route::post('/get/pos/product/variants', [PosController::class, 'getProductVariants'])->name('GetProductVariants');
    Route::post('/check/pos/product/variant', [PosController::class, 'checkProductVariant'])->name('CheckProductVariant');
    Route::post('/add/to/cart', [PosController::class, 'addToCart'])->name('AddToCart');
    Route::get('/remove/cart/item/{index}', [PosController::class, 'removeCartItem'])->name('RemoveCartItem');
    Route::get('/update/cart/item/{index}/{qty}', [PosController::class, 'updateCartItem'])->name('UpdateCartItem');
    Route::post('/save/new/customer', [PosController::class, 'saveNewCustomer'])->name('SaveNewCustomer');
    Route::get('/update/order/total/{shipping_charge}/{discount}', [PosController::class, 'updateOrderTotal'])->name('UpdateOrderTotal');
    Route::post('/apply/coupon', [PosController::class, 'applyCoupon'])->name('ApplyCoupon');
    Route::post('district/wise/thana', [PosController::class, 'districtWiseThana'])->name('DistrictWiseThana');
    Route::post('district/wise/thana/by/name', [PosController::class, 'districtWiseThanaByName'])->name('DistrictWiseThanaByName');
    Route::post('save/pos/customer/address', [PosController::class, 'saveCustomerAddress'])->name('SaveCustomerAddress');
    Route::get('get/saved/address/{user_id}', [PosController::class, 'getSavedAddress'])->name('GetSavedAddress');
    Route::post('change/delivery/method', [PosController::class, 'changeDeliveryMethod'])->name('ChangeDeliveryMethod');
    Route::post('place/order', [PosController::class, 'placeOrder'])->name('PlaceOrder');
    // Route::get('/edit/place/order/{slug}', [PosController::class, 'editPlaceOrder'])->name('EditPlaceOrder');
    // Route::post('/update/place/order', [PosController::class, 'updatePlaceOrder'])->name('UpdatePlaceOrder');


    // order routes
    Route::get('/view/orders', [OrderController::class, 'viewAllOrders'])->name('ViewAllOrders');
    Route::get('/view/pending/orders', [OrderController::class, 'viewPendigOrders'])->name('ViewPendigOrders');
    Route::get('/view/approved/orders', [OrderController::class, 'viewApprovedOrders'])->name('ViewApprovedOrders');
    Route::get('/view/delivered/orders', [OrderController::class, 'viewDeliveredOrders'])->name('ViewDeliveredOrders');
    Route::get('/view/cancelled/orders', [OrderController::class, 'viewCancelledOrders'])->name('ViewCancelledOrders');
    Route::get('/view/picked/orders', [OrderController::class, 'viewPickedOrders'])->name('ViewPickedOrders');
    Route::get('/view/intransit/orders', [OrderController::class, 'viewIntransitOrders'])->name('ViewIntransitOrders');
    Route::get('/order/details/{slug}', [OrderController::class, 'orderDetails'])->name('OrderDetails');
    Route::get('/cancel/order/{slug}', [OrderController::class, 'cancelOrder'])->name('CancelOrder');
    Route::get('/approve/order/{slug}', [OrderController::class, 'approveOrder'])->name('ApproveOrder');
    Route::get('/intransit/order/{slug}', [OrderController::class, 'intransitOrder'])->name('IntransitOrder');
    Route::get('/deliver/order/{slug}', [OrderController::class, 'deliverOrder'])->name('DeliverOrder');
    Route::post('/order/info/update', [OrderController::class, 'orderInfoUpdate'])->name('OrderInfoUpdate');
    Route::get('/order/edit/{slug}', [OrderController::class, 'orderEdit'])->name('OrderEdit');
    Route::post('/order/update', [OrderController::class, 'orderUpdate'])->name('OrderUpdate');
    Route::post('/add/more/product', [OrderController::class, 'addMoreProduct'])->name('AddMoreProduct');
    Route::post('/get/product/variants', [OrderController::class, 'getProductVariants'])->name('GetProductVariants');
    Route::get('delete/order/{slug}', [OrderController::class, 'deleteOrder'])->name('DeleteOrder');


    // promo codes
    Route::get('/add/new/code', [PromoCodeController::class, 'addPromoCode'])->name('AddPromoCode');
    Route::post('/save/promo/code', [PromoCodeController::class, 'savePromoCode'])->name('SavePromoCode');
    Route::get('/view/all/promo/codes', [PromoCodeController::class, 'viewAllPromoCodes'])->name('ViewAllPromoCodes');
    Route::get('/edit/promo/code/{slug}', [PromoCodeController::class, 'editPromoCode'])->name('EditPromoCode');
    Route::post('/update/promo/code', [PromoCodeController::class, 'updatePromoCode'])->name('UpdatePromoCode');
    Route::get('/remove/promo/code/{slug}', [PromoCodeController::class, 'removePromoCode'])->name('RemovePromoCode');

    // wishlist routes
    Route::get('/view/customers/wishlist', [WishListController::class, 'customersWishlist'])->name('CustomersWishlist');


    // support ticket routes
    Route::get('/pending/support/tickets', [SupportTicketController::class, 'pendingSupportTickets'])->name('PendingSupportTickets');
    Route::get('/solved/support/tickets', [SupportTicketController::class, 'solvedSupportTickets'])->name('SolvedSupportTickets');
    Route::get('/on/hold/support/tickets', [SupportTicketController::class, 'onHoldSupportTickets'])->name('OnHoldSupportTickets');
    Route::get('/rejected/support/tickets', [SupportTicketController::class, 'rejectedSupportTickets'])->name('RejectedSupportTickets');
    Route::get('/delete/support/ticket/{slug}', [SupportTicketController::class, 'deleteSupportTicket'])->name('DeleteSupportTicket');
    Route::get('/support/status/change/{slug}', [SupportTicketController::class, 'changeStatusSupport'])->name('ChangeStatusSupport');
    Route::get('/support/status/on/hold/{slug}', [SupportTicketController::class, 'changeStatusSupportOnHold'])->name('ChangeStatusSupportOnHold');
    Route::get('/support/status/in/progress/{slug}', [SupportTicketController::class, 'changeStatusSupportInProgress'])->name('ChangeStatusSupportInProgress');
    Route::get('/support/status/rejected/{slug}', [SupportTicketController::class, 'changeStatusSupportRejected'])->name('ChangeStatusSupportRejected');
    Route::get('/view/support/messages/{slug}', [SupportTicketController::class, 'viewSupportMessage'])->name('ViewSupportMessage');
    Route::post('/send/support/message', [SupportTicketController::class, 'sendSupportMessage'])->name('SendSupportMessage');


    // testimonial routes
    Route::get('/view/testimonials', [TestimonialController::class, 'viewTestimonials'])->name('ViewTestimonials');
    Route::get('/add/testimonial', [TestimonialController::class, 'addTestimonial'])->name('AddTestimonial');
    Route::post('/save/testimonial', [TestimonialController::class, 'saveTestimonial'])->name('SaveTestimonial');
    Route::get('/delete/testimonial/{slug}', [TestimonialController::class, 'deleteTestimonial'])->name('DeleteTestimonial');
    Route::get('/edit/testimonial/{slug}', [TestimonialController::class, 'editTestimonial'])->name('EditTestimonial');
    Route::post('/update/testimonial', [TestimonialController::class, 'updateTestimonial'])->name('UpdateTestimonial');


    // subscribed users routes
    Route::get('/view/all/subscribed/users', [SubscribedUsersController::class, 'viewAllSubscribedUsers'])->name('ViewAllSubscribedUsers');
    Route::get('/delete/subcribed/users/{id}', [SubscribedUsersController::class, 'deleteSubscribedUsers'])->name('DeleteSubscribedUsers');
    Route::get('/download/subscribed/users/excel', [SubscribedUsersController::class, 'downloadSubscribedUsersExcel'])->name('DownloadSubscribedUsersExcel');


    // backup download
    Route::get('/download/database/backup', [BackupController::class, 'downloadDBBackup'])->name('DownloadDBBackup');
    Route::get('/download/product/files/backup', [BackupController::class, 'downloadProductFilesBackup'])->name('DownloadProductFilesBackup');
    Route::get('/download/user/files/backup', [BackupController::class, 'downloadUserFilesBackup'])->name('DownloadUserFilesBackup');
    Route::get('/download/banner/files/backup', [BackupController::class, 'downloadBannerFilesBackup'])->name('DownloadBannerFilesBackup');
    Route::get('/download/category/files/backup', [BackupController::class, 'downloadCategoryFilesBackup'])->name('DownloadCategoryFilesBackup');
    Route::get('/download/subcategory/files/backup', [BackupController::class, 'downloadSubcategoryFilesBackup'])->name('DownloadSubcategoryFilesBackup');
    Route::get('/download/flag/files/backup', [BackupController::class, 'downloadFlagFilesBackup'])->name('DownloadFlagFilesBackup');
    Route::get('/download/ticket/files/backup', [BackupController::class, 'downloadTicketFilesBackup'])->name('DownloadTicketFilesBackup');
    Route::get('/download/blog/files/backup', [BackupController::class, 'downloadBlogFilesBackup'])->name('DownloadBlogFilesBackup');
    Route::get('/download/other/files/backup', [BackupController::class, 'downloadOtherFilesBackup'])->name('DownloadOtherFilesBackup');


    // push notification
    Route::get('/send/notification/page', [NotificationController::class, 'sendNotificationPage'])->name('SendNotificationPage');
    Route::get('/view/all/notifications', [NotificationController::class, 'viewAllNotifications'])->name('ViewAllNotifications');
    Route::get('/delete/notification/{id}', [NotificationController::class, 'deleteNotification'])->name('DeleteNotification');
    Route::get('/delete/notification/with/range', [NotificationController::class, 'deleteNotificationRangeWise'])->name('DeleteNotificationRangeWise');
    Route::post('/send/push/notification', [NotificationController::class, 'sendPushNotification'])->name('SendPushNotification');


    // sms service
    Route::get('/view/sms/templates', [SmsServiceController::class, 'viewSmsTemplates'])->name('ViewSmsTemplates');
    Route::get('/create/sms/template', [SmsServiceController::class, 'createSmsTemplate'])->name('CreateSmsTemplate');
    Route::post('/save/sms/template', [SmsServiceController::class, 'saveSmsTemplate'])->name('SaveSmsTemplate');
    Route::get('get/sms/template/info/{id}', [SmsServiceController::class, 'getSmsTemplateInfo'])->name('GetSmsTemplateInfo');
    Route::get('delete/sms/template/{id}', [SmsServiceController::class, 'deleteSmsTemplate'])->name('DeleteSmsTemplate');
    Route::get('/send/sms/page', [SmsServiceController::class, 'sendSmsPage'])->name('SendSmsPage');
    Route::post('/get/template/description', [SmsServiceController::class, 'getTemplateDescription'])->name('GetTemplateDescription');
    Route::post('/update/sms/template', [SmsServiceController::class, 'updateSmsTemplate'])->name('UpdateSmsTemplate');
    Route::post('/send/sms', [SmsServiceController::class, 'sendSms'])->name('SendSms');
    Route::get('/view/sms/history', [SmsServiceController::class, 'viewSmsHistory'])->name('ViewSmsHistory');
    Route::get('/delete/sms/with/range', [SmsServiceController::class, 'deleteSmsHistoryRange'])->name('DeleteSmsHistoryRange');
    Route::get('/delete/sms/{id}', [SmsServiceController::class, 'deleteSmsHistory'])->name('DeleteSmsHistory');


    // blog routes
    Route::get('/blog/categories', [BlogController::class, 'blogCategories'])->name('BlogCategories');
    Route::post('/save/blog/category', [BlogController::class, 'saveBlogCategory'])->name('SaveBlogCategory');
    Route::get('/delete/blog/category/{slug}', [BlogController::class, 'deleteBlogCategory'])->name('DeleteBlogCategory');
    Route::get('/feature/blog/category/{slug}', [BlogController::class, 'featureBlogCategory'])->name('FeatureBlogCategory');
    Route::get('/get/blog/category/info/{slug}', [BlogController::class, 'getBlogCategoryInfo'])->name('GetBlogCategoryInfo');
    Route::post('/update/blog/category', [BlogController::class, 'updateBlogCategoryInfo'])->name('UpdateBlogCategoryInfo');
    Route::get('/rearrange/blog/category', [BlogController::class, 'rearrangeBlogCategory'])->name('RearrangeBlogCategory');
    Route::post('/save/rearranged/blog/categories', [BlogController::class, 'saveRearrangeCategory'])->name('SaveRearrangeCategory');
    Route::get('/add/new/blog', [BlogController::class, 'addNewBlog'])->name('AddNewBlog');
    Route::post('/save/new/blog', [BlogController::class, 'saveNewBlog'])->name('SaveNewBlog');
    Route::get('/view/all/blogs', [BlogController::class, 'viewAllBlogs'])->name('ViewAllBlogs');
    Route::get('/delete/blog/{slug}', [BlogController::class, 'deleteBlog'])->name('DeleteBlog');
    Route::get('/edit/blog/{slug}', [BlogController::class, 'editBlog'])->name('EditBlog');
    Route::post('/update/blog', [BlogController::class, 'updateBlog'])->name('UpdateBlog');


    // delivery charges
    Route::get('/view/delivery/charges', [DeliveryChargeController::class, 'viewAllDeliveryCharges'])->name('ViewAllDeliveryCharges');
    Route::get('/get/delivery/charge/{id}', [DeliveryChargeController::class, 'getDeliveryCharge'])->name('GetDeliveryCharge');
    Route::post('/update/delivery/charge', [DeliveryChargeController::class, 'updateDeliveryCharge'])->name('UpdateDeliveryCharge');

    // upazaila thana
    Route::get('view/upazila/thana', [DeliveryChargeController::class, 'viewUpazilaThana'])->name('ViewUpazilaThana');
    Route::get('get/upazila/info/{id}', [DeliveryChargeController::class, 'getUpazilaInfo'])->name('getUpazilaInfo');
    Route::post('update/upazila/info', [DeliveryChargeController::class, 'updateUpazilaInfo'])->name('UpdateUpazilaInfo');
    Route::post('save/new/upazila', [DeliveryChargeController::class, 'saveNewUpazila'])->name('SaveNewUpazila');
    Route::get('delete/upazila/{id}', [DeliveryChargeController::class, 'deleteUpazila'])->name('DeleteUpazila');


    // custom page
    Route::get('create/new/page', [CustomPageController::class, 'createNewPage'])->name('CreateNewPage');
    Route::post('save/custom/page', [CustomPageController::class, 'saveCustomPage'])->name('SaveCustomPage');
    Route::get('view/all/pages', [CustomPageController::class, 'viewCustomPages'])->name('ViewCustomPages');
    Route::get('delete/custom/page/{slug}', [CustomPageController::class, 'deleteCustomPage'])->name('DeleteCustomPage');
    Route::get('edit/custom/page/{slug}', [CustomPageController::class, 'editCustomPage'])->name('EditCustomPage');
    Route::post('update/custom/page', [CustomPageController::class, 'updateCustomPage'])->name('UpdateCustomPage');


    // generate report
    Route::get('sales/report', [ReportController::class, 'salesReport'])->name('SalesReport');
    Route::post('generate/sales/report', [ReportController::class, 'generateSalesReport'])->name('GenerateSalesReport');


    // user role permission routes
    Route::get('/view/permission/routes', [PermissionRoutesController::class, 'viewAllPermissionRoutes'])->name('ViewAllPermissionRoutes');
    Route::get('/regenerate/permission/routes', [PermissionRoutesController::class, 'regeneratePermissionRoutes'])->name('RegeneratePermissionRoutes');
    Route::get('/view/user/roles', [UserRoleController::class, 'viewAllUserRoles'])->name('ViewAllUserRoles');
    Route::get('/new/user/role', [UserRoleController::class, 'newUserRole'])->name('NewUserRole');
    Route::post('save/user/role', [UserRoleController::class, 'saveUserRole'])->name('SaveUserRole');
    Route::get('/delete/user/role/{id}', [UserRoleController::class, 'deleteUserRole'])->name('DeleteUserRole');
    Route::get('/edit/user/role/{id}', [UserRoleController::class, 'editUserRole'])->name('EditUserRole');
    Route::post('update/user/role', [UserRoleController::class, 'updateUserRole'])->name('UpdateUserRole');
    Route::get('/view/user/role/permission', [UserRoleController::class, 'viewUserRolePermission'])->name('ViewUserRolePermission');
    Route::get('/assign/role/permission/{id}', [UserRoleController::class, 'assignRolePermission'])->name('AssignRolePermission');
    Route::post('/save/assigned/role/permission', [UserRoleController::class, 'saveAssignedRolePermission'])->name('SaveAssignedRolePermission');


    // Product Color Management
    Route::get('/add/new/product-color', [ProductColorController::class, 'addNewProductColor'])->name('AddNewProductColor');    
    Route::post('/save/new/product-color', [ProductColorController::class, 'saveNewProductColor'])->name('SaveNewProductColor');
    Route::get('/view/all/product-color', [ProductColorController::class, 'viewAllProductColor'])->name('ViewAllProductColor');
    Route::get('/delete/product-color/{slug}', [ProductColorController::class, 'deleteProductColor'])->name('DeleteProductColor');
    Route::get('/edit/product-color/{slug}', [ProductColorController::class, 'editProductColor'])->name('EditProductColor');      
    Route::post('/update/product-color', [ProductColorController::class, 'updateProductColor'])->name('UpdateProductColor');
  
    // Product Size Management
    Route::get('/add/new/product-size', [ProductSizeController::class, 'addNewProductSize'])->name('AddNewProductSize');    
    Route::post('/save/new/product-size', [ProductSizeController::class, 'saveNewProductSize'])->name('SaveNewProductSize');
    Route::get('/view/all/product-size', [ProductSizeController::class, 'viewAllProductSize'])->name('ViewAllProductSize');
    Route::get('/delete/product-size/{slug}', [ProductSizeController::class, 'deleteProductSize'])->name('DeleteProductSize');
    Route::get('/edit/product-size/{slug}', [ProductSizeController::class, 'editProductSize'])->name('EditProductSize');      
    Route::post('/update/product-size', [ProductSizeController::class, 'updateProductSize'])->name('UpdateProductSize');

    // Product Size Value Management
    Route::get('/add/new/product-size-value', [ProductSizeValueController::class, 'addNewProductSizeValue'])->name('AddNewProductSizeValue');    
    Route::post('/save/new/product-size-value', [ProductSizeValueController::class, 'saveNewProductSizeValue'])->name('SaveNewProductSizeValue');
    Route::get('/view/all/product-size-value', [ProductSizeValueController::class, 'viewAllProductSizeValue'])->name('ViewAllProductSizeValue');
    Route::get('/delete/product-size-value/{slug}', [ProductSizeValueController::class, 'deleteProductSizeValue'])->name('DeleteProductSizeValue');
    Route::get('/edit/product-size-value/{slug}', [ProductSizeValueController::class, 'editProductSizeValue'])->name('EditProductSizeValue');      
    Route::post('/update/product-size-value', [ProductSizeValueController::class, 'updateProductSizeValue'])->name('UpdateProductSizeValue');
  



    // // vendor routes
    // Route::get('/create/new/vendor', [VendorController::class, 'createNewVendor'])->name('CreateNewVendor');
    // Route::post('/save/vendor', [VendorController::class, 'saveVendor'])->name('SaveVendor');
    // Route::get('/view/all/vendors', [VendorController::class, 'viewAllVendors'])->name('ViewAllVendors');
    // Route::get('/view/vendor/requests', [VendorController::class, 'viewVendorRequests'])->name('ViewVendorRequests');
    // Route::get('/view/inactive/vendors', [VendorController::class, 'viewInactiveVendors'])->name('ViewInactiveVendors');
    // Route::get('/edit/vendor/{vendor_no}', [VendorController::class, 'editVendor'])->name('EditVendor');
    // Route::post('/update/vendor', [VendorController::class, 'updateVendor'])->name('UpdateVendor');
    // Route::get('/approve/vendor/{vendor_no}', [VendorController::class, 'approveVendor'])->name('ApproveVendor');
    // Route::get('/delete/vendor/{vendor_no}', [VendorController::class, 'deleteVendor'])->name('DeleteVendor');
    // Route::get('/download/approved/vendors/excel', [VendorController::class, 'downloadApprovedVendorsExcel'])->name('DownloadApprovedVendorsExcel');

    // // store routes
    // Route::get('/create/new/store', [StoreController::class, 'createNewStore'])->name('CreateNewStore');
    // Route::post('/save/store', [StoreController::class, 'saveStore'])->name('SaveStore');
    // Route::get('/view/all/stores', [StoreController::class, 'viewAllStores'])->name('ViewAllStores');
    // Route::get('/inactive/store/{id}', [StoreController::class, 'inactiveStore'])->name('InactiveStore');
    // Route::get('/activate/store/{id}', [StoreController::class, 'activateStore'])->name('ActivateStore');
    // Route::get('/edit/store/{slug}', [StoreController::class, 'editStore'])->name('EditStore');
    // Route::post('/update/store', [StoreController::class, 'updateStore'])->name('UpdateStore');

    // // withdraw routes
    // Route::get('create/new/withdraw', [WithdrawController::class, 'createWithdrawRequest'])->name('CreateWithdrawRequest');
    // Route::post('vendor/balance', [WithdrawController::class, 'getVendorBalance'])->name('CreateWithdrawRequest');
    // Route::post('save/withdraw/request', [WithdrawController::class, 'saveWithdrawRequest'])->name('SaveWithdrawRequest');
    // Route::get('view/all/withdraws', [WithdrawController::class, 'viewAllWithdraws'])->name('ViewAllWithdraws');
    // Route::get('view/withdraw/requests', [WithdrawController::class, 'viewWithdrawRequests'])->name('ViewWithdrawRequests');
    // Route::get('view/completed/withdraws', [WithdrawController::class, 'viewCompletedWithdraws'])->name('ViewCompletedWithdraws');
    // Route::get('view/cancelled/withdraws', [WithdrawController::class, 'viewCancelledWithdraws'])->name('ViewCancelledWithdraws');
    // Route::get('delete/withdraw/{id}', [WithdrawController::class, 'deleteWithdraw'])->name('DeleteWithdraw');
    // Route::get('get/withdraw/info/{id}', [WithdrawController::class, 'getWithdrawInfo'])->name('getWithdrawInfo');
    // Route::get('deny/withdraw/{id}', [WithdrawController::class, 'denyWithdraw'])->name('DenyWithdraw');
    // Route::post('approve/withdraw', [WithdrawController::class, 'approveWithdraw'])->name('ApproveWithdraw');

});