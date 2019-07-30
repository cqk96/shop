<?php include_once('../app/views/admin/_header.php') ?>
<style type="text/css">
html,body,img,div,p,h1,h2,h3,h4,h5,h6,a {
	padding: 0px;
	margin: 0px;
}
body,html {
	width: 100%;
	height: 100%;
}
.contianer { width: 100%; height: 100%; padding-top: 21px;  }

.left-box { width: 65%; height: 100%;  position: relative; overflow-x: hidden; display: inline-block; overflow-y: scroll; }
.right-box { width: 33%; height: 100%; float: right; position: relative; overflow-x: hidden; border-radius: 5px; background-color: #fff; overflow-y: scroll; }

.info-box { width: 100%; height: 170px; overflow: hidden; position: relative; }
.avatar-with-signature-box { width: 27%; height: 100%; background-color: #FFF; overflow: hidden; float: left; border-top-right-radius: 12px; border-bottom-right-radius: 12px; border-top-left-radius: 5px; border-bottom-left-radius: 5px; }

.avatar-box { width: 67px; height: 67px; margin-top: 33px; overflow: hidden; margin-left: auto; margin-right: auto; position: relative;  }
.avatar-img-box { width: 100%; height: 100%; border-radius: 100%; overflow: hidden; }
.avatar-img { width: 100%; height: auto; }

.nickname { text-align: center; font-size: 11px; color: #333333; margin-top: 10px; font-weight: bold;}
.signature { text-align: center; font-size: 6px; color: #999999; margin-top: 6px; font-weight: bold; }

.other-info-box {width: 72%; height: 100%; float: right; background-color: #FFF; overflow: hidden; position: relative;   padding-left: 24px; border-top-left-radius: 12px; border-bottom-left-radius: 12px; border-top-right-radius: 5px; border-bottom-right-radius: 5px;}

.company-name {font-size: 13px; color: #333333; margin-top: 32px; font-weight: bold; }
.job-box { font-size: 10px; color: #999999; margin-top: 8px; font-weight: bold; }
.position-name { margin-left: 21px; }
.introduce {overflow: hidden; font-size: 10px; color: #666666; padding-right: 29%; margin-top: 26px;     font-weight: bold; }

.edit-btn { position: absolute; top: 10px; right: 10px; width: 26px;}
.gender-img { width: 11px; position: absolute; right: 1px; bottom: 3px; }
.gender-img-1,.gender-img-3 { width: 14px; position: absolute; right: 1px; bottom: 3px; }
.gender-img-2 { width: 11px; position: absolute; right: 1px; bottom: 3px; }

.wait-deal-box {background-color: #FFF; width: 100%; height: 180px; border-radius: 5px; margin-top: 10px; }


/*名称*/
.deal-box-name { font-size: 13px; color: #000000; padding-left: 16px; font-weight: bold; }
.user-dynamics-name { font-size: 13px; color: #000000; padding-left: 16px; font-weight: bold; margin-bottom: 20px;  }
.notice-name  { font-size: 13px; color: #000000; font-weight: bold; position: relative; margin-bottom: 5px; font-weight: bold; }

.deal-choose-box { width: 100%; height: 90px; margin: 20px auto  0px  auto; }

.rest-10 {width: 100%; height: 10px; }
.rest-20 {width: 100%; height: 20px; }
.rest-30 {width: 100%; height: 30px; }

.each-deal-choose-box {width: 33.3333%; height: 100%; float: left;}

.each-deal-choose-img {width: 47px; height: 47px; margin: 10px auto 0 auto;}
.each-deal-choose-img img {width: 100%; height: auto; }

.each-deal-name {font-size: 11px; color: #333333; text-align: center; margin-top: 10px; font-weight: bold; }

.work-num {font-size: 11px; color: #ff782a; margin-left: 8px; }

.user-dynamics { width: 100%; height: auto; border-radius: 5px; background-color: #FFF; margin-top: 10px; overflow-y:  scroll; }

.each-dynamic-box { width: 90%; height: 80px; margin: 0 auto; margin-top: 2%; padding-bottom: 2%; /*border-bottom: 1px solid #ececec;*/ }
.each-dynamic-avatar-box-parent { width: 7%; height: 100%; overflow: hidden; display: inline-block; }
.each-dynamic-avatar-box { width: 100%; height: 100%; overflow: hidden; display: inline-block; }
.each-dynamic-avatar-box img {display: block; width: 100%; border-radius: 100%; }

.each-dynamic-info-box { width: 90%; height: 90%; float: right; border-bottom: 1px solid #ececec;  margin-top: 1%; }

.dynamic-user-name {width: 100%; font-size: 10px; color: #666666; font-weight: bold; }

.dynamic-content { width: 100%; font-size: 10px; color: #333333; margin-top: 6px; font-weight: bold; }
.time-box {font-size: 9px; margin-top: 6px; color: #999999; font-weight: bold; }
.comment-btn { margin-left: 15px; }

.notice-box { width: 90%; border-bottom: 1px solid #ececec; margin: 0 auto; min-height: 230px; }
.more-img { position: absolute; top: 3px; right: 15px; display: block; width: 31.5px; height: 11px; }
.each-notice { padding-top: 8px; padding-bottom: 8px; padding-right: 15px; font-size: 11px; color: #666666;  font-weight: bold; }

.visibility { visibility: hidden; }
</style>
<body>
<div class="contianer">
	<div class="left-box">
		<div class="info-box">
			<div class="avatar-with-signature-box">
				<div class="avatar-box">
					<div class='avatar-img-box'><img class='avatar-img' src="<?php echo empty($data['avatar']) ? '/images/emptylogo.png':$data['avatar'] ?>"></div>
					<img class='gender-img-<?php echo $data['gender']; ?>' src="<?php echo $genderImg; ?>">
				</div>
				<div class='nickname'><?php echo empty($data['nickname']) ? '暂无':$data['nickname'] ?></div>
				<div class='signature'><?php echo empty($data['motto']) ? '暂无':$data['motto'] ?></div>
			</div>

			<div class="other-info-box">
				<div class="company-name">杭州航桓科技有限公司</div>
				<div class="job-box"><?php echo empty($data['name']) ? '暂无':$data['name'] ?>·<?php echo empty($data['section']) ? '暂无':$data['section'] ?> <span class="position-name"><?php echo empty($data['jobName']) ? '暂无':$data['jobName'] ?></span></div>
				<div class="introduce"><?php echo empty($data['introduce']) ? '暂无':$data['introduce'] ?></div>

				<a href="/admin/user/read"><span class="edit-btn"><img src="/images/edit.png"></span></a>
			</div>

		</div>

		<!-- 待办事项 -->
		<div class="wait-deal-box">

			<div class="rest-20"></div>
			<div class='deal-box-name'>待办事项</div>

			<div class='deal-choose-box'>
				<div class="each-deal-choose-box">
					<a href="javascript:void(0);" onclick="alert('暂未开通');">
						<div class="each-deal-choose-img">
							<img src="/images/works.png">
						</div>
					</a>
					<div class='each-deal-name'>待完成工作 <span class="work-num">0</span></div>
				</div>
				<div class="each-deal-choose-box">
					<a href="javascript:void(0);" onclick="alert('暂未开通');">
						<div class="each-deal-choose-img">
							<img src="/images/email.png">
						</div>
					</a>
					<div class='each-deal-name'>待查阅邮件 <span class="work-num">0</span></div>
				</div>
				<div class="each-deal-choose-box">
					<a href="javascript:void(0);" onclick="alert('暂未开通');">
						<div class="each-deal-choose-img">
							<img src="/images/word.png">
						</div>
					</a>
					<div class='each-deal-name'>待提交报告 <span class="work-num">0</span></div>
				</div>
			</div>
		</div>

		<!-- 动态 -->
		<div class="user-dynamics">
			<div class="rest-10"></div>
			<div class='user-dynamics-name'>动态</div>
			<?php for ($i=0; $i < count($dynamics) ; $i++) { ?>
				<div class='each-dynamic-box'>
					<div class="each-dynamic-avatar-box-parent">
						<div class="each-dynamic-avatar-box">
							<img src="<?php echo $dynamics[$i]['avatar']; ?>" />
						</div>
					</div>
					<div class="each-dynamic-info-box">
						<div class="dynamic-user-name"><?php echo $dynamics[$i]['show_name']; ?></div>
						<div class="dynamic-content"><?php echo $dynamics[$i]['content']; ?></div>
						<div class="time-box"><?php echo date("Y-m-d H:i", $dynamics[$i]['create_time']); ?> <!-- <span class="comment-btn">留言</span> --></div>
					</div>
				</div> 
			<?php } ?>

			<div class="rest-20"></div>

		</div>

	</div>

	<div class="right-box">

		<!-- 通知 -->
		<div class="notice-box">
			<div class="rest-30"></div>
			<div class="notice-name"> 公告通知
				<a href="<?php echo empty($notices)? 'javascript:void(0);':'/admin/newsClass/news/more?cid='.$notices[0]['class_id'];?>"><img class="more-img" src="/images/more.png"></a>
			</div>

			<?php for ($i=0; $i < count($notices) ; $i++) { ?>
			<a href="/admin/news/show?id=<?php echo $notices[$i]['id']; ?>"><div class="each-notice"><?php echo $notices[$i]['title']?></div></a>
			<?php } ?>
			<div class="rest-10"></div>
		</div>

		<div class="notice-box" style="border-bottom: none;">
			<div class="rest-30"></div>
			<div class="notice-name"> 条例
				<a href="<?php echo empty($contractProvisions)? 'javascript:void(0);':'/admin/contractProvisions/more';?>"><img class="more-img" src="/images/more.png"></a>
			</div>
			<?php for ($i=0; $i < count($contractProvisions) ; $i++) { ?>
			<a href="/admin/contractProvisions/show?id=<?php echo $contractProvisions[$i]['id']; ?>"><div class="each-notice">《<?php echo $contractProvisions[$i]['name']?>》</div></a>
			<?php } ?>
			<div class="rest-10"></div>
		</div>

	</div>

</div>
</body>
<?php include_once('../app/views/admin/_footer.php') ?>
<script type="text/javascript">

function changeStaffInfo(name, value)
{
	
	var data = new Object();
	data[name] = value;

	var arr = new Object();
	$.ajax({
		url: "/front/api/v1/staff/updateInfo",
		type: "POST",
		dataType: "JSON",
		data: data,
		async: false,
		success: function(response){
			if(response['status']['success']){
				arr['ok'] = true;
				arr['message'] = '';
			} else {
				arr['ok'] = false;
				arr['message'] = response['status']['message'];
			}
		},
		error: function(res){
			// console.log(res.responseText);
		}
	});

	return arr;

}

// 改变签名
function changeSignature(obj)
{
	var afterText = $(obj).val();

	if(afterText=="") {
		$(obj).focus();
		return false;
	}

	var rs = changeStaffInfo("motto", afterText);

	if(rs.ok) {
		$('.signature').html("");
		$('.signature').text(afterText);
	}

}

$(document).ready(function(){
	$('.signature').dblclick(function(){
		var curText = $(this).text();
		var str = "<input id='signatureInput' type='text' class='form-control' value='"+curText+"' onblur='changeSignature(this)' />";
		$(this).html(str);
	});
});

</script>