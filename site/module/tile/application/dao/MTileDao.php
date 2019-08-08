<?php
class MTileDao
{
	/**
	 * For the supplied tile data create a new record
	 * 
	 * @param MCoreVoTile $data
	 * @return FDatabaseVoResult
	 */
	public static function createTile(MCoreVoTile $data, $imageId)
	{
		// Get the database connection
		$pdo = FDatabaseBoSingleton::Instance();
	
		// Initialize the Database Result object
		$daoResult = new FDatabaseVoResult();
	
		// Build the query string.  Using Heredoc because it's easier to read.
		$sql = <<<SQL
INSERT INTO core.Tile
(
	LocationId,
	ImageId,
	TileCaption
)
VALUES
(
	:locationId,
	:imageId,
	:tileCaption
);
SQL;
	
		$stmt = $pdo->prepare($sql);		
		$status = $stmt->execute(array(
						':locationId' => $data->getLocationId(),
						':imageId' => $imageId,
						':tileCaption' => $data->getTileCaption()
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
	 * For the supplied tile data create a new record
	 *
	 * @param MCoreVoTile $data
	 * @return FDatabaseVoResult
	 */
	public static function updateTile(MCoreVoTile $data, $imageId)
	{	    
	    // Get the database connection
	    $pdo = FDatabaseBoSingleton::Instance();
	
	    // Initialize the Database Result object
	    $daoResult = new FDatabaseVoResult();
	    
	    if (!$imageId)
	    {
	        $imageId = self::readImageId($data->getTileId());
	    }
	
	    // Build the query string.  Using Heredoc because it's easier to read.
	    $sql = <<<SQL
UPDATE core.Tile
SET
	ImageId = :imageId,
	TileCaption = :tileCaption
WHERE
    TileId = :tileId
    AND LocationId IN (SELECT LocationId FROM Location WHERE LocationId = :locationId
	AND UserId = :userId)	
SQL;
	
	    $stmt = $pdo->prepare($sql);
	    $status = $stmt->execute(array(
	        ':locationId' => $data->getLocationId(),
	        ':imageId' => $imageId,
	        ':tileCaption' => $data->getTileCaption(),
	        ':userId' => FSecurityBoSecurity::getUserId(),
	        ':tileId' => $data->getTileId()
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
	 * For the supplied locations get all the associated tiles
	 *
	 * @param int $locationId
	 * @return FDatabaseVoResult
	 */
	public static function readTiles($locationId)
	{
		// Initialize the Database Result object
		$daoResult = new FDatabaseVoResult();
	
		// Get the database connection
		$pdo = FDatabaseBoSingleton::Instance();
		 
		// Build the query string.  Using Heredoc because it's easier to read.
		$sql = <<<SQL
SELECT
    t.TileId, 
	t.LocationId, 
	i.ImageName, 
	t.TileCaption, 
	t.TileCreationTime,
    COUNT(utf.TileId) TileFavoriteCount,
    COALESCE(SUM(utv.VisitCount), 0) AS TileVisitCount
FROM
    Tile t
JOIN Image i ON t.ImageId = i.ImageId
JOIN Location l ON l.LocationId = t.LocationId
JOIN Transmitter tr ON tr.LocationId = l.LocationId
LEFT JOIN UserTileVisit utv ON utv.TileId = t.TileId
LEFT JOIN UserTileFavorite utf ON utf.TileId = t.TileId    
WHERE
	t.LocationId = :locationId AND t.TileDeleted=0
GROUP BY TileId	
ORDER BY 
    t.TileCreationTime DESC
SQL;
		 
		$stmt = $pdo->prepare($sql);
		$status = $stmt->execute(array(':locationId' => $locationId));
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
	 * For the supplied tileid get all the associated tile
	 *
	 * @param int $tileId
	 * @return FDatabaseVoResult
	 */
	public static function readTile($tileId)
	{
		// Initialize the Database Result object
		$daoResult = new FDatabaseVoResult();
	
		// Get the database connection
		$pdo = FDatabaseBoSingleton::Instance();
		 
		// Build the query string.  Using Heredoc because it's easier to read.
		$sql = <<<SQL
SELECT
    t.TileId, 
	t.LocationId, 
	i.ImageName, 
	t.TileCaption, 
	t.TileCreationTime,
    COUNT(utf.TileId) TileFavoriteCount,
    COALESCE(SUM(utv.VisitCount), 0) AS TileVisitCount
FROM
    Tile t
JOIN Image i ON t.ImageId = i.ImageId
LEFT JOIN UserTileVisit utv ON utv.TileId = t.TileId
LEFT JOIN UserTileFavorite utf ON utf.TileId = t.TileId      
WHERE
	t.tileId = :tileId AND t.TileDeleted=0
GROUP BY TileId	    	
SQL;
		 
		$stmt = $pdo->prepare($sql);
		$status = $stmt->execute(array(':tileId' => $tileId));
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
	 * Get the ImageId for the supplied Location.
	 *
	 * @param int $locationId
	 * @return int|boolean
	 */
	public static function readImageId($tileId)
	{
	    $return = false;
	
	    // Get the database connection
	    $pdo = FDatabaseBoSingleton::Instance();
	
	    // Initialize the Database Result object
	    $daoResult = new FDatabaseVoResult();
	
	    // Build the query string.  Using Heredoc because it's easier to read.
	    $sql = <<<SQL
SELECT
    t.ImageId
FROM
    Tile t
JOIN Location l on t.LocationId = l.LocationId 
    AND l.UserId = :userId
WHERE
   	t.TileId = :tileId AND t.TileDeleted=0
SQL;
	
	    $stmt = $pdo->prepare($sql);
	    $status = $stmt->execute(array(
	        ':tileId' => $tileId,
	        ':userId' => FSecurityBoSecurity::getUserId()
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
	 * Delete the supplied tile.
	 *
	 * @param int $tileId
	 * @return FDatabaseVoResult
	 */
	public static function deleteTile($tileId)
	{
	   	// Initialize the Database Result object
	   	$daoResult = new FDatabaseVoResult();	   	 
	   	 
	   	// Get the database connection
	   	$pdo = FDatabaseBoSingleton::Instance();
	   	 
	   	// Build the query string.  Using Heredoc because it's easier to read.
	   	$sql = <<<SQL
UPDATE Tile t
        JOIN
    Location l ON l.LocationId = t.LocationId
        AND l.UserId = :userId
        and t.TileId = :tileId 
SET 
    t.TileDeleted = 1	   	
SQL;
	   	 
	   	$stmt = $pdo->prepare($sql);
	   	$status = $stmt->execute(array(
	   	    ':tileId' => $tileId,
	   	    ':userId' => FSecurityBoSecurity::getUserId()
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
}