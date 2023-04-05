<?php

namespace App\Models;

use Phalcon\Validation;
use Phalcon\Validation\Validator\Uniqueness as UniquenessValidator;

class InfluenceArea extends BaseModel
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
     * @Primary
     * @Identity
     * @Column(column="displayId", type="integer", length=11, nullable=false)
     */
    protected $displayId;

    /**
     *
     * @var double
     * @Column(column="latitude", type="double", length=17, nullable=false)
     */
    protected $latitude;

    /**
     *
     * @var double
     * @Column(column="longitude", type="double", length=17, nullable=false)
     */
    protected $longitude;

    /**
     *
     * @var double
     * @Column(column="longitude", type="double", length=17, nullable=false)
     */
    protected $radius;

    /**
     *
     * @var string
     * @Column(column="businessProfileId", type="string", length=100, nullable=false)
     */
    protected $businessProfileId;

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
     * Method to set the value of field displayId
     *
     * @param integer $displayId
     * @return $this
     */
    public function setDisplayId($displayId)
    {
        $this->displayId = $displayId;

        return $this;
    }

    /**
     * Method to set the value of field latitude
     *
     * @param string $latitude
     * @return $this
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Method to set the value of field radius
     *
     * @param string $latitude
     * @return $this
     */
    public function setRadius($radius)
    {
        $this->radius = $radius;

        return $this;
    }

    /**
     * Method to set the value of field longitude
     *
     * @param string $longitude
     * @return $this
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

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
     * Returns the value of field displayId
     *
     * @return integer
     */
    public function getDisplayId()
    {
        return $this->displayId;
    }

    /**
     * Returns the value of field latitude
     *
     * @return double
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Returns the value of field longitude
     *
     * @return double
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Returns the value of field radius
     *
     * @return double
     */
    public function getRadius()
    {
        return $this->radius;
    }

        /**
     * Returns the value of field businessProfileId
     *
     * @return integer
     */
    public function getBusinessProfileId()
    {
        return $this->businessProfileId;
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
        $this->setSource("influenceArea");
        $this->hasOne('businessProfileId', 'App\\Models\\BusinessProfile', 'id', ['alias' => 'BusinessProfile']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'influenceArea';
    }

    public function validation()
    {
        $validator = new Validation();
        $validator->add(
            [
                "businessProfileId",
                "latitude",
                "longitude",
            ],
            new UniquenessValidator()
        );
        return $this->validate($validator);
    }

}
