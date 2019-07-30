<?php

use Phinx\Migration\AbstractMigration;

class TableAnswerExamResult extends AbstractMigration
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

        $table = $this->table("comp_answer_result");
        $table->addColumn("ae_id", "integer", array("comment"=>"回答考试题目id") )
              ->addColumn("eq_id", "integer", array("comment"=>"回答题目id"))
              ->addColumn("answer", "string", array("comment"=>"回答的选项索引"))
              ->addColumn('is_deleted','integer',array('limit'=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY ,'default'=>0,'null'=>true, 'comment'=>'是否被删除'))
              ->addColumn('create_time','integer',array('null'=>true, 'comment'=>'创建时间'))
              ->addColumn('update_time','integer',array('null'=>true, 'comment'=>'更新时间'))
              ->save();

    }
}
