<?php

use Phinx\Migration\AbstractMigration;

class TableTenDayDiaryComment extends AbstractMigration
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

        $table = $this->table("comp_ten_day_diary_comment");

        $table->addColumn("diary_id", "integer", array("comment"=>"日志表id") )
              ->addColumn("comment_type_id", "integer", array("comment"=>"日志点评分类1场长点评2公司高管点评", 'limit'=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY) )
              ->addColumn("approver", "integer", array("comment"=>"点评人") )
              ->addColumn("evaluation", "text", array("comment"=>"点评内容") )
              ->addColumn('is_deleted','integer', array('limit'=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY ,'default'=>0,'null'=>true, 'comment'=>'是否被删除0否1是') )
              ->addColumn('create_time','integer',array('null'=>true, 'comment'=>'创建时间') )
              ->addColumn('update_time','integer',array('null'=>true, 'comment'=>'更新时间') )
              ->save();

    }
}
