<?php
namespace Core\Form;

use Core\FixedArrayAccess;

class Form extends FixedArrayAccess
{
    protected $rules;
    protected $rulesMessages;
    protected $errors;

	/**
	 * Import object to validate
	 * @param FixedArrayAccess $identifiable
	 * @return $this
	 */
	public function import(FixedArrayAccess $identifiable)
    {
        $this->propertyNames = $identifiable->propertyNames;
        $this->properties = $identifiable->properties;
        
        return $this;
    }
    /**
     * 
     * @param string $property
     * @param callable $rule
     * @param string $message
     * @return \Core\Form
     * @throws \Exception
     */
    public function addRule($property, $rule, $message = null)
    {
        if (in_array($property, $this->propertyNames)) {
            $this->rules[$property] = $rule;
            $this->rulesMessages[$property] = $message;
        } else {
            throw new WrongRuleNameException('Incorrect property name '.$property);
        }
        
        return $this;
    }

	/**
	 * @param array $rules
	 */
	public function addRules(array $rules)
    {
        foreach ($rules as $property => $item) {
	        list($rule, $message) = $item;
            $this->addRule($property, $rule, $message);
        }
    }

	/**
	 * @return bool
	 */
	public function validate()
    {
        $this->errors = array();
        
        foreach ($this->rules as $property => $rule) {
            if (!$rule($this[$property])) {
                $this->errors[$property] = $this->rulesMessages[$property];
            }
        }

	    return $this->hasErrors();
    }

	/**
	 * @return bool
	 */
	public function hasErrors()
    {
        return !empty($this->errors);
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
}
