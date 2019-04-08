<?php

namespace App\Admin\Controllers;

use App\Models\Goods;
use App\Models\Category;
use App\Models\Brand;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Http\Request;

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
        $grid->category()->title('商品分类');
        $grid->click_count('点击量');
        $grid->sales_sum('销售量');
       // $grid->total_stock('库存');
        $grid->comment_count('评论数');
        $grid->price('价格');
        $grid->keywords('关键词');
        $grid->goods_cover('封面图')->display(function($thumb){
            if($thumb){
                $src=\Storage::disk('admin')->url($thumb);
                return "<img src='{$src}' width=100 height=60 />";
            }else{
                return "无";
            }
        });
      /*  $grid->goods_images('产品图集')->display(function($goods_images){
            $images='';
            collect($goods_images)->map(function($item,$key)use(&$images){
                 $src=\Storage::disk('admin')->url($item);
                 $images.= "<img src='{$src}' width=100 height=60 />";
            });
           return $images;
        });*/
        //$grid->goods_remark('商品简介');
       // $grid->goods_content('详细内容');
        $grid->goods_type('商品类型')->display(function($type){
            switch ($type) {
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
        $grid->collect_sum('收藏量');
        $grid->is_on_sale('上架')->display(function($is_on_sale){
            return $is_on_sale?"是":"否";
        });
        $grid->is_free_shipping('免邮')->display(function($is_free_shipping){
            return $is_free_shipping?"是":"否";
        });
        $grid->is_recommend('推荐')->display(function($is_recommend){
            return $is_recommend?"是":"否";
        });
        $grid->is_new('新品')->display(function($is_new){
            return $is_new?"是":"否";
        });
        $grid->sort('排序');
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
        $form->select('category_id','所属分类')->options(Category::selectOptions())->load('brand_id','/admin/api/brands')->rules('required');
        $form->select('brand_id','品牌')->rules('required');
        $form->number('click_count', '点击数')->default(0)->rules('required|min:0');
        //$form->decimal('price', '单价')->default(0.00);
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
        $form->select('goods_type', '商品类型')->options(['0'=>'普通商品','1'=>'抢购商品','2'=>'团购商品']);

    })->tab('规格参数',function($form){
         $form->hasMany('goods_skus','参数列表', function(Form\NestedForm $form) {

            $form->text('sku_title','名称')->rules('required');
            $form->decimal('unit_price', '本店价')->default(0.00);
            $form->decimal('market_price', '市场价')->default(0.00);
            $form->decimal('weight', '重量(g)')->default(0.00);
            $form->decimal('volume', '商品体积(m2)')->default(0.00);
            $form->text('goods_remark','描述');
            $form->number('stock','库存')->default(0);
            $form->number('give_integral','赠送积分')->default(0);

        });

    });

    $form->saving(function (Form $form) {
        $form->model()->price = collect($form->input('goods_skus'))->where(Form::REMOVE_FLAG_NAME, 0)->min('unit_price') ?: 0;
        $form->model()->total_stock=collect($form->input('goods_skus'))->sum('stock')?:0;
    });
       
        return $form;
    }

    public function getBrands(Request $request)
    {
        $category_id=$request->q;

        return Brand::where('category_id',$category_id)->get(['id',\DB::raw('name as text')]);

    }
}
