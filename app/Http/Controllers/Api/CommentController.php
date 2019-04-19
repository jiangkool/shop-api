<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Models\OrderItem;
use Dingo\Api\Routing\Helpers;
use App\Service\OrderService;

class CommentController extends Controller
{
	use Helpers;

    public function addOrderItemComment(CommentRequest $request,OrderItem $order_item,OrderService $orderService)
    {
    	$content=$request->content;
    	$rating=$request->rating;
		$orderService->addOrderItemComment($rating,$content,$order_item);

    	return $this->response->created();
    }
}
