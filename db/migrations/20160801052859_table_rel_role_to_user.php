<?php

use Phinx\Migration\AbstractMigration;

class TableRelRoleToUser extends AbstractMigration
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
        $table = $this->table('comp_rel_role_to_user');
        $table->addColumn('role_id','biginteger',array('comment'=>'角色id'))
              ->addColumn('user_id','biginteger',array('comment'=>'权限id'))
              ->addColumn('deleted', 'integer', array('null'=>true,'default'=>0,'comment'=>'0正常1删除'))
              ->save();

        $data = [
            ['role_id'=>1, 'user_id'=>1]
        ];

        $table->insert($data)
              ->save();
              
    }
}
