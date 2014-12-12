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
	protected $user;

	/*
	* Get table functions allow controllers to access the database interface classes.
	*/
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
		else
			return $this->redirect()->toRoute('user', array('action' => 'login'));

		if(!$this->user->role)
			$this->layout('layout/user');

		return parent::onDispatch( $e );
	}

	/*
	* Machine homepage Action
	*/

	public function indexAction()
	{
		return new ViewModel(array(
			'messages' => $this->flashMessenger()->getMessages(),
			'role' => $this->user->role,
		));
   	}

	/*
	* Mangage machines action
	*/
	public function queryMachinesAction()
	{
		// Get all machines
		$machines = $this->getMachineTable()->fetchAll();
		$options = array();
		// Build options list for select
		foreach($machines as $machine)
		{
			$options[$machine->machineip]=$machine->machineip;
		}
		// Build form with submit button and select with options bulit before
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
		// Get possible post from form just built.
		$request = $this->getRequest();
		if ($request->isPost()) {
			//fill form with data if returning later
			$form->setData($request->getPost());

			// If nothing on the form is fishy
			if ($form->isValid()) {
				$data = $form->getData();
				// query selected machine and its software
				$m = $this->getMachineTable()->getOneByMachineip($data['machine']);
				$software = $this->getSoftwareTable()->getByMachineip($data['machine']);
			}
		}

		//send data to the view
		return array(
			'm' => $m,
			'software' => $software,
			'form' => $form,
			'messages' => $this->flashMessenger()->getCurrentMessages());
	}


	/*
	* Finding videos by machines action
	*/

	public function queryVideosAction()
	{
		// Get all machines
		$machines = $this->getMachineTable()->fetchAll();
		$options = array();
		// Build options list for select
		foreach($machines as $machine)
		{
			$options[$machine->machineip]=$machine->machineip;
		}

		// Build form with submit button and select with options bulit before
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


		// Get possible post from form just built.
		$request = $this->getRequest();
		if ($request->isPost()) {
			//fill form with data if returning later
			$form->setData($request->getPost());
			// If nothing on the form is fishy
			if ($form->isValid()) {
				$data = $form->getData();
				// query videos
				$videos = $this->getVideoTable()->getByMachineip($data['machine']);
			}
		}
		

		//send data to the view
		return array(
			'videos' => $videos,
			'form' => $form,
			'messages' => $this->flashMessenger()->getCurrentMessages());
	}
}
