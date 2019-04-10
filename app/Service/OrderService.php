<?php

namespace App\Service;

use App\Models\User;
use App\Models\Order;
use App\Models\Address;
use App\Models\GoodsSku;
use App\Exceptions\InternalException;
use App\Jobs\OrderClosed;
use Carbon\Carbon;
use App\Models\Coupon;

class OrderService
{
	public function store(User $user,$order_items,$address_id,$product_type,$bark,Coupon $coupon=null)
	{
		return \DB::transaction(function () use($user, $order_items, $address_id, $product_type, $bark,$coupon) {

			if (!is_null($coupon)) {

	   			if ($user->coupons()->where('coupon_id',$coupon->id)->where('used',1)->first()) {
	   				throw new InternalException('该优惠券已使用');
	   			}
	   		}

   			$total_money=0;
   			$address=Address::find($address_id);
   			$address->update(['last_used_at' => Carbon::now()]);

	   		$order=new Order([
				'product_type'=>$product_type,
				'total_money'=>0,
				'address'=>[
					'addressee_name'=>$address->addressee_name,
					'phone'=>$address->phone,
					'province'=>$address->province,
					'city'=>$address->city,
					'district'=>$address->district,
					'address_details'=>$address->address_details
				],
				'bark'=>$bark,
				'refund_status'=>Order::REFUND_STATUS_PENDING
	   		]);

	   		$order->user()->associate($user);
	   		$order->save();

	   		foreach ($order_items as $item) {
	   			$goods_sku  = GoodsSku::find($item['goods_sku_id']);
	   			$orderItem=$order->items()->make([
	   				'amount'=>$item['amount'],
	   				'price'=>$goods_sku->unit_price
	   			]);

	   			$orderItem->goods()->associate($goods_sku->goods_id);
	   			$orderItem->goods_sku()->associate($goods_sku);
	   			
	   			//判断库存情况
	   			if($goods_sku->decreaseStock($item['amount']) <= 0)
	   			{
	   				throw new InternalException('库存不足');
	   			}

	   			$orderItem->save();

	   			$total_money+=$item['amount']*$goods_sku->unit_price;
	   		}

	   		if (!is_null($coupon)) {

	   			$total_money=$coupon->calculatedAmount($total_money);

	   			$order->update([
		   			'total_money' => $total_money,
		   			'coupon_id' => $coupon->id
	   			]);

	   			$user->coupons()->updateExistingPivot($coupon->id,['used'=>1]);

	   			$order->coupon->increment('used_amount');

	   		}else{

	   			$order->update([
		   			'total_money' => $total_money
	   			]);
	   		}

	   		dispatch(new OrderClosed($order,60));

	   		return $order;

   		});
	}


	public function applyOrderRefund(Order $order , $data)
	{
		$order->update([
   			'refund_status'=>Order::REFUND_STATUS_APPLIED,
   			'extra'=>$data
   		]);
	}

	public function receipt(Order $order)
	{
		if ($order->ship_status!=Order::SHIP_STATUS_DELIVERED 
			|| is_null($order->paid_at) 
			|| $order->closed) 
		{
			throw new InternalException('订单状态不正确');
		}

		$order->update([
			'ship_status'=>Order::SHIP_STATUS_RECEIVED
		]);


	}


}