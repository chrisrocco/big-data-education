<?php
// DIC configuration

$container = $app->getContainer();

// -----------------------------------------------------------------------------
// Service providers
// -----------------------------------------------------------------------------

// Twig
$container['view'] = function ($c) {
    $settings = $c->get('settings');
    $view = new Slim\Views\Twig($settings['view']['template_path'], $settings['view']['twig']);

    // Add extensions
    $view->addExtension(new Slim\Views\TwigExtension($c->get('router'), $c->get('request')->getUri()));
    $view->addExtension(new Twig_Extension_Debug());

    return $view;
};

// Flash messages
$container['flash'] = function ($c) {
    return new Slim\Flash\Messages;
};

// -----------------------------------------------------------------------------
// Service factories
// -----------------------------------------------------------------------------

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings');
    $logger = new Monolog\Logger($settings['logger']['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['logger']['path'], Monolog\Logger::DEBUG));
    return $logger;
};

// -----------------------------------------------------------------------------
// Action factories
// -----------------------------------------------------------------------------

$container[App\Action\HomeAction::class] = function ($c) {
    return new App\Action\HomeAction($c->get('view'), $c->get('logger'));
};

// ----------------------------------------------
// Database helper
// ----------------------------------------------
$container['database'] = function($c){
	$set = $c->get('settings');
	$db = new Database($set['database']['host'],
				$set['database']['username'],
				$set['database']['password'],
				$set['database']['name']);
	return $db;
};
// ----------------------------------------------
// Accounts helper
// ----------------------------------------------
$container['accounts'] = function($c){
	$accounts = new Accounts($c['database']);
	return $accounts;
};
// ----------------------------------------------
// Assignments helper
// ----------------------------------------------
$container['assignments'] = function($c){
	$assignments = new Assignments($c['database']);
	return $assignments;
};
// ----------------------------------------------
// Conflicts helper
// ----------------------------------------------
$container['conflictManager'] = function($c){
	$conflictManager = new ConflictManager($c['database'], $c['assignments']);
	return $conflictManager;
};