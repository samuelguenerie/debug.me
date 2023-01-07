<?php

namespace Plugo\Services\Mapper;

class Mapper
{
    /**
     * @param array $array
     * @param string $class
     * @return mixed
     */
    public function arrayToObject(array $array, string $class): mixed
    {
        return unserialize(sprintf(
            'O:%d:"%s"%s',
            strlen($class),
            $class,
            strstr(serialize($array), ':')
        ));
    }
}
