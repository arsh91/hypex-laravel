<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
 
Route::post('login', 'API\UserController@login');
Route::post('register', 'API\UserController@register');
Route::post('social_register', 'API\UserController@social_register');
Route::post('social_login', 'API\UserController@social_login');
Route::post('sendForgotPasswordEmail', 'API\UserController@sendForgotPasswordEmail');
Route::get('getHomeDetails', 'API\ProductsController@getHomeDetails');
Route::post('getFeaturedProductsList', 'API\ProductsController@getFeaturedProductsList');
Route::post('getProductDetail', 'API\ProductsController@getProductDetail');
Route::post('bargainProductDetail', 'API\ProductsController@bargainProductDetail');
Route::post('search', 'API\ProductsController@search');
Route::post('getSneakers', 'API\ProductsController@getSneakers');
Route::post('getStreetwears', 'API\ProductsController@getStreetwears');
Route::post('getFilters', 'API\ProductsController@getFilters');
Route::get('GetTrendingProducts', 'API\ProductsController@GetTrendingProducts');
Route::post('getBrandwiseProducts', 'API\ProductsController@getBrandwiseProducts');
Route::post('getCategorywiseProducts', 'API\ProductsController@getCategorywiseProducts');
Route::post('getRecommendedProducts', 'API\ProductsController@getRecommendedProducts');
Route::post('getVIPProducts', 'API\VipProductsController@getVIPProducts');
Route::post('getBlogDetail', 'API\ProductsController@getBlogDetail');
Route::post('getBlogList', 'API\ProductsController@getBlogList');
Route::post('getBargainProductsList', 'API\ProductsController@getBargainProductsList');
Route::post('getBackdoorSaleProducts', 'API\ProductsController@getBackdoorSaleProducts');
Route::post('getProvinces', 'API\AddressesController@getProvinces');
Route::get('getCountries', 'API\AddressesController@getCountries');

Route::get('test', 'API\AddressesController@getCountries');

Route::group(['middleware' => 'auth:api'], function(){
    // Route::post('details', 'API\UserController@details');
	Route::post('register_size', 'API\UserController@register_size');
	Route::post('logout', 'API\UserController@logout');
	Route::post('changePassword', 'API\UserController@changePassword');
	 Route::get('myProfile', 'API\UserController@myProfile');
	Route::post('updateProfile', 'API\UserController@updateProfile');
	Route::post('getMyBargainProducts', 'API\ProductsController@getMyBargainProducts');
	Route::post('addShippingReturnAddress', 'API\AddressesController@addShippingReturnAddress');
	Route::post('addShippingAddress', 'API\AddressesController@addShippingAddress');
	Route::post('addReturnAddress', 'API\AddressesController@addReturnAddress');
	Route::get('getShippingAddress', 'API\AddressesController@getShippingAddress');
	Route::get('getReturnAddress', 'API\AddressesController@getReturnAddress');
	Route::post('setDefaultShippingAddress', 'API\AddressesController@setDefaultShippingAddress');
	Route::post('setDefaultReturnAddress', 'API\AddressesController@setDefaultReturnAddress');
	Route::post('editShippingAddress', 'API\AddressesController@editShippingAddress');
	Route::post('editReturnAddress', 'API\AddressesController@editReturnAddress');
	Route::post('deleteShippingAddress', 'API\AddressesController@deleteShippingAddress');
	Route::post('deleteReturnAddress', 'API\AddressesController@deleteReturnAddress');
	Route::post('addCard', 'API\PaymentController@addCard');
	Route::get('getCards', 'API\AddressesController@getCards');
	Route::post('setDefaultCard', 'API\AddressesController@setDefaultCard');
	Route::post('deleteCard', 'API\AddressesController@deleteCard');
	Route::post('addPayoutEmailAddress', 'API\UserController@addPayoutEmailAddress');
	Route::get('getPayoutEmailAddress', 'API\UserController@getPayoutEmailAddress');
	Route::get('getSettingsInfo', 'API\UserController@getSettingsInfo');
	Route::post('buyOffer', 'API\SellingBiddingController@buyOffer');
	Route::post('sellOffer', 'API\SellingBiddingController@sellOffer');
	Route::post('sellNow', 'API\PaymentController@sellNow');
	Route::post('buyNow', 'API\PaymentController@buyNow');
	Route::post('payOfferAmount', 'API\PaymentController@payOfferAmount');
	Route::post('getOfferDetails', 'API\PaymentController@getOfferDetails');
	Route::get('getBuyingProducts', 'API\UserController@getBuyingProducts');
	Route::get('getSellingProducts', 'API\UserController@getSellingProducts');
	Route::post('SetNotificationSettings', 'API\UserNotificationsController@SetNotificationSettings');
	Route::get('getNotificationSettings', 'API\UserNotificationsController@getNotificationSettings');
	Route::post('setDeviceToken', 'API\UserNotificationsController@setDeviceToken');
	Route::post('getNotifications', 'API\UserNotificationsController@getNotifications');
	Route::post('markReadNoitifications', 'API\UserNotificationsController@markReadNoitifications');
	Route::get('getunreadNotificationCount', 'API\UserNotificationsController@getunreadNotificationCount');
	Route::post('deleteNotifications', 'API\UserNotificationsController@deleteNotifications');
	Route::post('deleteSingleNotifications', 'API\UserNotificationsController@deleteSingleNotifications');
	Route::post('getProductOfferDetails', 'API\PaymentController@getProductOfferDetails');
	Route::post('editOffer', 'API\SellingBiddingController@editOffer');
	
});
 