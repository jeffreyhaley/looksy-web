<?php
class FDatabaseVoResult
{	
	const NONE = -1;
	const ERROR = 0;
	const SUCCESS = 1;

	/**
	 * @var string
	 */
	public $message;
	/**
	 * @var int
	 */
	public $status;
	/**
	 * @var array
	 */
	public $data;
	
	public function __construct() {}
	
	/**
	 * Get the database status message.
	 * 
	  * This message is used to describe what happened when making a call to the database.
	 * For example if you tried to execute a delete and the delete failed.  The message would
	 * hold the failure message returned by the database.
	 * 
	 * @return string
	 */
	public function getMessage()
	{
		return $this->message;
	}
	
	/**
	 * Set the database status message.
	 * 
	 * This message is used to describe what happened when making a call to the database.
	 * For example if you tried to execute a delete and the delete failed.  The message would
	 * hold the failure message returned by the database.
	 * 
	 * @param string $message
	 * @throws InvalidArgumentException
	 */
	public function setMessage($message)
	{
		if (is_string($message))
		{
			$this->message = $message;
		}
		else {
			throw new InvalidArgumentException('Not a string.');
		}
	}	
	
	/**
	 * Get the status of the database result.
	 * 
	 * The status message indicates whether the query succeeded or failed.
	 * @return int
	 */
	public function getStatus()
	{
		return $this->status;
	}
	
	/**
	 * Get the status of the database result.
	 *
	 * The status message indicates whether the query succeeded or failed.
	 * @param int $status
	 */
	public function setStatus($status)
	{
		if ($status === self::NONE || $status === self::SUCCESS || $status === self::ERROR )
		{
			$this->status = $status;
		}
		else 
		{
			throw new InvalidArgumentException('Incorrect status specified');
		}
	}
	
	/**
	 * Get the database result set.
	 *
	 * The database result set may or maynot be set.  This is a helper for situation where we
	 * may need to look at messages or status associated with this data.
	 * 
	 * @return array
	 */
	public function getData()
	{
		return $this->data;
	}
	
	/**
	 * Get the status of the database result.
	 *
	 * The status message indicates whether the query succeeded or failed.
	 * @param array $data
	 */
	public function setData(array $data)
	{
		$this->data = $data;
	}
	
	/**
	 * Helper method for setting the message and status.
	 * 
	 * @param string $message
	 * @param int $status
	 */
	public function setResult($message, $status)
	{
		$this->setMessage($message);
		$this->setStatus($status);
	}
	/**
	 * JSON encode the content object
	 *
	 * @return json string
	 */
	public function Encode()
	{
		return json_encode($this);
	}	
}
?>