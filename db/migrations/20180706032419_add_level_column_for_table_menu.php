<?php

use Phinx\Migration\AbstractMigration;

class AddLevelColumnForTableMenu extends AbstractMigration
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
        $table = $this->table("comp_menus");

        $table->addColumn("level", "integer", array("null"=>true, "default"=>1, "comment"=>"菜单等级一级为1，以此类推") )
              ->save();

        // 更新一级菜单
        $this->execute('update `comp_menus` set level=1 where id in (1, 5, 9, 19, 21,23, 25, 27, 30); ');

        // 更新二级菜单
        $this->execute('update `comp_menus` set level=2 where id in (2, 3, 4, 6, 7, 8, 10, 11, 20, 22, 24, 26, 28, 29,31); ');

        // 更新三级菜单
        $this->execute('update `comp_menus` set level=3 where id in (12, 13, 14, 15, 16, 17, 18); ');

    }

}
