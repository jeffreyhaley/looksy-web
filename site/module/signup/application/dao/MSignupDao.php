<?php
class MSignupDao
{
	public function __construct() {}
	
	/**
	 * Attempts to create a new User account with the supplied data.
	 * 
	 * @param obj $data
	 * @return 
	 */
	public static function saveData(MCoreVoUser $data)
	{		
		$pdo = FDatabaseBoSingleton::Instance();
		$daoResult = new FDatabaseVoResult();
	
		// Check if there user already exists.
		$stmt = $pdo->prepare('SELECT COUNT(*) AS Count FROM User WHERE UserEmail=:email');
		$status = $stmt->execute(array(
				':email' => $data->getUserEmail()));
		$result = $stmt->fetch(PDO::FETCH_ASSOC);	

		if ($result['Count'] > 0)
		{
			$daoResult->setResult('Username already exits.', FDatabaseVoResult::ERROR);
		}
		else 
		{			
			$stmt = $pdo->prepare('INSERT INTO User (UserFirstName, UserLastName, UserEmail, UserPassword) VALUES (:firstname, :lastname, :email, :password)');
			$status = $stmt->execute(array(
					':firstname' => $data->getUserFirstName(), 
					':lastname' => $data->getUserLastName(), 
					':email' => $data->getUserEmail(), 
					':password' => $data->getUserPassword()));

			if ($status)
			{
				$daoResult->setResult('User added successfully.', FDatabaseVoResult::SUCCESS);
			}	
			else
			{
				error_log( print_r($stmt->errorInfo(),1));
				$daoResult->setResult('There was an error creating your user, please try again later.', FDatabaseVoResult::ERROR);						
			}
		}
		
		return $daoResult;
	}
}