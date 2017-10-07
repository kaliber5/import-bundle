<?php

namespace Kaliber5\ImportBundle\Import;

use Gaufrette\Filesystem;
use Kaliber5\LoggerBundle\LoggingTrait\LoggingTrait;
use Knp\Bundle\GaufretteBundle\FilesystemMap;

/**
 * Class GaufretteDataProvider
 *
 * This Class reads data from a Gaufrette Filesystem and moves it to an archive
 *
 * @package AppBundle\Import
 */
class GaufretteDataProvider implements ImportDataProviderInterface
{
    use LoggingTrait;

    /**
     * @var Filesystem
     */
    protected $fsImport;

    /**
     * @var Filesystem
     */
    protected $fsArchive;

    /**
     * @var string
     */
    protected $data;

    /**
     * @var string
     */
    protected $filename;

    /**
     * @var \DateTime
     */
    protected $dateTime;

    /**
     * @var boolean
     */
    protected $deleteAfterCopy;

    /**
     * GaufretteDataProvider constructor.
     *
     * @param Filesystem $fsImport
     * @param Filesystem $fsArchive
     * @param string     $filename
     */
    public function __construct(Filesystem $fsImport, Filesystem $fsArchive, $filename, $deleteAfterCopy = true)
    {
        $this->fsImport = $fsImport;
        $this->fsArchive = $fsArchive;
        $this->filename = $filename;
        $this->deleteAfterCopy = $deleteAfterCopy;
    }

    /**
     * @return null|string
     */
    public function getData()
    {
        if ($this->data === null) {
            $this->data = $this->getFileData();
        }

        return $this->data;
    }

    /**
     * sets the date time object for the timestamp of the archive file
     *
     * @param \DateTime $dateTime
     */
    public function setDateTime(\DateTime $dateTime)
    {
        $this->dateTime = $dateTime;
    }

    /**
     * reads the file if exists, copy to archive and return as string
     *
     * @return null|string
     */
    protected function getFileData()
    {
        $data = null;
        if ($this->fsImport->has($this->getFilename())) {
            $this->logInfo('File found: '.$this->getFilename());
            $data = $this->fsImport->read($this->getFilename());
            $this->fsArchive->write($this->getArchiveFilename(), $data);
            $this->logInfo('Wrote Copy to: '.$this->getArchiveFilename());
            if ($this->deleteAfterCopy) {
                $this->fsImport->delete($this->getFilename());
                $this->logInfo('File deleted: '.$this->getFilename());
            }
        } else {
            $this->logInfo('File not found: '.$this->getFilename());
        }

        return $data;
    }

    /**
     * The filename
     *
     * @return string
     */
    protected function getFilename()
    {
        return $this->filename;
    }

    /**
     * returns the filename with a timestamp inside of the name
     *
     * @return string
     */
    protected function getArchiveFilename()
    {
        return $this->getCurrentTimestamp().'_'.$this->getFilename();
    }

    /**
     * returns the current timestamp for the archive file
     *
     * @return string
     */
    protected function getCurrentTimestamp()
    {
        if ($this->dateTime) {
            return $this->dateTime->format('YmdHis');
        } else {
            $dt = new \DateTime();

            return $dt->format('YmdHis');
        }
    }
}
