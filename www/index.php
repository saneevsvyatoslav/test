<?php
spl_autoload_register(function (string $className){
    require_once __DIR__. '/../src/' . str_replace('\\', '/', $className) . '.php';
} );

try {
    var_dump(__DIR__);
    $route = $_GET['route'] ?? '';
    $routes = require __DIR__.'/../src/routes.php';

    $isRouteFound = false;
    foreach ($routes as $pattern => $controllerAndAction ){
        preg_match($pattern, $route, $matches);
        if (!empty($matches)) {$isRouteFound = true; unset($matches[0]); break;}
    }

    if(!$isRouteFound){
        throw new \my\Exceptions\NotFoundException();
    }

    $controllerName = $controllerAndAction[0];
    $actionName = $controllerAndAction[1];
    $controller = new $controllerName();
    $controller->$actionName(...$matches);

} catch (\my\Exceptions\DbException $e){
 $view = new \my\View\View(__DIR__.'/../templates/error');
 $view->renderHtml('500.php',['error' => $e->getMessage()],500);
} catch (\my\Exceptions\NotFoundException $e){
    $view = new \my\View\View(__DIR__.'/../templates/error');
    $view->renderHtml('404.php',['error' => $e->getMessage()],500);
}