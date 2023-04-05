<?php

namespace App\Models;

use Phalcon\Validation\Validator\Uniqueness as UniquenessValidator;
use Phalcon\Validation;

class PlanFeature extends \Phalcon\Mvc\Model
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
    protected $planId;

    /**
     *
     * @var integer
     */
    protected $featureId;

    /**
     * 
     * @var boolean
     */
    protected $special;

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
     * Method to set the value of field planId
     *
     * @param integer $planId
     * @return $this
     */
    public function setPlanId($planId)
    {
        $this->planId = $planId;

        return $this;
    }

    /**
     * Method to set the value of field featureId
     *
     * @param integer $featureId
     * @return $this
     */
    public function setFeatureId($featureId)
    {
        $this->featureId = $featureId;

        return $this;
    }

    /**
     * Method to set the value of field special
     * 
     * @param boolean $special
     * @return $this
     */
    public function setSpecial($special)
    {
        $this->special = $special;

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
     * Returns the value of field planId
     *
     * @return integer
     */
    public function getPlanId()
    {
        return $this->planId;
    }

    /**
     * Returns the value of field featureId
     *
     * @return integer
     */
    public function getFeatureId()
    {
        return $this->featureId;
    }

    /**
     * Returns the value of field special
     * 
     *  @return boolean
     */
    public function getSpecial()
    {
        return $this->special;
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("matchbizz");
        $this->setSource("planFeature");
        $this->belongsTo('planId', 'App\Models\Plan', 'id', ['alias' => 'Plan']);
        $this->belongsTo('featureId', 'App\Models\Feature', 'id', ['alias' => 'Feature']);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'planFeature';
    }

    public function validation()
    {
        $validator = new Validation();
        $validator->add(
            [
                "planId",
                "featureId",
            ],
            new UniquenessValidator()
        );
        return $this->validate($validator);
    }
}
