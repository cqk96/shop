<input type='hidden' name='id' value="<?php echo empty($newsClass['id'])? '':$newsClass['id'];  ?>" />
<input type='hidden' name='cover' value="<?php echo empty($newsClass['cover'])? '':$newsClass['cover'];  ?>" />
<li class="list-group-item" draggable="false">
栏目名称:
    <input type="text" class='form-control' name="class_name" value="<?php echo empty($newsClass['class_name'])? '':$newsClass['class_name'];  ?>" />
</li>
<li class="list-group-item" draggable="false">
    栏目封面:
    <input type="file" class='form-control' name="coverPic" />
</li>
<li class="list-group-item" draggable="false">
    一级栏目:
        <label class='control-label' >是</label><input type="radio" name="p_first" value="0" <?php if($newsClass['pclass_id']==0) echo "checked"; ?> />
        <label class='control-label' >否</label><input type="radio" name="p_first" value="1" <?php if($newsClass['pclass_id']!=0) echo "checked"; ?>/>
</li>
<li class="list-group-item" draggable="false">
    选择所属栏目(暂时只支持两级分类):
    
        <select name="pclass_id" class='form-control'>
            <?php for($i=0; $i<count($newsClasses); $i++){?>
                <option value="<?php echo  $newsClasses[$i]['id']; ?>" <?php if($newsClass['pclass_id']== $newsClasses[$i]['id'])  echo "selected";  ?> ><?php echo  $newsClasses[$i]['class_name']; ?></option>
            <?php } ?>
        </select>
    
</li>
<li class="list-group-item" draggable="false">
    是否隐藏:
    
        <label>是</label><input type="radio" name="hidden" value="1" <?php if($newsClass['hidden']==1) echo "checked"; ?> />
        <label>否</label><input type="radio" name="hidden" value="0" <?php if($newsClass['hidden']==0) echo "checked"; ?> />
    
</li>
<li class="list-group-item" draggable="false">
    <button type="submit" id="stopsubmit" onclick="return stopSubmit()" class="formSubmitBtn btn btn-primary btn-sm">提交</button>
</li>

