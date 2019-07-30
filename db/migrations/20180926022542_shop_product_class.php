<?php

use Phinx\Migration\AbstractMigration;

class ShopProductClass extends AbstractMigration
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
        $table = $this->table("Shop_Product_Class");
        $table->addColumn("product_id", 'integer', array("null"=>true, 'comment'=>'商品id'))
              ->addColumn("title", 'string', array("null"=>true, 'comment'=>'商品分类标题'))
              ->addColumn("Is_hidden", 'integer', array("null"=>true, 'comment'=>'是否隐藏默认0不隐藏1隐藏'))
              ->addColumn("is_deleted", "integer",array("null"=>true, 'default'=>0,'comment'=>'是否删除0否1是'))
              ->addColumn("create_time", "integer",array("null"=>true,'comment'=>'创建时间'))
              ->addColumn("update_time", "integer",array("null"=>true,'comment'=>'更新时间'))
              ->save();

    
    }
}
