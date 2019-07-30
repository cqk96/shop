<?php

use Phinx\Migration\AbstractMigration;

class TableExceptionHandling extends AbstractMigration
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

        $table = $this->table("comp_exception_handling");
        $table->addColumn("systemVersion", 'string', array("null"=>true, 'comment'=>'android or ios版本'))
              ->addColumn("appVersion", "integer",array("null"=>true,'comment'=>'app版本'))
              ->addColumn("phoneModel", "string",array("null"=>true,'comment'=>'手机具体型号'))
              ->addColumn("message", "text",array("null"=>true,'comment'=>'奔溃原因'))
              ->addColumn("causePosition", "string",array("null"=>true,'comment'=>'异常发生点'))
              ->addColumn("netStatus", "string",array("null"=>true,'comment'=>'网络状态'))
              ->addColumn("projectName", "string",array("null"=>true,'comment'=>'所属项目名'))
              ->addColumn("create_time", "integer",array("null"=>true,'comment'=>'创建时间'))
              ->save();

    }
}
