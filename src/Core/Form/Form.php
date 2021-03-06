<?php

namespace Core\Form;

use Core\Utils\WrongArgumentException;

class Form
{
	protected $rules;
	protected $rulesMessages;
	protected $errors = [];
	protected $input;
	protected $results;
	protected $errorMessages = [];

	public function import(array $input)
	{
		$this->input = $input;
		return $this;
	}

	public function addRule($property, callable $rule, $message = null, $ruleName = null, $isRequired = true)
	{
		$ruleName = $ruleName ?: $property;
		if (array_key_exists($property, $this->input)) {
			$this->rules[$ruleName] = [
				'property' => $property,
				'rule' => $rule,
				'required' => $isRequired
			];
			$this->rulesMessages[$ruleName] = $message?: 'Invalid '.$property;
			return $this;
		} elseif (!$isRequired) {
			return $this;
		}

		throw new WrongRuleNameException($property);
	}

	/**
	 * @return bool
	 */
	public function validate()
	{
		$this->errors = [];

		foreach ($this->rules as $ruleName => $ruleData) {
			$rule = $ruleData['rule'];
			$property = $ruleData['property'];
			$isRequired = $ruleData['required'];

			$this->errorMessages[$ruleName] = $this->rulesMessages[$ruleName];

			if (!$isRequired && empty($this->input[$property])) {
				break;
			}

			if (!$result = $rule($this->input[$property], $this)) {
				$this->errors[$ruleName] = true;
				break;
			}
			$this->results[$ruleName] = $result;
		}

		return !$this->hasErrors();
	}

	/**
	 * @return bool
	 */
	public function hasErrors()
	{
		return !empty($this->errors);
	}

	public function getErrors()
	{
		return array_intersect_key($this->errorMessages, $this->errors);
	}

	/**
	 * @param $property
	 * @return mixed
	 */
	public function getErrMsg($property)
	{
		if (isset($this->errors[$property])) {
			return $this->errors[$property];
		}
	}

	/**
	 * @param $property
	 * @return mixed
	 */
	public function getResult($property)
	{
		if (isset($this->results[$property])) {
			return $this->results[$property];
		}
	}

	public function markWrong($property, $errMsg)
	{
		$this->errorMessages[$property] = $errMsg;
		$this->errors[$property] = true;
	}

	public function getValue($property)
	{
		if (!isset($this->input[$property])) {
			throw new WrongArgumentException('Invalid property name '.$property);
		}

		return $this->input[$property];
	}
}