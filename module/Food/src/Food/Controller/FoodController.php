<?php
namespace Food\Controller;
use Food\Form\ReservationForm;
use Food\Model\Reservation;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Math\Rand;

class FoodController extends AbstractActionController
{
	protected $foodTable;
	protected $reservationTable;
	
	public function getFoodTable()
	{
		if(!$this->foodTable)
		{
			$sm = $this->getServiceLocator();
			$this->foodTable = $sm->get('Food\Model\FoodTable');
		}
		return $this->foodTable;
	}
	public function getReservationTable()
	{
		if(!$this->reservationTable)
		{
			$sm = $this->getServiceLocator();
			$this->reservationTable = $sm->get('Food\Model\ReservationTable');
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
		return new ViewModel(array(
			'reservations' => $this->getReservationTable()->fetchAll(),
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
				$models=$this->getFoodTable()->fetchAll();
				$integer=Rand::getInteger(0,count($models)-1);
				foreach($models as $food)
				{
					if($integer == 0)
					break;
					$integer--;
				}
				$data['location'] = $food->id;
				$reservation->exchangeArray($data);
				$id = $this->getReservationTable()->saveReservation($reservation);

				// Redirect to a view of this reservation
				return $this->redirect()->toRoute('food', array('action' => 'view', 'id' => $id));
			}
		}
		return array('form' => $form);
	}
  	public function editAction()
	{
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('food', array(
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
				return $this->redirect()->toRoute('food');
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
			return $this->redirect()->toRoute('food');
		}

		$request = $this->getRequest();
		if ($request->isPost()) {
			$del = $request->getPost('del', 'No');

			if ($del == 'Yes') {
				$id = (int) $request->getPost('id');
				$this->getReservationTable()->deleteReservation($id);
			}

			// Redirect to list
			return $this->redirect()->toRoute('food');
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
			return $this->redirect()->toRoute('food');
		}

		$reservation = $this->getReservationTable()->getReservation($id);
		$food=$this->getFoodTable()->getFood($id);
		return new ViewModel(array(
			'food'=>$food,
			'reservation' => $reservation
		));
	}
}
