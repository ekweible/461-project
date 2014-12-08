<?php
namespace Medical\Form;

use Zend\Form\Form;

class PasswordRecoveryForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('passwordRecovery');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name'=>'id',
            'type'=>'Hidden',
        ));
        $this->add(array(
            'name'=> 'email',
            'type' => 'Text',
            'options'=>array(
                'label'=> 'Email:',
            ),
        ));
        $this->add(array(
            'name'=>'submit',
            'type'=>'submit',
            'attributes'=>array(
                'value'=>'Recover Password',
                'id'=>'submitbutton',
            ),
        ));

    }
}
