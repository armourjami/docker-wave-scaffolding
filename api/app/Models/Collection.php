<?php

namespace Models;

use Wave\Log;

/**
 * Provide a proxy for accessing objects based on a unique property.
 *
 * Class ModelCollection
 * @package Helpers
 */
class Collection implements \Iterator {

    private $collection;


    public function __construct(array $models, $property){

        $this->collection = array();
        foreach($models as &$model){
            if(is_object($model) && isset($model->$property)){
                $this->collection[$model->$property] = &$model;
            }
            else {
                Log::write('models', "Property $property not set on " . get_class($model), Log::ERROR);
            }
        }
    }

    public function getCollectionKeys(){
        return array_keys($this->collection);
    }

    public function __isset($property){
        return isset($this->collection[$property]);
    }
    public function __get($property){
        if(isset($this->collection[$property]))
            return $this->collection[$property];
        return null;
    }


    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current() {
        return current($this->collection);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next() {
        next($this->collection);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key() {
        return key($this->collection);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     *       Returns true on success or false on failure.
     */
    public function valid() {
        return isset($this->collection[key($this->collection)]);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind() {
        reset($this->collection);
    }
}