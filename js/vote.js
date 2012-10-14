function vote(id, mode) {
	$.ajax({
		   type: "GET",
		   url: "/index.php/story/vote?id="+id+"&mode="+mode,
		   success: function(msg){
		     alert( "提交成功！");
		     $('#'+mode+'-'+id).html(parseInt($('#'+mode+'-'+id).text())+parseInt(msg));
		     
		   }
		});
}