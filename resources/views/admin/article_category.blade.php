<div class="box">
    <div class="box-header with-border">
        <section class="content-header">
        <a class="btn btn-sm btn-success" href="{{ route('article_category.create')}}">&nbsp;<i class="fa fa-plus-square" aria-hidden="true"></i> <span class="hidden-xs">添加</span></a>
    </section>
    </div>

    <!-- /.box-header -->
    <div class="box-body table-responsive no-padding">
       <table class="table table-hover" style="margin-bottom: 0;">
    <thead>
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>别名</th>
        <th>导航显示</th>
        <th>排序</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    @foreach($articleCategories as $category)
        @if($category->parent_id==0)
        <tr>
          <td>{{ $category->id }}</td>
          <td><b style="font-size: 16px">{{ $category->title }}</b></td>
          <td>{{ $category->cat_alias }}</td>
          <td>{!! $category->show_in_nav?"<span class='label label-success'>显示</span>":"<span class='label label-default'>隐藏</span>"  !!}</td>
          <td>{{ $category->order }}</td>
          <td><a class="btn btn-sm btn-default" href="{{ route('article_category.edit',$category->id) }}">&nbsp;<i class="fa fa-pencil-square-o" aria-hidden="true"></i> <span class="hidden-xs">编辑</span></a> <a href="javascript:void(0);"  data-id="{{$category->id}}" class="grid-row-delete-cate btn btn-sm btn-warning"><i class="fa fa-trash"></i> 删除</a></td> 
        </tr>
            @foreach($articleCategories->where('parent_id',$category->id)->all() as $subCate)
            <tr>
              <td>{{ $subCate->id }}</td>
              <td style="font-size: 14px">&nbsp;-- {{ $subCate->title }}</td>
              <td>{{ $subCate->cat_alias }}</td>
              <td>{!! $subCate->show_in_nav?"<span class='label label-success'>显示</span>":"<span class='label label-default'>隐藏</span>"  !!}</td>
              <td>{{ $subCate->order }}</td>
              <td><a class="btn btn-sm btn-default" href="{{ route('article_category.edit',$subCate->id) }}">&nbsp;<i class="fa fa-pencil-square-o" aria-hidden="true"></i> <span class="hidden-xs">编辑</span></a> <a href="javascript:void(0);" data-id="{{$subCate->id}}" class="grid-row-delete-cate btn btn-sm btn-warning"><i class="fa fa-trash"></i> 删除</a></td> 
            </tr>
            @if($articleCategories->where('parent_id',$subCate->id)->count()>0)
                @foreach($articleCategories->where('parent_id',$subCate->id)->all() as $subCate2)
                    <tr>
                      <td>{{ $subCate2->id }}</td>
                      <td  style="font-size: 12px">&nbsp;&nbsp;---- {{ $subCate2->title }}</td>
                      <td>{{ $subCate2->cat_alias }}</td>
                      <td>{!! $subCate2->show_in_nav?"<span class='label label-success'>显示</span>":"<span class='label label-default'>隐藏</span>"  !!}</td>
                      <td>{{ $subCate2->order }}</td>
                      <td><a class="btn btn-sm btn-default" href="{{ route('article_category.edit',$subCate2->id) }}">&nbsp;<i class="fa fa-pencil-square-o" aria-hidden="true"></i> <span class="hidden-xs">编辑</span></a> <a href="javascript:void(0);" data-id="{{$subCate2->id}}" class="grid-row-delete-cate btn btn-sm btn-warning"><i class="fa fa-trash"></i> 删除</a></td> 
                    </tr>
                @endforeach
            @endif
            @endforeach
        @endif
    @endforeach
    </tbody>
</table>

    </div>
    <div class="box-footer clearfix">
       
    </div>
    <!-- /.box-body -->
</div>

<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
