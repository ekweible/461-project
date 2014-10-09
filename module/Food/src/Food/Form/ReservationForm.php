<?php
namespace Food\Form;

use Zend\Form\Form;

class ReservationForm extends Form
{
	public function __construct($name = null)
	{
		//was album, changed to reservation. Might be food
		parent::__construct('reservation');
		$this->setAttribute('method', 'post');
		$this->add(array(
			'name'=>'id',
			'type'=>'Hidden',
		));
		$this->add(array(
			'name'=> 'title',
			'type' => 'Text',
			'options'=>array(
				'label'=> 'Title',
			),
		));
		$this->add(array(
			'name'=>'location',
			'type'=>'Hidden',
		));
/*		$this->add(array(
			'name'=>'location',
			'type'=>'Text',
			'options'=>array(
				'label'=>'Location',
			),
		));
*/
		$this->add(array(
			'name'=>'submit',
			'type'=>'submit',
			'attributes'=>array(
				'value'=>'Go',
				'id'=>'submitbutton',
			),
		));
	}
}
