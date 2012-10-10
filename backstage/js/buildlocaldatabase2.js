


function load1(i1){
	
	inow=i1;
	if (a[i1]){
		//start=true;
		//$('#'+a[i1][0]).html($('#'+a[i1][0]).html()+'.................<span id="'+a[i1][0]+'len">0</span>');
		
		$.ajax({
			url:a[i1][1],
			success:function(data){
				
			}
		})
			
	}
	else
		{
	//		start=false;
		}
	}

function load2(){
	//if (!start)
	//{
	//	setTimeout("load2()",500);
	//}
	//else
	//{
		if (a[inow]){
		$.ajax({
			url:'http://127.0.0.1:8100/api.php?table=helper&action=getTableLength&zipmode=num&table_length='+a[inow][0],
			success:function(data){
				var speed=(data-last)/0.5;
				$('#'+a[inow][0]+'len').html('已处理'+data+'，速度为：'+speed+'条/秒');
				last=data;
				setTimeout("load2()",500);
				//load2();
								}
			})
			
		}
		else
			{
			setTimeout("load2()",500);
		//	load2();
			}
//	}
}
function loader_eval(){
	
	 a=dlist.databases;
	for ( var i = 0; i < a.length; i++) {
		$('#list').html($('#list').html()+'<tr> <td id="'+a[i][0]+'">'+a[i][0]+'</td><td  id="'+a[i][0]+'len"> </td><td  id="'+a[i][0]+'sti"> </td></tr>');
		//alert($('#a[i][0]').html());
	}
	//var start=false;

	inow=0;
	last=0;
	load2();
	load1(0);
}
   