<?php
class MSignupBoSignup
{

	public function __construct()
	{
		$this->responseVo = FWebVoResponse::Singleton();
	}

	/**
	 * Takes in signup form data and attempts to create account
	 *
	 * Saves and accont and handles validation response.
	 *
	 * @param object $data
	 * @return Ambigous <\framework\model\web\Singleton, \framework\model\web\FwkWebVoContent>
	 */
	public function saveData($data)
	{
		// Set the fields that are required for login
		$requiredSignup = array(
				MCoreVoUser::USEREMAIL,
				MCoreVoUser::USERPASSWORD);
		
		// Setup the Location object
		$signupVo	= new MCoreVoUser($data, $requiredSignup);
		$this->responseVo->setValidation($signupVo->getValidation());
		
		if ($signupVo->isValid())
		{			
			$daoResult = MSignupDao::saveData($signupVo);

			if ($daoResult->getStatus() === FDatabaseVoResult::SUCCESS)
			{
				$message = 'User created successfully.';
				$this->responseVo->setMessage($message, FWebVoResponse::SUCCESS);
			}
			else {
				$message = 'Error creating user. Please try again.';				
				$signupVo->setValidationById(MCoreVoUser::USEREMAIL, 'Email taken.', FWebVoValidation::ERROR);
				
				$this->responseVo->setMessage($daoResult->getMessage(), FWebVoResponse::ERROR);
				$this->responseVo->setValidation($signupVo->getValidation());
			}
		}
		
		return $this->responseVo;
	}
}