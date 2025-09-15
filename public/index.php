<?php
declare(strict_types=1);

session_start();
require __DIR__ . '/../app/config.php';
require __DIR__ . '/../app/Router.php';
require __DIR__ . '/../app/TaskModel.php';
require __DIR__ . '/../app/TaskController.php';

use App\Router;
use App\TaskController;

$router = new Router();

// routes
$router->get('/', [TaskController::class, 'index']);
$router->post('/task/create', [TaskController::class, 'create']);
$router->post('/task/toggle', [TaskController::class, 'toggle']);
$router->post('/task/delete', [TaskController::class, 'delete']);
$router->post('/task/update', [TaskController::class, 'update']);
$router->post('/task/clear-completed', [TaskController::class, 'clearCompleted']);

$router->dispatch();
