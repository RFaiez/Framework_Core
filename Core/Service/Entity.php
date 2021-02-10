<?php

namespace Service;

use ReflectionObject;

class Entity{
    
    public function expose(){
        $normalizedObject = [];
        $object = new ReflectionObject($this);
        foreach ($object->getProperties() as $property) {
            $property->setAccessible(true);
            $normalizedObject[$property->getName()]=$property->getValue($this);
        }
        return $normalizedObject;
    }  

    public function getClassName()
    {
        return static::class;
    }
}