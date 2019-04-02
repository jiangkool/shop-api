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
        $grid->user_id('User id');
        $grid->product_type('Product type');
        $grid->total_money('Total money');
        $grid->address_id('Address id');
        $grid->paid_at('Paid at');
        $grid->payment_method('Payment method');
        $grid->payment_no('Payment no');
        $grid->refund_no('Refund no');
        $grid->refund_status('Refund status');
        $grid->ship_id('Ship id');
        $grid->ship_status('Ship status');
        $grid->bark('Bark');
        $grid->closed('Closed');
        $grid->extra('Extra');
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

        $form->text('no', 'No');
        $form->number('user_id', 'User id');
        $form->number('product_type', 'Product type');
        $form->decimal('total_money', 'Total money');
        $form->number('address_id', 'Address id');
        $form->datetime('paid_at', 'Paid at')->default(date('Y-m-d H:i:s'));
        $form->text('payment_method', 'Payment method');
        $form->text('payment_no', 'Payment no');
        $form->text('refund_no', 'Refund no');
        $form->text('refund_status', 'Refund status');
        $form->number('ship_id', 'Ship id');
        $form->text('ship_status', 'Ship status');
        $form->textarea('bark', 'Bark');
        $form->switch('closed', 'Closed');
        $form->textarea('extra', 'Extra');

        return $form;
    }
}
