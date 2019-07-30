<?php

use Phinx\Migration\AbstractMigration;

class AddSignInTimeForTableActivity extends AbstractMigration
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
        $table = $this->table("comp_activity");

        $table ->addColumn("sign_in_start_time", "integer", array("null"=>true, "comment"=>"签到起始时间", "after"=>"apply_end_time" ) )
               ->addColumn("sign_in_end_time", "integer", array("null"=>true, "comment"=>"签到结束时间", "after"=>"sign_in_start_time" ))
               ->save();
    }

}
