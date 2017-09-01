<?php

/**
 * Description of Base
 *
 * @author nzpetter
 */
abstract class Controller_Jsonapi_Base extends Controller
{

	const CONTENT_TYPE = 'application/vnd.api+json';

	public function before()
	{
		$this->checkContentType();
	}

	protected function checkContentType()
	{
		$headers = $this->request->headers();

		if (!isset($headers['content-type']))
		{
			return $this->_error('Content-Type missing', 406);
		}

		$contentTypes = explode(',', $headers['content-type']);
		$unacceptableContentTypes = [];
		foreach ($contentTypes as $contentType)
		{
			if ($contentType == self::CONTENT_TYPE)
			{
				$unacceptableContentTypes = [];
				break;
			}
			else
			{
				$unacceptableContentTypes[] = $contentType;
			}
		}

		if (count($unacceptableContentTypes) == 1)
		{
			$this->_error($unacceptableContentTypes[0], 415);
		}
		elseif (count($unacceptableContentTypes) > 1)
		{
			$this->_error(implode(',', $unacceptableContentTypes), 406);
		}
	}

	protected function error_output(array $data = [], int $code = 500)
	{
		$this->response->headers('Content-Type', self::CONTENT_TYPE)->status($code);
		echo json_encode($data);
	}

	protected function output(array $data = [], int $code = 200)
	{
		$this->response->headers('Content-Type', self::CONTENT_TYPE)->status($code);
		echo json_encode($data);
	}

	/**
	 * Generate an error message.
	 *
	 * @param string|Exception $exception
	 * @param int $code
	 */
	protected function _error($exception, $code = 0)
	{
		if (is_a($exception, 'Exception'))
		{
			$message = $exception->getMessage();
			$code = $exception->getCode();
		}
		else
		{
			$message = (string) $exception;
		}

		$output = array(
			'code' => $code,
			'error' => $message,
		);

		$this->error_output($output, $code);
		// This is here just to avoid going to the real action when the error is in before().
		// @TODO find a better solution.
		$this->request->action('error');
	}

	/**
	 * See comment in _error().
	 */
	public function action_error()
	{
		
	}

}
