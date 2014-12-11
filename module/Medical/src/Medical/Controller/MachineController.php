<?php
namespace Medical\Controller;
use Zend\Form\Form;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;

class MachineController extends AbstractActionController
{
	protected $userTable;
	protected $machineTable;
	protected $softwareTable;
	protected $videoTable;
	protected $reservationTable;
	protected $user;
	public function getUserTable()
	{
		if(!$this->userTable)
		{
			$sm = $this->getServiceLocator();
			$this->userTable = $sm->get('Medical\Model\UserTable');
		}
		return $this->userTable;
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
	public function getSoftwareTable()
	{
		if(!$this->softwareTable)
		{
			$sm = $this->getServiceLocator();
			$this->softwareTable = $sm->get('Medical\Model\SoftwareTable');
		}
		return $this->softwareTable;
	}
	public function onDispatch( \Zend\Mvc\MvcEvent $e )
	{
		$session = new Container('user');
		if ($session && $session->uid) 
		{
			$this->user = $session->uid;
		}
		else
			return $this->redirect()->toRoute('user', array('action' => 'login'));


		return parent::onDispatch( $e );
	}

	public function indexAction()
	{
		return new ViewModel(array(
			'messages' => $this->flashMessenger()->getMessages(),
		));
   	}

	public function logoutAction()
	{
		$session = new Container('user');
		$session->getManager()->getStorage()->clear('user');
		return $this->redirect()->toRoute('user', array('action' => 'login'));

	}

	public function queryMachinesAction()
	{
		$machines = $this->getMachineTable()->fetchAll();
		$options = array();
		foreach($machines as $machine)
		{
			$options[$machine->machineip]=$machine->machineip;
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
			'name'=> 'machine',
			'type' => 'Select',
			'options'=>array(
				'label'=> 'Machine IP: ',
				'options'=>$options
			),
		));
		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$data = $form->getData();
				$m = $this->getMachineTable()->getOneByMachineip($data['machine']);
				$software = $this->getSoftwareTable()->getByMachineip($data['machine']);
			}
		}

		return array(
			'm' => $m,
			'software' => $software,
			'form' => $form,
			'messages' => $this->flashMessenger()->getCurrentMessages());
	}

	public function queryVideosAction()
	{
		$machines = $this->getMachineTable()->fetchAll();
		$options = array();
		foreach($machines as $machine)
		{
			$options[$machine->machineip]=$machine->machineip;
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
			'name'=> 'machine',
			'type' => 'Select',
			'attributes' => array(
				'multiple' => 'multiple',
			),
			'options'=>array(
				'label'=> 'Machine IP: ',
				'options'=>$options
			),
		));
		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$data = $form->getData();
				$m = $this->getMachineTable()->getOneByMachineip($data['machine']);
				$videos = $this->getVideoTable()->getByMachineip($data['machine']);
			}
		}

		return array(
			'videos' => $videos,
			'form' => $form,
			'messages' => $this->flashMessenger()->getCurrentMessages());
	}
}
