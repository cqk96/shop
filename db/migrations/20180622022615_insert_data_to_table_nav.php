<?php

use Phinx\Migration\AbstractMigration;

class InsertDataToTableNav extends AbstractMigration
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
        
        $table = $this->table("comp_navs");

        $data = [
            [
                'text' => '首页',
                'url' => '#home',
                'order' => 1,
                'show' => 1,
                'created_at' => 1465997989,
                'updated_at' => 1465997989
            ],
            [
                'text' => '联系我们',
                'url' => '#contact',
                'order' => 2,
                'show' => 1,
                'created_at' => 1465997989,
                'updated_at' => 1465997989
            ]
        ];

        $table->insert( $data )
              ->save();
    }

}
