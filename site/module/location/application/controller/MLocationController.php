<?php
class MLocationController
{	
	public function dispatch($action, $data)
	{		
		if (FSecurityBoSecurity::getAuthenticated())
		{
			
			switch ($action)
			{
				
				case 'createLocation':				
					$locationBo = new MLocationBo();
					return $locationBo->createLocation($data);
				case 'readLocation':
					$locationBo = new MLocationBo();
					return $locationBo->readLocation();
				case 'updateLocation':
					$locationBo = new MLocationBo();
					return $locationBo->updateLocation($data);
				case 'deleteLocation':
					$locationBo = new MLocationBo();
					return $locationBo->deleteLocation($data);
				case 'readState':
					$locationBo = new MLocationBo();
					return $locationBo->readStates();
				case 'updateGeoLocation':
				    $locationBo = new MLocationBo();
				    return $locationBo->updateGeoLocation($data);
				default:
					$responseVo = FWebVoResponse::Singleton();
					$responseVo->setMessage('Invalid action: ' . $action, FWebVoResponse::ERROR);
					return $responseVo;
			}
		}
		else
		{
			throw new Exception('Request is not authenticated.');
		}
	}
}
