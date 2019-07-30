<?php

use Phinx\Migration\AbstractMigration;

class CreateTableNav extends AbstractMigration
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
		
        $table = $this->table('comp_navs');
        $table->addColumn('text','string', array('null'=>true))
              ->addColumn('url','string', array('null'=>true))
			  ->addColumn('order','integer', array('null'=>true,'default'=>99))
			  ->addColumn('show','integer', array('null'=>true,'default'=>1))
              ->addColumn('created_at','integer', array('null'=>true))
              ->addColumn('updated_at','integer', array('null'=>true))
              ->save();

    }
    
}
