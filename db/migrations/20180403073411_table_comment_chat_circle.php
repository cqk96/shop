<?php

use Phinx\Migration\AbstractMigration;

class TableCommentChatCircle extends AbstractMigration
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
        $table = $this->table('comp_comment_chat_circle');
        $table->addColumn('chat_id','biginteger',array('comment'=>"说说id"))
              ->addColumn('user_id','biginteger',array('comment'=>"评论人id"))
              ->addColumn('content','text',array('null'=>true, "comment"=>"评论内容"))
              ->addColumn('to_id','biginteger',array('default'=>0,'null'=>true, 'comment'=>'0没有回复  回复对象用户id'))
              ->addColumn('is_deleted','integer',array('limit'=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY ,'default'=>0,'null'=>true, 'comment'=>'是否被删除'))
              ->addColumn('created_time','integer',array('null'=>true, 'comment'=>'创建时间'))
              ->addColumn('updated_time','integer',array('null'=>true, 'comment'=>'更新时间'))
              ->save();
    }
}
