<?php

// print_r(apache_get_modules());
// echo "<pre>"; print_r($_SERVER); die;
// $_SERVER["REQUEST_URI"] = str_replace("/phalt/","/",$_SERVER["REQUEST_URI"]);
// $_GET["_url"] = "/";
use Phalcon\Di\FactoryDefault;
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\Url;
use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Config;
use Phalcon\Config\ConfigFactory;
use Phalcon\Session\Manager;
use Phalcon\Session\Adapter\Stream;
use Phalcon\Events\Manager as EventsManager;


$config = new Config([]);


// Define some absolute path constants to aid in locating resources
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

// Register an autoloader
$loader = new Loader();

$loader->registerDirs(
    [
        APP_PATH . "/controllers/",
        APP_PATH . "/models/",
        ]
    );
    
require BASE_PATH.'/vendor/autoload.php';

$loader->registerNamespaces(
    [
        'App\Collection' => APP_PATH.'/Collection'
    ]
    );

$loader->register();

$container = new FactoryDefault();

$container->set(
    'view',
    function () {
        $view = new View();
        $view->setViewsDir(APP_PATH . '/views/');
        return $view;
    }
);

$container->set(
    'url',
    function () {
        $url = new Url();
        $url->setBaseUri('/');
        return $url;
    }
);

$application = new Application($container);


$container->set(
    'db',
    function () {
        return new Mysql(
            [
                'host'     => 'mysql-server',
                'username' => 'root',
                'password' => 'secret',
                'dbname'   => 'spotify',
            ]
        );
    }
);




$container->setShared(
    "mongo",
    function () {
        $mongo= new MongoDB\Client(
            'mongodb://root:password123@mongo'
        );
        return $mongo;
    }
);


$container->set(
    'client',
    function () {
        $client = new MongoDB\Client(
            'mongodb+srv://prateek_cedcoss:Qwerty78@cluster0.m4mbp.mongodb.net/Cluster0?retryWrites=true&w=majority'
        );
        return $client;
    }
);

//--------------------------------Session starts-------------------------------//

$container->set(
    'session',
    function () {
        $session = new Manager();
        $files = new Stream(
            [
                'savePath' => '/tmp',
            ]
        );

        $session->setAdapter($files);
        $session->start();

        return $session;
    }
);

//--------------------------------Session ends-------------------------------//

try {
    // Handle the request
    $response = $application->handle(
        $_SERVER["REQUEST_URI"]
    );

    $response->send();
} catch (\Exception $e) {
    echo 'Exception: ', $e->getMessage();
}
