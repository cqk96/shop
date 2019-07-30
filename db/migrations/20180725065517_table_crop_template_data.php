<?php

use Phinx\Migration\AbstractMigration;

class TableCropTemplateData extends AbstractMigration
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
        
        $table = $this->table("comp_crop_template_data");

        $table->addColumn("crop_id", "integer", array("comment"=>"作物id") )
              ->addColumn("archive_template_id", "integer", array("comment"=>"档案id") )
              ->addColumn("template_data", "text", array("comment"=>"模板数据json字符串", 'limit'=>Phinx\Db\Adapter\MysqlAdapter::TEXT_LONG) )
              ->addColumn("user_id", "integer", array("comment"=>"操作的用户id") )
              ->addColumn('is_deleted','integer', array('limit'=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY ,'default'=>0,'null'=>true, 'comment'=>'是否被删除0否1是') )
              ->addColumn('create_time','integer',array('null'=>true, 'comment'=>'创建时间') )
              ->addColumn('update_time','integer',array('null'=>true, 'comment'=>'更新时间') )
              ->save();

    }
}

