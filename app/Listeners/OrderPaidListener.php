<?php

namespace App\Listeners;

use App\Events\OrderPaid;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\OrderItem;
use App\Models\Order;
use App\Notifications\OrderPaidNotification;

class OrderPaidListener implements ShouldQueue
{

    /**
     * Handle the event.
     *
     * @param  OrderPaid  $event
     * @return void
     */
    public function handle(OrderPaid $event)
    {
        $order=$event->getOrder();

        //再次验证是否支付
        if (!$order->paid_at || $order->closed) {
            return ;
        }

        $order->load('items.goods');

        foreach ($order->items as $item) {
            $goods=$item->goods;
            $soldCount = OrderItem::query()
                ->where('goods_id',$goods->id)
                ->whereHas('order',function($query){
                    $query->whereNotNull('paid_at');
                })->sum('amount');

            $goods->update([
                'sales_sum'=>$soldCount
            ]);
        }

       $order->user->notify(new OrderPaidNotification($order));

    }
}
