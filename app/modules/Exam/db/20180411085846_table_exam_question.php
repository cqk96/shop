<?php

use Phinx\Migration\AbstractMigration;

class TableExamQuestion extends AbstractMigration
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

        $table = $this->table("comp_exam_question");
        $table->addColumn("exam_id", "integer", array("comment"=>"考试表id") )
              ->addColumn("content", "string", array("comment"=>"题目描述"))
              ->addColumn("score", "integer", array("comment"=>"分数", "null"=>true, "default"=>0))
              ->addColumn("question_type", "integer", array("comment"=>"题目类型 默认1单选2判断3多选", 'limit'=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY, "null"=>true, "default"=>1) )
              ->addColumn("html_type", "integer", array("comment"=>"对应的input类型 默认1单选2多选", 'limit'=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY, "null"=>true, "default"=>1) )
              ->addColumn("question_index", "integer", array("comment"=>"题目排序", "null"=>true, "default"=>1))
              ->addColumn('is_deleted','integer',array('limit'=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY ,'default'=>0,'null'=>true, 'comment'=>'是否被删除'))
              ->addColumn('create_time','integer',array('null'=>true, 'comment'=>'创建时间'))
              ->addColumn('update_time','integer',array('null'=>true, 'comment'=>'更新时间'))
              ->save();

    }

}
