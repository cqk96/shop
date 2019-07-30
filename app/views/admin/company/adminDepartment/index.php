<?php include_once('../app/views/admin/_header.php') ?>
<link rel="stylesheet" type="text/css" href="/js/tools/layer/skin/default/layer.css">
<style type="text/css">
	td img {
		display: block;
		width: 100px;
		height: 100px;
		margin: 0 auto;
	}
	thead td,thead th,tbody td,tbody th {
		text-align: center;
	}
	table {
		width: 100%;
	}
	/*头部样式*/
	table {
		width: 100%;
	}
	thead tr{
		height: 40px;
	}
	/*修改默认样式*/
	.smart-widget-header {
		margin-bottom: 5px;
	}
	.smart-widget {
		border-top-width: 0px;
	}
	.smart-widget .smart-widget-header {
		background-color: #86a4ee;
		color: #fff;
	}
	tbody tr {
		height: 100px;
	}
	.singular {
		background-color: #f5f8fe;
	}
	tbody tr:nth-child(2n+1){
		background:#f5f8fe;
	}
	.dual {
		background-color: #FFF;
	}
	tbody tr:nth-child(2n){
		background:#FFF;
	}
	.smart-widget-option i {
		color: #FFF;
	}
	/*图像*/
	.operationBox a {
		display: block;
		width: 100%;
		margin-bottom: 10px;
	}
	.icon-img {
		margin-right: 5px;
	}
	.icon-img img{
		display: inline-block;
		width: 13px;
		height: 13px;
	}
	.operationBox {
		font-size: 12px;
	}
	/*分页样式*/
	.page-pagination {
		float: right;
		margin-right: 27px;
		margin-top: 10px;
		margin-bottom: 10px;
	}
	.page-pagination li {
		width: 20px;
		height: 20px;
		background-color: #FFF;
		color: #7c7c7c;
		float: left;
		text-align: center;
		margin-left: 3px;
		margin-right: 3px;
	}
	.page-pagination li a{
		display: block;
		width: 100%;
		height: 100%;
		line-height: 18px;
		border: 1px solid #dadada;
		color: #343434;
	}
	.page-pagination .active a{
		color: #FFF !important;
		background-color: #86a4ee !important;
		border-color: #7088c4 !important;
	}
	td img {
		display: block;
		width: 100px;
		height: 70px;
		margin: 0 auto;
	}


	.nosee { display: none; }
	/*菜单*/
	.menu-icon {display: inline-block; width: 20px; height: auto; }
	.menu-icon:hover {cursor: pointer; box-shadow: 0px 0px 6px #bebebe; }
	.menu-name-box {    text-align: left; padding-left: 30px;}

</style>
<div class='main-container'>

<div class="smart-widget">
	<div class="smart-widget-header">
		<?php echo $page_title;?>
		<span class="smart-widget-option">
			<a href="/admin/company/department/create">
				<i class="fa fa-plus"></i>
			</a>
			<a href="#" onclick="location.reload()" class="widget-refresh-option">
				<i class="fa fa-refresh"></i>
			</a>
		</span>
	</div>
	<table class="">
		<thead>
			<tr class='firstLine'>
				<th class="menu-name-box" width="40%">名称</th>
				<th class="" width="40%">修改时间</th>
				<th class=""  width="20%">操作</th>
			</tr>
		</thead>
		<tbody>

			<?php for($i=0; $i<count($data); $i++){ ?>
			<tr class="<?php echo $i%2==0? 'singular':'dual' ?> <?php if($data[$i]['level']!=1) { echo " nosee"; } ?>"  data-level="<?php echo $data[$i]['level']; ?>" >
				<td class="menu-name-box" style="text-indent: <?php echo empty($data[$i]['level']) || $data[$i]['level']==1? 0:($data[$i]['level']-1)*3; ?>em;">
					<img class='menu-icon plus-icon' src="/images/plus.png">
					<?php echo $data[$i]['level']==1? $data[$i]['name']:'|-'.$data[$i]['name'];?>
				</td>
				<td class=""> <?php echo date("Y-m-d H:i:s", $data[$i]['update_time']);?> </td>
				<td class='operationBox'>
					<?php if($data[$i]['level']==1){ ?>
					<a href="/admin/company/department/update?id=<?php echo $data[$i]['id']; ?>" ><span class="icon-img"><img src="/images/edit-icon.png" /></span>编辑</a>
					<?php } ?>
					<a href="javascript:void(0);" onclick="deleteMenu(this, <?php echo $data[$i]['id']; ?>)" ><span class="icon-img"><img src="/images/trash-icon.png" /></span>删除</a>
				</td>
			</tr>
			<?php } ?>

		</tbody>

	</table>
</div><!-- ./smart-widget -->


</div>
<?php include_once('../app/views/admin/_footer.php') ?>
<script type='text/javascript' src='/js/myFuncs.js'></script>
<script type="text/javascript" src="/js/tools/layer/layer.js"></script>
<script type="text/javascript">
function showDeleteItems(data)
{
	if(data.code!='001'){
		alert("删除失败");
	} else {
		//alert("删除成功");
		window.location.reload();
	}
}

// 拓展一个菜单 (对象为可点击的img)
function plusMenu(obj) {
	
	var parentObj = $(obj).parent().parent();
	var level = parentObj.attr("data-level");
	var objLevel = 0;
	var nextLevel = parseInt(level)+1;
	var ok = true;
	
	while(ok) {
		
		parentObj = parentObj.next();
		if(parentObj.length==0) {
			ok = false;
			break;
		} else{
			objLevel = parentObj.attr("data-level");
			if(objLevel==nextLevel) {
				// 显示
				parentObj.removeClass("nosee");
			} else if(objLevel==1){
				ok = false;
				break;
			}
		}

	}

}

// 收缩一个菜单
function minusMenu(obj)
{

	var parentObj = $(obj).parent().parent();
	var level = parentObj.attr("data-level");
	// var objLevel = 0;
	// var nextLevel = parseInt(level)+1;
	var ok = true;
	
	while(ok) {
		
		parentObj = parentObj.next();
		if(parentObj.length==0) {
			ok = false;
			break;
		} else{
			objLevel = parentObj.attr("data-level");
			if(objLevel>level) {

				// 改图片
				parentObj.find('.menu-icon').attr("src", "/images/plus.png");

				// 添加名称
				parentObj.find('.menu-icon').addClass("plus-icon");

				// 隐藏
				parentObj.addClass("nosee");

			} else if(objLevel==1){
				ok = false;
				break;
			}
		}

	}
}

// 消息提醒
function autoMessageNotice(content)
{
	var time =  arguments[1] ? arguments[1] : 2000;//设置参数b的默认值为2 
	layer.open({
		id: 1,
	    content: content,
	    skin: 'msg',
	    time: time //2m秒后自动关闭
	});
}

/*菜单删除*/
function deleteMenu(obj, menuId)
{
	
	layer.confirm('确定要删除该部门嘛? 这会删除相关所有菜单', {
	  	btn: ['确定','取消'],//按钮
	  	title: "提示",
	  	icon: 3,
	}, function(index){
		
		$.ajax({
			url: "/front/api/v1/department/delete",
			type: "POST",
			dataType: "JSON",
			async: "false",
			data: {
				id: menuId
			},
			success: function(response) {

				autoMessageNotice(response['status']['message']);

				if(response['status']['success']) {

					deleteArr = new Array();

					// 对应节点删除
					parentObj = $(obj).parent().parent();

					deleteArr.push(parentObj);

					// 下一个对象
					var nextObj = parentObj;
					var objLevel = parentObj.attr("data-level");

					// 循环节点删除
					var ok = true;
					while(ok) {

						nextObj = nextObj.next();

						if(nextObj.length==0) {
							ok = false;
							break;
						}

						var curLevel = nextObj.attr("data-level");

						if(objLevel<curLevel) {
							deleteArr.push(nextObj);
						} else if(curLevel==1) {
							ok = false;
							break;
						}

					}

					for(var i = deleteArr.length-1; i>=0; i--) {
						deleteArr[i].remove();
					}


				}

			}
		});

	  	layer.close(index);

	});

}

/*首页js*/
$(document).ready(function(){

	/*点击图标*/
	$('.menu-icon').click(function(){
		var className = $(this).attr("class");

		if(className.indexOf("plus-icon")>=0) {
			// 要进行拓展
			plusMenu(this);

			// 改图片
			$(this).attr("src", "/images/minus.png");

			// 移除名称
			$(this).removeClass("plus-icon");

		} else {

			// 要进行收缩
			minusMenu(this);

			// 改图片
			$(this).attr("src", "/images/plus.png");

			// 添加名称
			$(this).addClass("plus-icon");

		}

	});

});
</script>