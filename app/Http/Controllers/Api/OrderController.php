<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderStoreRequest;
use Carbon\Carbon;
use App\Models\Address;
use App\Models\Order;
use App\Models\GoodsSku;

class OrderController extends Controller
{

   public function store(OrderStoreRequest $request)
   {
   		$user=$request->user();
   		//dd(json_decode($request->order_items,true));
   		$order = \DB::transaction(function () use($request,$user) {

   			$total_money=0;
   			$address=Address::find($request->address_id);
   			$address->update(['last_used_at' => Carbon::now()]);

	   		$order=new Order([
				'product_type'=>$request->product_type,
				'total_money'=>0,
				'address'=>[
					'addressee_name'=>$address->addressee_name,
					'phone'=>$address->phone,
					'province'=>$address->province,
					'city'=>$address->city,
					'district'=>$address->district,
					'address_details'=>$address->address_details
				],
	   		]);

	   		$order->user()->associate($user);
	   		$order->save();

	   		$order_items=json_decode($request->order_items,true);

	   		foreach ($order_items as $item) {
	   			$goods_sku  = GoodsSku::find($item['goods_sku_id']);
	   			$orderItem=$order->items()->make([
	   				'amount'=>$item['amount'],
	   				'price'=>$goods_sku->unit_price
	   			]);

	   			$orderItem->goods()->associate($order);
	   			$orderItem->goods_sku()->associate($goods_sku);

	   			//判断库存情况
	   			if($goods_sku->decreaseStock($item['amount'])<=0)
	   			{
	   				abort('库存不足');
	   			}

	   			$orderItem->save();

	   			$total_money+=$item['amount']*$goods_sku->unit_price;
	   		}

	   		$order->update(['total_money' => $total_money]);

	   		return $order;

   		});
   		


   		return $order;
   }

}
