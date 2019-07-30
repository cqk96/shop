<?php include_once('../app/views/admin/_header.php') ?>
<h1><?php echo $page_title; ?></h1>
    <input type='hidden' name='id' value="<?php echo empty($news['id'])? '':$news['id'];  ?>" />
	<input type='hidden' name='cover' value='<?php echo empty($news['cover'])? '':$news['cover'];  ?>' />
	<li class="list-group-item" draggable="false">
	    所属栏目:　

	        <select name="class_id" class='form-control'>
	            <?php for($i=0; $i<count($newsClasses); $i++){ ?>
	            <option value='<?php echo $newsClasses[$i]['id']; ?>' <?php if($news['class_id']==$newsClasses[$i]['id']) echo "selected"; ?> ><?php echo $newsClasses[$i]['class_name']; ?></option>
	            <?php } ?>
	        </select>
	    
	</li>
	<li class="list-group-item" draggable="false">

	    文章标题:　
	    <input type='text' name='title' class='title form-control' id='title' value='<?php echo empty($news['title'])? '':$news['title'];  ?>' placeholder='请输入文章标题' />
	</li>
	<li class="list-group-item" draggable="false">

	    文章封面:　
	    
	        <?php
	            if(!empty($news['showCover'])) {
	            	echo "<a href='".$news['showCover']."' target='_blank' class='btn btn-info btn-xs'>查看封面</a>";
	            }
	        ?>
	    
	</li>
	<li class="list-group-item" draggable="false">

	    关键字:　
	    <input type='text' class='form-control' name='keywords' placeholder='请输入文章关键字（多个则以空格隔开）' value='<?php echo empty($news['keywords'])? '': $news['keywords']; ?>' >
	</li>
	<li class="list-group-item" draggable="false">

	    文章描述:　
	    <textarea name='description' class='form-control' placeholder="请输入文章描述"><?php echo empty($news['description'])? '': $news['description']; ?></textarea>
	</li>
	<li class="list-group-item" draggable="false">

	    文章起源:　
	    <input type="text" name='origin' class='form-control' placeholder="请输入文章起源" value="<?php echo empty($news['origin'])? '': $news['origin']; ?>" />
	</li>
	<li class="list-group-item" draggable="false">

	    文章外部链接:　
	    <input type="text" name='url' class='form-control' placeholder="请输入完整外部地址" value="<?php echo empty($news['url'])? '': $news['url']; ?>" />
	</li>
	<li class="list-group-item" draggable="false">

	    文章内容:　
	    
	        <!-- 加载编辑器的容器 -->
	        <script id="container" name="content" type="text/plain">
	            <?php echo empty($news['content'])? '': $news['content']; ?>
	        </script>
	        <!-- 配置文件 -->
	        <script type="text/javascript" src="/ueditor/ueditor.config.js"></script>
	        <!-- 编辑器源码文件 -->
	        <script type="text/javascript" src="/ueditor/ueditor.all.js"></script>
	        <!-- 实例化编辑器 -->
	        <script type="text/javascript">
	            var ue = UE.getEditor('container');
	        </script>
	    
	</li>
	<li class="list-group-item" draggable="false">
		<a href="javascript:void(0);" onclick="history.back()">返回</a>
	</li>
<?php include_once('../app/views/admin/_footer.php') ?>
<script type="text/javascript">
	$(document).ready(function(){
		$('button').addClass('nosee');
	});
</script>
