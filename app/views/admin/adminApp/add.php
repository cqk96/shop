<?php include_once('../app/views/admin/_header.php') ?>
<style type="text/css">
	#dragBox {
		width: 200px;
		height: 200px;
		position: relative;
		background-color: #FF7E0F;
		box-shadow: 0px 0px 3px gray;
	}
	#dragParentBox {
		position: relative;
	}
	.hoverDragBox {
		left: 75px !important;
		font-size: 85px !important;
	}
	.cloudSpan {
		font-size: 60px;
		color: #FFF;
		position: absolute;
		top: 55px;
		left: 85px;
	}
	.drag-text {
		color: #FFF;
		font-size: 16px;
	}
	.drag-text-position {
		position: absolute;
		top: 155px;
		left: 50px;
	}
	.progress {
		margin-top: 10px;
		width: 200px !important;
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

</style>
<div class='main-container'>
<form id="myForm" method='POST' action="/admin/code/create" style='padding:20px !important;'>
<table>
<div class="smart-widget">
	<div class="smart-widget-header">
		<?php echo $page_title;?>
	</div>
    <li class="list-group-item" draggable="false">
		<div id="dragBox">
		</div>
		<span id="cloudSpan" class="cloudSpan glyphicon glyphicon-cloud-upload"></span>
		<div id="dragText" class="drag-text drag-text-position">拖拽此處進行上傳</div>
		<div class="progress progress-striped active">
		  <div id="progressBar" class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
		    <span class="sr-only">0%</span>
		  </div>
		</div>
		<div id="uploadResult"></div>
	</li>
</table>
</form>
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

<script type='text/javascript' src='/js/jquery.min.js'></script>
<script type='text/javascript' src='/js/myFuncs.js'></script>
<?php include_once('../app/views/admin/_footer.php') ?>
<script type="text/javascript">

/**
* 上傳apk
*/
function uploadApk(file)
{
	var formData = new FormData();
	formData.append("file", file);
	requestObj = new XMLHttpRequest();

	requestObj.open("post", "/api/tools/uploadApk", true);
	requestObj.upload.onprogress = function(e){
		if(e.lengthComputable){
			var progress = (e.loaded/e.total * 100 | 0)+"%";
			$("#progressBar").css("width", progress);
		}
	}

	requestObj.onreadystatechange = uploadResult;

	requestObj.send(formData);

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
				//$("#uploadResult").text("上傳成功");
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
	var html = "<div class='btn btn-default marginTB-xs'><i class='fa fa-spinner fa-spin m-right-xs'></i>上傳成功,正在解析</div>";
	$("#uploadResult").html(html);
	var postArr = new Array();
	postArr['url'] = url;
	sendAjax(postArr, '/admin/app/parseApk', 'false', "parseApkOk");
}

/**
* 解析結果
*/
function parseApkOk(data)
{

	if(data['success']){
		$("#uploadResult").html("解析成功");
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

		//show
		$('#myModal').modal('show');

	} else {
		$("#progressBar").css("width", "0%");
		$("#uploadResult").html("解析失敗,失敗原因:"+data['message']);
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

/**
* 確定取消
*/
function doCancelOk(data)
{
	if(data['success']){
		window.location.reload();
	}
}

$(document).ready(function(){
	
	if('draggable' in document.createElement('span')){
		var dragObj = document.getElementById('dragBox');
		var cloudSpanObj = document.getElementById('cloudSpan');
		var dragTextObj = document.getElementById('dragText');
		dragObj.ondragenter = function(){
			$(".cloudSpan").addClass("hoverDragBox");
			return false;
		}
		dragObj.ondragleave = function(event){
			var y = event.clientY;
			var x = event.clientX;
			if(y>250 || y<125){
				$(".cloudSpan").removeClass("hoverDragBox");
			}
			if(x>230 || x<35){
				$(".cloudSpan").removeClass("hoverDragBox");
			}
			return false;
		}

		dragObj.ondrop = function(e){
			e.preventDefault();
			uploadApk(e.dataTransfer.files[0]);
			return false;
		}
		cloudSpanObj.ondrop = function(e){
			e.preventDefault();
			uploadApk(e.dataTransfer.files[0]);
			return false;
		}
		dragTextObj.ondrop = function(e){
			e.preventDefault();
			uploadApk(e.dataTransfer.files[0]);
			return false;
		}

		dragObj.ondragover = function(e){
			return false;
		}

		cloudSpanObj.ondragover = function(e){
			return false;
		}
		dragTextObj.ondragover = function(e){
			return false;
		}

		dragObj.ondragend = function(e){
			return false;
		}
		cloudSpanObj.ondragend = function(){
			return false;
		}
		dragTextObj.ondragend = function(){
			return false;
		}
	} else {
		//
		alert("當前瀏覽器不支持拖曳上傳,請更換瀏覽器");
	}

	$('#myModal').on('hide.bs.modal',
    function() {
        var postArr = new Array();
		postArr['id'] = $("#recordId").val();
		sendAjax(postArr, '/admin/app/doCancel', 'false', 'doCancelOk');
    });

});
</script>
