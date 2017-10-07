<?php

namespace Kaliber5\ImportBundle\Import;

use JMS\Serializer\Serializer;
use Webmozart\Assert\Assert;

/**
 * Class DeserializedImportObjectProvider
 *
 * This class provides objects from serializer
 *
 * @package AppBundle\Import
 */
class DeserializedImportObjectProvider implements ImportObjectsProviderInterface
{
    /**
     * @var ImportDataProviderInterface
     */
    protected $importDataProvider;

    /**
     * @var Serializer
     */
    protected $serializer;

    /**
     * @var string
     */
    protected $dataType;

    /**
     * @var string
     */
    protected $dataFormat;

    /**
     * DeserializedImportObjectProvider constructor.
     *
     * @param ImportDataProviderInterface $importDataProvider
     * @param Serializer                  $serializer
     * @param string                      $dataType
     * @param string                      $dataFormat
     */
    public function __construct(
        ImportDataProviderInterface $importDataProvider,
        Serializer $serializer,
        $dataType,
        $dataFormat = 'xml'
    ) {
        $this->importDataProvider = $importDataProvider;
        $this->serializer = $serializer;
        $this->dataType = $dataType;
        $this->dataFormat = $dataFormat;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException
     */
    public function getObjects()
    {
        $data = $this->importDataProvider->getData();
        Assert::notNull($data, 'Got null from data provider');
        Assert::notEmpty($data, 'Got empty data from data provider');

        return $this->serializer->deserialize($data, $this->dataType, $this->dataFormat);
    }
}
