<?php

use Phinx\Migration\AbstractMigration;

class ShopOrder extends AbstractMigration
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
          $table = $this->table("shop_order");
        $table->addColumn("product_id", 'integer', array("null"=>true, 'comment'=>'商品id'))
              ->addColumn("Order_detail", 'text', array("null"=>true, 'comment'=>'订单详情'))
              ->addColumn("order_num", 'string', array("null"=>true, 'comment'=>'订单号'))
            ->addColumn("Order_name", 'string', array("null"=>true, 'comment'=>'下单人'))  
              ->addColumn("country", 'string', array("null"=>true, 'comment'=>'国家'))   
              ->addColumn("currency", "string",array("null"=>true,'comment'=>'币种'))
              ->addColumn("phone", "integer",array("null"=>true,'comment'=>'电话'))
              ->addColumn("address", "text",array("null"=>true,'comment'=>'地址'))
              ->addColumn("title", "string",array("null"=>true,'comment'=>'商品名称'))
              ->addColumn("chinese_title", "string",array("null"=>true,'comment'=>'名称(中文)'))
              ->addColumn("foreign_title", "string",array("null"=>true,'comment'=>'名称(外文)'))
              ->addColumn("Launch", "string",array("null"=>true,'comment'=>'投放师'))
              ->addColumn("price", "integer",array("null"=>true,'comment'=>'应付金额'))
              ->addColumn("total", "integer",array("null"=>true,'comment'=>'总件数'))
              ->addColumn("Order_time", "integer",array("null"=>true,'comment'=>'下单时间'))
              ->addColumn("Logistics number", "integer",array("null"=>true,'comment'=>'国内物流单号'))
              ->addColumn("screen", "integer",array("null"=>true,'comment'=>'筛选', 'default'=>0))
              ->addColumn("query", "integer",array("null"=>true,'comment'=>'查询', 'default'=>0))
              ->addColumn("order", "integer",array("null"=>true,'comment'=>'排序', 'default'=>0))
              ->save();
    }
}
