<?php

require_once('./vendor/autoload.php');

use FeeCalcApp\Command\CalculateFeeCommand;
use Symfony\Component\Console\Application;
$argv[1] = 'prod';

$argv[2] = '';
if (!isset($argv[1])) {
    throw new \InvalidArgumentException('Missing input file with transaction information');
}

$env = isset($argv[2]) && $argv[2] === 'test' ? 'test' : 'prod';
$container = (new AppFactory())->create($env)->buildContainer();

$application = new Application();
$application->add($container->get(CalculateFeeCommand::class));
$application->run();
