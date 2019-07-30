<?php

use Phinx\Migration\AbstractMigration;

class AddColumnsForTableWebStationMessage extends AbstractMigration
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
        
        $table = $this->table("comp_web_station_already_pushed_result");

        $table->addColumn("type_id", "integer", array("comment"=>"消息类型", 'limit'=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY, "after"=>'id') )
              ->addColumn("is_read", "integer", array("comment"=>"标识是否已读 默认0否1是", "null"=>true, "default"=>0, 'limit'=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY, "after"=>"times") )
              ->addColumn("is_done", "integer", array("comment"=>"非指令类型情况下为完成  默认0否1是", "null"=>true, "default"=>0, 'limit'=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY, "after"=>"is_read") )
              ->save();

    }

}
