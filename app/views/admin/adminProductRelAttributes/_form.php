<!-- <input type='hidden' name='id' value="<?php echo empty($data['id'])? '':$data['id'];  ?>" /> -->
<!-- <input type='hidden' name='coverPath' value='<?php echo empty($data['top_cover'])? '':$data['top_cover'];  ?>' /> -->
<!-- 记录页数 -->
<input type='hidden' name='page' value='<?php echo empty($_GET['page'])? 1:$_GET['page'];  ?>' />
<li class="list-group-item" draggable="false">
    商品选择:　
    <select name="product_id" class="form-control">
        <option value="0">无</option>
        <?php for ($i=0; $i < count($products); $i++):?>
            <option value="<?php echo $products[$i]['id']; ?>" <?php if(!empty($productId) && $productId==$products[$i]['id']) echo "selected"; ?> ><?php echo $products[$i]['title']; ?></option>
        <?php endfor;?>
    </select>
</li>
<li class="list-group-item" draggable="false">
    属性选择:　
    <i class="addImgBtn fa fa-plus"></i>
    <div class="images-box">
        <?php if(!empty($data)):?>
        <?php foreach ($data as $data_attributes):?>
            <div>
                <!-- 属性 -->
                <select name="attributes[]" class="form-control attr-select">
                    <option value="0">无</option>
                    <?php for ($i=0; $i < count($attributes); $i++):?>
                        <option value="<?php echo $attributes[$i]['id']; ?>" <?php if(!empty($data_attributes['sub_attribute_id']) && $data_attributes['sub_attribute_id']==$attributes[$i]['id']) echo "selected"; ?> ><?php echo $attributes[$i]['name']; ?></option>
                    <?php endfor;?>
                </select>

                <div class='priceBox'>
                    <div class='input-group'>
                    <div class="input-group-addon">￥</div>
                    <input type='text' class='form-control' name='price[]' placeholder='请填写附加价格' value="<?php echo empty($data_attributes['price'])? '':number_format($data_attributes['price']/100, 2, '.', ''); ?>">
                    <div class="input-group-addon">元</div>
                    </div>
                </div>

                <span class='removeImgBtn' onclick='removeImgNode(this)'>&times;</span>

            </div>
        <?php endforeach;?>
        <?php endif;?>
    </div>
</li>
<li class="list-group-item" draggable="false">
    <button type='submit' class='btn btn-primay btn-sm'>提交</button>
</li>
