<?php
namespace App\Controller\Auth;

use App\Controller\AppController;
use App\Exception\ValidationErrorsException;
use Cake\Http\Exception\InternalErrorException;
// use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Exception;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;
use UnexpectedValueException;

/**
 * Api/Auths Controller
 */
class AuthController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        // $this->Auth->allow(['login', 'register', 'activate']);
        // $this->UserModel = TableRegistry::getTableLocator()->get('Users');
    }

    /**
     * [POST]
     * [PUBLIC]
     * 
     * Logs in the current user and returns a Jwt Token upon success
     * Only allow activated accounts
     * 
     * @return json - containing Jwt Token
     */
    public function login()
    {
        $this->request->allowMethod('post');
        $this->loadComponent('JWTHandler');
        $user = $this->Auth->identify();
        if ( ! $user) {
            throw new ValidationErrorsException(null, 'Username or password is incorrect');
        }
        if ( ! $user['is_activated']) {
            throw new ValidationErrorsException(null, 'Please activate your account first');
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
     * @return status 201 - created
     */
    public function register()
    {
        $this->request->allowMethod('post');
        $this->loadComponent('HasherHandler');
        $this->loadComponent('UserHandler');
        $requestData = $this->request->getData();
        $requestData['activation_key'] = $this->HasherHandler->generateRand();
        $user = $this->UserModel->addUser($requestData);
        try {
            $this->UserHandler->sendActivationMail(
                $this->request->getData(),
                Router::url('/api/auth/activate', true)
            );
        } catch (\Exception $e) {
            throw new InternalErrorException(__($e->getMessage()));
        }

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
     * @return void
     */
    public function activate()
    {
        // $this->request->allowMethod('get');
        // $key = $this->request->getParam('key');
        // if ( ! $this->UserModel->activateAccount($key)) {
        //     return $this->redirect('/activation-error');
        // }
        // return $this->redirect('/');
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
        $this->APIResponse->responseOk();
    }
}
