<?php
class MSignupVoSignup
{
	const USERNAME = 'username';
	const EMAIL = 'email';
	const FIRSTNAME = 'firstname';
	const LASTNAME = 'lastname';
	const PASSWORD = 'password';
	
	private $userName;
	private $email;
	private $password;
	private $firstName;
	private $lastName;
	private $validation = array();
	
	/**
	 * 
	 * @param unknown $data
	 */
	public function __construct($data)
	{
		$this->validation[self::EMAIL] = new FWebVoValidation(self::EMAIL, 'Email is required.', FWebVoValidation::ERROR);
		$this->validation[self::FIRSTNAME] = new FWebVoValidation(self::FIRSTNAME, 'First name is required.', FWebVoValidation::ERROR);
		$this->validation[self::LASTNAME] = new FWebVoValidation(self::LASTNAME, 'Last name is required.', FWebVoValidation::ERROR);
		$this->validation[self::PASSWORD] = new FWebVoValidation(self::PASSWORD, 'Email is required.', FWebVoValidation::ERROR);

		if (is_object($data))
		{
			foreach($data as $id => $value)
			{
				switch ($id)
				{
					case self::EMAIL:
						$this->setUserName($value);
						$this->setEmail($value);
						break;
					case self::FIRSTNAME:
						$this->setFirstName($value);
						break;
					case self::LASTNAME:
						$this->setLastName($value);
						break;
					case self::PASSWORD:
						$this->setPassword($value);
						break;						
				}
			}
		}
	}
	
	/**
	 * Retrieves the username
	 *
	 * @return string
	 */
	public function getUserName()
	{
		return $this->userName;
	}
	
	/**
	 * Sets the userName
	 * 
	 * Check the the supplied userName is valid and set the validation array.  User name
	 * will be the same as the email address.
	 * 
	 * @param string $userName
	 * @return bool
	 */
	public function setUserName($userName)
	{		
		if (filter_var($userName, FILTER_VALIDATE_EMAIL))
		{
			$v = new FWebVoValidation(self::USERNAME, 'Username is valid', FWebVoValidation::SUCCESS);
			$this->userName = $userName;	
		}
		else 
		{
			$v = new FWebVoValidation(self::USERNAME, 'Not valid username', FWebVoValidation::ERROR);
			error_log('Invalid userName.');
		}		
		
		$this->validation[self::USERNAME] = $v;
	}
	
	/**
	 * Retrieves the email
	 * 
	 * @return string
	 */
	public function getEmail()
	{
		return $this->email;
	}
	
	
	/**
	 * Sets the email
	 * 
	 * Check the the supplied email is valid and set the validation array.
	 * 
	 * @param string $email
	 * @return bool
	 */
	public function setEmail($email)
	{
		if (filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			$v = new FWebVoValidation(self::EMAIL, 'Username is valid', FWebVoValidation::SUCCESS);
			$this->email = $email;
		}
		else
		{
			$v = new FWebVoValidation(self::EMAIL, 'Not valid email', FWebVoValidation::ERROR);
			error_log('Invalid email.');
		}
	
		$this->validation[self::EMAIL] = $v;
	}
	
	/**
	 * Retrieves the firstname
	 *
	 * @return string
	 */
	public function getFirstName()
	{
		return $this->firstName;
	}
	
	
	/**
	 * Sets the firstname
	 *
	 * Check the the supplied firstname is valid and set the validation array.
	 *
	 * @param string $firstName
	 * @return bool
	 */
	public function setFirstName($firstName)
	{
		if (is_string($firstName) && !empty($firstName))
		{
			$v = new FWebVoValidation(self::FIRSTNAME, 'Username is valid', FWebVoValidation::SUCCESS);
			$this->firstName = $firstName;
		}
		else
		{
			$v = new FWebVoValidation(self::FIRSTNAME, 'Not valid firstname', FWebVoValidation::ERROR);
			error_log('Invalid firstname.');
		}
	
		$this->validation[self::FIRSTNAME] = $v;
	}	
	
	/**
	 * Retrieves the lastName
	 *
	 * @return string
	 */
	public function getLastName()
	{
		return $this->lastName;
	}
	
	
	/**
	 * Sets the lastName
	 *
	 * Check the the supplied lastName is valid and set the validation array.
	 *
	 * @param string $lastName
	 * @return bool
	 */
	public function setLastName($lastName)
	{
		if (is_string($lastName) && !empty($lastName))
		{
			$v = new FWebVoValidation(self::LASTNAME, 'Username is valid', FWebVoValidation::SUCCESS);
			$this->lastName = $lastName;
		}
		else
		{
			$v = new FWebVoValidation(self::LASTNAME, 'Not valid lastname', FWebVoValidation::ERROR);
			error_log('Invalid lastname.');
		}
	
		$this->validation[self::LASTNAME] = $v;
	}
	
	/**
	 * Retrieves the password
	 *
	 * @return string
	 */
	public function getPassword()
	{
		return $this->password;
	}
	
	
	/**
	 * Sets the password
	 *
	 * Check the the supplied password is valid and set the validation array.
	 *
	 * @param string $password
	 * @return bool
	 */
	public function setPassword($password)
	{
		if (is_string($password) && !empty($password))
		{
			$v = new FWebVoValidation(self::PASSWORD, 'Username is valid', FWebVoValidation::SUCCESS);
			$this->password = $password;
		}
		else
		{
			$v = new FWebVoValidation(self::PASSWORD, 'Not valid password', FWebVoValidation::ERROR);
			error_log('Invalid password.');
		}
	
		$this->validation[self::PASSWORD] = $v;
	}
	
	
	
	
	public function getValidation()
	{
		return $this->validation;
	}
	
	public function isValid()
	{
		foreach ($this->validation as $id => $value)
		{
			if ($value->getStatus() === FWebVoValidation::ERROR)
			{
				return false;
			}				
		}
		
		return true;
	}

}