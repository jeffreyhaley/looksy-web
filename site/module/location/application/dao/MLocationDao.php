<?php
class MLocationDao
{
	/**
	 * For the supplied login data check if the username and pass are valid
	 *
	 * @param array $data
	 * @return FDatabaseVoResult
	 */
	public static function insertLocation(MCoreVoLocation $data, $imageId)
	{
		// Get the database connection
		$pdo = FDatabaseBoSingleton::Instance();

		// Initialize the Database Result object
		$daoResult = new FDatabaseVoResult();

		// Build the query string.  Using Heredoc because it's easier to read.
		$sql = <<<SQL
INSERT INTO core.Location
(
	UserId,
	LocationName,
	LocationDescription,
	ImageId,
	LocationPhoneNumber,
	LocationAddress,
	LocationCity,
	USStateId,
	LocationZip,
	LocationWebSite,
	LocationTwitterUserName,
	LocationFacebookUserName,
	LocationInstagramUserName
)
VALUES
(
	:userId,
	:locationName,
	:locationDescription,
	:imageId,
	:locationPhoneNumber,
	:locationAddress,
	:locationCity,
	:usStateId,
	:locationZip,
	:locationWebSite,
	:locationTwitterUserName,
	:locationFacebookUserName,
	:locationInstagramUserName
);
SQL;

		$stmt = $pdo->prepare($sql);
		$status = $stmt->execute(array(
				':userId' => FWebVoRequest::getUserId(),
				':locationName' => $data->getLocationName(),
				':locationDescription' => $data->getLocationDescription(),
				':imageId' => $imageId,
				':locationPhoneNumber' => $data->getLocationPhoneNumber(),
				':locationAddress' => $data->getLocationAddress(),
				':locationCity' => $data->getLocationCity(),
				':usStateId' => $data->getUsStateId(),				
				':locationZip' =>  $data->getLocationZip(),
				':locationWebSite' => $data->getLocationWebSite(),
				':locationTwitterUserName' => $data->getLocationTwitterUserName(),
				':locationFacebookUserName' => $data->getLocationFacebookUserName(),
				':locationInstagramUserName' => $data->getLocationInstagramUserName()
		));
		


		// Check the status and return the results.
		if ($status)
		{
			$stmt = $pdo->prepare('SELECT LAST_INSERT_ID() as LocationId;');
			$status = $stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);		
			
			$daoResult->setResult(count($result) . ' Records retrieved.', FDatabaseVoResult::SUCCESS);
			$daoResult->setData($result);
		}
		else
		{
			error_log( print_r($stmt->errorInfo(),1));
			$daoResult->setResult('Query failed.', FDatabaseVoResult::ERROR);
		}

		return $daoResult;
	}
	
	/**
	 * For the supplied transmitter data insert a new record related to the location
	 *
	 * @param array $data
	 * @param int $data
	 * @return FDatabaseVoResult
	 */
	public static function insertTransmitter(MCoreVoTransmitter $data, $locationId)
	{	
		// Get the database connection
		$pdo = FDatabaseBoSingleton::Instance();
	
		// Initialize the Database Result object
		$daoResult = new FDatabaseVoResult();
	
		// Build the query string.  Using Heredoc because it's easier to read.
		$sql = <<<SQL
INSERT INTO core.Transmitter
(
	TransmitterName,
	TransmitterSSID,
	TransmitterBSSID,
	LocationId				
)
VALUES
(
	:transmitterName,
	:transmitterBssid,
	:transmitterSsid,
	:locationId
);
SQL;
	
		$stmt = $pdo->prepare($sql);
		$status = $stmt->execute(array(
				':transmitterName' => $data->getTransmitterName(),
				':transmitterBssid' => $data->getTransmitterBssid(),
				':transmitterSsid' => $data->getTransmitterBssid(),
				':locationId' => $locationId
		));
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
		// Check the status and return the results.
		if ($status)
		{
			$daoResult->setResult(count($result) . ' Records retrieved.', FDatabaseVoResult::SUCCESS);
			$daoResult->setData($result);
		}
		else
		{
			error_log( print_r($stmt->errorInfo(),1));
			$daoResult->setResult('Query failed.', FDatabaseVoResult::ERROR);
		}
	
		return $daoResult;
	}
	
	/**
	 * For the supplied login data check if the username and pass are valid
	 *
	 * @param array $data
	 * @return FDatabaseVoResult
	 */
	public static function updateLocation(MCoreVoLocation $data)
	{	
		// Get the database connection
		$pdo = FDatabaseBoSingleton::Instance();
	
		// Initialize the Database Result object
		$daoResult = new FDatabaseVoResult();
		
		if (!$data->getImageId())
		{		    
		    $imageId = self::readImageId($data->getLocationName(), FWebVoRequest::getUserId());
		}
		else
		{		    		    
		    $imageId = $data->getImageId();
		}
	
		// Build the query string.  Using Heredoc because it's easier to read.
		$sql = <<<SQL
UPDATE core.Location
SET
    ImageId = :imageId,
	LocationDescription = :locationDescription,
	LocationPhoneNumber = :locationPhoneNumber,
	LocationAddress = :locationAddress,
	LocationCity = :locationCity,
	USStateId = :usStateId,
	LocationZip = :locationZip,
	LocationWebSite = :locationWebSite,
	LocationTwitterUserName = :locationTwitterUserName,
	LocationFacebookUserName = :locationFacebookUserName,
	LocationInstagramUserName = :locationInstagramUserName
WHERE
	LocationName = :locationName
	AND UserId = :userId
SQL;
	
		$stmt = $pdo->prepare($sql);
		$status = $stmt->execute(array(
		        ':imageId' => $imageId,
				':userId' => FWebVoRequest::getUserId(),
				':locationName' => $data->getLocationName(),
				':locationDescription' => $data->getLocationDescription(),
				':locationPhoneNumber' => $data->getLocationPhoneNumber(),
				':locationAddress' => $data->getLocationAddress(),
				':locationCity' => $data->getLocationCity(),
				':usStateId' => $data->getUsStateId(),
				':locationZip' =>  $data->getLocationZip(),
				':locationWebSite' => $data->getLocationWebSite(),
				':locationTwitterUserName' => $data->getLocationTwitterUserName(),
				':locationFacebookUserName' => $data->getLocationFacebookUserName(),
				':locationInstagramUserName' => $data->getLocationInstagramUserName()
		));
		
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
		// Check the status and return the results.
		if ($status)
		{
			$daoResult->setResult(count($result) . ' Records retrieved.', FDatabaseVoResult::SUCCESS);
			$daoResult->setData($result);
		}
		else
		{
			error_log( print_r($stmt->errorInfo(),1));
			$daoResult->setResult('Query failed.', FDatabaseVoResult::ERROR);
		}
	
		return $daoResult;
	}
	
	/**
	 * Update geo coordinates for a location
	 * @param int $locationId LocationId
	 * @param double $lat location latitude
	 * @param double $lng location longitude
	 * @return FDatabaseVoResult
	 */
	public static function updateGeoLocation($locationId, $lat, $lng)
	{
	    $pdo = FDatabaseBoSingleton::Instance();
	    $daoResult = new FDatabaseVoResult();
	    $sql = "UPDATE Location 
	            SET LocationLat = $lat, LocationLng = $lng 
	            WHERE LocationId = $locationId";
	    $stmt = $pdo->prepare($sql);
	    $status = $stmt->execute();
	    
	    if ($status) {
	        $daoResult->setResult('Records updated', FDatabaseVoResult::SUCCESS);
	        $daoResult->setData(array());
	    
	    } else {
	        $daoResult->setResult('updateGeoLocation query failed', FDatabaseVoResult::ERROR);
	    }
	    
	    return $daoResult;
	}
	
	/**
	 * For the supplied login data check if the username and pass are valid
	 *
	 * @param array $data
	 * @return FDatabaseVoResult
	 */
	public static function updateTransmitter(MCoreVoTransmitter $data, $locationId)
	{
		// Get the database connection
		$pdo = FDatabaseBoSingleton::Instance();
	
		// Initialize the Database Result object
		$daoResult = new FDatabaseVoResult();
	
		// Build the query string.  Using Heredoc because it's easier to read.
		$sql = <<<SQL
UPDATE core.Transmitter
SET
	TransmitterName = :transmitterName,
	TransmitterSSID = :transmitterSsid,
	TransmitterBSSID = :transmitterBssid
WHERE
	LocationId = :locationId
SQL;
	
		$stmt = $pdo->prepare($sql);
		$status = $stmt->execute(array(
				':transmitterName' => $data->getTransmitterName(),
				':transmitterBssid' => $data->getTransmitterBssid(),
				':transmitterSsid' => $data->getTransmitterSsid(),
				':locationId' => $locationId
		));
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
		// Check the status and return the results.
		if ($status)
		{
			$daoResult->setResult(count($result) . ' Records retrieved.', FDatabaseVoResult::SUCCESS);
			$daoResult->setData($result);
		}
		else
		{
			error_log( print_r($stmt->errorInfo(), 1));
			$daoResult->setResult('Query failed.', FDatabaseVoResult::ERROR);
		}
	
		return $daoResult;
	}	


	/**
	 * Check if there's a duplicate location name.
	 *
	 * @param string $locationName
	 * @param int $userId
	 * @return boolean
	 */
	public static function validLocationName($locationName, $userId)
	{
		$return = false;

		// Get the database connection
		$pdo = FDatabaseBoSingleton::Instance();

		// Initialize the Database Result object
		$daoResult = new FDatabaseVoResult();

		// Build the query string.  Using Heredoc because it's easier to read.
		$sql = <<<SQL
SELECT
    Count(*) as Count
FROM
    Location
WHERE
    LocationName = :loationName 
    AND LocationDeleted = 0 
    AND UserId = :userId
	
SQL;

		$stmt = $pdo->prepare($sql);
		$status = $stmt->execute(array(
				':loationName' => $locationName,
				':userId' => $userId
		));
		$result = $stmt->fetch(PDO::FETCH_ASSOC);

		// Check the status and return the results.
		if ($status)
		{
			$return = ($result['Count'] == 0);

		}
		else
		{
			error_log( print_r($stmt->errorInfo(),1));
		}

		return $return;
	}
	
	/**
	 * Check if there's a duplicate location name.
	 *
	 * @param string $locationName
	 * @param int $userId
	 * @return boolean
	 */
	public static function validLocation($userId, $locationId)
	{
		$return = false;
	
		// Get the database connection
		$pdo = FDatabaseBoSingleton::Instance();
	
		// Initialize the Database Result object
		$daoResult = new FDatabaseVoResult();
	
		// Build the query string.  Using Heredoc because it's easier to read.
		$sql = <<<SQL
SELECT
    Count(*) as Count
FROM
    Location
WHERE
    LocationId = :locationId
    AND LocationDeleted = 0 
    AND UserId = :userId
SQL;
	
		$stmt = $pdo->prepare($sql);
		$status = $stmt->execute(array(
				':locationId' => $locationId,
				':userId' => $userId
		));
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
	
		// Check the status and return the results.
		if ($status)
		{
			$return = ($result['Count'] == 1);
	
		}
		else
		{
			error_log( print_r($stmt->errorInfo(),1));
		}
	
		return $return;
	}

	/**
	 * Get the ZipcodeId for the supplied Zipcode.
	 *
	 * @param string $zipcode
	 * @return boolean
	 */
	public static function validZipcode($zipcode)
	{
		$return = false;

		// Get the database connection
		$pdo = FDatabaseBoSingleton::Instance();

		// Initialize the Database Result object
		$daoResult = new FDatabaseVoResult();

		// Build the query string.  Using Heredoc because it's easier to read.
		$sql = <<<SQL
SELECT
    Count(*) as Count
FROM
    Zipcode
WHERE
    Zipcode = :zipcode
SQL;

		$stmt = $pdo->prepare($sql);
		$status = $stmt->execute(array(
				':zipcode' => $zipcode
		));
		$result = $stmt->fetch(PDO::FETCH_ASSOC);

		// Check the status and return the results.
		if ($status)
		{
			$return = $result['Count'] > 0 ? true : false;
		}
		else
		{
			error_log( print_r($stmt->errorInfo(),1));
		}

		return $return;
	}
	
	/**
	 * Get the ImageId for the supplied Location and user.
	 *
	 * @param string $locationName
	 * @param int $userId
	 * @return int|boolean
	 */
	public static function readImageId($locationName, $userId)
	{
	    $return = false;
	
	    // Get the database connection
	    $pdo = FDatabaseBoSingleton::Instance();
	
	    // Initialize the Database Result object
	    $daoResult = new FDatabaseVoResult();
	
	    // Build the query string.  Using Heredoc because it's easier to read.
	    $sql = <<<SQL
SELECT
    ImageId
FROM
    Location
WHERE
   	LocationName = :locationName
	AND UserId = :userId
SQL;
	
	    $stmt = $pdo->prepare($sql);
	    $status = $stmt->execute(array(
	        ':locationName' => $locationName,
	        ':userId' => $userId
	    ));
	    $result = $stmt->fetch(PDO::FETCH_ASSOC);
	
	    // Check the status and return the results.
	    if ($status)
	    {
	        $return = $result['ImageId'];
	    }
	    else
	    {
	        $return = false;
	    }
	
	    return $return;
	}

	/**
	 * For the supplied userid get all the associated locations
	 *
	 * @param array $data
	 * @return FDatabaseVoResult
	 */
	public static function getLocations($userId)
	{
		// Initialize the Database Result object
		$daoResult = new FDatabaseVoResult();

		if (!empty($userId))
		{
			// Get the database connection
			$pdo = FDatabaseBoSingleton::Instance();
				
			// Build the query string.  Using Heredoc because it's easier to read.
			$sql = <<<SQL
SELECT 
    l.LocationId,
    l.UserId,
    l.LocationName,
    l.LocationDescription,
    i.ImageName,
    l.LocationPhoneNumber,
    l.LocationAddress,
    l.LocationCity,
    l.USStateId,
    l.LocationZip,
    l.LocationWebSite,
    l.LocationTwitterUserName,
    l.LocationFacebookUserName,
    l.LocationInstagramUserName,
    t.TransmitterBssid,
    t.TransmitterSsid,
    uss.USStateAbbr
FROM
    Location l
        JOIN
    Transmitter t ON t.LocationId = l.LocationId
		JOIN
	Image i ON l.ImageId = i.ImageId
        JOIN
    USState uss ON l.USStateId = uss.USStateId
WHERE
    UserId = :userId
AND
    l.LocationDeleted = 0
SQL;

       $stmt = $pdo->prepare($sql);
       $status = $stmt->execute(array(
           ':userId' => $userId
       ));
       $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

       // Check the status and return the results.
       if ($status)
       {
         $daoResult->setResult(count($result) . ' Records retrieved.', FDatabaseVoResult::SUCCESS);
         $daoResult->setData($result);
       }
       else
       {
         error_log( print_r($stmt->errorInfo(),1));
         $daoResult->setResult('Query failed.', FDatabaseVoResult::ERROR);
       }
     }
     else
     {
       $daoResult->setResult('Invalid userid supplied.', FDatabaseVoResult::ERROR);
     }

     return $daoResult;
   }
   
   /**
    * For the supplied userid get all the associated states
    *
    * @return FDatabaseVoResult
    */
   public static function readStates()
   {
	   	// Initialize the Database Result object
	   	$daoResult = new FDatabaseVoResult();
	   
	   
	   	// Get the database connection
	   	$pdo = FDatabaseBoSingleton::Instance();
   
   		// Build the query string.  Using Heredoc because it's easier to read.
   		$sql = <<<SQL
SELECT 
    USStateId, USStateName, USStateAbbr
FROM
    USState
SQL;
   
	   	$stmt = $pdo->prepare($sql);
	   	$status = $stmt->execute();
	   	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	   
	   	// Check the status and return the results.
	   	if ($status)
	   	{
	   		$daoResult->setResult(count($result) . ' Records retrieved.', FDatabaseVoResult::SUCCESS);
	   		$daoResult->setData($result);
	   	}
	   	else
	   	{
	   		error_log( print_r($stmt->errorInfo(),1));
	   		$daoResult->setResult('Query failed.', FDatabaseVoResult::ERROR);
	   	}
	
	   
	   	return $daoResult;
   }
   
   /**
    * Delete the supplied location.
    *
    * @param array $data
    * @return FDatabaseVoResult
    */
   public static function deleteLocation($userId, $locationId)
   {
   	$daoResult = new FDatabaseVoResult();
   	$pdo = FDatabaseBoSingleton::Instance();
   	$sql = "UPDATE Location SET LocationDeleted=1 WHERE LocationId = $locationId";
   	$stmt = $pdo->prepare($sql);
	$status = $stmt->execute();
   	$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
   
   	if ($status)
   	{
   		$daoResult->setResult(count($result) . ' Records retrieved.', FDatabaseVoResult::SUCCESS);
   		$daoResult->setData($result);
   	}
   	else
   	{
   		error_log( print_r($stmt->errorInfo(),1));
   		$daoResult->setResult('Query failed.', FDatabaseVoResult::ERROR);
   	}
   
   	return $daoResult;
   }   
}
