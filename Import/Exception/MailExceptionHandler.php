<?php

namespace Kaliber5\ImportBundle\Import\Exception;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
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
     * @var MailerInterface
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
     * @param MailerInterface $mailer
     * @param EngineInterface $twig
     * @param string          $subject
     * @param string          $from
     * @param string|array    $to
     * @param string          $template
     */
    public function __construct(MailerInterface $mailer, EngineInterface $twig, $subject, $from, $to, $template)
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
            $email = (new TemplatedEmail())
                ->subject($this->subject)
                ->from($this->from)
                ->to($this->to)
                ->textTemplate($this->template)
                ->context(['exceptions' => $this->exceptions]);
            $this->mailer->send($email);
            $to = is_array($this->to) ? implode(', ', $this->to) : $this->to;
            $this->logInfo('Mail send to '.$to);
        } catch (TransportExceptionInterface $te) {
            $this->logDebug(sprintf('send mail debug infos: %s', $te->getDebug()));
            $this->logError('Cannot send email', $te);

        } catch (\Exception $e) {
            $this->logError('Cannot send email, general error', $e);
        }
    }
}
