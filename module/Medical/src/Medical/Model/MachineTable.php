<?php
namespace Medical\Model;

use Zend\Db\TableGateway\TableGateway;

class MachineTable
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
	
	public function getOneByMachineip($id)
	{
		$rowset = $this->tableGateway->select(array('machineip'=>$id));
		$row = $rowset->current();
		if(!$row)
		{
			throw new \Exception("could not find row $id");
		}
		return $row;
	}

    public function getOneByRoom($id)
    {
        $rowset = $this->tableGateway->select(array('roomid'=>$id));
        $row = $rowset->current();
        if (!$row)
        {
            return false;
        }
        return $row;
    }
}
