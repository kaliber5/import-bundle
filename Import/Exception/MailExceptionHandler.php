<?php

namespace Kaliber5\ImportBundle\Import\Exception;

use Symfony\Component\Templating\EngineInterface;

/**
 * Class MailExceptionHandler
 *
 * This class sends an email with the exception messages
 *
 * @package AppBundle\Import\Exception
 */
class MailExceptionHandler extends AbstractExceptionHandler implements ExceptionHandlerInterface
{
    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    /**
     * @var EngineInterface
     */
    protected $twig;

    /**
     * @var string
     */
    protected $subject;

    /**
     * @var string
     */
    protected $from;

    /**
     * @var string|array
     */
    protected $to;

    /**
     * @var string
     */
    protected $template;

    /**
     * MailExceptionHandler constructor.
     *
     * @param \Swift_Mailer   $mailer
     * @param EngineInterface $twig
     * @param string          $subject
     * @param string          $from
     * @param string|array    $to
     * @param string          $template
     */
    public function __construct(\Swift_Mailer $mailer, EngineInterface $twig, $subject, $from, $to, $template)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->subject = $subject;
        $this->from = $from;
        $this->to = $to;
        $this->template = $template;
    }

    /**
     * {@inheritdoc}
     */
    public function handleExceptions()
    {
        if (count($this->exceptions) < 1) {
            return;
        }
        try {
            $message = \Swift_Message::newInstance()
                ->setSubject($this->subject)
                ->setFrom($this->from)
                ->setTo($this->to)
                ->setBody(
                    $this->twig->render(
                        $this->template,
                        ['exceptions' => $this->exceptions]
                    ),
                    'text/plain'
                );
            /** @noinspection PhpParamsInspection */
            $this->mailer->send($message);
            $to = is_array($this->to) ? print_r($this->to, true) : $this->to;
            $this->logInfo('Mail send to '.$to);
        } catch (\Exception $e) {
            $this->logError('Cannot send email', $e);
        }
    }
}
