<?php

namespace App\Admin\Controllers;

use App\Models\Order;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Http\Request;
use App\Exceptions\InternalException;
use Pay;

class OrderController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('Index')
            ->description('description')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        $order=Order::find($id);
        $order_items=$order->items->load('goods');
        // $goods=$order->items->load('goods');
        return $content
            ->header('Detail')
            ->description('description')
            ->body(view('admin.order',compact('order','order_items'))->render());
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('Edit')
            ->description('description')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('Create')
            ->description('description')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Order);

        $grid->id('Id');
        $grid->no('No');
        $grid->user()->name('姓名');
        $grid->product_type('商品类型')->display(function($product_type){
            switch ($product_type) {
               case 2:
                    return '<span class="label label-default">团购商品</span>';
                    break;
                case 1:
                    return '<span class="label label-default">抢购商品</span>';
                    break;
                default:
                    return '<span class="label label-default">普通商品</span>';
                    break;
            }
        });

        $grid->total_money('总金额');
        $grid->address('地址')->display(function($address){
            return <<<ADD
    {$address['province']}{$address['city']}{$address['district']}{$address['address_details']} {$address['addressee_name']} {$address['phone']}
ADD;
        });
        $grid->paid_at('付款时间');
        $grid->payment_method('方式');
        $grid->payment_no('付款编号');
        $grid->refund_no('退款编号');
        $grid->refund_status('退款状态')->display(function($refund_status){
            if (!empty($refund_status)) {
                return Order::$refundStatusMap[$refund_status];
            }
        });
        $grid->ship_status('物流状态')->display(function($ship_status){
            if (!empty($ship_status)) {
                return Order::$shipStatusMap[$ship_status];
            }
        });
        //$grid->ship_status('物流状态');
        $grid->bark('备注');
        $grid->closed('关闭')->display(function($item){
            return $item?"<label class='label label-warning'>已关闭</label>":"<label class='label label-success'>正常</label>";
        });
        $grid->extra('额外')->display(function($extra){
            if (!empty($extra)) {
                return implode('', $extra);
            }
            
        });
        $grid->created_at('Created at');
        $grid->updated_at('Updated at');

        return $grid;
    }


    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Order);


        return $form;
    }

    public function refundConfirmation(Request $request,Order $order)
    {
        if (!$request->agree) {

            $data['reason']=$request->reason;
            $order->update([
                'refund_status'=>Order::REFUND_STATUS_FAILED,
                'extra'=>$data
            ]);

        }else{

            $this->_orderRefund($order);
        }

    }

    protected function _orderRefund(Order $order)
    {
        // 检测订单是否已支付 是否关闭 订单是否申请退款
        if (!$order->paid_at 
            || $order->closed 
            || $order->refund_status!=Order::REFUND_STATUS_APPLIED) {

            throw new InternalException('订单状态不正确！');
        }

        $refundNo = Order::getAvailableRefundNo();

        // 根据付款方式退款
        if ($order->payment_method=='alipay') {
            $data=[
                'out_trade_no'=>$order->payment_no,
                'refund_amount'=>$order->total_money,
                'out_request_no' => $refundNo
            ];

            $result = Pay::alipay()->refund($data);

            if (isset($result->sub_code)) {

                $order->update([
                    'refund_no'    => $refundNo,
                    'refund_status'=> Order::REFUND_STATUS_FAILED,
                    'extra'        => ['refund_failed_code'=>$result->sub_code]
                ]);

            }else{
                
                $order->update([
                    'refund_no'=>$refundNo,
                    'refund_status'=>Order::REFUND_STATUS_SUCCESS,
                ]);
            
            }
        }elseif ($order->payment_method=='wechat') {

            $data=[
                'out_trade_no' => $order->payment_no,
                'out_refund_no' => $refundNo,
                'total_fee' => $order->total_money*100,
                'refund_fee' => $order->total_money*100,
                'refund_desc' => '退款',
               // 'notify_url' => route('payment.wechat.refund_notify'),
            ];

            $result = Pay::wechat()->refund($data);

            $order->update([
                'refund_no'=>$refundNo,
                'refund_status'=>Order::REFUND_STATUS_PROCESSING,
            ]);
          
        }else{
            throw new InternalException('未知订单支付方式：'.$order->payment_method);
        } 

    }


}
