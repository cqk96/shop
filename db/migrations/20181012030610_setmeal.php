<?php

use Phinx\Migration\AbstractMigration;

class Setmeal extends AbstractMigration
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
        $table = $this->table('comp_setmeal');
        $table->addColumn('setname','string', array('null'=>true,'comment'=>'套餐名称')) 
              ->addColumn('skus','string', array('null'=>true,'comment'=>'库存')) 
              ->addColumn('price','float', array('null'=>true,'comment'=>'价格'))  
              ->addColumn('is_deleted', 'integer', array('null'=>true,'default'=>0,'comment'=>'0正常1删除'))
              ->save();
    }
}
