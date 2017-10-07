<?php

namespace Kaliber5\ImportBundle\Import;

/**
 * Interface ImportDataProviderInterface
 *
 * Interface for classes which provide data to import
 *
 * @package AppBundle\Import
 */
interface ImportDataProviderInterface
{
    /**
     * returns the data to import
     *
     * @return string
     */
    public function getData();
}
