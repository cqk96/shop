<?php

use Phinx\Migration\AbstractMigration;

class Properties extends AbstractMigration
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
        $table = $this->table('comp_properties');
        $table->addColumn('name','string', array('null'=>true,'comment'=>'属性名称'))  
              ->addColumn('chinese_name','string', array('null'=>true,'comment'=>'中文名称'))
              ->addColumn('foreign_name','string', array('null'=>true,'comment'=>'外文名称'))  
              ->addColumn('images','string', array('null'=>true,'comment'=>'图片'))  
              ->addColumn('is_deleted', 'integer', array('null'=>true,'default'=>0,'comment'=>'0正常1删除'))
              ->save();
    }
}
