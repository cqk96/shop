<?php

use Phinx\Migration\AbstractMigration;

class TypeIdForTableWebStationMessage extends AbstractMigration
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
        $table = $this->table("comp_web_station_message");

        $type_id = $table->hasColumn("type_id");
        if( !$type_id ) {
            $table->addColumn("type_id", "integer", array("comment"=>"消息类型 1消息", 'limit'=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY, "after"=>"id", "null"=>true, "default"=>1) )
                  ->save();
        }

    }

}
