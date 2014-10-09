<?php
namespace Food\Model;

use Zend\Db\TableGateway\TableGateway;

class FoodTable
{
	protected $tableGateway;
	
	public function __construct(TableGateway $tableGateway)
	{
		$this->tableGateway = $tableGateway;
	}
	
	public function fetchAll()
	{
		$resultSet = $this->tableGateway->select();
		return $resultSet;
	}
	
	public function getFood($id)
	{
		$id = (int) $id;
		$rowset = $this->tableGateway->select(array('id'=>$id));
		$row = $rowset->current();
		if(!$row)
		{
			throw new \Exception("could not find row $id");
		}
		return $row;
	}
	
	public function saveFood(Food $food)
	{
		$data = array(
			'name'=>$food->name,
		);
		$id=(int)$food->id;
		if($id==0)
		{
			$this->tableGateway->insert($data);
		}
		else
		{
			if($this->getAlbum($id))
			{
				$this->tableGateway->update($data,array('id'=>$id));
			}
			else
			{
				throw new \Exception('Form id does not exist');
			}
		}
	}
	public function deleteFood($id)
	{
		$this->tableGateway->delete(array('id'=>$id));
	}
}