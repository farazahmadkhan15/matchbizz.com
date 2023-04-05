<?php

namespace App\Models;

class WorkerProfile extends BaseModel
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
     * @Column(column="name", type="string", nullable=false)
     */
    protected $name;

    /**
     *
     * @var integer
     * @Column(column="age", type="integer", length=11, nullable=false)
     */
    protected $age;

    /**
     *
     * @var integer
     * @Column(column="yearsOfExperience", type="integer", length=11, nullable=false)
     */
    protected $yearsOfExperience;

    /**
     *
     * @var integer
     * @Column(column="businessProfileId", type="integer", length=11, nullable=true)
     */
    protected $businessProfileId;

    /**
     *
     * @var integer
     * @Column(column="ethnicityId", type="integer", length=11, nullable=false)
     */
    protected $ethnicityId;

    /**
     *
     * @var integer
     * @Column(column="genderId", type="integer", length=11, nullable=false)
     */
    protected $genderId;

    /**
     *
     * @var integer
     * @Column(column="faithId", type="integer", length=11, nullable=false)
     */
    protected $faithId;

    /**
     *
     * @var integer
     * @Column(column="lifeStyleId", type="integer", length=11, nullable=false)
     */
    protected $lifeStyleId;

    /**
     *
     * @var integer
     * @Column(column="maritalStatusId", type="integer", length=11, nullable=false)
     */
    protected $maritalStatusId;

    /**
     *
     * @var integer
     * @Column(column="educationId", type="integer", length=11, nullable=false)
     */
    protected $educationId;

    /**
     *
     * @var boolean
     * @Column(column="isOwner", type="boolean", nullable=false)
     */
    protected $isOwner;

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

    public function fromObject($attributes)
    {
        $this->setName($attributes->name);
        $this->setAge($attributes->age);
        $this->setYearsOfExperience($attributes->yearsOfExperience);
        $this->setEthnicityId($attributes->ethnicityId);
        $this->setGenderId($attributes->genderId);
        $this->setFaithId($attributes->faithId);
        $this->setLifeStyleId($attributes->lifeStyleId);
        $this->setMaritalStatusId($attributes->maritalStatusId);
        $this->setEducationId($attributes->educationId);
        return $this;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema('matchbizz');
        $this->setSource('workerProfile');
        $this->hasMany('id', WorkerProfileHobbie::class, 'workerProfileId', ['alias' => 'WorkerProfilehobbie']);
        $this->hasMany('id', WorkerProfileLanguage::class, 'workerProfileId', ['alias' => 'WorkerProfilelanguage']);
        $this->hasMany('id', WorkerProfileHobbie::class, 'workerProfileId', [ 'alias' => 'Hobbies' ]);
        $this->hasMany('id', WorkerProfileLanguage::class, 'workerProfileId', ['alias' => 'Languages']);
        $this->belongsTo('businessProfileId', BusinessProfile::class, 'id', ['alias' => 'BusinessProfile', 'foreignKey' => true]);
        $this->belongsTo('ethnicityId', Ethnicity::class, 'id', ['alias' => 'Ethnicity', 'foreignKey' => true]);
        $this->belongsTo('genderId', Gender::class, 'id', ['alias' => 'Gender', 'foreignKey' => true]);
        $this->belongsTo('faithId', Faith::class, 'id', ['alias' => 'Faith', 'foreignKey' => true]);
        $this->belongsTo('lifeStyleId', LifeStyle::class, 'id', ['alias' => 'Lifestyle', 'foreignKey' => true]);
        $this->belongsTo('maritalStatusId', MaritalStatus::class, 'id', ['alias' => 'MaritalStatus', 'foreignKey' => true]);
        $this->belongsTo('educationId', Education::class, 'id', ['alias' => 'Education', 'foreignKey' => true]);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'workerProfile';
    }

    public function getHobbieIds ()
    {
        return array_map(function($hobby){
            return $hobby['hobbieId'];
        }, $this->hobbies->toArray());
    }

    public function getLanguageIds ()
    {
        return array_map(function($language){
            return $language['languageId'];
        }, $this->languages->toArray());
    }

}
