<?php

use Phinx\Migration\AbstractMigration;

class AddColumnsPushedResultForPushedResult extends AbstractMigration
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
        $table->addColumn("pushed_result", "integer", array("comment"=>"三方推送是否推送成功 0否1是", 'limit'=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY, "after"=>"is_done") )
              ->save();
              
    }

}
