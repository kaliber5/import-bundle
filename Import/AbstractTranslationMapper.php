<?php

namespace Kaliber5\ImportBundle\Import;

use Kaliber5\LoggerBundle\LoggingTrait\LoggingTrait;

/**
 * Class AbstractTranslationMapper
 *
 * @package AppBundle\Import
 */
abstract class AbstractTranslationMapper extends AbstractMapper
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
     * @var MappingTranslatorInterface
     */
    protected $translator;

    /**
     * AbstractTranslationMapper constructor.
     *
     * @param MappingTranslatorInterface $translator
     */
    public function __construct(MappingTranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * returns the translated value
     *
     * @param string $value
     *
     * @return string
     */
    protected function translate($value)
    {
        return $this->translator->translate($value);
    }
}
