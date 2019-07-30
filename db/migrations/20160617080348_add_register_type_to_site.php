<?php

use Phinx\Migration\AbstractMigration;

class AddRegisterTypeToSite extends AbstractMigration
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
        $table->addColumn('register_type','integer',array('default'=>0)) // 用户注册方式：0：不允许注册 1：开放注册 2：邀请码注册
              ->save();
    }
}
