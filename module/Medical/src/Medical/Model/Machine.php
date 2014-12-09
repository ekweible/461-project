<?php

/*
mysql> desc machine;
+-----------+--------------+------+-----+---------+-------+
| Field     | Type         | Null | Key | Default | Extra |
+-----------+--------------+------+-----+---------+-------+
| machineip | varchar(255) | NO   | PRI | NULL    |       |
| status    | int(11)      | YES  |     | 0       |       |
+-----------+--------------+------+-----+---------+-------+
2 rows in set (0.01 sec)
*/

namespace Medical\Model;

class Machine
{
	public $machineip;
	public $status;
	
	public function exchangeArray($data)
	{
		$this->machineip = (!empty($data['machineip'])) ? $data['machineip'] : null;
		$this->status = (!empty($data['status'])) ? $data['status'] : null;
	}
}

