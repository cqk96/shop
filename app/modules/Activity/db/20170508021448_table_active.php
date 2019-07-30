<?php

use Phinx\Migration\AbstractMigration;

class TableActive extends AbstractMigration
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
        
        $table = $this->table("comp_activity");
        $table->addColumn("title", "string", array("null"=>true,  'comment'=>"活动名称"))
              ->addColumn("cover", "string", array("null"=>true,  'comment'=>"封面"))
              ->addColumn("start_time", "integer", array("null"=>true,  'comment'=>"举办时间(不用特殊处理)"))
              ->addColumn("end_time", "integer", array("null"=>true,  'comment'=>"结束时间(不用特殊处理)"))
              ->addColumn("apply_start_time", "integer", array("null"=>true,  'comment'=>"报名开始时间"))
              ->addColumn("apply_end_time", "integer", array("null"=>true,  'comment'=>"报名截止时间"))
              ->addColumn("content", "text", array("null"=>true,  'comment'=>"活动内容"))
              ->addColumn("description", "text", array("null"=>true,  'comment'=>"活动简述"))
              ->addColumn("total_people_count", "integer", array("null"=>true,  "default"=>0, 'comment'=>"活动总人数"))
              ->addColumn("apply_people_count", "integer", array("null"=>true,  "default"=>0, 'comment'=>"报名总人数"))
              ->addColumn("is_hidden", "integer", array("null"=>true, 'default'=>0 ,'comment'=>"是否隐藏", "limit"=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY))
              ->addColumn("is_deleted", "integer", array("null"=>true,'default'=>0 ,  'comment'=>"是否删除", "limit"=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY))
              ->addColumn("create_time", "integer", array("null"=>true,  'comment'=>"创建时间"))
              ->addColumn("update_time", "integer", array("null"=>true,  'comment'=>"更新时间"))
              ->save();

    }

}
