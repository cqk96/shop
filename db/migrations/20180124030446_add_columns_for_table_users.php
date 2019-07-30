<?php

use Phinx\Migration\AbstractMigration;

class AddColumnsForTableUsers extends AbstractMigration
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
        
        $table = $this->table("comp_users");

        $table->addColumn("phone", "string", array("null"=>true, "comment"=>"手机号，当账号为手机号时自动填入", "length"=>20, "after"=>"avatar"))
              ->addColumn("name", "string", array("null"=>true, "comment"=>"姓名", "length"=>30, "after"=>"phone"))
              ->addColumn("ethnicity", "string", array("null"=>true, "comment"=>"民族", "length"=>30, "after"=>"name"))
              ->addColumn("native_place", "string", array("null"=>true, "comment"=>"籍贯", "length"=>30, "after"=>"ethnicity"))
              ->addColumn("political", "integer", array("null"=>true, "comment"=>"政治面貌默认0 群众1团员2预备党员3党员",  "after"=>"native_place", 'default'=>0, "limit"=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY))
              ->addColumn("join_time", "integer", array("null"=>true, "comment"=>"入党(团)时间 群众为空",  "after"=>"political"))
              ->addColumn("university", "string", array("null"=>true, "comment"=>"毕业院校",  "after"=>"join_time"))
              ->addColumn("major", "string", array("null"=>true, "comment"=>"所学专业",  "after"=>"university"))
              ->addColumn("education", "integer", array("null"=>true, "default"=>"0无，1博士，2硕士，3本科，4专科，5高中，6初中", "comment"=>"所学专业", "default"=>0,  "after"=>"major"))
              ->addColumn("address", "string", array("null"=>true, "comment"=>"家庭住址",  "after"=>"education"))
              ->addColumn("work_experience", "text", array("null"=>true, "comment"=>"工作经历",  "after"=>"address"))
              ->addColumn("update_time", "integer", array("null"=>true, "comment"=>"更新时间"))
              ->save();
    }

}
