<?php
namespace App\Controller\Posts;

use App\Controller\AppController;
use App\Exception\PostNotFoundException;

/**
 * Likes Controller
 *
 * @property \App\Model\Table\LikesTable $Likes
 *
 * @method \App\Model\Entity\Like[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class LikesController extends AppController
{

    /**
     * [POST]
     * [PRIVATE]
     * 
     * Toggle likes a Post
     * 
     * @return int - number of likes of the post
     * 
     * @throws \App\Exception\PostNotFoundException
     */
    public function toggle()
    {
        $this->request->allowMethod('post');
        $postId = (int) $this->request->getParam('id');
        $userId = (int) $this->Auth->user('id');
        if ( ! $this->Likes->Posts->exists(['id' => $postId])) {
            throw new PostNotFoundException($postId);
        }
        $this->Likes->toggleLike($userId, $postId);

        return $this->APIResponse->responseOk();
    }
}
