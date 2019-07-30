<?php

use Phinx\Migration\AbstractMigration;

class CreateTablePieces extends AbstractMigration
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
		$table = $this->table('comp_pieces');
        $table->addColumn('description','string')   //名称
              ->addColumn('content','text', array('null'=>true))   //内容
              ->addColumn('type','integer', array('null'=>true,'default'=>0)) //类型      
              ->addColumn('created_at','integer', array('null'=>true))
              ->addColumn('updated_at','integer', array('null'=>true))
              ->save();
    }
}
