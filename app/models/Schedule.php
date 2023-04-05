<?php

namespace App\Models;

use Phalcon\Validation\Validator\Uniqueness as UniquenessValidator;
use Phalcon\Validation;

class Schedule extends BaseModel
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
     * @Column(column="type", type="string", nullable=false)
     */
    protected $type;

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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema('matchbizz');
        $this->setSource('schedule');
        $this->belongsTo('id', BusinessProfile::class, 'id', ['alias' => 'BusinessProfile']);
        $this->hasMany('id', ScheduleSpan::class, 'scheduleId', ['alias' => 'Intervals']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'schedule';
    }

    public function validation()
    {
        $validator = new Validation();
        $validator->add(
            [
                'type',
                'id',
            ],
            new UniquenessValidator()
        );
        return $this->validate($validator);
    }
}
