<?php

namespace Kaliber5\ImportBundle\Import;

use Symfony\Component\Serializer\SerializerInterface;
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
     * @var SerializerInterface
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
     * @var array
     */
    protected $context;

    /**
     * DeserializedImportObjectProvider constructor.
     *
     * @param ImportDataProviderInterface $importDataProvider
     * @param SerializerInterface         $serializer
     * @param string                      $dataType
     * @param string                      $dataFormat
     * @param array                       $context
     */
    public function __construct(
        ImportDataProviderInterface $importDataProvider,
        SerializerInterface $serializer,
        string $dataType,
        string $dataFormat = 'xml',
        array $context = []
    ) {
        $this->importDataProvider = $importDataProvider;
        $this->serializer = $serializer;
        $this->dataType = $dataType;
        $this->dataFormat = $dataFormat;
        $this->context = $context;
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
