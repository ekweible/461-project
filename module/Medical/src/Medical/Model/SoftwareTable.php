<?php
namespace Medical\Model;

use Zend\Db\TableGateway\TableGateway;

class SoftwareTable
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
	
	public function getOneByName($id)
	{
		$rowset = $this->tableGateway->select(array('name'=>$id));
		$row = $rowset->current();
		if(!$row)
		{
			throw new \Exception("could not find row $id");
		}
		return $row;
	}
	public function getByMachineip($id)
	{
		$rowset = $this->tableGateway->select(array('refip'=>$id));

		return $rowset;
	}


}
