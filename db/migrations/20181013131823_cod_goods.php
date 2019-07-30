<?php

use Phinx\Migration\AbstractMigration;

class CodGoods extends AbstractMigration
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
        $table = $this->table('comp_cod_goods');
        $table->addColumn('title','string')   //商品名
              ->addColumn('status_id','integer', array('null'=>true,'default'=>0,'comment'=>'商品状态'))
              ->addColumn('chinese_title','string', array('null'=>true,'comment'=>'中文名'))
              ->addColumn('foreign_title','string', array('null'=>true,'comment'=>'外文名'))
              ->addColumn('price','integer', array('null'=>true,'comment'=>'价格'))
              ->addColumn('Purchaseurl','string', array('null'=>true,'comment'=>'采购url'))
              ->addColumn('discount','integer', array('null'=>true,'comment'=>'折扣'))
              ->addColumn('Original_Price','integer', array('null'=>true,'comment'=>'原价'))
              ->addColumn('Inventory','integer', array('null'=>true,'comment'=>'库存'))
              ->addColumn('sales','integer', array('null'=>true,'comment'=>'销量'))       
              ->addColumn('rate','integer', array('null'=>true,'comment'=>'好评率'))     
              ->addColumn('labels','string', array('null'=>true,'comment'=>'产品标签'))
              ->addColumn('logo','string', array('null'=>true,'comment'=>'商品logo'))
              ->addColumn('thumbnail','string', array('null'=>true,'comment'=>'缩略图'))
              ->addColumn('images','text', array('null'=>true,'comment'=>'商品图集'))
              ->addColumn('updated_at','integer', array('null'=>true,'comment'=>'图片预览'))
              ->addColumn('detail','text', array('null'=>true,'comment'=>'商品详情'))
              ->addColumn('create_time','integer', array('null'=>true,'comment'=>'创建时间'))
              ->addColumn('update_time','integer', array('null'=>true,'comment'=>'更新时间'))
              ->addColumn('description','string', array('null'=>true,'comment'=>'商品简单描述'))
              ->addColumn('porder_id','integer', array('null'=>true,'comment'=>'商品排序'))
              ->addColumn('readme','text', array('null'=>true,'comment'=>'购买提示'))
              ->addColumn('rush_time','integer', array('null'=>true,'comment'=>'剩余抢购时间'))
              ->addColumn('domain_name','string', array('null'=>true,'comment'=>'域名'))
              ->addColumn('email','string', array('null'=>true,'comment'=>'联系邮箱'))
              ->addColumn('language','string', array('null'=>true,'comment'=>'语言'))
              ->addColumn('country','string', array('null'=>true,'comment'=>'国家'))
              ->addColumn('LINE','string', array('null'=>true,'comment'=>'LINE'))
              ->addColumn('pop800id','string', array('null'=>true,'comment'=>'pop800ids'))
              ->addColumn('is_deleted', 'integer', array('null'=>true,'default'=>0,'comment'=>'0正常1删除'))
             
              ->addColumn('content','string', array('null'=>true,'comment'=>'内容'))



              ->save();
    }
}
