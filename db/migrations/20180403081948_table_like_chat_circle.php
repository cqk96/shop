<?php

use Phinx\Migration\AbstractMigration;

class TableLikeChatCircle extends AbstractMigration
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
        $table = $this->table('comp_like_chat_circle');
        $table->addColumn('chat_id','biginteger',array('null'=>true, "comment"=>"说说id"))
              ->addColumn('user_id','biginteger',array('null'=>true, "comment"=>"用户id"))
              ->addColumn('is_deleted','integer',array("comment"=>"是否删除默认0否1是", "null"=>true, "default"=>0, "limit"=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY))
              ->addColumn('create_time','integer',array('null'=>true, 'comment'=>'创建时间'))
              ->addColumn('update_time','integer',array('null'=>true, 'comment'=>'更新时间'))
              ->save();
    }
}
