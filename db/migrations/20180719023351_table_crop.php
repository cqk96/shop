<?php

use Phinx\Migration\AbstractMigration;

class TableCrop extends AbstractMigration
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

        $table = $this->table("comp_crop");

        $table->addColumn("area_id", "integer", array("comment"=>"片区id") )
              ->addColumn("number", "string", array("comment"=>"作物编号") )
              ->addColumn("column_number", "integer", array("comment"=>"作物列号", "null"=>true) )
              ->addColumn("row_number", "string", array("comment"=>"作物行号", "null"=>true) )
              ->addColumn("status_id", "integer", array("comment"=>"作物状态 默认0正常1病虫害", 'limit'=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY ,'default'=>0,'null'=>true) )
              ->addColumn("planting_time", "integer", array("comment"=>"种植时间", "null"=>true) )
              ->addColumn('is_deleted','integer', array('limit'=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY ,'default'=>0,'null'=>true, 'comment'=>'是否被删除0否1是') )
              ->addColumn('create_time','integer',array('null'=>true, 'comment'=>'创建时间') )
              ->addColumn('update_time','integer',array('null'=>true, 'comment'=>'更新时间') )
              ->save();

    }

}
