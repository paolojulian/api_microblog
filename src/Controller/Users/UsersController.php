<?php
namespace App\Controller\Users;

use App\Controller\AppController;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        // $this->Auth->allow(['view']);
    }

    /**
     * [GET]
     * [PRIVATE]
     * View method
     *
     * @return \Cake\Http\Response|null
     * 
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view()
    {
        $this->request->allowMethod('get');
        $username = $this->request->getParam('username');
        $user = $this->Users->fetchUserProfile($username);
        $this->APIResponse->responseData($user);
    }

    /**
     * [POST]
     * [PRIVATE]
     * 
     * Follow a user
     *
     * @return \Cake\Http\Response|null
     * 
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function follow()
    {
        $this->request->allowMethod('post');
        $username = $this->request->getParam('username');
        $user = $this->Users->fetchUserProfile($username);
        $this->APIResponse->responseData($user);
    }


    /**
     * [GET]
     * [PRIVATE]
     * 
     * Fetches posts of given user
     * 
     * @return \Cake\Http\Response
     */
    public function fetchPosts()
    {
        $this->request->allowMethod('get');
        $page = $this->request->getQuery('page', 1);
        $username = $this->request->getParam('username');
        $userId = $this->Users->fetchByUsername($username)
            ->select(['id'])
            ->first()
            ->id;
        $posts = $this->Users->Posts->fetchPostsOfUser($userId, $page);

        return $this->APIResponse->responseData($posts);
    }
}
