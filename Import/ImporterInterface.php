<?php

namespace Kaliber5\ImportBundle\Import;

/**
 * Interface ImporterInterface
 *
 * A generic interface for the importers
 *
 * @package AppBundle\Import
 */
interface ImporterInterface
{

    /**
     * proceeds the import
     */
    public function import();
}
