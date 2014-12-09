<?php
namespace Medical\Form;

use Zend\Form\Form;

class RoomForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('room');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name'=>'roomid',
            'type'=>'Hidden',
        ));
        $this->add(array(
            'name'=> 'roomnum',
            'type' => 'Text',
            'options'=>array(
                'label'=> 'Room Number:',
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
