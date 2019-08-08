<?php
/**
 * Table Location
 **/
class MCoreVoLocation extends FDatabaseVoTable
{
	/* var int */
    protected $locationId;
    
    /* var int */
    protected $userId;
    
    /* var string */
    protected $locationName;
    
    /* var string */
    protected $locationDescription;
    
    /* var int */
    protected $locationImagePath;
    
    /* var int */
    protected $imageId;    
    
    /* var int */
    protected $locationPhoneNumber;
    
    /* var string */
    protected $locationAddress;
    
    /* var string */
    protected $locationCity;
    
    /* var string */
    protected $usStateId;
    
    /* var int */
    protected $locationZip;
    
    /* var string */
    protected $locationWebSite;
    
    /* var string */
    protected $locationTwitterUserName;
    
    /* var string */
    protected $locationFacebookUserName;
    
    /* var string */
    protected $locationInstagramUserName;
    
   
    // Constants used for Validation and reference.  They should match the columns represent the table.
    const LOCATIONID = 'LocationId';
    const USERID = 'UserId';
    const LOCATIONDESCRIPTION = 'LocationDescription';
    const LOCATIONNAME = 'LocationName';
    const LOCATIONPHONENUMBER = 'LocationPhoneNumber';
    const LOCATIONADDRESS = 'LocationAddress';
    const LOCATIONCITY = 'LocationCity';
    const USSTATEID = 'USStateId';
    const LOCATIONZIP = 'LocationZip';
    const IMAGEID = 'ImageId';
    const LOCATIONIMAGEPATH = 'LocationImagePath';
    const LOCATIONWEBSITE = 'LocationWebSite';
    const LOCATIONTWITTERUSERNAME = 'LocationTwitterUserName';
    const LOCATIONFACEBOOKUSERNAME = 'LocationFacebookUserName';
    const LOCATIONINSTAGRAMUSERNAME  = 'LocationInstagramUserName';
    
    /**
     * 
     * @return int
     */
    public function getLocationId()
    {
        return $this->locationId;
    }

    /**
     * 
     * @param $locationId
     */
    public function setLocationId($locationId)
    {
        $this->locationId = $locationId;
    }

    /**
     * 
     * @param $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * 
     * @return string
     */
    public function getLocationName()
    {
        return $this->locationName;
    }

    /**
     * 
     * @param $locationName
     */
    public function setLocationName($locationName)
    {
    	if (is_string($locationName) && !empty($locationName))
    	{
    		$this->setValidationById(self::LOCATIONNAME, 'Location name is valid', FWebVoValidation::SUCCESS);
    		$this->locationName = $locationName;
    	}
    	else
    	{
    		$this->setValidationById(self::LOCATIONNAME, 'Location name is not valid', FWebVoValidation::ERROR);
    		error_log('Invalid Location name.');
    	}
    }

    /**
     * 
     * @return string
     */
    public function getLocationDescription()
    {
        return $this->locationDescription;
    }

    /**
     * 
     * @param $locationDescription
     */
    public function setLocationDescription($locationDescription)
    {
    	if (is_string($locationDescription) && !empty($locationDescription))
    	{
    		$this->setValidationById(self::LOCATIONDESCRIPTION, 'Location description is valid', FWebVoValidation::SUCCESS);
    		$this->locationDescription = $locationDescription;
    	}
    	else
    	{
    		$this->setValidationById(self::LOCATIONDESCRIPTION, 'Location description is not valid', FWebVoValidation::ERROR);
    		error_log('Invalid Location description.');
    	}        
    }
    
    /**
     *
     * @return
     */
    public function getImageId()
    {
    	return $this->imageId;
    }
    
    /**
     *
     * @param $imageId
     */
    public function setImageId($imageId)
    {
    	if (!empty($imageId))
    	{
    		$this->setValidationById(self::IMAGEID, 'Location imageId is valid', FWebVoValidation::SUCCESS);
    		$this->imageId = $imageId;
    	}
    }    

    /**
     * 
     * @return 
     */
    public function getLocationImagePath()
    {
        return $this->locationImagePath;
    }

    /**
     * 
     * @param $locationImage
     */
    public function setLocationImagePath($locationImagePath)
    {
    	if (!empty($locationImagePath))
    	{
    		$this->setValidationById(self::LOCATIONIMAGEPATH, 'Location image is valid', FWebVoValidation::SUCCESS);
			$this->locationImage = $locationImagePath;
    	}
    	else
    	{
    		$this->setValidationById(self::LOCATIONIMAGEPATH, 'Location image is not valid', FWebVoValidation::ERROR);
    		error_log('Invalid Location image.');
    	}
    }

    /**
     * 
     * @return 
     */
    public function getLocationPhoneNumber()
    {
        return $this->locationPhoneNumber;
    }

    /**
     * 
     * @param $locationPhoneNumber
     */
    public function setLocationPhoneNumber($locationPhoneNumber)
    {
        if (is_string($locationPhoneNumber) && !empty($locationPhoneNumber))
        {
        	$this->setValidationById(self::LOCATIONPHONENUMBER, 'Location phone number is valid', FWebVoValidation::SUCCESS);
        	$this->locationPhoneNumber = $locationPhoneNumber;
        }
        else
        {
        	$this->setValidationById(self::LOCATIONPHONENUMBER, 'Location phone number is not valid', FWebVoValidation::ERROR);
        	error_log('Invalid Location phone number.');
        }
    }

    /**
     * 
     * @return 
     */
    public function getLocationAddress()
    {
        return $this->locationAddress;
    }

    /**
     * 
     * @param $locationAddress
     */
    public function setLocationAddress($locationAddress)
    {  
        if (is_string($locationAddress) && !empty($locationAddress))
        {
        	$this->setValidationById(self::LOCATIONADDRESS, 'Location address is valid', FWebVoValidation::SUCCESS);
        	$this->locationAddress = $locationAddress;
        }
        else
        {
        	$this->setValidationById(self::LOCATIONADDRESS, 'Location address is not valid', FWebVoValidation::ERROR);
        	error_log('Invalid Location address.');
        }
    }

    /**
     * 
     * @return 
     */
    public function getLocationCity()
    {
        return $this->locationCity;
    }

    /**
     * 
     * @param $locationCity
     */
    public function setLocationCity($locationCity)
    {        
        if (is_string($locationCity) && !empty($locationCity))
        {
        	$this->setValidationById(self::LOCATIONCITY, 'Location city is valid', FWebVoValidation::SUCCESS);
			$this->locationCity = $locationCity;
        }
        else
        {
        	$this->setValidationById(self::LOCATIONCITY, 'Location city is not valid', FWebVoValidation::ERROR);
        	error_log('Invalid Location city.');
        }        
    }

    /**
     * 
     * @return 
     */
    public function getUsStateId()
    {
        return $this->usStateId;
    }

    /**
     * 
     * @param $usStateId
     */
    public function setUsStateId($usStateId)
    {        
        if (is_string($usStateId) && !empty($usStateId))
        {
        	$this->setValidationById(self::USSTATEID, 'Location state is valid', FWebVoValidation::SUCCESS);
        	$this->usStateId = $usStateId;
        }
        else
        {
        	$this->setValidationById(self::USSTATEID, 'Location state is not valid', FWebVoValidation::ERROR);
        	error_log('Invalid Location state.');
        }
    }

    /**
     * 
     * @return 
     */
    public function getLocationZip()
    {
        return $this->locationZip;
    }

    /**
     * 
     * @param $locationZip
     */
    public function setLocationZip($locationZip)
    {
        if (is_string($locationZip) && !empty($locationZip))
        {
        	$this->setValidationById(self::LOCATIONZIP, 'Location zip is valid', FWebVoValidation::SUCCESS);
        	$this->locationZip = $locationZip;
        }
        else
        {
        	$this->setValidationById(self::LOCATIONZIP, 'Location zip is not valid', FWebVoValidation::ERROR);
        	error_log('Invalid Location zip.');
        }
    }
    
    /**
     *
     * @return string
     */
    public function getLocationWebSite()
    {
    	return $this->locationWebSite;
    }
    
    /**
     *
     * @param $locationWebSite
     */
    public function setLocationWebSite($locationWebSite)
    {
    	if (is_string($locationWebSite) && !empty($locationWebSite))
    	{
    		$this->setValidationById(self::LOCATIONWEBSITE, 'Location web site', FWebVoValidation::SUCCESS);
    		$this->locationWebSite = $locationWebSite;
    	}
//     	else
//     	{
//     		$this->setValidationById(self::LOCATIONWEBSITE, 'Location web site', FWebVoValidation::ERROR);
//     		error_log('Invalid Location web site.');
//     	}
    }    

    /**
     * 
     * @return string
     */
    public function getLocationTwitterUserName()
    {
        return $this->locationTwitterUserName;
    }

    /**
     * 
     * @param $locationTwitterUserName
     */
    public function setLocationTwitterUserName($locationTwitterUserName)
    {
        $this->locationTwitterUserName = $locationTwitterUserName;
    }

    /**
     * 
     * @return string
     */
    public function getLocationFacebookUserName()
    {
        return $this->locationFacebookUserName;
    }

    /**
     * 
     * @param $locationFacebookUserName
     */
    public function setLocationFacebookUserName($locationFacebookUserName)
    {
        $this->locationFacebookUserName = $locationFacebookUserName;
    }

    /**
     * 
     * @return string
     */
    public function getLocationInstagramUserName()
    {
        return $this->locationInstagramUserName;
    }

    /**
     * 
     * @param $locationInstagramUserName
     */
    public function setLocationInstagramUserName($locationInstagramUserName)
    {
        $this->locationInstagramUserName = $locationInstagramUserName;
    }
}