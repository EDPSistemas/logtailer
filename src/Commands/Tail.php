<?php

namespace EDP\LogTailer\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use EDP\LogTailer\Outputs\Mail;
use EDP\LogTailer\Tailer;

class Tail extends Command
{
    protected function configure()
    {

    $this
        ->setName('app:tail')
        ->setDescription('Tail one or more files and process the output')
        ->setHelp('Tail one or more files and process the output');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $tailer = new Tailer('./coisa');
        $mail = new Mail($tailer);
        $mail->addRecipient('ciro.mies@gmail.com');

        $mail->run();
    }
}
