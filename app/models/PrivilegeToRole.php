<?php
/**
* User Model
*/
namespace EloquentModel;
class PrivilegeToRole extends \Illuminate\Database\Eloquent\Model
{
	public $timestamps = false;
	protected $table = 'rel_privilege_to_role';
}
