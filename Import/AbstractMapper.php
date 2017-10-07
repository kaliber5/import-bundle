<?php

namespace Kaliber5\ImportBundle\Import;

use Kaliber5\LoggerBundle\LoggingTrait\LoggingTrait;
use Webmozart\Assert\Assert;

/**
 * Class AbstractMapper
 *
 * @package AppBundle\Import
 */
abstract class AbstractMapper implements MapperInterface
{
    use LoggingTrait;

    /**
     * @var object
     */
    protected $sourceObject;

    /**
     * @var object
     */
    protected $destinationObject;

    /**
     * {@inheritdoc}
     */
    public function setSource($source)
    {
        $this->sourceObject = $source;
    }

    /**
     * {@inheritdoc}
     */
    public function setDestination($destination)
    {
        $this->destinationObject = $destination;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException
     */
    public function map()
    {
        Assert::notNull($this->sourceObject, 'No source was set');
        Assert::notNull($this->destinationObject, 'No destination was set');
        $this->mapObjects();
    }

    /**
     * Do here the mapping
     */
    abstract protected function mapObjects();
}
