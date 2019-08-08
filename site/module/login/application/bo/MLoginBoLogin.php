<?php
class MLoginBoLogin 
{
	public function __construct()
	{
		$this->responseVo = FWebVoResponse::Singleton();
	}

	/**
	 * Takes in login form data and attempts to create account
	 *
	 * Saves and accont and handles validation response.
	 *
	 * @param object $data
	 * @return Ambigous <\framework\model\web\Singleton, \framework\model\web\FwkWebVoContent>
	 */
	public function login($data)
	{		
		// Set the fields that are required for login
		$required 	= array(MCoreVoUser::USEREMAIL, MCoreVoUser::USERPASSWORD);
		$loginVo 	= new MCoreVoUser($data);	
		
		$this->responseVo->setValidation($loginVo->getValidation());

		// Check if the supplied data meets the validation requirements
		if ($loginVo->isValid())
		{			
			$daoResult = MLoginDaoLogin::checkLogin($loginVo);

			if ($daoResult->getStatus() === FDatabaseVoResult::SUCCESS)
			{	
				$resultData 	= $daoResult->getData();
				$userEmail 		= $resultData[MCoreVoUser::USEREMAIL];
				$userId 		= intval($resultData[MCoreVoUser::USERID]);
				$authenticated 	= true;
			
				// Setting that the user is authenticated and logged in.
				FSecurityBoSecurity::setLogin($userId, $userEmail, $authenticated);
				
				// Set the response message					
				$this->responseVo->setData($daoResult->getData());
				$this->responseVo->setDataById('Authenticated', $authenticated);
				$this->responseVo->setMessage('Welcome.', FWebVoResponse::SUCCESS);
			}
			else {
				$request = FWebVoRequest::Singleton();
				
				$data = array();
				$data[MCoreVoUser::USEREMAIL] = false;
				$data['Authenticated'] = false;				
				
				$this->responseVo->setData($data);
				$this->responseVo->setMessage($daoResult->getMessage(), FWebVoResponse::ERROR);
				$this->responseVo->getValidationById(MCoreVoUser::USEREMAIL, 'Email or password is invalid.', FWebVoValidation::ERROR);
			}
		}
		else
		{
			//@todo: add logging
			error_log('NOT VALID');			
		}

		return $this->responseVo;
	}
}