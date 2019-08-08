<?php
class FLoaderBoAutoloader
{	
	const FOLDER = 0;
	const MODULE = 1;
	const TYPE = 2;
	
	const FRAMEWORK_FOLDER 	 = 'framework';
	const COMMON_FOLDER 	 = 'common';
	const MODULE_FOLDER 	 = 'module';
	const PUBLIC_FOLDER		 = 'public';
	const APPLICATION_FOLDER = 'application';
	
	/**
	 * Third party vendor require paths
	 *
	 * @var array
	 */
	protected static $vendorRequire;
	
	/**
	 * This Autoloader is registered in production.php. When a class cannot be found this autoloader is called.
	 *
	 * Be design the autoloader takes in a classname and then determines which files to
	 * include based on that classname.  The classname is made up of a few parts which
	 * are described below.  The important piece is that the classname must be delimited by
	 * a capitol letter.  Additional or absence of a capitol letter will fail to properly map
	 * to an existing file.
	 *
	 * File Naming Convention
	 * 		<basefolder>.<type>.<module>.<description>.php
	 * 		lib.bo.user.session.php
	 *  Class Naming Convention
	 *  	BasefolderTypeModuleDescription	LibBoUserSession
	 *  	Case must be camel where each capitol letter deliniates the breakdown of folders.
	
	 * @param string $className
	 * @return bool
	 */
	public static function frameworkAutoloadCase($className)
	{		
		// Split on uppercase letters
		$pathArray 	= preg_split('/(?=[A-Z])/', $className, -1, PREG_SPLIT_NO_EMPTY);		
		$folder 		= $pathArray[self::FOLDER];
		$module 		= $pathArray[self::MODULE];
		$application 	= self::APPLICATION_FOLDER;
		$type 			= $pathArray[self::TYPE];
		
		// To help make the file and class names shorter we use a single letter abbreviation.
		switch($folder)
		{
			case 'F':
				$folder = self::FRAMEWORK_FOLDER;
				break;
			case 'C':
				$folder = self::COMMON_FOLDER;
				break;
			case 'M':
				$folder = self::MODULE_FOLDER;
				break;
			case 'P':
				$folder = self::PUBLIC_FOLDER;
				break;
			default:
				break;				
		}
		
		
		// Build the path based on the PascalCase of the classname.
		$fileName	= $className . '.php';
		$filePath	= strtolower($folder . '/' . $module . '/' . $application . '/' . $type . '/');
		$fullPath 	= Config::ROOT_PATH . $filePath . $fileName;

		// If the file exists require it
		if (file_exists($fullPath))
		{
			require $fullPath;
			return true;
		}
		else
		{
			//error_log('Could not find class in frameworkAutoloadCase, checking next autoloader. ClassName=' . $className . ', Path=' . $fullPath);
			return false;
	
		}
	}	
	
	/**
	 * Get all the third party requires that are necessary.
	 *
	 * In general we'd like to keep Portal and third party code seperated.  The long term goal is to
	 * move away from the portal classmap and use an autoloader based on class name.  This would allow
	 * the large class map to be removed and only a third party class map would be necessary.
	 *
	 * @return array
	 */
	public static function getVendorRequire()
	{
		$vendorRequire = array(
				//'vendor/apache-log4php-2.2.1/src/main/php/Logger.php'
				'config/vendor/autoload.php'				
		);
	
		return $vendorRequire;
	}
	
	
	/**
	 * Get all third party include paths and include them.
	 *
	 * In general this should be a small set of files.  This was created because of a chicken and egg scenario with
	 * apache logging is smells a little off at this point.  During the transition phase for the autoloader
	 * files that cannot be autoloaded will be placed here.
	 *
	 * @return bool
	 */
	public static function VendorAutoload()
	{
		static::$vendorRequire = self::getVendorRequire();
	
		foreach (static::$vendorRequire as $path)
		{
			$fullPath = Config::ROOT_PATH . $path;
			if (is_file($fullPath))
			{
				require $fullPath;
			}
			else
			{
				return false;
			}
		}
	}
}