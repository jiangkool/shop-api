<?php

namespace App\Admin\Controllers;

use App\Models\GoodsSku;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class GoodsSkuController extends Controller
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
        $grid = new Grid(new GoodsSku);

        $grid->id('Id');
        $grid->title('Title');
        $grid->goods_id('Goods id');
        $grid->stock('Stock');
        $grid->unit_price('Unit price');
        $grid->market_price('Market price');
        $grid->weight('Weight');
        $grid->volume('Volume');
        $grid->goods_remark('Goods remark');
        $grid->give_integral('Give integral');
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
        $show = new Show(GoodsSku::findOrFail($id));

        $show->id('Id');
        $show->title('Title');
        $show->goods_id('Goods id');
        $show->stock('Stock');
        $show->unit_price('Unit price');
        $show->market_price('Market price');
        $show->weight('Weight');
        $show->volume('Volume');
        $show->goods_remark('Goods remark');
        $show->give_integral('Give integral');
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
        $form = new Form(new GoodsSku);

        $form->text('title', 'Title');
        $form->number('goods_id', 'Goods id');
        $form->number('stock', 'Stock');
        $form->decimal('unit_price', 'Unit price')->default(0.00);
        $form->decimal('market_price', 'Market price')->default(0.00);
        $form->number('weight', 'Weight');
        $form->decimal('volume', 'Volume')->default(0.00);
        $form->textarea('goods_remark', 'Goods remark');
        $form->number('give_integral', 'Give integral');

        return $form;
    }
}
