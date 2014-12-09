<?php
namespace Medical\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;

class MedicalController extends AbstractActionController
{
	protected $userTable;
	protected $foodTable;
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
	public function onDispatch( \Zend\Mvc\MvcEvent $e )
	{
		$session = new Container('user');
		if ($session && $session->uid) 
		{
			$this->user = $session->uid;
		}
		else
			return $this->redirect()->toRoute('user', array('action' => 'index'));


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
			return $this->redirect()->toRoute('user', array('action' => 'index'));

	}

}
