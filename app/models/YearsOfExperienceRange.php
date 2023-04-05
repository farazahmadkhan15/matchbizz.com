<?php

namespace App\Models;

use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness as UniquenessValidator;

class YearsOfExperienceRange extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(column="id", type="integer", length=11, nullable=false)
     */
    protected $id;

    /**
     *
     * @var double
     * @Column(column="min", type="integer", length=17, nullable=false)
     */
    protected $min;

    /**
     *
     * @var double
     * @Column(column="max", type="double", length=17, nullable=false)
     */
    protected $max;

    /**
     * Method to set the value of field id
     *
     * @param integer $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Method to set the value of field max
     *
     * @param string $max
     * @return $this
     */
    public function setMax($max)
    {
        $this->max = $max;

        return $this;
    }

    /**
     * Method to set the value of field min
     *
     * @param string $min
     * @return $this
     */
    public function setMin($min)
    {
        $this->min = $min;

        return $this;
    }

    /**
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field max
     *
     * @return double
     */
    public function getMax()
    {
        return $this->max;
    }

    /**
     * Returns the value of field min
     *
     * @return double
     */
    public function getMin()
    {
        return $this->min;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("matchbizz");
        $this->setSource("yearsOfExperienceRange");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'yearsOfExperienceRange';
    }

    public function validation()
    {
        $validator = new Validation();
        return $this->validate($validator);
    }
}
