<?php
namespace Medical\Model;

use Zend\Db\TableGateway\TableGateway;

class TypeTable
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
	
	public function getType($id)
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

	public function getUserTypes($types)
	{
		$types = explode(",",$types);
		$rowset = array();
		foreach($types as $type)
		{
			$row = $this->tableGateway->select(array('id'=>$type))->current();
			if($row)
				$rowset[] = $row->id;
		}
		return $rowset;
	}


}
