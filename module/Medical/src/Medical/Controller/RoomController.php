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


class RoomController extends AbstractActionController
{
    protected $roomTable;
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
    public function getRoomTable()
    {
        if(!$this->roomTable)
        {
            $sm = $this->getServiceLocator();
            $this->roomTable = $sm->get('Medical\Model\RoomTable');
        }
        return $this->roomTable;
    }

    public function indexAction()
    {
        return array('messages' => $this->flashMessenger()->getCurrentMessages());
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
                $room = $this->getRoomTable()->getRoomByNum($data['roomnum']);
                if(!$room)
                {
                    $room->exchangeArray($data);
                    $id = $this->getRoomTable()->saveRoom($room);
                    $this->flashMessenger()->addMessage('Room ' . $room->roomnum . ' created.');
                }
                else
                {
                    $this->flashMessenger()->addMessage('Room ' . $room->roomnum . ' already exists.');
                }
            }
        }
        return array(	'form' => $form,
            'messages' => $this->flashMessenger()->getCurrentMessages());
    }
//
//    public function selectEditAction()
//    {
//        $id = $this->user->id;
//        if (!$id) {
//            return $this->redirect()->toRoute('user', array(
//                'action' => 'add'
//            ));
//        }
//        $users = $this->getUserTable()->fetchAll();
//        $options = array();
//        foreach($users as $user)
//        {
//            $options[$user->id]=$user->username;
//        }
//        $form  = new Form();
//        $form->add(array(
//            'name'=>'submit',
//            'type'=>'submit',
//            'attributes'=>array(
//                'value'=>'Submit',
//                'id'=>'submitbutton',
//            ),
//        ));
//        $form->add(array(
//            'name'=> 'user',
//            'type' => 'Select',
//            'options'=>array(
//                'label'=> 'Role: ',
//                'options'=>$options
//            ),
//        ));
//        $request = $this->getRequest();
//        if ($request->isPost()) {
//            $form->setData($request->getPost());
//
//            if ($form->isValid()) {
//                $data = $form->getData();
//                return $this->redirect()->toRoute('user', array('action' => 'edit', 'id' => $data['user']));
//            }
//        }
//
//        return array(
//            'id' => $id,
//            'form' => $form,
//            'messages' => $this->flashMessenger()->getCurrentMessages());
//    }
//
//    public function queryUsersAction()
//    {
//        $form  = new UserForm();
//        $form->remove('password');
//        $form->remove('email');
//        $form->remove('id');
//        $form->remove('username');
//        $request = $this->getRequest();
//        if ($request->isPost()) {
//            $form->setData($request->getPost());
//
//            if ($form->isValid()) {
//                $data = $form->getData();
//                $users = $this->getUserTable()->getUsersByRole($data['role']);
//            }
//        }
//
//        return array(
//            'users' => $users,
//            'form' => $form,
//            'messages' => $this->flashMessenger()->getCurrentMessages());
//    }
//    public function editAction()
//    {
//        //$id = $this->user->id;
//        $id = (int) $this->params()->fromRoute('id', 0);
//        if (!$id) {
//            return $this->redirect()->toRoute('user', array(
//                'action' => 'add'
//            ));
//        }
//        $user = $this->getUserTable()->getUser($id);
//        $form  = new UserForm();
//        $form->bind($user);
//        $form->get('submit')->setAttribute('value', 'Edit');
//        $request = $this->getRequest();
//        if ($request->isPost()) {
//            $form->setInputFilter($user->getInputFilter());
//            $form->setData($request->getPost());
//
//            if ($form->isValid()) {
//                $data = $form->getData();
//                $userExists = $this->getUserTable()->getUserByName($data->username);
//                if(!$userExists || $user->username == $data->username)
//                {
//                    $this->getUserTable()->saveUser($data);
//
//                    // Redirect to list of users
//                    return $this->redirect()->toRoute('medical');
//                }
//                else
//                {
//                    $this->flashMessenger()->addMessage('Username already exists');
//                }
//            }
//        }
//
//        return array(
//            'id' => $id,
//            'form' => $form,
//            'messages' => $this->flashMessenger()->getCurrentMessages());
//    }
//
//    public function deleteAction()
//    {
//        $id = $this->user->id;
//        if (!$id) {
//            return $this->redirect()->toRoute('user', array(
//                'action' => 'add'
//            ));
//        }
//        $users = $this->getUserTable()->fetchAll();
//        $options = array();
//        foreach($users as $user)
//        {
//            $options[$user->id]=$user->username;
//        }
//        $form  = new Form();
//        $form->add(array(
//            'name'=>'submit',
//            'type'=>'submit',
//            'attributes'=>array(
//                'value'=>'Submit',
//                'id'=>'submitbutton',
//            ),
//        ));
//        $form->add(array(
//            'name'=> 'user',
//            'type' => 'Select',
//            'options'=>array(
//                'label'=> 'Role: ',
//                'options'=>$options
//            ),
//        ));
//        $request = $this->getRequest();
//        if ($request->isPost()) {
//            $form->setData($request->getPost());
//
//            if ($form->isValid()) {
//                $data = $form->getData();
//                if($id != $data['user'])
//                {
//                    $user = $this->getUserTable()->deleteUser($data['user']);
//                    $this->flashMessenger()->addMessage('User Deleted');
//                    return $this->redirect()->toRoute('user', array('action' => 'delete'));
//                }
//                else
//                {
//                    $this->flashMessenger()->addMessage('You Cannot Delete Yourself');
//                }
//
//            }
//        }
//
//        return array(
//            'id' => $id,
//            'form' => $form,
//            'messages' => $this->flashMessenger()->getCurrentMessages());
//    }
//    public function viewAction()
//    {
//    }
}