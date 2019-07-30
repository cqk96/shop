<?php

use Phinx\Migration\AbstractMigration;

class ChangePassDefaultValueForTableNews extends AbstractMigration
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
        $table = $this->table("comp_news");

        $table->changeColumn("pass", "integer", array("limit"=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY, "comment"=>"是否审核  默认1是0否", "default"=>1, "null"=>true) )
              ->save();

    }

}
