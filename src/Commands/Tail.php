<?php

namespace EDP\LogTailer\Commands;

use InvalidArgumentException;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use EDP\LogTailer\Outputs\Mail;
use EDP\LogTailer\Tailer;

class Tail extends Command
{
    protected $outputs = [
        "mail" => Mail::class,
    ];

    protected function configure()
    {

    $this
        ->setName('app:tail')
        ->setDescription('Tail one file and process the output')
        ->setHelp('Tail one file and process the output')
        ->setDefinition(
            new InputDefinition([
                new InputOption('file', 'f', InputOption::VALUE_REQUIRED),
                new InputOption('output', 'o', InputOption::VALUE_REQUIRED),
            ])
         );


    }

    public function initialize(InputInterface $input, OutputInterface $output) {
        if (!$input->getOption('file')) {
            throw new InvalidArgumentException('File must be provided');
        }

        if (!$input->getOption('output')) {
            throw new InvalidArgumentException('Output type must be provided');
        }

        if (!array_key_exists($input->getOption('output'), $this->outputs)) {
            throw new InvalidArgumentException('Output type must be supported: [' . implode(', ', array_keys($this->outputs)) . ']' );
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        echo __FUNCTION__;

        $tailer = new Tailer('./coisa');
        $mail = new Mail($tailer);
        $mail->addRecipient('ciro.mies@gmail.com');

        $mail->run();
    }
}
