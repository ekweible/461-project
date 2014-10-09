<?php
namespace Food\Model;

class Food
{
	public $id;
	public $name;
	
	public function exchangeArray($data)
	{
		$this->id = (!empty($data['id'])) ? $data['id'] : null;
		$this->name = (!empty($data['id'])) ? $data['name'] : null;
		$this->lat = (!empty($data['id'])) ? $data['lat'] : null;
		$this->lng = (!empty($data['id'])) ? $data['lng'] : null;
	}
}