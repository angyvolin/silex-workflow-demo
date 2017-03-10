<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Workflow\Dumper\GraphvizDumper;

$console = new Application('silex-workflow-demo app', '0.0.1');
$console->getDefinition()->addOption(new InputOption('--env', '-e', InputOption::VALUE_REQUIRED, 'The Environment name.', 'dev'));
$console->setDispatcher($app['dispatcher']);
$console
    ->register('workflow:build:svg')
    ->setDefinition(array(
        new InputArgument('service_name', InputArgument::REQUIRED, 'The service name of the workflow (ex workflow.article)'),
    ))
    ->setDescription('Build the SVG')
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {
        $app->boot();
        $name = $input->getArgument('service_name');

        $definition = $app[$name]->getDefinition();

        $dumper = new GraphvizDumper();

        $dot = $dumper->dump($definition, null, array('node' => array('width' => 1.6)));

        $process = new Process('dot -Tsvg');
        $process->setInput($dot);
        $process->mustRun();

        $svg = $process->getOutput();
        $svg = preg_replace('/.*<svg/ms', sprintf('<svg class="img-responsive" id="%s"', str_replace('.', '-', $name)), $svg);

        $shortName = explode('.', $name)[1];

        file_put_contents(sprintf('%s/templates/%s/doc.svg.twig', dirname(__DIR__), $shortName), $svg);
    })
;

return $console;
