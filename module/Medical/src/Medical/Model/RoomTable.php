<?php
namespace Medical\Model;

use Zend\Db\TableGateway\TableGateway;

class RoomTable
{
	protected $tableGateway;
    protected $machineTable;
	
	public function __construct(TableGateway $tableGateway)
	{
		$this->tableGateway = $tableGateway;
	}

    public function getMachineTable()
    {
        if(!$this->machineTable)
        {
            $sm = $this->getServiceLocator();
            $this->machineTable = $sm->get('Medical\Model\MachineTable');
        }
        return $this->machineTable;
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
            return false;
        }
        return $row;
    }

    public function getRooms()
    {
        return $this->fetchAll();
    }

    public function getRoomOptions()
    {
        $rooms = $this->getRooms();
        $roomOptions = array('-1' => '-- All --');
        foreach($rooms as $room) {
            $roomOptions[$room->roomid] = $room->roomnum;
        }
        asort($roomOptions);
        return $roomOptions;
    }

    public function saveRoom(Room $room)
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
	public function deleteRoom($id)
	{
		$this->tableGateway->delete(array('roomid' => $id));
	}
}
