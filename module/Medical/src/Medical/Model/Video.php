<?php

/*
desc video;
+-------------------+-------------+------+-----+---------+-------+
| Field             | Type        | Null | Key | Default | Extra |
+-------------------+-------------+------+-----+---------+-------+
| videoid           | int(11)     | NO   | PRI | NULL    |       |
| capturedvideoname | varchar(45) | YES  |     | NULL    |       |
| machineip         | varchar(45) | YES  | MUL | NULL    |       |
| captureddatetime  | datetime    | YES  |     | NULL    |       |
| dateanalysisdone  | datetime    | YES  |     | NULL    |       |
| uploadedfilename  | varchar(45) | YES  |     | NULL    |       |
| analysisdirname   | varchar(45) | YES  |     | NULL    |       |
| roomid            | int(11)     | YES  |     | NULL    |       |
| size              | int(11)     | YES  |     | NULL    |       |
| length            | int(11)     | YES  |     | NULL    |       |
+-------------------+-------------+------+-----+---------+-------+
10 rows in set (0.00 sec)

*/

namespace Medical\Model;

class Software
{
	public $videoid;
	public $capturedvideoname;
	public $machineip;
	public $captureddatetime;
	public $dateanalysisdone;
	public $uploadedfilename;
	public $analysisdirname;
	public $roomid;
	public $size;
	public $length;
	
	public function exchangeArray($data)
	{
		$this->videoid = (!empty($data['videoid'])) ? $data['videoid'] : null;
		$this->capturedvideoname = (!empty($data['capturedvideoname'])) ? $data['capturedvideoname'] : null;
		$this->machineip = (!empty($data['machineip'])) ? $data['machineip'] : null;
		$this->captureddatetime = (!empty($data['captureddatetime'])) ? $data['captureddatetime'] : null;
		$this->dateanalysisdone = (!empty($data['dateanalysisdone'])) ? $data['dateanalysisdone'] : null;
		$this->uploadedfilename = (!empty($data['uploadedfilename'])) ? $data['uploadedfilename'] : null;
		$this->analysisdirname = (!empty($data['analysisdirname'])) ? $data['analysisdirname'] : null;
		$this->roomid = (!empty($data['roomid'])) ? $data['roomid'] : null;
		$this->size = (!empty($data['size'])) ? $data['size'] : null;
		$this->length = (!empty($data['length'])) ? $data['length'] : null;
	}
}

