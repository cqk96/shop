<?php

use Phinx\Migration\AbstractMigration;

class TableProductOrder extends AbstractMigration
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
        
        $table = $this->table('comp_product_order');

        $table->addColumn("order_num", "string", array("comment"=>"订单号", "length"=>50) )
              ->addColumn("user_name", "string", array("comment"=>"下单人姓名") )
              ->addColumn("email", "string", array("comment"=>"下单人邮箱", "length"=>30 ) )
              ->addColumn("postcode", "string", array("comment"=>"邮编", "null"=>true, "length"=>20) )
              ->addColumn("erp_id", "string", array("comment"=>"erp id", "null"=>true ) )
              ->addColumn("product_id", "integer", array("comment"=>"商品id") )
              ->addColumn("phone", "string", array("comment"=>"下单人电话", "length"=>20) )
              ->addColumn("province", "string", array("comment"=>"省", "null"=>true, "length"=>10) )
              ->addColumn("city", "string", array("comment"=>"市", "null"=>true, "length"=>10) )
              ->addColumn("district", "string", array("comment"=>"次级市/区", "null"=>true, "length"=>10) )
              ->addColumn("street", "string", array("comment"=>"街道地址") )
              ->addColumn("payable_price", "integer", array("comment"=>"应付金额(分)", "default"=>0, "signed"=>false) )
              ->addColumn("paid_price", "integer", array("comment"=>"实付金额(分)", "default"=>0, "signed"=>false) )
              ->addColumn("amounts", "integer", array("comment"=>"购买数量", "default"=>0, "signed"=>false) )
              ->addColumn("freight", "integer", array("comment"=>"运费(分)", "default"=>0, "signed"=>false) )
              ->addColumn("express_name", "string", array("comment"=>"快递名称", "null"=>true, "length"=>20) )
              ->addColumn("express_code", "string", array("comment"=>"快递编码", "null"=>true, "length"=>50) )
              ->addColumn("remarks", "string", array("comment"=>"留言", "null"=>true) )
              ->addColumn('order_status','integer', array('limit'=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY ,'default'=>1,'null'=>true, 'comment'=>'订单状态 1正常，2异常，3确认，4取消，5待定', "signed"=>false) )
              ->addColumn('order_type','integer', array('limit'=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY ,'default'=>1,'null'=>true, 'comment'=>'订单类型 1货到付款', "signed"=>false) )
              ->addColumn('refuse_reason','string', array('null'=>true, 'comment'=>'拒绝理由') )
              ->addColumn('is_deleted','integer', array('limit'=>Phinx\Db\Adapter\MysqlAdapter::INT_TINY ,'default'=>0,'null'=>true, 'comment'=>'是否被删除', "signed"=>false) )
              ->addColumn('create_time','integer',array('null'=>true, 'comment'=>'创建时间', "signed"=>false))
              ->addColumn('update_time','integer',array('null'=>true, 'comment'=>'更新时间', "signed"=>false))
              ->save();

    }

}
