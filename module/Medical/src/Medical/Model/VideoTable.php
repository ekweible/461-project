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
	public function getByMachineip($id)
	{
		$rowset = $this->tableGateway->select(array('machineip'=>$id));
		return $rowset;
	}
	public function getByRoomid($id)
	{
		$rowset = $this->tableGateway->select(array('roomid'=>$id));
		return $rowset;
	}
}
