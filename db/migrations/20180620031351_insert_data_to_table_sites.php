<?php

use Phinx\Migration\AbstractMigration;

class InsertDataToTableSites extends AbstractMigration
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
        $table = $this->table("comp_sites");

        $data = [
            [
                'site_name'=>"vrigo 基础框架",
                'logo' => "/images/logo.png",
                'keywords' => '航桓 virgo框架 php',
                'description' => 'Virgo框架是航桓科技有限公司基于mvc模式的一款自主开发php框架',
                'copyright' => '© 2018 hzhanghuan.com. ALL Rights Reserved.',
                'address' => '杭州',
                'created_at' => '1529464893',
                'updated_at' => '1529464893',
                'postcode' => 1
            ]
        ];

        $table->insert($data)
              ->save();
    }
}
