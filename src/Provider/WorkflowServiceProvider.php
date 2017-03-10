<?php

namespace Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Api\EventListenerProviderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Twig\WorkflowExtension;
use Workflow\GuardListener;

class WorkflowServiceProvider implements ServiceProviderInterface, EventListenerProviderInterface
{
    public function register(Container $app)
    {
        $app->extend('twig', function (\Twig_Environment $twig, Container $app) {
            $twig->addExtension(new WorkflowExtension($app['workflow.registry']));

            return $twig;
        });
    }

    public function subscribe(Container $app, EventDispatcherInterface $dispatcher)
    {
        $dispatcher->addSubscriber(new GuardListener($app['security.authorization_checker']));
    }
}
