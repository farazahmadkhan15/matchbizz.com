<?php

namespace App\Validations;
use Phalcon\Mvc\User\Component;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Digit as DigitValidator;
use Phalcon\Validation\Validator\Numericality;
use Phalcon\Validation\Validator\Regex as RegexValidator;
use Phalcon\Validation\Validator\StringLength;
use App\Validations\DateTimeValidator;
use App\Validations\DateValidator;

class ModelValidator extends Component
{
    public $messages = [];

    /**
     * Gestiona las validacion y los mensajes que puedan ocurrirse
     *
     * @param  \Phalcon\Mvc\Model $model Modelo a validar
     * @return boolean
     */
    public function validateModel(\Phalcon\Mvc\Model $model)
    {
        $validation = new Validation();
        $validationObj = null;

        if (empty($model)) {
            return false;
        };

        /** @var \Phalcon\Annotations\Reflection $reflection */
        $reflection = $this->getDI()->get('annotations')->get($model);

        foreach ($reflection->getPropertiesAnnotations() as $field => $collection) {
            if (!$collection->has('Identity') && $collection->has('Column')) {
                /** @var Phalcon\Annotations\Annotation $column */
                $column =$collection->get('Column');
                if (!empty($column)) {
                    $arguments = $column->getArguments();
                    if (!empty($arguments)) {
                        /********************
                        * VALIDATE DATATYPE *
                        *********************/
                        $validateType = $this->validateDataType($arguments['type'], $field, $arguments['nullable']);
                        if (!empty($validateType)) {
                            $validation->add($field, $validateType);
                        }
                        /*****************
                         * VALIDATE NULL *
                         *****************/
                        if (!$arguments['nullable']) {
                            $validation->add(
                                $field,
                                new PresenceOf(
                                    [
                                        'message' => 'The :field is required',
                                    ]
                                )
                            );
                        }
                        /***********************
                         * VALIDATE MAXLENGTH  *
                         ***********************/
                        if (!empty($arguments['length'])) {
                            $validation->add(
                                $field,
                                new StringLength(
                                    [
                                        'messageMinimum' => 'The :field  is too long',
                                        'max'            => $arguments['length'],
                                        'allowEmpty'     => $arguments['nullable'],
                                    ]
                                )
                            );
                        }
                        /***********************
                         * VALIDATE MINLENGTH  *
                         ***********************/
                        if (!empty($arguments['minLength'])) {
                            $validation->add(
                                $field,
                                new StringLength(
                                    [
                                        'messageMinimum' => 'The :field  is too short',
                                        'min'            => $arguments['length'],
                                        'allowEmpty'     => $arguments['nullable'],
                                    ]
                                )
                            );
                        }
                        /*******************************
                         * VALIDATE REGULAR EXPRESION  *
                         ******************************/
                        if (!empty($arguments['regx'])) {
                            $validation->add(
                                new RegexValidator(
                                    [
                                        "pattern"    => '/'.$arguments['regx'].'/',
                                        "message"    => "Format is invalid",
                                        'allowEmpty' => $arguments['nullable'],
                                    ]
                                )
                            );
                        }
                    }
                }
            }
        }
        
        $this->messages = $validation->validate($model);

        return (count($this->messages)) ? false : true;
    }

    /**
     * Agrega la validacion correspondiente segun el tipo de dato del modelo
     *
     * @param  [string] $type   Nombre del tipo de dato
     * @param  [string] $field  Nombre de la propiedad, o variable a validar
     * @param  [bool]   $isNull Indica si acepta valores nulos
     * @return instanceOf Validation | null
     */
    public function validateDataType($type, $field, $isNull)
    {
        $validationObj = null;

        switch ($type) {
        case 'integer':
        case 'decimal':
        case 'float':
        case 'double':
            $validationObj = new Numericality(
                [
                    'field'   => $field,
                    'message' => 'The :field must be numeric',
                    'allowEmpty' => $isNull,
                ]
            );
            break;
        case 'date': 
            $validationObj = new DateValidator(
                [
                    'field'   => $field,
                    'message' => 'The :field is not a valida date',
                    'allowEmpty' => $isNull,
                ]
            );
            break;
        case 'datetime': 
            $validationObj =  new DateTimeValidator(
                [
                    'field'   => $field,
                    'allowEmpty' => $isNull,
                    'message' => 'The :field is not a valida date',
                ]
            );
            break;
        case 'email':
            $validationObj = new EmailValidator(
                [
                    'field'      => $field,
                    'allowEmpty' => $isNull,
                    'message'    => 'The :field is not a valid e-mail address',
                ]
            );
            break;
        }
        return $validationObj;
    }
}