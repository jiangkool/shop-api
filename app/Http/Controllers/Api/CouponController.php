<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Dingo\Api\Routing\Helpers;
use App\Models\Coupon;
use App\Exceptions\InternalException;
use Carbon\Carbon;

class CouponController extends Controller
{
	use Helpers;

    public function index()
    {
    	$coupons=Coupon::where('status',1)->get();

    	return $this->response->array($coupons);
    }

    public function receiveCoupon(Request $request, Coupon $coupon)
    {
    	if (!$coupon->checkCouponAvilable()
    		||($coupon->receive_amount==$coupon->amount)
    	) {
    		throw new InternalException('优惠券状态有误！');
    	}

    	$user=$request->user();

    	if ($user->coupons->find($coupon->id)) {
    		throw new InternalException('已领取该优惠券');
    	}

    	$user->coupons()->attach($coupon->id);

    	$coupon->increment('receive_amount');

    	return $this->response->array($user->coupons);

    }
}
