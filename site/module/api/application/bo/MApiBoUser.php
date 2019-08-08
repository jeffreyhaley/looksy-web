<?php
class MApiBoUser
{
	public function __construct() {
		$this->responseVo = FWebVoResponse::Singleton();
	}

	public function registerDevice($data)
	{		
		if ($this->validRegisterDevice($data)) {			
			$daoResult = MApiDaoUser::registerDevice($data->DeviceUUID);
			
			// set response
			if ($daoResult->getStatus() === FDatabaseVoResult::SUCCESS) {
				$this->responseVo->setMessage(
						$daoResult->getMessage(), FWebVoResponse::SUCCESS);
				
			} else {
				$this->responseVo->setMessage(
						$daoResult->getMessage(), FWebVoResponse::ERROR);
			}			
		}

		return $this->responseVo;
	}
	
	private function validRegisterDevice($data)	{

		if (empty($data->DeviceUUID)) {
			return false;
		}
		return true;
	}
	

}