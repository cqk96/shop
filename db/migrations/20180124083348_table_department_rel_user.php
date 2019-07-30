<?php

use Phinx\Migration\AbstractMigration;

class TableDepartmentRelUser extends AbstractMigration
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
        
        $table = $this->table("comp_department_rel_user");
        $table->addColumn("department_id", "integer", array("comment"=>"部门表id"))
              ->addColumn("user_id", "integer", array("comment"=>"用户表id"))
              ->addColumn("is_deleted", "integer", array("comment"=>"是否删除默认0否1是", "null"=>true, "default"=>0, "limit"=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY))
              ->addColumn("create_time", "integer", array("comment"=>"创建时间"))
              ->addColumn("update_time", "integer", array("comment"=>"更新时间"))
              ->save();

    }

}
