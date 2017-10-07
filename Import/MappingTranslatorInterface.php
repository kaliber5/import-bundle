<?php

namespace Kaliber5\ImportBundle\Import;

/**
 * Interface MappingTranslatorInterface
 *
 * An interface to translate the mapping values
 *
 * @package AppBundle\Import
 */
interface MappingTranslatorInterface
{
    /**
     * @param string $value
     * @param array  $params
     *
     * @return string
     */
    public function translate($value, array $params = array());
}
