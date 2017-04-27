<?php

use Angyvolin\Provider\WorkflowServiceProvider;
use Dflydev\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use Silex\Application;
use Silex\Provider\AssetServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;
use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Symfony\Component\Security\Core\Encoder\PlaintextPasswordEncoder;
use Symfony\Component\Security\Core\User\InMemoryUserProvider;

$app = new Application();
$app->register(new ServiceControllerServiceProvider());
$app->register(new AssetServiceProvider());

$dbopts = parse_url(getenv('DATABASE_URL'));
$app->register(new DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver' => 'pdo_pgsql',
        'host' => $dbopts['host'],
        'port' => $dbopts['port'],
        'user' => $dbopts['user'],
        'password' => $dbopts['pass'],
        'charset' => 'utf8',
        'dbname' => ltrim($dbopts["path"],'/'),
    ),
));
$app->register(new DoctrineOrmServiceProvider(), array(
    'orm.proxies_dir' => 'var/cache/proxies',
    'orm.em.options' => array(
        'mappings' => array(
            array(
                'use_simple_annotation_reader' => false,
                'type' => 'annotation',
                'namespace' => 'Entity',
                'path' => __DIR__.'/Entity',
            ),
        ),
    ),
));
$app->register(new HttpFragmentServiceProvider());
$app->register(new SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'dev' => array(
            'pattern' => '^/(_(profiler|wdt)|css|images|js)/',
            'security' => false,
        ),
        'main' => array(
            'pattern' => '^/',
            'anonymous' => true,
            'logout' => true,
            'switch_user' => array(
                'role' => 'IS_AUTHENTICATED_ANONYMOUSLY',
            ),
            'users' => new InMemoryUserProvider(array(
                'alice' => array('password' => 'password', 'roles' => array('ROLE_WRITTER')),
                'spellchecker' => array('password' => 'password', 'roles' => array('ROLE_SPELLCHECKER')),
                'journalist' => array('password' => 'password', 'roles' => array('ROLE_JOURNALIST')),
                'admin' => array('password' => 'password', 'roles' => array('ROLE_ADMIN')),
            )),
        ),
    ),
    'security.role_hierarchy' => array(
        'ROLE_ADMIN' => array('ROLE_WRITTER', 'ROLE_SPELLCHECKER', 'ROLE_JOURNALIST'),
    ),
    'security.default_encoder' => function () {
        return new PlaintextPasswordEncoder();
    },
));
$app->register(new SessionServiceProvider());
$app->register(new TwigServiceProvider());
$app->register(new WorkflowServiceProvider(), [
    'workflow.config' => require __DIR__.'/../config/workflow.php',
]);
$app->register(new Provider\WorkflowServiceProvider());

return $app;
