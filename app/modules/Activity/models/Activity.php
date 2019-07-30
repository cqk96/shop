<?php
/**
* Activity Model
*/
namespace Module\Activity\EloquentModel;
class Activity extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'activity';
									
	public $timestamps = false;
}