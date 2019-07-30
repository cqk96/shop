<?php

use Phinx\Migration\AbstractMigration;

class TableDiaryExamination extends AbstractMigration
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
        
        $table = $this->table("comp_diary_examination");

        $table->addColumn("user_id", "integer", array("comment"=>"用户id--谁的日志") )
              ->addColumn("type_id", "integer", array("comment"=>"日志类型 1十日报", 'limit'=>Phinx\Db\Adapter\MysqlAdapter::INT_SMALL) )
              ->addColumn("item_id", "integer", array("comment"=>"对应记录id") )
              ->addColumn("approver_id", "integer", array("comment"=>"审批人") )
              ->addColumn("approver_type_id", "integer", array("comment"=>"审批人角色type id") )
              ->addColumn("status_id", "integer", array("comment"=>"日志状态 默认0未处理1处理中2已处理", 'limit'=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY, "default"=>0) )
              ->addColumn('is_deleted','integer', array('limit'=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY ,'default'=>0,'null'=>true, 'comment'=>'是否被删除0否1是') )
              ->addColumn('create_time','integer',array('null'=>true, 'comment'=>'创建时间') )
              ->addColumn('update_time','integer',array('null'=>true, 'comment'=>'更新时间') )
              ->save();

    }

}
