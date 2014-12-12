<?php
namespace Medical\Controller;
use Medical\Form\PasswordRecoveryForm;
use Medical\Form\RoomForm;
use Medical\Model\PasswordRecovery;
use Zend\Form\Form;
use Medical\Model\Room;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Config\SessionConfig;
use Zend\Session\Container;
use Zend\Session\SessionManager;

/*
* Controller for contents of Rooms item in navigation
*/
class RoomController extends AbstractActionController
{
	protected $roomTable;
	protected $videoTable;
	protected $machineTable;
	protected $user;

	/*
	* Dispatch functions allow code to be run before all controllers
	*/
	public function onDispatch( \Zend\Mvc\MvcEvent $e )
	{
		$session = new Container('user');
		if ($session && $session->uid)
		{
			$this->user = $session->uid;
		}


		if(!$this->user->role)
			$this->layout('layout/user');
		return parent::onDispatch( $e );
	}

	/*
	* Get table functions allow controllers to access the database interface classes.
	*/
	public function getRoomTable()
	{
		if(!$this->roomTable)
		{
			$sm = $this->getServiceLocator();
			$this->roomTable = $sm->get('Medical\Model\RoomTable');
		}
		return $this->roomTable;
	}
	public function getVideoTable()
	{
		if(!$this->videoTable)
		{
			$sm = $this->getServiceLocator();
			$this->videoTable = $sm->get('Medical\Model\VideoTable');
		}
		return $this->videoTable;
	}
	public function getMachineTable()
	{
		if(!$this->machineTable)
		{
			$sm = $this->getServiceLocator();
			$this->machineTable = $sm->get('Medical\Model\MachineTable');
		}
		return $this->machineTable;
	}
	/*
	* Room page home action 
	*/
	public function indexAction()
	{
		return array('messages' => $this->flashMessenger()->getCurrentMessages(),
			'role' => $this->user->role,
			);
	}

	public function addAction()
	{
		$form = new RoomForm();
		$form->get('submit')->setValue('Add Form');

		$request = $this->getRequest();
		if ($request->isPost()) {
			$room = new Room();
			$form->setInputFilter($room->getInputFilter());
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$data = $form->getData();
				$id = $this->getRoomTable()->getRoomByNum($data['roomnum']);
				if(!$id)
				{
					$room->exchangeArray($data);
					$id = $this->getRoomTable()->saveRoom($room);
					$this->flashMessenger()->addMessage('Room ' . $room->roomnum . ' created.');
				}
				else
				{
					$this->flashMessenger()->addMessage('Room already exists.');
				}
			}
		}
		return array(	'form' => $form,
			'messages' => $this->flashMessenger()->getCurrentMessages());
	}

	public function queryAction()
	{
		$form  = new RoomForm();
		$form->remove('roomid');
		$form->remove('roomnum');
		$form->add(array(
			'name'=>'roomid',
			'type'=>'Select',
			'options'=>array(
				'label'=>'Room Number: ',
				'value_options' => $this->getRoomTable()->getRoomOptions()
			)
		));
		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$data = $form->getData();
				if ($data['roomid'] && $data['roomid'] != -1) {
					$room = $this->getRoomTable()->getRoomById($data['roomid']);
					$room->machineip = $this->getMachineTable()->getOneByRoom($room->roomid)->machineip;
					$rooms = array($room);
				} else {
					$roomsWithoutMachine = $this->getRoomTable()->getRooms();
					$rooms = array();
					foreach($roomsWithoutMachine as $room) {
						$roomWithMachine = new Room();
						$roomWithMachine->roomid = $room->roomid;
						$roomWithMachine->roomnum = $room->roomnum;
						$roomWithMachine->machineip = $this->getMachineTable()->getOneByRoom($room->roomid)->machineip;
						array_push($rooms, $roomWithMachine);
					}
				}
			}
		}

		return array(
			'rooms' => $rooms,
			'form' => $form,
			'messages' => $this->flashMessenger()->getCurrentMessages());
	}

	public function selectEditAction()
	{
		$id = $this->user->id;
		if (!$id) {
			return $this->redirect()->toRoute('room', array(
				'action' => 'add'
			));
		}
		$options = $this->getRoomTable()->getRoomOptions();
		unset($options['-1']);
		$form  = new Form();
		$form->add(array(
			'name'=>'submit',
			'type'=>'submit',
			'attributes'=>array(
				'value'=>'Submit',
				'id'=>'submitbutton',
			),
		));
		$form->add(array(
			'name'=> 'roomid',
			'type' => 'Select',
			'options'=>array(
				'label'=> 'Room: ',
				'options'=>$options
			),
		));
		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$data = $form->getData();
				return $this->redirect()->toRoute('room', array('action' => 'edit', 'id' => $data['roomid']));
			}
		}

		return array(
			'id' => $id,
			'form' => $form,
			'messages' => $this->flashMessenger()->getCurrentMessages());
	}

	public function editAction()
	{
		//$id = $this->user->id;
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('room', array(
				'action' => 'add'
			));
		}
		$room = $this->getRoomTable()->getRoomById($id);
		$form  = new RoomForm();
		$form->bind($room);
		$form->get('submit')->setAttribute('value', 'Edit');
		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setInputFilter($room->getInputFilter());
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$data = $form->getData();
				$roomExists = $this->getRoomTable()->getRoomByNum($data->roomnum);
				if(!$roomExists)
				{
					$this->getRoomTable()->saveRoom($data);

					// Redirect to list of rooms
					return $this->redirect()->toRoute('room');
				}
				else
				{
					$this->flashMessenger()->addMessage('Room already exists');
				}
			}
		}

		return array(
			'id' => $id,
			'form' => $form,
			'messages' => $this->flashMessenger()->getCurrentMessages());
	}

	public function deleteAction()
	{
		$id = $this->user->id;
		if (!$id) {
			return $this->redirect()->toRoute('user', array(
				'action' => 'add'
			));
		}
		$rooms = $this->getRoomTable()->fetchAll();
		$options = array();
		foreach($rooms as $room)
		{
			$options[$room->roomid]=$room->roomnum;
		}
		$form  = new Form();
		$form->add(array(
			'name'=>'submit',
			'type'=>'submit',
			'attributes'=>array(
				'value'=>'Submit',
				'id'=>'submitbutton',
			),
		));
		$form->add(array(
			'name'=> 'room',
			'type' => 'Select',
			'options'=>array(
				'label'=> 'Room: ',
				'options'=>$options
			),
		));
		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$data = $form->getData();
				$user = $this->getRoomTable()->deleteRoom($data['room']);
				$this->flashMessenger()->addMessage('Room Deleted');
				return $this->redirect()->toRoute('room', array('action' => 'delete'));
			}
		}

		return array(
			'id' => $id,
			'form' => $form,
			'messages' => $this->flashMessenger()->getCurrentMessages());
	}

	public function queryVideosAction()
	{
		$rooms = $this->getRoomTable()->fetchAll();
		$options = array();
		foreach($rooms as $room)
		{
			$options[$room->roomid]=$room->roomnum;
		}
		$form  = new Form();
		$form->add(array(
			'name'=>'submit',
			'type'=>'submit',
			'attributes'=>array(
				'value'=>'Submit',
				'id'=>'submitbutton',
			),
		));
		$form->add(array(
			'name'=> 'room',
			'type' => 'Select',
			'attributes' => array(
				'multiple' => 'multiple',
			),
			'options'=>array(
				'label'=> 'Room: ',
				'options'=>$options
			),
		));
		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$data = $form->getData();
				$r = $this->getRoomTable()->getRoomById($data['room']);
				$videos = $this->getVideoTable()->getByRoomid($data['room']);
			}
		}

		return array(
			'r' => $r,
			'videos' => $videos,
			'form' => $form,
			'messages' => $this->flashMessenger()->getCurrentMessages());
	}

}
