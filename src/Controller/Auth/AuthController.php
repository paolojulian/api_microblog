<?php
namespace App\Controller\Auth;

use App\Controller\AppController;
use App\Exception\EmailNotSentException;
use App\Exception\UserNotActivatedException;
use App\Exception\UserUnauthorizedException;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;

/**
 * Api/Auths Controller
 */
class AuthController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        // $this->Auth->allow(['login', 'register', 'activate']);
        $this->UserModel = TableRegistry::getTableLocator()->get('Users');
    }

    /**
     * [POST]
     * [PUBLIC]
     * 
     * Logs in the current user and returns a Jwt Token upon success
     * Only allow activated accounts
     * 
     * @return json - containing Jwt Token
     * 
     * @throws UserUnauthorizedException Provided Login Credentials is invalid
     * @throws UserNotActivatedException User is not yet activated based on the database
     * 
     * @uses component - JWTHandler
     */
    public function login()
    {
        $this->request->allowMethod('post');
        $this->loadComponent('JWTHandler');
        $user = $this->Auth->identify();
        if ( ! $user) {
            throw new UserUnauthorizedException();
        }
        if ( ! $user['is_activated']) {
            throw new UserNotActivatedException();
        }

        $this->APIResponse->responseData(['token' => $this->JWTHandler->encode($user)]);
    }
    
    /**
     * [POST]
     * [PUBLIC]
     * 
     * Signs up a user,
     * Sends an activation email after a successful registration
     * 
     * @return \App\Model\Entity\User
     * 
     * @throws EmailNotSentException If something unknown happened that the email failed to send
     * 
     * @uses component - UserHandler
     * @uses component - HasherHandler
     */
    public function register()
    {
        $this->request->allowMethod('post');
        $this->loadComponent('HasherHandler');
        // $this->loadComponent('UserHandler');
        $requestData = $this->request->getData();
        $requestData['activation_key'] = $this->HasherHandler->generateRand();
        $user = $this->UserModel->addUser($requestData);
        // try {
        //     $this->UserHandler->sendActivationMail(
        //         $this->request->getData(),
        //         Router::url('/api/auth/activate', true)
        //     );
        // } catch (\Exception $e) {
        //     throw new EmailNotSentException(__($e->getMessage()));
        // }

        return $this->APIResponse->responseCreated($user);
    }

    /**
     * [GET]
     * [PUBLIC]
     * 
     * A link is given to a user upon successful registration
     * 
     * Activates the user by the its activation key
     * 
     * @return null
     */
    public function activate()
    {
        $this->request->allowMethod('get');
        $key = $this->request->getParam('key');
        if ( ! $this->UserModel->activateAccount($key)) {
            return $this->redirect('/activation-error');
        }
        return $this->redirect('/login');
    }

    /**
     * [GET]
     * [PRIVATE]
     * 
     * Fetches profile of current user logged in
     * 
     */
    public function me()
    {
        // $this->request->allowMethod('get');
        // $user = $this->UserModel->get($this->Auth->user('id'));
        // $user->birthdate->format('Y-m-d');
        // $user->birthdate = $user->birthdate->format('Y-m-d');
        // return $this->responseData($user);
    }

    public function test()
    {
        throw new UserNotActivatedException();
        $this->APIResponse->responseOk();
    }
}
