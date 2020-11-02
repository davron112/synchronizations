<?php

namespace Davron112\Synchronizations\Mappers;

abstract class BaseMapper
{
    /**
     * Map data.
     *
     * @param array $data data from backbone
     */
    abstract public function map(array $data);
}
