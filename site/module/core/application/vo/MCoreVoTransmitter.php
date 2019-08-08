<?php
/**
 * Table Transmitter
 **/
class MCoreVoTransmitter extends FDatabaseVoTable
{
   /* var int */
   protected $transmitterId;
   
   /* var string */
   protected $transmitterName;
   
   /* var string */   
   protected $transmitterSSID;
   
   /* var string */
   protected $transmitterBSSID;
   
   /* var string */
   protected $locationId;
    
   
    // Constants used for Validation and reference.  They should match the columns represent the table.    
   	const TRANSMITTERID = 'TransmitterId';
    const TRANSMITTERNAME = 'TransmitterName';
    const TRANSMITTERSSID = 'TransmitterSSID';
    const TRANSMITTERBSSID = 'TransmitterBSSID';
    const LOCATIONID = 'LocationId';
    
    /**
     *
     * @return
     */
    public function getTransmitterId()
    {
    	return $this->transmitterId;
    }
    
    /**
     *
     * @param $transmitterId
     */
    public function setTransmitterId($transmitterId)
    {
    	$this->transmitterId = $transmitterId;
    }

    /**
     * 
     * @return 
     */
    public function getTransmitterName()
    {
        return $this->transmitterName;
    }

    /**
     * 
     * @param $transmitterName
     */
    public function setTransmitterName($transmitterName)
    {
    	if (is_string($transmitterName) && !empty($transmitterName))
    	{
    		$this->setValidationById(self::TRANSMITTERNAME, 'Transmitter name is valid', FWebVoValidation::SUCCESS);
    		$this->transmitterName = $transmitterName;
    	}
    	else
    	{
    		$this->setValidationById(self::TRANSMITTERNAME, 'Transmitter name is not valid', FWebVoValidation::ERROR);
    		error_log('Invalid Transmitter name.');
    	}
    }

    /**
     * 
     * @return 
     */
    public function getTransmitterSsid()
    {
        return $this->transmitterSsid;
    }
    
    /**
     *
     * @param $transmitterSsid
     */
    public function setTransmitterSsid($transmitterSsid)
    {
    	if (is_string($transmitterSsid) && !empty($transmitterSsid))
    	{
    		$this->setValidationById(self::TRANSMITTERSSID, 'Transmitter SSID is valid', FWebVoValidation::SUCCESS);
    		$this->transmitterSsid = $transmitterSsid;
    	}
    	else
    	{
    		$this->setValidationById(self::TRANSMITTERSSID, 'Transmitter SSID is not valid', FWebVoValidation::ERROR);
    		error_log('Invalid Transmitter SSID.');
    	}
    }
    
    /**
     *
     * @return
     */
    public function getTransmitterBssid()
    {
    	return $this->transmitterBssid;
    }    

    /**
     * 
     * @param $transmitterBssid
     */
    public function setTransmitterBssid($transmitterBssid)
    {
        
        if (is_string($transmitterBssid) && !empty($transmitterBssid))
        {
            // Lowercase string
            $transmitterBssid = strtolower($transmitterBssid);
            
            // Add colon every two characters.
            if (!strpos($transmitterBssid, ':'))
            {
                $chunks = str_split($transmitterBssid, 2);
                $transmitterBssid = implode(':', $chunks);
            }
            
        	$this->setValidationById(self::TRANSMITTERBSSID, 'Transmitter BSSID is valid', FWebVoValidation::SUCCESS);
        	$this->transmitterBssid = $transmitterBssid;
        }
        else
        {
        	$this->setValidationById(self::TRANSMITTERBSSID, 'Transmitter BSSID is not valid', FWebVoValidation::ERROR);
        	error_log('Invalid Transmitter BSSID.');
        }
    }
    
    /**
     *
     * @return
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

  
}