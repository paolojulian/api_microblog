<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\ORM\Table;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler', [
            'enableBeforeRedirect' => false,
        ]);
        $this->loadComponent('APIResponse');
        $this->loadComponent(
            'Auth',
            [
                'storage' => 'Memory',
                'authorize' => ['Controller'],
                'authenticate' => [
                    'Form' => [
                        'fields' => [
                            'username' => 'username',
                            'password' => 'password',
                        ],
                    ],
                    'ADmad/JwtAuth.Jwt' => [
                        'parameter' => 'token',
                        'userModel' => 'Users',
                        'fields' => [
                            'username' => 'id',
                        ],
                        // Boolean indicating whether the "sub" claim of JWT payload
                        // should be used to query the Users model and get user info.
                        // If set to `false` JWT's payload is directly returned.
                        'queryDatasource' => true,
                    ],
                ],

                'unauthorizedRedirect' => false,
                'checkAuthIn' => 'Controller.initialize',

                // If you don't have a login action in your application set
                // 'loginAction' to false to prevent getting a MissingRouteException.
                'loginAction' => '/auth/login',
            ]
        );
    }

    public function isAuthorized()
    {
        return true;
    }

    /**
     * Check if model is owned by user passed
     * !!IMPORTANT
     * table should have user_id as column name
     * for its owner
     * 
     * TODO
     * make it dynamic for any field_name
     * 
     * @param \Cake\ORM\Table $model - The table to check if the data belongs to the user passed
     * @param int $userId - The user who is trying to access an entity
     * @param string $paramKey - The param in url /posts/:id
     * 
     * @return bool
     */
    public function isOwnedBy(Table $model, int $userId, string $paramKey = 'id')
    {
        $reqId = (int) $this->request->getParam($paramKey);
        if (method_exists($model, 'isOwnedBy')) {
            return $model->isOwnedBy($reqId, $userId);
        } else {
            return $model->exists(['id' => $reqId, 'user_id' => $userId]);
        }
    }
}
