<?php
namespace Medical\Model;

use Zend\Db\TableGateway\TableGateway;

class RoomTable
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
	
	public function getRoomById($id)
	{
		$rowset = $this->tableGateway->select(array('roomid'=>$id));
		$row = $rowset->current();
		if(!$row)
		{
			throw new \Exception("could not find row $id");
		}
		return $row;
	}

    public function getRoomByNum($num)
    {
        $rowset = $this->tableGateway->select(array('roomnum'=>$num));
        $row = $rowset->current();
        if (!$row)
        {
            return null;
        }
        return $row;
    }

    public function saveUser(Room $room)
    {
        $data = array(
            'roomnum' => $room->roomnum,
        );
        $id=(int)$room->roomid;
        if($id == 0)
        {
            $this->tableGateway->insert($data);
        }
        else
        {
            if($this->getRoomById($id))
            {
                $this->tableGateway->update($data, array('roomid' => $id));
            }
            else
            {
                throw new \Exception('form id does not exist');
            }
        }
        return $this->tableGateway->lastInsertValue;
    }
}
