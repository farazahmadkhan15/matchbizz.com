<?php

namespace App\Models;
use Phalcon\Validation\Validator\Uniqueness as UniquenessValidator;
use Phalcon\Validation;

class WorkerProfileHobbie extends \Phalcon\Mvc\Model
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
     * @Column(column="workerProfileId", type="integer", length=11, nullable=false)
     */
    protected $workerProfileId;

    /**
     *
     * @var integer
     * @Column(column="hobbieId", type="integer", length=11, nullable=false)
     */
    protected $hobbieId;

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
     * Method to set the value of field workerProfileId
     *
     * @param integer $workerProfileId
     * @return $this
     */
    public function setWorkerProfileId($workerProfileId)
    {
        $this->workerProfileId = $workerProfileId;

        return $this;
    }

    /**
     * Method to set the value of field hobbieId
     *
     * @param integer $hobbieId
     * @return $this
     */
    public function setHobbieId($hobbieId)
    {
        $this->hobbieId = $hobbieId;

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
     * Returns the value of field workerProfileId
     *
     * @return integer
     */
    public function getWorkerProfileId()
    {
        return $this->workerProfileId;
    }

    /**
     * Returns the value of field hobbieId
     *
     * @return integer
     */
    public function getHobbieId()
    {
        return $this->hobbieId;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("matchbizz");
        $this->setSource("workerProfileHobbie");
        $this->belongsTo('workerProfileId', '\WorkerProfile', 'id', ['alias' => 'WorkerProfile']);
        $this->belongsTo('hobbieId', '\Hobbie', 'id', ['alias' => 'Hobbie']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'workerProfileHobbie';
    }

    public function validation()
    {
        $validator = new Validation();
        $validator->add(
            [
                "workerProfileId",
                "hobbieId",
            ],
            new UniquenessValidator()
        );
        return $this->validate($validator);
    }
}
