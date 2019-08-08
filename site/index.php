<?php
/**
 * Front Controller; every request goes through this file, and this is where we bootstrap the application, 
 * register class autoloading, configure everything. This is not a class but a file with 
 * procedural code that boots everything up.
*/
date_default_timezone_set ('UTC');

/**
 * Setup the Autoloading.  The vendor autoload loads libraries like logging, caching etc.  The
 * frameworkAutoloadCase loads classes based on their pascal cased file names.
 */
require './config/config.php';
require './framework/loader/application/bo/FLoaderBoAutoloader.php';
FLoaderBoAutoloader::VendorAutoload();
spl_autoload_register(array('FLoaderBoAutoloader','frameworkAutoloadCase'));


// error_log('P: '.print_r($_POST,1));
// error_log('R: '.print_r($_REQUEST,1));
// error_log('R: '.print_r($_GET,1));
/**
 * Setup the request and response.  Every request will need a Module, Action and data.  If they do not
 * contain these they are considered valid. 
 */
$requestVo 	= FWebBoRequest::SetupRequest();
$responseVo = FWebVoResponse::Singleton();
$module 	= $requestVo->getModule();
$action 	= $requestVo->getAction();
$data 		= $requestVo->getData();

// error_log(print_r($requestVo,1));

//move_uploaded_file

/**
 * Based on the module passed in, load the controller for the 
 * requested Module. If the controller does not exist throw and 
 * error. 
 */
$class = 'M' . ucfirst ($module) . 'Controller';
if (class_exists($class))
{
	$controller = new $class();	
	$responseVo = $controller->dispatch ($action, $data);
}
else
{
	throw new ErrorException('Invalid controller referenced.');
}

/**
 * Check that the response being supplied is a valid JSON response.
 * All responses must be in JSON.
 */
if ($responseVo != null)
{
	echo $responseVo->encode();
}
else
{
	throw new ErrorException('Invalid response supplied.');	
}
?>