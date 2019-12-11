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
     * Add method
     *
     * @return \Cake\Http\Response
     */
    public function add()
    {
        $this->request->allowMethod('post');
    }
}
