<?php

use Phinx\Migration\AbstractMigration;

class TableArchiveTemplate extends AbstractMigration
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
        
        $table = $this->table("comp_archive_template");

        $table->addColumn("name", "string", array("comment"=>"档案名称"))
              ->addColumn("code", "text", array("comment"=>"后台显示的编辑的html组件代码", 'limit'=>Phinx\Db\Adapter\MysqlAdapter::TEXT_LONG ))
              ->addColumn("show_code", "text", array("comment"=>"前端使用的完整的html代码 将要填写的内容写为顺序占位符", 'limit'=>Phinx\Db\Adapter\MysqlAdapter::TEXT_LONG, "null"=>true))
              ->addColumn("model_data", "text", array("comment"=>"-*-分隔每个组件json对象 的组合字符串", 'limit'=>Phinx\Db\Adapter\MysqlAdapter::TEXT_LONG))
              ->addColumn("status_id", "enum", array("comment"=>"是否启用默认0否1是", "default"=>"0", "values"=>[0,1]) )
              ->addColumn('is_deleted','integer', array('limit'=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY ,'default'=>0,'null'=>true, 'comment'=>'是否被删除0否1是') )
              ->addColumn('create_time','integer',array('null'=>true, 'comment'=>'创建时间') )
              ->addColumn('update_time','integer',array('null'=>true, 'comment'=>'更新时间') )
              ->save();
    }

}
