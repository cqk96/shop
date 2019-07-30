<tfoot>
<!--适用于7格布局-->
		<tr>
			<td colspan='7'>
				<?php echo $pageObj->pagination; ?>
			</td>
		</tr>
		<tr>
			<th> <a href="javascript:void(0);" class='allCheckBox btn btn-default btn-sm'>全选</a> </th>
			<th> <a href="javascript:void(0);" class='deleteChooseBtn btn btn-danger btn-sm'>删除</a> </th>
			<th colspan=4></th>
			<td id='user_num' class="text-right">共<?php echo $pageObj->totalCount;?>条记录</td>
		</tr>
</tfoot>