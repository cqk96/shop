<?php

use Phinx\Migration\AbstractMigration;

class TableExam extends AbstractMigration
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
        
        $table = $this->table("comp_exam");
        $table->addColumn("title", "string", array("comment"=>"考试题目"))
              ->addColumn("status_id", "integer", array("comment"=>"状态 默认0开启1关闭", 'limit'=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY, "null"=>true, "default"=>0) )
              ->addColumn("type_id", "integer", array("comment"=>"类型 默认1考试中心 2题库 3评估室", 'limit'=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY, "null"=>true, "default"=>1) )
              ->addColumn('is_deleted','integer',array('limit'=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY ,'default'=>0,'null'=>true, 'comment'=>'是否被删除'))
              ->addColumn('create_time','integer',array('null'=>true, 'comment'=>'创建时间'))
              ->addColumn('update_time','integer',array('null'=>true, 'comment'=>'更新时间'))
              ->save();
              
    }
}
