
//增加参数组件
function addParamGroup()
{
	var str = "<div class='form-group param-box'>"+
				"<label class='col-lg-2 control-label'></label>"+
				"<div class='col-lg-12'>"+
					"<div class='row'>"+
						"<div class='col-md-offset-1 col-md-3'>"+
							"<input type='text' name='keys[]' oninput='' onpropertychange='' required class='form-control keyInput' placeholder='Key'>"+
						"</div>"+
						"<div class='col-md-offset-1 col-md-3'>"+
							"<input type='text' name='values[]' class='form-control changeInputType' placeholder='Value'>"+
						"</div>"+
						"<div class='col-md-4 input-group'>"+
							"<div class='input-group-btn'>"+
								"<button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'><span class='showInlineText'>Text </span><span class='caret'></span></button>"+
								"<ul class='dropdown-menu'>"+
									"<li><a href='javascript:void(0);' onclick='changeInputType(this)' input-type='text' class='typeText'>Text</a></li>"+
									"<li><a href='javascript:void(0);' onclick='changeInputType(this)' input-type='file' class='typeText'>File</a></li>"+
								"</ul>"+
							"</div>"+
							"<label class='control-label'>"+
								"<a class='removeTag-a' href='javascript:void(0);' onclick='removeParamGroup(this)'><i class='glyphicon glyphicon-remove'></i></a>"+
							"</label>"+
						"</div>"+
					"</div>"+
				"</div>"+
			"</div>";
	$('.params-from').append(str);
}

//移除组件
function removeParamGroup(obj)
{
	var position = $('.removeTag-a').index(obj);
	
	$('.param-box').eq(position).remove();
}

//改变类型
function changeInputType(obj)
{
	
	var clickType = $(obj).text();
	var changeType = $(obj).attr('input-type');
	var parentObj = $(obj).parent().parent();
	var position = $('.dropdown-menu').index(parentObj);
	$('.showInlineText').eq(position).text(clickType+" ");
	$('.changeInputType').eq(position).prop('type',changeType);

	if(changeType=='file'){
		//去掉该行key name   将key值给此时名字
		$('.keyInput').eq(position).prop('name', '');
		var removeKeyName = $('.keyInput').eq(position).val();
		$('.changeInputType').eq(position).prop('name',removeKeyName);
		$('.keyInput').eq(position).attr('oninput', 'changeFileInput(this)')
		$('.keyInput').eq(position).attr('onpropertychange', 'changeFileInput(this)');
	} else {
		$('.keyInput').eq(position).prop('name', 'keys[]');
		$('.changeInputType').eq(position).prop('name','values[]');
		$('.keyInput').eq(position).attr('oninput', '')
		$('.keyInput').eq(position).attr('onpropertychange', '');
	}

}

//初始加载  file型 input改变key
function changeFileInput(obj)
{
	
	var currentValue = $(obj).val();
	var parendObj = $(obj).parent();
	parendObj.next().find('input').prop('name',currentValue);

}

$(document).ready(function(){

	$('.addParamGroup').click(function(){
		addParamGroup();
	});

});