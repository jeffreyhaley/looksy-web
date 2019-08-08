<?php
class MCoreBoUser
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
	public function loadUser()
	{			
		$request = FWebVoRequest::Singleton();
		$data = array();
		
		$data['module'] = $request::getModule();
		$data['action'] = $request::getAction();
		$data['data'] = $request::getData();
		$data['csrf'] = $request::getCSRF();
		$data['useremail'] = $request::getUserEmail();
		$data['userid'] = $request::getUserId();
		$data['authenticated'] = $request::getAuthenticated();
		
		$this->responseVo->setData($data);
		$this->responseVo->setMessage('User Loaded.', FWebVoResponse::SUCCESS);

		return $this->responseVo;
	}
	
	/**
	 * Removes the session
	 * 
	 * @return FWebVoResponse
	 */
	public function logout()
	{
		FSessionBoSession::sessionDestroy();
	
		return $this->responseVo;
	}
}