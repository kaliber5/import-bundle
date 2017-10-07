<?php

namespace Kaliber5\ImportBundle\Import\Image;

use Gaufrette\Filesystem;
use Gaufrette\StreamWrapper;
use Kaliber5\ImportBundle\Import\ImporterInterface;
use Kaliber5\LoggerBundle\LoggingTrait\LoggingTrait;
use Sonata\CoreBundle\Model\ManagerInterface;
use Sonata\MediaBundle\Model\MediaInterface;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Import images from a gaufrette filesystem as Sonata Media Entities
 *
 * Class Importer
 * @package AppBundle\Import\JewelryImage
 */
class Importer implements ImporterInterface
{
    use LoggingTrait;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var ManagerInterface
     */
    private $mediaManager;

    /**
     * @var string
     */
    private $context;

    /**
     * Importer constructor.
     * @param Filesystem       $filesystem
     * @param ManagerInterface $mediaManager
     * @param string           $context
     */
    public function __construct(Filesystem $filesystem, ManagerInterface $mediaManager, $context)
    {
        $this->filesystem = $filesystem;
        $this->mediaManager = $mediaManager;
        $this->context = $context;
    }

    /**
     * @return Filesystem
     */
    public function getFilesystem()
    {
        return $this->filesystem;
    }

    /**
     * @param Filesystem $filesystem
     */
    public function setFilesystem($filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @return ManagerInterface
     */
    public function getMediaManager()
    {
        return $this->mediaManager;
    }

    /**
     * @param ManagerInterface $mediaManager
     */
    public function setMediaManager($mediaManager)
    {
        $this->mediaManager = $mediaManager;
    }

    /**
     * @return string
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param string $context
     */
    public function setContext($context)
    {
        $this->context = $context;
    }

    /**
     * @inheritdoc
     */
    public function import()
    {
        $this->logDebug('Starting image import');

        $filesystem = $this->getFilesystem();
        $mediaManager = $this->getMediaManager();

        // Setup a file stream to use regular file functions, see https://knplabs.github.io/Gaufrette/streaming.html
        $map = StreamWrapper::getFilesystemMap();
        $map->set('image', $filesystem);
        StreamWrapper::register();

        foreach ($filesystem->keys() as $key) {
            if (!preg_match('/\.(png|jpg|jpeg)$/', $key)) {
                continue;
            }
            $file = new File('gaufrette://image/'.$key);

            $media = $mediaManager->findOneBy(['name' => $key]);
            if (!$media instanceof MediaInterface) {
                $media = $mediaManager->create();

                $this->logDebug('Importing new file: '.$key);
            } else {
                $this->logDebug('Updating existing file: '.$key);
            }

            $media->setBinaryContent($file);
            $media->setContext($this->getContext());
            $media->setProviderName('sonata.media.provider.image');
            $media->setEnabled(true);

            $mediaManager->save($media);
        }

        $this->logDebug('Finish image import');
    }
}
