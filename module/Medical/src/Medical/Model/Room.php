<?php

/*
mysql> desc room;
+---------+-------------+------+-----+---------+-------+
| Field   | Type        | Null | Key | Default | Extra |
+---------+-------------+------+-----+---------+-------+
| RoomID  | int(11)     | NO   | PRI | NULL    |       |
| RoomNum | varchar(45) | NO   |     | NULL    |       |
+---------+-------------+------+-----+---------+-------+
2 rows in set (0.00 sec)
*/

namespace Medical\Model;

class Room
{
	public $RoomID;
	public $RoomNum;
	
	public function exchangeArray($data)
	{
		$this->RoomID = (!empty($data['RoomID'])) ? $data['RoomID'] : null;
		$this->RoomNum = (!empty($data['RoomNum'])) ? $data['RoomNum'] : null;
	}
}

