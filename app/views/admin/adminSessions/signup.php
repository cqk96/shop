<html>
<head>
<title><?php echo $page_title; ?></title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,inital-scale=1.0,maximum-scale=1.0">
<link rel="stylesheet" type="text/css" href="/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="/css/simplify.min.css">
<link rel="stylesheet" type="text/css" href="/css/admin-register.css">
</head>
<body>
<div class="login-box" id="register-box">
  <div class="login-box login_sub login_sub_level_1">
  </div>
  <div class="login-box login_sub login_sub_level_2">
  </div>
    <div class="register-box smart-widget widget-dark-blue">
        <div class="smart-widget-header">
          注册 Sign Up
        </div>
        <div class="smart-widget-inner">
         
          <div class="smart-widget-body">
            <form class='form-horizontal no-margin'><!-- class='form-horizontal' -->
              <div class="form-group user-phone-box">
                <label class='col-md-2 control-label'>手机号</label>
                <div class='col-md-7'>
                    <input type="text" id="userPhone" class="form-control" placeholder="请输入手机号">
                </div>

                <div class='col-md-3 nosee user-phone-box-result'>
                    <label class='control-label'>

                        <span class="user-phone-box-error-icon nosee glyphicon glyphicon-remove" aria-hidden="true"></span>
                        <span class="user-phone-box-error-msg sr-only"></span><!-- 隐藏一段话 -->

                        <span class="user-phone-box-success-icon nosee glyphicon glyphicon-ok" aria-hidden="true"></span>
                        <span class="user-phone-box-success-msg sr-only">success</span><!-- 隐藏一段话 -->

                    </label>
                </div>
              </div>

              <div class="form-group user-password-box has-feedback">
                <label class='col-md-2 control-label'>密码</label>
                <div class='col-md-7'>
                  <input type="password" id="userPassword" class="form-control" placeholder="请输入密码">
                  <span class="showPassword glyphicon glyphicon-eye-open form-control-feedback" aria-hidden="true"></span>
                </div>
                <div class='col-md-3 nosee user-password-box-result'>
                    <label class='control-label'>

                        <span class="user-password-box-error-icon nosee glyphicon glyphicon-remove" aria-hidden="true"></span>
                        <span class="user-password-box-error-msg sr-only"></span>

                        <span class="user-password-box-success-icon nosee glyphicon glyphicon-ok" aria-hidden="true"></span>
                        <span class="user-password-box-success-msg sr-only">success</span>

                    </label>
                </div>
              </div>

              <div class="form-group user-pic-verify-box">
                <label class='col-md-2 control-label'>图形验证码</label>
                <div class='col-md-3'>
                  <input type="text" id="picVerify" class="form-control col-sm-10" placeholder="请输入验证码" size="6" maxLength="6">
                </div>
                <div class='col-md-4'>
                  <img class='registerVerify' src="<?php echo $verify_url; ?>">
                </div>
                <div class='col-md-3 nosee user-pic-verify-box-result'>
                  <label class='control-label'>

                      <span class="user-pic-verify-box-error-icon nosee glyphicon glyphicon-remove" aria-hidden="true"></span>
                      <span class="user-pic-verify-box-error-msg sr-only"></span>

                      <span class="user-pic-verify-box-success-icon nosee glyphicon glyphicon-ok" aria-hidden="true"></span>
                      <span class="user-pic-verify-box-success-msg sr-only">success</span>

                  </label>
                </div>
              </div>

              <div class="form-group user-msg-verify-box">
                <label class='col-md-2 control-label'>短信验证码</label>
                <div class='col-md-3'>
                  <input type="text" id="msgVerify" class="form-control col-sm-10" placeholder="请输入验证码" size="6" maxLength="6">
                </div>
                <div class='col-md-4'>
                  <button type="button" class="verifyBtn btn btn-warning btn-sm">发送手机短信</button>
                </div>
                <div class='col-md-3 nosee user-msg-verify-box-result'>
                  <label class='control-label'>

                      <span class="user-msg-verify-box-error-icon nosee glyphicon glyphicon-remove" aria-hidden="true"></span>
                      <span class="user-msg-verify-box-error-msg sr-only"></span>

                      <span class="user-msg-verify-box-success-icon nosee glyphicon glyphicon-ok" aria-hidden="true"></span>
                      <span class="user-msg-verify-box-success-msg sr-only">success</span>

                  </label>
                </div>
              </div>

              <div class="form-group user-protocal-box">
                <div class="col-md-offset-2 col-md-7">
                    <div class="custom-checkbox">
                      <input type="checkbox" id="protocalCheckbox" class='checkbox-grey'>
                      <label for="protocalCheckbox"></label>
                    </div>
                    阅读并接受
                    <a href="javascript:void(0);" class='btn btn-link'>《使用协议》</a>
                </div>
                <div class='col-md-3 nosee user-protocal-box-result'>
                  <label class='control-label'>

                      <span class="user-protocal-box-error-icon nosee glyphicon glyphicon-remove" aria-hidden="true"></span>
                      <span class="user-protocal-box-error-msg sr-only"></span>

                      <span class="user-protocal-box-success-icon nosee glyphicon glyphicon-ok" aria-hidden="true"></span>
                      <span class="user-protocal-box-success-msg sr-only">success</span>

                  </label>
                </div>
              </div>
              <div class="form-group">
                  <div class='col-md-offset-2 col-md-4'>
                      <button type="button" class="submitBtn btn btn-primary btn-sm" onclick="" style='float:right;background: #4c5f70;'>提交</button>
                  </div>
                  <div class='col-md-6'>
                      <a href="/admin" class="btn btn-default btn-sm">返回</a>
                  </div> 
              </div>
            </form>
          </div>
        </div><!-- ./smart-widget-inner -->
    </div>
</div>
</body>

<script type="text/javascript" src="/js/jquery.min.js"></script>
<script type="text/javascript" src="/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/js/myFuncs.js"></script>
<script type="text/javascript" src="/js/admin/admin-register.js?3"></script>
</html>
