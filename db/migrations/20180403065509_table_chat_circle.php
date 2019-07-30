<?php

use Phinx\Migration\AbstractMigration;

class TableChatCircle extends AbstractMigration
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
       
        $table = $this->table("comp_chat_circle");

        $table->addColumn("user_id", 'integer', array("comment"=>"用户id"))
              ->addColumn("content", 'text', array("comment"=>"说说内容"))
              ->addColumn("imgs", 'text', array("comment"=>"图片地址 多图以, 分隔", "null"=>true))
              ->addColumn("like_count", 'integer', array("comment"=>"点赞数", "null"=>true, "default"=>0))
              ->addColumn("is_deleted", "integer", array("comment"=>"是否删除默认0否1是", "limit"=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY))
              ->addColumn("create_time", "integer", array("comment"=>"创建时间"))
              ->addColumn("update_time", "integer", array("comment"=>"更新时间"))
              ->save();

    }

}
