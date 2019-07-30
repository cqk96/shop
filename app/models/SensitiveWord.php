<?php
/**
* User Model
*/
namespace EloquentModel;
class SensitiveWord extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'sensitive_words';
    public $timestamps = false;
}
