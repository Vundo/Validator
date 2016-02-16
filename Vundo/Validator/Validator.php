<?php

namespace Vundo\Validator;

class Validator {

	private $arguments = ['required', 'email', 'password'];
	protected $failed = false;

	public function validate($input, $rules)
	{
		foreach ($rules as $field => $arguments) {
			$this->callValidator($input[$field], $field, $arguments);
		}
		return ! $this->failed;
	}

	protected function required($input)
	{
		if(empty($input)) {
			$this->failed = true;
		}
	}

	protected function email($input)
	{
		if(!filter_var('bob@example.com', FILTER_VALIDATE_EMAIL)) {
			$this->failed = true;
		}
	}

	protected function password($input)
	{
		if(!(strlen($input) >= 4)) {
			$this->failed = true;
		} 
	}

	protected function multiple($arg)
	{
		if (count(explode('|', $arg)) > 1) {
			$args = explode('|', $arg);
			if (count(explode(':', $args[1]))) {
				$a 	   = $args[0];
				$specs = explode(':', $args[1]);
				return [true, $a, $specs];
			}
		} else {
			return false;
		}
	}

	protected function callValidator($input, $field, $arguments)
	{
		foreach ($arguments as $arg) {
			if ($this->multiple($arg)) {
				$argument = $this->multiple($arg)[1];
				// @TODO Add unique validator.
			} else {
				switch ($arg) {
					case 'required':
						$this->required($input);
						break;
					case 'email':
						$this->email($input);
						break;
					case 'password':
						$this->password($input);
						break;
				}	
			}
		}
	}
}
