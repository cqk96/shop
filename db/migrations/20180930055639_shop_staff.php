<?php

use Phinx\Migration\AbstractMigration;

class ShopStaff extends AbstractMigration
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
          $table = $this->table("shop_staff");
        $table->addColumn("staff_name", 'string', array("null"=>true, 'comment'=>'员工姓名'))
              ->addColumn("staff_usename", 'string', array("null"=>true, 'comment'=>'用户名'))
              ->addColumn("staff_password", 'string', array("null"=>true, 'comment'=>'密码'))
             ->addColumn("department", 'string', array("null"=>true, 'comment'=>'所属部门'))  
              ->addColumn("create_time", "integer",array("null"=>true,'comment'=>'创建时间'))
              ->addColumn("update_time", "integer",array("null"=>true,'comment'=>'更新时间'))
              ->save();
    }
}
