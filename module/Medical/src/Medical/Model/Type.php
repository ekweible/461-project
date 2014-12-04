<?php
namespace Medical\Model;

class Type
{
	public $id;
	public $name;
	
	public function exchangeArray($data)
	{
		$this->id = (!empty($data['id'])) ? $data['id'] : null;
		$this->name = (!empty($data['id'])) ? $data['name'] : null;
	}
}

