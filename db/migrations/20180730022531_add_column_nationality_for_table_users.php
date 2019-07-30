<?php

use Phinx\Migration\AbstractMigration;

class AddColumnNationalityForTableUsers extends AbstractMigration
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
        
        $table = $this->table("comp_users");

        $table->addColumn("nationality_number", "integer", array("comment"=>"国籍 整型", "length"=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY, "after"=>"nationality", "null"=>true) )
              ->save();

    }

}
