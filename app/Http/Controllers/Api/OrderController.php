<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderStoreRequest;
use App\Models\Order;
use App\Exceptions\InternalException;
use App\Http\Requests\OrderRefundRequest;
use Dingo\Api\Routing\Helpers;
use App\Service\OrderService;

class OrderController extends Controller
{
	use Helpers;

	protected $orderService;

	public function __construct(OrderService $orderService)
	{
		$this->orderService=$orderService;
	}

	public function store(OrderStoreRequest $request)
	{
		$user=$request->user();
		$address_id=$request->address_id;
		$product_type=$request->product_type;
		$bark=$request->bark;
		$order_items=json_decode($request->order_items,true);

		return $this->orderService->store($user,$order_items,$address_id,$product_type,$bark);
	}

	public function orderRefund(OrderRefundRequest $request,Order $order)
	{
		$this->authorize('own',$order);

		$data['refund_reason']=$request->refund_reason;

		$this->orderService->applyOrderRefund($order,$data);

		return $this->response->noContent();
	}

	public function receipt(Request $request,Order $order)
	{
		$this->authorize('own',$order);
		$this->orderService->receipt($order);
		
		return $this->response->noContent();
	}

}
