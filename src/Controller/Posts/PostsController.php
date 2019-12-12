<?php
namespace App\Controller\Posts;

use App\Controller\AppController;

/**
 * Posts Controller
 *
 *
 * @method \App\Model\Entity\Post[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PostsController extends AppController
{
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
     * [PUT]
     * [PRIVATE]
     * 
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
        // TODO investigate put cannot get data from multipart-formdata
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
}
