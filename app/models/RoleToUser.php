<?php
/**
* User Model
*/
namespace EloquentModel;
class RoleToUser extends \Illuminate\Database\Eloquent\Model
{
	public $timestamps = false;
	protected $table = 'rel_role_to_user';
}
