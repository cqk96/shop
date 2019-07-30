<!DOCTYPE html>
<html lang="en">
	<meta http-equiv="pragma" content="no-cache"/>
  	<head>
	    <?php include '_header.php' ?>
		<style type="text/css">
			.nosee {
				display: none;
			}
			.modalResultBox {
				position: absolute;
			    top: 0px;
			    font-size: 4px;
			    width: 100%;
			    left: 0px;
			    text-align: center;
			}
			.modalResultBox span {
				display: inline-block;
				margin: 0 auto;
				color: #FFF;
				background-color: #F7784A;
			}
			.modalOk {
				background-color: #77FF47 !important;
			}
			.user-profile-pic {
				border-radius: 100%;
				box-shadow: 0 0 4px gray;
			}
			.padding-md {
				padding: 30px !important;
			}
			.light-tag {color:  #FFF; font-size: 14px; }
			/*.wrapper {padding-top: 20px !important;}*/

			/*红火*/
			.fire-hot { color: red; margin-left: 5px; }

			/*红点*/
			.undo-work-tag {
				background-color: red;
			    display: inline-block;
			    width: 8px;
			    height: 8px;
			    border-radius: 100%;
			    margin-left: 1px;
			}
			/*.footer {
				position: fixed;
			}*/
			.modal-dialog {
				margin: 30px auto;
			}

			.top-nav-inner {
				position: relative;
			}

			.logo-box {
				width: 240px;
				height: 53px;
				position: absolute;
				top: 0px;
				left: 0px;
				background: #31B4E6;
			}

			.logo-box p{
				padding: 0px;
				margin: 0px;
				color: #FFF;
			}
			.logo-box .logo-title {
				margin-top: 7px;
			}
			.logo-img {
				float: left;
			    margin: 9px 21px 10px 11px;
			    width: 35px;
			}
		</style>
  	</head>

  	<body class="overflow-hidden">
		<div class="wrapper preload">
			<div class="top-nav">
				<div class="top-nav-inner">

					<!-- 后台logo -->
					<div class="logo-box">
						<img class="logo-img" src="/images/logo.png" />
						<p class="logo-title">Virgo 基础框架</p>
						<p class="logo-title-sub"></p>
					</div>

					<div class="nav-container">
						<div class="pull-right m-right-sm">
							<div class="user-block hidden-xs">
								<a href="#" id="userToggle" data-toggle="dropdown">
									<img id="adminAvatar" src="<?php echo empty($user['avatar'])? '/images/avatar.png':$user['avatar'] ?>" alt="avatar" class="img-circle inline-block user-profile-pic">
									<div class="user-detail inline-block">
										<?php echo empty($user['name'])? $user['user_login']:$user['name'] ?>
										<i class="fa fa-angle-down"></i>
									</div>
								</a>
								<div class="panel border dropdown-menu user-panel">
									<div class="panel-body paddingTB-sm">
										<ul>
											<li>
												<a href="javascript:void(0);" onclick="changeIframeUrl('/admin/user/read')">
													<i class="fa fa-edit fa-lg"></i><span class="m-left-xs">我的资料</span>
												</a>
											</li>

											<li>
												<a href="javascript:void(0);" class="edit-pwd">
													<i class="glyphicon glyphicon-lock"></i><span class="m-left-xs">修改密码</span>
												</a>
											</li>
											<li>
												<a href="javascript:void(0);" onclick="logOut()">
													<i class="fa fa-power-off fa-lg"></i><span class="m-left-xs">注销</span>
												</a>
											</li>
										</ul>
									</div>
								</div>
							</div>
							
						</div>
					</div>
				</div><!-- ./top-nav-inner -->	
			</div>
			<div class="sidebar-menu fixed">
				<div class="sidebar-inner scrollable-sidebar">
					<div class="main-menu">
						<ul class="accordion">
							<li class="menu-header">
								主菜单
							</li>
							<li class="bg-palette1 active">
								<a href="javascript:void(0);" onclick='changeUrl(this)' data-href='/admin/mine'>
									<span class="menu-content block">
										<span class="menu-icon"><i class="block fa fa-home fa-lg"></i></span>
										<span class="text m-left-sm">控制台</span>
									</span>
									<span class="menu-content-hover block">
										首页
									</span>
								</a>
							</li>
							

							<?php echo empty($backMenus)? '':$backMenus;  ?>
						</ul>
					</div>

					

					<div class="sidebar-fix-bottom clearfix">
						<div class="user-dropdown dropup pull-left">
							<!-- 个人设置 -->
							<!-- <a href="#" class="dropdwon-toggle font-18" data-toggle="dropdown"><i class="ion-person-add"></i>
							</a> -->
							<ul class="dropdown-menu">
								<li>
									<a href="inbox.html">
										Inbox
										<span class="badge badge-danger bounceIn animation-delay2 pull-right">1</span>
									</a>
								</li>			  
								<li>
									<a href="#">
										Notification
										<span class="badge badge-purple bounceIn animation-delay3 pull-right">2</span>
									</a>
								</li>			  
								<li>
									<a href="#" class="sidebarRight-toggle">
										Message
										<span class="badge badge-success bounceIn animation-delay4 pull-right">7</span>
									</a>
								</li>			  	  
								<li class="divider"></li>
								<li>
									<a href="#">Setting</a>
								</li>			  	  
							</ul>
						</div>
						<a href="javascript:void(0);" onclick="logOut()" class="pull-right font-18"><i class="ion-log-out"></i></a>
					</div>
				</div><!-- sidebar-inner -->
			</div>
			
			<!-- 右侧内容 -->
			<div class="main-container">
				<div class='sidebar-open main-content padding-md'>
					<iframe id='myFrame' src="/admin/mine" style="border: none" width='100%' height='100%' frameborder="no" >

					</iframe>
				<!--/ 右侧内容 -->
				</div>
			</div>

			<div class="footer">
				<span class="footer-brand">
					<strong class="text-danger">Virgo</strong> Admin
				</span>
				<p class="no-margin">
					<?php echo $site["copyright"];?>
				</p>	
			</div>
		</div><!-- /wrapper -->

		<a href="#" class="scroll-to-top hidden-print"><i class="fa fa-chevron-up fa-lg"></i></a>

		<!-- Delete Widget Confirmation -->
		<div class="custom-popup delete-widget-popup delete-confirmation-popup" id="deleteWidgetConfirm">
			<div class="popup-header text-center">
				<span class="fa-stack fa-4x">
				  <i class="fa fa-circle fa-stack-2x"></i>
				  <i class="fa fa-lock fa-stack-1x fa-inverse"></i>
				</span>
			</div>
			<div class="popup-body text-center">
				<h5>Are you sure to delete this widget?</h5>
				<strong class="block m-top-xs"><i class="fa fa-exclamation-circle m-right-xs text-danger"></i>This action cannot be undone</strong>
			
				<div class="text-center m-top-lg">
					<a class="btn btn-success m-right-sm remove-widget-btn">Delete</a>
					<a class="btn btn-default deleteWidgetConfirm_close">Cancel</a>
				</div>
			</div>
		</div>
	
  	</body>

<!-- 模态框（Modal） -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="position:relative;">
                <div class="modalResultBox nosee"><span></span></div>
                <h4 class="modal-title" id="myModalLabel">模态框（Modal）标题</h4>
            </div>
            <div class="modal-body">按下 ESC 按钮退出。</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary submitBtn" btn-purpose="1">应用</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- 提示音加载 -->
<audio id="noticeAudio" src="/notice-mp3/notice.mp3"></audio>

</html>

<?php include '_footer.php'; ?>
<script type="text/javascript" src="/js/myFuncs.js"></script>
<script type="text/javascript" src="/js/notice.js"></script>
<script type="text/javascript">

/**
* 登出
*/
function logOut()
{
	var rs = confirm("是否确定登出");
	if(rs) {
		var postArray = new Array();
		sendAjax(postArray, '/admin/user/logOut', 'true','logOutOk');
	}
}

function logOutOk(data)
{
	if(data['success']){
		window.location.href = '/admin';
	}
}

/**
* 检测密码更新结果
*/
function updateAdminPwdOk(data)
{
	
	if(data['success']){
		//success
		$('.modalResultBox span').text("更新密码成功");
		$(".modalResultBox").removeClass("nosee");
		$(".modalResultBox span").addClass("modalOk");
		//动画
		$('.modalResultBox span').animate({width:"100%"}, 1500, 'swing',function(){
			setTimeout(function(){
				$('.modalResultBox span').css('width', 'auto');
				$(".modalResultBox").addClass("nosee");
				$(".modalResultBox span").removeClass("modalOk");
				window.location.href = '/admin';
				return false;
			},1200);
		});
	} else {

		if(data['code']=='002'){
			window.Location.href = '/admin';
			return false;
		}

		if(data['code']=='028'){
			$('.modalResultBox span').text("原始密码不正确");
		} else {
			$('.modalResultBox span').text("更新失败");
		}
		
		$(".modalResultBox").removeClass("nosee");
		//动画
		$('.modalResultBox span').animate({width:"100%"}, 1500, 'swing',function(){
			setTimeout(function(){
				$('.modalResultBox span').css('width', 'auto');
				$(".modalResultBox").addClass("nosee");
				return false;
			},1200);
		});
	}
}

/**
* 检测修改密码
*/
function checkChangePWD()
{
	
	var orderPwd = $("#orderPwd").val();
	if(orderPwd==''){
		$('.modalResultBox span').text("旧密码不为空");
		$(".modalResultBox").removeClass("nosee");

		//动画
		$('.modalResultBox span').animate({width:"100%"}, 1500, 'swing',function(){
			setTimeout(function(){
				$('.modalResultBox span').css('width', 'auto');
				$("#orderPwd").focus();
				$(".modalResultBox").addClass("nosee");
				return false;
			},1200);
		});
	} else {
		var newerPwd = $("#newerPwd").val();
		if(newerPwd==''){
			$('.modalResultBox span').text("新密码不为空");
			$(".modalResultBox").removeClass("nosee");
			//动画
			$('.modalResultBox span').animate({width:"100%"}, 1500, 'swing',function(){
				setTimeout(function(){
					$('.modalResultBox span').css('width', 'auto');
					$("#newerPwd").focus();
					$(".modalResultBox").addClass("nosee");
					return false;
				},1200);
			});
		} else {
			var confirmPwd = $("#confirmPwd").val();
			if(confirmPwd==''){
				$('.modalResultBox span').text("确认密码不为空");
				$(".modalResultBox").removeClass("nosee");
				//动画
				$('.modalResultBox span').animate({width:"100%"}, 1500, 'swing',function(){
					setTimeout(function(){
						$('.modalResultBox span').css('width', 'auto');
						$("#confirmPwd").focus();
						$(".modalResultBox").addClass("nosee");
						return false;
					},1200);
				});
			} else {
				if(newerPwd!=confirmPwd){
					//提示错误
					$('.modalResultBox span').text("两次输入的密码不相同");
					$(".modalResultBox").removeClass("nosee");
					//动画
					$('.modalResultBox span').animate({width:"100%"}, 1500, 'swing',function(){
						setTimeout(function(){
							$('.modalResultBox span').css('width', 'auto');
							$(".modalResultBox").addClass("nosee");
							return false;
						},1200);
					});
				} else {
					return true;
				}
			}
		}
	}

}

function changeUrl(obj)
{
	//点击改变iframe src
	var currentUrl = $(obj).prop('href');
	if(currentUrl!='#'){

		$('.submenu-label').removeClass("light-tag");
		$(obj).find(".submenu-label").addClass("light-tag");

		var nav = $(obj).attr("data-href");
		$("#myFrame").attr("src",nav);
	}
}

function changeIframeUrl(url)
{
	$("#myFrame").attr("src",url);
}
$(document).ready(function(){

	//Delete Widget Confirmation
	$('#deleteWidgetConfirm').popup({
		vertical: 'top',
		pagecontainer: '.container',
		transition: 'all 0.3s'
	});

	//修改密码
	$(".edit-pwd").click(function(){

		//宽度
		$(".modal-dialog").css({
			"width":"400px"
		});

		//设置文本
		//$("#myModalLabel").css("font-size", "16px");

		$(".modal-title").html("修改密码");

		//title
		$(".modal-title").text("修改密码");

		var html = "<form id='editPWd'><div class='smart-widget-inner'>";
		html = html+"<li class='list-group-item' draggable='false' style='margin-bottom:20px;position:relative;'>旧密码<span class='fa fa-lock' style='position:absolute;top:38px;left:22px;'></span>";
		html = html+"<input class='form-control' type='password' id='orderPwd' placeholder='请输入旧密码' style='padding-left:20px'></li>";
		html = html+"<li class='list-group-item' draggable='false' style='margin-bottom:10px;position:relative;'>新密码<span class='fa fa-lock' style='position:absolute;top:38px;left:22px;'></span>";
		html = html+"<input class='form-control' type='password' id='newerPwd' placeholder='请输入新密码' style='padding-left:20px'></li>";
		html = html+"<li class='list-group-item' draggable='false' style='position:relative;'>确认密码<span class='fa fa-lock' style='position:absolute;top:38px;left:22px;'></span>";
		html = html+"<input class='form-control' type='password' id='confirmPwd' placeholder='请输入确认密码' style='padding-left:20px'></li>";
		html = html+"</div></form>";
		//body
		$(".modal-body").html(html);

		$('#myModal').modal('show');
	});

	//判断高度
	var navBarHeight = $('.nav-container').height();
	var clientHeight = $(window).height();
	$('.sidebar-open').height(clientHeight-navBarHeight);

	
	//提交
	$(".submitBtn").click(function(){
		var purpose = $('.submitBtn').attr("btn-purpose");
		//修改密码
		if(purpose==1){
			//判断动画是否结束
			if(!$(".modalResultBox span").is(":animated")){
				var rs = checkChangePWD();
				if(rs){
					//发送
					var postArray = new Array();
					postArray['orderPwd'] = $("#orderPwd").val();
					postArray['newerPwd'] = $("#newerPwd").val();
					sendAjax(postArray,'/admin/user/updateAdminPwd','false','updateAdminPwdOk');
				}
			}
		}
	});

});
			
</script>
