<?php
namespace App\Controller\Posts;

use App\Controller\AppController;
use App\Exception\PostNotFoundException;

/**
 * Comments Controller
 *
 * @property \App\Model\Table\CommentsTable $Comments
 *
 * @method \App\Model\Entitgty\Comment[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CommentsController extends AppController
{
    public $paginate = [
        'limit' => 10,
        'order' => [
            'Posts.created' => 'desc'
        ]
    ];

    public function isAuthorized()
    {
        if ( ! in_array($this->request->getParam('action'), ['delete', 'edit'])) {
            return true;
        }

        if ( ! parent::isOwnedBy($this->Comments, $this->Auth->user('id'), 'commentId')) {
            return false;
        }

        return parent::isAuthorized();
    }
    
    /**
     * [GET]
     * [PRIVATE]
     * 
     * Fetches Comments of the given post id
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $this->request->allowMethod('GET');
        $postId = $this->request->getParam('id');
        if ( ! $this->Comments->Posts->exists(['id' => $postId])) {
            throw new PostNotFoundException($postId);
        }
        $comments = $this->paginate($this->Comments->fetchByPost($postId));
        $this->APIResponse->responseData($comments);
    }

    /**
     * [POST]
     * [PRIVATE]
     * 
     * Adds a comment to a post
     *
     * @return \Cake\Http\Response|null
     * 
     * @throws \App\Exception\PostNotFoundException
     * @throws \App\Exception\ValidationErrorsException
     * @throws \Cake\Http\Exception\InternalErrorException
     */
    public function add()
    {
        $this->request->allowMethod('post');
        $postId = $this->request->getParam('id');
        $this->Comments->Posts->isExistOrThrow($postId);

        $requestData = $this->request->getData();
        $requestData['user_id'] = $this->Auth->user('id');
        $requestData['post_id'] = $postId;

        $comment = $this->Comments->addComment($requestData);

        return $this->APIResponse->responseCreated($comment);
    }

    /**
     * [PUT]
     * [PRIVATE]
     * 
     * Edits a comment to a post
     *
     * @return \Cake\Http\Response|null
     * 
     * @throws \App\Exception\PostNotFoundException
     * @throws \App\Exception\CommentNotFoundException
     * @throws \App\Exception\ValidationErrorsException
     * @throws \Cake\Http\Exception\InternalErrorException
     */
    public function edit()
    {
        $this->request->allowMethod('put');
        $postId = $this->request->getParam('id');
        $commentId = $this->request->getParam('commentId');
        $this->Comments->Posts->isExistOrThrow($postId);
        $this->Comments->isExistOrThrow($commentId);

        $requestData = $this->request->getData();
        $requestData['post_id'] = $postId;

        $comment = $this->Comments->editComment($commentId, $requestData);

        return $this->APIResponse->responseCreated($comment);
    }

    /**
     * [DELETE]
     * [PRIVATE]
     * 
     * Deletes a post
     * post should be owned by the current user
     *
     * @return \Cake\Http\Response|null
     * 
     * @throws \App\Exception\PostNotFoundException
     * @throws \App\Exception\CommentNotFoundException
     * @throws \App\Exception\ValidationErrorsException
     * @throws \Cake\Http\Exception\InternalErrorException
     */
    public function delete()
    {
        $this->request->allowMethod('put');
        $postId = $this->request->getParam('id');
        $commentId = $this->request->getParam('commentId');
        $this->Comments->Posts->isExistOrThrow($postId);
        $this->Comments->isExistOrThrow($commentId);

        $comment = $this->Comments->deleteComment($commentId);

        return $this->APIResponse->responseOk();
    }
}
