<?php

use Phinx\Migration\AbstractMigration;

class InsertDataToTablePiece extends AbstractMigration
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
        $table = $this->table("comp_pieces");

        $data = [
            [
                'id' => 1,
                'description' => '首页轮播文字1',
                'content' => '专业',
                'type' => 1,
                'created_at' => 1467793437,
                'updated_at' => 1467793437
            ],
            [
                'id' => 2,
                'description' => '首页轮播文字2',
                'content' => '琢磨',
                'type' => 1,
                'created_at' => 1467793437,
                'updated_at' => 1467793437
            ],
            [
                'id' => 3,
                'description' => '首页轮播文字3',
                'content' => '创造',
                'type' => 1,
                'created_at' => 1467793437,
                'updated_at' => 1467793437
            ],
            [
                'id' => 4,
                'description' => '首页标题1（项目）',
                'content' => '从网站到系统集成，科技不分高低，价值无论大小，我们都将助你前行',
                'type' => 1,
                'created_at' => 1467793437,
                'updated_at' => 1467793437
            ],
            [
                'id' => 5,
                'description' => '首页副标题1（项目）',
                'content' => '软硬件开发、系统集成、项目研发、解决方案',
                'type' => 1,
                'created_at' => 1467793437,
                'updated_at' => 1467793437
            ],
            [
                'id' => 6,
                'description' => '首页产品示意图片',
                'content' => '/images/index-product.png',
                'type' => 2,
                'created_at' => 1467793437,
                'updated_at' => 1467793437
            ],
            [
                'id' => 7,
                'description' => '首页标题6（联系）',
                'content' => '有什么想问的和想说的？',
                'type' => 1,
                'created_at' => 1467793437,
                'updated_at' => 1467793437
            ],
            [
                'id' => 8,
                'description' => '首页副标题6（联系）',
                'content' => '随时联系我们吧',
                'type' => 1,
                'created_at' => 1467793437,
                'updated_at' => 1467793437
            ],
            [
                'id' => 9,
                'description' => '首页标题7（关注）',
                'content' => '关注我们的公众微信号',
                'type' => 1,
                'created_at' => 1467793437,
                'updated_at' => 1467793437
            ],
            [
                'id' => 10,
                'description' => '首页副标题7（关注）',
                'content' => '订阅号会推送最新消息，服务号可以获取我们的服务',
                'type' => 1,
                'created_at' => 1467793437,
                'updated_at' => 1467793437
            ],
            [
                'id' => 11,
                'description' => '首页标题8（订阅）',
                'content' => '阅我们的最新消息!',
                'type' => 1,
                'created_at' => 1467793437,
                'updated_at' => 1467793437
            ],
            [
                'id' => 12,
                'description' => '首页副标题8（订阅）',
                'content' => '订阅我们的消息，可以第一时间获得航桓科技的最新产品、动态、优惠、项目信息',
                'type' => 1,
                'created_at' => 1467793437,
                'updated_at' => 1467793437
            ]
        ];

        $table->insert( $data )
              ->save();
    }

}
