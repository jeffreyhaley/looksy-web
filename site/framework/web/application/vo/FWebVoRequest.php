<?php
/*
 * Web content object used to standardize the content being returned to
 * the client for processing.
 */
class FWebVoRequest
{	
    /**
     * @var string
     */
    public $module;
    /**
     * @var string
     */
    public $action;
    /**
     * @var array
     */
    public $data;
    /**
     * @var string
     */
    public $csrf;
    /**
     * @var string
     */
    public $userEmail;
    /**
     * @var string
     */
    public $userId;    
    /**
     * @var bool
     */
    public $authenticated;    
    
    /**
     * @var FWebVoRequest
     */
    private static $instance;

    /**
     * is not allowed to call from outside: private!
     *
     */
    private function __construct() {}
    
    private static function intializeSingleton($module, $action, $data, $csrf, $userEmail, $userId, $authenticated)
    {    	
    	if (!empty($module))
    	{
    		self::setModule($module);
    	}
    	if (!empty($action))
    	{
    		self::setAction($action);
    	}
    	if (!empty($data))
    	{		
    		self::setData($data);
    	}
    	if (!empty($csrf))
    	{
    		self::setCSRF($csrf);
    	}
    	if (!empty($userEmail))
    	{
    		self::setUserEmail($userEmail);
    	} 
    	if (!empty($userId))
    	{
    		self::setUserId($userId);
    	}    	
    	if (!empty($authenticated))
    	{
    		self::setAuthenticated($authenticated);
    	}   	
    }

    /**
     * prevent the instance from being cloned
     *
     * @return void
     */
    private function __clone()
    {}
    
    /**
     * gets the instance via lazy initialization (created on first usage)
     *
     * @return Singleton
     */
    final public static function Singleton($module=null, $action=null, $data=array(), $csrf=null, $userEmail=null, $userId=null, $authenticated=false)
    {
        if (null === self::$instance) {
            self::$instance = new self();
            self::intializeSingleton($module, $action, $data, $csrf, $userEmail, $userId, $authenticated);
        }

        return self::$instance;
    }    
    
	/**
	 * JSON encode the content object.
	 * 
	 * This requries the the object properies remain public.  This is why we have
	 * public properties in addition to getters and setters.
	 *
	 * @return json string
	 */
	public function encode()
    {
    	return json_encode($this);
    }
    
	/**
     * @return string module
     */
    public static function getModule()
    {
        return self::$instance->module;
    }

	/**
     * @return string $action
     */
    public static function getAction()
    {
        return self::$instance->action;
    }

	/**
     * @return array $data
     */
    public static function getData()
    {
        return self::$instance->data;
    }
    
	/**
     * @return string $csrf
     */
    public static function getCSRF()
    {
        return self::$instance->csrf;
    }

    /**
     * @return string $userName
     */
    public static function getUserEmail()
    {
    	return self::$instance->userEmail;
    }
    
    /**
     * @return string $userName
     */
    public static function getUserId()
    {
    	return self::$instance->userId;
    }
    
    
    /**
     * @return bool $authenticated
     */
    public static function getAuthenticated()
    {
    	return self::$instance->authenticated;
    }    

	/**
     * @param string $module
     */
    public static function setModule($module)
    {  	
    	if (is_string($module) || is_null($module))
    	{
        	self::$instance->module = $module;
    	}
    	else
    	{
    		throw new InvalidArgumentException('Invalid module.');
    	}
    }

	/**
     * @param string $action
     */
    public static function setAction($action)
    {
        if (is_string($action) || is_null($action))
    	{
        	self::$instance->action = $action;
    	}
    	else
    	{
    		throw new InvalidArgumentException('Invalid action.');
    	}
    }

	/**
     * @param array|object $data
     */
    public static function setData($data)
    {
        self::$instance->data = $data;
    }
    
	/**
     * @param string $csrf
     */
    public static function setCSRF($csrf)
    {
        if (is_string($csrf) || is_null($csrf))
    	{
        	self::$instance->csrf = $csrf;
    	}
    	else
    	{
    		throw new InvalidArgumentException('Invalid csrf.');
    	}
    }  
    
    /**
     * @param string $userName
     */
    public static function setUserEmail($userEmail)
    {
    	if (is_string($userEmail) || is_null($userEmail))
    	{
    		self::$instance->userEmail = $userEmail;
    	}
    	else
    	{
    		throw new InvalidArgumentException('Invalid email.');
    	}
    }
    
    /**
     * @param int $userId
     */
    public static function setUserId($userId)
    {
    	if (is_int($userId) || is_null($userId))
    	{
    		self::$instance->userId = $userId;
    	}
    	else
    	{
    		throw new InvalidArgumentException('Invalid userId.');
    	}
    }
    
    /**
     * @param bool $authenticated
     */
    public static function setAuthenticated($authenticated)
    {
    	if (is_bool($authenticated))
    	{
    		self::$instance->authenticated = $authenticated;
    	}
    	else
    	{
    		throw new InvalidArgumentException('Invalid authenticated.');
    	}
    }
   
}  
?>