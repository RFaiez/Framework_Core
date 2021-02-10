<?php

namespace rfaiez\framework_core\Service;

use ReflectionObject;

class Entity
{
    /**
     * Get array data from entity properties values.
     *
     * @return array
     */
    public function expose(): array
    {
        $normalizedObject = [];
        $object = new ReflectionObject($this);
        foreach ($object->getProperties() as $property) {
            $property->setAccessible(true);
            $normalizedObject[$property->getName()] = $property->getValue($this);
        }

        return $normalizedObject;
    }

    /**
     * Get current class name.
     *
     * @return string
     */
    public function getClassName(): string
    {
        return static::class;
    }
}
