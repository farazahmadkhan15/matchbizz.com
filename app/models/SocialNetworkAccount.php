<?php

namespace App\Models;
use Phalcon\Validation\Validator\Uniqueness as UniquenessValidator;
use Phalcon\Mvc\Model;
use Phalcon\Validation;

class SocialNetworkAccount extends BaseModel
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
     * @var string
     * @Column(column="urlSegment", type="string", length=100, nullable=false)
     */
    protected $urlSegment;

    /**
     *
     * @var integer
     * @Column(column="socialNetworkId", type="integer", length=11, nullable=false)
     */
    protected $socialNetworkId;

    /**
     *
     * @var integer
     * @Column(column="businessProfileId", type="integer", length=11, nullable=false)
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
     * Method to set the value of field urlSegment
     *
     * @param string $urlSegment
     * @return $this
     */
    public function setUrlSegment($urlSegment)
    {
        $this->urlSegment = $urlSegment;

        return $this;
    }

    /**
     * Method to set the value of field socialNetworkId
     *
     * @param integer $socialNetworkId
     * @return $this
     */
    public function setSocialNetworkId($socialNetworkId)
    {
        $this->socialNetworkId = $socialNetworkId;

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
     * Returns the value of field urlSegment
     *
     * @return string
     */
    public function getUrlSegment()
    {
        return $this->urlSegment;
    }

    /**
     * Returns the value of field socialNetworkId
     *
     * @return integer
     */
    public function getSocialNetworkId()
    {
        return $this->socialNetworkId;
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
        $this->setSource("socialNetworkAccount");
        $this->belongsTo('socialNetworkId', '\SocialNetwork', 'id', ['alias' => 'Socialnetwork']);
        $this->belongsTo('businessProfileId', '\BusinessProfile', 'id', ['alias' => 'BusinessProfile']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'socialNetworkAccount';
    }

    public function validation()
    {
        $validator = new Validation();
        $validator->add(
            [
                "businessProfileId",
                "socialNetworkId",
            ],
            new UniquenessValidator()
        );
        return $this->validate($validator);
    }

}
