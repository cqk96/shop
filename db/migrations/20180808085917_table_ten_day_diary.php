<?php

use Phinx\Migration\AbstractMigration;

class TableTenDayDiary extends AbstractMigration
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
        $table = $this->table("comp_ten_day_diary");

        $table->addColumn("department_id", "integer", array("comment"=>"部门id") )
              ->addColumn("acre_id", "integer", array("comment"=>"地块id") )
              ->addColumn("user_id", "integer", array("comment"=>"用户id") )
              ->addColumn("issue", "integer", array("comment"=>"期号", "null"=>true, "default"=>1) )
              ->addColumn("year", "integer", array("comment"=>"年份") )
              ->addColumn("start_time", "integer", array("comment"=>"开始时间") )
              ->addColumn("end_time", "integer", array("comment"=>"截止时间") )
              ->addColumn("current_work_content", "text", array("comment"=>"本期工作内容") )
              ->addColumn("next_working_plan", "text", array("comment"=>"下期工作计划", "null"=>true) )
              ->addColumn("number_of_group_members", "integer", array("comment"=>"班组成员数") )
              ->addColumn("working_members_count", "integer", array("comment"=>"机动人员数") )
              ->addColumn("completion_of_current_term", "text", array("comment"=>"本期完成情况") )
              ->addColumn("existing_problems", "text", array("comment"=>"存在的问题", "null"=>true) )
              ->addColumn("prior_period_existing_problems", "text", array("comment"=>"上期存在的问题", "null"=>true) )
              ->addColumn('is_deleted','integer', array('limit'=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY ,'default'=>0,'null'=>true, 'comment'=>'是否被删除0否1是') )
              ->addColumn('create_time','integer',array('null'=>true, 'comment'=>'创建时间') )
              ->addColumn('update_time','integer',array('null'=>true, 'comment'=>'更新时间') )
              ->save();
    }
}
