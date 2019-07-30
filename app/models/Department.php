<?php
/**
* Department Model
*/
namespace EloquentModel;
class Department extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'departments'; //不含前缀的表名
	public $timestamps  = false;
	
	
	public function lists()
	{
		return \EloquentModel\Department::where('is_deleted','=',0)->orderBy('id','asc')->get();
	}
	
	
	
}
