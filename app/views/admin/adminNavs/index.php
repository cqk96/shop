<?php include_once('../app/views/admin/_header.php') ?>
<div class='main-container'>

<div class="smart-widget">
	<div class="smart-widget-header">
		<?php echo $page_title;?>
		<span class="smart-widget-option">
			<a href="/admin/nav/create">
				<i class="fa fa-plus"></i>
			</a>
			<a href="#" onclick="location.reload()" class="widget-refresh-option">
				<i class="fa fa-refresh"></i>
			</a>
		</span>
	</div>
	<div class="smart-widget-inner table-responsive">
		<table class="table table-striped no-margin">
			<thead>
				<tr class='firstLine'>
					<th class="text-right" width="1%"></th>
					<th class="text-right" width="1%">Id</th>
					<th class="text-right">显示文字</th>
					<th class="text-right">跳转链接</th>
					<th class="text-right">是否显示</th>
					<th class="text-right">排序</th>
					<th class="text-right">操作</th>
				</tr>
			</thead>
			<tbody>
				<?php for($i=0; $i<count($nav_list); $i++){ ?>
				<tr>
					<td class="text-right"><input type='checkbox' name='classesIds[]' class='eachNewsClassCheckBox' value="<?php echo $nav_list[$i]['id'] ?>" /></td>
					<td class="text-right"><?php echo ($record_start+$i+1);?></td>
					<td class="text-right"> <?php echo empty($nav_list[$i]['text'])? '该栏目已经被删除':$nav_list[$i]['text']; ?> </td>
					<td class="text-right"> <?php echo $nav_list[$i]['url']; ?> </td>
					<td class="text-right"> <?php echo $nav_list[$i]['show']==1?"是":"否"; ?> </td>
					<td class="text-right"> <?php echo $nav_list[$i]['order']; ?> </td>
					<td class='text-right operationBox'>
						<a type='button' href="/admin/nav/update?id=<?php echo $nav_list[$i]['id']; ?>" class='btn btn-default btn-sm'>编辑</a>
						<!-- <a type='button' href="/admin/news/read?id=<?php echo $nav_list[$i]['id']; ?>" class='btn btn-info btn-sm'>Read</a> -->
						<a type='button' href="/admin/nav/delete?id=<?php echo $nav_list[$i]['id']; ?>" onclick="return confirm('你确定要删除吗？')" class='btn btn-danger btn-sm'>删除</a>
					</td>
				</tr>
				<?php } ?>

			</tbody>
			<?php include_once('../app/views/admin/adminNavs/_tfoot.php') ?>
		</table>
	</div><!-- ./smart-widget-inner -->
</div><!-- ./smart-widget -->


</div>
<?php include_once('../app/views/admin/_footer.php') ?>