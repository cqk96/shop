<?php

use Phinx\Migration\AbstractMigration;

class InsertDataToTableFarm extends AbstractMigration
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
        $this->execute(" truncate table `comp_farm`; ");

        $table = $this->table("comp_farm");

        $data = [
            ['name'=>"上丁基地", 'acreage'=>10000, 'manager_id'=>1, 'acre_amount'=>15, 'create_time'=>1533717018, 'update_time'=>1533717018]
        ];

        $table->insert($data)
              ->save();
    }

}
