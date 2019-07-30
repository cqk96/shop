<?php
/**
* DepartmentRelUser Model
*/
namespace EloquentModel;
class DepartmentRelUser extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'department_rel_user';
									
	public $timestamps = false;
	
	function lists(){
		
	return \EloquentModel\DepartmentRelUser::where('is_deleted','=',0)->orderBy('id','asc')->get();
	
	
	
	}
	
}