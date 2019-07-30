<?php

use Phinx\Migration\AbstractMigration;

class InsertDataToArchiveTemplateCategory extends AbstractMigration
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
        
        $this->execute(" truncate table `comp_archive_template_category`; ");

        $table = $this->table("comp_archive_template_category");

        $data = [
            ['name'=>'前期准备', 'cover'=>'/images/templateCategoryImages/1.png', 'resume'=>'土地耕翻', 'order_index'=>1, 'create_time'=>1533696679, 'update_time'=>1533696679],
            ['name'=>'前期准备', 'cover'=>'/images/templateCategoryImages/2.png', 'resume'=>'施肥情况', 'order_index'=>2, 'create_time'=>1533696679, 'update_time'=>1533696679],
            ['name'=>'前期准备', 'cover'=>'/images/templateCategoryImages/3.png', 'resume'=>'播种移栽', 'order_index'=>3, 'create_time'=>1533696679, 'update_time'=>1533696679],
            ['name'=>'除草松土', 'cover'=>'/images/templateCategoryImages/4.png', 'resume'=>'', 'order_index'=>4, 'create_time'=>1533696679, 'update_time'=>1533696679],
            ['name'=>'修剪枝叶', 'cover'=>'/images/templateCategoryImages/5.png', 'resume'=>'', 'order_index'=>5, 'create_time'=>1533696679, 'update_time'=>1533696679],
            ['name'=>'施肥情况', 'cover'=>'/images/templateCategoryImages/6.png', 'resume'=>'', 'order_index'=>6, 'create_time'=>1533696679, 'update_time'=>1533696679],
            ['name'=>'灌溉情况', 'cover'=>'/images/templateCategoryImages/7.png', 'resume'=>'', 'order_index'=>7, 'create_time'=>1533696679, 'update_time'=>1533696679],
            ['name'=>'病虫害', 'cover'=>'/images/templateCategoryImages/8.png', 'resume'=>'', 'order_index'=>8, 'create_time'=>1533696679, 'update_time'=>1533696679],
            ['name'=>'开花情况', 'cover'=>'/images/templateCategoryImages/9.png', 'resume'=>'', 'order_index'=>9, 'create_time'=>1533696679, 'update_time'=>1533696679],
            ['name'=>'挂果情况', 'cover'=>'/images/templateCategoryImages/10.png', 'resume'=>'芒果', 'order_index'=>10, 'create_time'=>1533696679, 'update_time'=>1533696679],
            ['name'=>'挂果情况', 'cover'=>'/images/templateCategoryImages/11.png', 'resume'=>'蔬菜', 'order_index'=>11, 'create_time'=>1533696679, 'update_time'=>1533696679],
            ['name'=>'种植采收情况', 'cover'=>'/images/templateCategoryImages/12.png', 'resume'=>'蔬菜', 'order_index'=>12, 'create_time'=>1533696679, 'update_time'=>1533696679],
        ];

        $table->insert( $data )
              ->save();
    }

}
