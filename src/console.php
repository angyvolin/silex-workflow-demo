<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Process\Process;
use Symfony\Component\Workflow\Dumper\GraphvizDumper;

$console = new Application('My Silex Application', 'n/a');
$console->getDefinition()->addOption(new InputOption('--env', '-e', InputOption::VALUE_REQUIRED, 'The Environment name.', 'dev'));
$console->setDispatcher($app['dispatcher']);
$console
    ->register('workflow:build:svg')
    ->setDefinition(array(
        new InputArgument('service_name', InputArgument::REQUIRED, 'The service name of the workflow (ex workflow.article)'),
    ))
    ->setDescription('Build the SVG')
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {
        $name = $input->getArgument('service_name');

        $definition = $this->getContainer()->get($name)->getDefinition();

        $dumper = new GraphvizDumper();

        $dot = $dumper->dump($definition, null, ['node' => ['width' => 1.6]]);

        $process = new Process('dot -Tsvg');
        $process->setInput($dot);
        $process->mustRun();

        $svg = $process->getOutput();
        $svg = preg_replace('/.*<svg/ms', sprintf('<svg class="img-responsive" id="%s"', str_replace('.', '-', $name)), $svg);

        $shortName = explode('.', $name)[1];

        file_put_contents(sprintf('%s/Resources/views/%s/doc.svg.twig', $this->getContainer()->getParameter('kernel.root_dir'), $shortName), $svg);
    })
;

return $console;
