<?php
class FSecurityBoSecurity
{

	const USERID 		= 'userid';
	const USEREMAIL 	= 'useremail';
	const AUTHENTICATED = 'authenticated';

	public function __construct() {}
	
	
	/**
	 * Set the user email, authentication status and user id
	 * 
	 * @param unknown $userId
	 * @param unknown $userEmail
	 * @param unknown $authenticated
	 */
	public static function setLogin($userId, $userEmail, $authenticated)
	{		
		// Set the session
		self::setUserId($userId);
		self::setUserEmail($userEmail);
		self::setAuthenticated($authenticated);
	}


	/**
	 * Get the sessions userid
	 * 
	 * @return int
	 */
	public static function getUserId()
	{
		if (isset($_SESSION[FSecurityBoSecurity::USERID]))
		{
			$userId = $_SESSION[FSecurityBoSecurity::USERID];
		}
		else
		{
			$userId = false;
		}
		
		return $userId;
	}
	

	/**
	 * Sets the User id
	 *
	 * @param int $userId
	 * @throws InvalidArgumentException
	 */
	public static function setUserId($userId)
	{
		if (is_int($userId))
		{
			$_SESSION[FSecurityBoSecurity::USERID] = $userId;
		}
		else
		{
			throw new InvalidArgumentException('Integer expected');
		}
	}
	
	/**
	 * Get the user email
	 * 
	 * @return bool
	 */
	public static function getUserEmail()
	{
		if (isset($_SESSION[FSecurityBoSecurity::USEREMAIL]))
		{
			$userName = $_SESSION[FSecurityBoSecurity::USEREMAIL];
		}
		else
		{
			$userName = false;
		}
	
		return $userName;
	}
	
	/**
	 * Set the session user email
	 * 
	 * @param string $userEmail
	 */
	public static function setUserEmail($userEmail)
	{
		if (is_string($userEmail))
		{
			$_SESSION[FSecurityBoSecurity::USEREMAIL] = $userEmail;
		}
		else
		{
			throw new InvalidArgumentException('String expected');
		}		
		
	}
	
	/**
	 * Get authentication status
	 * 
	 * @return bool
	 */
	public static function getAuthenticated()
	{
		if (isset($_SESSION[FSecurityBoSecurity::AUTHENTICATED]))
		{
			$authenticated = $_SESSION[FSecurityBoSecurity::AUTHENTICATED];
		}
		else
		{
			$authenticated = false;
		}
		return $authenticated;
	}
	
	/**
	 * Sets authenticated in the session 
	 * 
	 * @param bool $authenticated
	 * @throws InvalidArgumentException
	 */
	public static function setAuthenticated($authenticated)
	{
		if (is_bool($authenticated))
		{
			$_SESSION[FSecurityBoSecurity::AUTHENTICATED] = $authenticated;
		}
		else
		{
			throw new InvalidArgumentException('Boolean expected');
		}
	}

	/**
	 * Log the user out by destroying the session.
	 * 
	 * @retrun null
	 */
	public static function logout()
	{	
		FSessionBoSession::sessionDestroy();
	}
}
