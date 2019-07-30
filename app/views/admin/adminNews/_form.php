<input type='hidden' name='id' value="<?php echo empty($news['id'])? '':$news['id'];  ?>" />
<input type='hidden' name='cover' value='<?php echo empty($news['cover'])? '':$news['cover'];  ?>' />
<input type='file' name='file' class="coverInput nosee" >
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
    <input type='text' required name='title' class='title form-control' id='title' value='<?php echo empty($news['title'])? '':$news['title'];  ?>' placeholder='请输入文章标题' />
</li>
<li class="list-group-item" draggable="false">

    文章封面:　
    
        <?php
            if($news['showCover']=="")
                echo "<button type='button' class='coverBtn btn btn-success btn-xs'>上传文件</button><span style='margin-left: 10px;color:#FF0000;'>(资料库尺寸: 115*158)</span>";
            else
                echo "<a href='".$news['showCover']."' target='_blank' class='btn btn-info btn-xs'>查看封面</a><a href='javascript:void(0);' class='clearBtn btn btn-danger btn-xs' onclick='clearNewsCover()'>点击清除封面</a><button type='button' class='coverBtn btn btn-success btn-xs'>修改封面</button>"
        ?>
    
</li>
<li class="list-group-item" draggable="false">

    文章描述:　
    <textarea name='description' class='form-control' placeholder="请输入文章描述"><?php echo empty($news['description'])? '': $news['description']; ?></textarea>
</li>
<li class="list-group-item" draggable="false">

    文章来源:　
    <input type="text" name='origin' class='form-control' placeholder="请输入文章来源" value="<?php echo empty($news['origin'])? '': $news['origin']; ?>" />
</li>
<!-- <li class="list-group-item" draggable="false">

    文章外部链接:　
    <input type="text" name='url' class='form-control' placeholder="请输入完整外部地址" value="<?php echo empty($news['url'])? '': $news['url']; ?>" />
</li> -->
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

    是否审核:　
    
        <label>否</label><input type='radio' name='pass' value='0' <?php if($news['pass']==0) echo "checked"; ?>  />
        <label>是</label><input type='radio' name='pass' value='1' <?php if($news['pass']==1) echo "checked"; ?> />
    
</li>

<li class="list-group-item" draggable="false">
    是否置顶 （置顶后文章会显示在该文章分类下推荐阅读中）
    <select name="top" class="form-control">
        <option value="0" <?php if( !empty($news['top']) && $news['top']==0) echo "selected"; ?> >否</option>
        <option value="1" <?php if( !empty($news['top']) && $news['top']==1) echo "selected"; ?> >是</option>
    </select>
</li>
<li class="list-group-item" draggable="false" > 

    <button type='submit' id="stopsubmit" class="btn btn-primary btn-sm" onclick="return stopSubmit()">提交</button>
</li>


  <!-- 删除提醒  -->


<script>
	//function submitStop(obj){ 
		
		//element = layui.element; 
		
		//var delurl = $(obj).attr("href"); 

		//document.getElementById('stop').disabled=true; 
		//layer.alert('添加成功', {icon: 1}); 

		
		//$("#idIframe").attr("src", delurl); 
		//window.location.reload(); 
		
		 /* function(othis){
		      //禁止指定项 
		      element.tabDelete('demo', 'stop'); 
		      
		      othis.addClass('layui-btn-disabled');
		    }  */
	
		
		 /* ,tabDelete: function(othis){
		      //删除指定Tab项
		      element.tabDelete('demo', 'stop'); //删除：“商品管理”
		      
		      othis.addClass('layui-btn-disabled');
		    } */
		    
		/* $('.site-demo-active').on('click', function(){
		    var othis = $(this), type = othis.data('type');
		    active[type] ? active[type].call(this, othis) : '';
		  });
		*/
		
		//return false; 
	//}	
</script>

<script>
/*
 	layui.use('element', function(){
	  var $ = layui.jquery
	  ,element = layui.element; //Tab的切换功能，切换事件监听等，需要依赖element模块 
 	}
		 
	  //触发事件 
	  var active = {
	    
			    ,tabDelete: function(othis){
			      //删除指定Tab项
			      element.tabDelete('demo', 'stop'); //删除：“商品管理” 
			      
			      othis.addClass('layui-btn-disabled');
			    }
			   
			  };

	});

	$('.site-demo-active').on('click', function(){
	    var othis = $(this), type = othis.data('type');
	    active[type] ? active[type].call(this, othis) : '';
	  }); */
	  
</script>


