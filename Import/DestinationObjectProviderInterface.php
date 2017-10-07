<?php

namespace Kaliber5\ImportBundle\Import;

/**
 * Interface DestinationObjectProviderInterface
 *
 * An interface for Mapping Destination Object Providers
 *
 * @package AppBundle\Import
 */
interface DestinationObjectProviderInterface
{

    /**
     * Returns the destination object
     *
     * @param mixed $source
     *
     * @return mixed
     */
    public function getOrCreateBySource($source);
}
