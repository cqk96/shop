<?php

use Phinx\Migration\AbstractMigration;

class TableSysRole extends AbstractMigration
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
        
        $table = $this->table('comp_sys_roles');
        $table->addColumn('name','string',array('limit'=>50))
              ->addColumn('description','string',array('comment'=>'描述'))
              ->addColumn('type_id','biginteger',array('comment'=>'角色类型'))
              ->addColumn('deleted', 'integer', array('null'=>true,'default'=>0,'comment'=>'0正常1删除'))
              ->addColumn('department_scope', 'integer', array('null'=>true,'default'=>0,'comment'=>'0正常1删除'))
              ->save();

        $roles = [
            ['name'=>'超级管理员', 'description'=>'拥有网站最高权限', 'type_id'=>1101],
            ['name'=>'系统角色', 'description'=>'后台管理员', 'type_id'=>1102],
            ['name'=>'职员', 'description'=>'公司职员', 'type_id'=>1103],
            ['name'=>'用户', 'description'=>'注册的普通用户', 'type_id'=>1104],
            ['name'=>'客户', 'description'=>'合作的客户', 'type_id'=>1105]
        ];

        $table->insert($roles)
              ->save();
              
    }
}
