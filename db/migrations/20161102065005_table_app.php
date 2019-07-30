<?php

use Phinx\Migration\AbstractMigration;

class TableApp extends AbstractMigration
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
        $table = $this->table("comp_apps");
        $table->addColumn("app_id", "string", array("null"=>true, "comment"=>'識別應用'))
              ->addColumn("name", "string", array("null"=>true, "comment"=>'應用名'))
              ->addColumn("package_name", "string", array("null"=>true, "comment"=>'包名'))
              ->addColumn("version_code", "integer", array("null"=>true, "comment"=>"版本號-開發使用"))
              ->addColumn("version_name", "string", array("null"=>true, "comment"=>"版本名-用戶"))
              ->addColumn("icon", "string", array("null"=>true, "comment"=>"應用圖標"))
              ->addColumn("apk_url", "string", array("null"=>true, "comment"=>"附件地址"))
              ->addColumn("description", "text", array("null"=>true, "comment"=>"描述"))
              ->addColumn("create_time", "integer", array("null"=>true, "comment"=>"應用時間"))
              ->save();
    }
}
