<?php

namespace App\Admin\Controllers;

use App\Models\Coupon;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class CouponController extends Controller
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
        $grid = new Grid(new Coupon);

        $grid->id('Id');
        $grid->title('名称');
        $grid->column('优惠信息')->display(function(){
            return Coupon::$typeMaps[$this->type].':'.$this->type_value;
        });
        $grid->min_condition_money('最低使用额度');
        $grid->amount('总数量');
        $grid->receive_amount('已领取');
        $grid->used_amount('已使用');
        $grid->start_time('领取开始时间');
        $grid->end_time('领取结束时间');
        $grid->start_use_time('使用开始时间');
        $grid->end_use_time('结束时间');
        $grid->status('状态')->display(function($status){
            return $status? "启用":"禁用";
        });
        $grid->use_scope('适应范围')->display(function($scope){
            return Coupon::$useScopeMaps[$scope];
        });
        $grid->created_at('Created at');
        $grid->updated_at('Updated at');
        $grid->actions(function ($actions) {
            //$actions->disableDelete();
            //$actions->disableEdit();
            $actions->disableView();
        });
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
        $show = new Show(Coupon::findOrFail($id));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Coupon);

        $form->text('title', '名称');
        $form->select('receive_type', '领取类型')->options(Coupon::$receiveTypeMaps)->default(Coupon::FREE_COLLECTION);
        $form->select('type', '类型')->options(Coupon::$typeMaps)->default('reduce');
        $form->decimal('type_value', '优惠数值')->help('只需填写数字 例如：20 减免类型为优惠20元 折扣类型为 20%');
        $form->decimal('min_condition_money', '最小使用金额')->default(0);
        $form->number('amount', '总数量')->default(0);
        $form->datetime('start_time', '领取开始时间')->default(date('Y-m-d H:i:s'));
        $form->datetime('end_time', '领取结束时间')->default(date('Y-m-d H:i:s'));
        $form->datetime('start_use_time', '开始使用时间')->default(date('Y-m-d H:i:s'));
        $form->datetime('end_use_time', '结束使用时间')->default(date('Y-m-d H:i:s'));
        $form->switch('status', '是否启用')->default(1);
        $form->select('use_scope', '使用范围')->options(Coupon::$useScopeMaps)->default('whole_store');

        return $form;
    }
}
