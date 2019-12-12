<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @category  Cake
 * @package   Cake
 * @author    Pipz <paolovincent.yns@gmail.com>
 * @copyright 2019 Copyright (c) Cake Software Foundation
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 * @link      https://cakephp.org CakePHP(tm) Project
 */
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

Router::defaultRouteClass(DashedRoute::class);

// /**
//  * Routes to use
//  * 
//  * @param RouteBuilder $routes - The route
//  * 
//  * @return void
//  */
Router::extensions(['json']);
$apiRoutes = function (RouteBuilder $routes) {

    $routes->setExtensions(['json', 'xml']);

    $routes->prefix('auth', function (RouteBuilder $routes) {
        $routes->connect(
            '/login',
            ['controller' => 'Auth', 'action' => 'login']
        )->setMethods(['POST']);

        $routes->connect(
            '/register',
            ['controller' => 'Auth', 'action' => 'register']
        )->setMethods(['POST']);

        $routes->connect(
            '/activate/:key',
            ['controller' => 'Auth', 'action' => 'activate']
        )->setMethods(['GET']);
    });

    $routes->prefix('users', function (RouteBuilder $routes) {
        $routes->connect(
            '/',
            ['controller' => 'Users', 'action' => 'edit']
        )->setMethods(['PUT']);

        $routes->connect(
            '/:username',
            ['controller' => 'Users', 'action' => 'view']
        )->setMethods(['GET']);

        $routes->connect(
            '/:username/follow',
            ['controller' => 'Users', 'action' => 'follow']
        )->setMethods(['POST']);

        $routes->connect(
            '/:username/posts',
            ['controller' => 'Users', 'action' => 'fetchPosts']
        )->setMethods(['GET']);

        $routes->connect(
            '/image',
            ['controller' => 'Users', 'action' => 'updateImage']
        )->setMethods(['PATCH']);
    });

    $routes->prefix('search', function (RouteBuilder $routes) {
        $routes->connect(
            '/',
            ['controller' => 'Search', 'action' => 'index']
        )->setMethods(['GET']);

        $routes->connect(
            '/users',
            ['controller' => 'Search', 'action' => 'users']
        )->setMethods(['GET']);

        $routes->connect(
            '/posts',
            ['controller' => 'Search', 'action' => 'posts']
        )->setMethods(['GET']);
    });

    $routes->prefix('posts', function (RouteBuilder $routes) {
        $routes->connect(
            '/',
            ['controller' => 'Posts', 'action' => 'index']
        )->setMethods(['GET']);

        $routes->connect(
            '/',
            ['controller' => 'Posts', 'action' => 'add']
        )->setMethods(['POST']);

        $routes->connect(
            '/:id',
            ['controller' => 'Posts', 'action' => 'view']
        )->setMethods(['GET']);

        $routes->connect(
            '/:id',
            ['controller' => 'Posts', 'action' => 'edit']
        )
        ->setPatterns(['id' => '\d+'])
        ->setMethods(['PUT', 'POST']);

        $routes->connect(
            '/:id',
            ['controller' => 'Posts', 'action' => 'delete']
        )
        ->setPatterns(['id' => '\d+'])
        ->setMethods(['DELETE']);

        $routes->connect(
            '/:id/share',
            ['controller' => 'Posts', 'action' => 'share']
        )
        ->setPatterns(['id' => '\d+'])
        ->setMethods(['POST']);

        $routes->connect(
            '/:id/likes',
            ['controller' => 'Posts', 'action' => 'like']
        )
        ->setPatterns(['id' => '\d+'])
        ->setMethods(['POST']);

        $routes->connect(
            '/:id/comments',
            ['controller' => 'Comments', 'action' => 'index']
        )
        ->setPatterns(['id' => '\d+'])
        ->setMethods(['GET']);

        $routes->connect(
            '/:id/comments',
            ['controller' => 'Comments', 'action' => 'add']
        )
        ->setPatterns(['id' => '\d+'])
        ->setMethods(['POST']);

        $routes->connect(
            '/:id/comments/:commentId',
            ['controller' => 'Comments', 'action' => 'edit']
        )
        ->setPatterns([
            'id' => '\d+',
            'commentId' => '\d+'
        ])
        ->setMethods(['PUT']);

        $routes->connect(
            '/:id/comments/:commentId',
            ['controller' => 'Comments', 'action' => 'delete']
        )
        ->setPatterns([
            'id' => '\d+',
            'commentId' => '\d+'
        ])
        ->setMethods(['PUT']);
    });

    $routes->fallbacks(DashedRoute::class);
};

Router::scope('/', $apiRoutes);
// Router::scope('/', function (RouteBuilder $routes) {
//     $routes->connect('/', ['controller' => 'Error', 'action' => 'display', 'home']);
//     $routes->connect('/*', ['controller' => 'Error', 'action' => 'display']);

//     $routes->fallbacks(DashedRoute::class);
// });