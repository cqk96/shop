<?php

use Phinx\Migration\AbstractMigration;

class TableInsertDataToTableDepartment extends AbstractMigration
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
        
        // 预插入清空对应部门数据
        $this->execute(" truncate  table   `comp_departments`; ");

        // 数据插入
        $table = $this->table("comp_departments");

        $data = [
            ['name'=>'贡河董事会', 'p_department_id'=>0, 'create_time'=>'1532943263', 'update_time'=>'1532943263'],
            ['name'=>'中柬国际农业合作示范园区', 'p_department_id'=>1, 'create_time'=>'1532943263', 'update_time'=>'1532943263'],
            ['name'=>'财务部', 'p_department_id'=>2, 'create_time'=>'1532943263', 'update_time'=>'1532943263'],
            ['name'=>'工程部', 'p_department_id'=>2, 'create_time'=>'1532943263', 'update_time'=>'1532943263'],
            ['name'=>'技术部', 'p_department_id'=>2, 'create_time'=>'1532943263', 'update_time'=>'1532943263'],
            ['name'=>'综合部', 'p_department_id'=>2, 'create_time'=>'1532943263', 'update_time'=>'1532943263'],
            ['name'=>'班组综合部', 'p_department_id'=>2, 'create_time'=>'1532943263', 'update_time'=>'1532943263'],
            ['name'=>'后勤部', 'p_department_id'=>2, 'create_time'=>'1532943263', 'update_time'=>'1532943263'],
            ['name'=>'试验地', 'p_department_id'=>5, 'create_time'=>'1532943263', 'update_time'=>'1532943263'],
            ['name'=>'嫁接组', 'p_department_id'=>5, 'create_time'=>'1532943263', 'update_time'=>'1532943263'],
            ['name'=>'一班组', 'p_department_id'=>7, 'create_time'=>'1532943263', 'update_time'=>'1532943263'],
            ['name'=>'二班组', 'p_department_id'=>7, 'create_time'=>'1532943263', 'update_time'=>'1532943263'],
            ['name'=>'三班组', 'p_department_id'=>7, 'create_time'=>'1532943263', 'update_time'=>'1532943263'],
            ['name'=>'四班组', 'p_department_id'=>7, 'create_time'=>'1532943263', 'update_time'=>'1532943263'],
            ['name'=>'五班组', 'p_department_id'=>7, 'create_time'=>'1532943263', 'update_time'=>'1532943263'],
            ['name'=>'六班组', 'p_department_id'=>7, 'create_time'=>'1532943263', 'update_time'=>'1532943263'],
            ['name'=>'七班组', 'p_department_id'=>7, 'create_time'=>'1532943263', 'update_time'=>'1532943263'],
            ['name'=>'八班组', 'p_department_id'=>7, 'create_time'=>'1532943263', 'update_time'=>'1532943263'],
            ['name'=>'九班组', 'p_department_id'=>7, 'create_time'=>'1532943263', 'update_time'=>'1532943263'],
            ['name'=>'十班组', 'p_department_id'=>7, 'create_time'=>'1532943263', 'update_time'=>'1532943263'],
        ];

        $table->insert($data)
              ->save();

    }

}
