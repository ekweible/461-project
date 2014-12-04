<?php
namespace Medical\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Reservation implements InputFilterAwareInterface
{
	public $id;
	public $location;
	public $title;
	public $creator;
	protected $inputFilter;
	
	public function exchangeArray($data)
	{
		$this->id = (isset($data['id'])) ? $data['id'] : null;
		$this->location = (isset($data['location'])) ? $data['location'] : null;
		$this->title = (isset($data['title'])) ? $data['title'] : null;
		$this->creator = (isset($data['creator'])) ? $data['creator'] : null;
	}
	
	public function getArrayCopy()
	{
		return get_object_vars($this);
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
				'name' => 'id',
				'required' => true,
				'filters' => array(
					array('name'=>'Int'),
				),
			)));
			$inputFilter->add($factory->createInput(array(
				'name'=> 'location',
				'required' => true,
				'filters' => array(
					array('name'=>'Int'),
				),
			)));
			$inputFilter->add($factory->createInput(array(
				'name'=>'title',
				'required'=>true,
				'filters'=>array(
					array('name'=>'StripTags'),
					array('name'=>'StringTrim'),
				),
				'validators'=>array(
					array(
						'name'=>'StringLength',
						'options'=>array(
							'encoding'=>'UTF-8',
							'min'=>1,
							'max'=>100,
						),
					),
				),
			)));
			$this->inputFilter=$inputFilter;
		}
		return $this->inputFilter;
	}
}
