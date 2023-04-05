<?php

namespace App\Models;

use Phalcon\Mvc\Model\MetaData;

/**
 * Appends: Add no attribute elements when you call toArray, like relations or getters.
 * Fillables: Attributes you can insert using fill method.
 * Hidden: Remove attributes when you call toArray.
 *
 * By default all the attributes in the model has their own get/set methods.
 */
class BaseModel extends \Phalcon\Mvc\Model
{
    use SoftDeletable;

    protected $__appends;

    protected $__fillables;

    protected $__hidden;

    public function appends($appends)
    {
        $this->__appends = $appends;
        return $this;
    }

    public function fillables($fillables)
    {
        $this->__fillables = $fillables;
        return $this;
    }

    public function hidden($hidden)
    {
        $this->__hidden = $hidden;
        return $this;
    }

    public function toArray($columns = NULL)
    {
        $array = parent::toArray($columns);
        if(!empty($this->__hidden) && is_array($this->__hidden)) {
            $array = array_diff_key($array,array_flip($this->__hidden));
        }
        if(!empty($this->__appends) && is_array($this->__appends)) {
            foreach($this->__appends as $key) {
                $value = $this->$key ?? $this->{'get'.ucfirst($key)}() ?? null;
                if (is_object($value)) $value = $value->toArray() ?? (array) $value;
                if(empty($array[$key]) || isset($value)) $array[$key] = $value;
            }
        }
        return $array;
    }

    public function beforeUpdate()
    {
        $metaData = new MetaData\Memory();
        $attributes = $metaData->getAttributes($this);

        if (in_array('updatedAt', $attributes) && is_null($this->deletedAt)) {
            $this->updatedAt = date('Y-m-d H:i:s');
        }
    }

    public function __call($method, $values) {
        if (($get = substr($method, 0, 3) == 'get') || ($set = substr($method, 0, 3) == 'set')) {
            $property = preg_replace_callback('/^[gs]et(.)/', function($matches){
                return strtolower($matches[1]);
            }, $method);
            if ($get) {
                if (property_exists($this, $property) && (!isset($this->__hidden) || !in_array($property, $this->__hidden))) {
                    return $this->$property;
                }
            }
            if ($set) {
                if (property_exists($this, $property) && (!isset($this->__fillables) || in_array($property, $this->__fillables))) {
                    $this->$property = $values[0];
                    return $this;
                }
            }
        }
        return parent::__call($method, $values);
    }

    public function fill($properties = [])
    {
        if(is_object($properties)) $properties = (array) $properties;
        if(!empty($properties)) {
            if(isset($this->__fillables) && is_array($this->__fillables)) {
                $properties = array_diff_key($properties, array_flip($this->__fillables));
            }
            foreach ($properties as $property => $value) {
                $this->{'set'.ucfirst($property)}(is_object($value) || is_array($value) ? json_encode($value) : $value);
            }
        }

        return $this;
    }
}

trait SoftDeletable
{
    public function beforeDelete()
    {
        $metaData = new MetaData\Memory();
        $attributes = $metaData->getAttributes($this);

        if (in_array('deletedAt', $attributes)) {
            $this->deletedAt = date('Y-m-d H:i:s');
            $this->save();
        }

        return false;
    }

    // source: https://forum.phalconphp.com/discussion/3248/auto-ignore-soft-delete-rows

    /**
     * @inheritdoc
     *
     * @access public
     * @static
     * @param array|string $parameters Query parameters
     * @return Phalcon\Mvc\Model\ResultsetInterface
     */
    public static function find($parameters = null)
    {
        $parameters = self::getSoftDeleteParameters($parameters);

        return parent::find($parameters);
    }

    /**
     * @inheritdoc
     *
     * @access public
     * @static
     * @param array|string $parameters Query parameters
     * @return Phalcon\Mvc\Model
     */
    public static function findFirst($parameters = null)
    {
        $parameters = self::getSoftDeleteParameters($parameters);

        return parent::findFirst($parameters);
    }

    /**
     * @inheritdoc
     *
     * @access public
     * @static
     * @param array|string $parameters Query parameters
     * @return mixed
     */
    public static function count($parameters = null)
    {
        //$parameters = self::getSoftDeleteParameters($parameters);

        return parent::count($parameters);
    }

    /**
     * @access protected
     * @static
     * @param array|string $parameters Query parameters
     * @return mixed
     */
    protected static function getSoftDeleteParameters($parameters = null)
    {
        $deletedField = 'deletedAt';

        if ($parameters === null) {
            $parameters = $deletedField . ' IS NULL';
        } elseif (is_int($parameters)) {
            $parameters = 'id = ' . $parameters . ' AND ' . $deletedField . ' IS NULL';
        } elseif (!is_array($parameters) && strpos($parameters, $deletedField) === false) {
            $parameters .= ' AND ' . $deletedField . ' IS NULL';
        } elseif (is_array($parameters)) {
            if (isset($parameters[0]) && strpos($parameters[0], $deletedField) === false) {
                $parameters[0] .= ' AND ' . $deletedField . ' IS NULL';
            } elseif (isset($parameters['conditions']) && strpos($parameters['conditions'], $deletedField) === false) {
                $parameters['conditions'] .= ' AND ' . $deletedField . ' IS NULL';
            }
        }

        return $parameters;
    }

    public function restore()
    {
        $this->setDeletedAt(null);
        $this->save();
    }

    public static function findWithTrashed($parameters = null)
    {
        return parent::find($parameters);
    }

    public static function findFirstWithTrashed($parameters = null)
    {
        return parent::findFirst($parameters);
    }
}
