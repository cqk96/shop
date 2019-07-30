<?php

use Phinx\Migration\AbstractMigration;

class TableUserMessage extends AbstractMigration
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
        
        $table = $this->table("comp_user_message");
        $table->addColumn("message_type_id",'integer',array('limit'=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY ,'default'=>1,'null'=>true, 'comment'=>'消息类型默认1推送消息2站内通知'))
              ->addColumn('content','text',array('comment'=>'消息内容'))
              ->addColumn('user_id','integer',array('comment'=>'用户id'))
              ->addColumn('read','integer', array('limit'=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY ,'default'=>0,'null'=>true, 'comment'=>'是否已阅读 默认0否1是'))
              ->addColumn('is_deleted','integer', array('limit'=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY ,'default'=>0,'null'=>true, 'comment'=>'是否被删除'))
              ->addColumn('create_time','integer',array('null'=>true, 'comment'=>'创建时间'))
              ->addColumn('update_time','integer',array('null'=>true, 'comment'=>'更新时间'))
              ->save();

    }

}
