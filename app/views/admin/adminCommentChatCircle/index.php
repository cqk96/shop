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
 <div class='main-container'>
 <div class='smart-widget'>
	 <div class='smart-widget-header'>
		 <?php echo $page_title;?>
		<span class='smart-widget-option'>
			 <!-- <a href='/admin/commentChatCircle/create'>
				 <i class='fa fa-plus'></i>
			 </a> -->
			 <a href='#' onclick='location.reload()' class='widget-refresh-option'>
				 <i class='fa fa-refresh'></i>
			 </a>
		 </span>
		<form id="searchForm" class='searchForm' action='commentChatCircle' method='get'>
			<div class='search-box'>
				<label class="label-control search-label"> 朋友圈内容: </label>
				<div class="search-item-box">
					<input class="form-control" type='text' name='title' value='<?php echo empty($_GET['title'])? '':$_GET['title']; ?>' placeholder='请输入标题' /> 
				</div>
			</div>

			<div class='search-box'>
				<label class="label-control search-label"> 评论内容: </label>
				<div class="search-item-box">
					<input class="form-control" type='text' name='commentTitle' value='<?php echo empty($_GET['commentTitle'])? '':$_GET['commentTitle']; ?>' placeholder='请输入评论内容' /> 
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
				<th>说说标题</th>
				<th>评论者</th>
				<th>评论内容</th>
				
				<th>创建时间</th>
				<th>修改时间</th>
				
				 <!-- <th class=''>用户类型</th> -->
				 <th class='' colspan=2>操作</th>
			 </tr>
		 </thead>
		 <tbody>
			 <?php for($i=0; $i<count($data); $i++){ ?>
			 <tr class='<?php echo $i%2==0? 'singular':'dual' ?>'>
				<td class=''>
					<input type='checkbox' name='classesIds[]' class='eachNewsClassCheckBox' value='<?php echo $data[$i]['id'] ?>' /></td>
				<td><?php echo $data[$i]['title'];?></td>
				<td><?php echo $data[$i]['chat_id'];?></td>
				<td><?php echo $data[$i]['name'];?></td>
				<td><?php echo $data[$i]['content'];?></td>
				
				<td><?php echo date('Y-m-d H:i:s',$data[$i]['created_time']);?></td>
				<td><?php echo date('Y-m-d H:i:s',$data[$i]['updated_time']);?></td>
				
				 <td class='operationBox' colspan=2>
					 <a type='button' href='/admin/commentChatCircle/delete?id=<?php echo $data[$i]['id']; ?>' onclick='return delNotice(this)'><span class='icon-img'><img src='/images/delete-icon.png'></span>删除</a>
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
 <?php include_once('../app/views/admin/_footer.php') ?>
 <script type='text/javascript' src='/js/myFuncs.js'></script> 
 <script type='text/javascript' src='/js/date-picker/jquery-ui.min.js'></script> 
 <script type='text/javascript'>

  /*显示placeholder*/
function showPlaceholder()
{

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

		/*textarea*/
		$("textarea").each(function(){
			var curPlaceHolder = $(this).attr("placeholder");
			var curValue = $.trim($(this).val());
			if( curValue=="" ) {
				$(this).val(curPlaceHolder);
			}
			
		});

		$("textarea").focus(function(){
			
			var curPlaceHolder = $.trim($(this).attr("placeholder"));
			var curValue = $.trim($(this).val());

			if( curPlaceHolder==curValue ) {
				$(this).val("");
			}

		});

		$("textarea").blur(function(){
			
			var curPlaceHolder = $.trim($(this).attr("placeholder"));
			var curValue = $.trim($(this).val());

			if( curValue=="" ) {
				$(this).val(curPlaceHolder);
			}

		}); 

	}

}

 function showDeleteItems(data)
 {
	 if(!data.success){alert('删除失败'); }
	else {window.location.reload(); }
	 }
 $(document).ready(function(){

 	_userAgent = navigator.userAgent;

 	$("#searchForm").submit(function(){

		$("#searchForm input").each(function(){
			
			var curPlaceHolder = $.trim($(this).attr("placeholder"));
			var curValue = $.trim($(this).val());

			if( curValue==curPlaceHolder ) {
				$(this).val("");
			}

		});

	});

	if(_userAgent.indexOf("MSIE")>0 && parseInt(_userAgent.match(/.*?MSIE\s(.*?);/)[1])<=9){
		showPlaceholder();
	}

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
		 deleteChooseItems('eachNewsClassCheckBox','你确定要删除选定项吗？','/admin/commentChatCircles/doDelete','','showDeleteItems','');
	 });
	//日期选择
	$( '#startTime' ).datepicker({dateFormat:'yy-mm-dd'});
	$( '#endTime' ).datepicker({dateFormat:'yy-mm-dd'});

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

