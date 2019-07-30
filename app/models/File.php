<?php
/**
* File Model
*/
namespace EloquentModel;
class File extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'files'; //不含前缀的表名
	public $timestamps  = false;

}
