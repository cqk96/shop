<?php

use Phinx\Migration\AbstractMigration;

class TableOperatePrivilegeToRole extends AbstractMigration
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
        $table = $this->table('comp_operate_privilege_to_role');
        $table->addColumn('role_id','string',array('null'=>true, 'comment'=>'角色id'))
              ->addColumn('operate_id','biginteger',array('null'=>true, 'comment'=>'权限id'))
              ->addColumn('deleted', 'integer', array('null'=>true,'default'=>0,'comment'=>'0正常1删除'))
              ->save();

        $data = [
            ['role_id'=>1, 'operate_id'=>1],
            ['role_id'=>1, 'operate_id'=>2],
            ['role_id'=>1, 'operate_id'=>3],
            ['role_id'=>1, 'operate_id'=>4],
            ['role_id'=>1, 'operate_id'=>5]
        ];

        $table->insert($data)
              ->save();
    }
}
