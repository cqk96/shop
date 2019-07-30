<?php

use Phinx\Migration\AbstractMigration;

class CreateTableCodes extends AbstractMigration
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
		
		$table = $this->table('comp_codes');
		$table->addColumn('type','integer',array('default'=>1)) // 码类型: 1:网站帐号注册邀请码
			  ->addColumn('code','string') // 码
			  ->addColumn('status','integer',array('default'=>0)) // 码状态: 0:未启用 1:未使用 2:已使用 3:无效
			  ->addColumn('expire_datetime','integer') // 过期时间
			  ->addColumn('created_at','integer', array('null'=>true))
              ->addColumn('updated_at','integer', array('null'=>true))
			  ->save();
		
    }
}
