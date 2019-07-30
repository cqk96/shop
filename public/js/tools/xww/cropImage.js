/**
* need jquery 
* need layer 
* need jcrop
*/ 
;(function($, window, document){

	var _userAgent = navigator.userAgent;

	var _triggerObj = null;

	/* 存储位置 */ 
	var _positionArr = new Object();

	/* 插入结果目标 */ 
	var _targetId = "";

	// 方法名
	$.fn.cropImage = function(){

		_targetId = arguments[0]? arguments[0]:"";

		if(!document.getElementById('chooseFileInput')){
			// 初始化增加特殊 input文件node
			var inputFileNode = document.createElement("input");
			inputFileNode.type = "file";
			inputFileNode.id = "chooseFileInput";
			inputFileNode.style.display = "none";
			inputFileNode.accept = "image/*";
			inputFileNode.value = "";
			document.getElementsByTagName('body')[0].appendChild(inputFileNode);
		}

		_triggerObj = this;

		// this.bind("click", null, openFileChoose);
		openFileChoose();

		document.getElementById('chooseFileInput').addEventListener("change", waitPreview);

		return this;

	};

	// 文件选择
	function openFileChoose()
	{
		// 模仿点击
		$("#chooseFileInput").click();
	}

	// 准备预览文件
	function waitPreview()
	{

		// 方法采用7-9
		if(_userAgent.indexOf("MSIE")>0 && parseInt(_userAgent.match(/.*?MSIE\s(.*?);/)[1])<=9){

			document.getElementById("chooseFileInput").select();
			// document.getElementById("chooseFileInput").blur();
		    var path = document.selection.createRange().text;

		    var divObj = document.createElement("div");
		    divObj.style.display = "none";
		    divObj.innerHTML="";
		    divObj.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled='true',sizingMethod='scale',src=\"" + path + "\")";
		    divObj.id = "iePrev";
		    // document.getElementsByTagName("body")[0].appendChild(divObj);
		    // console.log(divObj);
		    // console.log(divObj.innerHTML);
		    // console.log($("#iePrev").html());
		    // openLayer($("#iePrev").html());
		    // removeChild(divObj);
		} else {

			var fileObj = $("#chooseFileInput")[0].files[0];

			var readerObj = new FileReader();

			readerObj.readAsDataURL(fileObj);
			
			// 转化base64
			readerObj.addEventListener("loadend", function(){
				if(readerObj.readyState==2){
					openLayer("<div id='prevImgBox' class='prev-img-box wait-crop-img'><img src='"+readerObj.result+"' /></div>");
				}
			});

		}

	}

	// 弹出提醒框 进行剪切+预览
	function openLayer(content) {

		layer.open({
			id: 2,
			type: 1, 
			width: 200,
			height: 400,
			content: content,
			closeBtn: false,
			btn: ["确定", "取消", "重置"],
			success: function(layero, index){
				// 初始化裁切
				$('.wait-crop-img').Jcrop({
					onChange: restorePosition,
					onSelect: restorePosition,
					setSelect: [20, 20, 100, 100]
				}, function() {
				  jcropObj = this;
				  $('.jcrop-keymgr').remove();
				});

			},
			btn1: function(index, layero){
				// 确定
				// 判断对象是否为空 
				if(_positionArr['w']!=0 && _positionArr['h']!=0) {
					// 1.图片上传
					$.ajax({
						url: "/api/v1/image/uploadBase64",
						type: "POST",
						dataType: "JSON",
						async: false,
						data: {
							pics: $("#prevImgBox img").attr("src")
						},
						success: function(response){
							if(response['status']['success']){
								var successUrl = response['data'][0];
								// 图片处理与返回
								$.ajax({
									url: "/front/api/v1/image/cut",
									type: "POST",
									dataType: "JSON",
									async: false,
									data: {
										url: successUrl,
										x: _positionArr['x'],
										y: _positionArr['y'],
										w: _positionArr['w'],
										h: _positionArr['h']
									},
									success: function(response){
										if(response['status']['success']){
											var selector = _triggerObj.selector;
											var objId = _triggerObj[0]['id'];
											$(selector).attr("src", response['data']);
											$(selector).removeClass("auto-height");

											if(_targetId!="") {
												// 创建结果input
												if($("input[name='"+objId+"']").length==0) {
													// 创建
													$("#"+_targetId).append("<input type='hidden' name='"+objId+"' value='"+response['data']+"' />");
												} else {
													// 更改
													$("input[name='"+objId+"']").val(response['data']);
												}
											}

											autoMessageNotice("裁切成功");
										} else {
											autoMessageNotice(content);			
										}
									}
								});
							} else {
								autoMessageNotice(content);
							}
						}
					});
				}

				// 关闭layer
				layer.close(index);
				return false;
			},
			btn2: function(){
				emptyPosition();
			},
			btn3: function(index, layero) {
				if(typeof(jcropObj)!="udefined"){
					jcropObj.release();
				}
				emptyPosition();
				return false
			}
		});

		$("#chooseFileInput").val("");

	}

	/*存储位置*/ 
	function restorePosition(moveObj) {

		/*由于图片进行过缩放 所以要算出比例*/
		var xProportion = ($("#prevImgBox img")[0]['width'])/($("#prevImgBox img")[0]['naturalWidth']);
		var yProportion = ($("#prevImgBox img")[0]['height'])/($("#prevImgBox img")[0]['naturalHeight']);
		_positionArr['x'] = Math.floor(moveObj.x/xProportion);
		_positionArr['y'] = Math.floor(moveObj.y/yProportion);
		_positionArr['w'] = Math.floor(moveObj.w/xProportion);
		_positionArr['h'] = Math.floor(moveObj.h/yProportion);
	}

	/*清空位置*/ 
	function emptyPosition() {
		_positionArr['x'] = 0;
		_positionArr['y'] = 0;
		_positionArr['w'] = 0;
		_positionArr['h'] = 0;
	}


}(jQuery, window, document));