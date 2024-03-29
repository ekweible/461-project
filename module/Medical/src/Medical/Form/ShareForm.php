<?php
namespace Medical\Form;

use Zend\Form\Form;

class ShareForm extends Form
{
	public function __construct($name = null)
	{
		parent::__construct('user');
		$this->setAttribute('method', 'post');
		$this->add(array(
			'name'=> 'username',
			'type' => 'Text',
			'options'=>array(
				'label'=> 'Username:',
			),
		));
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
