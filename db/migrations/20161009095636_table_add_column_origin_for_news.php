<?php

use Phinx\Migration\AbstractMigration;

class TableAddColumnOriginForNews extends AbstractMigration
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
        $has_origin = $table->hasColumn("origin");
        if(!$has_origin){
            $table->addColumn("origin",'string',array('null'=>true,'comment'=>'起源'))
                  ->save();
        }
        
    }
}
