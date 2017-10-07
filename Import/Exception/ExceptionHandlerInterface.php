<?php

namespace Kaliber5\ImportBundle\Import\Exception;

/**
 * Interface ExceptionHandlerInterface
 *
 * @package AppBundle\Import\Exception
 */
interface ExceptionHandlerInterface
{
    /**
     * add an exception to the handler
     *
     * @param ImportException $exception
     */
    public function addException(ImportException $exception);

    /**
     * handles the exceptions
     */
    public function handleExceptions();

    /**
     * returns true if one or more Exceptions was added
     *
     * @return boolean
     */
    public function hasExceptions();

    /**
     * returns the added Exceptions
     *
     * @return ImportException[]
     */
    public function getExceptions();
}
