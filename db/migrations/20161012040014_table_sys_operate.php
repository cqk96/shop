<?php

use Phinx\Migration\AbstractMigration;

class TableSysOperate extends AbstractMigration
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
        $table = $this->table('comp_sys_operates');
        $table->addColumn('name','string',array('limit'=>50))
              ->addColumn('type_id','biginteger',array('comment'=>'权限类型'))
              ->addColumn('deleted', 'integer', array('null'=>true,'default'=>0,'comment'=>'0正常1删除'))
              ->save();

        $operates = [
            ['name'=>'后台登陆操作', 'type_id'=>3001],
            ['name'=>'增加数据操作', 'type_id'=>3002],
            ['name'=>'删除数据操作', 'type_id'=>3003],
            ['name'=>'查看数据操作', 'type_id'=>3004],
            ['name'=>'修改数据操作', 'type_id'=>3005]
        ];
        
        $table->insert($operates)
              ->save();
    }
}
