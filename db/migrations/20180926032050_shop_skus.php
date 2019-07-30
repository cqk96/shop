<?php

use Phinx\Migration\AbstractMigration;

class ShopSkus extends AbstractMigration
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

        $table = $this->table("shop_skus");
        $table->addColumn("product_id", 'integer', array("null"=>true, 'comment'=>'商品id'))
              ->addColumn("price", 'integer', array("null"=>true, 'comment'=>'售价/分'))
              ->addColumn("inventory", 'integer', array("null"=>true, 'comment'=>'库存'))
             ->addColumn("dispatch_place", 'text', array("null"=>true, 'comment'=>'发货地'))  
              ->addColumn("default_express", 'integer', array("null"=>true, 'comment'=>'默认快递'))   
              ->addColumn("is_deleted", "integer",array("null"=>true,'comment'=>'是否删除', 'default'=>0))
              ->addColumn("is_hidden", "integer",array("null"=>true,'comment'=>'是否隐藏', 'default'=>0))
              ->addColumn("create_time", "integer",array("null"=>true,'comment'=>'创建时间'))
              ->addColumn("update_time", "integer",array("null"=>true,'comment'=>'更新时间'))
              ->save();
    }
}
