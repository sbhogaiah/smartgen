<?php

/**
* Error Messages
*/
class ErrorMessages
{
	
	public $password;
	public $username;
	public $role;

	function __construct()
	{
		$this->password = 'Password is not correct!';
		$this->username = 'Username does not exist!';
		$this->role = 'User does not belong to the selected role!';
	}
}