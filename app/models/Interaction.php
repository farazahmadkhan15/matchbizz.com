<?php

namespace App\Models;

class Interaction extends BaseModel
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
     * @Column(column="interactionTypeId", type="integer", length=11, nullable=false)
     */
    protected $interactionTypeId;

    /**
     *
     * @var integer
     * @Column(column="userId", type="integer", length=11, nullable=false)
     */
    protected $userId;

    /**
     *
     * @var string
     * @Column(column="createdAt", type="string", nullable=false)
     */
    protected $createdAt;

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
     * Method to set the value of field interactionTypeId
     *
     * @param integer $interactionTypeId
     * @return $this
     */
    public function setInteractionTypeId($interactionTypeId)
    {
        $this->interactionTypeId = $interactionTypeId;

        return $this;
    }

    /**
     * Method to set the value of field userId
     *
     * @param integer $userId
     * @return $this
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

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
     * Returns the value of field id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field interactionTypeId
     *
     * @return integer
     */
    public function getInteractionTypeId()
    {
        return $this->interactionTypeId;
    }

    /**
     * Returns the value of field userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("matchbizz");
        $this->setSource("interaction");
        $this->belongsTo('interactionTypeId', '\InteractionType', 'id', ['alias' => 'Interactiontype']);
        $this->belongsTo('userId', '\User', 'id', ['alias' => 'User']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'interaction';
    }

}
