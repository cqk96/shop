<?php

use Phinx\Migration\AbstractMigration;

class ShopSysOperates extends AbstractMigration
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
      $table = $this->table("shop_sys_operates");
            $table->addColumn("name", 'string', array("null"=>true, 'comment'=>'操作名称'))
                  ->addColumn("type_id", 'biginteger', array("null"=>true, 'comment'=>'权限类型'))
                  ->addColumn("is_deleted", "integer",array("null"=>true,'comment'=>'是否删除', 'default'=>0))

                  ->save();
    }
}
