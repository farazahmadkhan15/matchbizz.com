<?php

namespace App\Models;

class Image extends BaseModel
{

    /**
     *
     * @var string
     * @Primary
     * @Identity
     * @Column(column="name", type="string", length=36, nullable=false)
     */
    protected $name;

    /**
     *
     * @var string
     * @Column(column="used", type="boolean", nullable=false)
     */
    protected $used;

    /**
     *
     * @var string
     * @Column(column="extension", type="string", length=11, nullable=false)
     */
    protected $extension;

    /**
     *
     * @var string
     * @Column(column="address", type="string", length=11, nullable=false)
     */
    protected $address;

        /**
     *
     * @var string
     * @Column(column="addressThumbnail", type="string", length=11, nullable=false)
     */
    protected $addressThumbnail;

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
     * Method to set the value of field name
     *
     * @param integer $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Method to set the value of field used
     *
     * @param string $used
     * @return $this
     */
    public function setUsed($used)
    {
        $this->used = $used;

        return $this;
    }

    /**
     * Method to set the value of field extension
     *
     * @param string $extension
     * @return $this
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Method to set the value of field address
     *
     * @param integer $address
     * @return $this
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Method to set the value of field addressThumbnail
     *
     * @param integer $addressThumbnail
     * @return $this
     */
    public function setAddressThumbnail($addressThumbnail)
    {
        $this->addressThumbnail = $addressThumbnail;

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
     * Returns the value of field name
     *
     * @return integer
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the value of field used
     *
     * @return string
     */
    public function getUsed()
    {
        return $this->used;
    }

    /**
     * Returns the value of field extension
     *
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Returns the value of field address
     *
     * @return integer
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Returns the value of field addressThumbnail
     *
     * @return integer
     */
    public function getAddressThumbnail()
    {
        return $this->addressThumbnail;
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
        $this->setSource("image");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'image';
    }

}
