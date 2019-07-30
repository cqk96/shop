<?php
/**
* FrontSource Model
*/
namespace EloquentModel;
class FrontSource extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'front_sources'; //不含前缀的表名
	public $timestamps  = false;

}
