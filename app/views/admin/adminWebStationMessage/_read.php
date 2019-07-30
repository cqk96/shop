<?php include_once('../app/views/admin/_header.php') ?>
<link rel="stylesheet" type="text/css" href="/js/tools/layer/skin/default/layer.css">
<style type="text/css">
	body {
		background-color: #F5F9FC;
		position: relative;
	}
	.rest-top {
		width: 100%;
		height: 20px;
	}
	.diary-content-box {
		width: 98%;
		margin: 0 auto;
		background-color: #FFF;
	}
	.content-title-box {
		font-size: 18px;
		color: #333333;
		padding-left: 20px;
		padding-right: 20px;
	}
	.tag-rect-box {
		width: 5px;
		height: 20px;
		display: inline-block;
		vertical-align: middle;
		margin-right: 9px;
	}
	.rect-color-1 {
		background-color: #4A90E2 ;
	}
	.rect-color-2 {
		background: #F5A623;
	}

	.diary-true-content {
		font-size: 14px;
		color: #666666;
		padding-left: 40px;
		padding-right: 40px;	
		text-align: justify;
	}
	.total-approval-box {
		width: 98%;
		margin: 0 auto;
		background-color: #FFF;	
	}

	.row {
		margin-left: 0px;
		margin-right: 0px;
	}

	.master-evaluation-box, .political-instructor-evaluation-box,.branch-secretary-evaluation-box,.leader-evaluation-box  {
	 	padding-left: 40px;
    	padding-right: 40px;
	}
	.approval-td {
	 	    padding-bottom: 20px;
	 }
	.total-approval-box label {

	    vertical-align: top;
	    display: inline-block;
	    margin-right: 2.2%;

	}
	.total-approval-box textarea {
		width: 100%;
    	display: inline-block;
    	padding: 18px 20.16px 17.1px 21.6px;
	}
	.total-approval-box table {
		width: 94%;
		/*border: 1px solid black;*/
		margin: 0 auto;
	}

	textarea {
		resize: none;
	}

	.submit-btn {
		float: right;
		margin-left: 40px;
	}
	.submit-btn:hover {
		cursor: pointer;
	}
	.return-btn {
		float: right;	
		
	}

	/*已评论*/
	.has-approval {
		background-color: #f2f2f2;
	}

	/*占位*/
	.rest-20 {
		width: 100%;
		height: 20px;
	}
	.rest-40 {
		width: 100%;
		height: 40px;
	}

	.item-info {
		margin-right: 10px;
	}
	.info-item {
		margin-bottom: inherit;
	}
	.creator-info {
		padding-left: 20px;
    	padding-right: 20px;
    	font-size: 14px;
		color: #666666;
		letter-spacing: 0;
	}
	.info-icon {
		margin-right: 7px;
		vertical-align: bottom;
	}

	.operate-btn-bar {
		position: absolute;
		width: 100%;
		height: 30px;
		bottom: 42px;
	}

	.creator-info .row .col-md-2 {
		display: inline-block;
	}
	.confirm-btn {
		display: block;
		margin: 0 auto;
	}
	.confirm-btn:hover {
		cursor: pointer;
	}
</style>
<body>
<div class="contianer">

	<div class="rest-top"></div>

	<!-- 日志内容 -->
	<div class="diary-content-box">

		<div class="rest-20"></div>
		<div class='content-title-box'>
			<div class="tag-rect-box rect-color-1"></div>
			消息详情
		</div>
		<div class="rest-20"></div>

		<div class='creator-info'>
			<div class='row'>
				<div class="col-md-2">
					<img class='info-icon' src="/images/creator.png">
					<label class="info-item"><?php echo empty($user) || empty($user['name'])? '未知':$user['name']; ?></label>
				</div>
				<div class="col-md-2">
					<img class='info-icon' src="/images/shijian.png">
					<label class="info-item"><?php echo empty($data['create_time'])? '未知':date("Y-m-d", $data['create_time']); ?></label>
				</div>
			</div>
		</div>
		<div class="rest-20"></div>

		<div class="diary-true-content">
			<label class="item-info">详情: </label>
			<?php echo $data['content']; ?>
		</div>
		<div class="rest-40"></div>

	</div>

</div>
	
	<div class='operate-btn-bar'>
		<img class='confirm-btn' src="/images/confirm.png" onclick="window.location.href='/admin/webStationMessages' ">
	</div>

</body>
<?php include_once('../app/views/admin/_footer.php') ?>
<script type="text/javascript" src="/js/tools/layer/layer.js"></script>
<script type="text/javascript">

// 提示信息
function autoMessageNotice(content)
{
	var time =  arguments[1] ? arguments[1] : 2000;//设置参数b的默认值为2 
	layer.open({
		id: 1,
	    content: content,
	    skin: 'msg',
	    time: time //2m秒后自动关闭
	});
}

$(document).ready(function(){

});

</script>