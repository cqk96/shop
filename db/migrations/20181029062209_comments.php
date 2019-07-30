<?php

use Phinx\Migration\AbstractMigration;

class Comments extends AbstractMigration
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
        $table = $this->table('comp_comments');
        $table->addColumn('productId','integer')   //产品ID
              ->addColumn('username','string', array('null'=>true,'comment'=>'用户名'))
              ->addColumn('starlevel','integer', array('null'=>true,'comment'=>'星级'))
              ->addColumn('content','text', array('null'=>true,'comment'=>'内容'))
              ->addColumn('pictures','text', array('null'=>true,'comment'=>'图片'))
              ->addColumn('create_time','integer', array('null'=>true,'comment'=>'创建时间'))
              ->addColumn('update_time','integer', array('null'=>true,'comment'=>'更新时间'))
              ->addColumn('is_anonymous', 'integer', array('null'=>true,'default'=>0,'comment'=>'0正常1匿名'))
              ->addColumn('is_deleted', 'integer', array('null'=>true,'default'=>0,'comment'=>'0正常1删除'))
              ->save();
    }
}
