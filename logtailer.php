<?php

require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Application;
use EDP\LogTailer\Commands\Tail as TailCommand;

$application = new Application();
$application->add(new TailCommand());

$application->run();
