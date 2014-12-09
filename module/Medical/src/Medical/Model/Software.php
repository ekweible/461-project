<?php

/*
mysql> desc software;
+-------+--------------+------+-----+---------+-------+
| Field | Type         | Null | Key | Default | Extra |
+-------+--------------+------+-----+---------+-------+
| name  | varchar(255) | NO   | PRI | NULL    |       |
| refip | varchar(255) | NO   | MUL | NULL    |       |
+-------+--------------+------+-----+---------+-------+
2 rows in set (0.00 sec)
*/

namespace Medical\Model;

class Software
{
	public $name;
	public $refip;
	
	public function exchangeArray($data)
	{
		$this->name = (!empty($data['name'])) ? $data['name'] : null;
		$this->refip = (!empty($data['refip'])) ? $data['refip'] : null;
	}
}

