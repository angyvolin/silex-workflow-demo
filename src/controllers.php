<?php

use Entity\Article;
use Entity\Task;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/* @var Silex\Application $app */
$app->get('/', function () use ($app) {
    return $app['twig']->render('homepage/index.html.twig', array());
})->bind('homepage');

// Article actions
$app->get('/article', function () use ($app) {
    $articles = $app['orm.repository_factory']->getRepository($app['orm.em'], 'Entity\\Article')->findAll();

    return $app['twig']->render('article/index.html.twig', array(
        'articles' => $articles,
    ));
})->bind('article_index');

$app->post('/article/create', function (Request $request) use ($app) {
    $article = new Article($request->request->get('title', 'title'));

    $app['orm.em']->persist($article);
    $app['orm.em']->flush();

    return $app->redirect(
        $app['url_generator']->generate('article_show', array('id' => $article->getId()))
    );
})->bind('article_create');

$app->get('/article/show/{id}', function ($id) use ($app) {
    /** @var Article $article */
    $article = $app['orm.repository_factory']->getRepository($app['orm.em'], 'Entity\\Article')->findOneById($id);

    return $app['twig']->render('article/show.html.twig', array(
        'article' => $article,
    ));
})->bind('article_show');

$app->post('/article/apply-transition/{id}', function (Request $request, $id) use ($app) {
    /** @var Article $article */
    $article = $app['orm.repository_factory']->getRepository($app['orm.em'], 'Entity\\Article')->findOneById($id);

    try {
        $app['workflow.article']->apply($article, $request->request->get('transition'));

        $app['orm.em']->flush();
    } catch (Symfony\Component\Workflow\Exception\ExceptionInterface $e) {
        $app['session']->getFlashBag()->add('danger', $e->getMessage());
    }

    return $app->redirect(
        $app['url_generator']->generate('article_show', array('id' => $article->getId()))
    );
})->bind('article_apply_transition');

$app->post('/article/reset-marking/{id}', function ($id) use ($app) {
    /** @var Article $article */
    $article = $app['orm.repository_factory']->getRepository($app['orm.em'], 'Entity\\Article')->findOneById($id);
    $article->setMarking([]);
    $app['orm.em']->flush();

    return $app->redirect(
        $app['url_generator']->generate('article_show', array('id' => $article->getId()))
    );
})->bind('article_reset_marking');

// Task actions
$app->get('/task', function () use ($app) {
    return $app['twig']->render('task/index.html.twig', array(
        'tasks' => $app['orm.repository_factory']->getRepository($app['orm.em'], 'Entity\\Task')->findAll(),
    ));
})->bind('task_index');

$app->post('/task/create', function (Request $request) use ($app) {
    $task = new Task($request->request->get('title', 'title'));

    $app['orm.em']->persist($task);
    $app['orm.em']->flush();

    return $app->redirect(
        $app['url_generator']->generate('task_show', array('id' => $task->getId()))
    );
})->bind('task_create');

$app->get('/task/show/{id}', function ($id) use ($app) {
    /** @var Task $task */
    $task = $app['orm.repository_factory']->getRepository($app['orm.em'], 'Entity\\Task')->findOneById($id);

    return $app['twig']->render('task/show.html.twig', array(
        'task' => $task,
    ));
})->bind('task_show');

$app->post('/task/apply-transition/{id}', function (Request $request, $id) use ($app) {
    /** @var Task $task */
    $task = $app['orm.repository_factory']->getRepository($app['orm.em'], 'Entity\\Task')->findOneById($id);

    try {
        $app['state_machine.task']->apply($task, $request->request->get('transition'));

        $app['orm.em']->flush();
    } catch (Symfony\Component\Workflow\Exception\ExceptionInterface $e) {
        $app['session']->getFlashBag()->add('danger', $e->getMessage());
    }

    return $app->redirect(
        $app['url_generator']->generate('task_show', array('id' => $task->getId()))
    );
})->bind('task_apply_transition');

$app->post('/task/reset-marking/{id}', function ($id) use ($app) {
    /** @var Task $task */
    $task = $app['orm.repository_factory']->getRepository($app['orm.em'], 'Entity\\Task')->findOneById($id);
    $task->setMarking(null);

    $app['orm.em']->flush();

    return $app->redirect(
        $app['url_generator']->generate('task_show', array('id' => $task->getId()))
    );
})->bind('task_reset_marking');

// Errors
$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    // 404.html, or 40x.html, or 4xx.html, or error.html
    $templates = array(
        'errors/'.$code.'.html.twig',
        'errors/'.substr($code, 0, 2).'x.html.twig',
        'errors/'.substr($code, 0, 1).'xx.html.twig',
        'errors/default.html.twig',
    );

    return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code)), $code);
});
