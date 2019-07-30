<?php

use Phinx\Migration\AbstractMigration;

class TableAddThirdColumns extends AbstractMigration
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
        $table = $this->table("comp_users");
        
        $has_wechat = $table->hasColumn('wechat_openid');
        if(!$has_wechat){
            $table->addColumn('wechat_openid', 'string', array('null'=>true, 'comment'=>'微信凭证'))
                  ->save();
        }

        $has_sina = $table->hasColumn('sina_openid');
        if(!$has_sina){
            $table->addColumn('sina_openid', 'string', array('null'=>true, 'comment'=>'新浪微博凭证'))
                  ->save();
        }

        $has_qq = $table->hasColumn('qq_openid');
        if(!$has_qq){
            $table->addColumn('qq_openid', 'string', array('null'=>true, 'comment'=>'qq凭证'))
                  ->save();
        }


    }
}
