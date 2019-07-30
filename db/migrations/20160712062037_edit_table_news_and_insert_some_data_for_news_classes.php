<?php

use Phinx\Migration\AbstractMigration;

class EditTableNewsAndInsertSomeDataForNewsClasses extends AbstractMigration
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
        //新闻表修改
        $table_news = $this->table('comp_news');
        $comment_count = $table_news->hasColumn('comment_count');
        if(!$comment_count){
            $table_news->addColumn('comment_count', 'biginteger',array('null'=>true,'default'=>0))
                       ->save();
        }

        $collect = $table_news->hasColumn('collect');
        if(!$collect){
            $table_news->addColumn('collect', 'biginteger',array('null'=>true,'default'=>0))
                       ->save();
        }

        $url = $table_news->hasColumn('url');
        if(!$url){
            $table_news->addColumn('url', 'string',array('null'=>true))
                       ->save();
        }

        // $newsClass = $this->table('comp_news_classes');
        // $newsClass->insert($newsClasses)
        //             ->save();
    }

}
