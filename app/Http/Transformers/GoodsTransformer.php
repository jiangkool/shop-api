<?php
namespace App\Http\Transformers;

use App\Models\Goods;
use App\Models\OrderItem;
use Dingo\Api\Http\Request;
use League\Fractal\ParamBag;
use League\Fractal\TransformerAbstract;

class GoodsTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['comments'];

 	public function transform(Goods $goods)
    {

        return [
        	'id'			=>	$goods->id,
        	'title'			=>	$goods->title,
        	'brand'			=>	$goods->brand->name,
        	'category'		=>	$goods->category->title,
        	'click_count'	=>	$goods->click_count,
        	'comment_count'	=>	$goods->comment_count,
        	'goods_cover'	=>	\Storage::disk('admin')->url($goods->goods_cover),
        	'price' 		=> 	$goods->price,
        	'keywords' 		=>  $goods->keywords,
        	'goods_type'   	=>	$goods->goods_type,
        	'collect_sum'	=>	$goods->collect_sum,
        	'is_free_shipping'=>$goods->is_free_shipping,
        	'is_recommend' => $goods->is_recommend,
        	'is_new' => $goods->is_new,
        	'sort' => $goods->sort,
        	'sales_sum' => $goods->sales_sum,
        	'goods_skus'=> $goods->goods_skus,
        	'avg_rating'=> $goods->order_items->avg('rating')
        ];
    }

    public function includeComments(OrderItem $order_item, ParamBag $params = null)
    {
        $limit = 10;
        if ($params->get('limit')) {
            $limit = (array) $params->get('limit');
            $limit = (int) current($limit);
        }
        $comments = $order_item->comments()->limit($limit)->get();
        return $this->collection($comments, new CommentTransformer())
            ->setMeta([
                'limit' => $limit,
                'count' => $comments->count(),
            ]);
    }

}