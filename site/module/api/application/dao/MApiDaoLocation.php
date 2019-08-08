<?php
class MApiDaoLocation
{
	public static function registerUserLocationVisit($deviceId, $locationId)
	{
		$userId = MApiDaoLocation::deviceUUIDToUserId($deviceId);
		$daoResult = new FDatabaseVoResult();
		$pdo = FDatabaseBoSingleton::Instance();
		$sql = "SELECT VisitCount 
				FROM UserLocationVisit 
				WHERE UserId = $userId 
				AND LocationId = $locationId";
		$stmt = $pdo->prepare($sql);
		$status = $stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if (count($result) == 0) {
			$sql = "INSERT INTO UserLocationVisit 
					(UserId, LocationId) VALUES 
					($userId, $locationId)";
			$stmt = $pdo->prepare($sql);
			$status = $stmt->execute();
		} else {
			$visitCount = $result[0]['VisitCount'];
			$visitCount++;
			$sql = "UPDATE UserLocationVisit 
					SET VisitCount = $visitCount, LastVisited = NOW() 
					WHERE UserId = $userId 
					AND LocationId = $locationId";
			$stmt = $pdo->prepare($sql);
			$status = $stmt->execute();
		}
		
		if ($status) {
			$daoResult->setResult('Records updated', FDatabaseVoResult::SUCCESS);
			$daoResult->setData(array());
				
		} else {
			$daoResult->setResult('registerUserLocationVisit query failed', FDatabaseVoResult::ERROR);
		}
		
		return $daoResult;
	}
	
	public static function registerUserLocationPassBy($deviceId, $locationId)
	{
		$userId = MApiDaoLocation::deviceUUIDToUserId($deviceId);
		$daoResult = new FDatabaseVoResult();
		$pdo = FDatabaseBoSingleton::Instance();
		$sql = "SELECT PassByCount
				FROM UserLocationPassBy
				WHERE UserId = $userId
				AND LocationId = $locationId";
		$stmt = $pdo->prepare($sql);
		$status = $stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if (count($result) == 0) {
			$sql = "INSERT INTO UserLocationPassBy
					(UserId, LocationId) VALUES
					($userId, $locationId)";
			$stmt = $pdo->prepare($sql);
			$status = $stmt->execute();
			
		} else {
			$passByCount = $result[0]['PassByCount'];
			$passByCount++;
			$sql = "UPDATE UserLocationPassBy
					SET PassByCount = $passByCount, LastPassBy = NOW()
					WHERE UserId = $userId
					AND LocationId = $locationId";
			$stmt = $pdo->prepare($sql);
			$status = $stmt->execute();
		}
		
		if ($status) {
			$daoResult->setResult('Records updated', FDatabaseVoResult::SUCCESS);
			$daoResult->setData(array());
			
		} else {
			$daoResult->setResult('registerUserLocationPassBy query failed', FDatabaseVoResult::ERROR);
		}
		
		return $daoResult;
	}
	
	public static function getLocationsForBssids($deviceId, array $bssidList) {
	    return MApiDaoLocation::getLocations($deviceId, $bssidList, null);
	}
	
	public static function getFavoriteLocations($deviceId) {
	    return MApiDaoLocation::getLocations($deviceId, null, $deviceId);
	}
	
	private static function getLocations($deviceId, array $bssidList=null, $deviceIdFilter=null)
	{
		$pdo = FDatabaseBoSingleton::Instance();
		$daoResult = new FDatabaseVoResult();
		
		if ($bssidList == null && $deviceIdFilter == null)
		    return;
		
		// build SELECT
		$sqlSELECT = "SELECT * ";
		
		// build FROM
		$sqlFROM = "FROM Location l, Transmitter t, USState us, Image i ";
		if ($deviceIdFilter != null) {
		    $userId = MApiDaoLocation::deviceUUIDToUserId($deviceIdFilter);
		    $sqlFROM .= ", UserLocationFavorite ulf ";
		}
		
		// build WHERE
		$sqlWHERE = "WHERE us.USStateId = l.USStateId
		             AND l.ImageId = i.ImageId 
		             AND l.LocationDeleted = 0
		             AND t.LocationId = l.LocationId ";
		
		if ($bssidList != null) {
		    $bssIdString = implode('","', $bssidList);
		    $sqlWHERE .= "AND t.TransmitterBSSID IN (\"$bssIdString\") ";
		}
		if ($deviceIdFilter != null) {
		    $userId = MApiDaoLocation::deviceUUIDToUserId($deviceIdFilter);
		    $sqlWHERE .= "AND ulf.LocationId = l.LocationId ";
		    $sqlWHERE .= "AND ulf.UserId = $userId ";
		}
		
		// group
		$sqlGROUPBY = "GROUP BY l.LocationId";
		
		// full query
		$sql = $sqlSELECT . $sqlFROM . $sqlWHERE . $sqlGROUPBY;
		
		$stmt = $pdo->prepare($sql);
		$status = $stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

		// Check the status and return the results
 		if ($status) {
 			// add favorites data and register pass by
 			foreach ($result as &$entry) {
 				$locationId = $entry['LocationId'];
 				
 				// set favorites
 				$entry['Favorite'] = 
 					MApiDaoLocation::isLocationFavorite(
 							$deviceId, $locationId)->getData()[0]['Favorite']; // yeah, this looks dumb
 				$entry['FavoriteCount'] = 
 					MApiDaoLocation::getLocationFavoriteCount(
 							$deviceId, $locationId)->getData()[0]['FavoriteCount'];
 				$entry['Hours'] =
 				    MApiDaoLocation::getLocationHours($deviceId, $locationId)->getData();
 			}
			$daoResult->setResult(count($result) . ' records retrieved', FDatabaseVoResult::SUCCESS);
			$daoResult->setData($result);
			
		} else {
			$daoResult->setResult('getLocations query failed', FDatabaseVoResult::ERROR);
		}

		return $daoResult;
	}
	
	public static function isLocationFavorite($deviceId, $locationId)
	{
		$pdo = FDatabaseBoSingleton::Instance();
		$daoResult = new FDatabaseVoResult();
		
		$sql = "SELECT COUNT(*) AS Favorite
				FROM UserLocationFavorite ulf, Device d
				WHERE ulf.UserId = d.UserId
				AND ulf.LocationId = $locationId
				AND d.DeviceUUID = '$deviceId'";
		
		$stmt = $pdo->prepare($sql);
		$status = $stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		if ($status) {
			$daoResult->setResult(count($result) . ' records retrieved', FDatabaseVoResult::SUCCESS);
			$daoResult->setData($result);
			
		} else {
			$daoResult->setResult('isLocationFavorite query failed', FDatabaseVoResult::ERROR);
		}
		
		return $daoResult;
	}
	
	public static function updateLocationFavoriteStatus($deviceId, $locationId, $favorite)
	{
		$pdo = FDatabaseBoSingleton::Instance();
		$daoResult = new FDatabaseVoResult();
	
		$userId = MApiDaoLocation::deviceUUIDToUserId($deviceId);
		if ($favorite) {
			$sql = "INSERT INTO UserLocationFavorite (UserId, LocationId)
					VALUES($userId, $locationId)";
		} else {
			$sql = "DELETE FROM UserLocationFavorite
					WHERE UserId = $userId AND LocationId = $locationId";
		}
	
		$stmt = $pdo->prepare($sql);
		$status = $stmt->execute();
	
		return MApiDaoLocation::getLocationFavoriteCount($deviceId, $locationId);
	}
	
	public static function getLocationsCount($deviceId, array $bssidList)
	{
		$pdo = FDatabaseBoSingleton::Instance();
		$daoResult = new FDatabaseVoResult();
		$bssIdString = implode('","', $bssidList);
		$sql = "SELECT t.TransmitterBSSID 
				FROM Location l 
				JOIN Transmitter t ON l.LocationId = t.LocationId 
				AND t.TransmitterBSSID IN (\"$bssIdString\") 
				AND l.LocationDeleted = 0 
				GROUP BY l.LocationId";
		$stmt = $pdo->prepare($sql);
		$status = $stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

		// Check the status and return the results
		if ($status) {
			$daoResult->setResult(count($result) . ' records retrieved', FDatabaseVoResult::SUCCESS);
			$daoResult->setData($result);
				
		} else {
			$daoResult->setResult('getLocationsCount query failed', FDatabaseVoResult::ERROR);
		}
	
		return $daoResult;
	}
	
	public static function getLocationFavoriteCount($deviceId, $locationId) 
	{
		$pdo = FDatabaseBoSingleton::Instance();
		$daoResult = new FDatabaseVoResult();
		$sql = "SELECT COUNT(*) AS FavoriteCount
				FROM UserLocationFavorite
				WHERE LocationId = $locationId";
		$stmt = $pdo->prepare($sql);
		$status = $stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		// Check the status and return the results
		if ($status) {
			$daoResult->setResult(count($result) . ' records retrieved', FDatabaseVoResult::SUCCESS);
			$daoResult->setData($result);
		
		} else {
			$daoResult->setResult('getLocationFavoriteCount query failed', FDatabaseVoResult::ERROR);
		}
		
		return $daoResult;
	}
	
	public static function getTilesForLocation($deviceId, $locationId)
	{
		$pdo = FDatabaseBoSingleton::Instance();
		$daoResult = new FDatabaseVoResult();
		$sql = "SELECT *
				FROM Tile t, Image i
				WHERE t.LocationId = $locationId
				AND t.ImageId = i.ImageId 
				AND t.TileDeleted = 0 
				ORDER BY TileCreationTime DESC";
		$stmt = $pdo->prepare($sql);
		$status = $stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
		// Check the status and return the results
		if ($status) {
			foreach ($result as &$entry) {
				$tileId = $entry['TileId'];
				$entry['Favorite'] = MApiDaoLocation::isTileFavorite(
							$deviceId, $tileId)->getData()[0]['Favorite'];
				$entry['FavoriteCount'] =
					MApiDaoLocation::getTileFavoriteCount(
							$deviceId, $tileId)->getData()[0]['FavoriteCount'];
			}
			$daoResult->setResult(count($result) . ' records retrieved', FDatabaseVoResult::SUCCESS);
			$daoResult->setData($result);
	
		} else {
			$daoResult->setResult('getLocationTiles query failed', FDatabaseVoResult::ERROR);
		}
	
		return $daoResult;
	}
	
	public static function getTileFavoriteCount($deviceId, $tileId) 
	{
		$pdo = FDatabaseBoSingleton::Instance();
		$daoResult = new FDatabaseVoResult();
		$sql = "SELECT COUNT(*) AS FavoriteCount
				FROM UserTileFavorite
				WHERE TileId = $tileId";
		$stmt = $pdo->prepare($sql);
		$status = $stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
		// Check the status and return the results
		if ($status) {
			$daoResult->setResult(count($result) . ' records retrieved', FDatabaseVoResult::SUCCESS);
			$daoResult->setData($result);
	
		} else {
			$daoResult->setResult('getTileFavoriteCount query failed', FDatabaseVoResult::ERROR);
		}
	
		return $daoResult;
	}
	
	public static function isTileFavorite($deviceId, $tileId)
	{
		$pdo = FDatabaseBoSingleton::Instance();
		$daoResult = new FDatabaseVoResult();
	
		$sql = "SELECT COUNT(*) AS Favorite
				FROM UserTileFavorite utf, Device d
				WHERE utf.UserId = d.UserId
				AND utf.TileId = $tileId
				AND d.DeviceUUID = '$deviceId'";
	
		$stmt = $pdo->prepare($sql);
		$status = $stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
		if ($status) {
			$daoResult->setResult(count($result) . ' records retrieved', FDatabaseVoResult::SUCCESS);
			$daoResult->setData($result);
				
		} else {
			$daoResult->setResult('isTileFavorite query failed', FDatabaseVoResult::ERROR);
		}
	
		return $daoResult;
	}
	
	public static function updateTileFavoriteStatus($deviceId, $tileId, $favorite)
	{
	    // perform the insert/delete
		$pdo = FDatabaseBoSingleton::Instance();
		$daoResult = new FDatabaseVoResult();
	
		$userId = MApiDaoLocation::deviceUUIDToUserId($deviceId);
		if ($favorite) {
			$isAlreadyFavorite = 
				MApiDaoLocation::isTileFavorite(
						$deviceId, $tileId)->getData()[0]['Favorite'] >= 1;
			if (!$isAlreadyFavorite)
				$sql = "INSERT INTO UserTileFavorite (UserId, TileId)
						VALUES($userId, $tileId)";
		} else {
			$sql = "DELETE FROM UserTileFavorite
					WHERE UserId = $userId AND TileId = $tileId";
		}

		$stmt = $pdo->prepare($sql);
		$status = $stmt->execute();
		
		// return the new count
		return MApiDaoLocation::getTileFavoriteCount($deviceId, $tileId);
	}
	
	public static function registerUserTileVisit($deviceId, $tileId)
	{
		$daoResult = new FDatabaseVoResult();
		$userId = MApiDaoLocation::deviceUUIDToUserId($deviceId);
		$pdo = FDatabaseBoSingleton::Instance();
		$sql = "SELECT VisitCount
				FROM UserTileVisit
				WHERE UserId = $userId
				AND TileId = $tileId";
		$stmt = $pdo->prepare($sql);
		$status = $stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if (count($result) == 0) {
			$sql = "INSERT INTO UserTileVisit
					(UserId, TileId) VALUES
					($userId, $tileId)";
			$stmt = $pdo->prepare($sql);
			$stmt->execute();
		} else {
			$visitCount = $result[0]['VisitCount'];
			$visitCount++;
			$sql = "UPDATE UserTileVisit
					SET VisitCount = $visitCount, LastVisited = NOW()
					WHERE UserId = $userId
					AND TileId = $tileId";
			$stmt = $pdo->prepare($sql);
			$stmt->execute();
		}
		if ($status) {
			$daoResult->setResult(count($result) . ' records retrieved', FDatabaseVoResult::SUCCESS);
			$daoResult->setData($result);
	
		} else {
			$daoResult->setResult('registerUserTileVisit query failed', FDatabaseVoResult::ERROR);
		}
		
		return $daoResult;
	}
	
	public static function getLocationHours($deviceId, $locationId)
	{
	    $pdo = FDatabaseBoSingleton::Instance();
	    $daoResult = new FDatabaseVoResult();
	
	    $sql = "SELECT *
        	    FROM LocationHours lh, Day d
        	    WHERE lh.LocationId = $locationId
        	    AND lh.DayId = d.DayId
        	    ORDER BY lh.DayId ASC";
	
	    $stmt = $pdo->prepare($sql);
	    $status = $stmt->execute();
	    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	    if ($status) {
	        $daoResult->setResult(count($result) . ' records retrieved', FDatabaseVoResult::SUCCESS);
	        $daoResult->setData($result);
	
	    } else {
	        $daoResult->setResult('getLocationHours query failed', FDatabaseVoResult::ERROR);
	    }
	
	    return $daoResult;
	}
	
	private static function deviceUUIDToUserId($deviceUUID) {
		$pdo = FDatabaseBoSingleton::Instance();
		$sql = "SELECT UserId FROM Device WHERE DeviceUUID = '$deviceUUID'";
		$stmt = $pdo->prepare($sql);
		$status = $stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $result[0]['UserId'];
	}
	
	
	
}