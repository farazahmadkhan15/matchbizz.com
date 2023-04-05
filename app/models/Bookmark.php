<?php

namespace App\Models;

use Phalcon\Validation\Validator\Uniqueness as UniquenessValidator;
use Phalcon\Validation;

class Bookmark extends \Phalcon\Mvc\Model
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
     * @var integer
     * @Column(column="businessProfileId", type="integer", length=11, nullable=false)
     */
    protected $businessProfileId;

    /**
     *
     * @var integer
     * @Column(column="customerProfileId", type="integer", length=11, nullable=false)
     */
    protected $customerProfileId;

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
     * Method to set the value of field businessProfileId
     *
     * @param integer $businessProfileId
     * @return $this
     */
    public function setBusinessProfileId($businessProfileId)
    {
        $this->businessProfileId = $businessProfileId;

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
     * Method to set the value of field CustomerProfileId
     *
     * @param integer $CustomerProfileId
     * @return $this
     */
    public function setCustomerProfileId($customerProfileId)
    {
        $this->customerProfileId = $customerProfileId;

        return $this;
    }

    /**
     * Returns the value of field BusinessProfileId
     *
     * @return integer
     */
    public function getBusinessProfileId()
    {
        return $this->businessProfileId;
    }

    /**
     * Returns the value of field CustomerProfileId
     *
     * @return integer
     */
    public function getCustomerProfileId()
    {
        return $this->customerProfileId;
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
        $this->setSource("bookmark");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'bookmark';
    }

    public function validation()
    {
        $validator = new Validation();
        $validator->add(
            [
                "customerProfileId",
                "businessProfileId",
            ],
            new UniquenessValidator()
        );
        return $this->validate($validator);
    }
}
