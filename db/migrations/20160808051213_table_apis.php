<?php

use Phinx\Migration\AbstractMigration;

class TableApis extends AbstractMigration
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
        
        $table = $this->table('comp_apis');
        $table->addColumn('url','text',array('null'=>true,'comment'=>'api地址'))
              ->addColumn('description','string',array('null'=>true,'comment'=>'api描述'))
              ->addColumn('params','text',array('null'=>true,'comment'=>"api参数,‘-,-’分割"))
              ->addColumn('method','string',array('null'=>true,'comment'=>'方法'))
              ->addColumn('http_protocal','string',array('null'=>true,'comment'=>'协议'))
              ->addColumn('status','integer',array('null'=>true,'default'=> 0,'comment'=>'是否已删除0未1删'))
              ->addColumn('project_id','integer',array('null'=>true,'comment'=>'项目id'))
              ->save();

    }
    
}
