<?php

namespace Kaliber5\ImportBundle\Import\Exception;

/**
 * Class ExceptionHandlerContainer
 *
 * A container to collect multiple exception handlers
 *
 * @package AppBundle\Import\Exception
 */
class ExceptionHandlerContainer extends AbstractExceptionHandler implements ExceptionHandlerInterface
{
    /**
     * @var ExceptionHandlerInterface[]
     */
    protected $exceptionHandlers = [];

    /**
     * ExceptionHandlerContainer constructor.
     *
     * @param ExceptionHandlerInterface[] $exceptionHandlers
     */
    public function __construct(array $exceptionHandlers)
    {
        foreach ($exceptionHandlers as $exceptionHandler) {
            $this->addExceptionHandler($exceptionHandler);
        }
    }

    /**
     * Adds an exceptionhandler
     *
     * @param ExceptionHandlerInterface $exceptionHandler
     */
    public function addExceptionHandler(ExceptionHandlerInterface $exceptionHandler)
    {
        $this->exceptionHandlers[] = $exceptionHandler;
    }

    /**
     * {@inheritdoc}
     */
    public function addException(ImportException $exception)
    {
        parent::addException($exception);

        foreach ($this->exceptionHandlers as $exceptionHandler) {
            $exceptionHandler->addException($exception);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function handleExceptions()
    {
        foreach ($this->exceptionHandlers as $exceptionHandler) {
            $exceptionHandler->handleExceptions();
        }
    }
}
