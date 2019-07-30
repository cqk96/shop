<?php

use Phinx\Migration\AbstractMigration;

class TableArchiveCategoryToMainBody extends AbstractMigration
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
        $table = $this->table("comp_archive_category_to_main_body");

        $table->addColumn("archive_template_category_id", "integer", array("comment"=>"分类id") )
              ->addColumn("main_body_type_id", "integer", array("comment"=>"主体id 1农场2地块3片区4作物5蔬菜6部门7班组织", 'limit'=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY) )
              ->addColumn("item_id", "integer", array("comment"=>"预留 为精确到具体对象做准备", "null"=>true))
              ->addColumn('is_deleted','integer', array('limit'=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY ,'default'=>0,'null'=>true, 'comment'=>'是否被删除0否1是') )
              ->addColumn('create_time','integer',array('null'=>true, 'comment'=>'创建时间') )
              ->addColumn('update_time','integer',array('null'=>true, 'comment'=>'更新时间') )
              ->save();
    }

}
