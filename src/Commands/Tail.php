<?php

namespace EDP\LogTailer\Commands;

use InvalidArgumentException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use EDP\LogTailer\Tailer;
use EDP\LogTailer\Outputs\OutputFactory;

class Tail extends Command
{
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

    public function initialize(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getOption('file')) {
            throw new InvalidArgumentException('File must be provided');
        }

        if (!$input->getOption('output')) {
            throw new InvalidArgumentException('Output type must be provided');
        }

        if (!array_key_exists($input->getOption('output'), OutputFactory::$outputs)) {
            throw new InvalidArgumentException('Output type must be supported: ['.implode(', ', array_keys(OutputFactory::$outputs)).']');
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //@todo: isso só é usado aqui, precisa mover para um singleton
        global $config;
        $optionFile = $input->getOption('file');
        $optionOutput = $input->getOption('output');

        if (!array_key_exists($optionOutput, $config['outputs'])) {
            throw new \Exception("Configuration for $optionOutput not found");
        }

        $tailer = new Tailer($optionFile);
        $options = $config['outputs'][$optionOutput];
        $output = OutputFactory::create($optionOutput, $options);

        $tailer->open();
        while (true) {
            $lines = $tailer->read();

            if (!empty($lines)) {
                $output->out($lines);
            }

            $tailer->sleep();
        }
    }
}
