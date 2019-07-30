<?php include_once('../app/views/admin/_header.php') ?>
 <link rel='stylesheet' type='text/css' href='/css/admin/index.css'>
 <div class='main-container'>
 <div class='smart-widget'>
	 <div class='smart-widget-header'>
		 <?php echo $page_title;?>
		<span class='smart-widget-option'>
			 <a href='/admin/instructionsMessages/create'>
				 <i class='fa fa-plus'></i>
			 </a>
			 <a href='#' onclick='location.reload()' class='widget-refresh-option'>
				 <i class='fa fa-refresh'></i>
			 </a>
		 </span>
		<form class='searchForm' action='instructionsMessages' method='get'>
			<input type='text' name='title' value='<?php echo empty($_GET['title'])? '':$_GET['title']; ?>' placeholder='请输入文章标题' />
			<input type='text' name='startTime' id='startTime' value='<?php echo empty($_GET['startTime'])? '':$_GET['startTime'] ?>' placeholder='请输入起始时间' />
			-
			<input type='text' name='endTime' id='endTime' value='<?php echo empty($_GET['endTime'])? '':$_GET['endTime'] ?>' placeholder='请输入结束时间' />
			<button class='btn btn-primary btn-sm' type='submit'>提交</button>
		</form>
		
	 </div>
	 <table id='indexTable' class=''>
		 <thead>
			 <tr class='firstLine'>
				 <th class=''><input type='checkbox' name='chooseAll' class='allCheckBox chooseAll'></th>
				 <th>id</th>
				<th>content</th>
				<th>author_id</th>
				<th>is_pushed</th>
				<th>create_time</th>
				<th>update_time</th>
				
				 <!-- <th class=''>用户类型</th> -->
				 <th class='' colspan=2>操作</th>
			 </tr>
		 </thead>
		 <tbody>
			 <?php for($i=0; $i<count($data); $i++){ ?>
			 <tr class='<?php echo $i%2==0? 'singular':'dual' ?>'>
				<td class=''>
					<input type='checkbox' name='classesIds[]' class='eachNewsClassCheckBox' value='<?php echo $data[$i]['id'] ?>' /></td>
				<td><?php echo $data[$i]['id'];?></td>
				<td><?php echo $data[$i]['content'];?></td>
				<td><?php echo $data[$i]['author_id'];?></td>
				<td><?php echo $data[$i]['is_pushed'];?></td>
				<td><?php echo $data[$i]['create_time'];?></td>
				<td><?php echo $data[$i]['update_time'];?></td>
				
				 <td class='operationBox' colspan=2>
					 <a href='/admin/instructionsMessages/update?id=<?php echo $data[$i]['id']; ?>' ><span class='icon-img'><img src='/images/edit-icon.png' /></span>编辑</a>
					 <a type='button' href='/admin/instructionsMessages/doDelete?id=<?php echo $data[$i]['id']; ?>' onclick='return confirm("你确定要删除吗？")'><span class='icon-img'><img src='/images/delete-icon.png'></span>删除</a>
				 </td>
			 </tr>
			 <?php } ?>
		 </tbody>
		 <?php include_once('../app/views/admin/adminInstructionsMessage/_tfoot.php') ?>
	 </table>
 </div><!-- ./smart-widget -->
 </div>
 <?php include_once('../app/views/admin/_footer.php') ?>
 <script type='text/javascript' src='/js/myFuncs.js'></script> 
 <script type='text/javascript' src='/js/date-picker/jquery-ui.min.js'></script> 
 <script type='text/javascript'>
 function showDeleteItems(data)
 {
	 if(!data.success){alert('删除失败'); }
	else {window.location.reload(); }
	 }
 $(document).ready(function(){
	 //全选checkbox
	 var allCheckBoxBtnClick = 1;
	 $('.allCheckBox').click(function(){
		 if(allCheckBoxBtnClick%2!=0){
			 $('.eachNewsClassCheckBox').each(function(){
				 $(this).prop('checked',true);
			 });
		 } else{
			 $('.eachNewsClassCheckBox').each(function(){
				 $(this).prop('checked',false);
			 });
		 }
		 allCheckBoxBtnClick++;
	 });
	 //删除选择的项目
	 $('.deleteChooseBtn').click(function(){
		 deleteChooseItems('eachNewsClassCheckBox','你确定要删除选定项吗？','/admin/instructionsMessages/destroy','','showDeleteItems','');
	 });
	//日期选择
	$( '#startTime' ).datepicker({dateFormat:'yy-mm-dd'});
	$( '#endTime' ).datepicker({dateFormat:'yy-mm-dd'});
 });
 </script>