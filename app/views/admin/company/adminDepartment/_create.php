<?php include_once('../app/views/admin/_header.php') ?>
<link rel="stylesheet" type="text/css" href="/js/tools/layer/skin/default/layer.css">
<style type="text/css">
	.icon-img { width: 20px; height: auto; display: inline-block; margin-right: 10px; }
	.icon-img:hover { cursor: pointer; box-shadow: 0px 0px 5px #bebebe; }
	.msg-notice-box {
		padding: 20px;
	}
	.icon-img-box {
		cursor: pointer;
	}
</style>
<div class='main-container'>

<div class="smart-widget">
	<div class="smart-widget-header">
		<?php echo $page_title;?>

		<span class="smart-widget-option">
			<div class="btn btn-warning btn-sm create-department">添加顶级部门</div>
		</span>

	</div>

	<div class="smart-widget-inner">
		
		<table class="table table-bordered" id="menuTable">
				<thead>
					<tr>
						<th>名称</th>
						<th>修改时间</th>
						<th>操作</th>
					</tr>
				</thead>
				<tbody>
					
				</tbody>
				<tfoot>
					<tr>
						<td colspan=3>
							<a class="btn btn-default btn-sm" href="/admin/company/departments">返回</a>						
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

// 真实修改文本
function changeMenuText(obj)
{
	
	var menuId = $(obj).attr("data-menu-id");

	var curText = $.trim( $(obj).val() );
	var curTextPlaceholder = $.trim( $(obj).attr("placeholder") );

	var curLevel = $(obj).attr("data-input-level");

	if(curText=="" || curText==curTextPlaceholder) {
		autoMessageNotice("名称不为空");
		return false;
	}
	
	$.ajax({
		url: "/front/api/v1/department/update",
		type: "POST",
		dataType: "JSON",
		async: false,
		data: {
			id: menuId,
			name: curText
		},
		success: function(response) {
			if(response['status']['success']) {
				// 更新成功
				var tdObj = $(obj).parent();

				if(curLevel==1) {
					curText = curText;
				} else {
					curText = "|-"+curText;
				}

				tdObj.html(curText);

			}

			autoMessageNotice(response['status']['message']);

		},
		error: function(res) {
			// console.log(res.responseText);
		}
	});

}

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

/*修改文本*/
function changeText(obj, menuId)
{
	
	var tdObj = $(obj).parent().parent().find("td:first");

	// 查询是否有input
	if(tdObj.find("input").length==1) {
		
		// 有input 不做修改 还原
		var inputValue = tdObj.find("input").val();
		var inputLevel = tdObj.find("input").attr("data-input-level");
		if(inputLevel==1){
			tdObj.html(inputValue);
		} else {
			tdObj.html("|-"+inputValue);
		}

		return false;
	}

	var curLevel = $(obj).parent().parent().attr("data-level");

	// 出现输入框
	var curText = $.trim( tdObj.text() );

	// 替换特殊符号
	curText = curText.replace("|-", "");

	var str = "<input type='text' class='form-control' name='' value='"+curText+"' data-menu-id='"+menuId+"' onblur='changeMenuText(this)' data-input-level='"+curLevel+"' />";

	tdObj.html(str);

}

/*创建部门*/
function createDepartment(obj, pid)
{
	
	var formatTemplate = "";
	formatTemplate += 	'<tr class=""  data-level="{{dataLevel}}" >';
	formatTemplate += 		'<td class="menu-name-box" style="text-indent: {{textIndent}}em;">';
	formatTemplate += 			'{{name}}';
	formatTemplate += 		'</td>';
	formatTemplate += 		'<td class="">{{updateTime}}</td>';
	formatTemplate += 		'<td class="operationBox">';
	formatTemplate += 			'<div onclick="changeText(this, {{id}})" class="icon-img-box"><span class="icon-img"><img src="/images/edit.png" /></span>修改</div>';
	formatTemplate +=			'<a href="/admin/company/department/users?id={{id}}"><span class="icon-img"><img src="/images/eye.png" /></span>查看人员</a>';
	formatTemplate += 			'<div onclick="createDepartment(this, {{id}})" class="icon-img-box" ><span class="icon-img"><img src="/images/plus.png" /></span>增加下级部门</div>';
	formatTemplate +=			'<div onclick="createParentDepartment(this, {{id}})" class="icon-img-box" ><span class="icon-img"><img src="/images/plus.png" /></span>增加上级部门</div>';
	formatTemplate += 		'</td>'
	formatTemplate +=	'</tr>';

	var departmentCreateStr = "";
	departmentCreateStr += "<div>";
	departmentCreateStr += "<input class='form-control' type='text' id='departmentName' value='' placeholder='请输入部门名称' />";
	departmentCreateStr += "</div>";

	layer.open({
		title:'添加部门',
		content: departmentCreateStr,
		btn: ['确定', '取消'],
		success: function(){
			if(_userAgent.indexOf("MSIE")>0 && parseInt(_userAgent.match(/.*?MSIE\s(.*?);/)[1])<=9){
				showPlaceholder();
			}
		},
		btn1: function(index, layero){
			/*请求添加部门*/
			var departmentName = $.trim( $("#departmentName").val() );
			var departmentNamePlaceholder = $.trim( $("#departmentName").attr("placeholder") );

			if(departmentName=="" || departmentName==departmentNamePlaceholder) {
				autoMessageNotice("部门名称不为空");
				return false;
			} else {
				$.ajax({
					url: "/front/api/v1/department/create",
					type: "POST",
					dataType: "JSON",
					async: false,
					data: {
						name: departmentName,
						pid: pid
					},
					success: function(response) {
						var data = response['data'];
						autoMessageNotice(response['status']['message']);
						if(response['status']['success']) {
							
							// 加入到对应的菜单之中
							if($("#menuTable tbody tr").length==0){
								// 一级部门
								var curLevel = 1;

							} else {
								// 对应的级别部门
								var trObj = $(obj).parent().parent();
								var trIndex = trObj.index();
								// console.log(trIndex);
								var pLevel = trObj.attr("data-level");
								pLevel = parseInt(pLevel);
								var curLevel = pLevel+1;
							}

							// 数据填入
							if(curLevel==1) {
								var curName = data['name'];
							} else {
								var curName = "|-"+data['name'];
							}

							formatTemplate = formatTemplate.replace("{{dataLevel}}", curLevel);
							formatTemplate = formatTemplate.replace("{{textIndent}}", (curLevel-1)*3);
							formatTemplate = formatTemplate.replace("{{name}}", curName);
							formatTemplate = formatTemplate.replace("{{updateTime}}", data['updateTime']);
							formatTemplate = formatTemplate.replace(/{{id}}/g, data['id']);

							if($("#menuTable tbody tr").length==0) {
								// 渲染
								$("#menuTable tbody").append(formatTemplate);
							} else {

								parentObj = trObj;
								positionObj = null;
								
								// 遍历
								$("#menuTable tbody tr").each(function(index){
									var eachLevel = $(this).attr("data-level");
									if(index<=trIndex){
										return true;
									}

									if(eachLevel<curLevel) {
										return false;
									}

									if(curLevel<=eachLevel) {
										positionObj = $(this);
									}

								});

								if(positionObj!=null) {

									// 此地插入
									positionObj.after(formatTemplate);

								} else {
									// 直接后插入
									parentObj.after(formatTemplate);
								}

							}

						} 
					},
					error: function(res) {
						// console.log(res.responseText);
					}
				});

				layer.close(index);
				
			}

		},
 	});

}


// 增加上级部门
function createParentDepartment(obj, cid)
{
	var formatTemplate = "";
	formatTemplate += 	'<tr class=""  data-level="{{dataLevel}}" >';
	formatTemplate += 		'<td class="menu-name-box" style="text-indent: {{textIndent}}em;">';
	formatTemplate += 			'{{name}}';
	formatTemplate += 		'</td>';
	formatTemplate += 		'<td class="">{{updateTime}}</td>';
	formatTemplate += 		'<td class="operationBox">';
	formatTemplate += 			'<div onclick="changeText(this, {{id}})"><span class="icon-img"><img src="/images/edit.png" /></span>修改</div>';
	formatTemplate +=			'<a href="/admin/company/department/users?id={{id}}"><span class="icon-img"><img src="/images/eye.png" /></span>查看人员</a>';
	formatTemplate += 			'<div onclick="createDepartment(this, {{id}})"><span class="icon-img"><img src="/images/plus.png" /></span>增加下级部门</div>';
	formatTemplate +=			'<div onclick="createParentDepartment(this, {{id}})"><span class="icon-img"><img src="/images/plus.png" /></span>增加上级部门</div>';
	formatTemplate += 		'</td>'
	formatTemplate +=	'</tr>';

	var departmentCreateStr = "";
	departmentCreateStr += "<div>";
	departmentCreateStr += "<input class='form-control' type='text' id='departmentName' value='' placeholder='请输入部门名称' />";
	departmentCreateStr += "</div>";

	layer.open({
		title:'添加部门',
		content: departmentCreateStr,
		btn: ['确定', '取消'],
		success: function(){
			if(_userAgent.indexOf("MSIE")>0 && parseInt(_userAgent.match(/.*?MSIE\s(.*?);/)[1])<=9){
				showPlaceholder();
			}
		},
		btn1: function(index, layero){
			/*请求添加部门*/
			var departmentName = $.trim( $("#departmentName").val() );
			var departmentNamePlaceholder = $.trim( $("#departmentName").attr("placeholder") );

			if(departmentName=="" || departmentName==departmentNamePlaceholder) {
				autoMessageNotice("部门名称不为空");
				return false;
			} else {
				
				$.ajax({
					url: "/front/api/v1/department/createHigher",
					type: "POST",
					dataType: "JSON",
					async: false,
					data: {
						name: departmentName,
						cid: cid
					},
					success: function(response){

						var data = response['data'];
						autoMessageNotice(response['status']['message']);

						if(response['status']['success']){

							// 加入到对应的菜单之中
							if($("#menuTable tbody tr").length==0){
								// 一级部门
								var curLevel = 1;

							} else {
								// 对应的级别部门
								var trObj = $(obj).parent().parent();
								var trIndex = trObj.index();
								// console.log(trIndex);
								var pLevel = trObj.attr("data-level");
								pLevel = parseInt(pLevel);
								var curLevel = pLevel;
								// if(curLevel<1){
								// 	curLevel = 1;
								// }
							}


							// 数据填入
							if(curLevel==1) {
								var curName = data['name'];
							} else {
								var curName = "|-"+data['name'];
							}

							formatTemplate = formatTemplate.replace("{{dataLevel}}", curLevel);
							formatTemplate = formatTemplate.replace("{{textIndent}}", (curLevel-1)*3);
							formatTemplate = formatTemplate.replace("{{name}}", curName);
							formatTemplate = formatTemplate.replace("{{updateTime}}", data['updateTime']);
							formatTemplate = formatTemplate.replace(/{{id}}/g, data['id']);

							// console.log(formatTemplate);

							if($("#menuTable tbody tr").length==0) {
								// 渲染
								$("#menuTable tbody").append(formatTemplate);
							} else {

								// 遍历 进行更改
								$("#menuTable tbody tr").each(function(index){
									var eachLevel = $(this).attr("data-level");
									if(index<trIndex){
										return true;
									}

									if(index==trIndex) {
										// 紧挨着的进行改变

										var dataLevelChange = $(this).attr("data-level");
										dataLevelChange = parseInt(dataLevelChange)+1;
										$(this).attr("data-level", dataLevelChange);

										var nameBoxObj = $(this).find('.menu-name-box');

										nameBoxObj.css("textIndent", ((dataLevelChange-1)*3)+"em");

										var curName = nameBoxObj.text();
										if(curName.indexOf("|-")<0) {
											curName = "|-"+curName;
											nameBoxObj.text(curName);
										}

									} else {

										if(eachLevel<curLevel) {
											return false;
										}

										if(curLevel<eachLevel) {
											
											var dataLevelChange = $(this).attr("data-level");
											dataLevelChange = parseInt(dataLevelChange)+1;
											$(this).attr("data-level", dataLevelChange);

											var nameBoxObj = $(this).find('.menu-name-box');

											nameBoxObj.css("textIndent", ((dataLevelChange-1)*3)+"em");

											var curName = nameBoxObj.text();
											if(curName.indexOf("|-")<0) {
												curName = "|-"+curName;
												nameBoxObj.text(curName);
											}

										}

									}

								});

								// 在定位点前头插入
								trObj.before(formatTemplate);
							}

						}
					},
					error: function(res) {
						// console.log(res.responseText)
					}
				});
				
				layer.close(index);

			}

		}
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

	var departmentCreateStr = "";
	departmentCreateStr += "<div>";
	departmentCreateStr += "<input class='form-control' type='text' id='departmentName' value='' placeholder='请输入部门名称' />";
	departmentCreateStr += "</div>";

	/*添加部门*/
	$('.create-department').click(function(){

		layer.open({
			title:'添加部门',
			content: departmentCreateStr,
			btn: ['确定', '取消'],
			success: function(){
				if(_userAgent.indexOf("MSIE")>0 && parseInt(_userAgent.match(/.*?MSIE\s(.*?);/)[1])<=9){
					showPlaceholder();
				}
			},
			btn1: function(index, layero){
				/*请求添加部门*/
				var departmentName = $.trim( $("#departmentName").val() );
				var departmentNamePlaceholder = $.trim( $("#departmentName").attr("placeholder") );
				if(departmentName=="" || departmentName==departmentNamePlaceholder) {
					autoMessageNotice("部门名称不为空");
					return false;
				} else {
					$.ajax({
						url: "/front/api/v1/department/create",
						type: "POST",
						dataType: "JSON",
						async: false,
						data: {
							name: departmentName
						},
						success: function(response) {
							if(response['status']['success']) {
								var formatTemplate = "";
								formatTemplate += 	'<tr class=""  data-level="{{dataLevel}}" >';
								formatTemplate += 		'<td class="menu-name-box" style="text-indent: {{textIndent}}em;">';
								formatTemplate += 			'{{name}}';
								formatTemplate += 		'</td>';
								formatTemplate += 		'<td class="">{{updateTime}}</td>';
								formatTemplate += 		'<td class="operationBox">';
								formatTemplate += 			'<div onclick="changeText(this, {{id}})" class="icon-img-box"><span class="icon-img"><img src="/images/edit.png" /></span>修改</div>';
								formatTemplate +=			'<a href="/admin/company/department/users?id={{id}}"><span class="icon-img"><img src="/images/eye.png" /></span>查看人员</a>';
								formatTemplate += 			'<div onclick="createDepartment(this, {{id}})" class="icon-img-box"><span class="icon-img"><img src="/images/plus.png" /></span>增加下级部门</div>';
								formatTemplate +=			'<div onclick="createParentDepartment(this, {{id}})" class="icon-img-box"><span class="icon-img"><img src="/images/plus.png" /></span>增加上级部门</div>';
								formatTemplate += 		'</td>'
								formatTemplate +=	'</tr>';

								var departmentCreateStr = "";
								departmentCreateStr += "<div>";
								departmentCreateStr += "<input class='form-control' type='text' id='departmentName' value='' placeholder='请输入部门名称' />";
								departmentCreateStr += "</div>";

								var data = response['data'];

								var curLevel = 1;

								var curName = data['name'];

								formatTemplate = formatTemplate.replace("{{dataLevel}}", curLevel);
								formatTemplate = formatTemplate.replace("{{textIndent}}", (curLevel-1)*3);
								formatTemplate = formatTemplate.replace("{{name}}", curName);
								formatTemplate = formatTemplate.replace("{{updateTime}}", data['updateTime']);
								formatTemplate = formatTemplate.replace(/{{id}}/g, data['id']);

								// 渲染
								$("#menuTable tbody").append(formatTemplate);

								$('.create-department').remove();

								autoMessageNotice(response['status']['message']);

							} 
						},
						error: function(res) {
							// console.log(res.responseText);
						}
					});

					layer.close(index);

				}

			},
	 	});

	});
	
});

</script>