<?php

use Phinx\Migration\AbstractMigration;

class ShopProducts extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {   
        $table = $this->table('shop_products');
        $table->addColumn('title','string')   //商品名
              ->addColumn('status_id','integer', array('null'=>true,'default'=>0))   //商品状态
              ->addColumn('price','integer', array('null'=>true))//价格
              ->addColumn('discount','integer', array('null'=>true))// 折扣
              ->addColumn('Original_Price','integer', array('null'=>true))//原价
              ->addColumn('Inventory','integer', array('null'=>true))// 库存
              ->addColumn('sales','integer', array('null'=>true))//销量            
              ->addColumn('rate','integer', array('null'=>true))//好评率      
              ->addColumn('labels','string', array('null'=>true))//产品标签
              ->addColumn('logo','string', array('null'=>true))//商品logo
              ->addColumn('Preview_picture','text', array('null'=>true))//图片预览
              ->addColumn('thumbnail','string', array('null'=>true))//缩略图
              ->addColumn('images','text', array('null'=>true))//商品图集
              ->addColumn('updated_at','integer', array('null'=>true))//图片预览
              ->addColumn('detail','text', array('null'=>true))//商品详情
              ->addColumn('create_time','integer', array('null'=>true))//创建时间
              ->addColumn('update_time','integer', array('null'=>true))//更新时间
              ->addColumn('description','string', array('null'=>true))//商品简单描述
              ->addColumn('porder_id','integer', array('null'=>true))//商品排序
              ->addColumn('readme','text', array('null'=>true))//购买提示
              ->addColumn('rush_time','integer', array('null'=>true))//剩余抢购时间

              ->save();

    }
}
