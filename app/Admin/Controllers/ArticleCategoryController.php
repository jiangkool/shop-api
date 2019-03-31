<?php

namespace App\Admin\Controllers;

use App\Models\ArticleCategory;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Encore\Admin\Tree;
use Encore\Admin\Traits\ModelTree as ModelTree;

class ArticleCategoryController extends Controller
{
    use ModelTree,HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        $articleCategories=ArticleCategory::orderBy('order','desc')->get();
        return $content
            ->header('Index')
            ->description('description')
            ->body(view('admin.article_category',compact('articleCategories'))->render());
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
        $grid = new Grid(new ArticleCategory);

        $grid->id('Id');
        $grid->title('类别名称');
        $grid->cat_alias('别名');
        $grid->parent()->title('父级栏目');
        $grid->show_in_nav('是否导航显示')->display(function($item){
            return $item?'是':'否';
        });
        $grid->order('排序');
        $grid->cat_desc('分类描述');
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
        $form = new Form(new ArticleCategory);

        $form->text('title', '类别名称')->rules('required');
        $form->text('cat_alias', '别名')->rules('required');
        $form->select('parent_id', '父级栏目')->options(ArticleCategory::selectOptions(null,'顶级栏目'))->rules('required');
        $form->switch('show_in_nav', '是否导航显示')->default(1);
        $form->number('order', '排序')->default(0);
        $form->textarea('cat_desc', '分类描述');

        return $form;
    }
}
