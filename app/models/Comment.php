<?php
/**
*  Model
*/
namespace EloquentModel;
class Comment extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'comments';
									
	public $timestamps = false;
}