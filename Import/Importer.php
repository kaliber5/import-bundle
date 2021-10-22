<?php

namespace Kaliber5\ImportBundle\Import;

use Kaliber5\ImportBundle\Import\Exception\ExceptionHandlerInterface;
use Kaliber5\ImportBundle\Import\Exception\ImportException;
use Kaliber5\LoggerBundle\LoggingTrait\LoggingTrait;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use InvalidArgumentException;

/**
 * Class Importer
 *
 * A generic importer class
 *
 * @package AppBundle\Import
 */
class Importer implements ImporterInterface
{

    use LoggingTrait;

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * @var ImportObjectsProviderInterface
     */
    protected $importObjectsProvider;

    /**
     * @var MapperInterface
     */
    protected $mapper;

    /**
     * @var DestinationObjectProviderInterface
     */
    protected $destinationObjectProvider;

    /**
     * @var ExceptionHandlerInterface
     */
    protected $exceptionHandler;

    /**
     * The valid imported objects
     *
     * @var array
     */
    protected $importedObjects = [];

    /**
     * Importer constructor.
     *
     * @param ValidatorInterface                 $validator
     * @param ImportObjectsProviderInterface     $importObjectsProvider
     * @param MapperInterface                    $mapper
     * @param DestinationObjectProviderInterface $destinationObjectProvider
     * @param ExceptionHandlerInterface          $exceptionHandler
     */
    public function __construct(
        ValidatorInterface $validator,
        ImportObjectsProviderInterface $importObjectsProvider,
        MapperInterface $mapper,
        DestinationObjectProviderInterface $destinationObjectProvider,
        ExceptionHandlerInterface $exceptionHandler
    ) {
        $this->validator = $validator;
        $this->importObjectsProvider = $importObjectsProvider;
        $this->mapper = $mapper;
        $this->destinationObjectProvider = $destinationObjectProvider;
        $this->exceptionHandler = $exceptionHandler;
    }

    /**
     * {@inheritdoc}
     */
    public function import()
    {
        try {
            $this->prepareImport();
            $objects = $this->getSourceObjects();
            foreach ($objects as $sourceObject) {
                $destinationObject = null;
                $errors = null;
                try {
                    $this->assertValidObject($sourceObject, 'Validierungsfehler im importierten Objekt');
                    $destinationObject = $this->getDestinationObject($sourceObject);
                    $this->mapObjects($sourceObject, $destinationObject);
                    $this->assertValidObject($destinationObject, 'Validierungsfehler im abgebildeten Objekt');
                    $this->addImportedObject($destinationObject);
                } catch (ImportException $ie) {
                    $this->addException($ie);
                } catch (\Exception $e) {
                    $this->addException(new ImportException($e->getMessage(), $sourceObject, $destinationObject, $errors, 0, $e));
                }
            }
            $this->finishImport();
        } catch (ImportException $ie) {
            $this->addException($ie);
        } catch (\Exception $e) {
            $this->addException(new ImportException($e->getMessage(), null, null, null, 0, $e));
        }
        try {
            $this->handleExceptions();
        } catch (\Exception $e) {
            $this->logError('Error on HandleExceptions', $e);
        }
    }

    /**
     * returns an array with the imported objects
     *
     * @return array
     */
    public function getImportedObjects()
    {
        return $this->importedObjects;
    }

    /**
     * returns true if the import has Exceptions
     *
     * @return boolean
     */
    public function hasExceptions()
    {
        return $this->exceptionHandler->hasExceptions();
    }

    /**
     * returns the exceptions
     *
     * @return ImportException[]
     */
    public function getExceptions()
    {
        return $this->exceptionHandler->getExceptions();
    }

    /**
     * do some work to prepare the import
     */
    protected function prepareImport()
    {
    }

    /**
     * do some work to finish the import
     */
    protected function finishImport()
    {
    }

    /**
     * adds the imported object
     *
     * @param $destinationObject
     */
    protected function addImportedObject($destinationObject)
    {
        $this->importedObjects[] = $destinationObject;
    }

    /**
     * handles the exceptions
     */
    protected function handleExceptions()
    {
        $this->exceptionHandler->handleExceptions();
    }

    /**
     * adds an exception to the handler
     *
     * @param ImportException $e
     */
    protected function addException(ImportException $e)
    {
        $this->exceptionHandler->addException($e);
    }

    /**
     * proceeds the mapping
     *
     * @param $sourceObject
     * @param $destinationObject
     */
    protected function mapObjects($sourceObject, $destinationObject)
    {
        $this->mapper->setSource($sourceObject);
        $this->mapper->setDestination($destinationObject);
        $this->mapper->map();
    }

    /**
     * @param $sourceObject
     *
     * @return mixed
     */
    protected function getDestinationObject($sourceObject)
    {
        return $this->destinationObjectProvider->getOrCreateBySource($sourceObject);
    }

    /**
     * @return object[]
     */
    protected function getSourceObjects()
    {
        try {
            return $this->importObjectsProvider->getObjects();
        } catch (\InvalidArgumentException $e) {
            $this->addException(new ImportException('Error on retrieving objects from provider: '.$e->getMessage(), null, null, null, 0, $e));
            $this->logInfo('Got no Objects', $e);

            return [];
        }
    }

    /**
     * @param $object
     *
     * @return \Symfony\Component\Validator\ConstraintViolationListInterface
     */
    protected function validateObject($object)
    {
        return $this->validator->validate($object);
    }

    /**
     * @param $object
     *
     * @throws InvalidArgumentException
     */
    protected function assertValidObject($object, $message = 'Validierungsfehler') {
        $errors = $this->validateObject($object);
        if (count($errors) > 0) {
            if (method_exists($object, '__toString')) {
                $message .= ': ' . $object;
            } else {
                $message .= ': ' . get_class($object);
            }
            $message .= PHP_EOL.(string) $errors;

            throw new InvalidArgumentException($message);
        }
    }
}
