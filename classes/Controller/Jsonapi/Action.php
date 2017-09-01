<?php

/**
 * Description of Action
 *
 * @author nzpetter
 */
class Controller_Jsonapi_Action extends Controller_Jsonapi_Base
{

	public function action_index()
	{
		$this->response->body('hello, world!');
	}

}
