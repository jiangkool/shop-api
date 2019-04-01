<?php

namespace App\Admin\Controllers;

use App\Models\Goods;
use App\Models\Category;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class GoodsController extends Controller
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
        $grid = new Grid(new Goods);

        $grid->id('Id');
        $grid->title('名称');
        $grid->brand()->name('所属品牌');
        $grid->category()->name('商品分类');
        $grid->click_count('点击量');
        $grid->total_stock('库存');
        $grid->comment_count('评论数');
        $grid->price('价格');
        $grid->keywords('关键词');
        $grid->goods_cover('封面图');
        $grid->goods_images('产品图集');
        //$grid->goods_remark('商品简介');
       // $grid->goods_content('详细内容');
        $grid->goods_type('商品类型');
        $grid->collect_sum('收藏量');
        $grid->is_on_sale('上架');
        $grid->is_free_shipping('免邮');
        $grid->is_recommend('推荐');
        $grid->is_new('新品');
        $grid->sort('排序');
        $grid->sales_sum('销售量');
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
        $show = new Show(Goods::findOrFail($id));

        $show->id('Id');
        $show->title('Title');
        $show->brand_id('Brand id');
        $show->click_count('Click count');
        $show->total_stock('Total stock');
        $show->comment_count('Comment count');
        $show->price('Price');
        $show->keywords('Keywords');
        $show->goods_cover('Goods cover');
        $show->goods_images('Goods images');
        $show->goods_remark('Goods remark');
        $show->goods_content('Goods content');
        $show->goods_type('Goods type');
        $show->collect_sum('Collect sum');
        $show->is_on_sale('Is on sale');
        $show->is_free_shipping('Is free shipping');
        $show->is_recommend('Is recommend');
        $show->is_new('Is new');
        $show->sort('Sort');
        $show->sales_sum('Sales sum');
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
        $form = new Form(new Goods);
        $form->tab('基本信息',function($form){
        $form->text('title', '名称')->rules('required');
        $form->select('category_id')->options(Category::selectOptions())->rules('required');
       // $form->select('brand_id')->load('api/brands');
        //$form->number('click_count', '点击数');
        $form->decimal('price', '单价')->default(0.00);
        $form->text('keywords', '关键词');
        $form->switch('is_on_sale', '上架')->default(1);
        $form->switch('is_free_shipping', '免邮')->default(0);
        $form->switch('is_recommend', '推荐')->default(0);
        $form->switch('is_new', '新品')->default(0);
        $form->number('sort', '排序')->default(0);

    })->tab('商品图片',function($form){
         $form->image('goods_cover', '封面');
         $form->multipleImage('goods_images', '图集')->removable();

    })->tab('商品详细',function($form){

        $form->textarea('goods_remark', '商品描述');
        $form->simditor('goods_content', '商品详情');
        $form->number('goods_type', '商品类型');

    })->tab('规格参数',function($form){
         $form->hasMany('goodsSkus', '', function(Form\NestedForm $form) {
            $form->text('title','名称')->rules('required');
            $form->decimal('unit_price', '本店价')->default(0.00);
            $form->decimal('market_price', '市场价')->default(0.00);
            $form->decimal('weight', '重量(g)')->default(0.00);
            $form->decimal('volume', '商品体积(m2)')->default(0.00);
            $form->text('goods_remark','描述');
            $form->number('give_integral','赠送积分')->default(0);
        });
    });
       
        

        return $form;
    }
}
