<?php
class MSignupController extends FControllerBoController
{	
	public function dispatch($action, $data)
	{
		switch ($action)
		{
			case 'submitSignup':
				$signupBo = new MSignupBoSignup();
				return $signupBo->saveData($data);
				break;
			default:
				break;
		}
	}
}