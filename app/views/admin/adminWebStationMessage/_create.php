<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <title>新建消息</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="hzhanghuan">

    <!-- Bootstrap core CSS -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">
	
	<!-- Font Awesome -->
	<link href="/css/font-awesome.min.css" rel="stylesheet">

	<!-- ionicons -->
	<link href="/css/ionicons.min.css" rel="stylesheet">
	
	<!-- Morris -->
	<link href="/css/morris.css" rel="stylesheet"/>	

	<!-- Datepicker -->
	<link href="/css/datepicker.css" rel="stylesheet"/>	

	<!-- Animate -->
	<link href="/css/animate.min.css" rel="stylesheet">

	<!-- Owl Carousel -->
	<link href="/css/owl.carousel.min.css" rel="stylesheet">
	<link href="/css/owl.theme.default.min.css" rel="stylesheet">

	<!-- Simplify -->
	<link href="/css/simplify.min.css" rel="stylesheet">

	<link href="/css/admin/global.css?1" rel="stylesheet">

	<link rel="stylesheet" type="text/css" href="/css/date-picker/jquery-ui.min.css">

	
	<style type="text/css">
	.smart-widget-header {font-size: 16px; }
	table { color: black; }
	</style>
	<!-- Jquery -->
	<script src="/js/jquery-1.11.1.min.js"></script>

<link rel="stylesheet" type="text/css" href="/js/tools/layer/skin/default/layer.css">
<link rel="stylesheet" href="/js/tools/layui/css/layui.css">
<link rel="stylesheet" href="/js/tools/zTree/zTreeStyle/zTreeStyle.css">
<style type="text/css">
	
.top-rest {
	width: 100%;
	height: 43.2px;
}

body {
	background-color: #FFF;
}
.contianer {
	background-color: #FFF;
}

.goal-table {
	width: 50%;
	margin: 0 auto;
	/*border: 1px solid black;*/
	overflow: hidden;
}

.item-padding-right {
	padding-right: 15px;
}

.item-padding-left {
	padding-left: 15px;
}

.goal-table td{
	padding-bottom: 18px;
	position: relative;
}
.goal-table label {
	padding-top: 9.9px;
	padding-bottom: 9.9px;
	margin-left: 21.6px;
	font-size: 12px;
	color: #A8ACB9;
}

.goal-table textarea {
	resize: none;
	padding: 6.3px 14.4px 0px 14.4px ;
}
.goal-table input { 
	padding-left: 21.6px;
	position: relative;
	z-index: 1;
}
.goal-table select { 
	padding-left: 21.6px;
}
/*.operate-btn {
	float: right;
}*/
.operate-btn:hover {
	cursor: pointer;
}

.choose-date-box {
	position: relative;
}
.date-btn {
	position: absolute;
	display: block;
	top: 5.76px;
	right: 11.52px;
	z-index: 3;
}
.date-btn:hover {
	cursor: pointer;
}
.layui-input-block {
	margin-left: inherit;
}
.target-title {
	vertical-align: top;
	margin-right: 28px;
}
.target-box {
	display: inline-block;
    width: 40%;
    height: 360px;
    overflow-y: auto;
    overflow-x: auto;
    border: 1px solid #979797;
	border-radius: 5px;
}
.target-td {
	padding-top: 22px;
}
.submit-btn {
	display: block;
	margin: 0 auto;
}
.operate-btn-box {
	padding-top: 41px;
}
</style>
</head>
<body>
<div class="contianer">

	<div class='top-rest'></div>

	<!--  -->
	<form class="layui-form" action="">
		<table class='goal-table'>
			<tbody>
				<tr>
					<td class='item-padding-right'  width="50%">
						<label class='control-label'>发起人</label>
						<input type="hidden" id="userId" />
						<div class="layui-input-block">
					      	<select name="author_id" required lay-verify="required" lay-filter="">
								<option value="<?php echo $user['id'] ?>" selected><?php echo $user['name'] ?></option>				        
					      	</select>
					    </div>
					</td>
					<td>&nbsp;</td>	
				</tr>

				<tr>
					<td colspan=2>
						<label class='control-label'>消息内容</label>
						<textarea id="messageContent" required class="form-control" placeholder='请输入消息内容' rows="6" ></textarea>
					</td>	
				</tr>

				<tr>
					<td colspan=2 class='target-td'>
						<label class='control-label target-title'>推送对象</label>
						<ul id="treeDemo" class="ztree target-box"></ul>
					</td>
				</tr>

			</tbody>

			<tfoot>
				<tr>
					<td class="operate-btn-box"  colspan=2 > 
						<img class="operate-btn submit-btn" src="/images/submit-btn.png"> 
					</td>
				</tr>
			</tfoot>

		</table>
	</form>

</div>
</body>
<?php include_once('../app/views/admin/_footer.php') ?>
<script type="text/javascript" src="/js/pagination.js"></script>
<script type="text/javascript" src="/js/tools/layer/layer.js"></script>
<script type='text/javascript' src='/js/tools/layui/layui.js'></script>
<script type="text/javascript" src="/js/tools/zTree/zTreeStyle/jquery.ztree.all.min.js"></script>
<script type="text/javascript">

// 预处理
function childrenAndUserResult(treeId, parentNode, responseData)
{
	
	var childrenDepartments = responseData['data']['departments'];
	var childrenUsers = responseData['data']['users'];

	if( childrenDepartments == null && childrenUsers == null ) {
		return null;
	}

	var childrenNodes = [];

	if( childrenDepartments != null ) {

		for (var i = 0; i < childrenDepartments.length; i++) {
			var tempSubNodeObject = new Object();
			tempSubNodeObject['name'] = childrenDepartments[i].name;
			tempSubNodeObject['id'] = childrenDepartments[i].id;
			tempSubNodeObject['isParent'] = true;
			childrenNodes.push(tempSubNodeObject);
		}

	}

	if( childrenUsers != null ) {

		for (var i = 0; i < childrenUsers.length; i++) {
			var tempSubNodeObject = new Object();
			tempSubNodeObject['name'] = childrenUsers[i].name;
			tempSubNodeObject['id'] = childrenUsers[i].id;
			childrenNodes.push(tempSubNodeObject);
		}

	}

	return childrenNodes;

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

// 检测是否必填项为空
function checkEmptyItem()
{
	
	postUserArr = [];
	var checkedNodes = zTreeObj.getCheckedNodes(true);
	
	if( checkedNodes.length == 0 ) {
		autoMessageNotice("请选择要推送的人员");
		return false;
	}

	for (var i = 0; i < checkedNodes.length; i++) {
		
		if( typeof(checkedNodes[i].isParent)=="undefined" ) {
			postUserArr.push(checkedNodes[i].id);
		}

	}

	if( postUserArr.length == 0) {
		autoMessageNotice("请选择要推送的人员");
		return false;
	}

	var ok = true;
	$("textarea").each(function(){
		
		var isRequired = $(this).attr("required");
		if( typeof(isRequired)!="undefined" ) {
			var curPlaceHolder = $.trim($(this).attr("placeholder"));
			var curValue = $.trim($(this).val());
			if( curPlaceHolder== curValue) {
				autoMessageNotice(curPlaceHolder);
				ok = false;
				return true;
			}
		}

	});

	if( ok==false ) {
		return false;
	}

	return true;

}

$(document).ready(function(){

	layui.use(['layer', 'form'], function(){
		var layer = layui.layer;
		form = layui.form;

	});

	// json
	var usersJson = <?php echo "'".$usersJson."'"; ?>;
	var departmentsJson = <?php echo "'".$departmentsJson."'"; ?>;

	// array
	var usersArr = usersJson=="null"? null:JSON.parse(usersJson);
	var departmentsArr = departmentsJson=="null"? null:JSON.parse(departmentsJson);

   // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
   var setting = {
   		async: {
   			enable: true,
   			autoParam: ["id=pid"],
   			type: "get",
   			url: "/api/v1/department/childrenAndUser",
   			dataFilter: childrenAndUserResult
   		},
   		check: {
			enable: true
		}

   };

   // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
   var zNodes = [];

   if( departmentsArr!=null ) {
   		
   		for (var i = 0; i < departmentsArr.length; i++) {
   			var tempNodeObject = new Object();
   			tempNodeObject['name'] = departmentsArr[i].name;
   			tempNodeObject['id'] = departmentsArr[i].id;

   			// // 判断是否有子节点
   			if( departmentsArr[i].subDepartmens!=null ) {
   				var subDepartmens = departmentsArr[i].subDepartmens;
   				tempNodeObject['children'] = new Array();
   				for (var j = 0; j < subDepartmens.length; j++) {
   					var tempSubNodeObject = new Object();
   					tempSubNodeObject['name'] = subDepartmens[j].name;
   					tempSubNodeObject['id'] = subDepartmens[j].id;
   					tempSubNodeObject['isParent'] = true;
   					tempNodeObject['children'].push(tempSubNodeObject);
   				}
   			} else {
   				tempNodeObject['isParent'] = true;
   			}

   			zNodes.push(tempNodeObject);

   		}

   }

   if( usersArr!=null ) {
	   	
	   	for (var i = 0; i < usersArr.length; i++) {

   			var tempNodeObject = new Object();
   			tempNodeObject['name'] = usersArr[i].name;
   			tempNodeObject['id'] = usersArr[i].id;

   			zNodes.push(tempNodeObject);

	   	}

   }

    zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);

	// 判断是否 ie ie10以下处理placeholder
	_userAgent = navigator.userAgent;
	if(_userAgent.indexOf("MSIE")>0 && parseInt(_userAgent.match(/.*?MSIE\s(.*?);/)[1])<=9){

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

	$('.submit-btn').click(function(){

		// 检测必填项是否为空
		var checkRs = checkEmptyItem();
		
		if( checkRs ) {

			$('.submit-btn').unbind("click");

			// 提交
			
			$.ajax({
				url: "/api/v1/message/webStation/create",
				type: "POST",
				dataType: "JSON",
				data: {
					content: $.trim($("#messageContent").val()),
					userIds: postUserArr.join(",")
				},
				async: false,
				success: function(response) {
					if( response['status']['success'] ) {
						setTimeout(function(){
							window.location.href = "/admin/webStationMessages";
						}, 1500);
					} else {
						autoMessageNotice( response['status']['message'] );
					}
				},
				error: function(err) {
					// console.log(err.responseText);
				}
			});

		}

	});

});
</script>