<?php

use Phinx\Migration\AbstractMigration;

class TableMonthDiary extends AbstractMigration
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
        
        $table = $this->table("comp_monthly_diary");

        $table->addColumn("year", "integer", array("comment"=>"年份") )
              ->addColumn("month", "integer", array("comment"=>"月份") )
              ->addColumn("content", "text", array("comment"=>"工作内容") )
              ->addColumn("transliteration", "string", array("comment"=>"姓名译音") )
              ->addColumn("maintenance", "string", array("comment"=>"养护情况") )
              ->addColumn("weed", "string", array("comment"=>"除草情况") )
              ->addColumn("mechanical_usage", "string", array("comment"=>"机械使用情况") )
              ->addColumn("fertilization", "string", array("comment"=>"施肥情况") )
              ->addColumn("other_work", "string", array("comment"=>"其他情况", "null"=>true) )
              ->addColumn("remarks", "text", array("comment"=>"备注", "null"=>true) )
              ->addColumn('is_deleted','integer', array('limit'=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY ,'default'=>0,'null'=>true, 'comment'=>'是否被删除0否1是') )
              ->addColumn('create_time','integer',array('null'=>true, 'comment'=>'创建时间') )
              ->addColumn('update_time','integer',array('null'=>true, 'comment'=>'更新时间') )
              ->save();

    }

}
