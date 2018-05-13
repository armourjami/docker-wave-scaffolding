<?php

namespace Controllers;

use Wave\Controller;

class BaseController extends Controller {


    public function sanitize($payload = null, $property_whitelist = array(), $multidimensional = false){

        $payload = json_decode(json_encode($payload), true);

        if(!$multidimensional){
            $property_whitelist = array_flip($property_whitelist);
        }

        return self::sanitizeArrayItem($payload, $property_whitelist);

    }

    private static function sanitizeArrayItem(array $entity, array $properties_allowed = []){

        $sanitized = array_intersect_key($entity, $properties_allowed);

        foreach($properties_allowed as $property => $sub_properties){
            if(is_array($sub_properties) && isset($sanitized[$property]) && is_array($sanitized[$property])){
                $sanitized[$property] = self::sanitizeArray($sanitized[$property], $sub_properties);
            }
        }

        return $sanitized;

    }

    private static function sanitizeArray(array $entities, array $properties_allowed = []){

        $sanitized = [];
        foreach($entities as $key => $entity){
            if(is_integer($key)){
                $sanitized[] = self::sanitizeArrayItem($entity, $properties_allowed);
            } else if(in_array($key, array_keys($properties_allowed))){
                $sanitized[$key] = $entity;
            }
        }

        return $sanitized;

    }

    public function manualError($field, $title, $message){

        $payload = ['errors' => [$field => ['reason' => $title, 'message' => $message]]];

        return $this->request($payload);
    }

    public function addCustomNewRelicParam($name, $value){
        if(extension_loaded('newrelic')){
            newrelic_add_custom_parameter($name, $value);
        }
    }

}