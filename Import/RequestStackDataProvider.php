<?php

namespace Kaliber5\ImportBundle\Import;

use Kaliber5\LoggerBundle\LoggingTrait\LoggingTrait;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class RequestStackDataProvider
 *
 * Provides the Request-Content as Data
 *
 * @package AppBundle\Import
 */
class RequestStackDataProvider implements ImportDataProviderInterface
{
    use LoggingTrait;

    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * RequestStackDataProvider constructor.
     *
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->requestStack->getCurrentRequest() ? $this->requestStack->getCurrentRequest()->getContent() : null;
    }
}
