<?php

use Phinx\Migration\AbstractMigration;

class TableArchiveTemplateCategory extends AbstractMigration
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
        $table = $this->table("comp_archive_template_category");

        $table->addColumn("name", "string", array("length"=>10, "comment"=>"档案模板分类名称") )
              ->addColumn("cover", "string", array("length"=>70, "comment"=>"封面", "null"=>true) )
              ->addColumn("resume", "string", array("length"=>10, "comment"=>"简述", "null"=>true) )
              ->addColumn("order_index", "integer", array('limit'=>Phinx\Db\Adapter\MysqlAdapter::INT_SMALL, "comment"=>"排序 越小越前面", "default"=>9999) )
              ->addColumn('is_deleted','integer', array('limit'=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY ,'default'=>0,'null'=>true, 'comment'=>'是否被删除0否1是') )
              ->addColumn('create_time','integer',array('null'=>true, 'comment'=>'创建时间') )
              ->addColumn('update_time','integer',array('null'=>true, 'comment'=>'更新时间') )
              ->save();

    }

}
