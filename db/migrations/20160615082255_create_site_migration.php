<?php

use Phinx\Migration\AbstractMigration;

class CreateSiteMigration extends AbstractMigration
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
		$table = $this->table('comp_sites');
        $table->addColumn('site_name','string', array('null'=>true))
              ->addColumn('domain','string', array('null'=>true))
              ->addColumn('logo','string', array('null'=>true))
              ->addColumn('keywords','string', array('null'=>true))
              ->addColumn('description','text', array('null'=>true))
              ->addColumn('copyright','string', array('null'=>true))
			  ->addColumn('address','string', array('null'=>true))
			  ->addColumn('phone','string', array('null'=>true))
			  ->addColumn('email','string', array('null'=>true))
			  ->addColumn('address_lnt','string', array('null'=>true))
			  ->addColumn('address_lat','string', array('null'=>true))
			  ->addColumn('qq','string', array('null'=>true))
              ->addColumn('created_at','integer', array('null'=>true))
              ->addColumn('updated_at','integer', array('null'=>true))
              ->save();
    }
}
