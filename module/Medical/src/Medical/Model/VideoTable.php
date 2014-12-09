<?php
namespace Medical\Model;

use Zend\Db\TableGateway\TableGateway;

class VideoTable
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
	
	public function getOneByVideoid($id)
	{
		$rowset = $this->tableGateway->select(array('videoid'=>$id));
		$row = $rowset->current();
		if(!$row)
		{
			throw new \Exception("could not find row $id");
		}
		return $row;
	}
}
