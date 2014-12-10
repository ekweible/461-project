<?php

/*
mysql> desc machine;
+-------------+--------------+------+-----+---------+-------+
| Field       | Type         | Null | Key | Default | Extra |
+-------------+--------------+------+-----+---------+-------+
| machineip   | varchar(255) | NO   | PRI | NULL    |       |
| status      | int(11)      | NO   |     | 0       |       |
| capacity    | int(11)      | NO   |     | NULL    |       |
| storageused | int(11)      | NO   |     | NULL    |       |
| roomid      | int(11)      | NO   | MUL | NULL    |       |
+-------------+--------------+------+-----+---------+-------+
5 rows in set (0.00 sec)
*/

namespace Medical\Model;

class Machine
{
	public $machineip;
	public $status;
	public $capacity;
	public $storageused;
	public $roomid;
	
	public function exchangeArray($data)
	{
		$this->machineip = (!empty($data['machineip'])) ? $data['machineip'] : null;
		$this->status = (!empty($data['status'])) ? $data['status'] : null;
		$this->capacity = (!empty($data['capacity'])) ? $data['capacity'] : null;
		$this->storageused= (!empty($data['storageused'])) ? $data['storageused'] : null;
		$this->roomid = (!empty($data['roomid'])) ? $data['roomid'] : null;
	}
}

