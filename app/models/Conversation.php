<?php

namespace App\Models;

class Conversation extends BaseModel
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
     * @Column(column="topic", type="string", length=100, nullable=false)
     */
    protected $topic;

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
     * Method to set the value of field customerProfileId
     *
     * @param integer $customerProfileId
     * @return $this
     */
    public function setCustomerProfileId($customerProfileId)
    {
        $this->customerProfileId = $customerProfileId;

        return $this;
    }

    /**
     * Method to set the value of field topic
     *
     * @param integer $topic
     * @return $this
     */
    public function setTopic($topic)
    {
        $this->topic = $topic;

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
     * Returns the value of field businessProfileId
     *
     * @return integer
     */
    public function getBusinessProfileId()
    {
        return $this->businessProfileId;
    }

    /**
     * Returns the value of field businessProfileId
     *
     * @return integer
     */
    public function getCustomerProfileId()
    {
        return $this->customerProfileId;
    }

    /**
     * Returns the value of field topic
     *
     * @return string
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("matchbizz");
        $this->setSource("conversation");
        $this->hasMany('id', 'Message', 'conversationId', ['alias' => 'Message']);
        $this->belongsTo('businessProfileId', '\BusinessProfile', 'id', ['alias' => 'BusinessProfile']);
        $this->belongsTo('customerProfileId', '\CustomerProfile', 'id', ['alias' => 'Customerprofile']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'conversation';
    }
}
