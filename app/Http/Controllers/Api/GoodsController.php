<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Goods;
use Dingo\Api\Routing\Helpers;
use App\Http\Transformers\GoodsTransformer;

class GoodsController extends Controller
{
	use Helpers;

    public function index(Request $request)
    {
    	$goods=Goods::where('is_on_sale',1)->with('goods_skus','order_items')->get();
    	
    	return $this->response->collection($goods, GoodsTransformer::class);
    }

    public function show(Request $request,Goods $goods)
    {
    	$goods->load('goods_skus');

    	
    }

}
