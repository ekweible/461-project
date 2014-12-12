<?php
namespace Medical\Controller;
use Zend\Form\Form;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;


/*
 * home page controller
*/

class MedicalController extends AbstractActionController
{
	protected $userTable;
	protected $machineTable;
	protected $softwareTable;
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
	* Home page action
	*/
	public function indexAction()
	{
		return new ViewModel(array(
			'messages' => $this->flashMessenger()->getMessages(),
			'role' => $this->user->role,
		));
   	}

	/*
	* logout Action does not actually have a view
	* Redirects to login action within the user controller
	*/
	public function logoutAction()
	{
		$session = new Container('user');
		$session->getManager()->getStorage()->clear('user');
		return $this->redirect()->toRoute('user', array('action' => 'login'));

	}

}
