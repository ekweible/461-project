<?php
namespace Medical\Controller;
use Zend\Form\Form;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;

class MedicalController extends AbstractActionController
{
	protected $userTable;
	protected $machineTable;
	protected $softwareTable;
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

		if(!$this->user->role)
			$this->layout('layout/user');

		return parent::onDispatch( $e );
	}

	public function indexAction()
	{
		return new ViewModel(array(
			'messages' => $this->flashMessenger()->getMessages(),
			'role' => $this->user->role,
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
				$machines = $this->getMachineTable()->getOneByMachineip($data['machine']);
				$software = $this->getSoftwareTable()->getByMachineip($data['machine']);
			}
		}

		return array(
			'm' => $machines,
			'software' => $software,
			'form' => $form,
			'messages' => $this->flashMessenger()->getCurrentMessages());
	}
}
