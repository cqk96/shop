<?php

use Phinx\Migration\AbstractMigration;

class TableArea extends AbstractMigration
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
        
        $table = $this->table("comp_area");

        $table->addColumn("name", "string", array("comment"=>"片区名"))
              ->addColumn("manager_id", "integer", array("comment"=>"片区负责人"))
              ->addColumn("acreage", "decimal", array("comment"=>"地块面积 /平方米", "precision"=>10, "scale"=>2))
              ->addColumn("acre_id", "integer", array("comment"=>"地块id", 'limit'=>Phinx\Db\Adapter\MysqlAdapter::INT_SMALL))
              ->addColumn("type_id", "integer", array("comment"=>"类型id 1水果2蔬菜", "default"=>1, 'limit'=>Phinx\Db\Adapter\MysqlAdapter::INT_SMALL))
              ->addColumn("remarks", "text", array("comment"=>"备注", "null"=>true))
              ->addColumn("crop_amount", "integer", array("comment"=>"作物数量"))
              ->addColumn("crop_type_id", "integer", array("comment"=>"作物种类 关联种类表", 'limit'=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY))
              ->addColumn("status_id", "integer", array("comment"=>"作物状态0正常1虫害", "default"=>0, 'limit'=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY))
              ->addColumn('is_deleted','integer', array('limit'=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY ,'default'=>0,'null'=>true, 'comment'=>'是否被删除'))
              ->addColumn('create_time','integer',array('null'=>true, 'comment'=>'创建时间'))
              ->addColumn('update_time','integer',array('null'=>true, 'comment'=>'更新时间'))
              ->save();
              
    }

}
