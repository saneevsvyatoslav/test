<?php
return [
    '~^parser$~' => [\my\Controllers\ParserController::class, 'view'],
    '~^users/login$~' => [\my\Controllers\UsersController::class, 'login'],
    '~^users/(\d+)/activate/(.+)$~' =>[\my\Controllers\UsersController::class, 'activate'],
    '~^users/register$~'=> [\my\Controllers\UsersController::class, 'signUp'],
    '~^articles/(\d+)/delete$~'=> [\my\Controllers\ArticlesController::class, 'delete'],
    '~^articles/(\d+)/edit$~' => [\my\Controllers\ArticlesController::class, 'edit'],
    '~^articles/add$~' => [\my\Controllers\ArticlesController::class, 'add'],
    '~^articles/(\d+)$~' => [\my\Controllers\ArticlesController::class, 'view'],
    '~^hello/(.*)$~' => [\my\Controllers\MainController::class, 'sayHello'],
    '~^$~' => [\my\Controllers\MainController::class, 'main']
];