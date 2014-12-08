<?php
namespace Medical\Controller;
use Medical\Form\PasswordRecoveryForm;
use Medical\Form\UserForm;
use Medical\Model\PasswordRecovery;
use Medical\Model\User;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Config\SessionConfig;
use Zend\Session\Container;
use Zend\Session\SessionManager;


class UserController extends AbstractActionController
{
	protected $userTable;
	protected $typeTable;
	protected $user;
	public function onDispatch( \Zend\Mvc\MvcEvent $e )
	{
		$session = new Container('user');
		if ($session && $session->uid) 
		{
			$this->user = $session->uid;
		}
		return parent::onDispatch( $e );
	}

	public function initSession($config)
	{
	    $sessionConfig = new SessionConfig();
	    $sessionConfig->setOptions($config);
	    $sessionManager = new SessionManager($sessionConfig);
	    $sessionManager->start();
	    Container::setDefaultManager($sessionManager);
	}	
	public function getUserTable()
	{
		if(!$this->userTable)
		{
			$sm = $this->getServiceLocator();
			$this->userTable = $sm->get('Medical\Model\UserTable');
		}
		return $this->userTable;
	}

	public function indexAction()
	{
		$form = new UserForm();
		$form->get('submit')->setValue('Login');
		$form->remove('email');
		$form->remove('role');
		$request = $this->getRequest();
		if ($request->isPost()) {
			$user = new User();
			$form->setInputFilter($user->getInputFilter());
			$form->setData($request->getPost());
			if ($form->isValid()) {
				$data = $form->getData();
				$userExists=$this->getUserTable()->validateUser($data['username'],$data['password']);
				// Redirect to a view of this reservation
				if($userExists){
					$this->initSession(array(
					    'remember_me_seconds' => 600,
					    'use_cookies' => true,
					    'cookie_httponly' => true,
					));
					$sessionUser = new Container('user');
					$sessionUser->uid = $userExists;
					return $this->redirect()->toRoute('medical', array('action' => 'index'));
				}
				else
					$this->flashMessenger()->addMessage('User or Password Not found');
			}
		}

		return array(	'form' => $form,
				'messages' => $this->flashMessenger()->getCurrentMessages());
   	}

	public function addAction()
	{
		$form = new UserForm();
		$form->get('submit')->setValue('Create Account');

		$request = $this->getRequest();
		if ($request->isPost()) {
			$user = new User();
			$form->setInputFilter($user->getInputFilter());
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$data = $form->getData();
				$id = $this->getUserTable()->getUserByName($data['username']);
				if(!$id)
				{
					$user->exchangeArray($data);
					$id = $this->getUserTable()->saveUser($user);
					$userExists=$this->getUserTable()->validateUser($data['username'],$data['password']);
					// Redirect to a view of this reservation
					if($userExists){
						$this->initSession(array(
						    'remember_me_seconds' => 600,
						    'use_cookies' => true,
						    'cookie_httponly' => true,
						));
						$sessionUser = new Container('user');
						$sessionUser->uid = $userExists;
					}
					// Redirect to a view of this reservation
					return $this->redirect()->toRoute('medical', array('action' => 'index'));
				}
				else
				{
					$this->flashMessenger()->addMessage('Username already exists');
				}
			}
		}
		return array(	'form' => $form,
				'messages' => $this->flashMessenger()->getCurrentMessages());
	}
  	public function editAction()
	{

		$id = $this->user->id;
		if (!$id) {
			return $this->redirect()->toRoute('user', array(
				'action' => 'add'
			));
		}
		$user = $this->getUserTable()->getUser($id);
		$form  = new UserForm();
		$form->bind($user);
		$form->get('submit')->setAttribute('value', 'Edit');
		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setInputFilter($user->getInputFilter());
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$data = $form->getData();
				$userExists = $this->getUserTable()->getUserByName($data->username);
				if(!$userExists || $user->username == $data->username)
				{
					$this->getUserTable()->saveUser($data);
	
					// Redirect to list of users
					return $this->redirect()->toRoute('medical');
				}
				else
				{
					$this->flashMessenger()->addMessage('Username already exists');
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
	}
	public function viewAction()
	{
	}

    public function recoverAction() {
        $form = new PasswordRecoveryForm();

        $request = $this->getRequest();
        if ($request->isPost()) {
            $passwordRecovery = new PasswordRecovery();
            $form->setInputFilter($passwordRecovery->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $data = $form->getData();
                $id = $this->getUserTable()->getUserByEmail($data['email']);
                if (!$id) {
                    $this->flashMessenger()->addMessage('No account with that email exists.');
                } else {
                    $this->emailPasswordToUser($id);
                    $this->flashMessenger()->addMessage('Your password has been emailed to you.');
                }
            }
        }
        return array(	'form' => $form,
            'messages' => $this->flashMessenger()->getCurrentMessages());
    }

    public function emailPasswordTouser($id) {
        $user = $this->getUserTable()->getUser($id);

        $to      = $user->email;
        $subject = 'Medical Video System Password Recovery';
        $message = 'Username: ' . $user->username . '\n';
        $message .= 'Password: ' . $user->password . '\n';
        $headers = '';
//        $headers .= 'From: webmaster@example.com' . "\r\n";
//        $headers .= 'Reply-To: webmaster@example.com' . "\r\n";

        mail($to, $subject, $message, $headers);
    }
}
