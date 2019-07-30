<?php

use Phinx\Migration\AbstractMigration;

class TableNews extends AbstractMigration
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
        $table = $this->table('comp_news');
        $table->addColumn('class_id','biginteger')
              ->addColumn('title','string')
              ->addColumn('cover','string')
              ->addColumn('author','biginteger', array('null'=>true))
              ->addColumn('keywords','string')
              ->addColumn('flags','text', array('null'=>true))
              ->addColumn('content','text')
              ->addColumn('pics','text',array('null'=>true))
              ->addColumn('status','integer',array('limit'=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY ,'default'=>0,'null'=>true))
              ->addColumn('pass','integer',array('limit'=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY ,'default'=>0,'null'=>true))
              ->addColumn('top','integer',array('limit'=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY ,'default'=>0,'null'=>true))
              ->addColumn('hits','biginteger',array('limit'=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY ,'default'=>0,'null'=>true))
              ->addColumn('created_at','integer', array('null'=>true))
              ->addColumn('updated_at','integer', array('null'=>true))
              ->save();
    }
}
