<?php

namespace Core\DAO;

use Core\Utils\UUID;

abstract class DAOBaseUUID extends DAOBase
{
    const UUID = 'uuid';

    public function __construct(array $propertyNames)
    {
        $properties = [static::UUID];
        $properties = array_merge($properties, $propertyNames);
        parent::__construct($properties);
    }

    public function getUUID()
    {
        return $this[static::UUID];
    }

    public function setUUID($uuid)
    {
        $this[static::UUID] = $uuid;
        return $this;
    }

    public function getByUUID($uuid)
    {
        return $this->getByPropId(static::UUID, $uuid);
    }

    public function save($allProperties = true)
    {
        if (!$this->getUUID() && !$this->getId()) {
            $this->setUUID(UUID::get());
        }

        parent::save($allProperties);
    }
}
