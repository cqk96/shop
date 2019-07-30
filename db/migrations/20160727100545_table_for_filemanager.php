<?php

use Phinx\Migration\AbstractMigration;

class TableForFilemanager extends AbstractMigration
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
		$table = $this->table('comp_files');
		$table->addColumn('name','string')   //文件名
              ->addColumn('type','string') //类型
			  ->addColumn('description','string')	 //说明		  
              ->addColumn('content','text', array('null'=>true))   //内容可用于路径
			  ->addColumn('create_time','integer', array('null'=>true)) //上传时间
              ->addColumn('down_count','biginteger', array('null'=>true,'default'=>0)) //下载次数
			  ->addColumn('resource_name','string') //生成文件路径
			  ->save();
    }
}
