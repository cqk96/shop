<?php

use Phinx\Migration\AbstractMigration;

class TableAcre extends AbstractMigration
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
        
        $table = $this->table("comp_acre");

        $table->addColumn("name", "string", array("comment"=>"地块名"))
              ->addColumn("farm_id", "integer", array("comment"=>"农场id", 'limit'=>Phinx\Db\Adapter\MysqlAdapter::INT_SMALL))
              ->addColumn("manager_id", "integer", array("comment"=>"地块负责人"))
              ->addColumn("acreage", "decimal", array("comment"=>"地块面积 /亩", "precision"=>10, "scale"=>2))
              ->addColumn("area_amount", "integer", array("comment"=>"片区数量"))
              ->addColumn('is_deleted','integer', array('limit'=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY ,'default'=>0,'null'=>true, 'comment'=>'是否被删除'))
              ->addColumn('create_time','integer',array('null'=>true, 'comment'=>'创建时间'))
              ->addColumn('update_time','integer',array('null'=>true, 'comment'=>'更新时间'))
              ->save();

    }

}