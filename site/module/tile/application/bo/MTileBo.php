<?php
class MTileBo
{
	public function __construct()
	{
		$this->responseVo = FWebVoResponse::Singleton();
	}

	/**
	 * Creates a new tile for the associated location.
	 * 
	 * @param array $data
	 * @return FWebVoResponse
	 */
	public function createTile($data)
	{
		// Setup the Tile object
		$tileVo  = new MCoreVoTile($data);
		$imageVo = $this->buildImageVo($data, CImageBoProcess::TYPE_TILE);	
	 
		// Set the validation of the location object
		$this->responseVo->setValidation($tileVo->getValidation());
		
		if ($tileVo->isValid())
		{		    
			// write to db
			$daoResult = MTileDao::createTile($tileVo, $imageVo->getImageId());
			 
			if ($daoResult->getStatus() === FDatabaseVoResult::SUCCESS)
			{
				// Set the response message
				$this->responseVo->setData($daoResult->getData());
				$this->responseVo->setMessage('Posted!', FWebVoResponse::SUCCESS);
			}
			else
			{
				// Set the response message
				$this->responseVo->setMessage('Failed to retreive tiles.', FWebVoResponse::ERROR);
			}
		}
		else
		{
			$this->responseVo->setMessage('Tile data failed validation.', FWebVoResponse::ERROR);
		}
		 
		return $this->responseVo;
	}
	
	/**
	 * Updates a new tile for the associated location.
	 *
	 * @param array $data
	 * @return FWebVoResponse
	 */
	public function updateTile($data)
	{
	    // Setup the Tile object
	    $tileVo  = new MCoreVoTile($data);
	    $imageVo = $this->buildImageVo($data, CImageBoProcess::TYPE_TILE);
	
	    // Set the validation of the location object
	    $this->responseVo->setValidation($tileVo->getValidation());
	
	    if ($tileVo->isValid())
	    {
	        // write to db
	        $daoResult = MTileDao::updateTile($tileVo, $imageVo->getImageId());
	
	        if ($daoResult->getStatus() === FDatabaseVoResult::SUCCESS)
	        {
	            // Set the response message
	            $this->responseVo->setData($daoResult->getData());
	            $this->responseVo->setMessage('Updated', FWebVoResponse::SUCCESS);
	        }
	        else
	        {
	            // Set the response message
	            $this->responseVo->setMessage('Failed to retreive tiles.', FWebVoResponse::ERROR);
	        }
	    }
	    else
	    {
	        $this->responseVo->setMessage('Tile data failed validation.', FWebVoResponse::ERROR);
	    }
	    	
	    return $this->responseVo;
	}	
	
	/**
	 * Gets all of the tiles for the specified location
	 *
	 * @return FWebVoResponse
	 */
	public function readTiles($data)
	{		
		// Get the current user
		$currentUserId = FSecurityBoSecurity::getUserId();
		
		// Check that the current user has access to the specified location
		$required   	= array(MCoreVoTile::LOCATIONID);
		$tileVo			= new MCoreVoTile($data, $required);
		$validLocation 	= MLocationDao::validLocation($currentUserId, $tileVo->getLocationId());

		if ($tileVo->isValid() && $validLocation)
		{
			// Get the tiles for the specified location.
			$daoResult = MTileDao::readTiles($tileVo->getLocationId());
			
			if ($daoResult->getStatus() === FDatabaseVoResult::SUCCESS)
			{				
				// Set the response message
				$this->responseVo->setData($daoResult->getData());
				$this->responseVo->setMessage($daoResult->getMessage(), FWebVoResponse::SUCCESS);
			}
			else
			{
				// Set the response message
				$this->responseVo->setMessage($daoResult->getMessage(), FWebVoResponse::ERROR);
			}
		}
		else 
		{
			$this->responseVo->setMessage('Tile data failed validation.', FWebVoResponse::ERROR);
		}
			
		return $this->responseVo;
	}	
	
	/**
	 * Gets the tile for the specified TileId
	 *
	 * @return FWebVoResponse
	 */
	public function readTile($data)
	{	    
	    // Check that the current user has access to the specified location
	    $tileVo	= $this->buildTileVo($data);	    	
    
	    if ($tileVo->isValid())
	    {
	        // Get the tiles for the specified location.
	        $daoResult = MTileDao::readTile($tileVo->getTileId());
	        	
	        if ($daoResult->getStatus() === FDatabaseVoResult::SUCCESS)
	        {
	            // Set the response message
	            $this->responseVo->setData($daoResult->getData());
	            $this->responseVo->setMessage($daoResult->getMessage(), FWebVoResponse::SUCCESS);
	        }
	        else
	        {
	            // Set the response message
	            $this->responseVo->setMessage($daoResult->getMessage(), FWebVoResponse::ERROR);
	        }
	    }
	    else
	    {
	        $this->responseVo->setMessage('Tile data failed validation.', FWebVoResponse::ERROR);
	    }
	    	
	    return $this->responseVo;
	}
	
	/**
	 * Delete the supplied tile.
	 *
	 * @return FWebVoResponse
	 */
	public function deleteTile($data)
	{	    
	    $tileVo = $this->buildTileVo($data);
	
	    if ($tileVo->isValid())
	    {
	       $daoResult = MTileDao::deleteTile($tileVo->getTileId());
	    }
	
	    if ($daoResult->getStatus() === FDatabaseVoResult::SUCCESS)
	    {
	        // Set the response message
	        $this->responseVo->setData($daoResult->getData());
	        $this->responseVo->setMessage('Post has been removed.', FWebVoResponse::SUCCESS);
	    }
	    else
	    {
	        // Set the response message
	        $this->responseVo->setMessage($daoResult->getMessage(), FWebVoResponse::ERROR);
	    }
	
	    return $this->responseVo;
	}	
	
	/**
	 * Build a TileVo
	 * 
	 * @param array $data
	 * @return MCoreVoTile
	 */
	private function buildTileVo($data)
	{
	    // Set the fields that are required
	    $requiredTile = array(
	        MCoreVoTile::TILEID
	    );
	    
	    // Setup the Tile object
	    $tileVo = new MCoreVoTile($data, $requiredTile);
	
	    return $tileVo;
	}	
	
	/**
	 * Build a ImageVo
	 * 
	 * @param array $data
	 * @return MCoreVoImage
	 */
	private function buildImageVo($data, $entityType)
	{
	    // Set the fields that are required
	    $requiredImage = array();
	
	    // Setup the Transmitter object
	    $imageVo = new MCoreVoImage($data, $requiredImage);
	    $imageVo->setImageEntityType($entityType);
	    $imageVo->setImageRotationDeg($data->rotateImage);
	
	    // If the src is set then update the image
	    if($imageVo->getImageSrc())
	    {
	        $imageVo = CImageBoProcess::saveImage($imageVo, $entityType);
	    }
	     
	    return $imageVo;
	}
}