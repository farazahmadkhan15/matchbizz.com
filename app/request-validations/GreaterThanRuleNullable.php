<?php

use Rakit\Validation\Rule;

class GreaterThanRuleNullable extends Rule
{

    protected $message = "The :attribute :firstField must be greater than :secondField";

    protected $fillable_params = ['firstField','secondField'];

    public function check($value)
    {
        $this->requireParameters($this->fillable_params);
        
        $firstField = $this->parameter('firstField');
        $secondField = $this->parameter('secondField');
        if($value[$firstField] == null && $value[$secondField] == null){
            return false;
        }
        return $value[$firstField] > $value[$secondField] || $value[$firstField] == null || $value[$secondField] == null;
    }

}