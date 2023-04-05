<?php

namespace App\Models;
use Phalcon\Validation\Validator\Uniqueness as UniquenessValidator;
use Phalcon\Validation;

class WorkerProfileLanguage extends \Phalcon\Mvc\Model
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
     * @Column(column="languageId", type="integer", length=11, nullable=false)
     */
    protected $languageId;

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
     * Method to set the value of field languageId
     *
     * @param integer $languageId
     * @return $this
     */
    public function setLanguageId($languageId)
    {
        $this->languageId = $languageId;

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
     * Returns the value of field languageId
     *
     * @return integer
     */
    public function getLanguageId()
    {
        return $this->languageId;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("matchbizz");
        $this->setSource("workerProfileLanguage");
        $this->belongsTo('workerProfileId', '\WorkerProfile', 'id', ['alias' => 'WorkerProfile']);
        $this->belongsTo('languageId', '\Language', 'id', ['alias' => 'Language']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'workerProfileLanguage';
    }

    public function validation()
    {
        $validator = new Validation();
        $validator->add(
            [
                "workerProfileId",
                "languageId",
            ],
            new UniquenessValidator()
        );
        return $this->validate($validator);
    }
}
