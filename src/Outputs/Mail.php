<?php

namespace EDP\LogTailer\Outputs;

use BadArgumentException;
use RuntimeException;

class Mail
{
    protected $from;
    protected $recipients = [];

    public function __construct($options = null)
    {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }

    public function setOptions($options)
    {
        if (!is_array($options)) {
            throw new BadArgumentException('Invalid options supplied');
        }

        if (array_key_exists('from', $options)) {
            $this->setFrom($options['from']);
        }

        if (array_key_exists('recipients', $options)) {
            foreach ($options['recipients'] as $recipient) {
                $this->addRecipient($recipient);
            }
        }
    }

    public function setFrom($from)
    {
        if (!filter_var($from, FILTER_VALIDATE_EMAIL)) {
            throw new BadArgumentException("From $from inválido");
        }

        $this->from = $from;
    }

    public function addRecipient($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new BadArgumentException("Email $email inválido");
        }

        $this->recipients[] = $email;
    }

    public function getHeaders()
    {
        $headers = [];

        if ($this->from) {
            $headers[] = sprintf('From: %s', $this->from);
            $headers[] = sprintf('Reply-To: %s', $this->from);
        }

        $headers[] = 'X-Mailer: PHP LogTailer';

        return implode("\r\n", $headers);
    }

    public function getSubject()
    {
        return sprintf(
            '[LogTailer][%s] - Report',
            gethostname()
        );
    }

    public function out($lines)
    {
        if (empty($this->recipients)) {
            throw new RuntimeException('Lista de destinatários vazia');
        }

        $headers = $this->getHeaders();

        if (!getenv('REALLY_SEND_EMAIL')) {
            print_r([implode(',', $this->recipients), $this->getSubject(), implode("\n", $lines), $headers]);

            return;
        }

        mail(implode(',', $this->recipients), $this->getSubject(), implode("\n", $lines), $headers);
    }
}
