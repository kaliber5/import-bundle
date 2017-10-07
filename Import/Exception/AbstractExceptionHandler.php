<?php

namespace Kaliber5\ImportBundle\Import\Exception;

use Kaliber5\LoggerBundle\LoggingTrait\LoggingTrait;

/**
 * Class AbstractExceptionHandler
 *
 * @package AppBundle\Import\Exception
 */
abstract class AbstractExceptionHandler implements ExceptionHandlerInterface
{
    use LoggingTrait;

    /**
     * @var ImportException[]
     */
    protected $exceptions = [];

    /**
     * {@inheritdoc}
     */
    public function addException(ImportException $exception)
    {
        $this->exceptions[] = $exception;
    }

    /**
     * {@inheritdoc}
     */
    public function hasExceptions()
    {
        return (count($this->exceptions) > 0);
    }

    /**
     * {@inheritdoc}
     */
    public function getExceptions()
    {
        return $this->exceptions;
    }

    /**
     * {@inheritdoc}
     */
    abstract public function handleExceptions();
}
