<?php

use Phinx\Migration\AbstractMigration;

class AddColumnsExtraContentForPushedResult extends AbstractMigration
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
        $table->addColumn("extra_content", "string", array("comment"=>"额外内容 用于msg_id为0的情况", "after"=>"pushed_result", "null"=>true) )
              ->save();
    }
}
