<?php
namespace Medical\Model;

use Zend\Db\TableGateway\TableGateway;

class ReservationTable
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
	
	public function getReservation($id)
	{
		$id=(int)$id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		if(!$row)
		{
			throw new \Exception("could not find row $id");
		}
		return $row;
	}

	public function getReservationsByShared($shared)
	{
		$shared = explode(",",$shared);
		$rowset = array();
		foreach( $shared as $share)
			$rowset[] = $this->tableGateway->select(array('id' => $share))->current();


		return $rowset;
	}
	public function getReservationsByCreator($id)
	{
		$id=(int)$id;
		$rowset = $this->tableGateway->select(array('creator' => $id));
		return $rowset;
	}
	
	public function saveReservation(Reservation $reservation)
	{
		$data = array(
			'location' => $reservation->location,
			'title' => $reservation->title,
			'creator' => $reservation->creator,
		);
		
		$id=(int)$reservation->id;
		if($id == 0)
		{
			$this->tableGateway->insert($data);
		}
		else
		{
			if($this->getReservation($id))
			{
				$this->tableGateway->update($data, array('id' => $id));
			}
			else
			{
				throw new \Exception('form id does not exist');
			}
		}
		return $this->tableGateway->lastInsertValue;
	}
	public function deleteReservation($id)
	{
		$this->tableGateway->delete(array('id' => $id));
	}
}
	
