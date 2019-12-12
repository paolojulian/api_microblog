<?php
namespace App\Controller\Posts;

use App\Controller\AppController;
use App\Exception\PostNotFoundException;

/**
 * Posts Controller
 *
 *
 * @method \App\Model\Entity\Post[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PostsController extends AppController
{
    public function isAuthorized()
    {
        if ( ! in_array($this->request->getParam('action'), ['delete', 'edit'])) {
            return true;
        }

        if ( ! parent::isOwnedBy($this->Posts, $this->Auth->user('id'))) {
            return false;
        }

        return parent::isAuthorized();
    }

    /**
     * [GET]
     * [PRIVATE]
     * 
     * Fetches data for home page
     * 
     * @return \Cake\Http\Response
     */
    public function index()
    {
        $this->request->allowMethod('get');
        $page = $this->request->getQuery('page', 1);
        $userId = $this->Auth->user('id');
        $posts = $this->Posts->fetchPostsForLanding($userId, $page);

        return $this->APIResponse->responseData($posts);
    }


    /**
     * [POST]
     * [PRIVATE]
     * 
     * Adds a post entity
     * 
     * @return \Cake\Http\Response
     * 
     * @throws \App\Exception\ValidationErrorsException
     * @throws \Cake\Http\Exception\InternalErrorException
     */
    public function add()
    {
        $this->request->allowMethod('post');
        $this->loadComponent('PostHandler');
        $requestData = $this->request->getData();
        $requestData['user_id'] = $this->Auth->user('id');
        if (isset($requestData['img'])) {
            // Upload img to webroot/img/posts and take the path
            $requestData['img_path'] = $this->PostHandler->uploadImage($requestData['img']);
        }
        $post = $this->Posts->addPost($requestData);
        return $this->APIResponse->responseCreated($post);
    }

    /**
     * [PUT, POST]
     * [PRIVATE]
     * 
     * TODO investigate put cannot get data from multipart-formdata 
     * Edits a post entity
     * Set img_path to empty if you want to clear img
     * 
     * @return \Cake\Http\Response
     * 
     * @throws \App\Exception\ValidationErrorsException
     * @throws \App\Exception\UserForbiddenException - Can only edit owned posts
     * @throws \App\Exception\PostNotFoundException
     * @throws \Cake\Http\Exception\InternalErrorException
     */
    public function edit()
    {
        $this->request->allowMethod(['post', 'put']);
        $this->loadComponent('PostHandler');
        $postId = (int) $this->request->getParam('id');

        $requestData = $this->request->getData();
        $requestData['user_id'] = $this->Auth->user('id');
        if (isset($requestData['img']) && !!$requestData['img']) {
            // Upload img to webroot/img/posts and take the path
            $requestData['img_path'] = $this->PostHandler->uploadImage($requestData['img']);
        }
        $post = $this->Posts->updatePost($postId, $requestData);
        return $this->APIResponse->responseData($post);
    }

    /**
     * [DELETE]
     * [PRIVATE]
     * 
     * Deletes a Post
     * 
     * @throws \App\Exception\PostNotFoundException
     * @throws \Cake\Http\Exception\InternalErrorException
     * 
     * @return status 204
     */
    public function delete()
    {
        $this->request->allowMethod('delete');
        $this->Posts->deletePost((int)$this->request->getParam('id'));
        return $this->APIResponse->responseOk();
    }

    /**
     * [POST]
     * [PRIVATE]
     * 
     * Shares a post
     * 
     * @return object - Post Entity
     * 
     * @throws \App\Exception\ValidationErrorsException
     * @throws \App\Exception\PostNotFoundException
     * @throws \Cake\Http\Exception\InternalErrorException
     */
    public function share()
    {
        $this->request->allowMethod('post');
        $postId = (int)$this->request->getParam('id');
        $requestData = $this->request->getData();
        $requestData['user_id'] = (int)$this->Auth->user('id');

        // Save data
        $post = $this->Posts->sharePost($postId, $requestData);

        return $this->APIResponse->responseCreated($post);
    }
}
