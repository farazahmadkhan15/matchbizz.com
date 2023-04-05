<?php

namespace App\Models;

class Category extends BaseModel
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
     * @Column(column="name", type="string", length=100, nullable=false)
     */
    protected $name;

    /**
     *
     * @var string
     * @Column(column="description", type="string", nullable=false)
     */
    protected $description;

    /**
     *
     * @var integer
     * @Column(column="parentCategoryId", type="integer", length=11, nullable=true)
     */
    protected $parentCategoryId;

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(column="iconId", type="integer", length=11, nullable=false)
     */
    protected $iconId;

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
     * Method to set the value of field description
     *
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Method to set the value of field parentCategoryId
     *
     * @param integer $parentCategoryId
     * @return $this
     */
    public function setParentCategoryId($parentCategoryId)
    {
        $this->parentCategoryId = $parentCategoryId;

        return $this;
    }

    /**
     * Method to set the value of field iconId
     *
     * @param integer $iconId
     * @return $this
     */
    public function setIconId($iconId)
    {
        $this->iconId = $iconId;

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
     * Returns the value of field description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Returns the value of field parentCategoryId
     *
     * @return integer
     */
    public function getParentCategoryId()
    {
        return $this->parentCategoryId;
    }

    /**
     * Returns the value of iconId
     *
     * @return integer
     */
    public function getIconId()
    {
        return $this->iconId;
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
        $this->setSource("category");
        $this->hasMany('id', 'Service', 'typeId', ['alias' => 'Service']);
        $this->hasMany('id', 'category', 'parentCategoryId', ['alias' => 'category']);
        $this->belongsTo('parentCategoryId', '\Category', 'id', ['alias' => 'category']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'category';
    }

}
