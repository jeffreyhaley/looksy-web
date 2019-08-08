<?php
/*
 * Web content object used to standardize the content being returned to
 * the client for processing.
 */
class FWebVoValidation
{
	const NONE = -1;
	const ERROR = 0;
	const SUCCESS = 1;	
	const INFO = 2;
	const STATUS = 3;


	/**
	 * @var string
	 */
	public $id;
    /**
     * @var bool
     */
    public $status;
    /**
     * @var string
     */
    public $message;
    

    /**
     * 
     *
     */
     public function __construct($id, $message, $status) {
     	if (!is_null($id))
     	{
     		$this->setId($id);
     	}
     	if (!is_null($status))
     	{
     		$this->setStatus($status);
     	}
     	if (!is_null($message))
     	{
     		$this->setMessage($message);
     	}
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
     * @return the $status
     */
    public function getStatus()
    {
        return $this->status;
    }

	/**
     * @return the $message
     */
    public function getMessage()
    {
        return $this->message;
    }

	/**
     * @return the $data
     */
    public function getId()
    {
        return $this->id;
    }
      

    public function setId($id)
    {
    	if (is_string($id))
    	{
    		$this->id = $id;
    	}
    	else
    	{
    		throw new \InvalidArgumentException('Invalid id.');
    	}
    }
        
    
    /**
     * @param bool $status
     */
    public function setStatus($status)
    {
    	if ($status === self::NONE || $status === self::SUCCESS || $status === self::ERROR || $status === self::INFO || $status === self::STATUS )
    	{
        	$this->status = $status;
    	}
    	else
    	{
    		throw new \InvalidArgumentException('Invalid status.');
    	}
    }

	/**
     * @param string $message
     * @param string $status
     */
    public function setMessage($message, $status=null)
    {
    	if (is_string($message))
    	{
        	$this->message = $message;
    	}
    	else
    	{
    		throw new \InvalidArgumentException('Invalid message.');
    	}
    }
}  
?>