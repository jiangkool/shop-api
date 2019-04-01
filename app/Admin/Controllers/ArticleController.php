<?php

namespace App\Admin\Controllers;

use App\Models\Article;
use App\Models\ArticleCategory;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class ArticleController extends Controller
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
            ->header('文档管理')
            ->description('列表')
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
        $grid = new Grid(new Article);

        $grid->id('ID');
        $grid->articleCategory()->title('栏目');
        $grid->title('标题');
        $grid->adminUser()->username('发布者');
        $grid->author('作者');
        $grid->keywords('关键词');
        $grid->file_url('附件')->display(function($file){
            if($file){
             $src=\Storage::disk('admin')->url($file);
             return "<a href='{$src}' class='btn btn-sm btn-default' target='_blank' >附件</a>";
         }else{
            return "无";
         }
        });
        $grid->click('点击')->sortable();
        $grid->thumb('缩略图')->display(function($thumb){
            if($thumb){
            $src=\Storage::disk('admin')->url($thumb);
            return "<img src='{$src}' width=100 height=60 />";
            }else{
            return "无";
         }
        });
        $grid->is_published('是否发布')->display(function($is_published){
            return $is_published?"是":"否";
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
        $show = new Show(Article::findOrFail($id));

        $show->id('Id');
        $show->article_category_id('Article category id');
        $show->title('Title');
        $show->content('Content');
        $show->author('Author');
        $show->keywords('Keywords');
        $show->file_url('File url');
        $show->description('Description');
        $show->click('Click');
        $show->thumb('Thumb');
        $show->is_published('Is published');
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
        //dd(auth()->guard('admin')->user()->id);
        $form = new Form(new Article);

        $form->select('article_category_id', '栏目')->options(ArticleCategory::selectOptions())->rules('required');
        $form->text('title', '标题')->rules('required');
        $form->simditor('content', '内容')->rules('required');
        $form->text('author', '作者');
        $form->text('keywords', 'Keywords');
        $form->file('file_url', '附件');
        $form->textarea('description', '描述');
        $form->number('click', 'Click')->default(0);
        $form->image('thumb', '缩略图');
        $form->switch('is_published', '是否发布')->default(1);
        $form->hidden('admin_user_id');
        $form->saving(function($form){
            $form->admin_user_id=auth()->guard('admin')->user()->id;
        });
        return $form;
    }
}
