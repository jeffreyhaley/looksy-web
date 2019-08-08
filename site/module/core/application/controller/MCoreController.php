<?php
class MCoreController extends FControllerBoController
{
	public function dispatch($action, $data)
	{		
		switch ($action)
		{
			case 'logout':
				$userBo = new MCoreBoUser();				
				return $userBo->logout($data);
				break;
			case 'loadUser':	
			default:			
				$userBo = new MCoreBoUser();				
				return $userBo->loadUser();
				break;
		}
	}
}