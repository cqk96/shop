<?php

use Phinx\Migration\AbstractMigration;

class AddColumnManagerIdForTableUsers extends AbstractMigration
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
        $table = $this->table("comp_users");

        $table->addColumn("manager_id", "integer", array("comment"=>"部门主管id 0表示未配置", "null"=>true, "default"=>0, "after"=>"birthday") )
              ->save();
    }

}
