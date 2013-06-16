<?php
namespace Core\Form;

use Core\FixedArrayAccess;

class Form extends FixedArrayAccess
{
    protected $rules;
    protected $rulesMessages;
    protected $errors;


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
    
    public function addRules(array $rules)
    {
        foreach ($rules as $property => $item) {
            $this->addRule($property, $item[0], $item[1]);
        }
    }
    
    public function validate()
    {
        $this->errors = array();
        
        foreach ($this->rules as $property => $rule) {
            if (!$rule($this[$property])) {
                $this->errors[$property] = $this->rulesMessages[$property];
            }
        }
    }
    
    public function hasErrors()
    {
        return !empty($this->errors);
    }
    
    public function getErrMsg($property)
    {
        if (isset($this->errors[$property])) {
            return $this->errors[$property];
        }
    }
}
