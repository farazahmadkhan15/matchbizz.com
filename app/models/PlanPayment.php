<?php

namespace App\Models;

class PlanPayment extends BaseModel
{

    /**
     *
     * @var integer
     */
    protected $id;

    /**
     *
     * @var integer
     */
    protected $planSubscriptionId;

    /**
     *
     * @var string
     */
    protected $transactionId;

    /**
     *
     * @var string
     */
    protected $status;

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
     * Method to set the value of field planSubscriptionId
     *
     * @param integer $planSubscriptionId
     * @return $this
     */
    public function setPlanSubscriptionId($planSubscriptionId)
    {
        $this->planSubscriptionId = $planSubscriptionId;

        return $this;
    }

    /**
     * Method to set the value of field transactionId
     *
     * @param string $transactionId
     * @return $this
     */
    public function setTransactionIdId($transactionId)
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    /**
     * Method to set the value of field status
     *
     * @param string $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

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
     * Returns the value of field planSubscriptionId
     *
     * @return integer
     */
    public function getPlanSubscriptionId()
    {
        return $this->planSubscriptionId;
    }

    /**
     * Returns the value of field status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     *  Returns the value of field transactionId
     *
     * @return string
     */
    public function getTransactionIdId()
    {
        return $this->transactionId;
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
        $this->setSource("planPayment");
        $this->belongsTo('planSubscriptionId', 'AppModels\PlanSubscription', 'id', ['alias' => 'Plansubscription']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'planPayment';
    }
}
