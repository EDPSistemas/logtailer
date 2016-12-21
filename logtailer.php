<?php

require __DIR__.'/vendor/autoload.php';

use Dotenv\Dotenv;
use EDP\LogTailer\Commands\Tail as TailCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Yaml\Yaml;



$dotenv = new Dotenv(__DIR__);
$dotenv->load();

$value = Yaml::parse(file_get_contents(getenv('YAML_CONFIG_FILE')));

print_r($value);

$application = new Application();
$application->add(new TailCommand());

$application->run();
