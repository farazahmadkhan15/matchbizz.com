<?php

use Rakit\Validation\Rule;
use Phalcon\Mvc\Model\Manager;

class RequiredOnlyIfRule extends Rule
{
    protected $message = ":attribute is required only if :field is :val";

    protected $fillable_params = ['field', 'val'];

    public function check($value)
    {
        $this->requireParameters(['field', 'val']);

        $anotherField = $this->parameter('field');
        $anotherRequiredValue = $this->parameter('val');

        $anotherValue = $this->validation->getValue($anotherField);

        return $anotherValue == $anotherRequiredValue && is_array($value);
    }

}