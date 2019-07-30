<?php

use Phinx\Migration\AbstractMigration;

class TableManageApp extends AbstractMigration
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
       
        $table = $this->table("comp_manage_app");

        $table->addColumn("name", "string", array("comment"=>"应用名称"))
              ->addColumn("version_code", "integer", array("comment"=>"开发版本号"))
              ->addColumn("version_text", "string", array("comment"=>"用户版本号"))
              ->addColumn("apk_url", "string", array("comment"=>"apk文件存储地址"))
              ->addColumn("description", "text", array("comment"=>"描述", "null"=>true))
              ->addColumn('is_deleted','integer',array('limit'=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY ,'default'=>0,'null'=>true, 'comment'=>'是否被删除'))
              ->addColumn('create_time','integer',array('null'=>true, 'comment'=>'创建时间'))
              ->addColumn('update_time','integer',array('null'=>true, 'comment'=>'更新时间'))
              ->save();

    }

}
