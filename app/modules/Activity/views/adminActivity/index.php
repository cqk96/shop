<!DOCTYPE>
<html>
<head>
	<?php include_once('../app/views/admin/_header.php') ?>
	<link rel='stylesheet' type='text/css' href='/css/admin/index.css?1'>
	<link rel="stylesheet" type="text/css" href="/js/tools/layer/skin/default/layer.css">
	<style type="text/css">
	ul,li{
		padding: 0px;
		margin: 0px;
	}
	a.allCheckBox {
		float: inherit;
		margin-left: 15px;
	}
	.searchForm .search-box {
		display: inline-block;
		width: 23%;
	}
	.searchForm .search-box .form-control {
		display: inline-block;
	}
	.searchForm .search-box .search-label{
		margin-right: 20px;
		vertical-align:  middle;
	}
	.searchForm .search-box .search-button{
		margin-right: 20px;
	}
	.searchForm .search-box .search-item-box{ 
		display: inline-block;
	}

	.operate-box {
		padding-top: 10px;
		vertical-align: middle;
	}
	.show-btn:hover{
		cursor: pointer;
	}
	.qrcode-img-box img{
		display: block;
		margin: 0 auto;
	}

	.search-button-box .btn-sm{
		line-height: inherit;
		vertical-align: inherit;
	}
	.bottom-bar-box{
		margin-top: 10px;
		margin-bottom: 10px;
	}
	.bottom-bar-box .operate-btn{
		margin-right: 27px;
	}
	.bottom-bar-box .first-operate-btn{
		margin-left: 15px;	
	}
	#goForm  .btn-xs{
		line-height: inherit;
		vertical-align: inherit;
	}
	.page-pagination {
		float: right;
		margin-bottom: inherit;
	}

	</style>
</head>
<body>
 <div class='main-container'>
 <div class='smart-widget'>
	 <div class='smart-widget-header'>
		 <?php echo $page_title;?>
		<span class='smart-widget-option'>
			 <a href='/admin/activitys/create'>
				 <i class='fa fa-plus'></i>
			 </a>
			 <a href='#' onclick='location.reload()' class='widget-refresh-option'>
				 <i class='fa fa-refresh'></i>
			 </a>
		 </span>
		<form class='searchForm' id="searchForm" action='activitys' method='get'>
			<div class='search-box'>
				<label class="label-control search-label"> 活动名称: </label>
				<div class="search-item-box">
					<input class='form-control' type='text' name='title' value='<?php echo empty($_GET['title'])? '':$_GET['title']; ?>' placeholder='请输入活动名称' />
				</div>
			</div>

			<div class='search-box'>
				<label class="label-control search-label"> 记录起始时间: </label>
				<div class="search-item-box">
					<input class='form-control' type='text' name='startTime' id='startTime' value='<?php echo empty($_GET['startTime'])? '':$_GET['startTime'] ?>' placeholder='请输入起始时间' />
				</div>
			</div>

			<div class='search-box'>
				<label class="label-control search-label"> 结束时间: </label>
				<div class="search-item-box">
					<input class='form-control' type='text' name='endTime' id='endTime' value='<?php echo empty($_GET['endTime'])? '':$_GET['endTime'] ?>' placeholder='请输入结束时间' />
				</div>
			</div>

			<div class='search-box search-button-box'>
				<span class="search-button">
					<button id="searchBtn" class='btn btn-primary btn-sm' type='submit'>提交</button>
				</span>

				<div class="search-item-box">
					<div class="search-result-box"> 共查询到 <?php echo $pageObj->totalCount; ?> 条记录 </div>
				</div>

			</div>

		</form>
		
	 </div>
	 <table id='indexTable' class=''>
		 <thead>
			 <tr class='firstLine'>
				<th class=''><input type='checkbox' name='chooseAll' class='allCheckBox chooseAll'></th>
				<th>ID</th>
				<th>活动名称</th>
				<th>封面</th>
				<th>状态</th>
				<th>更新时间</th>
				<th class='' colspan=2>操作</th>
			 </tr>
		 </thead>
		 <tbody>
			 <?php for($i=0; $i<count($data); $i++){ ?>
			 <tr class='<?php echo $i%2==0? 'singular':'dual' ?>'>
				<td  width="5%" class=''>
					<input type='checkbox' name='classesIds[]' class='eachNewsClassCheckBox' value='<?php echo $data[$i]['id'] ?>' /></td>
				<td width="10%"><?php echo ($start+$i);?></td>
				<td width="20%"><?php echo $data[$i]['title'];?></td>
				<td width="15%"><img class="coverImg" src="<?php echo empty($data[$i]['cover'])? '/images/default-pic.jpg':$data[$i]['cover'];?>"></td>
				<td width="5%"><?php echo $hidden_status[$data[$i]['is_hidden']];?></td>
				<td width="15%"><?php echo date("Y-m-d H:i:s", $data[$i]['update_time']);?></td>
				
				 <td  width="20%" class='operationBox'>
					 <a href='/admin/activitys/update?id=<?php echo $data[$i]['id']; ?>' ><span class='icon-img'><img src='/images/edit-icon.png' /></span>编辑</a>
					 <a type='button' href='/admin/activitys/doDelete?id=<?php echo $data[$i]['id']; ?>' onclick='return delNotice(this)'><span class='icon-img'><img src='/images/delete-icon.png'></span>删除</a>
					 <a href='/admin/activitys/application?id=<?php echo $data[$i]['id']; ?>' ><span class='icon-img'><img src='/images/edit-icon.png' /></span>报名情况查询</a>
					 <!-- <a href='/admin/activitys/readQrcode?id=' > -->
					 	<div class="show-btn" onclick="showSignInImg(this, <?php echo $data[$i]['id']; ?>)">	
					 		<span class='icon-img'><img src='/images/eye-icon.png' /></span>查看活动二维码
					 	</div>
					 <!-- </a> -->
				 </td>
			 </tr>
			 <?php } ?>
		 </tbody>
		 
	 </table>

	 <div class="bottom-bar-box">
		
		<a href='javascript:void(0);' style='' class='allCheckBox btn btn-default btn-xs operate-btn first-operate-btn'>全选</a>
		<a href='javascript:void(0);' class='deleteChooseBtn btn btn-danger btn-xs operate-btn'>删除</a>
		
		<?php echo $pageObj->pagination; ?>
		
	</div>

 </div><!-- ./smart-widget -->
 </div>
 
 <iframe id="idIframe" name="id_iframe" style="display: none;"></iframe>
</body>
 <?php include_once('../app/views/admin/_footer.php') ?>
</html>
 <script type='text/javascript' src='/js/myFuncs.js'></script> 
<script type='text/javascript' src='/js/date-picker/jquery-ui.min.js'></script> 
<script type="text/javascript" src="/js/tools/layer/layer.js"></script>
 <script type='text/javascript'>
 function showDeleteItems(data)
 {
	 if(!data.success){alert('删除失败'); }
	else {window.location.reload(); }
	 }

// 显示签到图片
function showSignInImg(obj, id)
{
	
	layer.open({
	  title: '活动签到二维码'
	  ,content: '<div class="qrcode-img-box"><img src="/admin/activitys/readQrcode?id=' + id + '" /></div>'
	}); 

}

$(document).ready(function(){

 	_userAgent = navigator.userAgent;
	if(_userAgent.indexOf("MSIE")>0 && parseInt(_userAgent.match(/.*?MSIE\s(.*?);/)[1])<=9){

		$("input[type='text']").each(function(){
			var curPlaceHolder = $(this).attr("placeholder");
			var curValue = $.trim($(this).val());
			if( curValue=="" ) {
				$(this).val(curPlaceHolder);
			}
			
		});

		$("input[type='text']").focus(function(){
			
			var curPlaceHolder = $.trim($(this).attr("placeholder"));
			var curValue = $.trim($(this).val());

			if( curPlaceHolder==curValue ) {
				$(this).val("");
			}

		});

		$("input[type='text']").blur(function(){
			
			var curPlaceHolder = $.trim($(this).attr("placeholder"));
			var curValue = $.trim($(this).val());

			if( curValue=="" ) {
				$(this).val(curPlaceHolder);
			}

		});

	}

	if(_userAgent.indexOf("MSIE")>0) {

		window.document.body.attachEvent("onkeydown", function(event) {
			if( event.keyCode!=13 ) {
				// return false;
			} else {
				$("#searchBtn").click();
			}

		});
	} else {
		window.document.body.addEventListener("keydown", function(event) {
			if( event.which!=13 ) {
				return false;
			} else {
				$("#searchBtn").click();
			}

		});
	}

	$("#searchForm").submit(function(){

		$("#searchForm input").each(function(){
			
			var curPlaceHolder = $.trim($(this).attr("placeholder"));
			var curValue = $.trim($(this).val());

			if( curValue==curPlaceHolder ) {
				$(this).val("");
			}

		});

	});

	 //全选checkbox
	 var allCheckBoxBtnClick = 1;
	 $('.allCheckBox').click(function(){
		 if(allCheckBoxBtnClick%2!=0){
			 $('.eachNewsClassCheckBox').each(function(){
				 $(this).prop('checked',true);
			 });
		 } else{
			 $('.eachNewsClassCheckBox').each(function(){
				 $(this).prop('checked',false);
			 });
		 }
		 allCheckBoxBtnClick++;
	 });
	 //删除选择的项目
	 $('.deleteChooseBtn').click(function(){
		 deleteChooseItems('eachNewsClassCheckBox','你确定要删除选定项吗？','/admin/activitys/destroy','','showDeleteItems','');
	 });
	//日期选择
	$( '#startTime' ).datepicker({dateFormat:'yy-mm-dd'});
	$( '#endTime' ).datepicker({dateFormat:'yy-mm-dd'});
 });
 </script>
 
 <!-- 删除提醒  -->
 <script >
	function delNotice(obj){
		
		var delurl = $(obj).attr("href");
		
		 layer.confirm('确认要删除吗？删除后不能恢复！', {
			  btn: ['确认','取消'] //按钮 
			}, function(){
				layer.msg('删除成功', {icon: 1});
				$("#idIframe").attr("src", delurl);
				window.location.reload();
				
			},function(index){ // 取消 
				layer.close(index);
				return false;
				 
			}); 

		return false;
	}	
</script>