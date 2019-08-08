<?php
/**
 * Table User
 **/
class MCoreVoUser extends FDatabaseVoTable
{
    /* var integer */
    protected $userId;
    
    /* var string */
    protected $userFirstName;
    
    /* var string */
    protected $userLastName;
    
    /* var string */
    protected $userEmail;
    
    /* var string */
    protected $userPassword;
    
    // Constants used for Validation and reference.  They should match the columns represent the table.
    const USERID = 'UserId';
    const USEREMAIL = 'UserEmail';
    const USERFIRSTNAME = 'UserFirstName';
    const USERLASTNAME = 'UserLastName';
    const USERPASSWORD = 'UserPassword';
    

    /**
     * Get the UserId
     * 
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }
    
    /**
     *
     * @return string
     */
    public function getUserFirstName()
    {
    	return $this->userFirstName;
    }
    
    /**
     * 
     * @param $userFirstName
     */
    public function setUserFirstName($userFirstName)
    {
    	if (is_string($userFirstName) && !empty($userFirstName))
    	{    		
    		$this->setValidationById(self::USERFIRSTNAME, 'First name is valid', FWebVoValidation::SUCCESS);
    		$this->userFirstName = $userFirstName;
    	}
    	else
    	{
    		$this->setValidationById(self::USERFIRSTNAME, 'First name is not valid', FWebVoValidation::ERROR);
    		error_log('First name is not valid');
    	}
    }    
    
    /**
     *
     * @return string
     */    
    public function getUserLastName()
    {
    	return $this->userLastName;
    }
    
    /**
     *
     * @param $userLastName
     */    
    public function setUserLastName($userLastName)
    {
    	if (is_string($userLastName) && !empty($userLastName))
		{
			$this->setValidationById(self::LASTNAME, 'Last name is valid', FWebVoValidation::SUCCESS);
			$this->userLastName = $userLastName;
		}
		else
		{
			$this->setValidationById(self::USERLASTNAME, 'Last name is not valid', FWebVoValidation::ERROR);
			error_log('Invalid lastname.');
		}
    }    

    /**
     *
     * @return string
     */
    public function getUserEmail()
    {
        return $this->userEmail;
    }

    /**
     * Set the Email.
     * 
     * Email is used for the username and email address.
     * 
     * @param string $email
     */
    public function setUserEmail($userEmail)
    {
    	if (filter_var($userEmail, FILTER_VALIDATE_EMAIL))
		{
			$this->setValidationById(self::USEREMAIL, 'Email is valid', FWebVoValidation::SUCCESS);
			$this->userEmail = $userEmail;
		}
		else
		{
			$this->setValidationById(self::USEREMAIL, 'Email not valid', FWebVoValidation::ERROR);
			error_log('Email not valid');
		}
    }
    
    /**
     *
     * @return string
     */    
    public function getUserPassword()
    {
    	return $this->userPassword;
    }
    
    /**
     *
     * @param $userPassword
     */    
    public function setUserPassword($userPassword)
    {
    	if (is_string($userPassword) && !empty($userPassword))
		{
			$this->setValidationById(self::USERPASSWORD, 'Password is valid', FWebVoValidation::SUCCESS);
			$this->userPassword = $userPassword;
		}
		else
		{
			$this->setValidationById(self::USERPASSWORD, 'Password is not valid', FWebVoValidation::ERROR);
			error_log('Password is not valid');
		}
    }    
}