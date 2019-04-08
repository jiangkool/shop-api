<div class="box">
    <div class="box-header with-border">
        <section class="content-header">
          <h1>订单详细</h1>
    </section>
    </div>

    <!-- /.box-header -->
    <div class="box-body table-responsive no-padding">
       <table class="table table-bordered" style="margin-bottom: 0;">
          <tr><th width="100">订单号</th><td>{{ $order->no }}</td></tr>
          <tr><th>会员</th><td>{{ $order->user->name }}</td></tr>
          <tr><th>下单时间</th><td>{{ $order->created_at }}</td></tr>
          <tr><th>收件信息</th><td>{{ implode(' ', $order->address) }}</td></tr>
          <tr><th>订单内容</th><td>
            @foreach($order_items as $item)   
            <div style="float: left;margin: 10px"><img src="/uploads/{{ $item->goods->goods_cover }}" width="100" /></div>
              <b>{{ $item->goods->title }}</b><br>
              <b>{{ $item->goods_sku->sku_title }}</b><br>
              <b>类目:</b> <span>{{ $item->goods->category->title }} {{ $item->goods->brand->name }}</span><br>
              <b>单价:</b> <span>{{ $item->price }} 元</span><br>
              <b>数量:</b> <span>X {{ $item->amount }} </span><hr>
              <b>总计:</b> <span>{{ $item->amount*$item->price }} 元</span><br>
              <hr>
            @endforeach
          </td></tr>
          <tr><th>合计</th><td><b>{{ $order->total_money }} 元 </b></td></tr>
          <tr><th>备注</th><td>{{ $order->bark }}</td></tr>
          <tr><th>订单状态</th><td>
              {!! $order->closed? '关闭':'<span class="label label-default">未关闭</span>' !!}
               @if($order->paid_at) 
                @if($order->refund_status==App\Models\Order::REFUND_STATUS_PENDING)
                  <span class="label label-success">已付款</span> &nbsp;&nbsp;付款时间：{{ $order->paid_at }}

                @else
                   <span class="label label-warning">{{ App\Models\Order::$refundStatusMap[$order->refund_status] }} </span>
                    &nbsp;&nbsp;额外信息: {{ implode(' ', $order->extra) }}
                @endif
              @else
                <span class="label label-default">未付款</span>
              @endif
              
            </td>
          </tr>
          @if($order->refund_status==App\Models\Order::REFUND_STATUS_APPLIED)
          <tr><th>操作</th><td><a  class="btn btn-sm btn-success" id="btn-refund-agree" data-id="{{ $order->id }}">同意退款</a> <a data-id="{{ $order->id }}"  class="btn btn-sm btn-warning" id="btn-refund-disagree">拒绝退款</a></td></tr>
          @endif 
          @if($order->refund_status==App\Models\Order::REFUND_STATUS_PENDING)
          <tr>
            <th>物流信息</th><td>todo</td>
          </tr>
          @endif
      </table>  

    </div>
    <div class="box-footer clearfix">
       
    </div>
    <!-- /.box-body -->
</div>

<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>
  $(function(){
    // 不同意 按钮的点击事件
    $('#btn-refund-disagree').click(function() {
      // Laravel-Admin 使用的 SweetAlert 版本与我们在前台使用的版本不一样，因此参数也不太一样
      swal({
        title: '输入拒绝退款理由',
        input: 'text',
        showCancelButton: true,
        confirmButtonText: "确认",
        cancelButtonText: "取消",
        showLoaderOnConfirm: true,
        preConfirm: function(inputValue) {
          if (!inputValue) {
            swal('理由不能为空', '', 'error')
            return false;
          }
          // Laravel-Admin 没有 axios，使用 jQuery 的 ajax 方法来请求
          return $.ajax({
            url: '{{ route('admin.orders.handle_refund', [$order->id]) }}',
            type: 'POST',
            data: JSON.stringify({   // 将请求变成 JSON 字符串
              agree: false,  // 拒绝申请
              reason: inputValue,
              // 带上 CSRF Token
              // Laravel-Admin 页面里可以通过 LA.token 获得 CSRF Token
              _token: LA.token,
            }),
            contentType: 'application/json',  // 请求的数据格式为 JSON
          });
        },
        allowOutsideClick: () => !swal.isLoading()
      }).then(function (ret) {
        // 如果用户点击了『取消』按钮，则不做任何操作
        if (ret.dismiss === 'cancel') {
          return;
        }
        swal({
          title: '操作成功',
          type: 'success'
        }).then(function() {
          // 用户点击 swal 上的按钮时刷新页面
          location.reload();
        });
      });
    });

    // 同意 按钮的点击事件
    $('#btn-refund-agree').click(function() {
      swal({
        title: '确认要将款项退还给用户？',
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: "确认",
        cancelButtonText: "取消",
        showLoaderOnConfirm: true,
        preConfirm: function() {
          return $.ajax({
            url: '{{ route('admin.orders.handle_refund', [$order->id]) }}',
            type: 'POST',
            data: JSON.stringify({
              agree: true, // 代表同意退款
              _token: LA.token,
            }),
            contentType: 'application/json',
          });
        }
      }).then(function (ret) {
        // 如果用户点击了『取消』按钮，则不做任何操作
        if (ret.dismiss === 'cancel') {
          return;
        }
        swal({
          title: '操作成功',
          type: 'success'
        }).then(function() {
          // 用户点击 swal 上的按钮时刷新页面
          location.reload();
        });
      });
    });

  })
</script>