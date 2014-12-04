<?php
namespace Medical\Controller;
use Medical\Form\ReservationForm;
use Medical\Form\ShareForm;
use Medical\Model\User;
use Medical\Model\Reservation;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Math\Rand;
use Zend\Session\Config\SessionConfig;
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
	public function getFoodTable()
	{
		if(!$this->foodTable)
		{
			$sm = $this->getServiceLocator();
			$this->foodTable = $sm->get('Medical\Model\FoodTable');
		}
		return $this->foodTable;
	}
	public function getReservationTable()
	{
		if(!$this->reservationTable)
		{
			$sm = $this->getServiceLocator();
			$this->reservationTable = $sm->get('Medical\Model\ReservationTable');
		}
		return $this->reservationTable;
	}

	public function randomAction()
	{
		$models=$this->getFoodTable()->fetchAll();
		$integer=Rand::getInteger(0,count($models)-1);
		foreach($models as $food)
		{
			if($integer == 0)
			break;
			$integer--;
		}
		
		return new ViewModel(array(
			'food'=>$food,
		));
		
	}

	public function indexAction()
	{
		$shared = $this->getUserTable()->getUser($this->user->id)->shared;
		return new ViewModel(array(
			'reservations' => $this->getReservationTable()->getReservationsByCreator($this->user->id),
			'shared' => $this->getReservationTable()->getReservationsByShared($shared),
			'messages' => $this->flashMessenger()->getMessages(),
		));
   	}

	public function addAction()
	{
		$form = new ReservationForm();
		$form->get('submit')->setValue('Add');

		$request = $this->getRequest();
		if ($request->isPost()) {
			$reservation = new Reservation();
			$form->setInputFilter($reservation->getInputFilter());
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$data = $form->getData();
				$allowedTypes = $this->getUserTable()->getUser($this->user->id)->types;
				if($allowedTypes){
					$allowedTypes = explode(',',$allowedTypes);
					$randType=$allowedTypes[Rand::getInteger(0,count($allowedTypes)-1)];
					$models=$this->getFoodTable()->getFoodType($randType);
					$integer=Rand::getInteger(0,count($models)-1);
					foreach($models as $food)
					{
						if($integer == 0)
						break;
						$integer--;
					}
					$data['location'] = $food->id;
					$data['creator'] = $this->user->id;
					$reservation->exchangeArray($data);
					$id = $this->getReservationTable()->saveReservation($reservation);
					// Redirect to a view of this reservation
					return $this->redirect()->toRoute('medical', array('action' => 'view', 'id' => $id));
				}
				else
				{
					$this->flashMessenger()->addMessage('No Allowed preferences. Please edit your profile.');
					return $this->redirect()->toRoute('medical', array('action' => 'index',));
				}
			}
		}
		return array('form' => $form);
	}
  	public function editAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('medical', array(
				'action' => 'add'
			));
		}
		$reservation = $this->getReservationTable()->getReservation($id);

		$form  = new ReservationForm();
		$form->bind($reservation);
		$form->get('submit')->setAttribute('value', 'Edit');

		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setInputFilter($reservation->getInputFilter());
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$this->getReservationTable()->saveReservation($form->getData());

				// Redirect to list of reservations
				return $this->redirect()->toRoute('medical');
			}
		}

		return array(
			'id' => $id,
			'form' => $form,
		);
	}
	public function deleteAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('medical');
		}

		$request = $this->getRequest();
		if ($request->isPost()) {
			$del = $request->getPost('del', 'No');

			if ($del == 'Yes') {
				$id = (int) $request->getPost('id');
				$this->getReservationTable()->deleteReservation($id);
			}

			// Redirect to list
			return $this->redirect()->toRoute('medical');
		}

		return array(
			'id'	=> $id,
			'reservation' => $this->getReservationTable()->getReservation($id)
		);
	}

	public function viewAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('medical');
		}

		$reservation = $this->getReservationTable()->getReservation($id);
		$food=$this->getFoodTable()->getFood($reservation->location);
		return new ViewModel(array(
			'food'=>$food,
			'reservation' => $reservation
		));
	}

	public function logoutAction()
	{
		$session = new Container('user');
		$session->getManager()->getStorage()->clear('user');
			return $this->redirect()->toRoute('user', array('action' => 'index'));

	}

	public function shareAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('medical');
		}

		$request = $this->getRequest();
		if ($request->isPost()) {
			$id = (int) $request->getPost('id');
			$user = $request->getPost('username');
			$user = $this->getUserTable()->getUserByName($user);
			if($user)
			{
				$shared = array();
				if($user->shared && $user)
				{
					$shared  = explode(",",$user->shared);
				}
				$shared[] = $id;
				$shared = array_unique($shared);
				$shared = implode(",",$shared);
				$user->shared = $shared;
				$this->getUserTable()->saveUser($user);
				$this->flashMessenger()->clearMessages();
				$this->flashMessenger()->addMessage('Reservation Successfully Shared');
			}
			else
			{
				//flash messenger
					$this->flashMessenger()->addMessage('User Not found');
			}
		}

		return array(
			'id'	=> $id,
			'reservation' => $this->getReservationTable()->getReservation($id),
			'messages' => $this->flashMessenger()->getCurrentMessages(),
		);
	}

}
