<?php

use Phinx\Migration\AbstractMigration;

class TableDiaryRead extends AbstractMigration
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

        $table = $this->table("comp_diary_read");

        $table->addColumn("user_id", "integer", array("comment"=>"填写人id") )
              ->addColumn("type_id", "integer", array("comment"=>"日志类型 1月报", 'limit'=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY) )
              ->addColumn("item_id", "integer", array("comment"=>"对应的 日志表id") )
              ->addColumn("to_user_id", "integer", array("comment"=>"阅读人用户id ") )
              ->addColumn("to_type_id", "integer", array("comment"=>"阅读人 type_id ") )
              ->addColumn("status_id", "integer", array("comment"=>"是否已经查看0否1是", 'limit'=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY ) )
              ->addColumn('is_deleted','integer', array('limit'=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY ,'default'=>0,'null'=>true, 'comment'=>'是否被删除0否1是') )
              ->addColumn('create_time','integer',array('null'=>true, 'comment'=>'创建时间') )
              ->addColumn('update_time','integer',array('null'=>true, 'comment'=>'更新时间') )
              ->save();

    }
}
