<?php

use Phinx\Migration\AbstractMigration;

class InsertDataForTableFrontSource extends AbstractMigration
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
        $table = $this->table("comp_front_sources");

        $data = [
            [
                'id' => 1,
                'main_title' => '个很棒的项目',
                'main_content' => '不仅为了名利，还为了有趣而精彩的探求过程，我们不畏艰难，随时准备挑战高峰',
                'parameter' => 'counter',
                'category' => '首页介绍',
                'description' => '首页介绍-列表',
                'created_at' => '1474533784',
            ],
            [
                'id' => 2,
                'main_title' => '位满意的客户',
                'main_content' => '每一位客户都怀着梦想而来，而我们最重视的就是人的梦想，全力以赴并将之带入现实是我们最高的追求',
                'parameter' => 'counter1',
                'category' => '首页介绍',
                'description' => '首页介绍-列表',
                'created_at' => '1474533784',
            ],
            [
                'id' => 3,
                'main_title' => '项专业的成果',
                'main_content' => '学习、钻研、创造是人类历史上最伟大的历程，站在无数前人的成果上，我们将毕生投入到开创未来的事业中',
                'parameter' => 'counter2',
                'category' => '首页介绍',
                'description' => '首页介绍-列表',
                'created_at' => '1474533784',
            ]
        ];

        $table->insert($data)
              ->save();

    }

}
