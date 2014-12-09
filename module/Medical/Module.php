<?php
namespace Medical;

use Medical\Model\Food;
use Medical\Model\Reservation;
use Medical\Model\User;
use Medical\Model\Type;
use Medical\Model\FoodTable;
use Medical\Model\ReservationTable;
use Medical\Model\UserTable;
use Medical\Model\TypeTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module
{
	public function getAutoloaderConfig()
	{
		return array(
			'Zend\Loader\ClassMapAutoloader'=>array(
				__DIR__ . '/autoload_classmap.php',
			),
			'Zend\Loader\StandardAutoLoader'=>array(
				'namespaces'=>array(
					__NAMESPACE__=>__DIR__ . '/src/' . __NAMESPACE__,
				),
			),
		);
	}
	
	public function getConfig()
	{
		return include __DIR__ . '/config/module.config.php';
	}
	
	public function getServiceConfig()
	{
		return array(
			'factories'=>array(
				'Medical\Model\FoodTable'=>function($sm)
				{
					$tableGateway=$sm->get('FoodTableGateway');
					$table=new FoodTable($tableGateway);
					return $table;
				},
				'FoodTableGateway'=>function($sm)
				{
					$dbAdapter=$sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new Food());
					return new tableGateway('food', $dbAdapter, null, $resultSetPrototype);
				},
				'Medical\Model\ReservationTable'=>function($sm)
				{
					$tableGateway=$sm->get('ReservationTableGateway');
					$table=new ReservationTable($tableGateway);
					return $table;
				},
				'ReservationTableGateway'=>function($sm)
				{
					$dbAdapter=$sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new Reservation());
					return new tableGateway('reservation', $dbAdapter, null, $resultSetPrototype);
				},
				'Medical\Model\UserTable'=>function($sm)
				{
					$tableGateway=$sm->get('UserTableGateway');
					$table=new UserTable($tableGateway);
					return $table;
				},
				'UserTableGateway'=>function($sm)
				{
					$dbAdapter=$sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new User());
					return new tableGateway('user', $dbAdapter, null, $resultSetPrototype);
				},
				'Medical\Model\RoomTable'=>function($sm)
				{
					$tableGateway=$sm->get('RoomTableGateway');
					$table=new RoomTable($tableGateway);
					return $table;
				},
				'RoomTableGateway'=>function($sm)
				{
					$dbAdapter=$sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new Room());
					return new tableGateway('room', $dbAdapter, null, $resultSetPrototype);
				},
				'Medical\Model\MachineTable'=>function($sm)
				{
					$tableGateway=$sm->get('MachineTableGateway');
					$table=new MachineTable($tableGateway);
					return $table;
				},
				'MachineTableGateway'=>function($sm)
				{
					$dbAdapter=$sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new Machine());
					return new tableGateway('machine', $dbAdapter, null, $resultSetPrototype);
				},
				'Medical\Model\VideoTable'=>function($sm)
				{
					$tableGateway=$sm->get('VideoTableGateway');
					$table=new VideoTable($tableGateway);
					return $table;
				},
				'VideoTableGateway'=>function($sm)
				{
					$dbAdapter=$sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new Video());
					return new tableGateway('video', $dbAdapter, null, $resultSetPrototype);
				},
				'Medical\Model\SoftwareTable'=>function($sm)
				{
					$tableGateway=$sm->get('SoftwareTableGateway');
					$table=new SoftwareTable($tableGateway);
					return $table;
				},
				'SoftwareTableGateway'=>function($sm)
				{
					$dbAdapter=$sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new Software());
					return new tableGateway('software', $dbAdapter, null, $resultSetPrototype);
				},
			),
		);
	}
}
