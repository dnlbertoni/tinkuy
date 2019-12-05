<?php
define('APP_ROOT', __DIR__);
$dotenv = Dotenv\Dotenv::create(__DIR__.'/../public/');
$dotenv->load();

return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'proc',
            'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/tinkuy.log',
            'level' => \Monolog\Logger::DEBUG,
        ],
        'doctrine' => [
            // if true, metadata caching is forcefully disabled
            'dev_mode' => true,

            // path where the compiled metadata info will be cached
            // make sure the path exists and it is writable
            'cache_dir' => APP_ROOT . '/var/doctrine',

            // you should add any other path containing annotated entity classes
            'metadata_dirs' => [APP_ROOT . '/src/Domain'],

            'connection' => [
                'driver' => 'pdo_mysql',
                'host' => getenv('DB_HOST'),
                'port' => 3306,
                'dbname' => getenv('DB_NAME') ,
                'user' => getenv('DB_USER'),
                'password' => getenv('DB_PASS'),
                'charset' => 'utf-8'
            ]
        ]
    ],
];
