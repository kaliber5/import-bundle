<?php
namespace Kaliber5\ImportBundle\Import\Exception;

use Symfony\Component\Validator\ConstraintViolationList;

/**
 * Class ImportException
 *
 * @package AppBundle\Import\Exception
 */
class ImportException extends \Exception
{
    /**
     * @var object
     */
    protected $sourceObject;

    /**
     * @var object
     */
    protected $destinationObject;

    /**
     * @var ConstraintViolationList
     */
    protected $violation;

    /**
     * ImportException constructor.
     *
     * @param string                       $message
     * @param object|null                  $sourceObject
     * @param object|null                  $destinationObject
     * @param ConstraintViolationList|null $violation
     * @param int                          $code
     * @param \Exception|null              $previous
     */
    public function __construct($message = "", $sourceObject = null, $destinationObject = null, ConstraintViolationList $violation = null, $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->sourceObject = $sourceObject;
        $this->destinationObject = $destinationObject;
        $this->violation = $violation;
    }

    /**
     * @return object
     */
    public function getSourceObject()
    {
        return $this->sourceObject;
    }

    /**
     * @return object
     */
    public function getDestinationObject()
    {
        return $this->destinationObject;
    }

    /**
     * @return ConstraintViolationList
     */
    public function getViolation()
    {
        return $this->violation;
    }

    /**
     * @return string
     */
    public function getVerboseMessage()
    {
        $message = $this->getMessage();
        if ($this->getSourceObject()) {
            $message .= ":\n";
            if (method_exists($this->getSourceObject(), '__toString')) {
                $message .= $this->getSourceObject()->__toString();
            } else {
                $message .= get_class($this->getSourceObject());
            }
        }
        if ($this->getDestinationObject()) {
            $message .= ":\n";
            if (method_exists($this->getDestinationObject(), '__toString')) {
                $message .= $this->getDestinationObject()->__toString();
            } else {
                $message .= get_class($this->getDestinationObject());
            }
        }
        if ($this->getViolation()) {
            foreach ($this->getViolation() as $violation) {
                $message .= "\n";
                $invalidValue = '';
                if (is_string($violation->getInvalidValue())) {
                    $invalidValue = ' = '.$violation->getInvalidValue();
                }
                $message .= $violation->getPropertyPath().$invalidValue.': '.$violation->getMessage();
            }
        }

        return $message."\n";
    }
}
