/**
* 函数工具简化操作
* @author xww <5648*****@qq.com>
*/

/*
* 函数列表
*
* 返回性别整数
* int getGenderNum(string genderText)
*/

/*
* 返回性别整数
* @param [genderText]   性别文本
* @return int
*/
function getGenderNum(genderText)
{

	switch(genderText){
            case "男":
                return 1;
                break;
            case "女":
                return 2;
                break;
            default:
                return 3;
                break;
        }

}


//计算input内容长度  改变size 默认10
function calculateInoutSize()
{
	$('.canBeEditItem').each(function(){
		var inputValue = $(this).val();
		var inputSize = inputValue.length;
		if(inputSize>10)
			$(this).css("width",inputSize+'em');
	});
}

/*
* 改变列值
* 暂时只支持POST
* @param [columns]     array     修改的列名与值
* @param [url]         string    ajax地址
* @param [callback]    string    成功回调函数
* @param [errCallback] string    失败回调函数
*/

function sendAjax(columns,url,async,callback,errCallback)
{
	var async = arguments[2]? arguments[2]:'';
	var callback = arguments[3]? arguments[3]: '';
	var errCallback = arguments[4]? arguments[4]: '';
	
	//定义默认成功回调函数
	function defaultCallback(data)
	{
		// console.log(data);
	}

	//定义默认失败回调函数
	function defaultErrCallback(err)
	{
		// console.log("err:"+err.responseText);
	}


	var type="POST";

	if((typeof columns)!='object' || url==''){
		alert("函数sendAjax: 错误的参数传入");
	}else {

		if(callback=='')
			callback = "defaultCallback";

		if(errCallback=='')
			errCallback = "defaultErrCallback";

		if(async=='')
			async = true;

		if((typeof eval(callback))!='function')
			alert(callback+"回调函数不存在");
		else if((typeof eval(errCallback))!='function')
			alert(errCallback+"回调函数不存在");
		else {
			var dataObj = new Object();

			for(var key in columns){
				dataObj[key] = columns[key];
			}
			
			$.ajax({
				url: url,
				type: 'POST',
				dataType: "JSON",
				data: dataObj,
				async: async,
				complete: function(x,y){
					//console.log(url+" : "+x.status);
				},
				success: function(data){
					eval(callback).call(callback,data);
				},
				headers: function(request){
					
				},
				error: function(err){
					eval(errCallback).call(errCallback,err);
				}
			});

		}		

	}

}

//ajax 函数截至

/*
* 自动居中  依赖父容器的大小
* need jquery
* param  string id
*
* need position absolute
*/
function getAutoMiddle(boxId)
{
  	
  if((typeof boxId)!="string"){
  		alert("错误的参数传入");
  		return false;
  }

  var parentWidth = $("#"+boxId).parent().width();
  var parentHeight = $("#"+boxId).parent().height();
  var childWidth = $("#"+boxId).width();
  var childHeight = $("#"+boxId).height();

  var childLeft = (parentWidth-childWidth)/2;
  var childTop = (parentHeight-childHeight)/2;

  //值为-
  if(childLeft<0)
  		childLeft = 0;
  if(childTop<0)
  		childTop = 0;

  $("#"+boxId).css({
    'left': childLeft+'px',
    'top': childTop+'px'
  });

}

//自动居中函数截至

/*
* 自定义属性值获取
* param1 string className
* param2 string/num index
* param3 string attribute
* need jquery
*/
function getPersonalAttributes(className,index,attribute)
{
	if((typeof className)!='string' || (typeof attribute)!='string'){
		alert(错误的参数传入);
		return false;
	}

	return $('.'+className).eq(index).attr(attribute);

}
//自定义属性值获取 截止


/*
* 根据当前对象大小容器调整对象容器大小
* parameter1 string currentObj id/class
* parameter2 string goalObj id/class
* need jquery
*/
function  changeObjSize(currentObj,goalObj)
{
	var currentObjSymbol = '.';
	var goalObjSymbol = '.';
	
	if(document.getElementById(currentObj)){
		currentObjSymbol = '#';
	}

	if(document.getElementById(goalObj)){
		goalObjSymbol = '#';
	}


	var objWidth = $(currentObjSymbol+currentObj).width();
	var objHeight = $(currentObjSymbol+currentObj).height();

	$(goalObjSymbol+goalObj).css({
		'width': objWidth,
		'height': objHeight
	});

}

//根据当前对象大小容器调整对象容器大小 截止

//获取cookie的内容
/*
* 只支持一维数组
* param1 string cookie name
* return string value or false( not found );
*/
function getCookieValue(key)
{
	var result = false;
	if(key=='')
		alert('函数:getCookieValue参数不为空');
	else{
		var cookies_str = document.cookie;
		var cookie_array = cookies_str.split(';');	
		var format = new RegExp(key+"=(.*)");
		for (var i = cookie_array.length - 1; i >= 0; i--) {
			var rs = cookie_array[i].match(format);
			
			if(rs){
				result = rs[1];
			}
		}
	}

	return result;
	
}

/*ajax请求 传送选定项目  for checkbox*/
/*
* param1 string checkbox className
* param2 string notice str
* param3 string ajax url
* param4 bool async
* param5 string success callback
* param6 string flase callback
*/
function deleteChooseItems(itemClassName,noticeStr,url,async,scallback,fcallback)
{
	var itemClassName = (arguments[0]=='')? '':arguments[0];
	var noticeStr = (arguments[1]=='')? '':arguments[1];
	var url = (arguments[2]=='')? '':arguments[2];
	var async = (arguments[3]=='')? '':arguments[3];
	var scallback = (arguments[4]=='')? '':arguments[4];
	var fcallback = (arguments[5]=='')? '':arguments[5];



	if(noticeStr){
		var ok = confirm(noticeStr);	
		if(!ok)
			return false;
	}

	var params = new Array();
	var postParam = new Array();

	$("."+itemClassName).each(function(){

		if($(this).prop('checked'))
			params.push($(this).val());
	});

	if(params.length==0){
		alert("尚未选择任何项目");
	} else {
		postParam['ids'] = params;

		sendAjax(postParam,url,async,scallback,fcallback);

	}
	
}

/*ajax请求 传送选定项目  for checkbox*/
/*
* param1 string checkbox className
* param2 string notice str
* param3 string ajax url
* param4 bool async
* param5 string success callback
* param6 string flase callback
*/
function doSomeWithCheckBoxItems(itemClassName,noticeStr,url,async,scallback,fcallback)
{
	var itemClassName = (arguments[0]=='')? '':arguments[0];
	var noticeStr = (arguments[1]=='')? '':arguments[1];
	var url = (arguments[2]=='')? '':arguments[2];
	var async = (arguments[3]=='')? '':arguments[3];
	var scallback = (arguments[4]=='')? '':arguments[4];
	var fcallback = (arguments[5]=='')? '':arguments[5];



	if(noticeStr){
		var ok = confirm(noticeStr);	
		if(!ok)
			return false;
	}

	var params = new Array();
	var postParam = new Array();

	$("."+itemClassName).each(function(){

		if($(this).prop('checked'))
			params.push($(this).val());
	});

	if(params.length==0){
		alert("尚未选择任何项目");
	} else {
		postParam['ids'] = params;
		sendAjax(postParam,url,async,scallback,fcallback);

	}
	
}

/*验证函数*/
/*
*  param1 rules [[item1Id,item2Id],'ruleName'];
*/

function myValidate(rules)
{
	if((typeof rules)!='object'){
		alert("针对函数myValidate错误的参数传入");
		return false;
	}

	
	var eachLogs = new Array();

	var countValidateRules = rules.length;

	for(var i=0; i<countValidateRules; i++){
		var errorLogs = new Array();
		for(var j=0; j<rules[i][0].length; j++){
			errorLogs[j] = new Array();
			var rs = myValidateRules(rules[i][0][j],rules[i][1]);
			errorLogs[j]['id'] = rules[i][0][j];
			errorLogs[j]['rule'] = rules[i][1];
			errorLogs[j]['success'] = rs;
		}
	}

	return errorLogs;

}

/*规则函数*/
function myValidateRules(id,item)
{
	switch(item){
		case 'require':
			return $("#"+id).val()==''? false:true;
		break;
	}

}

/*解析结果*/
function myParseResult(result,response)
{
	var ok = true;
	for(var i=0; i<result.length; i++){
		if(!result[i].success){
			failureValidate(result[i].id,result[i].rule,response);
			ok = false;
			break;
		}
	}
	if(!ok)
		return false;
	else
		return true;
}

/*规则匹配失败实行*/
function failureValidate(id,item,response)
{
	//console.log(id);
	for(var j=0; j<response.length; j++){

		//规则匹配
		if(response[j][response[j].length-1]==item){
			for(var k=0;k<response[j].length-1; k++){
				
				//项目匹配
				if(response[j][k][0]==id){
					$("#"+id).focus();
					alert(response[j][k][1]);
					break;
				}
			}
		}

	}

}

//input数值判断
function InputNumber(obj)
{
	var reg = /[a-zA-Z]/;
	var value = $(obj).val();

	var numberLength = arguments[1];
	var symbol = arguments[2];

	//数字判断
	if(reg.test(value)){
		var numFormat = /[0-9]/g;
		var res = value.match(numFormat);
		if(res){
			res = res.join('');
		}
		$(obj).val(res);
		return false;
	}

	//长度判断
	if((typeof numberLength)!='undefined' && numberLength!=''){
		if(value.length>numberLength){
			var rightString = value.substr(0,numberLength);
			$(obj).val(rightString);
			return false;
		}
	}

	//数值判断
	if((typeof symbol)!='undefined' && symbol!=''){
		var symbolFormat = /^(-)/;
		if(symbol=='+') {
			if(symbolFormat.test(value)){
				$(obj).val('');
			}
		} else if(symbol=='-'){
			if(!symbolFormat.test(value)){
				$(obj).val('');
			}
		}
	}

}