<?php

use Phinx\Migration\AbstractMigration;

class ShopAddressManagers extends AbstractMigration
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
          $table = $this->table("shop_address_managers");
        $table->addColumn("name", 'string', array("null"=>true, 'comment'=>'收货人姓名'))
              ->addColumn("phone", 'string', array("null"=>true, 'comment'=>'收货人电话'))
              ->addColumn("province", 'string', array("null"=>true, 'comment'=>'所在省'))
             ->addColumn("city", 'string', array("null"=>true, 'comment'=>'所在市'))  
              ->addColumn("default_express", 'text', array("null"=>true, 'comment'=>'详细地址'))   
              ->addColumn("user_id", "string",array("null"=>true,'comment'=>'所属用户'))
              ->addColumn("default_use", "integer",array("null"=>true,'comment'=>'是否默认使用','default'=>0))
              ->addColumn("update_time", "integer",array("null"=>true,'comment'=>'更新时间'))
              ->addColumn("is_deleted", "integer",array("null"=>true,'comment'=>'是否删除0不删1删除','default'=>0))
              ->save();
    }
}
