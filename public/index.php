<?php
ini_set('max_execution_time', 300);
ini_set('memory_limit', '512M');

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

use Illuminate\Foundation\Application;

if (file_exists($maintenance = __DIR__ . '/../storage/framework/maintenance.php')) {
    require $maintenance;
}
require __DIR__ . '/../vendor/autoload.php';
/** @var Application $app */
$app = require_once __DIR__ . '/../bootstrap/app.php';

$app->handleRequest(Request::capture());
