<?php

namespace Kaliber5\ImportBundle\Import;

use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class DomainMappingTranslator
 * Translate values from a given domain using the symfony translator
 *
 * @package AppBundle\Import
 */
class DomainMappingTranslator implements MappingTranslatorInterface
{

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var string
     */
    protected $domain;

    /**
     * @var string
     */
    protected $locale;

    /**
     * DomainMappingTranslator constructor.
     *
     * @param TranslatorInterface $translator
     * @param string              $domain
     * @param string              $locale
     */
    public function __construct(TranslatorInterface $translator, $domain, $locale = 'de_DE')
    {
        $this->translator = $translator;
        $this->domain = $domain;
        $this->locale = $locale;
    }

    /**
     * {@inheritdoc}
     */
    public function translate($value, array $params = [])
    {
        return $this->translator->trans($value, $params, $this->domain, $this->locale);
    }
}
