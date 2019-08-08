<?php
/*
 * Web content object used to standardize the content being returned to
* the client for processing.
*/
class FWebVoResponse
{
	const NONE = '';
	const SUCCESS = 'success';
	const ERROR = 'error';
	const INFO = 'info';
	const STATUS = 'status';

	/**
	 * @var bool
	 */
	public $status;
	/**
	 * @var string
	 */
	public $message;
	/**
	 * @var array
	 */
	public $data;
	/**
	 * @var string
	 */
	public $validation;

	/**
	 * @var FwkWebVoContent
	 */
	private static $instance;

	/**
	 * is not allowed to call from outside: private!
	 *
	 */
	private function __construct()
	{
	}

	/**
	 * prevent the instance from being cloned
	 *
	 * @return void
	 */
	private function __clone()
	{
	}

	/**
	 * gets the instance via lazy initialization (created on first usage)
	 *
	 * @return Singleton
	 */
	final public static function Singleton()
	{
		if (null === self::$instance) {
			self::$instance = new self();
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
	 * @return the $status
	 */
	public function getStatus()
	{
		return self::$instance->status;
	}

	/**
	 * @return the $message
	 */
	public function getMessage()
	{
		return self::$instance->message;
	}

	/**
	 * @return the $data
	 */
	public function getData()
	{
		return self::$instance->data;
	}

	/**
	 * @return the $validation
	 */
	public function getValidation()
	{
		return self::$instance->validation;
	}

	/**
	 * @param bool $status
	 */
	public function setStatus($status)
	{
		if ($status === self::NONE || $status === self::SUCCESS || $status === self::ERROR || $status === self::INFO )
		{
			self::$instance->status = $status;
		}
		else
		{
			throw new InvalidArgumentException('Invalid status.');
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
			self::$instance->message .= ' ' .$message;
		}

		if (!empty($status))
		{
			self::$instance->setStatus($status);
		}
	}

	/**
	 * @param string $data
	 */
	public function setData(array $data)
	{
		self::$instance->data = $data;
	}
	
	/**
	 * @param string $data
	 */
	public function appendData(array $data)
	{
		self::$instance->data = array_merge(self::$instance->data, $data);
	}
	
	/**
	 * @param string $id
	 * @param string $value
	 */
	public function setDataById($id, $value)
	{
		self::$instance->data[$id] = $value;
	}
	
	/**
	 * @param string $id
	 * @return string $value
	 */
	public function getDataById($id)
	{
		return self::$instance->data[$id];
	}

	/**
	 * @param string $validation
	 */
	public function setValidation(array $validation)
	{
		self::$instance->validation = $validation;
	}
	
	/**
	 * 
	 * @param unknown $id
	 * @param FWebVoValidation $validation
	 */
	public function setValidationById($id, $message, $status)
	{
		self::$instance->validation[$id] = new FWebVoValidation($key, $message, $status);
	}
	
	/**
	 * 
	 * @param string $id
	 */
	public function getValidationById($id)
	{
		return self::$instance->validation[$id];
	}
}
?>