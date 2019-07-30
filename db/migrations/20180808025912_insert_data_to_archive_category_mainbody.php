<?php

use Phinx\Migration\AbstractMigration;

class InsertDataToArchiveCategoryMainbody extends AbstractMigration
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

        $this->execute(" truncate table `comp_archive_category_to_main_body`; ");

        $table = $this->table("comp_archive_category_to_main_body");

        $data = [
            ['archive_template_category_id'=>1,'main_body_type_id'=>3,'create_time'=>1533696679, 'update_time'=>1533696679],
            ['archive_template_category_id'=>2,'main_body_type_id'=>3,'create_time'=>1533696679, 'update_time'=>1533696679],
            ['archive_template_category_id'=>3,'main_body_type_id'=>3,'create_time'=>1533696679, 'update_time'=>1533696679],
            ['archive_template_category_id'=>4,'main_body_type_id'=>3,'create_time'=>1533696679, 'update_time'=>1533696679],
            ['archive_template_category_id'=>5,'main_body_type_id'=>3,'create_time'=>1533696679, 'update_time'=>1533696679],
            ['archive_template_category_id'=>6,'main_body_type_id'=>3,'create_time'=>1533696679, 'update_time'=>1533696679],
            ['archive_template_category_id'=>7,'main_body_type_id'=>3,'create_time'=>1533696679, 'update_time'=>1533696679],
            ['archive_template_category_id'=>8,'main_body_type_id'=>3,'create_time'=>1533696679, 'update_time'=>1533696679],
            ['archive_template_category_id'=>9,'main_body_type_id'=>3,'create_time'=>1533696679, 'update_time'=>1533696679],
            ['archive_template_category_id'=>10,'main_body_type_id'=>3,'create_time'=>1533696679, 'update_time'=>1533696679],

            ['archive_template_category_id'=>1,'main_body_type_id'=>5,'create_time'=>1533696679, 'update_time'=>1533696679],
            ['archive_template_category_id'=>2,'main_body_type_id'=>5,'create_time'=>1533696679, 'update_time'=>1533696679],
            ['archive_template_category_id'=>3,'main_body_type_id'=>5,'create_time'=>1533696679, 'update_time'=>1533696679],
            ['archive_template_category_id'=>4,'main_body_type_id'=>5,'create_time'=>1533696679, 'update_time'=>1533696679],
            ['archive_template_category_id'=>5,'main_body_type_id'=>5,'create_time'=>1533696679, 'update_time'=>1533696679],
            ['archive_template_category_id'=>6,'main_body_type_id'=>5,'create_time'=>1533696679, 'update_time'=>1533696679],
            ['archive_template_category_id'=>7,'main_body_type_id'=>5,'create_time'=>1533696679, 'update_time'=>1533696679],
            ['archive_template_category_id'=>8,'main_body_type_id'=>5,'create_time'=>1533696679, 'update_time'=>1533696679],
            ['archive_template_category_id'=>9,'main_body_type_id'=>5,'create_time'=>1533696679, 'update_time'=>1533696679],
            ['archive_template_category_id'=>11,'main_body_type_id'=>5,'create_time'=>1533696679, 'update_time'=>1533696679],
            ['archive_template_category_id'=>12,'main_body_type_id'=>5,'create_time'=>1533696679, 'update_time'=>1533696679],
        ];

        $table->insert( $data )
              ->save();
    }
}
