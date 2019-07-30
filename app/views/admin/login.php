<html>
<head>
<title><?php echo $page_title; ?></title>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="/css/admin-login.css">
</head>
<body>
<div class="login-box" id="register-box">
  <h2>登录<span class='sub-title'>Login</span></h2>
  <form class='form-horizontal'>
    <!-- 每一个输入选项 -->
    <div class="fill-box">
      <label id="email" class="col-sm-2 control-label">用户名</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" id="user_login" name="user_login" placeholder="请输入用户名">
          <p class="bg-danger nosee">用户名不为空</p>
      </div>
    </div>


    <div class="fill-box">
      <label id="pwd" class="col-sm-2 control-label">密码</label>
      <div class="col-sm-10">
        <input type="password" class="form-control" id="password" name="password" placeholder="请输入密码">
        <p class="bg-danger nosee">密码不为空</p>
      </div>
    </div>

    <div class="verify-box">
        <label>验证码</label>
        <input type="text" name="verify" id="verify">
        <img src="<?php echo $verify_url; ?>" onclick="this.src=<?php echo "'".$verify_url."'"; ?>">
		<p class="bg-danger nosee">验证码不为空</p>
    </div>
	<div style="TEXT-ALIGN: CENTER;">
		<?php if($site["register_type"]!=0){ ?>
		<a href="/signup"  class='btn btn-primary'>注册</a>
		<?php } ?>
		<button type="button" id="loginBtn" class='btn btn-default' >登录</button>
	</div>
    
    <p id="remind" class="bg-danger nosee">...</p>
  </form>
</div>
</body>

<script type="text/javascript" src="/js/jquery.min.js"></script>
<script type="text/javascript" src="/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/js/myFuncs.js"></script>
<script type="text/javascript" src="/js/admin-login.js"></script>
</html>
