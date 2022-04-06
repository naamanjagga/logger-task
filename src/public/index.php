<?php

use Phalcon\Di\FactoryDefault;
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\Url;
use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Session\Adapter\Stream as SessionAdapter;
use Phalcon\Session\Manager as SessionManager;
use Phalcon\Config;
use Phalcon\Config\ConfigFactory;
use Phalcon\Mvc\Router;
use Phalcon\Http\Response;
use Phalcon\Http\Response\Cookies;
use Phalcon\Escaper;
use Phalcon\Logger;
use Phalcon\Logger\Adapter\Stream;

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

// Register an autoloader
$loader = new Loader();

$loader->registerDirs(
    [
        APP_PATH . '/controllers/',
        APP_PATH . '/models/',
        APP_PATH . '/components/',
    ]
);

$loader->register();

$loader = new Loader();

// $loader->registerClasses(
//     [
//         'myEscape' =>  APP_PATH .'/components/myescape.php',
//     ]
// );

$loader->register();

$container = new FactoryDefault();


// $container->set(
//     'db',
//     function () use ($config) {
//         return new Mysql(
//             [
//                 'host'     => $config->db->host,
//                 'username' => $config->db->username,
//                 'password' => $config->db->password,
//                 'dbname'   => $config->db->name,
//             ]
//         );
//     }
// );

$container->set(
    'escaper',
    function ()  {
        $escaper = new Escaper();
        return $escaper;
    }
);

$container->set(
    'db',
    function () {
        return new Mysql(
            [
                'host'     => 'mysql-server',
                'username' => 'root',
                'password' => 'secret',
                'dbname'   => 'mydatabase',
            ]
        );
    }
);

$container->setShared('session', function () {
    $session = new SessionManager();
    $files = new SessionAdapter([
        'savePath' => sys_get_temp_dir(),
    ]);
    $session->setAdapter($files);
    $session->start();

    return $session;
});

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


$container->set(
    'config',
    function () {
        $fileName = '../app/config/config.php';
        $factory  = new ConfigFactory();

        return $factory->newInstance('php', $fileName);
    },
    true
);

$router = new Router();

$router->add(
    '/',
    [
        'controller' => 'index',
        'action'     => 'index',
    ]
);

$router->handle(
    $_SERVER["REQUEST_URI"]
);

$container->set(
    'error',
    function () {
        $response = new Response(
            "Sorry, the page doesn't exist",
            404,
            'Not Found'
        );
    },
    true
);

$container->set(
    "cookies",
    function () {
        $cookies = new Cookies();
        $cookies->useEncryption(false);
        return $cookies;
    }
);

$container->set(
    "crypt",
    function () {
        $crypt = new Crypt();
        $crypt->setKey('AED@!sft56$');
        return $crypt;
    }
);

$container->set(
    "service",
    function () {
        $ts = new DateTime();
        $str = $ts->format('Y-m-d H:i:s:u');
        return $str;
    }
);
$container->set(
    "logger1",
    function (){
    $adapter1 = new Stream(APP_PATH.'/logs/login.log');
    $logger1  = new Logger(
       'messages',
        [
            'login' => $adapter1,
        ]
        );
     return $logger1;
    }
  );

  $container->set(
    "logger2",
    function (){
    $adapter2 = new Stream(APP_PATH.'/logs/sign.log');
    $logger2  = new Logger(
       'messages',
        [
            'login' => $adapter2,
        ]
        );
     return $logger2;
    }
  );

$application = new Application($container);

try {
    // Handle the request
    $response = $application->handle(
        $_SERVER["REQUEST_URI"]
    );

    $response->send();
} catch (\Exception $e) {
    echo 'Exception: ', $e->getMessage();
}
