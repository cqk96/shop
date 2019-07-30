<?php

use Phinx\Migration\AbstractMigration;

class ShopProperty extends AbstractMigration
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
     $table = $this->table("shop_property");
            $table->addColumn("product_id", 'integer', array("null"=>true, 'comment'=>'商品id'))
                  ->addColumn("name", 'integer', array("null"=>true, 'comment'=>'属性组名称'))
                  ->addColumn("foreign_title", 'integer', array("null"=>true, 'comment'=>'属性外文名称'))
                 ->addColumn("chinese_title", 'text', array("null"=>true, 'comment'=>'属性中文名称'))  
                  ->addColumn("Imagess", 'integer', array("null"=>true, 'comment'=>'图片预览'))   
                  ->save();
    }
}
