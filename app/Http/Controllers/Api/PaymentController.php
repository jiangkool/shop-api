<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Exceptions\InvalidRequestException;
use Dingo\Api\Routing\Helpers;
use Carbon\Carbon;
use Pay;
use App\Events\OrderPaid;

class PaymentController extends Controller
{
    use Helpers;

    public function alipayPayment(Order $order,Request $request)
    {
    	if ($order->paid_at || $order->closed) {
    		throw new InvalidRequestException('订单已关闭');
    	}
    	$order=[
    		'out_trade_no' => $order->no,
    		'total_money'  => $order->total_money,
    		'subject'      => '支付订单：'.$order->no,
    	];

    	return pay::alipay()->app($order);
    }

    public function alipayReturn()
    {
        try {
            Pay::alipay()->verify();
        } catch (\Exception $e) {
            return $this->response->error('数据不正确！');
        }

        return $this->response->array(['msg' => '付款成功']);
    }

    public function alipayNotify()
    {
        //验证阿里返回的的数据
        $data=Pay::alipay()->verify();

        //如果不是交易成功 或者 交易完成 状态 则不走后续流程
        if(!in_array($data->trade_status, ['TRADE_SUCCESS', 'TRADE_FINISHED'])) {
            return Pay::alipay()->success();
        }

        // 校验订单是否存在
        $order = Order::where('no', $data->out_trade_no)->first();

        if (!$order) {
            return 'fail';
        }

        // 已支付订单直接返回
        if ($order->paid_at) {
            // 返回数据给支付宝
            return Pay::alipay()->success();
        }

        // 更新订单付款信息
        $order->update([
            'paid_at'        => Carbon::now(), // 支付时间
            'payment_method' => 'alipay', // 支付方式
            'payment_no'     => $data->trade_no, // 支付宝订单号
        ]);
        
        $this->afterPaid($order);

        return Pay::alipay()->success();
    }

    public function wechatPayment(Order $order,Request $request)
    {
        if ($order->paid_at || $order->closed) {
            throw new InvalidRequestException('订单已关闭');
        }
        $order=[
            'out_trade_no' => $order->no,
            'total_fee'  => $order->total_money,
            'body'      => '支付订单：'.$order->no,
        ];

        return Pay::wechat()->app($order);
    }

    public function wechatNotify()
    {
        //验证阿里返回的的数据
        $data=Pay::wechat()->verify();

        //如果不是交易成功 或者 交易完成 状态 则不走后续流程
        if($data->return_code!='SUCCESS') {
            return Pay::wechat()->success();
        }

        // 校验订单是否存在
        $order = Order::where('no', $data->out_trade_no)->first();

        if (!$order) {
            return 'fail';
        }

        // 已支付订单直接返回
        if ($order->paid_at) {
            // 返回数据给微信
            return Pay::wechat()->success();
        }

        // 更新订单付款信息
        $order->update([
            'paid_at'        => Carbon::now(), // 支付时间
            'payment_method' => 'wechat', // 支付方式
            'payment_no'     => $data->transaction_id, // 微信支付订单号
        ]);

        $this->afterPaid($order);

        return Pay::wechat()->success();
    }

    public function afterPaid(Order $order)
    {
        event(new OrderPaid($order));
    }

    public function wechatRefundNotify(Request $request)
    {
        // 微信失败响应xml
        $failXml = '<xml><return_code><![CDATA[FAIL]]></return_code><return_msg><![CDATA[FAIL]]></return_msg></xml>';

        // 验证微信服务器返回退款数据 true 为 refund 状态
        $data=Pay::wechat()->verify(null, true);

        if (!$order = Order::where('no', $data['out_trade_no'])->first()) {
            return $failXml;
        }
        // 状态为成功 SUCCESS
        if ($data['refund_status'] === 'SUCCESS') {
            $order->update([
                'refund_status'=>Order::REFUND_STATUS_SUCCESS
            ]);

        }else{

            $extra = $order->extra;
            $extra['refund_failed_code'] = $data['refund_status'];
            $order->update([
                'refund_status' => Order::REFUND_STATUS_FAILED,
                'extra'         => $extra
            ]);
        }

        return Pay::wechat()->success();

    }


}
