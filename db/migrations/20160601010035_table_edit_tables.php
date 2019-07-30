<?php

use Phinx\Migration\AbstractMigration;

class TableEditTables extends AbstractMigration
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
        /*新闻表修改*/
        $table1 = $this->table('comp_news');
        $column1_1  = $table1->hasColumn('description');
        if(!$column1_1){
            $table1->addColumn('description', 'text')
                   ->save();
        }
        /*新闻表修改 end*/

        /*栏目表修改*/
        $table2 = $this->table('comp_news_classes');
        $column2_1  = $table2->hasColumn('cover');
        if(!$column2_1){
            $table2->addColumn('cover', 'text')
                   ->save();
        }
        /*栏目表修改 end*/
    }
    
}
