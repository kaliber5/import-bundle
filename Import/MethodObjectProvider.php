<?php

namespace Kaliber5\ImportBundle\Import;

/**
 * Class MethodObjectProvider
 *
 * This class calls a method on a object and returns the result
 * Usefull if the given DataObjectsProvider returns an object and the target entities must be
 * retrieved by a method call
 *
 * @package AppBundle\Import
 */
class MethodObjectProvider implements ImportObjectsProviderInterface
{
    /**
     * @var ImportObjectsProviderInterface
     */
    protected $importObjectsProvider;

    /**
     * @var string
     */
    protected $method;

    /**
     * MethodObjectProvider constructor.
     *
     * @param ImportObjectsProviderInterface $importObjectsProvider
     * @param string                         $method
     */
    public function __construct(ImportObjectsProviderInterface $importObjectsProvider, $method)
    {
        $this->importObjectsProvider = $importObjectsProvider;
        $this->method = $method;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException
     */
    public function getObjects()
    {
        $object = $this->importObjectsProvider->getObjects();
        if (is_object($object) && method_exists($object, $this->method)) {
            return call_user_func([$object, $this->method]);
        }

        return $object;
    }
}
