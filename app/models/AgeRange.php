<?php

namespace App\Models;

use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness as UniquenessValidator;

class AgeRange extends BaseModel
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
     *
     * @var string
     * @Column(column="createdAt", type="string", nullable=false)
     */
    protected $createdAt;

    /**
     *
     * @var string
     * @Column(column="updatedAt", type="string", nullable=false)
     */
    protected $updatedAt;

    /**
     *
     * @var string
     * @Column(column="deletedAt", type="string", nullable=true)
     */
    protected $deletedAt;

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
     * Method to set the value of field createdAt
     *
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Method to set the value of field updatedAt
     *
     * @param string $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Method to set the value of field deletedAt
     *
     * @param string $deletedAt
     * @return $this
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;

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
     * Returns the value of field createdAt
     *
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Returns the value of field updatedAt
     *
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Returns the value of field deletedAt
     *
     * @return string
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("matchbizz");
        $this->setSource("ageRange");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'ageRange';
    }

    public function validation()
    {
        $validator = new Validation();
        return $this->validate($validator);
    }
}
