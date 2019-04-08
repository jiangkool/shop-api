
$(function(){

	$("a.grid-row-delete-cate").click(function(){

		var id=$(this).data('id');
		var url='/admin/article_category/'+id;
		swal({
		 title: "确认删除?",
	      type: "warning",
	      showCancelButton: true,
	      confirmButtonColor: "#DD6B55",
	      confirmButtonText: "确认",
	      closeOnConfirm: true,
	      cancelButtonText: "取消"
		})
		.then((willDelete) => {
		  if (willDelete.value==true) {
		  	axios.delete(url).then(function(res){
			swal('删除成功',{
				icon: "success",
			});
			 $.pjax.reload('#pjax-container');
			})
		  } else {
		    swal("Your imaginary file is safe!");
		  }
		});
		
	})

	$("a.grid-row-delete-cate2").click(function(){

		var id=$(this).data('id');
		var url='/admin/category/'+id;
		swal({
		 title: "确认删除?",
	      type: "warning",
	      showCancelButton: true,
	      confirmButtonColor: "#DD6B55",
	      confirmButtonText: "确认",
	      closeOnConfirm: true,
	      cancelButtonText: "取消"
		})
		.then((willDelete) => {
		  if (willDelete.value==true) {
		  	axios.delete(url).then(function(res){
			swal('删除成功',{
				icon: "success",
			});
			 $.pjax.reload('#pjax-container');
			})
		  } else {
		    swal("Your imaginary file is safe!");
		  }
		});
		
	})

	$(".agree").click(function(){

		swal({
		  content: {
		    element: "input"
		  },
		});

	})


})