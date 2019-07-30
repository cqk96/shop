<?php

use Phinx\Migration\AbstractMigration;

class TableReportComment extends AbstractMigration
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
        
        $table = $this->table('comp_report_comments');
        $table->addColumn('comment_id','biginteger', array('null'=>true, 'comment'=>"评论记录id"))
              ->addColumn('user_id','biginteger', array('null'=>true))
              ->addColumn('reason','string', array('null'=>true, 'comment'=>'举报原因'))
              ->addColumn('is_deleted','integer',array('limit'=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY ,'default'=>0,'null'=>true, 'comment'=>'0未删除1已删除'))
              ->addColumn('create_time','integer', array('null'=>true))
              ->save();
        
    }
}
