<?php

namespace Kaliber5\ImportBundle\Import\Exception;

/**
 * Class LoggerExceptionHandler
 *
 * @package AppBundle\Import\Exception
 */
class LoggerExceptionHandler extends AbstractExceptionHandler implements ExceptionHandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public function handleExceptions()
    {
        foreach ($this->exceptions as $exception) {
            $this->logError($exception->getVerboseMessage());
            $this->logDebug($exception->getVerboseMessage(), $exception);
            if ($exception->getPrevious()) {
                $this->logDebug($exception->getPrevious()->getMessage(), $exception->getPrevious());
            }
        }
    }
}
