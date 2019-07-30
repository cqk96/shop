<!DOCTYPE html>
<html lang="en">
  <head>
		<title><?php echo $site["site_name"];?></title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="keywords" content="<?php echo $site["keywords"];?>">
		<meta name="description" content="<?php echo $site["description"];?>">
		<link rel="shortcut icon" href="/favicon.ico" /> 
		<link rel="stylesheet" type="text/css" href="../timber/css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="../timber/css/font-awesome.css">
		<link rel='stylesheet' id='camera-css'  href='../timber/css/camera.css' type='text/css' media='all'>

		<link rel="stylesheet" type="text/css" href="../timber/css/slicknav.css">
		<link rel="stylesheet" href="../timber/css/prettyPhoto.css" type="text/css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="../timber/css/style.css">
		<link rel="stylesheet" type="text/css" href="/css/fancybox/jquery.fancybox.css" /> 
		<link href="/css/automail.css" rel="stylesheet">
		
		<style>
			.slicknav_menu:before{
			float: left;
			height: 40px;
			background: url(../images/logo1.png)no-repeat;
			width: 110px;
			margin-left: 7px;
			margin-top: 7px;
			}
			.resetfont{
				
				text-transform:uppercase;
				color:#fff;
				font-size: 14px;
				text-align:center;
				font-family: 'Roboto', 'sans-serif';
				font-weight: 700;
			}			
		</style>
		
		<script type="text/javascript" src="../timber/js/jquery-1.8.3.min.js"></script>
		
		
		
		<script>
			var _hmt = _hmt || [];
			(function() {
			  var hm = document.createElement("script");
			  hm.src = "//hm.baidu.com/hm.js?150441474aa59862a446d6942d4760b9";
			  var s = document.getElementsByTagName("script")[0]; 
			  s.parentNode.insertBefore(hm, s);
			})();
		</script>

		<script type="text/javascript" src="../timber/js/jquery.mobile.customized.min.js"></script>
		<script type="text/javascript" src="../timber/js/jquery.easing.1.3.js"></script> 
		<script type="text/javascript" src="../timber/js/camera.min.js"></script>
		<script type="text/javascript" src="../timber/js/myscript.js"></script>
		<script src="../timber/js/sorting.js" type="text/javascript"></script>
		<script src="../timber/js/jquery.isotope.js" type="text/javascript"></script>
		<script src="../js/jquery.form.js" type="text/javascript"></script>
		<!--script type="text/javascript" src="../timber/js/jquery.nav.js"></script-->
		

		<script>
			jQuery(function(){
					jQuery('#camera_wrap_1').camera({
					transPeriod: 500,
					time: 3000,
					height: '490px',
					thumbnails: false,
					pagination: true,
					playPause: false,
					loader: false,
					navigation: false,
					hover: false
				});
			});
		</script>
		
	</head>
	<body>
    
    <!--home start-->
    
    <div id="home">
    	<div class="headerLine">
	<div id="menuF" class="default">
		<div class="container">
			<div class="row">
				<div class="logo col-md-4">
					<div>
						<a href="#">
							<img style="max-height: 70px;" src="<?php echo $site["logo"];?>">
						</a>
					</div>
				</div>
				<div class="col-md-8">
					<div class="navmenu"style="text-align: center;">
						<ul id="menu">
							<?php 
								for($i=0;$i<sizeof($nav_list);$i++){
									if($i==0){
										echo "<li class=\"active\" ><a href=\"".$nav_list[$i]["url"]."\">".$nav_list[$i]["text"]."</a></li>";
									}else{
										echo "<li><a class='' href=\"".$nav_list[$i]["url"]."\">".$nav_list[$i]["text"]."</a></li>";
									}
									
								}
							?>
							
							<?php
								if($site['register_type']!=0){
									echo "<li><a class='redirect' href=\"/admin\">登录</a></li>";
								}
							?>
							
		
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
		<div class="container">
			<div class="row wrap">
				<div class="col-md-12 gallery"> 
						<div class="camera_wrap camera_white_skin" id="camera_wrap_1">
							<div data-thumb="" data-src="../timber/images/slides/blank.gif">
								<div class="img-responsive camera_caption fadeFromBottom">
									<h2><?php echo $pieceModel->find(1)['content'];?></h2>
								</div>
							</div>
							<div data-thumb="" data-src="../timber/images/slides/blank.gif">
								<div class="img-responsive camera_caption fadeFromBottom">
									<h2><?php echo $pieceModel->find(2)['content'];?></h2>
								</div>
							</div>
							<div data-thumb="" data-src="../timber/images/slides/blank.gif">
								<div class="img-responsive camera_caption fadeFromBottom">
									<h2><?php echo $pieceModel->find(3)['content'];?></h2>
								</div>
							</div>
						</div><!-- #camera_wrap_1 -->
				</div>
			</div>
		</div>
	</div>
		<div class="container">
			<div class="row">
				<?php for($i=0;$i<count($first_lists);$i++){?>
					<div class="col-md-4 project">
						<h3 id="<?php echo $first_lists[$i]['parameter']?>">0</h3>
						<h4><?php echo empty($first_lists[$i]['main_title'])?'':$first_lists[$i]['main_title']?></h4>
						<p><?php echo empty($first_lists[$i]['main_content'])?'':$first_lists[$i]['main_content']?></p>
					</div>
				<?php } ?>
			</div>
		</div>
		<div class="container">
			<div class="row">
				<div class="col-md-12 cBusiness">
					<h3><?php echo $pieceModel->find(4)['content'];?></h3>
					<h4><?php echo $pieceModel->find(5)['content'];?></h4>
				</div>
			</div>
		</div>
		<div class="container">
			<div class="row">
				<div class="col-md-12 centPic">
					<img class="img-responsive"  src="<?php echo $pieceModel->find(6)['content'];?>"/>
				</div>
			</div>
		</div>   
    </div>
    
    <!--contact start-->
    
    <div id="contact">
    	<div class="line5">					
			<div class="container">
				<div class="row Ama">
					<div class="col-md-12">
					<h3><?php echo $pieceModel->find(7)['content'];?></h3>
					<p><?php echo $pieceModel->find(8)['content'];?></p>
					</div>
				</div>
			</div>
		</div>
		<div class="container">
			<div class="row">
				<div class="col-md-9 col-xs-12 forma">
					<form id="applicationForm" action="/application/create" method="post" onsubmit="return submitApplication();">
						<input type="text" class="col-md-6 col-xs-12 name" name='name' placeholder='姓名 *'/>
						<input type="text" class="col-md-6 col-xs-12 Email" name='email' placeholder='Email *'/>
						<input type="text" class="col-md-12 col-xs-12 Subject" name='subject' placeholder='主题'/>
						<textarea type="text" class="col-md-12 col-xs-12 Message" name='content' placeholder='内容 *'></textarea>
						<div class="cBtn col-xs-12">
							<ul>
								<li class="clear"><a href="javascript:void(0)" onclick="resetApplicationForm();"><i class="fa fa-times"></i>清空</a></li>
								<li class="send"><a href="javascript:void(0)" onclick="return submitApplication();"><i class="fa fa-share"></i>发送</a></li>
							</ul>
						</div>
					</form>
					<script>
						var canApply = true;
						function submitApplication(){
							// jquery 表单提交  
							if(canApply){
								canApply = false;
								$("#applicationForm").ajaxSubmit(function(rs) {  
									var result = JSON.parse(rs);
									alert(result.message);
									canApply = true;
								});  
							}
							
							  
							return false; // 必须返回false，否则表单会自己再做一次提交操作，并且页面跳转  
						}
						
						function resetApplicationForm(){
							$("#applicationForm input").val("");
							$("#applicationForm textarea").val("");
							$("#applicationForm textarea").html("");
						}
						function distips(){
							alert("该功能尚未开放");
						}
					</script>
				</div>
				<div class="col-md-3 col-xs-12 cont">
					<ul>
						<li><i class="fa fa-home"></i><?php echo $site["address"];?></li>
						<li><i class="fa fa-phone"></i><?php echo $site["phone"];?></li>
						<li><a href="mailto:<?php echo $site["email"];?>"><i class="fa fa-envelope"></i><?php echo $site["email"];?></li></a>
					</ul>
				</div>
			</div>
		</div>
		<div class="line6">
					<iframe src="/map" width="100%" height="750" frameborder="0" style="border:0"></iframe>			
		</div>
		<div class="container">
			<div class="row ftext">
				<div class="col-md-12">
				<a id="features"></a>
				<h3><?php echo $pieceModel->find(9)['content'];?></h3>
				<p><?php echo $pieceModel->find(10)['content'];?></p>
				</div>
				<div class="cBtn">
					<ul style="margin-top: 23px; margin-bottom: 0px; padding-left: 26px;">
						<a href="images/qrcode-dy.png" class="fancybox resetfont"><li class="dowbload"><i class="fa fa-qrcode"></i>订阅号二维码</li></a>
						<a href="images/qrcode-fw.png" class="fancybox resetfont"><li class="dowbload"><i class="fa fa-qrcode"></i>服务号二维码</li></a>
					</ul>
			</div>
			</div>
		</div>
		<div class="line7">
			<div class="container">
				<div class="row footer">
					<div class="col-md-12">
						<h3><?php echo $pieceModel->find(11)['content'];?></h3>
						<p><?php echo $pieceModel->find(12)['content'];?></p>
						<div class="fr">
						<div style="display: inline-block;">
							<input  class="col-md-6 fEmail" name='Email' placeholder='请输入您的Email'/>
							<a href="javascript:;" class="subS showtip">订阅!</a>
						</div>
						</div>
					</div>
					<div class="soc col-md-12">
						<ul>
							<li class="soc1 showtip"><a href="javascript:;"></a></li>
							<li class="soc2 showtip"><a href="javascript:;"></a></li>
							<li class="soc3 showtip"><a href="javascript:;"></a></li>
							<li class="soc4 showtip"><a href="javascript:;"></a></li>
							<li class="soc5 showtip"><a href="javascript:;"></a></li>
							<li class="soc6 showtip"><a href="javascript:;"></a></li>
							<li class="soc7 showtip"><a href="javascript:;"></a></li>
							<li class="soc8 showtip"><a href="javascript:;"></a></li>
							
						</ul>
					</div>
				</div>
			</div>
		</div>
		<div class="lineBlack">
			<div class="container">
				<div class="row downLine">
					<div class="col-md-12 text-right">
						
					</div>
					<div class="col-md-6 text-left copy">
						<p><?php echo $site["copyright"];?></p>
					</div>
					<div class="col-md-6 text-right dm">
						<ul id="downMenu">
							<?php 
								for($i=0;$i<sizeof($nav_list);$i++){
									if($i==0){
										echo "<li class=\"active\" ><a href=\"".$nav_list[$i]["url"]."\">".$nav_list[$i]["text"]."</a></li>";
									}else if($i==sizeof($nav_list)-1){
										echo "<li class=\"last\"><a href=\"".$nav_list[$i]["url"]."\">".$nav_list[$i]["text"]."</a></li>";
									}else{
										echo "<li><a href=\"".$nav_list[$i]["url"]."\">".$nav_list[$i]["text"]."</a></li>";
									}
									
								}
							?>
						</ul>
					</div>
				</div>
			</div>
		</div>
    </div>		
		
		
	<script src="../timber/js/jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"></script>
	<script src="../timber/js/bootstrap.min.js"></script>
	<script src="../timber/js/jquery.slicknav.js"></script>
	<script type="text/javascript" src="/js/automail/autoMail.js"></script>
	<script type="text/javascript" src="/js/jquery.fancybox.js"></script> 
	<script>

			$(document).ready(function(){
			$(".bhide").click(function(){
				$(".hideObj").slideDown();
				$(this).hide(); //.attr()
				return false;
			});
			$(".bhide2").click(function(){
				$(".container.hideObj2").slideDown();
				$(this).hide(); // .attr()
				return false;
			});
			$('.showtip').click(function(){
				alert("该功能尚未开放");
			});
			$('.showtip2').click(function(){
				alert("暂时未完成");
			});
			$('#email').autoMail({
					emails:['hanghuan.com','qq.com','163.com','126.com','sina.com','sohu.com','yahoo.cn','gmail.com','hotmail.com']
			});		
			$( ".fancybox").fancybox();			
			$('.heart').mouseover(function(){
					$(this).find('i').removeClass('fa-heart-o').addClass('fa-heart');
				}).mouseout(function(){
					$(this).find('i').removeClass('fa-heart').addClass('fa-heart-o');
				});
				
				function sdf_FTS(_number,_decimal,_separator)
				{
				var decimal=(typeof(_decimal)!='undefined')?_decimal:2;
				var separator=(typeof(_separator)!='undefined')?_separator:'';
				var r=parseFloat(_number)
				var exp10=Math.pow(10,decimal);
				r=Math.round(r*exp10)/exp10;
				rr=Number(r).toFixed(decimal).toString().split('.');
				b=rr[0].replace(/(\d{1,3}(?=(\d{3})+(?:\.\d|\b)))/g,"\$1"+separator);
				r=(rr[1]?b+'.'+rr[1]:b);

				return r;
}
				
			setTimeout(function(){
					$('#counter').text('0');
					$('#counter1').text('0');
					$('#counter2').text('0');
					setInterval(function(){
						
						var curval=parseInt($('#counter').text());
						var curval1=parseInt($('#counter1').text().replace(' ',''));
						var curval2=parseInt($('#counter2').text());
						if(curval<22){
							$('#counter').text(curval+1);
						}
						if(curval1<17){
							$('#counter1').text(sdf_FTS((curval1+20),0,' '));
						}
						if(curval2<30){
							$('#counter2').text(curval2+1);
						}
					}, 2);
					
				}, 500);
				
			});
	</script>
	<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery('#menu').slicknav();
		
	});
	</script>
	
	<script type="text/javascript">
    $(document).ready(function(){
       
        var $menu = $("#menuF");
            
        $(window).scroll(function(){
            if ( $(this).scrollTop() > 100 && $menu.hasClass("default") ){
                $menu.fadeOut('fast',function(){
                    $(this).removeClass("default")
                           .addClass("fixed transbg")
                           .fadeIn('fast');
                });
            } else if($(this).scrollTop() <= 100 && $menu.hasClass("fixed")) {
                $menu.fadeOut('fast',function(){
                    $(this).removeClass("fixed transbg")
                           .addClass("default")
                           .fadeIn('fast');
                });
            }
        });
	});
    //jQuery
	</script>
	<script>
		/*menu*/
		function calculateScroll() {
			var contentTop      =   [];
			var contentBottom   =   [];
			var winTop      =   $(window).scrollTop();
			var rangeTop    =   200;
			var rangeBottom =   500;
			$('.navmenu').find('a:not(.redirect)').each(function(){
				// if($(this).attr('href')!="/admin"){
					contentTop.push( $( $(this).attr('href') ).offset().top );
					contentBottom.push( $( $(this).attr('href') ).offset().top + $( $(this).attr('href') ).height() );
				// }
			})
			$.each( contentTop, function(i){
				if ( winTop > contentTop[i] - rangeTop && winTop < contentBottom[i] - rangeBottom ){
					$('.navmenu li')
					.removeClass('active')
					.eq(i).addClass('active');				
				}
			})
		};
		
		$(document).ready(function(){
			calculateScroll();
			$(window).scroll(function(event) {
				calculateScroll();
			});
			$('.navmenu ul li a:not(.redirect)').click(function() {  
				$('html, body').animate({scrollTop: $(this.hash).offset().top - 80}, 800);
				return false;
			});
		});		
	</script>	
	<script type="text/javascript" charset="utf-8">

		jQuery(document).ready(function(){
			jQuery(".pretty a[rel^='prettyPhoto']").prettyPhoto({animation_speed:'normal',theme:'light_square',slideshow:3000, autoplay_slideshow: true, social_tools: ''});
			
		});
	</script>
	</body>
	
</html>