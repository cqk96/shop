<?php

use Phinx\Migration\AbstractMigration;

class TableForFontsource extends AbstractMigration
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
		$table=$this->table('comp_front_sources')
					->addColumn('main_title','string',array('null'=>true,'default'=>'标题','comment'=>'主标题'))
					->addColumn('sub_title','string',array('null'=>true,'comment'=>'副标题'))
					->addColumn('main_pic','string',array('null'=>true,'comment'=>'主要图片'))
					->addColumn('secondary_pic','string',array('null'=>true,'comment'=>'次要图片'))
					->addColumn('main_content','string',array('null'=>true,'default'=>'暂时没有内容','comment'=>'主要内容'))
					->addColumn('secondary_content','string',array('null'=>true,'comment'=>'次要内容'))
					->addColumn('main_figure','string',array('null'=>true,'comment'=>'主要数值'))
					->addColumn('secondary_figure','string',array('null'=>true,'comment'=>'次要数值'))
					->addColumn('parameter','string',array('null'=>true,'comment'=>'参数'))
					->addColumn('category','string',array('null'=>true,'default'=>'未分类','comment'=>'分类'))
					->addColumn('description','string',array('null'=>true,'comment'=>'备注'))
					->addColumn('created_at','integer', array('null'=>true))
					->save();
	}
}
