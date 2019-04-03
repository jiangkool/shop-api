<?php

namespace App\Admin\Controllers;

use App\Models\Order;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

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
        return $content
            ->header('Detail')
            ->description('description')
            ->body($this->detail($id));
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
        $grid->refund_status('退款状态');
        $grid->ship_id('物流');
        //$grid->ship_status('物流状态');
        $grid->bark('备注');
        $grid->closed('关闭');
        $grid->extra('额外');
        $grid->created_at('Created at');
        $grid->updated_at('Updated at');

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Order::findOrFail($id));

        $show->id('Id');
        $show->no('No');
        $show->user_id('User id');
        $show->product_type('Product type');
        $show->total_money('Total money');
        $show->address_id('Address id');
        $show->paid_at('Paid at');
        $show->payment_method('Payment method');
        $show->payment_no('Payment no');
        $show->refund_no('Refund no');
        $show->refund_status('Refund status');
        $show->ship_id('Ship id');
        $show->ship_status('Ship status');
        $show->bark('Bark');
        $show->closed('Closed');
        $show->extra('Extra');
        $show->created_at('Created at');
        $show->updated_at('Updated at');

        return $show;
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
}
