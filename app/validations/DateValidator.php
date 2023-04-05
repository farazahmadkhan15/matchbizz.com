<?php

namespace App\Validations;
use Phalcon\Validation\Message;
use Phalcon\Validation\Validator;
use Phalcon\Validation\ValidatorInterface;

class DateValidator extends Validator
{
    /**
     * Executes the validation
     *
     * @param Phalcon\Validation $validator
     * @param string $attribute
     * @return boolean
     */
    public function validate(\Phalcon\Validation $validator, $attribute)
    {
        $value = $validator->getValue($attribute);
        $regexFecha = '/^(\d{4})(\/|-)(0[1-9]|1[0-2])\2([0-2][0-9]|3[0-1])$/';
        if (!preg_match($regexFecha, $value)) {

            $message = $this->getOption('message');
            if (!$message) {
                $message = 'Invalid Date';
            }

            $validator->appendMessage(new Message($message, $attribute, 'datetime'));

            return false;
        }

        return true;
    }
}