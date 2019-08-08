<?php
class MApiBoLocation
{

	public function __construct() {
		$this->responseVo = FWebVoResponse::Singleton();
	}

	public function getLocationsForBssids($data) {
		
		if ($this->validParams(array($data->DeviceUUID, $data->BssidList))) {			
			$this->handleResult(MApiDaoLocation::getLocationsForBssids(
					$data->DeviceUUID, json_decode($data->BssidList)));
			
		} else {
			$this->responseVo->setMessage(
					'Validation failed', FWebVoResponse::ERROR);
		}

		return $this->responseVo;
	}
	
	public function getLocationsCount($data) {
		
		if ($this->validParams(array($data->DeviceUUID, $data->BssidList))) {
			$this->handleResult(MApiDaoLocation::getLocationsCount(
					$data->DeviceUUID, json_decode($data->BssidList)));
			
		} else {
			$this->responseVo->setMessage(
					'Validation failed', FWebVoResponse::ERROR);
		}
	
		return $this->responseVo;
	}
	
	public function getFavoriteLocations($data) {
		
		if ($this->validParams(array($data->DeviceUUID)))  {
			$this->handleResult(MApiDaoLocation::getFavoriteLocations(
					$data->DeviceUUID));
			
		} else {
			$this->responseVo->setMessage('Validation failed', FWebVoResponse::ERROR);
		}
	
		return $this->responseVo;
	}
	
	public function isLocationFavorite($data) {
	
		if ($this->validParams(array($data->DeviceUUID, $data->LocationId))) {
			$this->handleResult(MApiDaoLocation::isLocationFavorite(
					$data->DeviceUUID, $data->LocationId));
				
		} else {
			$this->responseVo->setMessage('Validation failed', FWebVoResponse::ERROR);
		}
	
		return $this->responseVo;
	}
	
	public function updateLocationFavoriteStatus($data) {
	
		if ($this->validParams(array($data->DeviceUUID, 
				$data->LocationId, $data->Favorite))) {
			$this->handleResult(MApiDaoLocation::updateLocationFavoriteStatus(
					$data->DeviceUUID, $data->LocationId, $data->Favorite));
	
		} else {
			$this->responseVo->setMessage('Validation failed', FWebVoResponse::ERROR);
		}
	
		return $this->responseVo;
	}
	
	public function getLocationFavoriteCount($data) {
	
		if ($this->validParams(array($data->DeviceUUID, $data->LocationId))) { 
			$this->handleResult(MApiDaoLocation::getLocationFavoriteCount(
					$data->DeviceUUID, $data->LocationId));
	
		} else {
			$this->responseVo->setMessage('Validation failed', FWebVoResponse::ERROR);
		}
	
		return $this->responseVo;
	}
	
	/**
	 * Tiles
	 */
	
	public function getTilesForLocation($data) {
	
		if ($this->validParams(array($data->DeviceUUID, $data->LocationId))) {
			$this->handleResult(MApiDaoLocation::getTilesForLocation(
					$data->DeviceUUID, $data->LocationId));
	
		} else {
			$this->responseVo->setMessage('Validation failed', FWebVoResponse::ERROR);
		}
	
		return $this->responseVo;
	}
	
	public function getTileFavoriteCount($data) {
	
		if ($this->validParams(array($data->DeviceUUID, $data->TileId))) {
			$this->handleResult(MApiDaoLocation::getTileFavoriteCount(
					$data->DeviceUUID, $data->TileId));
	
		} else {
			$this->responseVo->setMessage('Validation failed', FWebVoResponse::ERROR);
		}
	
		return $this->responseVo;
	}
	
	public function updateTileFavoriteStatus($data) {
	
		if ($this->validParams(array($data->DeviceUUID, $data->TileId, $data->Favorite))) {
			$this->handleResult(MApiDaoLocation::updateTileFavoriteStatus(
					$data->DeviceUUID, $data->TileId, $data->Favorite));
	
		} else {
			$this->responseVo->setMessage('Validation failed', FWebVoResponse::ERROR);
		}
	
		return $this->responseVo;
	}
	
	public function isTileFavorite($data) {
	
		if ($this->validParams(array($data->DeviceUUID, $data->TileId))) {
			$this->handleResult(MApiDaoLocation::isTileFavorite(
					$data->DeviceUUID, $data->TileId));
	
		} else {
			$this->responseVo->setMessage('Validation failed', FWebVoResponse::ERROR);
		}
	
		return $this->responseVo;
	}
	
	public function registerUserTileVisit($data) {
	
		if ($this->validParams(array($data->DeviceUUID, $data->TileId))) {
			$this->handleResult(MApiDaoLocation::registerUserTileVisit(
					$data->DeviceUUID, $data->TileId));
	
		} else {
			$this->responseVo->setMessage('Validation failed', FWebVoResponse::ERROR);
		}
	
		return $this->responseVo;
	}
	
	public function registerUserLocationVisit($data) {
	
		if ($this->validParams(array($data->DeviceUUID, $data->LocationId))) {
			$this->handleResult(MApiDaoLocation::registerUserLocationVisit(
					$data->DeviceUUID, $data->LocationId));
	
		} else {
			$this->responseVo->setMessage('Validation failed', FWebVoResponse::ERROR);
		}
	
		return $this->responseVo;
	}
	
	public function registerUserLocationPassBy($data) {
	
		if ($this->validParams(array($data->DeviceUUID, $data->LocationId))) {
			$this->handleResult(MApiDaoLocation::registerUserLocationPassBy(
					$data->DeviceUUID, $data->LocationId));
	
		} else {
			$this->responseVo->setMessage('Validation failed', FWebVoResponse::ERROR);
		}
	
		return $this->responseVo;
	}
	
	public function getLocationHours($data) {
	
	    if ($this->validParams(array($data->DeviceUUID, $data->LocationId))) {
	        $this->handleResult(MApiDaoLocation::getLocationHours(
	            $data->DeviceUUID, $data->LocationId));
	
	    } else {
	        $this->responseVo->setMessage('Validation failed', FWebVoResponse::ERROR);
	    }
	
	    return $this->responseVo;
	}
	
	
	
	/**
	 * Validation routine
	 */
	private function validParams(array $params) {
		foreach ($params as $param) {
			if (!isset($param)) return false;
		}
		
		// TODO: validate deviceId exists in DB
		
		return true;
	}
	
	/**
	 * Utilities
	 */
	private function handleResult($daoResult) {
		if ($daoResult->getStatus() === FDatabaseVoResult::SUCCESS) {
			$this->responseVo->setData($daoResult->getData());
			$this->responseVo->setMessage(
					$daoResult->getMessage(), FWebVoResponse::SUCCESS);
		
		} else	{
			$this->responseVo->setMessage(
					'Failed to process the request. Oops.', FWebVoResponse::ERROR);
		}
	}
	
	

}