<?php

use Phinx\Migration\AbstractMigration;

class InsertRolesToTableRole extends AbstractMigration
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
        
        $this->execute(" update `comp_sys_roles` set name='班组组长', description='班组的领导' where id=5; ");

        $this->execute(" update `comp_sys_roles` set name='系统管理员' where id=2; ");

        $table = $this->table("comp_sys_roles");

        $data = [
            ['name'=>'主管领导', 'description'=>'场长后的日志审批', 'type_id'=>1106],
            ['name'=>'部门领导', 'description'=>'各部门领导', 'type_id'=>1107],
            ['name'=>'场长', 'description'=>'管理农场', 'type_id'=>1108],
            ['name'=>'公司高管', 'description'=>'场长后的日志审批', 'type_id'=>1109]
        ];

        $table->insert( $data )
              ->save();

    }

}
