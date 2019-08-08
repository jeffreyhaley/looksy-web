<?php

class MLocationBo
{    
    const CREATE = 0;
    const UPDATE = 1;

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
    public function createLocation($data)
    {
        
        $locationVo = $this->buildLocationVo($data);        
        $transmitterVo = $this->buildTransmitterVo($data);
        $imageVo = $this->buildImageVo($data, CImageBoProcess::TYPE_LOCATION);
        
        // Set the validation.
        $this->responseVo->setValidation($locationVo->getValidation());
        
        /**
         * Next check that the data is valid and try to insert new records.
         */
        if ($locationVo->isValid())
        {
            // Next write the data to the databse
            $daoResultLocation = MLocationDao::insertLocation($locationVo, $imageVo->getImageId());
            $daoResultTransmitter = MLocationDao::insertTransmitter($transmitterVo, $daoResultLocation->getData()[0]['LocationId']);
            
            if ($daoResultLocation->getStatus() === FDatabaseVoResult::SUCCESS && $daoResultTransmitter->getStatus() === FDatabaseVoResult::SUCCESS)
            {
                // Set the response message
                $this->responseVo->setData($daoResultLocation->getData());
                $this->responseVo->appendData($daoResultTransmitter->getData());
                $this->responseVo->setMessage('Location created.', FWebVoResponse::SUCCESS);
            }
            else
            {
                // Set the response message
                $this->responseVo->setMessage($daoResultLocation->getMessage(), FWebVoResponse::ERROR);
            }
        }
        else
        {
            $this->responseVo->setMessage('Location data failed validation.', FWebVoResponse::ERROR);
        }
        
        return $this->responseVo;
    }

    /**
     * Takes in login form data and attempts to create account
     *
     * Saves and accont and handles validation response.
     *
     * @param object $data            
     * @return Ambigous <\framework\model\web\Singleton, \framework\model\web\FwkWebVoContent>
     */
    public function updateLocation($data)
    {
        $imageVo = $this->buildImageVo($data, CImageBoProcess::TYPE_LOCATION);        
        $locationVo = $this->buildLocationVo($data, self::UPDATE);        
        $locationVo->setImageId($imageVo->getImageId());
        $transmitterVo = $this->buildTransmitterVo($data);
        
        // Set the validation.
        $this->responseVo->setValidation($locationVo->getValidation());
        
        /**
         * Next check that the data is valid and try to insert new records.
         */
        if ($locationVo->isValid())
        {
            // Next write the data to the databse
            $daoResultLocation = MLocationDao::updateLocation($locationVo);
            $daoResultTransmitter = MLocationDao::updateTransmitter($transmitterVo, $locationVo->getLocationId());
            
            if ($daoResultLocation->getStatus() === FDatabaseVoResult::SUCCESS && $daoResultTransmitter->getStatus() === FDatabaseVoResult::SUCCESS)
            {
                // Set the response message
                $this->responseVo->setData($daoResultLocation->getData());
                $this->responseVo->appendData($daoResultTransmitter->getData());
                $this->responseVo->setMessage('Location updated.', FWebVoResponse::SUCCESS);
            }
            else
            {
                // Set the response message
                $this->responseVo->setMessage($daoResult->getMessage(), FWebVoResponse::ERROR);
            }
        }
        else
        {
            $this->responseVo->setMessage('Location update failed validation.', FWebVoResponse::ERROR);
        }
        
        return $this->responseVo;
    }
    
    /**
     * Update the lat/lng for a location.
     * @param array $data
     * @return Ambigous <Singleton, FwkWebVoContent>
     */
    public function updateGeoLocation($data)
    {
        if (isset($data->LocationLat) && isset($data->LocationLng) && isset($data->LocationId)) {

            $daoResult = MLocationDao::updateGeoLocation(
                $data->LocationId, $data->LocationLat, $data->LocationLng);
            
            if ($daoResult->getStatus() === FDatabaseVoResult::SUCCESS) {
                $this->responseVo->setData($daoResult->getData());
                $this->responseVo->setMessage('Location updated.', FWebVoResponse::SUCCESS);
                
            } else {
                $this->responseVo->setMessage($daoResult->getMessage(), FWebVoResponse::ERROR);
            }
            
        } else {
            $this->responseVo->setMessage('Location update failed validation.', FWebVoResponse::ERROR);
        }
        
        return $this->responseVo;
    }

    /**
     * Gets all of the locations for the current user.
     *
     * @return FWebVoResponse
     */
    public function deleteLocation($data)
    {
        $requiredLocation = array(
            MCoreVoLocation::LOCATIONID
        );
        
        // Setup the Location object
        $locationVo = new MCoreVoLocation($data, $requiredLocation);
        
        $currentUserId = FSecurityBoSecurity::getUserId();
        $daoResult = MLocationDao::deleteLocation($currentUserId, $locationVo->getLocationId());
        
        if ($daoResult->getStatus() === FDatabaseVoResult::SUCCESS)
        {
            // Set the response message
            $this->responseVo->setData($daoResult->getData());
            $this->responseVo->setMessage('Location and all associated posts have been removed.', FWebVoResponse::SUCCESS);
        }
        else
        {
            // Set the response message
            $this->responseVo->setMessage($daoResult->getMessage(), FWebVoResponse::ERROR);
        }
        
        return $this->responseVo;
    }

    /**
     * Gets all of the locations for the current user.
     *
     * @return FWebVoResponse
     */
    public function readLocation()
    {
        $currentUserId = FSecurityBoSecurity::getUserId();
        $daoResult = MLocationDao::getLocations($currentUserId);
        
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
        
        return $this->responseVo;
    }

    /**
     * Gets the states
     *
     * @return FWebVoResponse
     */
    public function readStates()
    {
        $daoResult = MLocationDao::readStates();
        
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
        
        return $this->responseVo;
    }
    
    /**
     * 
     */
    private function buildLocationVo($data, $action=null)
    {
        // Set the fields that are required for login
        $requiredLocation = array(
            MCoreVoLocation::LOCATIONNAME,
            MCoreVoLocation::LOCATIONDESCRIPTION,
            MCoreVoLocation::LOCATIONPHONENUMBER,
            MCoreVoLocation::LOCATIONADDRESS,
            MCoreVoLocation::LOCATIONCITY,
            MCoreVoLocation::USSTATEID,
            MCoreVoLocation::LOCATIONZIP
        );
        
        // Setup the Location object
        $locationVo = new MCoreVoLocation($data, $requiredLocation);
        
        // Check the zipcode
        $validZip = MLocationDao::validZipcode($locationVo->getLocationZip());
        if (!$validZip)
        {
            $locationVo->setValidationById(MCoreVoLocation::LOCATIONZIP, 'Invalid zipcode.', FWebVoValidation::ERROR);
        }
        
        // Check for duplicate location names
        $validLocationName = MLocationDao::validLocationName($locationVo->getLocationName(), FSecurityBoSecurity::getUserId());

        if ($action === self::CREATE && !$validLocationName)
        {
            $locationVo->setValidationById(MCoreVoLocation::LOCATIONNAME, 'Location name does not exist.', FWebVoValidation::ERROR);
        }
        else if ($action === self::UPDATE && $validLocationName)
        {
            $locationVo->setValidationById(MCoreVoLocation::LOCATIONNAME, 'Location already exists.', FWebVoValidation::ERROR);
        }
        
        return $locationVo;
    }
    
    /**
     *
     */
    private function buildTransmitterVo($data)
    {
        // Set the fields that are required
        $requiredTransmitter = array(
            MCoreVoTransmitter::TRANSMITTERSSID,
            MCoreVoTransmitter::TRANSMITTERBSSID
        );
        
        // Setup the Transmitter object
        $transmitterVo = new MCoreVoTransmitter($data, $requiredTransmitter);
    
        return $transmitterVo;
    }
    
    /**
     *
     */
    private function buildImageVo($data, $entityType)
    {
        // Set the fields that are required
        $requiredImage = array();
        
        // Setup the Transmitter object
        $imageVo = new MCoreVoImage($data, $requiredImage);
        $imageVo->setImageEntityType($entityType);
                
        // If the src is set then update the image
        if($imageVo->getImageSrc())
        {        
            $imageVo = CImageBoProcess::saveImage($imageVo, $entityType);            
        }
     
        return $imageVo;
    }
}
