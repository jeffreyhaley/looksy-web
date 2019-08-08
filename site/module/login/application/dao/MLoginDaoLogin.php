<?php
class MLoginDaoLogin
{
	/**
	 * For the supplied login data check if the username and pass are valid
	 * 
	 * @param array $data
	 * @return FDatabaseVoResult
	 */
	public static function checkLogin($data)
	{
		$pdo = FDatabaseBoSingleton::Instance();
		$daoResult = new FDatabaseVoResult();
		
		$stmt = $pdo->prepare('SELECT COUNT(*) AS Authenticated, UserEmail, UserId  FROM User WHERE UserEmail=:email AND UserPassword=:password');
		$status = $stmt->execute(array(':email' => $data->getuserEmail(), ':password' => $data->getUserPassword()));	
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		
		if ($result['Authenticated'] === '1')
		{		
			$daoResult->setResult('Login is valid.', FDatabaseVoResult::SUCCESS);
			$daoResult->setData($result);
		}
		else
		{
			error_log( print_r($stmt->errorInfo(),1));
			$daoResult->setResult('Incorrect username or password.', FDatabaseVoResult::ERROR);			
		}
		
		return $daoResult;
	}
}