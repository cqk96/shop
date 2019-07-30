<?php

use Phinx\Migration\AbstractMigration;

class TableCurrencyManagement extends AbstractMigration
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
        
        $table = $this->table("comp_currency_management");

        $table->addColumn("name", "string", array("comment"=>"货币名称", "length"=>30) )
              ->addColumn("abbreviation", "string", array("comment"=>"货币简称", "null"=>true, "length"=>10) )
              ->addColumn("front_symbol", "string", array("comment"=>"前置符号", "length"=>5) )
              ->addColumn("back_symbol", "string", array("comment"=>"后置符号", "null"=>true, "length"=>5) )
              ->addColumn('is_deleted','integer', array('limit'=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY ,'default'=>0,'null'=>true, 'comment'=>'是否被删除', "signed"=>false) )
              ->addColumn('create_time','integer',array('null'=>true, 'comment'=>'创建时间'))
              ->addColumn('update_time','integer',array('null'=>true, 'comment'=>'更新时间'))
              ->save();

    }

}
