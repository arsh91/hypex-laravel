<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('website.home');
});
*/

Auth::routes(['verify' => true]);
Route::get('/signup', function () {
	
	if(Auth::user() != NULL){
		return redirect('/');
	}else{
		return view('register');
	}
});

Route::get('/clear-cache', function() {
   	Artisan::call('cache:clear');
   	Artisan::call('config:clear');
   	Artisan::call('view:clear');
   	Artisan::call('route:clear');
    return "Cache is cleared";
});


Route::get('/vip-signup', function () {
	
	if(Auth::user() != NULL){
		return redirect('/');
	}else{
		return view('vip-register');
	}
});


Route::get('/signin', function () {
	
	if(Auth::user() != NULL){
		return redirect('/');
	}else{
		return view('login');
	}
});

Route::get('/vip-signin', function () {
	
	if(Auth::user() != NULL){
		return redirect('/');
	}else{
		return view('vip-login');
	}
});

Route::get('/forgot-password', function () {
	
	return view('forgot-password');
});



Route::namespace('v1\website')->group(function () {
	
	Route::get('/', 'HomeController@index')->name('home');
	
	Route::get('/product-detail/{id}', 'ProductsController@getProductDetail');
	
	 Route::get('/saveCurrencyToSession', 'UsersController@saveCurrencyToSession');

	Route::get('/vip-product-detail/{id}', 'ProductsController@getVipProductDetail'); // Vip Product details
	
	//Route::get('/products/shoes', 'ProductsController@getAllShoes');

	/*##################   CATEGORY ROUTES  #####################*/

	Route::get('/category/{id}', 'ProductsController@getCategoryListing');

	/*##################   CATEGORY ROUTES  #####################*/

	//Route::get('/products/tshirts', 'ProductsController@getAllTshirts');

	Route::any('/contact-us', 'HomeController@contact')->name('contact-us');

	Route::match(['get','post'],'/products/products-filter', 'ProductsController@filterProducts');

	Route::get('/logout', 'HomeController@logout');
	
	Route::post('register','HomeController@register')->name('register');
	
	Route::post('login','HomeController@login')->name('login');
	
	Route::post('forgot-password','HomeController@sendForgotPasswordEmail')->name('forgot-password');
	
	Route::get('category','BrandsController@category');
	
	Route::get('reset-password/{token}','HomeController@checkResetLinkValidity');
	
	Route::post('reset-password','HomeController@resetPassword')->name('reset-password');
	
    Route::get('brand-data','BrandsController@brandData');

	/*##################   VIP ROUTES  #####################*/
	Route::get('vip-home','VipController@vipnewHome');
	Route::get('/vip-products', 'VipController@getVipProductListing');
	Route::match(['get','post'],'/vip/products-filter', 'VipController@vipFilterProducts');

	/*##################  VIP ROUTES  ####################*/

	/*###Route to get the discounted price after promo code application###*/
	Route::post('/applyProductPromoCode', 'ProductsController@applyProductPromoCode');
    
	Route::get('/shipstationOrders123.php', 'ProductsController@shipstationOrders')->name('shipstation-orders');
	
	Route::get('/search-result', 'ProductsController@searchresult')->name('search-result');
    
    Route::get('/scrap', 'ScrapController@stockxScrap')->name('scrap');
    
    Route::get('/locale/{locale}', function ($locale) {
	
        Session::put('locale',$locale);
        return redirect()->back();
        
    });
});



Route::namespace('v1\website')->middleware(['auth:web'])->group(function () {
	
	Route::get('/my-profile', 'UsersController@myProfile')->name('my-profile');
	Route::get('/user-account', 'UsersController@userAcount')->name('user-account');

	/*##################   VIP ROUTES  #####################*/
	Route::get('/vip/vip-store','VipController@vipStore')->name('vip-store');
	Route::get('/vip/vip-sale','VipController@vipSale')->name('vip-sale');
	Route::get('/vip/load-product', 'VipController@laodproduct');
	/*##################   VIP ROUTES  #####################*/

	Route::any('/changeold-password', 'UsersController@changeoldPassword')->name('changeold-password');

	//shipping address routes STARTS
	Route::match(['get', 'post'],'update-shipping-address/{id}', 'UsersController@updateShippingAddress')->name('update-shipping-address');
	Route::match(['get', 'post'],'/add-shipping-address', 'UsersController@addShippingAddress')->name('add-shipping-address');
	Route::get('/view-shipping-address', 'UsersController@viewShippingAddress')->name('view-shipping-address');
	Route::get('/delete-shipping-address/{id}','UsersController@deleteShippingAddress');
	//##Shipping address routes ENDS##

	//Billing Address Routes STARTS
	//Route::match(['get', 'post'],'/add-return-address', 'UsersController@addReturnAddress')->name('add-return-address');
	Route::get('/add-return-address', 'UsersController@addReturnAddress')->name('add-return-address'); //To show the form
	Route::post('/save-return-address', 'UsersController@saveReturnAddress')->name('save-return-address'); //To show the form

	Route::get('/view-return-address', 'UsersController@viewReturnAddress')->name('view-return-address');

	Route::get('/update-return-address/{id}', 'UsersController@updateReturnAddress')->name('update-return-address'); //To show the update form
	Route::post('/edit-return-address/{id}', 'UsersController@editReturnAddress')->name('edit-return-address'); //To save the editted form


	Route::match(['get','post'],'/delete-return-address/{id}','UsersController@deleteReturnAddress');
	//##Billing Address Routes ENDS##


	//test wepay//
	Route::match(['get','post'],'/wepaytest','ProductsController@wepaytest');


	Route::post('/update-profile', 'UsersController@updateProfile')->name('update-profile');
	Route::post('/savebid', 'ProductsController@saveBid')->name('saveBid');
	Route::post('/savesell', 'ProductsController@saveSell')->name('saveSell');
	Route::get('/product-bid/{id}/{size}/{type}', 'ProductsController@bidProduct')->name('bid-product')->middleware('AuthCheckRedirectMiddleware');
	
	Route::get('/direct-purchase/{id}/{size}/{type}', 'ProductsController@buyProduct')->name('direct-purchase')->middleware('AuthCheckRedirectMiddleware');

	Route::get('/promo-purchase/{id}/{size}/{type}', 'ProductsController@buyPromoProduct')->name('promo-purchase')->middleware('AuthCheckRedirectMiddleware');

	Route::get('/vip-purchase/{id}/{size}/{type}', 'ProductsController@buyVipProduct')->name('vip-purchase')->middleware('AuthCheckRedirectMiddleware');
	
	Route::post('/purchase-bid', 'ProductsController@purchaseBid')->name('purchaseBid');

	Route::post('/purchase-promo', 'ProductsController@purchasePromo')->name('purchasePromo');

	Route::post('/purchase-vip', 'ProductsController@purchaseVip')->name('purchaseVip');
	
	Route::post('/sell-bid', 'ProductsController@sellBid')->name('sellBid');
	
	Route::get('/sell-now/{id}/{size}/{type}', 'ProductsController@sellBidProduct')->name('direct-sell')->middleware('AuthCheckRedirectMiddleware');
	
	Route::get('/product-sell/{id}/{size}/{type}', 'ProductsController@sellProduct')->name('sell-product')->middleware('AuthCheckRedirectMiddleware');
	
	Route::post('/create-stripe-customer', 'PaymentController@createStripeCustomer')->name('create-customer')->middleware('AuthCheckRedirectMiddleware');
    
    Route::post('/save-user-cards', 'PaymentController@saveCards')->name('save-cards')->middleware('AuthCheckRedirectMiddleware');
	
	Route::post('/payment', 'PaymentController@chargePayment')->name('charge-payment')->middleware('AuthCheckRedirectMiddleware');

	Route::post('/promo-payment', 'PaymentController@promoChargePayment')->name('promo-payment')->middleware('AuthCheckRedirectMiddleware');

	Route::post('/vip-payment', 'PaymentController@vipChargePayment')->name('vip-payment')->middleware('AuthCheckRedirectMiddleware');

	Route::post('/charge-subcription', 'PaymentController@chargeSubcription')->name('charge-subcription')->middleware('AuthCheckRedirectMiddleware');
	
	Route::post('/deduct-payment', 'PaymentController@deductPayment')->name('deduct-payment')->middleware('AuthCheckRedirectMiddleware');
    Route::get('/savePriceAjax', 'ProductsController@savePriceAjax');
    Route::get('/savePriceToSession','ProductsController@savePriceToSession');
	Route::get('/save-payment','UsersController@savePayment');

	Route::get('/subscription-payment/{id}','UsersController@subscriptionPayment')->name('buy_plan'); // buy subscription

	Route::get('/subscriptioncheck','UsersController@subscriptioncheck'); // check subscription

    Route::get('/remove-card/{prefix}','UsersController@removeCard');
    
    Route::get('/payout-info','UsersController@getPayoutInfo');
    Route::post('/save-payouts','UsersController@savePayoutInfo')->name('save-payouts');
    
   

	Route::get('/convert/single', 'UsersController@single');

	/*###Route to get dynamic province list on changing the country###*/
	Route::post('/getProvince', 'ProductsController@getProvince');
    
    Route::get('/notify', 'testController@iosPushFunction')->name('notify');


    

});



/* ------------------ Admin Routes Start -------------*/

Route::namespace('v1\admin')->group(function () {
	Route::match(['post','get'],'admin/login','DashboardController@login');
	Route::match(['post','get'],'admin','DashboardController@login');
	Route::match(['get'],'admin/logout','DashboardController@logout');
    Route::match(['get'],'admin/auto-scrap','ProductManagementController@automatedScrapping');


});

// ->middleware(['checkIsAdminLogin']);

Route::namespace('v1\admin')->middleware(['auth:web'])->group(function () {
		// Dashboard Controller
	Route::get('admin/dashboard','DashboardController@dashboard');
});

Route::namespace('v1\admin')->middleware(['auth:web','checkIsAdminLogin'])->group(function () {

	// Product Management Controller
	Route::match(['get','post'],'admin/new-product-import','ProductManagementController@newProductImport');
	Route::match(['get'],'admin/product-list','ProductManagementController@allproduct');
    Route::match(['get'],'admin/vip-product-list','ProductManagementController@vipProducts');
	Route::match(['get'],'admin/product-list-paginate','ProductManagementController@productListPaginate');
	Route::match(['get'],'admin/each-product-detail/{id}','ProductManagementController@eachProductDetail');
	Route::match(['get','post'],'admin/add-new-product','ProductManagementController@addNewProduct');
	Route::match(['get','post'],'admin/edit-product/{id}','ProductManagementController@editProduct');
	Route::match(['post'],'admin/get_brand_type_list','ProductManagementController@getBrandTypeList');
	Route::match(['get'],'admin/action-on-product/{id}','ProductManagementController@actionOnProduct');

	// User Management Controller
	// Route::match(['get'],'admin/user-list','UserManagementController@userList');
	Route::match(['get'],'admin/user-list','UserManagementController@index'); // List of users
	Route::match(['get'],'admin/user-list-paginate','UserManagementController@userListPaginate');
	Route::match(['get'],'admin/each-user-detail/{id}','UserManagementController@eachUserDetail');
	Route::match(['get','post'],'admin/edit-user-detail/{id}','UserManagementController@eachUserEdit');
	Route::match(['get','post'],'admin/user/{id}/{status}', 'UserManagementController@changeStatus');  //  delete User
	Route::match(['get','post'],'admin/bids-products/{id}','UserManagementController@bidsProductList'); // Bids product listing
	Route::match(['get','post'],'admin/buy-history/{id}','UserManagementController@buyinghistory'); // Buy History
	Route::match(['get','post'],'admin/sell-products/{id}','UserManagementController@sellProductLists'); // Sell product listing
	Route::match(['get','post'],'admin/sell-history/{id}','UserManagementController@sellinghistory'); // Sell History

	// Stats Management Controller
	Route::match(['get'],'admin/highestbid', 'StatsManagementController@highestbid');
	Route::match(['get'],'admin/highestorder', 'StatsManagementController@highestorder');

	// Category Management Controller
	Route::match(['get'],'admin/category-list', 'CategoryManagementController@categoryList');
	Route::match(['get'],'admin/category-list-paginate','CategoryManagementController@categoryListPaginate');
	Route::match(['get','post'],'admin/each-category-detail/{id}','CategoryManagementController@eachCategoryDetail');
	Route::match(['get','post'],'admin/add-category','CategoryManagementController@addCategory');
	Route::match(['get','post'],'admin/edit-category/{id}','CategoryManagementController@editCategory');
	Route::match(['get'],'admin/delete-category/{id}','CategoryManagementController@deleteCategory');

	// Brand Management Controller
	Route::match(['get'],'admin/brand-list', 'BrandManagementController@brandList');
	Route::match(['get'],'admin/brand-list-paginate','BrandManagementController@brandListPaginate');
	Route::match(['get','post'],'admin/each-brand-detail/{id}','BrandManagementController@eachBrandDetail');
	Route::match(['get','post'],'admin/add-brand','BrandManagementController@addBrand');
	Route::match(['get','post'],'admin/edit-brand/{id}','BrandManagementController@editBrand');
	Route::match(['get'],'admin/delete-brand/{id}','BrandManagementController@deleteBrand');
	Route::match(['post'],'admin/delete_brand_type','BrandManagementController@deleteBrandType');

	// Order Management Controller
	Route::match(['get'],'admin/order-list','OrderManagementController@list');
    Route::match(['get'],'admin/vip-order-list','OrderManagementController@vipOrderList');
	Route::match(['get'],'admin/order-rejected','OrderManagementController@rejectedlist');
	Route::match(['get'],'admin/order-history','OrderManagementController@historylist');
	Route::match(['get'],'admin/order/{id}','OrderManagementController@labeldata');
	Route::match(['get'],'admin/order/each-order-detail/{id}','OrderManagementController@view');
	Route::match(['get','post'],'admin/order/reject-order/{id}/{status}', 'OrderManagementController@rejectOrder');  //  delete order
	Route::match(['get','post'],'admin/order-data/{order_refrencenumber}/{id}','OrderManagementController@getoderdata');


	// Shipping Management Controller
	Route::match(['get'],'admin/shipping-list','ShippingManagementController@list');
	Route::match(['get'],'admin/shipping/each-shipping-detail/{id}','ShippingManagementController@view');
	Route::match(['get','post'],'admin/shipping/deleteshipping/{id}/{status}', 'ShippingManagementController@deleteshipping');  //  delete shipping
	Route::match(['get','post'],'admin/shipping/add-shipping', 'ShippingManagementController@addshipping');  //  add shipping
	Route::match(['get','post'],'admin/shipping/edit-shipping/{id}', 'ShippingManagementController@editshipping');  //  edit shipping
	
	// Subscriptions Management Controller

	Route::get('admin/plans-list', 'SubscriptionPlansController@list');
    Route::any('admin/plans/add-plan', 'SubscriptionPlansController@add')->name('add_plan');
	Route::any('admin/plans/edit-plans-detail/{id}', 'SubscriptionPlansController@edit');
	Route::any('admin/plans/each-plans-detail/{id}', 'SubscriptionPlansController@eachPlanDetail');

	// Subscribed Users List

	Route::get('admin/subscribed/users-list', 'SubscriptionPlansController@subscribedUserlist');
	Route::any('admin/subscribed/each-sub-users-detail/{id}', 'SubscriptionPlansController@eachUserSubscribedDetail');

	//scrap route for ajax
	Route::post('/admin/scrap-product/{id}', 'ProductManagementController@scrap');
	Route::post('/admin/scrap-product-sell/{id}', 'ProductManagementController@scrapSell');
	
	//search product
	Route::get('/admin/search-product-list/', 'ProductManagementController@productSearch');
	Route::get('/admin/all-product-list/', 'ProductManagementController@ajaxallproduct');
    
    Route::get('/admin/vip-search-product-list/', 'ProductManagementController@vipProductSearch');
	Route::get('/admin/vip-all-product-list/', 'ProductManagementController@ajaxVipAllproduct');
    
    Route::get('/admin/vip-sale-list/', 'VipSaleCounterController@showSale');
	Route::any('/admin/edit-sale/{id}', 'VipSaleCounterController@editSale');
	
	// currency home 
	Route::get('/admin/currency-list/', 'VipSaleCounterController@showCurrency');
	Route::any('/admin/edit-currency/{id}', 'VipSaleCounterController@editCurrency');

    
	 
});

/* ------------------ Admin Routes End - ----*/


Route::get('/privacy-policy', function () {
    return view('static/privacy-policy');
});

Route::get('/term-condition', function () {
    return view('static/term-condition');
});

Route::get('/faq', function () {
    return view('static/faq');
});

// Route::get('/contact-us', function () {
//     return view('static/contact-us');
// });
