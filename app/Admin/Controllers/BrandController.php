<?php

namespace App\Admin\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class BrandController extends Controller
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
        $grid = new Grid(new Brand);

        $grid->id('Id');
        $grid->category()->title('所属分类');
        $grid->name('名称');
        $grid->logo('Logo')->display(function($src){
            if ($src) {
                $href=\Storage::disk('admin')->url($src);
                return "<img src={$href} width=100 height=60 />";
            }

            return "--";
        });
        $grid->desc('描述');
        $grid->sort('排序')->sortable();
        $grid->is_rec('推荐')->display(function($item){
            return $item?"是":"否";
        });
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
        $show = new Show(Brand::findOrFail($id));

        $show->id('Id');
        $show->category_id('Category id');
        $show->name('Name');
        $show->logo('Logo');
        $show->desc('Desc');
        $show->sort('Sort');
        $show->is_rec('Is rec');
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
        $form = new Form(new Brand);

        $form->select('category_id', '所属分类')->options(Category::selectOptions())->rules('required');
        $form->text('name', '名称')->rules('required');
        $form->image('logo', 'Logo');
        $form->textarea('desc', '描述');
        $form->number('sort', '排序')->default(0);
        $form->switch('is_rec', '推荐')->default(0);

        return $form;
    }
}
