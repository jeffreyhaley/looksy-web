<?php
class MLoginController
{	
	public function dispatch($action, $data)
	{	
		switch ($action)
		{
			case 'submitLogin':				
				$loginBo = new MLoginBoLogin();
				return $loginBo->login($data);
				break;
			default:
				break;
		}
	}
}