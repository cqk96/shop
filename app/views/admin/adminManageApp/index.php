<?php include_once('../app/views/admin/_header.php') ?>
 <link rel='stylesheet' type='text/css' href='/css/admin/index.css'>
 <style type="text/css">
.searchForm .search-box {
	display: inline-block;
	width: 23%;
}
.searchForm .search-box .form-control {
	display: inline-block;
}
.searchForm .search-box .search-label{
	margin-right: 20px;
	/*vertical-align:  middle;*/
}
.searchForm .search-box .search-button{
	margin-right: 20px;
}
.searchForm .search-box .search-item-box{ 
	display: inline-block;
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
 <div class='main-container'>
 <div class='smart-widget'>
	 <div class='smart-widget-header'>
		 <?php echo $page_title;?>
		<span class='smart-widget-option'>
			 <a href='/admin/manageApps/create'>
				 <i class='fa fa-plus'></i>
			 </a>
			 <a href='#' onclick='location.reload()' class='widget-refresh-option'>
				 <i class='fa fa-refresh'></i>
			 </a>
		 </span>
		<form id="searchForm" class='searchForm' action='manageApps' method='get'>

			<div class='search-box'>
				<label class="label-control search-label"> 开发版本号: </label>
				<div class="search-item-box">
					<!-- 搜索版本号 -->
					<input class="form-control" type='text' name='version_code' value='<?php echo empty($_GET['version_code'])? '':$_GET['version_code']; ?>' placeholder='请输入开发版本号' />
				</div>
			</div>

			<div class='search-box'>
				<label class="label-control search-label"> 记录起始时间: </label>
				<div class="search-item-box">
					<input class="form-control" type='text' name='startTime' id='startTime' value='<?php echo empty($_GET['startTime'])? '':$_GET['startTime'] ?>' placeholder='请输入起始时间' />
				</div>
			</div>

			<div class='search-box'>
				<label class="label-control search-label"> 结束时间: </label>
				<div class="search-item-box">
					<input class="form-control" type='text' name='endTime' id='endTime' value='<?php echo empty($_GET['endTime'])? '':$_GET['endTime'] ?>' placeholder='请输入结束时间' />
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
				<th>应用名称</th>
				<th>开发版本号</th>
				<th>用户版本号</th>
				<th>下载</th>
				<th>创建时间</th>
				<th>更新时间</th>
				<th class='' colspan=2>操作</th>
			 </tr>
		 </thead>
		 <tbody>
			 <?php for($i=0; $i<count($data); $i++){ ?>
			 <tr class='<?php echo $i%2==0? 'singular':'dual' ?>'>
				<td class=''>
					<input type='checkbox' name='classesIds[]' class='eachNewsClassCheckBox' value='<?php echo $data[$i]['id'] ?>' /></td>
				<td><?php echo $data[$i]['id'];?></td>
				<td><?php echo $data[$i]['name'];?></td>
				<td><?php echo $data[$i]['version_code'];?></td>
				<td><?php echo $data[$i]['version_text'];?></td>
				<td>
					
					<?php if( !empty($data[$i]['apk_url']) ) { ?>
					<a href="/admin/manageApps/download?id=<?php echo $data[$i]['id']; ?>" class="btn btn-link">下载</a>
					<?php } else { ?>
					&nbsp;
					<?php } ?>

				</td>
				<td><?php echo date("Y-m-d H:m:s", $data[$i]['create_time']);?></td>
				<td><?php echo date("Y-m-d H:m:s", $data[$i]['update_time']);?></td>
				
				 <td class='operationBox' colspan=2>
					 <a href='/admin/manageApps/update?id=<?php echo $data[$i]['id']; ?>' ><span class='icon-img'><img src='/images/edit-icon.png' /></span>编辑</a>
					 <a type='button' href='/admin/manageApps/doDelete?id=<?php echo $data[$i]['id']; ?>' onclick='return confirm("你确定要删除吗？")'><span class='icon-img'><img src='/images/delete-icon.png'></span>删除</a>
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
 <?php include_once('../app/views/admin/_footer.php') ?>
 <script type='text/javascript' src='/js/myFuncs.js'></script> 
 <script type='text/javascript' src='/js/date-picker/jquery-ui.min.js'></script> 
 <script type='text/javascript'>
 function showDeleteItems(data)
 {
	 if(!data.success){alert('删除失败'); }
	else {window.location.reload(); }
	 }
 $(document).ready(function(){
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
		 deleteChooseItems('eachNewsClassCheckBox','你确定要删除选定项吗？','/admin/manageApps/destroy','','showDeleteItems','');
	 });
	//日期选择
	$( '#startTime' ).datepicker({dateFormat:'yy-mm-dd'});
	$( '#endTime' ).datepicker({dateFormat:'yy-mm-dd'});

	// 判断是否 ie ie10以下处理placeholder
	var _userAgent = navigator.userAgent;
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

	$("#searchForm").submit(function(){
		
		$("#searchForm input").each(function(){
			
			var curPlaceHolder = $.trim($(this).attr("placeholder"));
			var curValue = $.trim($(this).val());

			if( curValue==curPlaceHolder ) {
				$(this).val("");
			}

		});

	});

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

 });
 </script>