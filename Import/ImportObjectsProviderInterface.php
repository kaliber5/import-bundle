<?php

namespace Kaliber5\ImportBundle\Import;

/**
 * Interface ImportObjectsProviderInterface
 *
 * @package AppBundle\Import
 */
interface ImportObjectsProviderInterface
{

    /**
     * returns an object from import
     *
     * @return mixed
     */
    public function getObjects();
}
