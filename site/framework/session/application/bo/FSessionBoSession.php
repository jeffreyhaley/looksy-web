<?php
/**
 * Session Handler and Session Wrapper
 */
class FSessionBoSession
{

	public function __construct()
	{
		parent::__construct();
	}

	public static function sessionStart()
	{
		//if (session_status() == PHP_SESSION_NONE)
		{
			session_start();
		}
	}

	public static function sessionDestroy()
	{
		session_destroy();
		session_unset();
		$_SESSION = null;
	}

}
