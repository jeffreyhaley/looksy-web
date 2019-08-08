<?php
class FDatabaseVoTable
{	
	private $validation = array();
	private $data;

	/**
	 *
	 * @param unknown $data
	 */
	final public function __construct($data=array(), array $required=array())
	{
		$this->setRequired($required);
		$this->setData($data);
	}
	
	public function setData($data)
	{
		if (is_object($data) || is_array($data))
		{
			foreach($data as $id => $value)
			{
				$setter = 'set' . $id;
				
				if(method_exists($this, $setter))
				{
					$this->$setter($value);
				}
				
			}
		}
	}
	
	public function setRequired(array $required)
	{
		foreach ($required as $key)
		{
			$this->setValidationById($key, "$key is required.", FWebVoValidation::ERROR);
		}
	}
	
	public function setRequiredById($key)
	{
		$this->setValidationById($key, "$key is required.", FWebVoValidation::ERROR);
	}
	
	
	public function setValidation()
	{
		$this->$validation = validation;
	}
	
	public function setValidationById($key, $message='', $status=FWebVoValidation::ERROR)
	{
		if (empty($message))
		{
			$message = "$key is invalid";
		}
		
		$this->validation[$key] = new FWebVoValidation($key, $message, $status);
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
?>