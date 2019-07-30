<?php include_once('../app/views/admin/_header.php') ?>
<div class='main-container'>
<table id='indexTable' style='padding:20px !important;'>
	<caption><?php echo $page_title; ?><a class='tableAddActionBtn btn btn-primary btn-sm' href="/admin/news/create">Add</a></caption>
	<thead>
		<tr><h1><?php echo $page_sub_title; ?></h1></tr>
	</thead>
	<tbody>
		<tr class='firstLine'>
			<th></th>
			<th>Id</th>
			<th>所属栏目名称</th>
			<th>文章标题</th>
			<th>修改时间</th>
			<th>Operation</th>
		</tr>
		<?php for($i=0; $i<count($news); $i++){ ?>
		<tr>
			<td width='5%'><input type='checkbox' name='classesIds[]' class='eachNewsClassCheckBox' value="<?php echo $news[$i]['id'] ?>" /></td>
			<td width='10%'><?php echo $news[$i]['id'] ?></td>
			<td width='20%'> <?php echo empty($news[$i]['class_name'])? '该栏目已经被删除':$news[$i]['class_name']; ?> </td>
			<td width='20%'> <?php echo $news[$i]['title']; ?> </td>
			<td width='20%'> <?php echo $news[$i]['updated_at']; ?> </td>
			<td class='operationBox' width='25%'>
				<a type='button' href="/admin/news/update?id=<?php echo $news[$i]['id']; ?>" class='btn btn-default btn-sm'>Edit</a>
				<!-- <a type='button' href="/admin/news/read?id=<?php echo $news[$i]['id']; ?>" class='btn btn-info btn-sm'>Read</a> -->
				<a type='button' href="/admin/news/delete?id=<?php echo $news[$i]['id']; ?>" onclick="return confirm('你确定要删除吗？')" class='btn btn-danger btn-sm'>Delete</a>
			</td>
		</tr>
		<?php } ?>
	</tbody>
	<tfoot>
		<tr>
			<td class='operationBox'><button type='button' class='allCheckBox btn btn-sm'>全选</button></td>
			<td class='operationBox'><button type='button' class='deleteChooseBtn btn btn-danger btn-sm'>Delete</button></td>
		</tr>
	</tfoot>
</table>
</div>
<?php include_once('../app/views/admin/_footer.php') ?>