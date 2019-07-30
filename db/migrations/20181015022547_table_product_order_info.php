<?php

use Phinx\Migration\AbstractMigration;

class TableProductOrderInfo extends AbstractMigration
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
        $table = $this->table("comp_product_order_info");

        $table->addColumn("order_id", "integer", array("comment"=>"商品订单表id", "signed"=>false) )
              ->addColumn("thumbnail", "string", array("comment"=>"缩略图", "null"=>true) )
              ->addColumn("country_name", "string", array("comment"=>"国家名称") )
              ->addColumn("currency_name", "string", array("comment"=>"币种") )
              ->addColumn("product_name", "string", array("comment"=>"商品名") )
              ->addColumn("chinese_name", "string", array("comment"=>"商品中文名") )
              ->addColumn("foreign_name", "string", array("comment"=>"商品外文名") )
              ->addColumn("author_id", "integer", array("comment"=>"投放师id", "signed"=>false) )
              ->addColumn("setmeal_json", "text", array("comment"=>"购买的套餐json") )
              ->addColumn('is_deleted','integer', array('limit'=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY ,'default'=>0,'null'=>true, 'comment'=>'是否被删除', "signed"=>false) )
              ->addColumn('create_time','integer',array('null'=>true, 'comment'=>'创建时间', "signed"=>false))
              ->addColumn('update_time','integer',array('null'=>true, 'comment'=>'更新时间', "signed"=>false))
              ->save();
    }

}
