<?php
class MLoginVoLogin extends FDatabaseVoTable
{
	const EMAIL = 'email';
	const PASSWORD = 'password';
	const FIRSTNAME = 'firstname';
	
	private $email;
	private $password;
	
	/**
	 * 
	 * @param unknown $data
	 */
	public function __construct($data, $required)
	{
		$this->setRequired($required);

		if (is_object($data))
		{
			foreach($data as $id => $value)
			{
				switch ($id)
				{
					case self::EMAIL:
						$this->setEmail($value);
						break;
					case self::PASSWORD:
						$this->setPassword($value);
						break;						
				}
			}
		}
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
			$v = new FWebVoValidation(self::EMAIL, 'Email is valid', FWebVoValidation::SUCCESS);
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
			$v = new FWebVoValidation(self::PASSWORD, 'Password is valid', FWebVoValidation::SUCCESS);
			$this->password = $password;
		}
		else
		{
			$v = new FWebVoValidation(self::PASSWORD, 'Not valid password', FWebVoValidation::ERROR);
			error_log('Invalid password.');
		}
	
		$this->validation[self::PASSWORD] = $v;
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
			$v = new FWebVoValidation(self::FIRSTNAME, 'Firstname is valid', FWebVoValidation::SUCCESS);
			$this->firstName = $firstName;
		}
		else
		{
			$v = new FWebVoValidation(self::FIRSTNAME, 'Not valid firstname', FWebVoValidation::ERROR);
			error_log('Invalid firstname.');
		}
	
		$this->validation[self::FIRSTNAME] = $v;
	}
}