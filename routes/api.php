<?php

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api',
    'middleware'=>'bindings'
], function ($api) {

	$api->get('index','HomeController@index');
	$api->post('user/login','HomeController@login');
	$api->post('user/register','HomeController@register');

	// passport 
	$api->group(['middleware' => 'auth:api'],function($api){
		
		$api->get('user/me','HomeController@me');
		$api->get('user/addresses','AddressController@index');
		$api->post('user/addresses','AddressController@create');
		$api->get('user/addresses/{id}/edit','AddressController@edit');
		$api->put('user/addresses/{id}','AddressController@update');
		$api->delete('user/addresses/{id}','AddressController@destroy');

		$api->get('order/index','OrderController@index')->name('order.index');
		$api->get('order/create','OrderController@create')->name('order.create');
		$api->post('order','OrderController@store')->name('order.store');
		$api->post('order/{order}/receipt','OrderController@receipt')->name('order.receipt');

		$api->post('order/{order}/alipay','PaymentController@alipayPayment')->name('alipay');

		$api->post('order/{order}/refund','OrderController@orderRefund')->name('order_refund');

		$api->get('coupon','CouponController@index');
		$api->get('user/coupon/{coupon}','CouponController@receiveCoupon');

		$api->post('order_item/{order_item}/comment','CommentController@addOrderItemComment');
	});

	$api->get('goods','GoodsController@index');

	
	
});

Route::post('payment/alipay/notify', 'PaymentController@alipayNotify')->name('payment.alipay.notify');
Route::post('payment/wechat/notify', 'PaymentController@wechatNotify')->name('payment.wechat.notify');
Route::post('payment/wechat/refund_notify', 'PaymentController@wechatRefundNotify')->name('payment.wechat.refund_notify');