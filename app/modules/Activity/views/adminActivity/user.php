<?php include_once('../app/views/admin/_header.php') ?>
<div class='main-container'>
	 <form id='myForm' method="POST" action="/admin/application/user/update">
		 <div class='smart-widget'>
			 <div class='smart-widget-header'>
			 <div class='smart-widget-inner'>
				 <ul class='list-group to-do-list sortable-list no-border'>
				 	<input type="hidden" name="uid" value="<?php echo $_GET['id']; ?>" />
				 	<input type="hidden" name="aid" value="<?php echo $_GET['aid']; ?>" />

					<li class='list-group-item' draggable='false'>
						姓名
						<input class="form-control" name="name" required type="text" value="<?php echo empty($data['name'])? '':$data['name'];?>">
					</li>
					<li class='list-group-item' draggable='false'>
						手机号
						<input class="form-control" name="user_login" required type="text" value="<?php echo empty($data['user_login'])? '':$data['user_login'];?>">
					</li>
					<li class='list-group-item' draggable='false'>
						监护人手机号
						<input class="form-control" name="parent_phone" required type="text" value="<?php echo empty($data['parent_phone'])? '':$data['parent_phone'];?>">
					</li>
					<li class='list-group-item' draggable='false'>
						性别
						<select name="gender" class="form-control">
							<option value="1" <?php if(!empty($data['gender']) && $data['gender']==1) { echo "selected"; } ?> >男</option>
							<option value="2" <?php if(!empty($data['gender']) && $data['gender']==2) { echo "selected"; } ?> >女</option>
						</select>
					</li>
					<li class='list-group-item' draggable='false'>
						校区
						<input class="form-control" name="campus" required type="text" value="<?php echo empty($data['campus'])? '':$data['campus'];?>">
					</li>
					<li class='list-group-item' draggable='false'>
						年级
						<input class="form-control" name="grade" required type="text" value="<?php echo empty($data['grade'])? '':$data['grade'];?>">
					</li>
					<li class='list-group-item' draggable='false'>
						身高
						<input class="form-control" name="height" required type="text" value="<?php echo empty($data['height'])? '':$data['height'];?>">
					</li>
					<li class='list-group-item' draggable='false'>
						体重
						<input class="form-control" name="weight" required type="text" value="<?php echo empty($data['weight'])? '':$data['weight'];?>">
					</li>
					<li class='list-group-item' draggable='false'>
						身份证
						<input class="form-control" name="IDCard" required type="text" value="<?php echo empty($data['IDCard'])? '':$data['IDCard'];?>">
					</li>
					<li class='list-group-item' draggable='false'>
						是否缴费
						<select name="orderStatus" class="form-control">
							<option value="0" <?php if(!empty($data['status']) && $data['status']=="未支付"){ echo "selected"; } ?> >未缴费</option>
							<option value="1" <?php if(!empty($data['status']) && $data['status']=="已支付"){ echo "selected"; } ?> >已缴费</option>
						</select>
					</li>
					<li class='list-group-item' draggable='false'>
						监护人姓名
						<input class="form-control" name="parent_name" required type="text" value="<?php echo empty($data['parent_name'])? '':$data['parent_name'];?>">
					</li>
					<li class='list-group-item' draggable='false'>
						监护人微信号
						<input class="form-control" name="parent_wechat" required type="text" value="<?php echo empty($data['parent_wechat'])? '':$data['parent_wechat'];?>">
					</li>
					<li class='list-group-item' draggable='false'>
						护照号
						<input class="form-control" name="passport_num" type="text" value="<?php echo empty($data['passport_num'])? '':$data['passport_num'];?>">
					</li>
					<li class='list-group-item' draggable='false'>
						信仰
						<input class="form-control" name="faith" type="text" value="<?php echo empty($data['faith'])? '':$data['faith'];?>">
					</li>
					<li class='list-group-item' draggable='false'>
						过敏信息
						<input class="form-control" name="allergic_message" type="text" value="<?php echo empty($data['allergic_message'])? '':$data['allergic_message'];?>">
					</li>
					<li class='list-group-item' draggable='false'>
						健康状态
						<input class="form-control" name="health_condition" type="text" value="<?php echo empty($data['health_condition'])? '':$data['health_condition'];?>">
					</li>
					<li class='list-group-item' draggable='false'>
						备注
						<input class="form-control" name="remind" type="text" value="<?php echo empty($data['remind'])? '':$data['remind'];?>">
					</li>
					<li class='list-group-item' draggable='false'>
						<a href="/admin/activitys" class="btn btn-default">返回</a>
						<button type="submit" class="btn btn-primary">提交</button>
					</li>
			 </div>
		 </div>
	 </form>
 </div>
<?php include_once('../app/views/admin/_footer.php') ?>