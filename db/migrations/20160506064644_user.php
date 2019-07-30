<?php

use Phinx\Migration\AbstractMigration;

class User extends AbstractMigration
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
        
        $table = $this->table('comp_users');
        $table->addColumn('user_login','string', array("null"=>true))
              ->addColumn('password','text', array("null"=>true))
              ->addColumn('access_token','text', array("null"=>true))
              ->addColumn('token_expire_time','integer', array("null"=>true))
              ->addColumn('avatar','text', array("null"=>true))
              ->addColumn('nickname','string', array("null"=>true))
              ->addColumn('gender','integer',array('limit' => Phinx\Db\Adapter\MysqlAdapter::INT_TINY,'default'=>3, 'null'=>true))
              ->addColumn('age','integer',array('limit' => Phinx\Db\Adapter\MysqlAdapter::INT_TINY, 'null'=>true, 'default'=>3))
              ->addColumn('introduce','text', array("null"=>true))
              ->addColumn('is_deleted','integer',array('limit' => Phinx\Db\Adapter\MysqlAdapter::INT_TINY,'default'=>0, 'null'=>true, 'comment'=>'0正常1禁用'))
              ->addColumn('create_time','integer', array("null"=>true))
              ->save();
        $data = [
          ['user_login'=>'admin', 'password'=>'21232f297a57a5a743894a0e4a801fc3', 'access_token'=>'', 'token_expire_time'=>0, 'avatar'=>'', 'nickname'=>'King', 'introduce'=>'','is_deleted'=>0, 'create_time'=>1470187088]
        ];

        $table->insert($data)
              ->save();

    }

}
