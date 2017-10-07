<?php

namespace Kaliber5\ImportBundle\Import;

/**
 * Interface MapperInterface
 *
 * A Generic Interface for mappers
 *
 * @package AppBundle\Import
 */
interface MapperInterface
{
    /**
     * Sets the source object
     *
     * @param mixed $source
     */
    public function setSource($source);

    /**
     * Sets the destination object
     *
     * @param mixed $destination
     */
    public function setDestination($destination);

    /**
     * map the values from the source to the destination object
     */
    public function map();
}
