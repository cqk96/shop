<?php
/**
* User Model
*/
namespace EloquentModel;
class RoleToMenu extends \Illuminate\Database\Eloquent\Model
{
	public $timestamps = false;
	protected $table = 'rel_role_to_menu';
}
