<?php
class FDatabaseBoSingleton {
	
	static $database = null;
	
	/**
	 * Call this method to get singleton
	 *
	 * @return UserFactory
	 */
	public static function Instance()
	{		
		if (self::$database === null) {
			new FDatabaseBoSingleton();
		}
		
		return self::$database;
	}
	
	/**
	 * Private ctor so nobody else can instance it
	 *
	 */
	private function __construct()
	{
		$dbPassword = Config::DB_PASS;
		$dbName 	= Config::DB_NAME;
		$dbUser 	= Config::DB_USER;
		$dbHostname = Config::DB_HOSTNAME;
		
		self::$database = new PDO("mysql:host=$dbHostname:3306;dbname=". $dbName, $dbUser, $dbPassword);
		
		if (!self::$database) {
			echo ('Could not connect.');
		}
	}
	
}

?>