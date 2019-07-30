<?php
/**
* ExamQuestion Model
*/
namespace Module\Exam\EloquentModel;
class ExamQuestion extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'exam_question';
									
	public $timestamps = false;
}