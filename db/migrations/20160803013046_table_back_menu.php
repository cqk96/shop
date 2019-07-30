<?php

use Phinx\Migration\AbstractMigration;

class TableBackMenu extends AbstractMigration
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
        
        $table = $this->table('comp_menus');
        $table->addColumn('name','string', array('null'=>true, 'comment'=>'菜单名称'))
              ->addColumn('url','string', array('null'=>true, 'comment'=>'菜单跳转地址'))
              ->addColumn('order','integer', array('null'=>true,'default'=>1000, 'comment'=>'菜单排序 越小越靠前'))
              ->addColumn('show','integer', array('null'=>true,'default'=>1, 'comment'=>'是否显示 1显示'))
              ->addColumn('parentid','integer', array('null'=>true,'default'=>0, 'comment'=>'是否为一级菜单 0是其他为对应的父级'))
              ->addColumn('created_at','integer', array('null'=>true))
              ->addColumn('updated_at','integer', array('null'=>true))
              ->addColumn('status','integer', array('null'=>true, 'default'=>0, 'comment'=>'0正常1删除'))
              ->save();

        $data = [
          ['name'=>'文章管理', 'url'=> '','order'=>1, 'parentid'=>0,'created_at'=>1470194792,'updated_at'=>1470194792],
          ['name'=>'分类管理', 'url'=>'/admin/newsClasses','order'=>2,'parentid'=>1,'created_at'=>1470194792,'updated_at'=>1470194792],
          ['name'=>'文章管理', 'url'=>'/admin/news','order'=>3,'parentid'=>1,'created_at'=>1470194792,'updated_at'=>1470194792],
          ['name'=>'片段管理', 'url'=>'/admin/pieces','order'=>4,'parentid'=>1,'created_at'=>1470194792,'updated_at'=>1470194792],
          ['name'=>'系统管理', 'url'=>'','order'=>5,'parentid'=>0,'created_at'=>1470194792,'updated_at'=>1470194792],
          ['name'=>'站点管理', 'url'=>'/admin/site','order'=>6,'parentid'=>5,'created_at'=>1470194792,'updated_at'=>1470194792],
          ['name'=>'导航管理', 'url'=>'/admin/nav','order'=>7,'parentid'=>5,'created_at'=>1470194792,'updated_at'=>1470194792],
          ['name'=>'用户管理', 'url'=>'/admin/users','order'=>8,'parentid'=>5,'created_at'=>1470194792,'updated_at'=>1470194792],
          ['name'=>'后台管理', 'url'=>'','order'=>9,'parentid'=>0,'created_at'=>1470194792,'updated_at'=>1470194792],
          ['name'=>'后台权限管理', 'url'=>'','order'=>10,'parentid'=>9,'created_at'=>1470194792,'updated_at'=>1470194792],
          ['name'=>'菜单管理', 'url'=>'','order'=>11,'parentid'=>9,'created_at'=>1470194792,'updated_at'=>1470194792],
          ['name'=>'权限管理', 'url'=>'/admin/sys/privileges','order'=>12,'parentid'=>10,'created_at'=>1470194792,'updated_at'=>1470194792],
          ['name'=>'角色管理', 'url'=>'/admin/sys/roles','order'=>13,'parentid'=>10,'created_at'=>1470194792,'updated_at'=>1470194792],
          ['name'=>'用户角色管理', 'url'=>'/admin/sys/rtus','order'=>14,'parentid'=>10,'created_at'=>1470194792,'updated_at'=>1470194792],
          ['name'=>'角色权限管理', 'url'=>'/admin/sys/ptrs','order'=>15,'parentid'=>10,'created_at'=>1470194792,'updated_at'=>1470194792],
          ['name'=>'角色操作权限管理', 'url'=>'/admin/sys/opms','order'=>16,'parentid'=>10,'created_at'=>1470194792,'updated_at'=>1470194792],
          ['name'=>'后台菜单管理', 'url'=>'/admin/sys/menus','order'=>17,'parentid'=>11,'created_at'=>1470194792,'updated_at'=>1470194792],
          ['name'=>'后台菜单分配管理', 'url'=>'/admin/sys/rtms','order'=>18,'parentid'=>11,'created_at'=>1470194792,'updated_at'=>1470194792],

          ['name'=>'后台活动管理', 'url'=>'','order'=>19,'parentid'=>0,'created_at'=>1470194792,'updated_at'=>1470194792],
          ['name'=>'活动管理', 'url'=>'/admin/activitys','order'=>20,'parentid'=>19,'created_at'=>1470194792,'updated_at'=>1470194792],

          ['name'=>'后台敏感词管理', 'url'=>'','order'=>21,'parentid'=>0,'created_at'=>1470194792,'updated_at'=>1470194792],
          ['name'=>'敏感词管理', 'url'=>'/admin/sensitiveWords','order'=>22,'parentid'=>21,'created_at'=>1470194792,'updated_at'=>1470194792],

          ['name'=>'后台轮播图管理', 'url'=>'','order'=>23,'parentid'=>0,'created_at'=>1470194792,'updated_at'=>1470194792],
          ['name'=>'轮播管理', 'url'=>'/admin/carouselImgs','order'=>24,'parentid'=>23,'created_at'=>1470194792,'updated_at'=>1470194792],

          ['name'=>'后台考试管理', 'url'=>'','order'=>25,'parentid'=>0,'created_at'=>1470194792,'updated_at'=>1470194792],
          ['name'=>'考试题管理', 'url'=>'/admin/exams','order'=>26,'parentid'=>25,'created_at'=>1470194792,'updated_at'=>1470194792],

          ['name'=>'后台在线考试', 'url'=>'','order'=>27,'parentid'=>0,'created_at'=>1470194792,'updated_at'=>1470194792],
          ['name'=>'在线考试', 'url'=>'/admin/exams/testLists','order'=>28,'parentid'=>27,'created_at'=>1470194792,'updated_at'=>1470194792],
          ['name'=>'在线考试结果', 'url'=>'/admin/answerExams','order'=>29,'parentid'=>27,'created_at'=>1470194792,'updated_at'=>1470194792],

          ['name'=>'后台app管理', 'url'=>'','order'=>30,'parentid'=>0,'created_at'=>1470194792,'updated_at'=>1470194792],
          ['name'=>'apk管理', 'url'=>'/admin/manageApps','order'=>31,'parentid'=>30,'created_at'=>1470194792,'updated_at'=>1470194792]
        ];

        $table->insert($data)
              ->save();

    }

}
