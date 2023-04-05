<?php

namespace App\Models;

class Plan extends BaseModel
{

    /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @var string
     */
    protected $name;

    /**
     *
     * @var string
     */
    protected $costCurrencyCode;
    
    /**
     *
     * @var double
     */
    protected $costAmount;

    /**
     *
     * @var integer
     */
    protected $billingCycleFrequency;

    /**
     *
     * @var integer
     */
    protected $billingCycleFrequencyInterval;

    /**
     *
     * @var integer
     */
    protected $billingCycleNumber;

    /**
     *
     * @var string
     */
    protected $createdAt;

    /**
     *
     * @var string
     */
    protected $updatedAt;

    /**
     *
     * @var string
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
     * Method to set the value of field name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Method to set the value of field cost
     *
     * @param string $costCurrencyCode
     * @return $this
     */
    public function setCostCurrencyCode($costCurrencyCode)
    {
        $this->costCurrencyCode = $costCurrencyCode;

        return $this;
    }

    /**
     * Method to set the value of field costAmount
     *
     * @param double $costAmount
     * @return $this
     */
    public function setCostAmount($costAmount)
    {
        $this->costAmount = $costAmount;

        return $this;
    }

    /**
     * Method to set the value of field billingCycleInMonths
     *
     * @param integer $billingCycleInMonths
     * @return $this
     */
    public function setBillingCycleFrequency($billingCycleFrequency)
    {
        $this->billingCycleFrequency = $billingCycleFrequency;

        return $this;
    }

    /**
     * Method to set the value of field billingCycleInMonths
     *
     * @param integer $billingCycleInMonths
     * @return $this
     */
    public function setBillingCycleFrequencyInterval($billingCycleFrequencyInterval)
    {
        $this->billingCycleFrequencyInterval = $billingCycleFrequencyInterval;

        return $this;
    }

    /**
     * Method to set the value of field billingCycleNumber
     *
     * @param integer $billingCycleNumber
     * @return $this
     */
    public function setBillingCycleNumber($billingCycleNumber)
    {
        $this->billingCycleNumber = $billingCycleNumber;

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
     * Returns the value of field name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the value of field costCurrencyCode
     *
     * @return string
     */
    public function getCostCurrencyCode()
    {
        return $this->costCurrencyCode;
    }

    /**
     * Returns the value of field costAmount
     *
     * @return double
     */
    public function getCostAmount()
    {
        return $this->costAmount;
    }

    /**
     * Returns the value of field billingCycleFrequency
     *
     * @return string
     */
    public function getBillingCycleFrequency()
    {
        return $this->billingCycleFrequency;
    }

    /**
     * Returns the value of field billingCycleFrequencyInterval
     *
     * @return integer
     */
    public function getBillingCycleFrequencyInterval()
    {
        return $this->billingCycleFrequencyInterval;
    }

    /**
     * Returns the value of field billingCycleNumber
     *
     * @return integer
     */
    public function getBillingCycleNumber()
    {
        return $this->billingCycleNumber;
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
        $this->setSource("plan");
        $this->hasMany('id', 'App\Models\Planfeature', 'planId', ['alias' => 'Feature']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'plan';
    }

    public function getFeatureIds ()
    {
        return array_map(function($feature){
            return $feature['featureId'];
        }, $this->feature->toArray());
    }

}
