<?php

use Rakit\Validation\Rule;

class GreaterThanRule extends Rule
{

    protected $message = "The :attribute :firstField must be greater than :secondField";

    protected $fillable_params = ['firstField','secondField'];

    public function check($value)
    {
        $this->requireParameters($this->fillable_params);

        $firstField = $this->parameter('firstField');
        $secondField = $this->parameter('secondField');

        return $value[$firstField] > $value[$secondField];
    }

}