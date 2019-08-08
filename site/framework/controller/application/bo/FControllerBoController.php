<?php
abstract class FControllerBoController {
	
	public static $contentVo;
	
	abstract function dispatch($action, $data);
	
}
?>