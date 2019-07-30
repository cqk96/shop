<?php

use Phinx\Migration\AbstractMigration;

class TableCarouselImg extends AbstractMigration
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

        $table = $this->table("comp_carousel_img");
        $table->addColumn("cover", "string", array("comment"=>"轮播图"))
              ->addColumn("title", "string", array("comment"=>"轮播标题, 用作预留字段", "null"=>"true"))
              ->addColumn("url", "string", array("comment"=>"轮播指向地址, 用作预留字段", "null"=>"true"))
              ->addColumn("order_index", "integer", array("comment"=>"轮播图排序 显示时数值越大就在越前面"))
              ->addColumn("is_hidden", "integer", array("comment"=>"轮播图 显示时是否显示0否1是", "null"=>true, "default"=>0, "limit"=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY))
              ->addColumn("is_deleted", "integer", array("comment"=>"轮播图 是否被删除 默认0否1是", "null"=>true, "default"=>0, "limit"=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY))
              ->addColumn("create_time", "integer", array("comment"=>"创建时间"))
              ->addColumn("update_time", "integer", array("comment"=>"修改时间"))
              ->save();

    }
    
}
