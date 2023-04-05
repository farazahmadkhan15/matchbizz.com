<?php

namespace App\Models;

use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;

class BusinessProfile extends BaseModel
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
     * @Column(column="type", type="string", nullable=false)
     */
    protected $type;

    /**
     *
     * @var string
     * @Column(column="address", type="string", length=100, nullable=false)
     */
    protected $address;

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
     * @Column(column="email", type="string", length=100, nullable=false)
     */
    protected $email;

    /**
     *
     * @var string
     * @Column(column="phone", type="string", length=20, nullable=false)
     */
    protected $phone;

    /**
     *
     * @var string
     * @Column(column="description", type="string", length=100, nullable=false)
     */
    protected $description;

    /**
     *
     * @var integer
     * @Column(column="license", type="string", length=30, nullable=false)
     */
    protected $license;

    /**
     *
     * @var integer
     * @Column(column="insurance", type="string", length=30, nullable=true)
     */
    protected $insurance;

    /**
     *
     * @var double
     * @Column(column="rating", type="double", length=10, nullable=false)
     */
    protected $rating;

    /**
     *
     * @var string
     * @Column(column="businessProfileId", type="integer", nullable=false)
     */
    protected $businessProfileId;

    /**
     *
     * @var string
     * @Column(column="userId", type="integer", nullable=false)
     */
    protected $userId;

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(column="id", type="integer", length=11, nullable=false)
     */
    protected $reviewCount;

    /**
     *
     * @var string
     * @Column(column="imageId", type="string", length=50, nullable=true)
     */
    protected $imageId;

    /**
     *
     * @var string
     * @Column(column="website", type="string", length=200, nullable=true)
     */
    protected $website;

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
     * Validations and BusinessProfile logic
     *
     * @return boolean
     */
    public function validation()
    {
        $validator = new Validation();

        $validator->add(
            'email',
            new EmailValidator([
                'model'   => $this,
                'message' => 'Please enter a correct email address',
            ])
        );

        return $this->validate($validator);
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema('matchbizz');
        $this->setSource('businessProfile');
        $this->hasMany('id', Claim::class, 'businessProfileId', ['alias' => 'Claim']);
        $this->hasMany('id', GalleryImage::class, 'businessProfileId', ['alias' => 'GalleryImage']);
        $this->hasMany('id', Message::class, 'businessProfileId', ['alias' => 'Message']);
        $this->hasMany('id', WorkerProfile::class, 'businessProfileId', ['alias' => 'WorkerProfile']);
        $this->hasMany('id', Review::class, 'businessProfileId', ['alias' => 'Review']);
        $this->hasOne('id', Schedule::class, 'id', ['alias' => 'Schedules']);
        $this->hasMany('id', ScheduleSpan::class, 'businessProfileId', ['alias' => 'Schedulespan']);
        $this->hasMany('id', Service::class, 'businessProfileId', ['alias' => 'Service']);
        $this->hasMany('id', Socialnetworkaccount::class, 'businessProfileId', ['alias' => 'Socialnetworkaccount']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'businessProfile';
    }

}