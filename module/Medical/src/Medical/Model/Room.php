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

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;


class Room implements InputFilterAwareInterface
{
	public $roomid;
	public $roomnum;
    protected $inputFilter;
	
	public function exchangeArray($data)
	{
		$this->roomid = (!empty($data['roomid'])) ? $data['roomid'] : null;
		$this->roomnum = (!empty($data['roomnum'])) ? $data['roomnum'] : null;
	}

    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("not used");
    }

    public function getInputFilter()
    {
        if(!$this->inputFilter)
        {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                'name' => 'roomid',
                'required' => true,
                'filters' => array(
                    array('name'=>'Int'),
                ),
            )));
            $inputFilter->add($factory->createInput(array(
                'name'=>'roomnum',
                'required'=>true,
                'filters'=>array(
                    array('name'=>'Int'),
                ),
            )));
            $this->inputFilter=$inputFilter;
        }
        return $this->inputFilter;
    }
}

