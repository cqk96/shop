<?php include_once('../app/views/admin/_header.php') ?>
<style type="text/css">
	.headerBox {
		width: 100%;
		height: 200px;
		background-color: #FFF;
		border: 1px solid #FFF;
		margin-bottom: 40px;
	}
	.headerConcreteBox {
		width: 86%;
		min-width: 755px;
		height: 150px;
		margin: 50px auto 0 auto;
	}
	.bodyBox {
		width: 755px;
		height: auto;
		margin: 20px auto 90px auto;
	}
	.appInfoBox {
		width: 755px;
		height: auto;
		margin: 0 auto 0 auto;
	}
	.imgBox {
		width: 150px;
		height: 150px;
		float: left;
		position: relative;
	}
	.imgBox img {
		display: block;
		width: 105px;
		height: 105px;
	}
	.infoBox {
		width: 450px;
		height: 150px;
		float: left;
		position: relative;
	}
	.headerOperateBox {
		width: 150px;
		height: 150px;
		float: right;
	}

	.infoBar {
		width: 100%;
		height: 30px;
	}

	.appTagBox {
		padding: 2px 8px;
		border: 1px solid #9b9b9b;
		border-radius: 6px;
		display: inline-block;
		margin-top: 2px;
		font-size: 8px;
	}
	.packageNameBox {
		padding: 0px 8px;
		display: inline-block;
		margin-top: 2px;
		font-size: 8px;
	}
	.packageNameInfo {
		padding: 4px 8px;
		border: 1px solid #9b9b9b;
	}
	.vertical-line {
		display: inline-block;
		padding: 0px;
		margin-left: 10px;
		margin-right: 10px;
		color: gray;
	}
	.uploadBox {
		width: 100%;
		height: 30px;
		background-color: #3AB2A7;
		color: #FFF;
		border-radius: 5px;
		text-align: center;
		font-size: 14px;
		line-height: 30px;
	}
	.uploadBox:hover{
		cursor: pointer;
	}
	.uploadBox span{
		line-height: 30px;
	}
	.uploadBtnLeft {
		margin-left: 6px;
	}
	.operateBar {
		width: 100%;
		height: 60px;
		margin-top: 30px;
	}
	.operateBar li {
		width: 20%;
		float: left;
		border-left: 1px solid #9B9B9B;
		list-style: none;
		height: 100%;
		color: #9B9B9B;
	}
	.operateBar li span {
		font-size: 24px;
		margin-left: 15px;
	}
	.operateBar li div {
		font-size: normal;
		margin-left: 15px;
		margin-top: 14px;
	}

	.bottomTrangle {
		width: 0px;
		height: 0px;
		border-left: 15px solid transparent;
		border-right: 15px solid transparent;
		border-top: 15px solid transparent;
		border-bottom: 15px solid #f5f5f5;
		position: absolute;
		bottom: 0px;
	}
	.bottomTrangleLeft_1 {
		left: 35px;
	}
	.bottomTrangleLeft_2 {
		left: 30px;
	}
	.nosee {
		display: none;
	}

	/*body*/
	.eachItemBox {
		width: 100%;
		height: auto;
		position: relative;
		z-index: 1;
	}
	.ballBox {
		width: 45px;
		height: 45px;
		border-radius: 100%;
		border: 1px solid #9B9B9B;
		background-color: #F6F6F6;
		position: absolute;
		top: 0px;
		left: 0px;
		z-index: 3
	}
	.uploadIcon {
		position: absolute;
		color: #9B9B9B;
		font-size: 18px;
		top: 13.5px;
		left: 13.5px;
	}
	.itemContentBox {
		padding-left: 70px;
		border-left: 1px solid #9B9B9B;
		width: 726px;
		margin-left: 24px;
		z-index: 2;
		position: relative;
	}
	.versionBox {
		font-size: 18px;
		margin-bottom: 10px;
	}
	.timeBox {
		margin-bottom: 10px;
		color: #9B9B9B;
	}
	.timeIcon {
		margin-right: 5px;
	}
	pre {
		border: none;
		padding: 0px;
		margin: 0px;
		margin-bottom: 10px;
		width: 75%;
	}
	.editBtn {
		padding: 6px 10px;
		border: 1px solid #9B9B9B;
		border-radius: 15px;
	}
	.editBtn:hover {
		cursor: pointer;
	}
	.downloadBtn {
		padding: 4px 10px;
		border: 1px solid #9B9B9B;
		border-radius: 15px;
		margin-left: 10px;
	}
	.downloadBtn:hover,.deleteBtn:hover {
		cursor: pointer;
	}
	.deleteBtn {
		padding: 4px 10px;
		border: 1px solid #9B9B9B;
		border-radius: 15px;
		margin-left: 10px;
	}
	a.downloadBtn{
		color: #9B9B9B;
	}
	.bodyBox textarea {
		resize: none;
	}
	.saveBtn,.cancelBtn{
		float: right;
	}
	.saveBtn {
		background-color: #F8BA0B;
	    color: #FFF;
	    border-radius: 15px;
	    padding: 4px 15px;
	    display: inline-block;
	    margin-top: 20px;
	}
	.saveBtn:hover {
		cursor: pointer;
	}
	.cancelBtn {
		margin-top: 20px;
		display: inline-block;
		padding: 4px 15px;
		color: #9B9B9B;
	}
	.cancelBtn:hover {
		cursor: pointer;
		color: gray;
	}
	.deleteBox {
		width: 100%;
	    height: 30px;
	    border-color: #E86950;
	    color: #E86950;
	    border-radius: 5px;
	    text-align: center;
	    font-size: 14px;
	    border: 1px solid red;
	    margin-top: 20px;
	}
	.deleteBox:hover {
		cursor: pointer;
	}
	.deleteBox span {
		line-height: 30px;
	}
	.deleteBtnLeft {
		margin-left: 6px;
	}
	.appInfoBox table{
		width: 100%;
	}
	.appInfoBox table td {
		padding-top: 10px;
		padding-bottom: 10px;
	}
	.appInfoItem {
		color: #9B9B9B;
	}
	.appInfoValue {
		color: black;
		font-size: 18px;
	}
	.appIdBox td{
		padding-bottom: 60px !important;
	}
	.appIdBox_2 td {
		padding-top: 30px !important;
	}
	.activeLi {
		color: black !important;
		border-left-color: black !important; 
	}

	/*modal*/
	.modal-content {
		background-color: #FFF !important;
		border-radius: 10px;
	}
	.iconImage {
		width: 100px;
		height: 100px;
		display: block;
		margin: 20px auto 0px auto;
	}
	.modal-body table {
		border-collapse: inherit;
	}
	.closeBtn {
		font-size: 40px;
	    font-weight: 300;
	    margin-top: -10px !important;
	}
	.confirmBar {
		width: 100%;
		background-color: #FF7E0F;
		text-align: center;
		color: #FFF;
		border-bottom-left-radius: 9px;
		border-bottom-right-radius: 9px;
	}
	.modal-footer {
		padding: 0px;
	}
	.modal-footer:hover {
		cursor: pointer;
	}
	.modal-footer span{
		padding: 15px 8px;
		font-size: 20px;
	}
	.loadImg {
		display: block;
    	margin: 0 auto;
	}
</style>
<div class="headerBox">
	<input type="hidden" name="" id="appId" value="<?php echo empty($lists[0]['app_id'])? '':$lists[0]['app_id'] ?>">
	<input type="hidden" name="" id="packageName" value="<?php echo empty($lists[0]['package_name'])? '':$lists[0]['package_name'] ?>">
	<input style="display:none;" type="file" id="resource">
	<div class="headerConcreteBox">
		<div class="imgBox">
			<img src="<?php echo $lists[0]['icon']; ?>">
			<div class="bottomTrangle bottomTrangleLeft_1 nosee">

			</div>
		</div>
		<div class="infoBox">
			<div class="infoBar">
				<span class="appTagBox">Android</span>
				<span class="packageNameBox">
					<span class='packageNameInfo' style="border-top-left-radius: 6px;border-bottom-left-radius: 6px;border-right:none">PackageName</span><span class='packageNameInfo' style="border-top-right-radius: 6px;border-bottom-right-radius: 6px;"><?php echo $lists[0]['package_name'] ?></span>
				</span>
			</div>

			<ul class="operateBar">
				<li class="activeLi">
					<span class="fa  fa-file-text-o"></span>
					<div>基本信息</div>
				</li>
			</ul>

			<!-- 一行三角 -->
			<div class="bottomTrangle bottomTrangleLeft_2">

			</div>
		</div>
		<div class="headerOperateBox">
			<div class="uploadBox">
				<span class="fa fa-cloud-upload"><span class="uploadBtnLeft">上传新版本</span></span>
			</div>
			<div class="deleteBox">
				<span class="fa fa-trash-o"><span class="deleteBtnLeft">删除</span></span>
			</div>
		</div>
	</div>
</div>

<div class="appInfoBox">
	<table>
		<tr class="appIdBox">
			<td width="20%" class="appInfoItem">应用 ID</td>
			<td width="80%" class="appInfoValue"><?php echo $lists[0]['app_id']; ?></td>
		</tr>
		<tr class="appIdBox_2">
			<td width="20%" class="appInfoItem" style="border-top: 1px solid #eee">应用名称</td>
			<td width="80%" class="" style="border-top: 1px solid #eee"><input class="form-control" type="text" value="<?php echo $lists[0]['name']; ?>"/></td>
		</tr>
	</table>
</div>

<div class="bodyBox">
	<?php for($i=0; $i<count($lists); $i++){?>
	<div class="eachItemBox">
		<div class="ballBox">
			<span class="uploadIcon fa fa-cloud-upload"></span>
		</div>
		<div class="itemContentBox">
			<div class="versionBox">
				<b><?php echo $lists[$i]['version_name']; ?> (Build <?php echo $lists[$i]['version_code']; ?>)</b>
			</div>
			<div class="timeBox">
				<span class="timeIcon glyphicon glyphicon-time"></span><?php echo date("Y-m-d H:i:s", $lists[$i]['create_time']); ?>
			</div>
			<pre class="editDescription_<?php echo $i; ?>" data-id="<?php echo $lists[$i]['id']; ?>"><?php echo $lists[$i]['description']; ?></pre>
			<div class="smallOperateBar" style="padding-bottom: 80px;">
				<span alt="编辑更新日志" title="编辑更新日志" class="editBtn glyphicon glyphicon-pencil">
				</span>

				<a class="downloadBtn" target="_blank" href="/admin/app/download?id=<?php echo $lists[$i]['id']?>&appId=<?php echo $lists[$i]['app_id']?>" alt="下载源文件" title="下载源文件">
					<span class="downloadIcon fa fa-cloud-download"></span>
					<span><?php echo $lists[$i]['filesize'] ?></span>
				</a>

				<?php if($i!=0){?>
				<span class="deleteBtn" data-del-id="<?php echo $lists[$i]['id']; ?>">
					<span class='deleteIcon	glyphicon glyphicon-trash'></span>
					<span>刪除</span>
				</span>
				<?php }?>
			</div>
		</div>
	</div>
	<?php }?>
</div>

<!-- 模态框（Modal） -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            	<button type="button" class="close closeBtn" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">模态框（Modal）标题</h4>
            </div>
            <div class="modal-body" style="padding: 0px">
            	
            </div>
            <div class="modal-footer">
                
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<?php include_once('../app/views/admin/_footer.php') ?>
<script type="text/javascript" src="/js/myFuncs.js"></script>

<script type="text/javascript">
//更新结果
function doUpdateDescriptionOk(data)
{
	if(data['success']){
		var index = data['index'];
		$('.editDescription_'+index).html(textareaTagContent[index]);
		$('.smallOperateBar').eq(index).removeClass("nosee");
		return false;
	}
}

// 保存
function saveOperation(obj)
{
	
	var appId = $("#appId").val();
	var index = $('.saveBtn').index($(obj));
	var id = $('pre').eq(index).attr("data-id");
	
	if(appId=='' || id==''){
		alert("参数错误");
		return false;
	}

	var currentDescription = $(obj).prev().val();

	textareaTagContent[index] = currentDescription;

	var postArray = new Array();
	postArray['id'] = id;
	postArray['appId'] = appId;
	postArray['description'] = currentDescription;
	postArray['index'] = index;

	sendAjax(postArray, '/admin/app/doUpdateDescription', 'false', 'doUpdateDescriptionOk');
}

//取消
function cancelOperation(obj)
{
	
	var index = $('.cancelBtn').index($(obj));
	var originHtml = preTagContent[index];
	$('.editDescription_'+index).html(originHtml);
	$('.smallOperateBar').eq(index).removeClass("nosee");

}

//删除老版本成功
function deleteOlderOk(data)
{
	
	if(data['success']){
		window.location.reload();
	}

}

//删除成功
function deleteOk(data)
{
	if(data['success']){
		window.location. href = '/admin/apps';
	}
}

/**
* 上傳結果
*/
function uploadResult()
{

	if(requestObj.readyState == 4) {
		if(requestObj.status==200){
			var result = JSON.parse(requestObj.responseText);
			if(result['status']['success']){
				parseApk(result['data']);
			} else {
				$("#uploadResult").text(result['status']['message']);
			}
		}
	}

}

/**
* 解析
*/
function parseApk(url)
{
	//loading 浮现一个loading图片
	$("#myModalLabel").text("加载中");
	var loadHtml = "<img class='loadImg' src='/images/loading.gif'>";

	$('.modal-body').html(loadHtml);

	//show
	$('#myModal').modal('show');
	
	var postArr = new Array();
	postArr['url'] = url;
	postArr['packageName'] = $("#packageName").val();
	sendAjax(postArr, '/admin/app/parseApk', 'false', "parseApkOk");
}

/**
* 解析結果
*/
function parseApkOk(data)
{

	if(data['success']){

		//彈框 modal
		$("#myModalLabel").text("解析結果");
		var ss = "<li class='list-group-item' draggable='false' style='border:none;padding: 0px;'><table class='table'><tbody>";
		var idStr = "<input type='hidden' id='recordId' value='"+data.data['id']+"' />";
        var iconStr = "<tr><td rowspan=4 style='border-top:none'><img class='iconImage' src='"+data.data['icon']+"' /></td></tr>";
        var buildStr = "<tr> <td style='border-top:none'>"+data.data['version_name']+"(Build "+data.data['version_code']+")</td></tr>";
        var appNameStr = "<tr> <td style='border-top:none'> <input disabled readonly type='text' class='form-control' value='"+data.data['name']+"' /></td></tr>";
        var packageNameStr = "<tr><td style='border-top:none'> <input disabled readonly type='text' class='form-control' value='"+data.data['package_name']+"' /></td></tr>";
        var logStr = "<tr style='border-top: 1px solid gray'> <td width='40%' style='text-align:center;'>更新日志</td> <td width='55%'> <textarea id='description' class='form-control' name='' style='resize:none' rows='5'></textarea> </td> <td></td> </tr>";
        var se = "</tbody> </table> </li>";
		
		var html = ss+idStr+iconStr+buildStr+appNameStr+packageNameStr+logStr+se;
		$(".modal-body").html(html);

		//modal footer
		var footStr = "<div class='confirmBar' onclick='submitApk()'> <span class='glyphicon glyphicon-ok'></span> <span>確定</span> </div>";
		$(".modal-footer").html(footStr);

	} else {
		//解析失敗
		$("#myModalLabel").text("解析结果");
		$(".modal-body").html("<h3>解析失敗,失敗原因:"+data['message']+"</h3>");
	}
}

/**
* 確定取消
*/
function doCancelOk(data)
{
	if(data['success']){
		window.location.reload();
	}
}

/**
* 確定提交apk
*/
function submitApk()
{
	var postArr = new Array();
	postArr['id'] = $("#recordId").val();
	postArr['description'] = $("#description").val()==''? '暫無描述':$("#description").val();
	sendAjax(postArr, '/admin/app/doUpdate', 'false', 'doUpdateOk');
}

/**
* 更新結果
*/
function doUpdateOk(data)
{
	if(data['success']){
		window.location.reload();
	}
}

$(document).ready(function(){
	//全局内容
	preTagContent = new Array();
	textareaTagContent = new Array();

	$('.editBtn').click(function(){
		var index = $('.editBtn').index(this);
		var html = $('.editDescription_'+index).html();
		preTagContent[index] = html;
		var replaceStr = "<textarea rows=5 class='form-control'>"+html+"</textarea><span class='saveBtn' onclick='saveOperation(this)'>保存</span><span class='cancelBtn' onclick='cancelOperation(this)'>取消</span>";
		$('.editDescription_'+index).html(replaceStr);
		$('.smallOperateBar').eq(index).addClass("nosee");
	});

	//删除
	$('.deleteBtn').click(function(){
		var rs = confirm("确定要删除嘛?");
		if(rs){
			var appId = $("#appId").val();
			var id = $(this).attr("data-del-id");
			var postArray = new Array();
			postArray['appId'] = appId;
			postArray['id'] = id;
			sendAjax(postArray, '/admin/app/deleteOlder', 'true', 'deleteOlderOk');
		}
	});

	//删除
	$('.deleteBox').click(function(){
		var rs = confirm("确定要删除嘛?");
		if(rs){
			var appId = $("#appId").val();
			var postArray = new Array();
			postArray['appId'] = appId;
			sendAjax(postArray, '/admin/app/doDelete', 'true', 'deleteOk');
		}
	});

	//上传
	$('.uploadBox').click(function(){
		$("#resource").click();
	});

	$("#resource").change(function(){
		var obj = document.getElementById('resource');
		var fname = obj.files[0]['name'];
		var file = obj.files[0];
		var format = /apk/;
		if(format.test(fname)){
			var formData = new FormData();
			formData.append("file", file);
			requestObj = new XMLHttpRequest();

			requestObj.open("post", "/api/tools/uploadApk", true);

			requestObj.onreadystatechange = uploadResult;

			requestObj.send(formData);
		} else {
			alert("格式错误");
			return false;
		}

	});

	$('#myModal').on('hide.bs.modal',
    function() {
        var postArr = new Array();
		postArr['id'] = $("#recordId").val();
		sendAjax(postArr, '/admin/app/doCancel', 'false', 'doCancelOk');
    });

});
</script>