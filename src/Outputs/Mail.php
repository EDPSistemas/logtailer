<?php

namespace EDP\LogTailer\Outputs;

use \BadArgumentException;
use \RuntimeException;

use EDP\LogTailer\Tailer;

class Mail
{
    protected $tailer;

    public function __construct(Tailer $tailer)
    {
        $this->tailer = $tailer;
        $this->tailer->open();
    }

    public function addRecipient($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new BadArgumentException("Email $email inválido");
        }

        $this->recipients[] = $email;
    }

    public function run()
    {
        var_dump($this->tailer);
        while (true) {
            $lines = $this->tailer->read();

            if (!empty($lines)) {
                $this->sendEmail($lines);
            }

            $this->tailer->sleep();
        }
    }

    protected function sendEmail($lines)
    {
        if (empty($this->recipients)) {
            throw new RuntimeException('Lista de destinatários vazia');
        }

        mail(implode(',', $this->recipients), 'Subject', implode("\n", $lines));
    }
}
