<?php

use Phinx\Migration\AbstractMigration;

class InsertDataToNewsClasses extends AbstractMigration
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
        
        $table = $this->table("comp_news_classes");

        // 进行清空
        $this->execute(" truncate table comp_news_classes ");

        $data = [
            ['class_name'=>'公司简介', 'update_time'=>1533104522],
            ['class_name'=>'规章制度', 'update_time'=>1533104522],
            ['class_name'=>'企业文化', 'update_time'=>1533104522],
            ['class_name'=>'病虫害识别', 'update_time'=>1533104522],
            ['class_name'=>'芒果品种分类', 'update_time'=>1533104522]
        ];

        $table->insert($data)
              ->save();
    }

}
