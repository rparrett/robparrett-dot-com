<?php

// DIC configuration
$container = $app->getContainer();

$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    
    // TODO: configure twig cache

    $view = new Slim\Views\Twig($settings['template_path']);
    $view->addExtension(new Knlv\Slim\Views\TwigMessages(
        $c->get('flash')
    ));

    $view->addExtension(new RobParrett\AuthenticationTwigExtension(
        $c->get('auth')
    ));

    return $view;
};

$container['sqlite'] = function($c) {
    $settings = $c->get('settings')['sqlite'];
    
    return new SQLite3($settings['path']);
};

$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

$container['withingsClient'] = function($c) {
    $settings = $c->get('settings')['withings'];

    return new RobParrett\WithingsAPIClient(
        $settings['withings']['oauthToken'],
        $settings['withings']['oauthTokenSecret'],
        $settings['withings']['oauthConsumerKey'],
        $settings['withings']['oauthConsumerSecret']
    );
};

$container['withingsModel'] = function($c) {
    return new RobParrett\Model\Withings(
        $c->get('sqlite'),
        $c->get('withingsClient')
    );
};

$container['auth'] = function($c) {
    return new RobParrett\Model\Authentication(
        $c->get('sqlite')
    );
};

$container['flash'] = function($c) {
    return new \Slim\Flash\Messages();
};
