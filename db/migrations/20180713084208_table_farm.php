<?php

use Phinx\Migration\AbstractMigration;

class TableFarm extends AbstractMigration
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
        
        $table = $this->table("comp_farm");

        $table->addColumn("name", "string", array("comment"=>"农场名称", "length"=>30))
              ->addColumn("acreage", "decimal", array("comment"=>"农场面积 单位亩", "precision"=>10, "scale"=>2))
              ->addColumn("manager_id", "integer", array("comment"=>"负责人 用户id"))
              ->addColumn("acre_amount", "integer", array("comment"=>"地块数量", 'limit'=>Phinx\Db\Adapter\MysqlAdapter::INT_SMALL))
              ->addColumn('is_deleted','integer', array('limit'=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY ,'default'=>0,'null'=>true, 'comment'=>'是否被删除'))
              ->addColumn('create_time','integer',array('null'=>true, 'comment'=>'创建时间'))
              ->addColumn('update_time','integer',array('null'=>true, 'comment'=>'更新时间'))
              ->save();

    }

}
