<?php
namespace Medical\Model;

use Zend\Db\TableGateway\TableGateway;

class UserTable
{
	protected $tableGateway;
	
	public function __construct(TableGateway $tableGateway)
	{
		$this->tableGateway = $tableGateway;
	}
	
	public function fetchAll()
	{
		$resultSet = $this->tableGateway->select();
		return $resultSet;
	}
	

	public function getTypes()
	{
		
	}
	public function validateUser($username, $password)
	{
		$rowset = $this->tableGateway->select(array('username' => $username, 'password' => $password));
		$row = $rowset->current();
		if(!$row)
			return false;
		else
			return $row;
	}
	public function getUserByName($username)
	{
		$rowset = $this->tableGateway->select(array('username' => $username));
		$row = $rowset->current();
		if(!$row)
			return false;
		else
			return $row;
	}
	public function getUser($id)
	{
		$id=(int)$id;
		$rowset = $this->tableGateway->select(array('id' => $id));
		$row = $rowset->current();
		if(!$row)
		{
			throw new \Exception("could not find row $id");
		}
		return $row;
	}

	public function saveUser(User $user)
	{
		$data = array(
			'username' => $user->username,
			'password' => $user->password,
            'email' => $user->email,
            'role' => $user->role
		);
		
		$id=(int)$user->id;
		if($id == 0)
		{
			$this->tableGateway->insert($data);
		}
		else
		{
			if($this->getUser($id))
			{
				$this->tableGateway->update($data, array('id' => $id));
			}
			else
			{
				throw new \Exception('form id does not exist');
			}
		}
		return $this->tableGateway->lastInsertValue;
	}
	public function deleteUser($id)
	{
		$this->tableGateway->delete(array('id' => $id));
	}
}
