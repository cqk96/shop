function show_err_login(err)
{
    var error_text = err.responseText;
    $("#remind").text(error_text);
    $("#remind").removeClass("nosee");
}

function show_manager(data)
{
    window.location.href = '/admin/home';
}
$(document).ready(function(){

  //自动居中
  getAutoMiddle('register-box');

  window.onresize = function(){
      getAutoMiddle('register-box');
  }

  //点击事件
  $("#loginBtn").click(function(){
    //验证所有项目已经fill
    $("#verify").next().removeClass("nosee");
    var result = myValidate([[['user_login','password','verify'],'require']]);

    for(var i=0; i<result.length; i++){
        if(result[i].success===false && result[i].rule=='require'){
            $("#"+result[i].id).parent().find('p').removeClass("nosee");
            $("#"+result[i].id).focus();
            return false;
        } else {
            $("#"+result[i].id).parent().find('p').addClass("nosee");
        }
    }
        var postParams = new Array();
        postParams['user_login'] = $("#user_login").val();
        postParams['password'] = $("#password").val();
        postParams['verify'] = $("#verify").val();
        sendAjax(postParams,'/admin/readUser','','show_manager','show_err_login');
  });

  //test keyboard event 
  window.onkeypress=function(event){

    //回车事件
    if(event.keyCode==13)
      $("#loginBtn").click();
  }

});