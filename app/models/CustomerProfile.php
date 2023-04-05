<?php

namespace App\Models;

use Phalcon\Validation\Validator\Uniqueness as UniquenessValidator;
use Phalcon\Validation\Validator\Email as EmailValidator;

use Phalcon\Validation;

class CustomerProfile extends BaseModel
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
     * @Column(column="firstName", type="string", length=50, nullable=false)
     */
    protected $firstName;

    /**
     *
     * @var string
     * @Column(column="lastName", type="string", length=50, nullable=false)
     */
    protected $lastName;

    /**
     *
     * @var string
     * @Column(column="gender", type="string", nullable=false)
     */
    protected $gender;

    /**
     *
     * @var string
     * @Column(column="email", type="string", length=100, nullable=false)
     */
    protected $email;

    /**
     *
     * @var string
     * @Column(column="languageId", type="string", length=50, nullable=false)
     */
    protected $languageId;

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
     * @var string
     * @Column(column="userId", type="integer", length=50, nullable=false)
     */
    protected $userId;

    /**
     *
     * @var string
     * @Column(column="imageId", type="string", length=50, nullable=true)
     */
    protected $imageId;

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
     * Method to set the value of field firstName
     *
     * @param string $firstName
     * @return $this
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Method to set the value of field lastName
     *
     * @param string $lastName
     * @return $this
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Method to set the value of field gender
     *
     * @param string $lastName
     * @return $this
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Method to set the value of field email
     *
     * @param string $lastName
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Method to set the value of field languageId
     *
     * @param string $languageId
     * @return $this
     */
    public function setLanguageId($languageId)
    {
        $this->languageId = $languageId;

        return $this;
    }

    /**
     * Method to set the value of field latitude
     *
     * @param double $latitude
     * @return $this
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Method to set the value of field longitude
     *
     * @param double $longitude
     * @return $this
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Method to set the value of field userId
     *
     * @param double $userId
     * @return $this
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Method to set the value of field userId
     *
     * @param double $userId
     * @return $this
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Method to set the value of field userId
     *
     * @param double $userId
     * @return $this
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Method to set the value of field imageId
     *
     * @param string $userId
     * @return $this
     */
    public function setImageId($imageId)
    {
        $this->imageId = $imageId;

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
     * Returns the value of field firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Returns the value of field lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Returns the value of field gender
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Returns the value of field email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Returns the value of field languageId
     *
     * @return string
     */
    public function getLanguageId()
    {
        return $this->languageId;
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
     * Returns the value of field userId
     *
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Method to set the value of field userId
     *
     * @param double $userId
     * @return $this
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Method to set the value of field userId
     *
     * @param double $userId
     * @return $this
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Returns the value of field imageId
     *
     * @return $string
     */
    public function getImageId()
    {
        return $this->imageId;
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
     * Validations and business logic
     *
     * @return boolean
     */
    public function validation()
    {
        $validator = new Validation();

        $validator->add(
            'email',
            new EmailValidator(
                [
                    'model'   => $this,
                    'message' => 'Please enter a correct email address',
                ]
            )
        );

        return $this->validate($validator);
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("matchbizz");
        $this->setSource("customerProfile");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'customerProfile';
    }
}
