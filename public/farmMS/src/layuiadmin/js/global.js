// 加入cookie
function addCookie(name,value,time){

    var currentTime = new Date().getTime() + time ;
    var expireDate = new Date(currentTime);
    document.cookie = name+"="+value+";expires="+expireDate+";path=/";
}

//取出 cookie
function getCookie(name){
    var strCookie=document.cookie;
    var arrCookie=strCookie.split("; ");
    for(var i=0;i<arrCookie.length;i++){
        var arr=arrCookie[i].split("=");
        if(arr[0]==name)return arr[1];
    }
    return "";
}

function UnixToDate(unixTime, isFull, timeZone) {
    if (typeof (timeZone) == 'number'){
        unixTime = parseInt(unixTime) + parseInt(timeZone) * 60 * 60;
    }
    var time = new Date(unixTime * 1000);
    var ymdhis = "";
    ymdhis += time.getUTCFullYear() + "-";
    ymdhis += (time.getUTCMonth()+1) + "-";
    ymdhis += time.getUTCDate();
    if (isFull === true){
        ymdhis += " " + time.getUTCHours() + ":";
        ymdhis += time.getUTCMinutes() + ":";
        ymdhis += time.getUTCSeconds();
    }
    return ymdhis;
}

// 返回上一页
function backHistory(){
    setTimeout(function(){
        window.history.back(-1)
    }, 1500);
}
// 鉴权失败
function identityFailure(code){
    if(code == '007' || code == '016'){
        parent.window.location.href="/farmMS/src/views/user/login.html";
    }
}

// 身份验证\
function tokenInvalid(accessToken,username){
    if(accessToken == '' || username == ''){
        layer.msg('身份验证过期,正在为您跳转登录页,请重新登录...');
        setTimeout(function(){
            parent.window.location.href="/farmMS/src/views/user/login.html";
        }, 1500);
    }
}



