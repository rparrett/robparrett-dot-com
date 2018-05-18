<?php

use Slim\Http\Request;
use Slim\Http\Response;

$app->get('/', function (Request $request, Response $response, array $args) {
    $this->logger->info("Slim-Skeleton '/' route");

    return $this->renderer->render($response, 'index.html', [
        'test' => 'test2'
    ]);
});

$app->get('/rng', function(Request $request, Response $response, array $args) {
    $config = $this->settings['rng'];
    $rng = new RobParrett\RandomNameGenerator($config);

    $names = array();
    for ($i = 0; $i < 20; $i++) {
        $names[] = $rng->get(20);
    }

    return $this->renderer->render($response, 'rng.html', [
        'names' => $names
    ]);
});

$app->get('/biometrics', function(Request $request, Response $response, array $args) {
    $settings = $this->settings['withings'];

    $withings = $this->withingsModel;

    $weights = $withings->getWeightsLocal($settings['userId']);

    return $this->renderer->render($response, 'biometrics.html', [
        'weights' => $withings->formatWeightsForHighCharts($weights)
    ]);
});

$app->get('/tipit', function(Request $request, Response $response, array $args) {
    return $this->renderer->render($response, 'tipit.html', []);
});

$app->get('/fanbot', function(Request $request, Response $response, array $args) {
    return $this->renderer->render($response, 'fanbot.html', []);
});

$app->get('/pongclock', function(Request $request, Response $response, array $args) {
    return $this->renderer->render($response, 'pongclock.html', []);
});

$app->get('/login', function(Request $request, Response $response, array $args) {
    return $this->renderer->render($response, 'login.html', []);
});

$app->post('/login', function(Request $request, Response $response, array $args) {
    $username = $request->getParam('username');
    $password = $request->getParam('password');
    $remember = $request->getParam('remember');

    if (!$username || !$password) {
        $this->flash->addMessageNow('error', 'Invalid request');
        return $this->renderer->render($response, 'login.html', []);
    }

    $user = $this->auth->authenticate($username, $password, $remember);
    if ($user === false) {
        $this->flash->addMessageNow('error', 'Login Failed.');
        return $this->renderer->render($response, 'login.html', []);
    }
    
    return $response->withRedirect('/');
});

$app->get('/logout', function(Request $request, Response $response, array $args) {
    $this->auth->logout();
    
    $this->flash->addMessage('success', 'You have been logged out.');

    return $response->withRedirect('/');
});

$app->get('/secret', function(Request $request, Response $response, array $args) {
    return $this->renderer->render($response, 'secret.html', []);
})->add(new RobParrett\Middleware\RequireSuperuser($container));
