<?php
/**
 * Table Tile
 **/
class MCoreVoTile extends FDatabaseVoTable
{
	/* var int */
    protected $tileId;
    
    /* var int */
    protected $locationId;
    
    /* var string */
    //protected $tileName;
    
    /* var string */
    protected $tileImagePath;
    
    /* var int */
    protected $tileCaption;
    
    // Constants used for Validation and reference.  They should match the columns represent the table.    
    const TILEID = 'TileId';
    const LOCATIONID = 'LocationId';
    //const TILENAME = 'TileName';
    const TILEIMAGEPATH = 'TileImagePath';
    const TILECAPTION = 'TileCaption';
    

    /**
     * 
     * @return int
     */
    public function getTileId()
    {
        return $this->tileId;
    }

    /**
     * Set the Tile Id
     * 
     * @param int $tileId
     */
    public function setTileId($tileId)
    {        
    	if (is_numeric($tileId) && !empty($tileId))
    	{
    		$this->setValidationById(self::TILEID, 'TileId is valid', FWebVoValidation::SUCCESS);
    		$this->tileId = $tileId;
    	}
    	else
    	{
    		$this->setValidationById(self::TILEID, 'TileId is not valid', FWebVoValidation::ERROR);
    		error_log('Invalid TileId.');
    	}   	      
    }

	/**
	 * 
	 * @return int
	 */
    public function getLocationId()
    {
        return $this->locationId;
    }

    /**
     * Set the Location Id
     */
    public function setLocationId($locationId)
    {
        if (is_numeric($locationId) && !empty($locationId))
        {
        	$this->setValidationById(self::LOCATIONID, 'LocationId is valid', FWebVoValidation::SUCCESS);
			$this->locationId = $locationId;
        }
        else
        {
        	$this->setValidationById(self::LOCATIONID, 'LocationId is not valid', FWebVoValidation::ERROR);
        	error_log('Invalid LocationId.');
        }
    }

    /**
     * 
     * @retrun string
     */
    public function getTileImagePath()
    {
        return $this->tileImagePath;
    }

    /**
     * Set the Time Image Path
     * @param unknown $tileImagePath
     */
    public function setTileImagePath($tileImagePath)
    {
    	if (is_string($tileImagePath) && !empty($tileImagePath))
    	{
    		$this->setValidationById(self::TILEIMAGEPATH, 'Tile image path is valid', FWebVoValidation::SUCCESS);
    		$this->tileImagePath = $tileImagePath;
    	}
    	else
    	{
    		$this->setValidationById(self::TILEIMAGEPATH, 'Tile image path is not valid', FWebVoValidation::ERROR);
    		error_log('Invalid Tile image path.');
    	}        
    }

    /**
     * 
     * @return string
     */
    public function getTileCaption()
    {
        return $this->tileCaption;
    }

    /**
     * 
     * @param string $tileCaption
     */
    public function setTileCaption($tileCaption)
    {
    	if (is_string($tileCaption) && !empty($tileCaption))
    	{
    		$this->setValidationById(self::TILECAPTION, 'Tile image caption is valid', FWebVoValidation::SUCCESS);
    		$this->tileCaption = $tileCaption;
    	}
    	else
    	{
    		$this->setValidationById(self::TILECAPTION, 'Tile image caption is not valid', FWebVoValidation::ERROR);
    		error_log('Invalid Tile image caption.');
    	}
    }
}