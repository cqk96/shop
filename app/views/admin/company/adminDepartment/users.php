<?php include_once('../app/views/admin/_header.php') ?>
<link rel="stylesheet" type="text/css" href="/js/tools/layer/skin/default/layer.css">
<style type="text/css">
	
	th { text-align: center; }
	td { text-align: center; }
	.icon-img { display: inline-block; width: 12px; }
	.icon-img:hover { cursor: pointer; box-shadow: 0px 0px 4px #bebebe; }

</style>
<div class='main-container'>

	<div class="smart-widget">
		<div class="smart-widget-header">
			<label class="control-label">部门：</label><?php echo $page_title;?>
			<span class="smart-widget-option">
				<div class="btn btn-warning btn-sm" id="createDepartmentUser">添加用户</div>
			</span>
		</div>

		<div class="smart-widget-inner">

			<input type="hidden" id="departmentId" value="<?php echo $data['id']; ?>" />

			<table class="table table-bordered" id="userTable">
					<thead>
						<tr>
							<th>名称</th>
							<th>修改时间</th>
							<th>是否领导</th>
							<th>操作</th>
						</tr>
					</thead>
					<tbody>
						<?php for ($i=0; $i < count($users); $i++) { ?>
							<tr>
								<td> <?php echo $users[$i]['name'] ?> </td>
								<td> <?php echo date("Y-m-d H:i:s", $users[$i]['update_time']); ?> </td>
								<td> <a class='btn btn-link' href="javascript:void(0);" data-leader="<?php echo $users[$i]['is_leader']; ?> " onclick="changeLeader(this, <?php echo $users[$i]['id']; ?>)" ><?php echo empty($users[$i]['is_leader'])? "否":"是"; ?> </a></td>
								<td>
									<a href="javascript:void(0);" onclick="deleteUser(this, <?php echo $users[$i]['id']; ?>)" ><span class="icon-img"><img src="/images/trash-icon.png" /></span>删除</a>
								</td>
							</tr>
						<?php } ?>
					</tbody>
					<tfoot>
						<tr>
							<td colspan=4>
								<div class="btn btn-default btn-sm" onclick="history.back();">返回</div>
							</td>
						</tr>
					</tfoot>
				</table>

		</div><!-- ./smart-widget-inner -->

	</div><!-- ./smart-widget -->

</div>
<?php include_once('../app/views/admin/_footer.php') ?>
<script type='text/javascript' src='/js/myFuncs.js'></script>
<script type="text/javascript" src="/js/tools/layer/layer.js"></script>
<script type="text/javascript">

// 消息提醒
function autoMessageNotice(content)
{
	var time =  arguments[1] ? arguments[1] : 2000;//设置参数b的默认值为2 
	
	var contentStr = "";
	contentStr += "<div class='msg-notice-box'>";
	contentStr += content;
	contentStr += "</div>";

	layer.open({
		id: 1,
		type: 1,
	    content: contentStr,
	    area: "300px",
	    skin: 'msg',
	    time: time //2m秒后自动关闭
	});

}

// 用户搜索
function getSearchList(name)
{
	
	$.ajax({
		url: "/api/v1/department/user/notInSearch?search="+name,
		type: "GET",
		dataType: "json",
		async: false,
		cache: false,
		success: function(response){
			
			$("#userSelect").remove();
			if(response['status']['success']) {

				if(response['data']!=null) {
					
					var str = "<select id='userSelect' class='form-control'>";
					for(var i=0; i<response['data'].length; i++) {
						str += "<option value='"+response['data'][i]['id']+"'>"+response['data'][i]['name']+"</option>";
					}
					str += "</select>";

					$("#searchContentBox").append(str);

				}

			}
		},
		error: function(res) {
			// console.log(res.responseText);
		}
	});
}


/*删除用户*/
function deleteUser(obj, id)
{
	
	layer.confirm('确定要删除该员工吗?', {
	  	btn: ['确定','取消'],//按钮
	  	title: "提示",
	  	icon: 3,
	}, function(index){
		
		$.ajax({
			url: "/front/api/v1/department/user/delete",
			type: "POST",
			dataType: "JSON",
			asnyc: false,
			data: {
				id: id
			},
			success: function(response){
				if(response['status']['success']) {
					// 删除节点
					$(obj).parent().parent().remove();
				}
				autoMessageNotice(response['status']['message']);
			},
			error: function(res) {
				// console.log(res.responseText);		
			}
		});

	});

}

/*是否改变领导*/
function changeLeader(obj, id)
{
	
	layer.confirm('确定要更换领导吗?', {
	  	btn: ['确定','取消'],//按钮
	  	title: "提示",
	  	icon: 3,
	}, function(index){
		
		$.ajax({
			url: "/front/api/v1/department/user/leader/update",
			type: "POST",
			dataType: "json",
			data: {
				id: id
			},
			async: false,
			success: function(response){
				if(response['status']['success']) {
					setTimeout(function(){
						window.location.reload();
					}, 1400);
				}
				autoMessageNotice(response['status']['message']);

			},
			error: function(res) {
				// console.log(res.responseText);		
			}
		});

		layer.close(index);

	});

}

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

/*init*/
$(document).ready(function(){

	_userAgent = navigator.userAgent;

	var userSearchStr = "";
	userSearchStr += "<div id='searchContentBox'>";
	userSearchStr += "<input class='form-control' type='text' id='searchUser' value='' placeholder='请输入用户账号进行搜索' />";
	userSearchStr += "</div>";

	/*添加用户*/
	$('#createDepartmentUser').click(function(){

		/*获取所有用户*/ 
		layer.open({
			title:'添加部门用户',
			content: userSearchStr,
			btn: ['确定', '取消'],
			success: function(layero, index){

				if(_userAgent.indexOf("MSIE")>0) {
					document.getElementById("searchUser").attachEvent("onkeydown", function(event) {
						getSearchList(document.getElementById("searchUser").value);
					});

				} else {
					document.getElementById("searchUser").addEventListener("input", function(event){
						getSearchList(event.target.value);
					});
					
				}

				if(_userAgent.indexOf("MSIE")>0 && parseInt(_userAgent.match(/.*?MSIE\s(.*?);/)[1])<=9){
					showPlaceholder();
				}
				
			},
			btn1: function(index, layero){

				// 判断节点是否存在
				if(document.getElementById("userSelect") && document.getElementById("departmentId") ){
					var userId = $("#userSelect").val();
					var departmentId = $("#departmentId").val();
					if( departmentId && userId ) {
						/*请求添加部门用户关联*/
						// todo
						$.ajax({
							url: "/front/api/v1/department/user/create",
							dataType: "JSON",
							type: "POST",
							data: {
								id: departmentId,
								userId: userId
							},
							async: false,
							success: function(response){

								if(response['status']['success']) {
									var formatTemplateStr = "";
									formatTemplateStr += 	'<tr>';
									formatTemplateStr +=		'<td>'+response['data']['name']+'</td>';
									formatTemplateStr +=		'<td>'+response['data']['updateTime']+'</td>';
									formatTemplateStr +=		'<td> <a class="btn btn-link" href="javascript:void(0);" data-leader="0" onclick="changeLeader(this, '+response['data']['id']+')" >否</a></td>';
									formatTemplateStr +=		'<td><a href="javascript:void(0);" onclick="deleteUser(this, '+response['data']['id']+')" ><span class="icon-img"><img src="/images/trash-icon.png" /></span>删除</a>';
									formatTemplateStr +=	'</td>';
									formatTemplateStr += '</tr>';


								}

								$("#userTable tbody").append(formatTemplateStr);

								autoMessageNotice(response['status']['message']);

							},
							error: function(res){
								// console.log(res.responseText);
							}
						});
					}

					layer.close(index);

				}

			},
	 	});

	});
	
});

</script>