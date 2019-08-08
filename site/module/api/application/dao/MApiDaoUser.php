<?php
class MApiDaoUser
{
	public static function registerDevice($deviceUUID)
	{
		$pdo = FDatabaseBoSingleton::Instance();
		$daoResult = new FDatabaseVoResult();

		$sql = "SELECT * FROM Device WHERE BINARY DeviceUUID = '$deviceUUID'";

		// check if we are already registered
		$stmt = $pdo->prepare($sql);
		$status = $stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		// if registered
		if (count($result) > 0) {
			$registerCount = $result[0]['DeviceRegisterCount'];
			$deviceId = $result[0]['DeviceId'];
			$registerCount++;
			
			// increment count
			$sql = "UPDATE Device 
					SET DeviceRegisterCount = $registerCount, 
						DeviceLastRegistered = NOW() 
					WHERE DeviceId = $deviceId";
			$stmt = $pdo->prepare($sql);
			$status = $stmt->execute();
			
		} else {
			// register new device
			// start by creating a new blank user record
			$sql = "INSERT INTO User VALUES()";
			$stmt = $pdo->prepare($sql);
			$status = $stmt->execute();
			$userId = $pdo->lastInsertId();
			
			// then a new device record
			$sql = "INSERT INTO Device 
						(DeviceUUID, DeviceTypeId, DeviceRegisterCount, DeviceLastRegistered, UserId) 
					VALUES ('$deviceUUID', 1, 1, NOW(), $userId)";
			$stmt = $pdo->prepare($sql);
			$status = $stmt->execute();
		}

 		if ($status) {
			$daoResult->setResult('Device registration succeeded', FDatabaseVoResult::SUCCESS);
		
 		} else {
			$daoResult->setResult('Device registration failed', FDatabaseVoResult::ERROR);
		}

		return $daoResult;
	}
	
}