<?php

use Phinx\Migration\AbstractMigration;

class TableSysPrivilege extends AbstractMigration
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
        
        $table = $this->table('comp_sys_privileges');
        $table->addColumn('name','string',array('limit'=>50))
              ->addColumn('type_id','biginteger',array('comment'=>'权限类型'))
              ->addColumn('deleted', 'integer', array('null'=>true,'default'=>0,'comment'=>'0正常1删除'))
              ->save();

        $privileges = [
            ['name'=>'菜单权限', 'type_id'=>2001],
            ['name'=>'操作权限', 'type_id'=>2002]
        ];
        
        $table->insert($privileges)
              ->save();
    }
}
