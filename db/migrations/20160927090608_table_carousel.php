<?php

use Phinx\Migration\AbstractMigration;

class TableCarousel extends AbstractMigration
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

        $table = $this->table('comp_carousels');
        $table->addColumn('belong_to','string',array('null'=>true, 'comment'=>'轮播项目所属地址'))
              ->addColumn('type','integer',array('null'=>true, 'default'=>1,'comment'=>"项目类型默认1文章"))
              ->addColumn('item_id','biginteger',array('null'=>true,'comment'=>'项目id'))
              ->addColumn('sub_title','string',array('null'=>true,'comment'=>'副标题'))
              ->addColumn('is_hidden','integer',array('limit'=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY ,'null'=>true, 'default'=>0,'comment'=>'是否隐藏'))
              ->addColumn('created_time','integer',array('null'=>true, 'comment'=>'创建时间'))
              ->addColumn('updated_time','integer',array('null'=>true, 'comment'=>'更新时间'))
              ->save();

    }
}
