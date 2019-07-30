<?php
namespace VirgoModel;
class SensitiveWordModel {
	protected $sensitiveWordObj = '';

	public function __construct()
	{
		$this->sensitiveWordObj = new \EloquentModel\SensitiveWord;
	}

	public function index()
	{
		return $this->sensitiveWordObj->all();
	}

	public function read($id)
	{
		return $this->sensitiveWordObj->find($id);
	}

	public function doUpdate()
	{
		$id = $_POST['id'];

		unset($_POST['id']);

		return $this->sensitiveWordObj->where('id',$id)->update($_POST);
	}

}