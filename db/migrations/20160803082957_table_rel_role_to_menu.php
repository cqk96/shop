<?php

use Phinx\Migration\AbstractMigration;

class TableRelRoleToMenu extends AbstractMigration
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
        
        $table = $this->table('comp_rel_role_to_menu');
        $table->addColumn('role_id','biginteger',array('comment'=>'拥有菜单权限的角色id'))
              ->addColumn('menu_id','biginteger',array('comment'=>'菜单id'))
              ->addColumn('deleted', 'integer', array('null'=>true,'default'=>0,'comment'=>'0正常1删除'))
              ->save();

        // $data = [
        //     ['role_id'=>1, 'menu_id'=>1],
        //     ['role_id'=>1, 'menu_id'=>2],
        //     ['role_id'=>1, 'menu_id'=>3],
        //     ['role_id'=>1, 'menu_id'=>4],
        //     ['role_id'=>1, 'menu_id'=>5],
        //     ['role_id'=>1, 'menu_id'=>6],
        //     ['role_id'=>1, 'menu_id'=>7],
        //     ['role_id'=>1, 'menu_id'=>8],
        //     ['role_id'=>1, 'menu_id'=>9],
        //     ['role_id'=>1, 'menu_id'=>10],
        //     ['role_id'=>1, 'menu_id'=>11],
        //     ['role_id'=>1, 'menu_id'=>12],
        //     ['role_id'=>1, 'menu_id'=>13],
        //     ['role_id'=>1, 'menu_id'=>14],
        //     ['role_id'=>1, 'menu_id'=>15],
        //     ['role_id'=>1, 'menu_id'=>16],
        //     ['role_id'=>1, 'menu_id'=>17],
        //     ['role_id'=>1, 'menu_id'=>18],
        //     ['role_id'=>1, 'menu_id'=>19],
        //     ['role_id'=>1, 'menu_id'=>20],
        //     ['role_id'=>1, 'menu_id'=>21],
        //     ['role_id'=>1, 'menu_id'=>22],
        //     ['role_id'=>1, 'menu_id'=>23],
        //     ['role_id'=>1, 'menu_id'=>24],
        //     ['role_id'=>1, 'menu_id'=>25],
        //     ['role_id'=>1, 'menu_id'=>26],
        //     ['role_id'=>1, 'menu_id'=>27],
        //     ['role_id'=>1, 'menu_id'=>28]
        // ];

        // $table->insert($data)
        //       ->save();

    }
    
}
